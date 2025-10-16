<?php

namespace PolosHermanoz\YoutubeStudio\VideoEditorTools;

class Video
{
    private string $title;
    private bool $hasBlur = false;
    private bool $isTrimmed = false;
    private ?string $addedAudio = null;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function trim(): void
    {
        $this->isTrimmed = true;
    }

    public function blur(): void
    {
        $this->hasBlur = true;
    }

    public function addAudio(string $audioTitle): void
    {
        $this->addedAudio = $audioTitle;
    }

    public function getStatus(): array
    {
        return [
            'title' => $this->title,
            'trimmed' => $this->isTrimmed,
            'blurred' => $this->hasBlur,
            'audio' => $this->addedAudio,
        ];
    }
}
