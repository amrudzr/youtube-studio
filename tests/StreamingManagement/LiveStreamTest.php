<?php

namespace PolosHermanoz\YoutubeStudio\Tests\StremingManagement;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\StreamingManagement\LiveStream;
use PolosHermanoz\YoutubeStudio\StreamingManagement\User;
use PolosHermanoz\YoutubeStudio\StreamingManagement\Channel;
use Exception;

class LiveStreamTest extends TestCase
{
    private $editorUser;
    private $eligibleChannel;

    /**
     * Menyiapkan objek yang dibutuhkan sebelum setiap test dijalankan.
     */
    protected function setUp(): void
    {
        // Sesuai Precondition: User login dan Kanal memenuhi syarat
        $this->editorUser = new User('Editor'); // Sesuai langkah 1
        $this->eligibleChannel = new Channel(true);
    }
    
    /**
     * @test
     * Menguji keseluruhan alur kerja dari penjadwalan hingga laporan pasca-siaran.
     * Nama fungsi ini mencerminkan judul Test Case.
     */
    public function testScheduleAndBroadcastWorkflow(): void
    {
        // Langkah 1 & 2: Login dan masuk menu (diwakili oleh pembuatan objek LiveStream)
        $liveStream = new LiveStream($this->editorUser, $this->eligibleChannel);
        $this->assertInstanceOf(LiveStream::class, $liveStream);

        // Langkah 3, 4, 5, & 6: Menjadwalkan siaran dengan data lengkap
        $streamDetails = [
            'title'       => 'Live Coding Session PHPUnit',
            'description' => 'Membangun unit test dari test case.',
            'category'    => 'Education',
            'time'        => '2025-10-17 14:00:00',
            'thumbnail'   => '/thumbnails/live-coding.jpg',
        ];
        
        $isScheduled = $liveStream->scheduleStream($streamDetails);

        // Validasi Expected Result untuk tahap penjadwalan
        $this->assertTrue($isScheduled, "Gagal menyimpan jadwal siaran.");
        $this->assertTrue($liveStream->isScheduled(), "Status siaran seharusnya 'terjadwal'.");
        $this->assertEquals($streamDetails['title'], $liveStream->title, "Judul siaran tidak sesuai.");
        
        // Langkah 7: Memulai siaran pada waktu yang ditentukan
        $isStarted = $liveStream->startStream();

        // Validasi Expected Result untuk tahap memulai siaran
        $this->assertTrue($isStarted, "Gagal memulai siaran.");
        $this->assertTrue($liveStream->isLive(), "Status siaran seharusnya 'sedang berlangsung'.");

        // Langkah 8: Memantau analitik real-time
        $analytics = $liveStream->getRealTimeAnalytics();
        
        // Validasi Expected Result untuk analitik
        $this->assertIsArray($analytics, "Analitik harus berupa array.");
        $this->assertNotEmpty($analytics, "Analitik real-time tidak boleh kosong.");
        $this->assertArrayHasKey('viewers', $analytics, "Data 'viewers' tidak ditemukan.");
        $this->assertArrayHasKey('likes', $analytics, "Data 'likes' tidak ditemukan.");
        $this->assertArrayHasKey('chat_count', $analytics, "Data 'chat_count' tidak ditemukan.");
        $this->assertArrayHasKey('duration_seconds', $analytics, "Data 'durasi' tidak ditemukan.");

        // Langkah 9: Mengakhiri siaran
        $liveStream->endStream();
        
        // Validasi Expected Result untuk tahap akhir siaran
        $this->assertFalse($liveStream->isLive(), "Status siaran seharusnya sudah 'berakhir'.");
        
        // Langkah 10: Periksa laporan analitik pasca-siaran
        $report = $liveStream->getPostStreamReport();

        // Validasi Expected Result untuk laporan
        $this->assertIsArray($report, "Laporan harus berupa array.");
        $this->assertArrayHasKey('total_viewers', $report, "Laporan harus memiliki total penonton.");
        $this->assertArrayHasKey('final_duration', $report, "Laporan harus memiliki durasi final.");
    }
    
    /**
     * @test
     * Menguji kasus gagal jika kanal tidak memenuhi syarat.
     */
    public function testShouldThrowExceptionIfChannelIsNotEligible(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Channel is not eligible for live streaming.");
        
        $ineligibleChannel = new Channel(false);
        new LiveStream($this->editorUser, $ineligibleChannel);
    }
}   