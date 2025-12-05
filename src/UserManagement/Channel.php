<?php

namespace PolosHermanoz\YoutubeStudio\UserManagement;

class Channel
{
    /** @var User[] keyed by email */
    private array $members = [];

    private string $ownerEmail;

    public function __construct(string $ownerEmail)
    {
        $this->validateEmail($ownerEmail);

        $this->ownerEmail = $ownerEmail;
        $this->members[$ownerEmail] = new User($ownerEmail, 'Owner');
    }

    private function validateEmail(string $email): void
    {
        $pattern = '/^[A-Za-z0-9._%+\-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/';

        if (!preg_match($pattern, $email)) {
            throw new \InvalidArgumentException("Invalid email format");
        }

        [$local, $domain] = explode('@', $email, 2);

        if ($local === '' || $domain === '') {
            throw new \InvalidArgumentException("Invalid email format");
        }

        if (strpos($local, '..') !== false) {
            throw new \InvalidArgumentException("Invalid email local part");
        }

        if (strpos($domain, '..') !== false) {
            throw new \InvalidArgumentException("Invalid domain format");
        }

        if ($domain[0] === '.' || substr($domain, -1) === '.') {
            throw new \InvalidArgumentException("Invalid domain format");
        }

        if ($domain[0] === '-' || substr($domain, -1) === '-') {
            throw new \InvalidArgumentException("Invalid domain format");
        }

        if (strpos($domain, '.') === false) {
            throw new \InvalidArgumentException("Invalid domain format");
        }
    }

    private function validateRole(string $role): void
    {
        if (!in_array($role, User::VALID_ROLES, true)) {
            throw new \InvalidArgumentException("Invalid role: $role");
        }
    }

    private function ensureExists(string $email): void
    {
        if (!isset($this->members[$email])) {
            throw new \RuntimeException("User not found");
        }
    }

    private function ensureNotOwner(string $email): void
    {
        if ($email === $this->ownerEmail) {
            throw new \RuntimeException("Cannot modify owner");
        }
    }

    public function invite(string $email, string $role): bool
    {
        $this->validateEmail($email);
        $this->validateRole($role);

        if (isset($this->members[$email])) {
            throw new \RuntimeException("User already invited");
        }

        $this->members[$email] = new User($email, $role);

        return true;
    }

    public function changeRole(string $email, string $newRole): bool
    {
        $this->ensureExists($email);
        $this->ensureNotOwner($email);
        $this->validateRole($newRole);

        $this->members[$email]->setRole($newRole);

        return true;
    }

    public function removeAccess(string $email): bool
    {
        $this->ensureExists($email);
        $this->ensureNotOwner($email);

        unset($this->members[$email]);

        return true;
    }

    /** @return User[] */
    public function getMembers(): array
    {
        return $this->members;
    }
}