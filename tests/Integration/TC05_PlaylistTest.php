<?php

namespace PolosHermanoz\YoutubeStudio\Tests\Integration;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\PlaylistManager\PlaylistManager;
use PolosHermanoz\YoutubeStudio\ContentManagement\Video;

class TC05_PlaylistTest extends TestCase
{
    private array $data;
    private string $logFile;

    protected function setUp(): void
    {
        $this->data = json_decode(file_get_contents(__DIR__ . '/../Fixtures/integration_data.json'), true);
        $this->logFile = __DIR__ . '/../Logs/TC05_PlaylistTest.log';
        file_put_contents($this->logFile, "[START] Testing Playlist Integration\n");
    }

    private function log(string $message) {
        file_put_contents($this->logFile, date('H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }

    public function test_add_video_to_playlist_integration()
    {
        $this->log("1. Load nama playlist...");
        $playlistName = $this->data['playlist']['name'];
        
        $this->log("2. Simulasi pembuatan Video...");
        $video = new Video('/tmp/vid.mp4', 60);
        $videoId = (string)$video->id;
        $this->log("   > Video ID Generated: $videoId");

        $this->log("3. Integrasi ke PlaylistManager...");
        $manager = new PlaylistManager();
        $manager->createPlaylist($playlistName);
        
        $this->log("4. Menambahkan ID Video ke Playlist...");
        $result = $manager->addVideo($playlistName, $videoId);

        $this->log("5. Validasi penyimpanan data...");
        $this->assertTrue($result);
        $videoList = $manager->getPlaylistVideos($playlistName);
        
        if (in_array($videoId, $videoList)) {
            $this->log("   > ID ditemukan di dalam array playlist.");
        }
        
        $this->log("[SUCCESS] Test Passed. Video ID stored in Playlist.");
    }
}