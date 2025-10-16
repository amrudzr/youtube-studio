<?php

namespace PolosHermanoz\YoutubeStudio;

class CommunityManager
{
    private array $comments = [];
    private array $approved = [];
    private array $replied = [];
    private array $deleted = [];

    public function addComment(string $author, string $text): void
    {
        $this->comments[] = [
            'author' => $author,
            'text' => $text,
            'status' => 'pending'
        ];
    }

    public function approveComment(string $text): bool
    {
        foreach ($this->comments as &$comment) {
            if ($comment['text'] === $text && $comment['status'] === 'pending') {
                $comment['status'] = 'approved';
                $this->approved[] = $comment;
                return true;
            }
        }
        return false;
    }

    public function replyToComment(string $originalText, string $reply): bool
    {
        foreach ($this->approved as $comment) {
            if ($comment['text'] === $originalText) {
                $this->replied[] = [
                    'original' => $originalText,
                    'reply' => $reply
                ];
                return true;
            }
        }
        return false;
    }

    public function deleteComment(string $text): bool
    {
        foreach ($this->comments as $key => $comment) {
            if ($comment['text'] === $text) {
                unset($this->comments[$key]);
                $this->deleted[] = $text;
                return true;
            }
        }
        return false;
    }

    public function getApprovedComments(): array
    {
        return $this->approved;
    }

    public function getRepliedComments(): array
    {
        return $this->replied;
    }

    public function getDeletedComments(): array
    {
        return $this->deleted;
    }
}
