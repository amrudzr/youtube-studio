<?php
use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\VideoEdit\VideoEdit;

class VideoEditTest extends TestCase
{
    public function testUpdateMetadataSuccessfully()
    {
        $editor = new VideoEdit();

        $video = [
            'title' => 'Old Title',
            'description' => 'Old Description',
            'visibility' => 'Public'
        ];

        $newData = [
            'title' => 'New Title',
            'description' => 'New Description',
            'visibility' => 'Private'
        ];

        $updatedVideo = $editor->updateMetadata($video, $newData);

        $this->assertEquals('New Title', $updatedVideo['title']);
        $this->assertEquals('New Description', $updatedVideo['description']);
        $this->assertEquals('Private', $updatedVideo['visibility']);
        $this->assertEquals('saved', $updatedVideo['status']);
    }

    public function testUpdateMetadataWithMissingFieldThrowsException()
    {
        $editor = new VideoEdit();

        $video = [
            'title' => 'Old Title',
            'description' => 'Old Description',
            'visibility' => 'Public'
        ];

        $newData = [
            'title' => 'New Title',
            'visibility' => 'Private'
            // description sengaja dikosongkan untuk uji error
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Field 'description' tidak boleh kosong");

        $editor->updateMetadata($video, $newData);
    }
}
