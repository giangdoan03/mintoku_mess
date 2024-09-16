<?php /*Template Name: Trang chủ */?>
<?php
$placeholder_image = get_stylesheet_directory_uri() . '/images/mintokumesse_logo.png';

// Lấy URL động của trang 'list-job'
$list_job_url = get_permalink(get_page_by_path('list-job'));
?>
<?php get_header(); ?>
<div class="container" id="page_content">
    <div class="logo_job">
        <img src="<?php echo $placeholder_image; ?>" alt="">
    </div>
    <div class="wrap-content">
        <div><h3>求人</h3></div>
        <div class="menu">
            <div class="menu_item">
                <a href="<?php echo $list_job_url; ?>">求人</a>
            </div>
            <div class="menu_item">
                <!-- Sử dụng link động cho 動画コンテンツ -->
                <a href="#">動画コンテンツ</a>
            </div>
            <div class="menu_item">
                <a href="#">キャムテックアカデミー</a>
            </div>
            <div class="menu_item">
                <a href="#">質問掲示板</a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
