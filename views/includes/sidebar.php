<div class="sidebar_area">
    <?php

        $objCategoryRepository = new \Classes\Blog\CategoryRepository();
        $objTagRepository = new \Classes\Blog\TagRepository();

        $categories = $objCategoryRepository->getCategories();
        $tags = $objTagRepository->getTags();

    ?>

    <div class="sidebar_block">
        <ul>
            <li class="search">
                <form method="get" action="search.php">
                    <div class="search-wrapper card">
                        <input id="search" name="search" placeholder="search"><button type="submit"><i class="material-icons">search</i></button>
                    </div>
                </form>
            </li>
        </ul>
    </div>

    <div class="sidebar_block categories">
        <h5 class="title">categories</h5>
        <ul>
            <?php if ($categories) :?>
                <?php foreach ($categories as $category) :?>
                    <li><a href="<?php echo base_url('category.php?category='.$category->category) ?>"><?php echo $category->category ?></a></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <div class="sidebar_block">
        <h5 class="title">Tags</h5>
        <div class="tags">
            <?php if ($tags) :?>
                <?php foreach ($tags as $tag) :?>
                    <a href="<?php echo base_url('tag.php?tag='.$tag->tag) ?>"><?php echo $tag->tag ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>