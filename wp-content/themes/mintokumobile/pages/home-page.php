<?php /*Template Name: Trang chủ */?>
<?php
$placeholder_image = get_stylesheet_directory_uri() . '/images/mintokumesse_logo.png';
?>
<?php get_header(); ?>
    <div class="container" id="page_content">
        <div class="logo_job">
            <img src="<?php echo $placeholder_image; ?>" alt="">
        </div>
        <?php echo do_shortcode('[post_types_list]'); ?>
    </div>

<?php get_footer(); ?>