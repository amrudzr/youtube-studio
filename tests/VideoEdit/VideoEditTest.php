<?php

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\AudioLibrary;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\Video;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\VideoEditor;

class VideoEditTest extends TestCase
{
    public function testAudioLibraryReturnsCorrectTracks()
    {
        $library = new AudioLibrary();

        $tracks = $library->getAvailableTracks();

        $this->assertIsArray($tracks);
        $this->assertCount(3, $tracks);
        $this->assertContains('Calm Waves', $tracks);
        $this->assertContains('Uplifting Beat', $tracks);
        $this->assertContains('Soft Piano', $tracks);
    }

    public function testAudioLibraryTrackAvailable()
    {
        $library = new AudioLibrary();
        $this->assertTrue($library->isTrackAvailable('Calm Waves'));
    }

    public function testAudioLibraryTrackUnavailable()
    {
        $library = new AudioLibrary();
        $this->assertFalse($library->isTrackAvailable('Unknown Track'));
    }

    public function testVideoInitialState()
    {
        $video = new Video("My Video");
        $status = $video->getStatus();

        $this->assertEquals("My Video", $status['title']);
        $this->assertFalse($status['trimmed']);
        $this->assertFalse($status['blurred']);
        $this->assertNull($status['audio']);
    }

    public function testVideoTrim()
    {
        $video = new Video("Test");
        $video->trim();

        $this->assertTrue($video->getStatus()['trimmed']);
    }

    public function testVideoBlur()
    {
        $video = new Video("Test");
        $video->blur();

        $this->assertTrue($video->getStatus()['blurred']);
    }

    public function testVideoAddAudio()
    {
        $video = new Video("Test");
        $video->addAudio("Soft Piano");

        $this->assertEquals("Soft Piano", $video->getStatus()['audio']);
    }

    public function testVideoReplaceAudio()
    {
        $video = new Video("Test");
        $video->addAudio("Soft Piano");
        $video->addAudio("Calm Waves");

        $this->assertEquals("Calm Waves", $video->getStatus()['audio']);
    }

    public function testEditorTrimVideoUpdatesState()
    {
        $library = new AudioLibrary();
        $editor  = new VideoEditor($library);
        $video   = new Video("Test");

        $this->assertTrue($editor->trimVideo($video));
        $this->assertTrue($video->getStatus()['trimmed']);
    }

    public function testEditorBlurVideoUpdatesState()
    {
        $library = new AudioLibrary();
        $editor  = new VideoEditor($library);
        $video   = new Video("Test");

        $this->assertTrue($editor->blurVideo($video));
        $this->assertTrue($video->getStatus()['blurred']);
    }

    public function testEditorAddAudioValid()
    {
        $library = new AudioLibrary();
        $editor  = new VideoEditor($library);
        $video   = new Video("Test");

        $this->assertTrue($editor->addAudio($video, "Calm Waves"));
        $this->assertEquals("Calm Waves", $video->getStatus()['audio']);
    }

    public function testEditorAddAudioInvalidThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Audio track not found");

        $library = new AudioLibrary();
        $editor  = new VideoEditor($library);
        $video   = new Video("Test");

        $editor->addAudio($video, "Unknown Track");
    }

    public function testEditorReplacingAudioStillWorks()
    {
        $library = new AudioLibrary();
        $editor  = new VideoEditor($library);
        $video   = new Video("Test");

        $editor->addAudio($video, "Calm Waves");
        $editor->addAudio($video, "Soft Piano");

        $this->assertEquals("Soft Piano", $video->getStatus()['audio']);
    }
}