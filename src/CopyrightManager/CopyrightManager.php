<?php

namespace PolosHermanoz\YoutubeStudio\CopyrightManager;

/**
 * Kelas untuk mengelola Klaim Hak Cipta pada Video
 */
class CopyrightManager
{
    private array $claims = [];
    
    // Status Klaim
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_DISPUTED = 'DISPUTED';
    public const STATUS_REMOVED = 'REMOVED';

    public function __construct()
    {
        // Simulasi adanya klaim pada video A123
        $this->claims['v-A123'] = [
            'status' => self::STATUS_ACTIVE,
            'claimant' => 'Musik Label X',
            'claimed_content' => 'Lagu "Melodi Terlarang" (0:30 - 1:15)',
            'monetization_impact' => 'Monetisasi dialihkan ke pemilik hak cipta',
            'available_actions' => ['Dispute', 'Remove Content', 'Accept'],
        ];
    }

    /**
     * Mendapatkan status notifikasi klaim untuk sebuah video.
     */
    public function getClaimNotificationStatus(string $videoId): bool
    {
        return isset($this->claims[$videoId]) && $this->claims[$videoId]['status'] === self::STATUS_ACTIVE;
    }

    /**
     * Mendapatkan detail klaim untuk ditampilkan kepada pengguna.
     */
    public function getClaimDetails(string $videoId): ?array
    {
        return $this->claims[$videoId] ?? null;
    }

    /**
     * Pengguna mengambil tindakan (misalnya, mengajukan sengketa/dispute).
     */
    public function takeAction(string $videoId, string $action): bool
    {
        if (!isset($this->claims[$videoId])) {
            return false;
        }

        $details = $this->claims[$videoId];

        if (in_array($action, $details['available_actions'])) {
            if ($action === 'Dispute') {
                $this->claims[$videoId]['status'] = self::STATUS_DISPUTED;
                $this->claims[$videoId]['available_actions'] = ['Withdraw Dispute'];
                return true;
            }
            
            if ($action === 'Remove Content') {
                $this->claims[$videoId]['status'] = self::STATUS_REMOVED;
                $this->claims[$videoId]['available_actions'] = [];
                return true;
            }
            
            if ($action === 'Accept') {
                $this->claims[$videoId]['available_actions'] = [];
                return true;
            }
        }
        
        return false;
    }
}