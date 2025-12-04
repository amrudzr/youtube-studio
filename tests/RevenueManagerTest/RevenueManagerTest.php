<?php

namespace PolosHermanoz\YoutubeStudio\Tests\RevenueManager;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\RevenueManager\RevenueManager;

class RevenueManagerTest extends TestCase
{
    /** @test */
    public function test_it_can_display_accurate_revenue_data()
    {
        // Setup: kanal sudah dimonetisasi dengan data backend
        $backendAds = 1250.50;
        $backendMembership = 349.50;

        // Buat instance RevenueManager (data dari frontend)
        $manager = new RevenueManager(1250.50, 349.50);

        // Step 1: Hitung total pendapatan
        $this->assertEquals(1600.00, $manager->getTotalRevenue(), 'Total pendapatan tidak sesuai.');

        // Step 2: Pastikan breakdown sesuai
        $breakdown = $manager->getRevenueBreakdown();
        $this->assertEquals($backendAds, $breakdown['ads']);
        $this->assertEquals($backendMembership, $breakdown['membership']);

        // Step 3: Verifikasi keakuratan dengan data backend
        $this->assertTrue(
            $manager->verifyRevenueData($backendAds, $backendMembership),
            'Data pendapatan tidak sesuai dengan backend.'
        );
    }
}
