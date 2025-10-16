<?php

namespace PolosHermanoz\YoutubeStudio\VideoEditorTools;

class AudioLibrary
{
    private array $availableTracks = [
        'Calm Waves',
        'Uplifting Beat',
        'Soft Piano',
    ];

    public function getAvailableTracks(): array
    {
        return $this->availableTracks;
    }

    public function isTrackAvailable(string $track): bool
    {
        return in_array($track, $this->availableTracks, true);
    }
}
