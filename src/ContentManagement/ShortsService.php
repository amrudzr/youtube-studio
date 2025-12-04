<?php

namespace PolosHermanoz\YoutubeStudio\ContentManagement;

class ShortsService
{
    // Upload video Short
    public function uploadShort(User $user, string $filePath, int $duration, string $title, string $description): ?Video
    {
        // Validasi: User harus punya izin 'upload' DAN durasi harus < 60 detik
        if (!$user->can('upload') || $duration >= 60) {
            return null; // Gagal upload
        }

        // Buat objek Video baru
        $video = new Video($filePath, $duration);
        $video->title = $title;
        $video->description = $description;
        
        // Simulasi output ke layar bahwa proses sedang berjalan
        echo "Uploading short video: $title...\n";
        return $video;
    }

    // Menambahkan musik ke video
    public function addMusic(User $user, Video $video, string $musicId): bool
    {
        if (!$user->can('edit')) return false; // Cek izin edit
        $video->musicId = $musicId;
        return true;
    }

    // Menambahkan teks overlay
    public function addTextOverlay(User $user, Video $video, string $text): bool
    {
        if (!$user->can('edit')) return false;
        $video->textOverlay = $text;
        return true;
    }

    // Menerbitkan video
    public function publish(User $user, Video $video): bool
    {
        if (!$user->can('publish')) return false;
        $video->isPublished = true;
        return true;
    }
}