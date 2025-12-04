<?php

namespace PolosHermanoz\YoutubeStudio\ContentManagement;

class Comment
{
    public $id;
    public $text;
    public $author;
    public $status = 'pending'; // Status awal 'pending' sebelum disetujui admin
    public $replyToId;          // ID komentar induk jika ini adalah balasan

    public function __construct(int $id, string $text, string $author)
    {
        $this->id = $id;
        $this->text = $text;
        $this->author = $author;
    }
}