<?php

namespace PolosHermanoz\YoutubeStudio\ContentManagement;

class Comment
{
    public $id;
    public $text;
    public $author;
    public $status = 'pending'; // pending, approved, deleted
    public $replyToId;

    public function __construct(int $id, string $text, string $author)
    {
        $this->id = $id;
        $this->text = $text;
        $this->author = $author;
    }
}