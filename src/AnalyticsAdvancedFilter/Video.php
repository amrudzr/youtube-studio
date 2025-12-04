<?php

namespace PolosHermanoz\YoutubeStudio\AnalyticsAdvancedFilter;

class Video
{
    private string $title;
    private string $region;
    private string $ageGroup;
    private int $views;
    private string $uploadDate;

    public function __construct(
        string $title,
        string $region,
        string $ageGroup,
        int $views,
        string $uploadDate
    ) {
        if ($views < 0) {
            throw new \InvalidArgumentException("Views cannot be negative");
        }

        $this->title = $title;
        $this->region = $region;
        $this->ageGroup = $ageGroup;
        $this->views = $views;
        $this->uploadDate = $uploadDate;
    }

    public function getTitle(): string { return $this->title; }
    public function getRegion(): string { return $this->region; }
    public function getAgeGroup(): string { return $this->ageGroup; }
    public function getViews(): int { return $this->views; }
    public function getUploadDate(): string { return $this->uploadDate; }
}