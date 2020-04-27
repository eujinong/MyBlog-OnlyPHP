<?php

    require_once $_SERVER['DOCUMENT_ROOT']."/myblog/vendor/autoload.php";

    use \Classes\Blog\CategoryRepository;
    use \Classes\ErrorMessage\ErrorMessage;
    use \Classes\Blog\BlogService;
    use \Classes\Member\MembershipService;
    use \Classes\Validation\Input;
    use \Classes\Util\Redirect;


    $objMembershipService = new MembershipService();

    if (Input::exists('get')) {
        $userId = Input::get('id');
        $objMembershipService->deleteBlog($userId);
    }

    Redirect::to(base_url("user/admin/dashboard/blogs"));

