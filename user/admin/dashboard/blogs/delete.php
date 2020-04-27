<?php

    require_once $_SERVER['DOCUMENT_ROOT']."/myblog/vendor/autoload.php";

    use \Classes\Blog\CategoryRepository;
    use \Classes\ErrorMessage\ErrorMessage;
    use \Classes\Blog\BlogService;
    use \Classes\Validation\Input;
    use \Classes\Util\Redirect;


    $objectBlogService = new BlogService();
    $objErrMessage = new ErrorMessage();
    $objCategoryRepository = new CategoryRepository();

    if (Input::exists('get')) {
        $blogId = Input::get('id');
        $objectBlogService->deleteBlog($blogId);
    }

    Redirect::to(base_url("user/admin/dashboard/blogs"));

