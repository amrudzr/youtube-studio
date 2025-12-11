<?php

require_once __DIR__ . '/vendor/autoload.php';

use PolosHermanoz\YoutubeStudio\ContentManagement\User;
use PolosHermanoz\YoutubeStudio\ContentManagement\ShortsService;
use PolosHermanoz\YoutubeStudio\ContentManagement\CommentService;
use PolosHermanoz\YoutubeStudio\ContentManagement\Comment;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\VideoEditor;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\AudioLibrary;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\Video as EditorVideo;
use PolosHermanoz\YoutubeStudio\StreamingManagement\LiveStream;
use PolosHermanoz\YoutubeStudio\StreamingManagement\Channel;
use PolosHermanoz\YoutubeStudio\PlaylistManager\PlaylistManager;

header('Content-Type: application/json');

// ==========================================
// 1. HELPER FUNCTIONS (Logging & Database)
// ==========================================

function writeLog($message) {
    // Simpan log di folder tests/Logs/api_debug.log
    $logFile = __DIR__ . '/tests/Logs/api_debug.log';
    
    // Pastikan folder ada
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0777, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    // Format: [Waktu] [IP] Pesan
    $logEntry = "[$timestamp] [{$_SERVER['REMOTE_ADDR']}] $message" . PHP_EOL;
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

// FUNGSI YANG HILANG SEBELUMNYA
function saveToDatabase($key, $data) {
    $dbFile = __DIR__ . '/database.json';
    
    // 1. Baca data lama
    if (file_exists($dbFile)) {
        $jsonContent = file_get_contents($dbFile);
        $currentData = json_decode($jsonContent, true);
        
        // Jika file ada tapi kosong atau bukan JSON valid, inisialisasi ulang
        if (!is_array($currentData)) {
            $currentData = ['videos' => [], 'approved_comments' => [], 'playlists' => []];
        }
    } else {
        $currentData = ['videos' => [], 'approved_comments' => [], 'playlists' => []];
    }

    // 2. Tambahkan data baru
    // Pastikan key tujuan ada dalam array, jika tidak, buat array baru
    if (!isset($currentData[$key])) {
        $currentData[$key] = [];
    }
    
    $currentData[$key][] = $data;

    // 3. Simpan kembali ke file
    file_put_contents($dbFile, json_encode($currentData, JSON_PRETTY_PRINT));
}

function sendResponse($status, $message, $data = []) {
    $response = ['status' => $status, 'message' => $message, 'data' => $data];
    echo json_encode($response);
    
    // Log response status
    writeLog("Response Sent: [$status] $message");
    exit;
}

// ==========================================
// 2. MAIN LOGIC
// ==========================================

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_GET['action'] ?? '';

// Log Request Masuk
writeLog("New Request: $method /?action=$action");
if ($method === 'POST') {
    writeLog("Payload: " . json_encode($_POST));
}

try {
    if ($method === 'POST') {
        switch ($action) {
            case 'upload_shorts':
                $role = $_POST['role'] ?? 'Viewer';
                $title = $_POST['title'] ?? '';
                $duration = (int)($_POST['duration'] ?? 0);

                writeLog("Action: Upload Shorts by $role");

                $user = new User($role);
                $service = new ShortsService();
                
                ob_start(); 
                $video = $service->uploadShort($user, '/tmp/demo.mp4', $duration, $title, 'Desc');
                ob_end_clean();

                if ($video) {
                    // Simpan ke database JSON
                    $videoData = [
                        'id' => $video->id,
                        'title' => $video->title,
                        'duration' => $video->duration,
                        'uploaded_by' => $role,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    saveToDatabase('videos', $videoData);

                    sendResponse('success', 'Shorts uploaded successfully', $videoData);
                } else {
                    writeLog("Error: Upload rejected (Permission/Duration)");
                    sendResponse('error', 'Upload failed. Check permissions or duration.');
                }
                break;

            case 'add_audio':
                $trackName = $_POST['track_name'] ?? '';
                writeLog("Action: Add Audio '$trackName'");
                
                $library = new AudioLibrary();
                $editor = new VideoEditor($library);
                $video = new EditorVideo('Vlog Santai');

                try {
                    $editor->addAudio($video, $trackName);
                    sendResponse('success', 'Audio added to video', $video->getStatus());
                } catch (Exception $e) {
                    writeLog("Error: " . $e->getMessage());
                    sendResponse('error', $e->getMessage());
                }
                break;

            case 'start_stream':
                $role = $_POST['role'] ?? 'Viewer';
                $isEligible = filter_var($_POST['is_eligible'] ?? false, FILTER_VALIDATE_BOOLEAN);
                
                writeLog("Action: Start Stream by $role (Eligible: " . ($isEligible ? 'Yes' : 'No') . ")");

                $user = new User($role); 
                $channel = new Channel($isEligible);

                try {
                    $stream = new LiveStream($user, $channel);
                    
                    // 1. Integrasi Logic: Lakukan 'Scheduling' (Otomatis dummy data)
                    // Karena startStream() akan return false jika belum scheduled
                    $stream->scheduleStream([
                        'title' => 'Live Stream API Test',
                        'description' => 'Testing dari API Endpoint',
                        'time' => date('Y-m-d H:i:s'),
                        'thumbnail' => '/img/live_thumb.jpg'
                    ]);

                    // 2. Integrasi Logic: Coba Mulai Stream
                    if ($stream->startStream()) {
                        
                        // 3. SIMPAN KE DATABASE JSON
                        $streamData = [
                            'stream_id' => 'live_' . rand(1000, 9999),
                            'title' => $stream->title,
                            'host' => $role,
                            'started_at' => date('Y-m-d H:i:s'),
                            'status' => 'live'
                        ];
                        saveToDatabase('streams', $streamData);
                        // ---------------------------

                        sendResponse('success', 'Live stream started and saved', $streamData);
                    } else {
                        writeLog("Error: Failed to start stream logic");
                        sendResponse('error', 'Failed to start stream (Scheduling issue?)');
                    }

                } catch (Exception $e) {
                    writeLog("Error: " . $e->getMessage());
                    sendResponse('error', $e->getMessage());
                }
                break;

            case 'approve_comment':
                $role = $_POST['role'] ?? 'Viewer';
                $commentId = (int)($_POST['comment_id'] ?? 1);
                writeLog("Action: Approve Comment #$commentId by $role");

                $user = new User($role);
                $comment = new Comment($commentId, 'Konten bagus', 'Netizen');
                $service = new CommentService();

                if ($service->approve($user, $comment)) {
                    // Simpan ke database JSON
                    $approvalData = [
                        'comment_id' => $commentId,
                        'approved_by' => $role,
                        'status' => 'approved',
                        'timestamp' => date('Y-m-d H:i:s')
                    ];
                    saveToDatabase('approved_comments', $approvalData);

                    sendResponse('success', 'Comment approved', $approvalData);
                } else {
                    writeLog("Error: Approval denied");
                    sendResponse('error', 'Permission denied');
                }
                break;

            case 'add_to_playlist':
                $playlistName = $_POST['playlist_name'] ?? 'My Favorites';
                $videoId = $_POST['video_id'] ?? 'vid_123';
                writeLog("Action: Add Video $videoId to Playlist '$playlistName'");

                $manager = new PlaylistManager();
                $manager->createPlaylist($playlistName);
                
                if ($manager->addVideo($playlistName, $videoId)) {
                    // Simpan ke database JSON
                    $playlistData = [
                        'playlist_name' => $playlistName,
                        'video_id' => $videoId,
                        'added_at' => date('Y-m-d H:i:s')
                    ];
                    saveToDatabase('playlists', $playlistData); // <-- Fungsi ini sekarang sudah ada

                    sendResponse('success', 'Video added to playlist', [
                        'playlist' => $playlistName,
                        'videos' => $manager->getPlaylistVideos($playlistName)
                    ]);
                } else {
                    writeLog("Error: Failed add to playlist");
                    sendResponse('error', 'Failed to add video (maybe duplicate)');
                }
                break;

            default:
                writeLog("Warning: Action '$action' not found");
                sendResponse('error', 'Action not found');
        }
    } else {
        writeLog("Warning: Method $method not allowed");
        sendResponse('error', 'Method not allowed');
    }
} catch (Exception $e) {
    writeLog("CRITICAL ERROR: " . $e->getMessage());
    sendResponse('error', 'Server Error: ' . $e->getMessage());
}