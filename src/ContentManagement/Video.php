<?php

namespace PolosHermanoz\YoutubeStudio\ContentManagement;

class Video
{
    public $id;
    public $title;
    public $description;
    public $filePath;    // Lokasi file video disimpan
    public $duration;    // Durasi dalam detik
    public $musicId;     // ID musik latar (jika ada)
    public $textOverlay; // Teks di atas video (jika ada)
    public $isPublished = false; // Status tayang, defaultnya belum tayang

    public function __construct(string $filePath, int $duration)
    {
        $this->filePath = $filePath;
        $this->duration = $duration;
        // Memberikan ID acak antara 100-999 sebagai simulasi database
        $this->id = rand(100, 999);
    }
}