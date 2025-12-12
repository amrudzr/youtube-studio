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
// 1. LOGGING FUNCTION
// ==========================================
function writeLog($message) {
    $logFile = __DIR__ . '/tests/Logs/api_debug.log';
    
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0777, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $logEntry = "[$timestamp] [$ip] $message" . PHP_EOL;
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}


// ==========================================
// 2. DATABASE SAVE (JSON + MYSQL)
// ==========================================
function saveToDatabase($key, $data) {

    // ============================
    // A. SIMPAN KE JSON
    // ============================
    $dbFile = __DIR__ . '/database.json';
    
    if (file_exists($dbFile)) {
        $content = json_decode(file_get_contents($dbFile), true);
        if (!is_array($content)) $content = [];
    } else {
        $content = [];
    }

    if (!isset($content[$key])) {
        $content[$key] = [];
    }

    $content[$key][] = $data;

    file_put_contents($dbFile, json_encode($content, JSON_PRETTY_PRINT));



    // ============================
    // B. SIMPAN KE MYSQL
    // ============================
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "youtube_studio";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Table name = key
        $table = $key;

        // Buat tabel otomatis
        $fields = [];
        foreach ($data as $col => $value) {
            $fields[] = "`$col` TEXT";
        }

        $fieldsSql = implode(", ", $fields);

        $pdo->exec("CREATE TABLE IF NOT EXISTS `$table` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            $fieldsSql,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );");

        // Insert Data
        $columns = implode(", ", array_map(fn($c) => "`$c`", array_keys($data)));
        $placeholders = implode(", ", array_map(fn($c) => ":$c", array_keys($data)));

        $stmt = $pdo->prepare("INSERT INTO `$table` ($columns) VALUES ($placeholders)");
        $stmt->execute($data);

    } catch (Exception $e) {
        writeLog("MySQL ERROR: " . $e->getMessage());
    }
}


// ==========================================
// 3. RESPONSE HANDLER
// ==========================================
function sendResponse($status, $message, $data = []) {
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    writeLog("Response Sent: [$status] $message");
    exit;
}


// ==========================================
// 4. MAIN API LOGIC
// ==========================================
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_GET['action'] ?? '';

writeLog("New Request: $method /?action=$action");


try {
    if ($method === 'POST') {

        switch ($action) {

            // ============================
            // UPLOAD SHORT VIDEO
            // ============================
            case 'upload_shorts':
                $role = $_POST['role'] ?? 'Viewer';
                $title = $_POST['title'] ?? '';
                $duration = (int)($_POST['duration'] ?? 0);

                $user = new User($role);
                $service = new ShortsService();

                ob_start();
                $video = $service->uploadShort($user, '/tmp/demo.mp4', $duration, $title, 'Desc');
                ob_end_clean();

                if ($video) {
                    $data = [
                        'video_id' => $video->id,
                        'title'    => $video->title,
                        'duration' => $video->duration,
                        'uploaded_by' => $role,
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    saveToDatabase('videos', $data);
                    sendResponse('success', 'Shorts uploaded', $data);
                }

                sendResponse('error', 'Upload failed');
                break;



            // ============================
            // ADD AUDIO
            // ============================
            case 'add_audio':
                $track = $_POST['track_name'] ?? '';

                $library = new AudioLibrary();
                $editor = new VideoEditor($library);
                $video = new EditorVideo('Vlog');

                try {
                    $editor->addAudio($video, $track);
                    sendResponse('success', 'Audio added', $video->getStatus());
                } catch (Exception $e) {
                    sendResponse('error', $e->getMessage());
                }
                break;



            // ============================
            // START LIVE STREAM
            // ============================
            case 'start_stream':
                $role = $_POST['role'] ?? 'Viewer';
                $eligible = filter_var($_POST['is_eligible'] ?? false, FILTER_VALIDATE_BOOLEAN);

                $user = new User($role);
                $channel = new Channel($eligible);
                $stream = new LiveStream($user, $channel);

                $stream->scheduleStream([
                    'title' => 'Live Test',
                    'description' => 'API Stream',
                    'time' => date('Y-m-d H:i:s'),
                    'thumbnail' => '/img/thumb.jpg'
                ]);

                if ($stream->startStream()) {
                    $data = [
                        'stream_id' => 'live_' . rand(1000, 9999),
                        'title' => $stream->title,
                        'host'  => $role,
                        'status' => 'live',
                        'started_at' => date('Y-m-d H:i:s')
                    ];

                    saveToDatabase('streams', $data);
                    sendResponse('success', 'Live Started', $data);
                }

                sendResponse('error', 'Start failed');
                break;



            // ============================
            // APPROVE COMMENT
            // ============================
            case 'approve_comment':
                $role = $_POST['role'] ?? 'Viewer';
                $commentId = (int)($_POST['comment_id'] ?? 1);

                $user = new User($role);
                $comment = new Comment($commentId, 'Komentar bagus', 'Netizen');
                $service = new CommentService();

                if ($service->approve($user, $comment)) {
                    $data = [
                        'comment_id' => $commentId,
                        'approved_by' => $role,
                        'status' => 'approved',
                        'timestamp' => date('Y-m-d H:i:s')
                    ];

                    saveToDatabase('approved_comments', $data);
                    sendResponse('success', 'Comment Approved', $data);
                }

                sendResponse('error', 'Permission Denied');
                break;



            // ============================
            // ADD TO PLAYLIST
            // ============================
            case 'add_to_playlist':
                $playlist = $_POST['playlist_name'] ?? 'My Playlist';
                $videoId = $_POST['video_id'] ?? 'vid_123';

                $manager = new PlaylistManager();
                $manager->createPlaylist($playlist);
                $manager->addVideo($playlist, $videoId);

                $data = [
                    'playlist_name' => $playlist,
                    'video_id' => $videoId,
                    'added_at' => date('Y-m-d H:i:s')
                ];

                saveToDatabase('playlists', $data);

                sendResponse('success', 'Video added to playlist', $data);
                break;


            default:
                sendResponse('error', 'Unknown action');
        }

    } else {
        sendResponse('error', 'Method Not Allowed');
    }
}
catch (Exception $e) {
    writeLog("ERROR: " . $e->getMessage());
    sendResponse('error', $e->getMessage());
}

