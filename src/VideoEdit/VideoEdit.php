<?php
namespace PolosHermanoz\YoutubeStudio\VideoEdit;

class VideoEdit
{
    /**
     * Mengubah dan menyimpan metadata video.
     *
     * @param array $video Metadata awal video (judul, deskripsi, visibilitas, dll)
     * @param array $newData Metadata baru yang ingin diperbarui
     * @return array Metadata video yang telah diperbarui
     */
    public function updateMetadata(array $video, array $newData): array
    {
        // Validasi field penting
        $requiredFields = ['title', 'description', 'visibility'];
        foreach ($requiredFields as $field) {
            if (empty($newData[$field])) {
                throw new \Exception("Field '$field' tidak boleh kosong");
            }
        }

        // Gabungkan data lama dengan data baru
        $updatedVideo = array_merge($video, $newData);

        // Simulasi penyimpanan ke database atau sistem publikasi
        $updatedVideo['status'] = 'saved';

        return $updatedVideo;
    }
}
