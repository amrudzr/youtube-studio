<?php

namespace PolosHermanoz\YoutubeStudio\StreamingManagement;

class Channel
{
    private $isEligibleForLive; // Boolean: true jika memenuhi syarat, false jika tidak

    public function __construct(bool $isEligible)
    {
        $this->isEligibleForLive = $isEligible;
    }

    public function isEligible(): bool
    {
        return $this->isEligibleForLive;
    }
}