<?php

namespace PolosHermanoz\YoutubeStudio\StreamingManagement;

class Channel
{
    private $isEligibleForLive;

    public function __construct(bool $isEligible)
    {
        $this->isEligibleForLive = $isEligible;
    }

    public function isEligible(): bool
    {
        return $this->isEligibleForLive;
    }
}