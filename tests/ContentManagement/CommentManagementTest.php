<?php

namespace PolosHermanoz\YoutubeStudio\Tests\ContentManagement;

use PHPUnit\Framework\TestCase;
// Mengimpor class yang akan dites
use PolosHermanoz\YoutubeStudio\ContentManagement\User;
use PolosHermanoz\YoutubeStudio\ContentManagement\Comment;
use PolosHermanoz\YoutubeStudio\ContentManagement\CommentService;

class CommentManagementTest extends TestCase
{
    private $editorUser;
    private $commentService;

    // setUp() dijalankan otomatis sebelum SETIAP fungsi test
    protected function setUp(): void
    {
        // Precondition: User login sebagai Editor
        $this->editorUser = new User('Editor');
        $this->commentService = new CommentService();
    }

    /**
     * Menguji aksi 'menyetujui' komentar.
     * Skenario: Editor menyetujui komentar yang statusnya 'pending'.
     */
    public function testApproveComment()
    {
        // Setup data dummy
        $comment = new Comment(1, "Video yang bagus!", "Viewer123");
        
        // Assert awal: pastikan status awalnya benar 'pending'
        $this->assertEquals('pending', $comment->status);

        // Action: Jalankan fungsi approve
        $success = $this->commentService->approve($this->editorUser, $comment);

        // Assert akhir: Pastikan fungsi mengembalikan true dan status berubah
        $this->assertTrue($success);
        $this->assertEquals('approved', $comment->status);
    }

    /**
     * Menguji aksi 'membalas' komentar.
     */
    public function testReplyToComment()
    {
        $originalComment = new Comment(2, "Videonya keren!", "SubscriberA");
        $replyText = "Terima kasih!";

        // Action: Jalankan fungsi reply
        $replyComment = $this->commentService->reply($this->editorUser, $originalComment, $replyText);

        // Assert: Cek apakah balasan berhasil dibuat
        $this->assertNotNull($replyComment); // Objek tidak boleh null
        $this->assertEquals($replyText, $replyComment->text); // Teks harus sama
        $this->assertEquals($originalComment->id, $replyComment->replyToId); // ID induk harus cocok
        $this->assertEquals('approved', $replyComment->status); // Balasan admin harus langsung approved
    }

    /**
     * Menguji aksi 'menghapus' komentar.
     */
    public function testDeleteComment()
    {
        $spamComment = new Comment(3, "Komentar spam.", "Spammer01");
        
        // Action: Jalankan fungsi delete
        $success = $this->commentService->delete($this->editorUser, $spamComment);

        // Assert: Status harus berubah jadi 'deleted'
        $this->assertTrue($success);
        $this->assertEquals('deleted', $spamComment->status);
    }
}