<?php

namespace PolosHermanoz\YoutubeStudio\StreamingManagement;

class User
{
    private $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}