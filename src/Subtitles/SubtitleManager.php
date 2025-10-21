<?php

namespace PolosHermanoz\YoutubeStudio\Subtitles;

class SubtitleManager
{
    /**
     * Mensimulasikan penambahan subtitle dari file upload.
     */
    public function addSubtitleFromFile(Video $video, string $languageCode, string $fileContent): Subtitle
    {
        $subtitle = new Subtitle($languageCode, $fileContent);
        $video->addSubtitle($subtitle);
        
        return $subtitle;
    }

    /**
     * Mensimulasikan penambahan subtitle dari transkrip otomatis.
     */
    public function addSubtitleFromAutoSync(Video $video, string $languageCode, string $transcriptContent): Subtitle
    {
        // Untuk simulasi ini, logikanya sama dengan upload file
        $subtitle = new Subtitle($languageCode, $transcriptContent);
        $video->addSubtitle($subtitle);

        return $subtitle;
    }

    /**
     * Mempublikasikan subtitle yang sudah ada untuk bahasa tertentu.
     */
    public function publishSubtitle(Video $video, string $languageCode): bool
    {
        $subtitle = $video->getSubtitle($languageCode);

        if ($subtitle) {
            $subtitle->publish();
            return true;
        }

        return false;
    }
}