<?php

namespace PolosHermanoz\YoutubeStudio\AnalyticsAdvancedFilter;

class Video
{
    public string $title;
    public string $region;
    public string $ageGroup;
    public int $views;
    public string $uploadDate;

    public function __construct(string $title, string $region, string $ageGroup, int $views, string $uploadDate)
    {
        $this->title = $title;
        $this->region = $region;
        $this->ageGroup = $ageGroup;
        $this->views = $views;
        $this->uploadDate = $uploadDate;
    }
}