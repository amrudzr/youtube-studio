<?php

namespace PolosHermanoz\YoutubeStudio\Tests\Integration;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\ContentManagement\User;
use PolosHermanoz\YoutubeStudio\ContentManagement\ShortsService;
use PolosHermanoz\YoutubeStudio\ContentManagement\Video;

class TC01_ShortsUploadTest extends TestCase
{
    private array $data;
    private string $logFile;

    protected function setUp(): void
    {
        $this->data = json_decode(file_get_contents(__DIR__ . '/../Fixtures/integration_data.json'), true);
        
        // Setup File Log
        $this->logFile = __DIR__ . '/../Logs/TC01_ShortsUploadTest.log';
        file_put_contents($this->logFile, "[START] Testing Upload Shorts Integration\n");
    }

    private function log(string $message) {
        file_put_contents($this->logFile, date('H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }

    public function test_editor_can_upload_shorts()
    {
        $this->log("1. Membaca data user dari JSON...");
        $role = $this->data['users'][0]['role'];
        $user = new User($role);
        $this->log("   > User terbuat dengan Role: $role");
        
        $this->log("2. Membaca metadata video...");
        $videoData = $this->data['content']['shorts'];
        $this->log("   > Judul: {$videoData['title']}, Durasi: {$videoData['duration']}s");

        $this->log("3. Memanggil ShortsService...");
        $service = new ShortsService();
        
        $this->log("4. Eksekusi uploadShort()...");
        $video = $service->uploadShort(
            $user, 
            $videoData['file_path'], 
            $videoData['duration'], 
            $videoData['title'], 
            $videoData['description']
        );

        $this->log("5. Validasi hasil (Assertions)...");
        $this->assertInstanceOf(Video::class, $video);
        $this->assertEquals($videoData['title'], $video->title);
        
        $this->log("[SUCCESS] Test Passed. Video Object Created.");
    }
}