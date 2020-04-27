<?php
namespace Classes\Blog;


class Comment
{
    private $comment;

    /**
     * Comment constructor.
     * @param $comment
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }

}