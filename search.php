<?php
    require_once "vendor/autoload.php";
    use \Classes\Config\Config;
    use \Classes\Util\Session;
    use \Classes\Member\MembershipService;
    use \Classes\Blog\BlogRepository;
    use \Classes\Validation\Input;
    use \Classes\Blog\Blog;
?>

<?php  require_once "views/includes/header.php" ?>
<?php
    $objMembershipService = new MembershipService();
    $objBlog = new Blog();
?>
<div class="content_area">
    <div class="container">
        <div class="row">
            <div class="col m9">
                <div class="content">
                    <div class="blog_block">
                        <?php
                            $objBlogRepository = new BlogRepository();

                            if (Input::exists('get')) {
                                $search = Input::get('search');
                            }
                            $blogs = $objBlogRepository->getBlogBySearch($search);

                            if ($blogs) :
                                foreach ($blogs as $blog) :
                        ?>
                        <div class="card">
                            <div class="card-image">
                                <img src="<?php echo base_url('uploads/'.$blog->featured_image) ?>">
                                <span class="card-title author">by <span>Riyad Uddin</span></span>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title"><strong><a href="<?php echo 'single-blog.php?id='.$blog->id ?>"><?php echo $blog->title; ?></a></strong></h4>
                                <div><?php echo string_limit($blog->body); ?></div>
                            </div>

                            <div class="card-action">
                                <div class="col s4">
                                    <div class="comments">
                                        <i class="fa fa-comments"></i> <?php echo $objBlog->countComments($blog->id); ?>
                                    </div>
                                </div>
                                <div class="col s4">
                                    <ul class="rating">
                                        <li class="fill"> <i class="fa fa-star"></i></li>
                                        <li class="fill"> <i class="fa fa-star"></i></li>
                                        <li class="fill"> <i class="fa fa-star"></i></li>
                                        <li class="fill"> <i class="fa fa-star-o"></i></li>
                                        <li class="fill"> <i class="fa fa-star-o"></i></li>
                                    </ul>
                                </div>
                                <div class="col s4">
                                    <div class="like">
                                        <span class="upvote"><i class="fa fa-thumbs-up"></i> 14</span>
                                        <span class="downvote"><i class="fa fa-thumbs-down"></i> 2</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                                endforeach;
                            endif
                        ?>

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