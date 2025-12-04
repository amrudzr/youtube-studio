<?php

namespace PolosHermanoz\YoutubeStudio\AnalyticsAdvancedFilter;

class AnalyticsFilter
{
    /** @return Video[] */
    public function filterByRegion(array $videos, string $region): array
    {
        return array_values(
            array_filter($videos, fn($video) =>
                $video instanceof Video && $video->getRegion() === $region
            )
        );
    }

    /** @return Video[] */
    public function filterByAgeGroup(array $videos, string $ageGroup): array
    {
        return array_values(
            array_filter($videos, fn($video) =>
                $video instanceof Video && $video->getAgeGroup() === $ageGroup
            )
        );
    }

    public function comparePerformance(Video $v1, Video $v2): string
    {
        $views1 = $v1->getViews();
        $views2 = $v2->getViews();

        if ($views1 === $views2) {
            return "Both videos have equal performance";
        }

        return $views1 > $views2
            ? "{$v1->getTitle()} performs better than {$v2->getTitle()}"
            : "{$v2->getTitle()} performs better than {$v1->getTitle()}";
    }
}