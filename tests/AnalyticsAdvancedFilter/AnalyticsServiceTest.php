<?php

namespace PolosHermanoz\YoutubeStudio\Tests\AnalyticsAdvancedFilter;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\AnalyticsAdvancedFilter\AnalyticsFilter;
use PolosHermanoz\YoutubeStudio\AnalyticsAdvancedFilter\AnalyticsService;
use PolosHermanoz\YoutubeStudio\AnalyticsAdvancedFilter\Video;

class AnalyticsServiceTest extends TestCase
{
    private AnalyticsService $service;
    private array $videos;

    protected function setUp(): void
    {
        $filter = new AnalyticsFilter();
        $this->service = new AnalyticsService($filter);

        $this->videos = [
            new Video('Video A', 'Indonesia', '18-24', 5000, '2024-04-01'),
            new Video('Video B', 'Indonesia', '25-34', 3000, '2024-03-12'),
            new Video('Video C', 'USA', '18-24', 7000, '2024-01-05'),
            new Video('Video D', 'Indonesia', '18-24', 9000, '2023-12-10'),
        ];
    }

    public function testFilterByRegionAndAgeGroup(): void
    {
        $result = $this->service->filterAndCompare($this->videos, 'Indonesia', '18-24');

        $this->assertCount(2, $result['filtered']);
        $this->assertEquals(14000, $result['summary']['total_views']);
        $this->assertEquals(7000, $result['summary']['average_views']);
    }

    public function testComparePerformanceBetweenVideos(): void
    {
        $filter = new AnalyticsFilter();
        $result = $filter->comparePerformance($this->videos[0], $this->videos[3]);

        $this->assertStringContainsString('Video D performs better', $result);
    }

    public function testSummaryCalculation(): void
    {
        $summary = $this->service->getSummary($this->videos);
        $this->assertEquals(4, $summary['total_videos']);
        $this->assertEquals(24000, $summary['total_views']);
        $this->assertEquals(6000, $summary['average_views']);
    }
}