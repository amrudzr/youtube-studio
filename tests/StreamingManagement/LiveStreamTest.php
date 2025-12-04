<?php

namespace PolosHermanoz\YoutubeStudio\Tests\StreamingManagement; // Typo 'Streming' diperbaiki jadi 'Streaming'

use PHPUnit\Framework\TestCase;
use Exception;

// Class dari StreamingManagement
use PolosHermanoz\YoutubeStudio\StreamingManagement\LiveStream;
use PolosHermanoz\YoutubeStudio\StreamingManagement\Channel;

// PENTING: User diambil dari ContentManagement (Sesuai perbaikan struktur sebelumnya)
use PolosHermanoz\YoutubeStudio\ContentManagement\User;

class LiveStreamTest extends TestCase
{
    private $editorUser;
    private $eligibleChannel;

    protected function setUp(): void
    {
        // User 'Editor' dibuat dari class User yang ada di ContentManagement
        $this->editorUser = new User('Editor'); 
        
        // Channel yang memenuhi syarat (isEligible = true)
        $this->eligibleChannel = new Channel(true);
    }
    
    /**
     * @test
     * Menguji alur lengkap: Jadwal -> Mulai -> Analitik -> Selesai -> Laporan
     */
    public function testScheduleAndBroadcastWorkflow(): void
    {
        // 1. Inisialisasi LiveStream
        $liveStream = new LiveStream($this->editorUser, $this->eligibleChannel);
        $this->assertInstanceOf(LiveStream::class, $liveStream);

        // 2. Menjadwalkan siaran
        $streamDetails = [
            'title'       => 'Live Coding Session PHPUnit',
            'description' => 'Membangun unit test dari test case.',
            'category'    => 'Education',
            'time'        => '2025-10-17 14:00:00',
            'thumbnail'   => '/thumbnails/live-coding.jpg',
        ];
        
        $isScheduled = $liveStream->scheduleStream($streamDetails);

        // Validasi Penjadwalan
        $this->assertTrue($isScheduled, "Gagal menyimpan jadwal siaran.");
        $this->assertTrue($liveStream->isScheduled(), "Status siaran seharusnya 'terjadwal'.");
        $this->assertEquals($streamDetails['title'], $liveStream->title);
        
        // 3. Memulai siaran (Start Stream)
        $isStarted = $liveStream->startStream();

        // Validasi Live Status
        $this->assertTrue($isStarted, "Gagal memulai siaran.");
        $this->assertTrue($liveStream->isLive(), "Status siaran seharusnya 'sedang berlangsung'.");

        // 4. Cek Analitik Real-time
        $analytics = $liveStream->getRealTimeAnalytics();
        
        $this->assertIsArray($analytics);
        $this->assertArrayHasKey('viewers', $analytics); // Pastikan ada data viewers
        $this->assertArrayHasKey('duration_seconds', $analytics);

        // 5. Akhiri Siaran
        $liveStream->endStream();
        $this->assertFalse($liveStream->isLive(), "Status siaran seharusnya sudah 'berakhir'.");
        
        // 6. Cek Laporan Akhir (Post Stream Report)
        $report = $liveStream->getPostStreamReport();

        $this->assertIsArray($report);
        $this->assertArrayHasKey('total_viewers', $report);
        $this->assertArrayHasKey('final_duration', $report);
    }
    
    /**
     * @test
     * Menguji skenario gagal: Channel belum memenuhi syarat
     */
    public function testShouldThrowExceptionIfChannelIsNotEligible(): void
    {
        // Kita berharap sistem melempar Error (Exception)
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Channel is not eligible for live streaming.");
        
        // Buat channel yang TIDAK eligible (false)
        $ineligibleChannel = new Channel(false);
        
        // Ini harusnya error dan test dianggap PASS jika error muncul
        new LiveStream($this->editorUser, $ineligibleChannel);
    }
}