<?php

namespace PolosHermanoz\YoutubeStudio;

class RevenueManager
{
    private float $adsRevenue;
    private float $membershipRevenue;

    public function __construct(float $adsRevenue, float $membershipRevenue)
    {
        $this->adsRevenue = $adsRevenue;
        $this->membershipRevenue = $membershipRevenue;
    }

    public function getTotalRevenue(): float
    {
        return $this->adsRevenue + $this->membershipRevenue;
    }

    public function getRevenueBreakdown(): array
    {
        return [
            'ads' => $this->adsRevenue,
            'membership' => $this->membershipRevenue
        ];
    }

    public function verifyRevenueData(float $expectedAds, float $expectedMembership): bool
    {
        return abs($this->adsRevenue - $expectedAds) < 0.01 &&
               abs($this->membershipRevenue - $expectedMembership) < 0.01;
    }
}
