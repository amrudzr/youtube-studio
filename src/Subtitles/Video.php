<?php

namespace PolosHermanoz\YoutubeStudio\Subtitles;

class Video
{
    /** @var Subtitle[] */
    private array $subtitles = [];

    public function __construct(public readonly string $videoId) {}

    public function addSubtitle(Subtitle $subtitle): void
    {
        $this->subtitles[$subtitle->getLanguageCode()] = $subtitle;
    }

    public function getSubtitle(string $languageCode): ?Subtitle
    {
        return $this->subtitles[$languageCode] ?? null;
    }
}