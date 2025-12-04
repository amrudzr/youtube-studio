<?php

namespace PolosHermanoz\YoutubeStudio\StreamingManagement;

use Exception;

class LiveStream
{
    private $user;
    private $channel;
    
    public $title;
    public $description;
    public $category;
    public $scheduledTime;
    public $thumbnailPath;

    private $isScheduled = false;
    private $isLive = false;
    private $streamStartTime;

    public function __construct(User $user, Channel $channel)
    {
        // Memeriksa Precondition
        if ($user->getRole() !== 'Pengelola' && $user->getRole() !== 'Editor') {
            throw new Exception("User does not have permission.");
        }
        if (!$channel->isEligible()) {
            throw new Exception("Channel is not eligible for live streaming.");
        }

        $this->user = $user;
        $this->channel = $channel;
    }

    public function scheduleStream(array $details): bool
    {
        $this->title = $details['title'] ?? 'Judul Default';
        $this->description = $details['description'] ?? '';
        $this->category = $details['category'] ?? 'General';
        $this->scheduledTime = $details['time'] ?? null;
        $this->thumbnailPath = $details['thumbnail'] ?? null;

        if ($this->title && $this->scheduledTime && $this->thumbnailPath) {
            $this->isScheduled = true;
            return true;
        }
        return false;
    }
    
    public function startStream(): bool
    {
        if (!$this->isScheduled) {
            return false;
        }
        $this->isLive = true;
        $this->streamStartTime = time();
        return true;
    }

    public function getRealTimeAnalytics(): array
    {
        if (!$this->isLive) {
            return [];
        }
        // Simulasi data analitik real-time
        return [
            'viewers' => rand(100, 500),
            'likes' => rand(20, 100),
            'chat_count' => rand(50, 200),
            'duration_seconds' => time() - $this->streamStartTime,
        ];
    }
    
    public function endStream(): void
    {
        $this->isLive = false;
    }

    public function getPostStreamReport(): array
    {
        if ($this->isLive || !$this->streamStartTime) {
             return ['error' => 'Stream has not finished or never started.'];
        }
        // Simulasi laporan pasca-siaran
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