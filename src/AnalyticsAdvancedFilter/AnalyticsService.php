<?php

namespace PolosHermanoz\YoutubeStudio\AnalyticsAdvancedFilter;

class AnalyticsService
{
    private AnalyticsFilter $filter;

    public function __construct(AnalyticsFilter $filter)
    {
        $this->filter = $filter;
    }

    public function getSummary(array $videos): array
    {
        $totalViews = array_sum(array_map(fn($v) => $v->views, $videos));
        return [
            'total_videos' => count($videos),
            'total_views' => $totalViews,
            'average_views' => count($videos) > 0 ? round($totalViews / count($videos)) : 0,
        ];
    }

    public function filterAndCompare(array $videos, string $region, string $ageGroup): array
    {
        $filteredByRegion = $this->filter->filterByRegion($videos, $region);
        $filteredByAge = $this->filter->filterByAgeGroup($filteredByRegion, $ageGroup);

        return [
            'filtered' => $filteredByAge,
            'summary' => $this->getSummary($filteredByAge),
        ];
    }
}