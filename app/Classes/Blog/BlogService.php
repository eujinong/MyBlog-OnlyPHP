<?php

namespace Classes\Blog;

use Classes\Database\DB;
use Classes\Blog\Blog;
use Classes\Blog\TagRepository;
use Classes\Blog\BlogRepository;
use Classes\Form\FormFile;
use Classes\Validation\Validation;
use Classes\Database\DbHelper;
class BlogService
{
    private $objValidation;
    private $objFormFile;
    private $db;
    private $objBlog;
    private $objBlogRepository;

    public function __construct()
    {
        $this->objValidation = new Validation();
        $this->objFormFile = new FormFile();
        $this->db = DB::getInstance();
        $this->objBlog = new Blog();
        $this->objBlogRepository = new BlogRepository();

    }

    public function submitBlog(Blog $blog)
    {
        // remove previous thumbnail and upload new one if there thumbnail
        $uploaded_filename = $this->objFormFile->uploadFile($blog->featured_image);
        $blog->featured_image = $uploaded_filename;


        // if file uploaded insert blog into database
        if ($uploaded_filename) {

            $inserted  = $this->objBlogRepository->addBlog($blog);
            if ($inserted) {
                if(!empty($blog->tags)) {
                    foreach ($blog->tags as $temp_tag) {
                        $this->objBlog->addPivotBlogTag($inserted, $temp_tag);
                    }
                }
            }

            return $inserted;
        }

        return false;
    }

    public function updateBlog(Blog $blog, $blogId)
    {
        if (isset($blog->new_featured_image['name'])) {
            if ($blog->new_featured_image['name']) {
                unlink('../../../../uploads/'.$blog->featured_image);
                $uploaded_filename = $this->objFormFile->uploadFile($blog->new_featured_image, '../../../../uploads/');
                $blog->featured_image = $uploaded_filename;
            }
        }

        $updated  = $this->objBlogRepository->updateBlog($blog, $blogId);

        if ($updated) {
            $this->removeRelatedTags($blogId);

            if(!empty($blog->tags)) {
                foreach ($blog->tags as $temp_tag) {
                    $this->objBlog->addPivotBlogTag($blogId, $temp_tag);
                }
            }

            return $updated;
        }
        return false;
    }


    public function updateBlogActivation(Blog $blog, $blogId)
    {
        return $this->objBlogRepository->updateBlogActivation($blog, $blogId);
    }

    public function deleteBlog($id)
    {
        $blog = $this->objBlogRepository->get($id);
        if (file_exists("../../../uploads/" . $blog->featured_image)) {
            unlink('../../../uploads/'.$blog->featured_image);
        }
        $this->objBlogRepository->deleteBlog($id);
        $this->objBlogRepository->removeTags($id);



    }

    public function removeRelatedTags($blog_id)
    {
        $this->objBlogRepository->removeTags($blog_id);
    }

    public function approveBlog()
    {

    }

    public function rejectBlog()
    {

    }

    public function getBlogByCategory()
    {
        
    }

    public function countBlogLikes($blogId)
    {
        return $this->db->get('blog_like', ['blog_id', '=', $blogId])->count();
    }

    public function countCommentLikes($blogId, $commentId)
    {
        return $this->db->query('SELECT * FROM comment_like WHERE blog_id ='.$blogId.' AND comment_id = '.$commentId)->count();
    }

    public function countReplyLikes($blogId, $commentId, $replyId)
    {
        return $this->db->query('SELECT * FROM reply_like WHERE blog_id ='.$blogId.' AND comment_id = '.$commentId.' AND reply_id = '.$replyId)->count();
    }


    public function countBlogLikeByUser($blogId, $userId)
    {
        return $this->db->query('SELECT * FROM blog_like WHERE blog_id ='.$blogId.' AND user_id = '.$userId)->count();

    }

    public function countCommentLikeByUser($blogId, $userId, $commentId)
    {
        return $this->db->query('SELECT * FROM comment_like WHERE blog_id ='.$blogId.' AND user_id = '.$userId. ' AND comment_id = '.$commentId )->count();
    }

    public function countReplyLikeByUser($blogId, $userId, $commentId, $replyId)
    {
        return $this->db->query('SELECT * FROM reply_like WHERE blog_id ='.$blogId.' AND user_id = '.$userId. ' AND comment_id = '.$commentId.' AND reply_id = '.$replyId )->count();
    }

    public function removeBlogLikeOfUser($blogId, $userId)
    {
        return $this->db->query('DELETE FROM blog_like WHERE blog_id = '.$blogId. ' AND user_id = '.$userId);
    }

    public function removeCommentLikeOfUser($blogId, $userId, $commentId)
    {
        return $this->db->query('DELETE FROM comment_like WHERE blog_id = '.$blogId. ' AND user_id = '.$userId.' AND comment_id = '.$commentId);
    }

    public function removeReplyLikeOfUser($blogId, $userId, $commentId, $replyId)
    {
        return $this->db->query('DELETE FROM reply_like WHERE blog_id = '.$blogId. ' AND user_id = '.$userId.' AND comment_id = '.$commentId.' AND reply_id = '.$replyId);
    }





    public function likeBlog($blogId, $userId)
    {
        return $this->db->insert('blog_like', [
            'liked' => 1,
            'user_id' => $userId,
            'blog_id' => $blogId,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s')
        ]);
    }


    public function likeComment($blogId, $userId, $commentId)
    {
        return $this->db->insert('comment_like', [
            'liked' => 1,
            'user_id' => $userId,
            'blog_id' => $blogId,
            'comment_id' => $commentId,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s')
        ]);
    }


    public function likeReply($blogId, $userId, $commentId, $replyId)
    {
        return $this->db->insert('reply_like', [
            'liked' => 1,
            'user_id' => $userId,
            'blog_id' => $blogId,
            'comment_id' => $commentId,
            'reply_id' => $replyId,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s')
        ]);
    }


}