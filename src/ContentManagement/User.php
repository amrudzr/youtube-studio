<?php

namespace PolosHermanoz\YoutubeStudio\ContentManagement;

class User
{
    private $role; // Menyimpan peran user, misal: 'Editor', 'Viewer', 'Pengelola'

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    // Fungsi utama untuk mengecek izin (Authorization)
    public function can(string $permission): bool
    {
        // Jika role adalah 'Editor', dia punya akses ke upload, edit, publish, dan komen
        if ($this->role === 'Editor' && in_array($permission, ['upload', 'edit', 'publish', 'manage_comments'])) {
            return true;
        }
        // Jika bukan Editor, tolak akses (return false)
        return false;
    }
}