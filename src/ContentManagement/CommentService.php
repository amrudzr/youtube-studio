<?php

namespace PolosHermanoz\YoutubeStudio\ContentManagement;

class CommentService
{
    // Menyetujui komentar agar tampil
    public function approve(User $user, Comment $comment): bool
    {
        // Cek apakah user punya izin kelola komentar
        if (!$user->can('manage_comments')) return false;
        
        $comment->status = 'approved';
        return true;
    }

    // Membalas komentar
    public function reply(User $user, Comment $originalComment, string $replyText): ?Comment
    {
        // Cek izin dulu
        if (!$user->can('manage_comments')) return null;
        
        // Buat objek komentar baru sebagai balasan
        $reply = new Comment(rand(1000, 9999), $replyText, $user->getRole());
        $reply->replyToId = $originalComment->id; // Sambungkan ke komentar asli
        $reply->status = 'approved'; // Balasan dari admin otomatis disetujui
        return $reply;
    }

    // Menghapus komentar (soft delete dengan status 'deleted')
    public function delete(User $user, Comment $comment): bool
    {
        if (!$user->can('manage_comments')) return false;
        $comment->status = 'deleted';
        return true;
    }
}