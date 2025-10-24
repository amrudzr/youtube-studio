<?php

namespace PolosHermanoz\YoutubeStudio\ContentManagement;

class CommentService
{
    public function approve(User $user, Comment $comment): bool
    {
        if (!$user->can('manage_comments')) return false;
        $comment->status = 'approved';
        return true;
    }

    public function reply(User $user, Comment $originalComment, string $replyText): ?Comment
    {
        if (!$user->can('manage_comments')) return null;
        
        $reply = new Comment(rand(1000, 9999), $replyText, $user->getRole());
        $reply->replyToId = $originalComment->id;
        $reply->status = 'approved'; // Balasan biasanya langsung disetujui
        return $reply;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if (!$user->can('manage_comments')) return false;
        $comment->status = 'deleted';
        return true;
    }
}