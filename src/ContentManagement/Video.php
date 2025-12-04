<?php

namespace PolosHermanoz\YoutubeStudio\ContentManagement;

class Video
{
    public $id;
    public $title;
    public $description;
    public $filePath;
    public $duration;
    public $musicId;
    public $textOverlay;
    public $isPublished = false;

    public function __construct(string $filePath, int $duration)
    {
        $this->filePath = $filePath;
        $this->duration = $duration;
        $this->id = rand(100, 999);
    }
}