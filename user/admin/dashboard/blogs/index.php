<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/myblog/vendor/autoload.php";

    use \Classes\Blog\BlogService;
    use \Classes\Blog\BlogRepository;
    use \Classes\Blog\Blog;
    use \Classes\Util\Session;
    use \Classes\Validation\Input;

    $objSession = new Session();
    $objBlogService = new BlogService();
    $objBlog= new Blog();


    if (Input::exists()) {
        if (Input::issetInput('check')) {
            $blog_id = Input::get('blog_id');
            $objBlog->active = 1;
            $objBlogService->updateBlogActivation($objBlog,$blog_id);
        }

        if (Input::issetInput('cross')) {
            $blog_id = Input::get('blog_id');
            $objBlog->active = 0;
            $objBlogService->updateBlogActivation($objBlog,$blog_id);
        }
    }

?>

<?php require_once "../../../views/includes/header.php" ?>
<?php require_once "../../../views/includes/sidebar.php" ?>



    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>Version 2.0</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <!--form begin-->
                <div class="col-md-12">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="box">
                        <div class="box-body">

                            <?php

                                $session_message = $objSession->get('session_message');

                                if (isset($session_message)) {
                                    echo $session_message;
                                    $objSession->unsetSession('session_message');
                                }
                            ?>

                            <table id="data_table" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Title</th>
                                    <th>Active</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                    $objBlogRepository = new BlogRepository();
                                    $blogs = $objBlogRepository->getAllBlogs('active');

                                    if($blogs) :
                                            $count = 1;
                                        foreach ($blogs as $single_blog) :
                                            $checkDisabled = $crossDisabled = '';
                                            $single_blog->active == 1 ? $checkDisabled = 'disabled' : $crossDisabled = 'disabled';
                                ?>

                                <tr>
                                    <td> <?php echo $count++; ?> </td>
                                    <td> <?php echo $single_blog->title; ?>  </td>
                                    <td> <?php echo $single_blog->active; ?>  </td>
                                    <td> <?php echo $single_blog->created_at; ?> </td>
                                    <td>

                                        <div class="btn-group">

                                            <form style="display: inline-block" action="" method="POST">
                                                <input type="hidden" name="blog_id" value="<?php echo $single_blog->id ?>">
                                                <button class="btn btn-sm btn-success" title="Approve blog" type="submit" <?php echo $checkDisabled ?> name="check"><i class="fa fa-check"></i></button>
                                            </form>

                                            <form style="display: inline-block" action="" method="POST">
                                                <input type="hidden" name="blog_id" value="<?php echo $single_blog->id ?>">
                                                <button class="btn btn-sm btn-danger" title="Reject blog" type="submit" <?php echo $crossDisabled ?> name="cross"><i class="fa fa-times"></i></button>
                                            </form>

                                            <form style="display: inline-block;" action="<?php echo base_url("user/admin/dashboard/blogs/delete.php?id=".$single_blog->id) ?>" method="POST">
                                                <input type="hidden" name="blog_id" value="<?php echo $single_blog->id ?>">
                                                <button class="btn btn-sm btn-default" title="Delete blog" type="submit" name="delete"><i class="fa fa-trash-o"></i></button>
                                            </form>




                                        </div>


                                    </td>
                                </tr>

                                <?php
                                        endforeach;
                                    endif;
                                ?>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>SL</th>
                                    <th>Title</th>
                                    <th>Active</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>

                            <!--data table finished-->

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
<?php require_once "../../../views/includes/footer.php" ?>