<?php /*Template Name: Trang chủ */?>
<?php get_header(); ?>
    <div class="container" id="page_content">
        <?php echo do_shortcode('[post_types_list]'); ?>
    </div>

<?php get_footer(); ?>