<?php

namespace PolosHermanoz\YoutubeStudio\Tests;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\CommunityManager;

class CommunityManagerTest extends TestCase
{
    /** @test */
    public function test_it_can_approve_reply_and_delete_comments()
    {
        $manager = new CommunityManager();

        // Step 1: Tambah komentar baru
        $manager->addComment('UserPublik', 'Video yang bagus!');
        $manager->addComment('UserSpam', 'Komentar spam.');

        // Step 2: Setujui komentar pertama
        $approved = $manager->approveComment('Video yang bagus!');
        $this->assertTrue($approved, 'Komentar seharusnya bisa disetujui.');

        // Step 3: Balas komentar yang disetujui
        $replied = $manager->replyToComment('Video yang bagus!', 'Terima kasih!');
        $this->assertTrue($replied, 'Seharusnya bisa membalas komentar yang disetujui.');

        // Step 4: Hapus komentar spam
        $deleted = $manager->deleteComment('Komentar spam.');
        $this->assertTrue($deleted, 'Komentar spam seharusnya bisa dihapus.');

        // Step 5: Verifikasi hasil akhir
        $approvedComments = $manager->getApprovedComments();
        $this->assertCount(1, $approvedComments);
        $this->assertEquals('Video yang bagus!', $approvedComments[0]['text']);

        $repliedComments = $manager->getRepliedComments();
        $this->assertCount(1, $repliedComments);
        $this->assertEquals('Terima kasih!', $repliedComments[0]['reply']);

        $deletedComments = $manager->getDeletedComments();
        $this->assertContains('Komentar spam.', $deletedComments);
    }
}
