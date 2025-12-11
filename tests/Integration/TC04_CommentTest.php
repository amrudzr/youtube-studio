<?php

namespace PolosHermanoz\YoutubeStudio\Tests\Integration;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\ContentManagement\CommentService;
use PolosHermanoz\YoutubeStudio\ContentManagement\Comment;
use PolosHermanoz\YoutubeStudio\ContentManagement\User;

class TC04_CommentTest extends TestCase
{
    private array $data;
    private string $logFile;

    protected function setUp(): void
    {
        $this->data = json_decode(file_get_contents(__DIR__ . '/../Fixtures/integration_data.json'), true);
        $this->logFile = __DIR__ . '/../Logs/TC04_CommentTest.log';
        file_put_contents($this->logFile, "[START] Testing Comment Approval Workflow\n");
    }

    private function log(string $message) {
        file_put_contents($this->logFile, date('H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }

    public function test_comment_approval_workflow()
    {
        $this->log("1. Load komentar pending...");
        $commentData = $this->data['content']['comment'];
        $comment = new Comment($commentData['id'], $commentData['text'], $commentData['author']);
        $this->log("   > Status awal: " . $comment->status);

        $this->log("2. Load User Editor...");
        $role = $this->data['users'][0]['role'];
        $user = new User($role);

        $this->log("3. Service melakukan Approval...");
        $service = new CommentService();
        $isApproved = $service->approve($user, $comment);

        $this->log("4. Validasi perubahan status...");
        $this->assertTrue($isApproved);
        $this->assertEquals('approved', $comment->status);
        $this->log("   > Status akhir: " . $comment->status);
        
        $this->log("[SUCCESS] Test Passed. Comment status updated.");
    }
}