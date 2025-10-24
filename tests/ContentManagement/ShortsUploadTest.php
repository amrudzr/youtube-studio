<?php

namespace PolosHermanoz\YoutubeStudio\Tests\ContentManagement;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\ContentManagement\User;
use PolosHermanoz\YoutubeStudio\ContentManagement\ShortsService;

class ShortsUploadTest extends TestCase
{
    private $editorUser;
    private $shortsService;

    /**
     * Precondition: User login sebagai Editor.
     */
    protected function setUp(): void
    {
        // 1. Login sebagai user dengan role Editor.
        $this->editorUser = new User('Editor');
        $this->shortsService = new ShortsService();
    }

    public function testUploadAndManageShortsVideo()
    {
        // Precondition: File video berdurasi <60 detik tersedia.
        $videoFile = 'path/to/vertical_video.mp4';
        $videoDuration = 59; // < 60 detik
        
        // 2. Masuk ke menu Create → Upload videos.
        // 3. Pilih file video berformat vertikal (< 60 detik).
        // 4. Tambahkan judul dan deskripsi.
        $video = $this->shortsService->uploadShort(
            $this->editorUser,
            $videoFile,
            $videoDuration,
            'Judul Video Shorts Keren',
            'Deskripsi singkat video.'
        );

        $this->assertNotNull($video, "Editor seharusnya bisa mengunggah video.");
        $this->assertEquals('Judul Video Shorts Keren', $video->title);

        // 5. Masuk ke opsi Editor → Tambahkan musik dari library.
        $musicAdded = $this->shortsService->addMusic($this->editorUser, $video, 'music_track_001');
        $this->assertTrue($musicAdded);
        $this->assertEquals('music_track_001', $video->musicId);

        // 6. Tambahkan teks overlay ke dalam video.
        $textAdded = $this->shortsService->addTextOverlay($this->editorUser, $video, 'Teks Overlay Keren');
        $this->assertTrue($textAdded);
        $this->assertEquals('Teks Overlay Keren', $video->textOverlay);

        // 7. Simpan perubahan. (Tersirat dalam method addMusic/addText)
        
        // 8. Publikasikan video Shorts.
        $published = $this->shortsService->publish($this->editorUser, $video);
        $this->assertTrue($published);
        
        // Expected Result (Simulasi dari langkah 9)
        // Memastikan video berhasil dipublikasi dengan semua atribut
        $this->assertTrue($video->isPublished);
        
        // 9. Periksa tampilan di berbagai device (mobile & desktop).
        // Langkah ini biasanya adalah pengujian E2E (End-to-End) menggunakan
        // tools seperti Selenium atau Cypress, sulit disimulasikan di PHPUnit (unit test).
        // Kita asumsikan jika status 'isPublished' true, tampilan akan sesuai.
    }
}