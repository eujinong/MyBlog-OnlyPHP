<?php

namespace Classes\Blog;

use Classes\Database\DB;
use Classes\Database\DbHelper;

class Blog
{
    public $title;
    public $body;
    public $created_at;
    public $rating;
    public $featured_image;
    public $category_id;
    public $active;
    public $tags;
    public $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function addComment(Comment $comment, $user_id, $blog_id)
    {
        return $this->db->insert('comment', [
            'comment' => $comment->getComment(),
            'user_id' => $user_id,
            'blog_id' => $blog_id,
            'created_at' => date("Y-m-d h:i:s"),
            'updated_at' => date("Y-m-d h:i:s")
        ]);
    }

    public function addReply(Reply $reply, $comment_id, $user_id, $blog_id)
    {
        return $this->db->insert('reply', [
            'reply' => $reply->getReply(),
            'user_id' => $user_id,
            'blog_id' => $blog_id,
            'comment_id' => $comment_id,
            'created_at' => date("Y-m-d h:i:s"),
            'updated_at' => date("Y-m-d h:i:s")
        ]);
    }



    public function deleteComment(Comment $comment)
    {

    }

    public function editComment(Comment $comment)
    {

    }

    public function addCategory(Category $category)
    {
        $this->Category = $category;
    }

    public function addPivotBlogTag($blog_id, $tag_id)
    {
        $inserted  = $this->db->insert('blog_has_tag',[
            'tag_id' => $tag_id,
            'blog_id' => $blog_id
        ]);
        return $inserted;
    }

    public function getComments($blogId)
    {
        $result = $this->db->query('
                  SELECT Comment.*, user.name, user.photo_url from comment
                  INNER JOIN user on comment.user_id = user.id
                  WHERE comment.blog_id = '.$blogId.'
                  ORDER BY comment.id DESC
                  ');
        return $result->results();
    }

    public function getReplies($comment_id)
    {
        $result = $this->db->query('
                SELECT Reply.*, user.name, user.photo_url from
                Reply INNER JOIN user on reply.user_id = user.id
                WHERE reply.comment_id = '.$comment_id.'
                ');
        return $result->results();
    }


    public function countComments($blogId)
    {
        $result = $this->db->get('comment', ['blog_id', '=', $blogId]);
        return $result->count();
    }


}