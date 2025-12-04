<?php

namespace PolosHermanoz\YoutubeStudio\Tests\PlaylistManager;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\PlaylistManager\PlaylistManager;

class PlaylistManagerTest extends TestCase
{
    private PlaylistManager $playlistManager;

    protected function setUp(): void
    {
        $this->playlistManager = new PlaylistManager();
    }

    /**
     * Test membuat playlist baru.
     */
    public function test_create_playlist_successfully(): void
    {
        $result = $this->playlistManager->createPlaylist("My Favorites");
        
        $this->assertTrue($result);
        $this->assertTrue($this->playlistManager->playlistExists("My Favorites"));
    }

    /**
     * Test menambahkan video ke playlist.
     */
    public function test_add_video_to_playlist_successfully(): void
    {
        $this->playlistManager->createPlaylist("My Favorites");
        $result = $this->playlistManager->addVideo("My Favorites", "video123");
        
        $this->assertTrue($result);
        $this->assertContains("video123", $this->playlistManager->getPlaylistVideos("My Favorites"));
    }


    /**
     * Test menambahkan video duplikat.
     */
    public function test_add_duplicate_video_returns_false(): void
    {
        $this->playlistManager->createPlaylist("My Favorites");
        $this->playlistManager->addVideo("My Favorites", "video123");
        $result = $this->playlistManager->addVideo("My Favorites", "video123");
        
        $this->assertFalse($result);
    }

    /**
     * Test menghapus video dari playlist.
     */
    public function test_remove_video_from_playlist_successfully(): void
    {
        $this->playlistManager->createPlaylist("My Favorites");
        $this->playlistManager->addVideo("My Favorites", "video123");
        
        $result = $this->playlistManager->removeVideo("My Favorites", "video123");
        
        $this->assertTrue($result);
        $this->assertNotContains("video123", $this->playlistManager->getPlaylistVideos("My Favorites"));
    }

    /**
     * Test mengubah urutan video dalam playlist.
     */
    public function test_reorder_video_in_playlist(): void
    {
        $this->playlistManager->createPlaylist("My Favorites");
        $this->playlistManager->addVideo("My Favorites", "video1");
        $this->playlistManager->addVideo("My Favorites", "video2");
        $this->playlistManager->addVideo("My Favorites", "video3");
        
        $result = $this->playlistManager->reorderVideo("My Favorites", "video1", 2);
        
        $this->assertTrue($result);
        $videos = $this->playlistManager->getPlaylistVideos("My Favorites");
        $this->assertSame(["video2", "video3", "video1"], $videos);
    }

    /**
     * Test mendapatkan daftar playlist.
     */
    public function test_get_all_playlist_names(): void
    {
        $this->playlistManager->createPlaylist("Favorites");
        $this->playlistManager->createPlaylist("Watch Later");
        
        $playlistNames = $this->playlistManager->getAllPlaylistNames();
        
        $this->assertContains("Favorites", $playlistNames);
        $this->assertContains("Watch Later", $playlistNames);
        $this->assertCount(2, $playlistNames);
    }

    /**
     * Test memindahkan video antar playlist.
     */
    public function test_move_video_between_playlists(): void
    {
        $this->playlistManager->createPlaylist("Source");
        $this->playlistManager->createPlaylist("Target");
        $this->playlistManager->addVideo("Source", "video123");
        
        $result = $this->playlistManager->moveVideo("Source", "Target", "video123");
        
        $this->assertTrue($result);
        $this->assertNotContains("video123", $this->playlistManager->getPlaylistVideos("Source"));
        $this->assertContains("video123", $this->playlistManager->getPlaylistVideos("Target"));
    }

    /**
     * Test mendapatkan jumlah video dalam playlist.
     */
    public function test_get_video_count(): void
    {
        $this->playlistManager->createPlaylist("My Favorites");
        $this->playlistManager->addVideo("My Favorites", "video1");
        $this->playlistManager->addVideo("My Favorites", "video2");
        
        $count = $this->playlistManager->getVideoCount("My Favorites");
        
        $this->assertSame(2, $count);
    }
}