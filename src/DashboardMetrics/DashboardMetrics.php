<?php

namespace PolosHermanoz\YoutubeStudio\DashboardMetrics;

/**
 * Class DashboardMetrics bertanggung jawab untuk mengambil data metrik utama
 * dari sumber data untuk ditampilkan di dasbor.
 */
class DashboardMetrics
{
    /**
     * Mengambil metrik kunci seperti penayangan, waktu tonton, dan subscriber.
     *
     * Dalam aplikasi nyata, metode ini akan terhubung ke database atau API
     * untuk mendapatkan data yang akurat. Di sini, kita simulasikan
     * data yang dikembalikan.
     *
     * @return array Data metrik yang berisi 'views', 'watchTime', dan 'subscribers'.
     */
    public function getMetrics(): array
    {
        // Simulasi pengambilan data dari database atau sumber data utama.
        return [
            'views' => 15720,
            'watchTime' => 850.5, // dalam jam
            'subscribers' => 1250,
        ];
    }
}
