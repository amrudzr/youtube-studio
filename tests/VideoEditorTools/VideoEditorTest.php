<?php

namespace PolosHermanoz\YoutubeStudio\Tests\VideoEditorTools;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\AudioLibrary;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\Video;
use PolosHermanoz\YoutubeStudio\VideoEditorTools\VideoEditor;

class VideoEditorTest extends TestCase
{
    private VideoEditor $editor;
    private Video $video;

    protected function setUp(): void
    {
        $library = new AudioLibrary();
        $this->editor = new VideoEditor($library);
        $this->video = new Video('Sample Video');
    }

    public function testTrimVideo(): void
    {
        $result = $this->editor->trimVideo($this->video);
        $this->assertTrue($result);

        $status = $this->video->getStatus();
        $this->assertTrue($status['trimmed']);
    }

    public function testBlurVideo(): void
    {
        $result = $this->editor->blurVideo($this->video);
        $this->assertTrue($result);

        $status = $this->video->getStatus();
        $this->assertTrue($status['blurred']);
    }

    public function testAddAudioWithValidTrack(): void
    {
        $result = $this->editor->addAudio($this->video, 'Soft Piano');
        $this->assertTrue($result);

        $status = $this->video->getStatus();
        $this->assertEquals('Soft Piano', $status['audio']);
    }

    public function testAddAudioWithInvalidTrackThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->editor->addAudio($this->video, 'Metal Madness');
    }

    public function testFullEditFlow(): void
    {
        $this->editor->trimVideo($this->video);
        $this->editor->blurVideo($this->video);
        $this->editor->addAudio($this->video, 'Calm Waves');

        $status = $this->video->getStatus();

        $this->assertTrue($status['trimmed']);
        $this->assertTrue($status['blurred']);
        $this->assertEquals('Calm Waves', $status['audio']);
    }
}
