<?php

namespace PolosHermanoz\YoutubeStudio\Tests\Integration;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\StreamingManagement\LiveStream;
use PolosHermanoz\YoutubeStudio\StreamingManagement\Channel;
use PolosHermanoz\YoutubeStudio\ContentManagement\User;

class TC03_LiveStreamTest extends TestCase
{
    private array $data;
    private string $logFile;

    protected function setUp(): void
    {
        $this->data = json_decode(file_get_contents(__DIR__ . '/../Fixtures/integration_data.json'), true);
        $this->logFile = __DIR__ . '/../Logs/TC03_LiveStreamTest.log';
        file_put_contents($this->logFile, "[START] Testing Live Stream Validation\n");
    }

    private function log(string $message) {
        file_put_contents($this->logFile, date('H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }

    public function test_livestream_creation_integration()
    {
        $this->log("1. Persiapan Data User & Channel...");
        $role = $this->data['users'][0]['role'];
        $isEligible = $this->data['streaming']['channel_eligible'];
        
        $this->log("   > User Role: $role");
        $this->log("   > Channel Eligible: " . ($isEligible ? 'Yes' : 'No'));

        $user = new User($role);
        $channel = new Channel($isEligible);

        $this->log("2. Mencoba inisialisasi LiveStream...");
        $stream = new LiveStream($user, $channel);
        $this->log("   > Constructor LiveStream berhasil memvalidasi User & Channel.");

        $this->log("3. Validasi Objek...");
        $this->assertInstanceOf(LiveStream::class, $stream);
        
        $this->log("[SUCCESS] Test Passed. LiveStream Object Created.");
    }
}