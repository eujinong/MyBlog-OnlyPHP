<?php
    require_once "vendor/autoload.php";
    use \Classes\Validation\Input;
    use \Classes\Blog\BlogRepository;
    use \Classes\Util\Token;
    use \Classes\Validation\Validation;
    use \Classes\Blog\Blog;
    use \Classes\Blog\BlogService;
    use \Classes\Blog\Comment;
    use \Classes\Blog\Reply;
    use \Classes\Util\Session;

    $objBlog = new Blog();
    $objValidation = new Validation();
    $objBlogService = new BlogService();
?>

<?php

    if (Input::exists('get')) {
        $blogId = Input::get('id');
    }

    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {
            if (Input::get('comment_form')) {

                $objValidation->validate($_POST,
                    array(
                        'comment' => array(
                            'required' => true,
                        )
                    )
                );

                if ($objValidation->passed()) {
                    $objComment = new Comment(Input::get('comment'));

                    $inserted = $objBlog->addComment($objComment, Session::get('user'), $blogId);

                    if ($inserted) {
                        // do something
                    }
                }

            }
        }

        if (Input::get('reply_form')) {
            $objValidation->validate($_POST,
                array(
                    'reply' => array(
                        'required' => true,
                    )
                )
            );

            if ($objValidation->passed()) {
                $objReply = new Reply(Input::get('reply'));
                $comment_id = Input::get('comment_id');
                $inserted = $objBlog->addReply($objReply, $comment_id, Session::get('user'), $blogId);

                if ($inserted) {
                    // do something
                }
            }
        }
    }

?>

<?php  require_once "views/includes/header.php" ?>

<div class="content_area">
    <div class="container">
        <div class="row">
            <div class="col m9">
                <div class="content">
                    <div class="blog_block">
                        <?php
                            if (Input::exists('get')) {
                                $objBlogRepository = new BlogRepository();
                                $blog = $objBlogRepository->get($blogId);
                            }
                        ?>

                        <div class="card">
                            <div class="card-image">
                                <img src="<?php echo base_url('uploads/'.$blog->featured_image) ?>">
                                <span class="card-title author">by <span>Abdur Rahman</span></span>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title"><strong><?php echo $blog->title; ?></strong></h4>
                                <div>
                                    <?php echo $blog->body; ?>
                                </div>
                            </div>
                            <div class="card-action">
                                <div class="col s6">
                                    <div class="comments">
                                        <i class="fa fa-comments"></i> <?php echo $objBlog->countComments($blogId); ?>
                                    </div>
                                </div>
                                <div class="col s6">
                                    <div class="like <?php
                                                    if($member) {
                                                        if ($objBlogService->countBlogLikeByUser($blogId, Session::get('user'))) {
                                                            echo ' liked';
                                                        }
                                                    } ?>">
                                        <span class="upvote">
                                            <button data-blogId="<?php echo $blogId; ?>" data-userId="<?php echo Session::get('user'); ?>" data-baseurl="<?php echo base_url('api/') ?>">
                                                <i class="fa fa-thumbs-up"></i>
                                            </button>
                                            <span class="likeCount"><?php echo $objBlogService->countBlogLikes($blogId); ?></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!--answer area begin-->
                    <div class="answer_area"> <!-- answer area begin -->
                        <div class="post_an_answer clearfix">
                            <form class="col s12" method="post" action="">

                                <div class="row">
                                    <div class="input-field col s12">
                                        <textarea id="textarea1" class="materialize-textarea" name="comment"></textarea>
                                        <label for="textarea1">Post a comment</label>
                                    </div>
                                    <div class="input-field col s12 right-align">
                                        <input type="hidden" name="comment_form" value="comment">
                                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                                        <button class="btn waves-effect waves-light" type="submit" name="comment_btn">Comment
                                            <i class="fa fa-message"></i>
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>

                        <div class="answers">
                            <?php
                                $comments = $objBlog->getComments($blogId);
                              if ($comments) :
                                  foreach ($comments as $comment) :
                            ?>

                              <div class="single_answer">
                                  <div class="single_answer_content">
                                      <div class="single_answer_text">
                                          <div class="userinfo">
                                              <div class="avatar">
                                                  <img src="
                                                  <?php echo empty($comment->photo_url) ?
                                                      base_url('user/dist/img/'.\Classes\Config\Config::get('defaults/profile_pic')) :
                                                      base_url('user/uploads/'.$comment->photo_url);
                                                  ?>" alt="" class="img-circle">
                                              </div>
                                          </div>
                                          <div class="posttext">
                                              <h6><strong><?php echo $comment->name ?></strong> says:</h6>
                                              <p><?php echo $comment->comment ?></p>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>

                                      <div class="question_info_bottom center-align">
                                          <ul>
                                              <li class="like
                                              <?php
                                                  if($member) {
                                                      if ($objBlogService->countCommentLikeByUser($blogId, Session::get('user'), $comment->id)) {
                                                          echo ' liked';
                                                      }
                                                  }
                                              ?>
                                              "><span class="upvote"><button data-commentId="<?php echo $comment->id; ?>" data-blogId="<?php echo $blogId; ?>" data-userId="<?php echo Session::get('user'); ?>"  data-baseurl="<?php echo base_url('api/') ?>"><i class="fa fa-thumbs-up"></i></button> <span class="likeCount"><?php echo $objBlogService->countCommentLikes($blogId, $comment->id); ?></span></span></li>
                                              <li><a href="#"> <i class="fa fa-clock-o"></i>Posted on : <?php echo $comment->created_at; ?></a></li>
                                          </ul>
                                      </div>
                                  </div>

                                  <div class="replies"><!-- replies begin -->
                                      <?php
                                          $replies = $objBlog->getReplies($comment->id);
                                          if ($replies) :
                                              foreach ($replies as $reply) :
                                      ?>


                                              <div class="reply"><!-- single reply begin -->
                                                  <div class="single_answer_content">
                                                      <div class="single_answer_text">
                                                          <div class="userinfo">
                                                              <div class="avatar">
                                                                  <img src="
                                                                  <?php echo empty($reply->photo_url) ?
                                                                      base_url('user/dist/img/'.\Classes\Config\Config::get('defaults/profile_pic')) :
                                                                      base_url('user/uploads/'.$reply->photo_url);
                                                                  ?>" alt="" class="img-circle">
                                                              </div>
                                                          </div>
                                                          <div class="posttext">
                                                              <h6><strong><?php echo $reply->name ?></strong> replied:</h6>
                                                              <p><?php echo $reply->reply ?></p>
                                                          </div>
                                                          <div class="clearfix"></div>
                                                      </div>

                                                      <div class="question_info_bottom center-align">
                                                          <ul>
                                                              <li class="like
                                                                <?php
                                                                  if($member) {
                                                                      if ($objBlogService->countReplyLikeByUser($blogId, Session::get('user'), $comment->id, $reply->id)) {
                                                                          echo ' liked';
                                                                      }
                                                                  }
                                                              ?>
                                                              "><span class="upvote"><button data-replyId="<?php echo $reply->id; ?>" data-commentId="<?php echo $comment->id; ?>" data-blogId="<?php echo $blogId; ?>" data-userId="<?php echo Session::get('user'); ?>" data-baseurl="<?php echo base_url('api/') ?>"><i class="fa fa-thumbs-up"></i></button> <span class="likeCount"><?php echo $objBlogService->countBlogLikes($blogId); ?></span></span></li>
                                                              <li><a href="#"> <i class="fa fa-clock-o"></i>Posted on : <?php echo $reply->created_at; ?></a></li>
                                                          </ul>
                                                      </div>
                                                  </div>
                                              </div><!-- single reply finish -->


                                          <?php
                                              endforeach;
                                          endif;
                                          ?>

                                      <div class="reply"><!-- single reply begin -->
                                          <div class="single_answer_text">
                                              <div class="post_an_answer clearfix">
                                                  <form class="col s12" method="post" action="">

                                                      <div class="row">
                                                          <div class="input-field col s12">
                                                              <textarea id="textarea2" class="materialize-textarea" name="reply"></textarea>
                                                              <label for="textarea2">Reply to this comment</label>
                                                          </div>
                                                          <div class="input-field col s12 right-align">
                                                              <input type="hidden" name="comment_id" value="<?php echo $comment->id ?>">
                                                              <input type="hidden" name="reply_form" value="comment">
                                                              <button class="btn waves-effect waves-light" type="submit" name="action">Reply
                                                                  <i class="fa fa-message"></i>
                                                              </button>
                                                          </div>

                                                      </div>
                                                  </form>
                                              </div>
                                          </div>
                                      </div><!-- single reply finish -->

                                  </div> <!-- replies finished -->
                              </div>
                              <!--single answer finish-->


                            <?php
                                endforeach;
                              endif;
                            ?>

                        </div>
                        <!--answers finish-->
                    </div> <!-- answer area finished -->
                    <!--answer area finish-->



                </div>
            </div>

            <div class="col m3">
                <?php include_once 'views/includes/sidebar.php' ?>
            </div>
        </div>
    </div>
</div>


<?php require_once "views/includes/footer.php" ?>