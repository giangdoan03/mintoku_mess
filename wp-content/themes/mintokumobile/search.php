<?php get_header(); ?>

<div class="content-area">
    <main class="site-main">
        <h1 class="page-title"><?php _e('Search Results', 'your-theme-textdomain'); ?></h1>

        <?php
        // Hiển thị danh sách bài viết theo năm
        display_grouped_by_year();
        ?>

    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
