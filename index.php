<?php
    session_start();
    require_once "vendor/autoload.php";
    use \Classes\Config\Config;
    use \Classes\Util\Session;
    use \Classes\Blog\BlogService;
    use \Classes\Member\MembershipService;
    use \Classes\Blog\BlogRepository;
    use \Classes\Blog\Blog;
?>

<?php  require_once "views/includes/header.php" ?>

<?php
    $objMembershipService = new MembershipService();
    $objBlog = new Blog();
    $objBlogService = new BlogService();
?>

<div class="content_area">
    <div class="container">
        <div class="row">
            <div class="col m9">
                <div class="content">
                    <div class="blog_block">
                        <?php
                            $objBlogRepository = new BlogRepository();
                            $blogs = $objBlogRepository->getAllBlogs();
                        ?>


                        <?php
                            if ($blogs) :
                                foreach ($blogs as $blog) :?>
                                    <div class="card">
                                        <div class="card-image">
                                            <img src="<?php echo base_url('uploads/'.$blog->featured_image) ?>">
                                            <span class="card-title author">by <span>Pritom Chokroborty</span></span>
                                        </div>
                                        <div class="card-content">
                                            <h4 class="card-title"><strong><a href="<?php echo 'single-blog.php?id='.$blog->id ?>"><?php echo $blog->title; ?></a></strong></h4>
                                            <div><?php echo string_limit($blog->body); ?></div>
                                        </div>

                                        <div class="card-action">
                                            <div class="col s6">
                                                <div class="comments">
                                                    <i class="fa fa-comments"></i> <?php echo $objBlog->countComments($blog->id); ?>
                                                </div>
                                            </div>
                                            <div class="col s6">
                                                <div class="like <?php
                                                if($member) {
                                                    if ($objBlogService->countBlogLikeByUser($blog->id, Session::get('user'))) {
                                                        echo ' liked';
                                                    }
                                                } ?>">
                                                    <span class="upvote">
                                                        <button data-blogId="<?php echo $blog->id; ?>" data-userId="<?php echo Session::get('user'); ?>" data-baseurl="<?php echo base_url('api/') ?>">
                                                            <i class="fa fa-thumbs-up"></i>
                                                        </button>
                                                        <span class="likeCount"><?php echo $objBlogService->countBlogLikes($blog->id); ?></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        <?php  endforeach;
                            endif ?>

                    </div>
                </div>
            </div>

            <div class="col m3">
                <?php include_once 'views/includes/sidebar.php' ?>
            </div>
        </div>
    </div>
</div>

<?php require_once "views/includes/footer.php" ?>