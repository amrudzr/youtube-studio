<?php

namespace PolosHermanoz\YoutubeStudio\Tests\ContentManagement;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\ContentManagement\User;
use PolosHermanoz\YoutubeStudio\ContentManagement\ShortsService;
use PolosHermanoz\YoutubeStudio\ContentManagement\Video; // Tambahan agar class Video dikenali

class ShortsUploadTest extends TestCase
{
    private $editorUser;
    private $shortsService;

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
        $videoDuration = 59; // < 60 detik (Syarat Shorts)
        
        // 2-4. Upload video vertical
        $video = $this->shortsService->uploadShort(
            $this->editorUser,
            $videoFile,
            $videoDuration,
            'Judul Video Shorts Keren',
            'Deskripsi singkat video.'
        );

        // Validasi: Pastikan objek video terbentuk
        $this->assertNotNull($video, "Editor seharusnya bisa mengunggah video.");
        $this->assertEquals('Judul Video Shorts Keren', $video->title);

        // 5. Tambahkan musik
        $musicAdded = $this->shortsService->addMusic($this->editorUser, $video, 'music_track_001');
        $this->assertTrue($musicAdded);
        $this->assertEquals('music_track_001', $video->musicId);

        // 6. Tambahkan teks overlay
        $textAdded = $this->shortsService->addTextOverlay($this->editorUser, $video, 'Teks Overlay Keren');
        $this->assertTrue($textAdded);
        $this->assertEquals('Teks Overlay Keren', $video->textOverlay);

        // 8. Publikasikan video Shorts
        $published = $this->shortsService->publish($this->editorUser, $video);
        $this->assertTrue($published);
        
        // Validasi Akhir: Cek status published
        $this->assertTrue($video->isPublished);
    }
}