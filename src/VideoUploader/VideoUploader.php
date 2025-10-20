<?php

namespace PolosHermanoz\YoutubeStudio\VideoUploader;

class VideoUploader
{
    public function upload($file)
    {
        // Validasi format file
        $validFormats = ['mp4', 'mov'];
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if (!in_array($extension, $validFormats)) {
            throw new \Exception("Invalid file format");
        }

        // Simulasi encoding dan publikasi berhasil
        return "Video uploaded and published successfully!";
    }
}
