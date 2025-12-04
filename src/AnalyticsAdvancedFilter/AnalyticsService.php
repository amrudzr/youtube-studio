<?php

namespace PolosHermanoz\YoutubeStudio\AnalyticsAdvancedFilter;

class AnalyticsService
{
    private AnalyticsFilter $filter;

    public function __construct(AnalyticsFilter $filter)
    {
        $this->filter = $filter;
    }

    /** @return array<string, int|float> */
    public function getSummary(array $videos): array
    {
        $validVideos = array_filter($videos, fn($v) => $v instanceof Video);

        $count = count($validVideos);
        $totalViews = array_sum(array_map(fn(Video $v) => $v->getViews(), $validVideos));

        return [
            'total_videos' => $count,
            'total_views'  => $totalViews,
            'average_views' => $count > 0 ? round($totalViews / $count, 2) : 0
        ];
    }

    /** @return array<string, mixed> */
    public function filterAndCompare(array $videos, string $region, string $ageGroup): array
    {
        $filteredByRegion = $this->filter->filterByRegion($videos, $region);
        $filteredByAge    = $this->filter->filterByAgeGroup($filteredByRegion, $ageGroup);

        return [
            'filtered' => $filteredByAge,
            'summary'  => $this->getSummary($filteredByAge),
        ];
    }
}