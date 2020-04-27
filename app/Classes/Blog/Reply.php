<?php
namespace Classes\Blog;


class Reply
{
    private $reply;

    /**
     * Reply constructor.
     * @param $reply
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    /**
     * @return mixed
     */
    public function getReply()
    {
        return $this->reply;
    }



}