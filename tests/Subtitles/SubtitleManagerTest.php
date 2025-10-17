<?php

namespace PolosHermanoz\YoutubeStudio\Tests\Subtitles;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\Subtitles\SubtitleManager;
use PolosHermanoz\YoutubeStudio\Subtitles\Video;

class SubtitleManagerTest extends TestCase
{
    private SubtitleManager $manager;
    private Video $video;

    protected function setUp(): void
    {
        // Siapkan objek yang akan digunakan di semua tes
        $this->manager = new SubtitleManager();
        $this->video = new Video('video123');
    }

    /**
     * Tes untuk memastikan subtitle bisa ditambahkan dari upload file.
     * Sesuai Expected Result: "Dapat menambahkan subtitle dari hasil upload..."
     */
    public function test_it_can_add_subtitle_from_file_upload(): void
    {
        // Arrange: Siapkan data uji berupa konten file subtitle palsu
        $srtContent = "1\n00:00:01,000 --> 00:00:03,000\nHalo dunia!";
        $languageCode = 'id'; // Bahasa Indonesia

        // Act: Panggil method untuk menambahkan subtitle dari file
        $this->manager->addSubtitleFromFile($this->video, $languageCode, $srtContent);

        // Assert: Pastikan subtitle sudah ditambahkan ke video dengan benar
        $subtitle = $this->video->getSubtitle($languageCode);

        $this->assertNotNull($subtitle);
        $this->assertSame($languageCode, $subtitle->getLanguageCode());
        $this->assertSame($srtContent, $subtitle->getContent());
        $this->assertSame('draft', $subtitle->getStatus(), "Subtitle baru harus berstatus draft.");
    }

    /**
     * Tes untuk memastikan subtitle bisa ditambahkan dari transkrip otomatis.
     * Sesuai Expected Result: "...transkrip otomatis..."
     */
    public function test_it_can_add_subtitle_from_auto_sync(): void
    {
        // Arrange: Siapkan data uji berupa konten transkrip
        $transcriptContent = "Ini adalah transkrip otomatis.";
        $languageCode = 'en'; // Bahasa Inggris

        // Act: Panggil method untuk menambahkan subtitle dari transkrip
        $this->manager->addSubtitleFromAutoSync($this->video, $languageCode, $transcriptContent);

        // Assert: Pastikan subtitle sudah ditambahkan ke video
        $subtitle = $this->video->getSubtitle($languageCode);
        $this->assertNotNull($subtitle);
        $this->assertSame($transcriptContent, $subtitle->getContent());
    }

    /**
     * Tes untuk memastikan subtitle dalam berbagai bahasa bisa dipublikasikan.
     * Sesuai Expected Result: "...dan mempublikasikan subtitle dalam berbagi bahasa"
     */
    public function test_it_can_publish_a_subtitle(): void
    {
        // Arrange: Tambahkan dulu subtitle bahasa Indonesia sebagai draft
        $languageCode = 'id';
        $this->manager->addSubtitleFromFile($this->video, $languageCode, "Konten tes.");

        // Act: Publikasikan subtitle tersebut
        $result = $this->manager->publishSubtitle($this->video, $languageCode);

        // Assert: Pastikan proses publikasi berhasil dan statusnya berubah
        $this->assertTrue($result);
        $subtitle = $this->video->getSubtitle($languageCode);
        $this->assertSame('published', $subtitle->getStatus());
    }
}