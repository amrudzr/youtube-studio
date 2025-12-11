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
        $this->service = new AnalyticsService(new AnalyticsFilter());

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

    public function testComparePerformance(): void
    {
        $filter = new AnalyticsFilter();

        $result = $filter->comparePerformance(
            $this->videos[0],
            $this->videos[3]
        );

        $this->assertStringContainsString('Video D performs better', $result);
    }

    public function testSummary(): void
    {
        $summary = $this->service->getSummary($this->videos);

        $this->assertEquals(4, $summary['total_videos']);
        $this->assertEquals(24000, $summary['total_views']);
        $this->assertEquals(6000, $summary['average_views']);
    }

    public function testNegativeViewsThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Video('Invalid', 'ID', '18-24', -100, '2024-01-01');
    }

    public function testFilteringSkipsNonVideoObjects(): void
    {
        $mixed = [...$this->videos, "NOT_A_VIDEO", 999];

        $summary = $this->service->getSummary($mixed);

        $this->assertEquals(4, $summary['total_videos']);
    }

    public function testCompareEqualPerformance(): void
    {
        $v1 = new Video('Vid X', 'ID', '18-24', 5000, '2024-01-01');
        $v2 = new Video('Vid Y', 'ID', '18-24', 5000, '2024-01-02');

        $filter = new AnalyticsFilter();

        $this->assertEquals(
            'Both videos have equal performance',
            $filter->comparePerformance($v1, $v2)
        );
    }

    public function testSummaryHandlesZeroViewsCorrectly(): void
    {
        $videos = [
            new Video('Zero Views 1', 'Indonesia', '18-24', 0, '2024-02-01'),
            new Video('Zero Views 2', 'Indonesia', '25-34', 0, '2024-02-02'),
        ];

        $summary = $this->service->getSummary($videos);

        $this->assertEquals(2, $summary['total_videos']);
        $this->assertEquals(0, $summary['total_views']);
        $this->assertEquals(0, $summary['average_views']);
    }

    public function testSummaryForEmptyVideoList(): void
    {
        $summary = $this->service->getSummary([]);

        $this->assertEquals(0, $summary['total_videos']);
        $this->assertEquals(0, $summary['total_views']);
        $this->assertEquals(0, $summary['average_views']);
    }

    public function testComparePerformanceWhenBothVideosHaveZeroViews(): void
    {
        $v1 = new Video('V1', 'ID', '18-24', 0, '2024-01-01');
        $v2 = new Video('V2', 'ID', '18-24', 0, '2024-01-02');

        $filter = new AnalyticsFilter();
        $result = $filter->comparePerformance($v1, $v2);

        $this->assertEquals('Both videos have equal performance', $result);
    }

    public function testComparePerformanceWhenOneVideoHasZeroViews(): void
    {
        $v1 = new Video('No Views', 'ID', '18-24', 0, '2024-01-01');
        $v2 = new Video('Some Views', 'ID', '18-24', 1000, '2024-01-02');

        $filter = new AnalyticsFilter();
        $result = $filter->comparePerformance($v1, $v2);

        $this->assertStringContainsString('Some Views performs better', $result);
    }
}