<?php

namespace PolosHermanoz\YoutubeStudio\AudioLibrary;

class AudioTrack
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $artist,
        public readonly string $genre,
        public readonly string $downloadUrl
    ) {}
}