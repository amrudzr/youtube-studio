<?php

namespace PolosHermanoz\YoutubeStudio\Tests;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\VideoUploader;

class VideoUploaderTest extends TestCase
{
    public function test_upload_valid_file_successfully()
    {
        $uploader = new VideoUploader();
        $result = $uploader->upload("video.mp4");

        $this->assertSame("Video uploaded and published successfully!", $result);
    }

    public function test_upload_invalid_file_format_fails()
    {
        $uploader = new VideoUploader();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid file format");

        $uploader->upload("video.txt");
    }
}
