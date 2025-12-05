<?php

namespace PolosHermanoz\YoutubeStudio\UserManagement;

class User
{
    private string $email;
    private string $role;

    // Centralized valid roles
    public const VALID_ROLES = ['Owner', 'Manager', 'Editor', 'Viewer'];

    public function __construct(string $email, string $role = 'Viewer')
    {
        $this->email = $email;
        $this->setRole($role);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        if (!in_array($role, self::VALID_ROLES, true)) {
            throw new \InvalidArgumentException("Invalid role: $role");
        }

        $this->role = $role;
    }
}