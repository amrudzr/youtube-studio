<?php

namespace PolosHermanoz\YoutubeStudio\Tests;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\DashboardMetrics\DashboardMetrics;

class DashboardMetricsTest extends TestCase
{
    /**
     * Menguji apakah class DashboardMetrics dapat mengambil data metrik
     * secara akurat sesuai dengan sumber data.
     *
     * Tes ini mencerminkan verifikasi TC-3, di mana kita membandingkan
     * data yang ditampilkan dengan data yang seharusnya.
     */
    public function test_it_can_fetch_dashboard_metrics_accurately(): void
    {
        // 1. Persiapan (Setup)
        // Inisialisasi class yang akan diuji.
        $dashboard = new DashboardMetrics();

        // Data yang diharapkan (ini bisa dianggap sebagai data dari database/sumber utama).
        $expectedMetrics = [
            'views' => 15720,
            'watchTime' => 850.5,
            'subscribers' => 1250,
        ];

        // 2. Eksekusi (Act)
        // Panggil method yang ingin diuji.
        $actualMetrics = $dashboard->getMetrics();

        // 3. Pengecekan (Assert)
        // Bandingkan hasil aktual dengan hasil yang diharapkan.
        // Ini mensimulasikan langkah ke-5 dari TC-3: "Bandingkan angka tersebut".
        $this->assertSame($expectedMetrics, $actualMetrics, "Data metrik yang ditampilkan tidak sesuai dengan sumber data.");
    }
}
