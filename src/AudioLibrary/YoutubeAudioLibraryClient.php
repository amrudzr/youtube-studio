<?php

namespace PolosHermanoz\YoutubeStudio\AudioLibrary;

class YoutubeAudioLibraryClient
{
    /** @var AudioTrack[] */
    private array $tracksDatabase = [];

    public function __construct()
    {
        // Mengisi "database" palsu kita dengan data uji
        $this->tracksDatabase = [
            new AudioTrack('yt001', 'Morning Stroll', 'Luke Station', 'Cinematic', 'https://example.com/download/yt001'),
            new AudioTrack('yt002', 'Midnight Groove', 'Beatmaster', 'Funk', 'https://example.com/download/yt002'),
            new AudioTrack('yt003', 'Forest Lullaby', 'Nature Sounds', 'Ambient', 'https://example.com/download/yt003'),
            new AudioTrack('sfx01', 'Dog Barking', 'Sound Effects', 'SFX', 'https://example.com/download/sfx01'),
        ];
    }

    /**
     * Mensimulasikan pencarian lagu berdasarkan judul atau artis.
     * @return AudioTrack[]
     */
    public function searchTracks(string $query): array
    {
        if (empty($query)) {
            return [];
        }

        $query = strtolower($query);
        
        return array_filter($this->tracksDatabase, function (AudioTrack $track) use ($query) {
            return str_contains(strtolower($track->title), $query) || str_contains(strtolower($track->artist), $query);
        });
    }
}