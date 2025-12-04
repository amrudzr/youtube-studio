<?php

namespace PolosHermanoz\YoutubeStudio\UserManagement;

class Channel
{
    private array $members = [];
    private string $ownerEmail;

    public function __construct(string $ownerEmail)
    {
        $this->ownerEmail = $ownerEmail;
        $this->members[$ownerEmail] = new User($ownerEmail, 'Owner');
    }

    public function invite(string $email, string $role): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format");
        }

        $validRoles = ['Manager', 'Editor', 'Viewer'];
        if (!in_array($role, $validRoles, true)) {
            throw new \InvalidArgumentException("Invalid role: $role");
        }

        $this->members[$email] = new User($email, $role);
        return true;
    }

    public function changeRole(string $email, string $newRole): bool
    {
        if (!isset($this->members[$email])) {
            throw new \RuntimeException("User not found");
        }

        $this->members[$email]->setRole($newRole);
        return true;
    }

    public function removeAccess(string $email): bool
    {
        if (!isset($this->members[$email])) {
            throw new \RuntimeException("User not found");
        }

        unset($this->members[$email]);
        return true;
    }

    public function getMembers(): array
    {
        return $this->members;
    }
}
