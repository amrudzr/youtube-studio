<?php

namespace PolosHermanoz\YoutubeStudio\Tests\AudioLibrary;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\AudioLibrary\AudioLibraryManager;
use PolosHermanoz\YoutubeStudio\AudioLibrary\YoutubeAudioLibraryClient;

class AudioLibraryManagerTest extends TestCase
{
    private AudioLibraryManager $manager;

    protected function setUp(): void
    {
        // Arrange: Siapkan semua objek yang dibutuhkan untuk setiap tes
        $fakeClient = new YoutubeAudioLibraryClient();
        $this->manager = new AudioLibraryManager($fakeClient);
    }

    /**
     * Tes untuk memastikan fitur pencarian berfungsi.
     * Sesuai Expected Result: "Dapat mencari..."
     */
    public function test_it_can_search_for_music(): void
    {
        // Act: Lakukan pencarian dengan kata kunci "Groove"
        $results = $this->manager->search('Groove');

        // Assert: Pastikan hasilnya sesuai harapan
        $this->assertCount(1, $results); // Harus menemukan 1 lagu
        // array_values untuk mereset key array setelah filter
        $this->assertSame('Midnight Groove', array_values($results)[0]->title);
    }

    /**
     * Tes untuk memastikan fitur pemutaran berfungsi.
     * Sesuai Expected Result: "...memutar..."
     */
    public function test_it_can_simulate_playing_music(): void
    {
        // Arrange: Dapatkan satu lagu untuk diputar
        $trackToPlay = $this->manager->search('Stroll')[0];

        // Act: Putar lagu tersebut
        $playStatus = $this->manager->play($trackToPlay);
        
        // Assert: Pastikan output "pemutaran" sesuai
        $this->assertSame("Now playing: Morning Stroll by Luke Station", $playStatus);
    }

    /**
     * Tes untuk memastikan fitur unduh berfungsi.
     * Sesuai Expected Result: "...dan mengunduh musik"
     */
    public function test_it_can_get_a_download_link(): void
    {
        // Arrange: Dapatkan satu lagu untuk diunduh
        // Bungkus hasil pencarian dengan array_values() untuk mereset key-nya
        $trackToDownload = array_values($this->manager->search('Barking'))[0];

        // Act: "Unduh" lagu tersebut
        $downloadLink = $this->manager->download($trackToDownload);

        // Assert: Pastikan link unduhan yang didapat benar
        $this->assertSame("https://example.com/download/sfx01", $downloadLink);
    }
}