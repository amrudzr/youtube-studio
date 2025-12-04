<?php

namespace PolosHermanoz\YoutubeStudio\StreamingManagement;

use Exception;
// PENTING: Mengimpor class User dari folder ContentManagement agar bisa digunakan di sini
use PolosHermanoz\YoutubeStudio\ContentManagement\User;

class LiveStream
{
    private $user;
    private $channel;
    
    // Properti data stream
    public $title;
    public $description;
    public $category;
    public $scheduledTime;
    public $thumbnailPath;

    // Status stream
    private $isScheduled = false;
    private $isLive = false;
    private $streamStartTime;

    public function __construct(User $user, Channel $channel)
    {
        // VALIDASI 1: Cek Role User (Hanya Pengelola atau Editor yang boleh live)
        if ($user->getRole() !== 'Pengelola' && $user->getRole() !== 'Editor') {
            throw new Exception("User does not have permission.");
        }
        // VALIDASI 2: Cek apakah Channel memenuhi syarat (misal: subscribers cukup)
        if (!$channel->isEligible()) {
            throw new Exception("Channel is not eligible for live streaming.");
        }

        $this->user = $user;
        $this->channel = $channel;
    }

    // Menjadwalkan stream baru
    public function scheduleStream(array $details): bool
    {
        // Mengisi data dari array input, atau pakai default jika kosong
        $this->title = $details['title'] ?? 'Judul Default';
        $this->description = $details['description'] ?? '';
        $this->category = $details['category'] ?? 'General';
        $this->scheduledTime = $details['time'] ?? null;
        $this->thumbnailPath = $details['thumbnail'] ?? null;

        // Validasi: Judul, Waktu, dan Thumbnail wajib ada
        if ($this->title && $this->scheduledTime && $this->thumbnailPath) {
            $this->isScheduled = true;
            return true;
        }
        return false;
    }
    
    // Memulai siaran langsung
    public function startStream(): bool
    {
        // Tidak bisa mulai jika belum dijadwalkan
        if (!$this->isScheduled) {
            return false;
        }
        $this->isLive = true;
        $this->streamStartTime = time(); // Catat waktu mulai (timestamp sekarang)
        return true;
    }

    // Mendapatkan data penonton saat ini (Real-time)
    public function getRealTimeAnalytics(): array
    {
        if (!$this->isLive) {
            return []; // Kembalikan array kosong jika tidak sedang live
        }
        // Simulasi angka acak untuk demo
        return [
            'viewers' => rand(100, 500),
            'likes' => rand(20, 100),
            'chat_count' => rand(50, 200),
            'duration_seconds' => time() - $this->streamStartTime, // Hitung durasi berjalan
        ];
    }
    
    // Mengakhiri siaran
    public function endStream(): void
    {
        $this->isLive = false;
    }

    // Laporan akhir setelah stream selesai
    public function getPostStreamReport(): array
    {
        // Cek error: Jika masih live atau stream belum pernah dimulai
        if ($this->isLive || !$this->streamStartTime) {
             return ['error' => 'Stream has not finished or never started.'];
        }
        // Data statis sebagai contoh laporan
        return [
            'total_viewers' => 1200,
            'peak_viewers' => 510,
            'total_likes' => 250,
            'final_duration' => 3600, // 1 jam
        ];
    }

    public function isScheduled(): bool
    {
        return $this->isScheduled;
    }

    public function isLive(): bool
    {
        return $this->isLive;
    }
}