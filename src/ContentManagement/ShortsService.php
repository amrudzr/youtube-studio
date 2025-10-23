<?php

namespace PolosHermanoz\YoutubeStudio\ContentManagement;

class ShortsService
{
    // Ini adalah mock service, dalam aplikasi nyata ini akan
    // berinteraksi dengan database atau API
    
    public function uploadShort(User $user, string $filePath, int $duration, string $title, string $description): ?Video
    {
        if (!$user->can('upload') || $duration >= 60) {
            return null;
        }

        $video = new Video($filePath, $duration);
        $video->title = $title;
        $video->description = $description;
        
        // Simulasikan proses penyimpanan
        echo "Uploading video: $title...\n";
        return $video;
    }

    public function addMusic(User $user, Video $video, string $musicId): bool
    {
        if (!$user->can('edit')) return false;
        $video->musicId = $musicId;
        // Simulasikan penyimpanan
        return true;
    }

    public function addTextOverlay(User $user, Video $video, string $text): bool
    {
        if (!$user->can('edit')) return false;
        $video->textOverlay = $text;
        // Simulasikan penyimpanan
        return true;
    }

    public function publish(User $user, Video $video): bool
    {
        if (!$user->can('publish')) return false;
        $video->isPublished = true;
        // Simulasikan penyimpanan
        return true;
    }
}