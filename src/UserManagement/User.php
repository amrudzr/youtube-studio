<?php

namespace PolosHermanoz\YoutubeStudio\UserManagement;

class User
{
    private string $email;
    private string $role;

    public function __construct(string $email, string $role = 'Viewer')
    {
        $this->email = $email;
        $this->role = $role;
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
        $validRoles = ['Manager', 'Editor', 'Viewer'];
        if (!in_array($role, $validRoles, true)) {
            throw new \InvalidArgumentException("Invalid role: $role");
        }
        $this->role = $role;
    }
}
