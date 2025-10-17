<?php

namespace PolosHermanoz\YoutubeStudio\AnalyticsAdvancedFilter;

class AnalyticsFilter
{
    public function filterByRegion(array $videos, string $region): array
    {
        return array_filter($videos, fn($video) => $video->region === $region);
    }

    public function filterByAgeGroup(array $videos, string $ageGroup): array
    {
        return array_filter($videos, fn($video) => $video->ageGroup === $ageGroup);
    }

    public function comparePerformance(Video $v1, Video $v2): string
    {
        if ($v1->views > $v2->views) {
            return "{$v1->title} performs better than {$v2->title}";
        } elseif ($v1->views < $v2->views) {
            return "{$v2->title} performs better than {$v1->title}";
        }
        return "Both videos have equal performance";
    }
}