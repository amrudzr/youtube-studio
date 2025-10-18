<?php

namespace PolosHermanoz\YoutubeStudio\PlaylistManager;

/**
 * Kelas untuk mengelola Playlist YouTube Studio
 */
class PlaylistManager
{
    private array $playlists = [];

    /**
     * Membuat playlist baru.
     */
    public function createPlaylist(string $name): bool
    {
        if (isset($this->playlists[$name])) {
            return false; // Playlist sudah ada
        }
        $this->playlists[$name] = [];
        return true;
    }

    /**
     * Menambahkan video ke playlist.
     */
    public function addVideo(string $playlistName, string $videoId): bool
    {
        if (!isset($this->playlists[$playlistName])) {
            return false; // Playlist tidak ada
        }
        if (in_array($videoId, $this->playlists[$playlistName])) {
            return false; // Video sudah ada
        }
        $this->playlists[$playlistName][] = $videoId;
        return true;
    }

    /**
     * Menghapus video dari playlist.
     */
    public function removeVideo(string $playlistName, string $videoId): bool
    {
        if (!isset($this->playlists[$playlistName])) {
            return false;
        }
        $index = array_search($videoId, $this->playlists[$playlistName]);
        if ($index !== false) {
            array_splice($this->playlists[$playlistName], $index, 1);
            return true;
        }
        return false; // Video tidak ditemukan
    }

    /**
     * Mengubah urutan video (memindahkan video ke posisi baru).
     * Diasumsikan indeks 0 adalah awal.
     */
    public function reorderVideo(string $playlistName, string $videoId, int $newPosition): bool
    {
        if (!isset($this->playlists[$playlistName])) {
            return false;
        }

        $playlist = &$this->playlists[$playlistName];
        $currentIndex = array_search($videoId, $playlist);

        if ($currentIndex === false) {
            return false; // Video tidak ditemukan
        }

        $video = $playlist[$currentIndex];
        
        // Hapus video dari posisi lama
        array_splice($playlist, $currentIndex, 1);
        
        // Masukkan kembali video ke posisi baru
        // pastikan posisi tidak melebihi batas
        $newPosition = min($newPosition, count($playlist)); 
        array_splice($playlist, $newPosition, 0, [$video]);

        return true;
    }

    /**
     * Mendapatkan daftar video dalam playlist.
     */
    public function getPlaylistVideos(string $playlistName): array
    {
        return $this->playlists[$playlistName] ?? [];
    }

    /**
     * Menghapus playlist.
     */
    public function deletePlaylist(string $playlistName): bool
    {
        if (!isset($this->playlists[$playlistName])) {
            return false;
        }
        unset($this->playlists[$playlistName]);
        return true;
    }

    /**
     * Mendapatkan daftar semua nama playlist.
     */
    public function getAllPlaylistNames(): array
    {
        return array_keys($this->playlists);
    }

    /**
     * Memeriksa apakah playlist exists.
     */
    public function playlistExists(string $playlistName): bool
    {
        return isset($this->playlists[$playlistName]);
    }

    /**
     * Memindahkan video dari satu playlist ke playlist lain.
     */
    public function moveVideo(string $sourcePlaylist, string $targetPlaylist, string $videoId): bool
    {
        if (!isset($this->playlists[$sourcePlaylist]) || !isset($this->playlists[$targetPlaylist])) {
            return false;
        }

        if (!in_array($videoId, $this->playlists[$sourcePlaylist])) {
            return false;
        }

        // Hapus dari source playlist
        $this->removeVideo($sourcePlaylist, $videoId);
        
        // Tambahkan ke target playlist
        return $this->addVideo($targetPlaylist, $videoId);
    }

    /**
     * Mendapatkan jumlah video dalam playlist.
     */
    public function getVideoCount(string $playlistName): int
    {
        return isset($this->playlists[$playlistName]) ? count($this->playlists[$playlistName]) : 0;
    }

    /**
     * Mendapatkan total jumlah playlist.
     */
    public function getTotalPlaylists(): int
    {
        return count($this->playlists);
    }
}