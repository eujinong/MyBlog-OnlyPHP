<?php

namespace Classes\Blog;

use Classes\Form\FormFile;
use Classes\Blog\Blog;
use Classes\Validation\Validation;
use Classes\Database\DB;


class BlogRepository
{
    private $validation;
    private $formFile;
    private $db;

    /**
     * BlogRepository constructor.
     */
    public function __construct()
    {
        $this->validation = new Validation();
        $this->formFile = new FormFile();
        $this->db = DB::getInstance();
    }


    public function addBlog(Blog $blog)
    {
         $this->db->insert('blog',[
            'title' => $blog->title,
            'body' => $blog->body,
            'featured_image' => $blog->featured_image,
            'user_id' => 1,
            'category_id' => $blog->category_id,
            'created_at' => date("Y-m-d h:i:s"),
            'updated_at' => date("Y-m-d h:i:s")
        ]);

        return $this->db->lastInsertedId();
    }

    public function updateBlog(Blog $blog, $blogId)
    {
        return $this->db->update('blog', $blogId, [
            'title' => $blog->title,
            'body' => $blog->body,
            'featured_image' => $blog->featured_image,
            'user_id' => 1,
            'category_id' => $blog->category_id,
            'updated_at' => date("Y-m-d h:i:s")
        ]);
    }

    public function updateBlogActivation(Blog $blog, $blogId)
    {
        return $this->db->update('blog', $blogId, [
            'active' => $blog->active,
            'updated_at' => date("Y-m-d h:i:s")
        ]);
    }
    
    public function get($id)
    {
        $objBlog = new Blog();

        $blog = $this->db->query(
            "SELECT blog.*, category.category FROM blog INNER JOIN category on blog.category_id = category.id WHERE blog.id = {$id}"
        );


        if ($blog->count()) {
            $blog = $blog->first();
            $objBlog->title = $blog->title;
            $objBlog->body = $blog->body;
            $objBlog->category_id = $blog->category_id;
            $objBlog->featured_image = $blog->featured_image;

            $tags = $this->db->query(
                "SELECT * FROM tag WHERE id IN (SELECT tag_id from blog_has_tag WHERE blog_id = {$id})"
            );

            if ($tags->count()) {
                $objBlog->tags = $tags->results();
            }

        }

        return $objBlog;
    }

    public function deleteBlog($id)
    {
        return $this->db->delete('blog', ['id', '=', $id]);
    }
    
    public function removeTags($id)
    {
        return  $this->db->delete('blog_has_tag',['blog_id', '=', $id]);
    }

    public function getAllStories($orderby = null, $order = 'ASC')
    {
        return $this->db->all('blog', $orderby, $order);
    }

    public function getStoriesByCategory($category = '')
    {
        $objCategory = $this->db->get('category', ['category', '=', $category])->first();
        return $this->db->get('blog', ['category_id', '=', $objCategory->id])->results();
    }

    public function getStoriesByTag($tag = '')
    {
        $objTag = $this->db->get('tag', ['tag', '=', $tag])->first();
        return $this->db->query('SELECT blog.*, blog_has_tag.tag_id FROM blog INNER JOIN blog_has_tag on blog.id = blog_has_tag.blog_id WHERE tag_id ='.$objTag->id)->results();
    }

    public function getBlogBySearch($search = '')
    {
        return $this->db->query("SELECT * FROM blog WHERE title LIKE '%".$search."%'")->results();
    }

    public function getTagsArrayOfBlog(array $tags)
    {
        $tagsArray = array();
        foreach ($tags as $tag) {
           $tagsArray[] = $tag->id;
        }

        return $tagsArray;
    }
}