<?php

namespace PolosHermanoz\YoutubeStudio\ContentManagement;

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

    public function can(string $permission): bool
    {
        if ($this->role === 'Editor' && in_array($permission, ['upload', 'edit', 'publish', 'manage_comments'])) {
            return true;
        }
        return false;
    }
}