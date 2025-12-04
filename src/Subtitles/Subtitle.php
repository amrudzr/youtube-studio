<?php

namespace PolosHermanoz\YoutubeStudio\Subtitles;

class Subtitle
{
    private string $status = 'draft'; // Status: draft, published

    public function __construct(
        private readonly string $languageCode,
        private string $content
    ) {}
    
    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
    
    public function publish(): void
    {
        $this->status = 'published';
    }
}