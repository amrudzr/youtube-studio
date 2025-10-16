<?php

namespace PolosHermanoz\YoutubeStudio\VideoEditorTools;

class VideoEditor
{
    private AudioLibrary $audioLibrary;

    public function __construct(AudioLibrary $library)
    {
        $this->audioLibrary = $library;
    }

    public function trimVideo(Video $video): bool
    {
        $video->trim();
        return true;
    }

    public function blurVideo(Video $video): bool
    {
        $video->blur();
        return true;
    }

    public function addAudio(Video $video, string $audioTitle): bool
    {
        if (!$this->audioLibrary->isTrackAvailable($audioTitle)) {
            throw new \InvalidArgumentException("Audio track not found: $audioTitle");
        }

        $video->addAudio($audioTitle);
        return true;
    }
}
