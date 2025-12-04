<?php

namespace PolosHermanoz\YoutubeStudio\Tests\ContentManagement;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\ContentManagement\User;
use PolosHermanoz\YoutubeStudio\ContentManagement\Comment;
use PolosHermanoz\YoutubeStudio\ContentManagement\CommentService;

class CommentManagementTest extends TestCase
{
    private $editorUser;
    private $commentService;

    protected function setUp(): void
    {
        // Precondition: User login sebagai Editor
        $this->editorUser = new User('Editor');
        $this->commentService = new CommentService();
    }

    /**
     * Menguji aksi 'menyetujui' komentar.
     * Data Uji: Komentar untuk disetujui: "Video yang bagus!"
     */
    public function testApproveComment()
    {
        $comment = new Comment(1, "Video yang bagus!", "Viewer123");
        $this->assertEquals('pending', $comment->status);

        $success = $this->commentService->approve($this->editorUser, $comment);

        // Expected Result: Sistem berhasil melakukan aksi (menyetujui)
        $this->assertTrue($success);
        $this->assertEquals('approved', $comment->status);
    }

    /**
     * Menguji aksi 'membalas' komentar.
     * Data Uji: Teks balasan: "Terima kasih!"
     */
    public function testReplyToComment()
    {
        $originalComment = new Comment(2, "Videonya keren!", "SubscriberA");
        $replyText = "Terima kasih!";

        $replyComment = $this->commentService->reply($this->editorUser, $originalComment, $replyText);

        // Expected Result: Sistem berhasil melakukan aksi (membalas)
        $this->assertNotNull($replyComment);
        $this->assertEquals($replyText, $replyComment->text);
        $this->assertEquals($originalComment->id, $replyComment->replyToId);
        $this->assertEquals('approved', $replyComment->status); // Balasan langsung tayang
    }

    /**
     * Menguji aksi 'menghapus' komentar.
     * Data Uji: Komentar untuk dihapus: "Komentar spam.”
     */
    public function testDeleteComment()
    {
        $spamComment = new Comment(3, "Komentar spam.", "Spammer01");
        $this->assertEquals('pending', $spamComment->status); // atau 'approved'

        $success = $this->commentService->delete($this->editorUser, $spamComment);

        // Expected Result: Sistem berhasil melakukan aksi (menghapus)
        $this->assertTrue($success);
        $this->assertEquals('deleted', $spamComment->status);
    }
}