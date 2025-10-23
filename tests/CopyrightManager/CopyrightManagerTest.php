<?php

namespace PolosHermanoz\YoutubeStudio\Tests\CopyrightManager;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\CopyrightManager\CopyrightManager;

class CopyrightManagerTest extends TestCase
{
    private CopyrightManager $manager;
    private string $claimedVideoId = 'v-A123';
    private string $unclaimedVideoId = 'v-B456';

    protected function setUp(): void
    {
        $this->manager = new CopyrightManager();
    }

    /**
     * Test notifikasi klaim muncul untuk video yang diklaim.
     */
    public function test_claim_notification_shows_for_claimed_video(): void
    {
        $this->assertTrue($this->manager->getClaimNotificationStatus($this->claimedVideoId));
    }

    /**
     * Test notifikasi klaim tidak muncul untuk video tanpa klaim.
     */
    public function test_claim_notification_does_not_show_for_unclaimed_video(): void
    {
        $this->assertFalse($this->manager->getClaimNotificationStatus($this->unclaimedVideoId));
    }

    /**
     * Test mendapatkan detail klaim untuk video yang diklaim.
     */
    public function test_get_claim_details_for_claimed_video(): void
    {
        $details = $this->manager->getClaimDetails($this->claimedVideoId);
        
        $this->assertIsArray($details);
        $this->assertArrayHasKey('claimant', $details);
        $this->assertArrayHasKey('claimed_content', $details);
        $this->assertArrayHasKey('available_actions', $details);
        $this->assertStringContainsString('Musik Label X', $details['claimant']);
    }

    /**
     * Test mendapatkan detail klaim mengembalikan null untuk video tanpa klaim.
     */
    public function test_get_claim_details_returns_null_for_unclaimed_video(): void
    {
        $details = $this->manager->getClaimDetails($this->unclaimedVideoId);
        
        $this->assertNull($details);
    }

    /**
     * Test mengambil tindakan dispute pada klaim hak cipta.
     */
    public function test_take_dispute_action_on_copyright_claim(): void
    {
        $result = $this->manager->takeAction($this->claimedVideoId, 'Dispute');
        
        $this->assertTrue($result);
        
        $updatedDetails = $this->manager->getClaimDetails($this->claimedVideoId);
        $this->assertEquals(CopyrightManager::STATUS_DISPUTED, $updatedDetails['status']);
        $this->assertEquals(['Withdraw Dispute'], $updatedDetails['available_actions']);
    }

    /**
     * Test mengambil tindakan remove content pada klaim hak cipta.
     */
    public function test_take_remove_content_action_on_copyright_claim(): void
    {
        $manager = new CopyrightManager();
        $result = $manager->takeAction($this->claimedVideoId, 'Remove Content');
        
        $this->assertTrue($result);
        
        $details = $manager->getClaimDetails($this->claimedVideoId);
        $this->assertEquals(CopyrightManager::STATUS_REMOVED, $details['status']);
        $this->assertEquals([], $details['available_actions']);
    }

    /**
     * Test mengambil tindakan yang tidak valid mengembalikan false.
     */
    public function test_take_invalid_action_returns_false(): void
    {
        $result = $this->manager->takeAction($this->claimedVideoId, 'InvalidAction');
        
        $this->assertFalse($result);
    }

    /**
     * Test mengambil tindakan pada video yang tidak ada mengembalikan false.
     */
    public function test_take_action_on_nonexistent_video_returns_false(): void
    {
        $result = $this->manager->takeAction('nonexistent-video', 'Dispute');
        
        $this->assertFalse($result);
    }
}