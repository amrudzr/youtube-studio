<?php

namespace PolosHermanoz\YoutubeStudio\AudioLibrary;

class AudioLibraryManager
{
    public function __construct(private YoutubeAudioLibraryClient $client)
    {
    }

    /**
     * Melakukan pencarian trek audio.
     */
    public function search(string $query): array
    {
        return $this->client->searchTracks($query);
    }

    /**
     * Mensimulasikan pemutaran trek audio.
     */
    public function play(AudioTrack $track): string
    {
        // Di aplikasi nyata, ini bisa memicu event di UI.
        // Untuk tes, kita kembalikan string yang bisa diverifikasi.
        return "Now playing: {$track->title} by {$track->artist}";
    }

    /**
     * Mensimulasikan pengunduhan dengan mengembalikan URL download.
     */
    public function download(AudioTrack $track): string
    {
        // Di aplikasi nyata, ini bisa memicu download file.
        // Untuk tes, kita cukup kembalikan URL-nya.
        return $track->downloadUrl;
    }
}