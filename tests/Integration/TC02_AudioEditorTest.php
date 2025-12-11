<?php

namespace PolosHermanoz\YoutubeStudio\Tests\Integration;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\VideoEditor;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\AudioLibrary;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\Video;

class TC02_AudioEditorTest extends TestCase
{
    private array $data;
    private string $logFile;

    protected function setUp(): void
    {
        $this->data = json_decode(file_get_contents(__DIR__ . '/../Fixtures/integration_data.json'), true);
        $this->logFile = __DIR__ . '/../Logs/TC02_AudioEditorTest.log';
        file_put_contents($this->logFile, "[START] Testing Audio Editor Integration\n");
    }

    private function log(string $message) {
        file_put_contents($this->logFile, date('H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }

    public function test_editor_uses_audio_library()
    {
        $this->log("1. Mengambil aset audio dari JSON...");
        $trackName = $this->data['assets']['audio_track'];
        $this->log("   > Lagu target: $trackName");

        $this->log("2. Inisialisasi AudioLibrary & Editor...");
        $library = new AudioLibrary();
        $editor = new VideoEditor($library);
        $video = new Video('Vlog Santai');

        $this->log("3. Eksekusi addAudio()...");
        $result = $editor->addAudio($video, $trackName);
        $this->log("   > Editor berkomunikasi dengan Library untuk cek ketersediaan.");

        $this->log("4. Validasi hasil...");
        $this->assertTrue($result);
        $this->assertEquals($trackName, $video->getStatus()['audio']);
        
        $this->log("[SUCCESS] Test Passed. Audio added to Video metadata.");
    }
}