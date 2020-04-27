<?php
require_once $_SERVER['DOCUMENT_ROOT']."/myblog/vendor/autoload.php";

use \Classes\Member\MemberRepository;
use \Classes\Blog\BlogService;
use \Classes\Validation\Input;
$app = new \Slim\App;


$app->post('/like/blog/{blogId}', function ($request, $response, $args) {

    if ($request->hasHeader('Authorization')) {
        $apiKey = $request->getHeader('Authorization')[0];
        $bodyData = $request->getParsedBody();

        $objBlogService = new BlogService();
        $objMemberRepository = new MemberRepository();
        if ($user_id = $objMemberRepository->isValidApiKey($apiKey)) {
            if (count($bodyData)) {
                if ($objBlogService->countBlogLikeByUser($args['blogId'], $bodyData['userId'])) {
                    $objBlogService->removeBlogLikeOfUser($args['blogId'], $bodyData['userId']);

                    $count = $objBlogService->countBlogLikes($args['blogId']);
                    $data = [
                        'error' => false,
                        'likeCount' => $count,
                        'liked' => false
                    ];
                    return $response->withJson($data);

                }
                $objBlogService->likeBlog($args['blogId'], $bodyData['userId']);
                $count = $objBlogService->countBlogLikes($args['blogId']);
                $data = [
                    'error' => false,
                    'likeCount' => $count,
                    'liked' => true
                ];
                return $response->withJson($data);
            }
        }
    }

    $data = [
        'error' => true,
        'message' => 'Failed to like'
    ];
    return $response->withJson($data);

});




$app->post('/like/comment/{commentId}', function ($request, $response, $args) {
    if ($request->hasHeader('Authorization')) {
        $apiKey = $request->getHeader('Authorization')[0];
        $bodyData = $request->getParsedBody();

        $objBlogService = new BlogService();
        $objMemberRepository = new MemberRepository();
        if ($user_id = $objMemberRepository->isValidApiKey($apiKey)) {
            if (count($bodyData)) {
                if ($objBlogService->countCommentLikeByUser($bodyData['blogId'], $bodyData['userId'], $args['commentId'])) {
                    $objBlogService->removeCommentLikeOfUser($bodyData['blogId'], $bodyData['userId'], $args['commentId']);

                    $count = $objBlogService->countCommentLikes($bodyData['blogId'], $args['commentId']);
                    $data = [
                        'error' => false,
                        'likeCount' => $count,
                        'liked' => false
                    ];
                    return $response->withJson($data);

                }
                $objBlogService->likeComment($bodyData['blogId'], $bodyData['userId'], $args['commentId']);
                $count = $objBlogService->countCommentLikes($bodyData['blogId'], $args['commentId']);
                $data = [
                    'error' => false,
                    'likeCount' => $count,
                    'liked' => true
                ];
                return $response->withJson($data);
            }
        }
    }

    $data = [
        'error' => true,
        'message' => 'Failed to like'
    ];
    return $response->withJson($data);

});


$app->post('/like/reply/{replyId}', function ($request, $response, $args) {
    if ($request->hasHeader('Authorization')) {
        $apiKey = $request->getHeader('Authorization')[0];
        $bodyData = $request->getParsedBody();

        $objBlogService = new BlogService();
        $objMemberRepository = new MemberRepository();
        if ($user_id = $objMemberRepository->isValidApiKey($apiKey)) {
            if (count($bodyData)) {
                if ($objBlogService->countReplyLikeByUser($bodyData['blogId'], $bodyData['userId'], $bodyData['commentId'], $args['replyId'])) {
                    $objBlogService->removeReplyLikeOfUser($bodyData['blogId'], $bodyData['userId'], $bodyData['commentId'], $args['replyId']);

                    $count = $objBlogService->countReplyLikes($bodyData['blogId'], $bodyData['commentId'], $args['replyId']);
                    $data = [
                        'error' => false,
                        'likeCount' => $count,
                        'liked' => false
                    ];
                    return $response->withJson($data);

                }
                $objBlogService->likeReply($bodyData['blogId'], $bodyData['userId'], $bodyData['commentId'], $args['replyId']);
                $count = $objBlogService->countReplyLikes($bodyData['blogId'], $bodyData['commentId'], $args['replyId']);
                $data = [
                    'error' => false,
                    'likeCount' => $count,
                    'liked' => true
                ];
                return $response->withJson($data);
            }
        }
    }

    $data = [
        'error' => true,
        'message' => 'Failed to like'
    ];
    return $response->withJson($data);

});

