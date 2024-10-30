<?php
/* Template Name: Thỏa thuận sử dụng */

// Include WordPress header
get_header();

$placeholder_image = get_stylesheet_directory_uri() . '/images/mintokumesse_logo.png';
?>
<div id="page-term" class="page-term">
    <div class="banner">
        <div class="logo_job_term_page">
            <img src="<?php echo esc_url($placeholder_image); ?>" alt="">
        </div>
        <h2 class="title_term_page"><?php the_title(); ?></h2>
    </div>
    <div class="container">
        <?php
        // Start the loop to get the page content
        while ( have_posts() ) :
            the_post(); // Set up the post data
            ?>
            <div class="page-content">
                <?php the_content(); ?> <!-- Display the page content -->
            </div>
            <div class="back_page">
                <a href="#" id="backButton">Quay lại</a>
            </div>

        <?php
        endwhile; // End of the loop.
        ?>
    </div>
</div><!-- #main -->
<script>
    document.getElementById('backButton').addEventListener('click', function() {
        if (window.history.length > 1) {
            window.history.go(-2); // Quay lại 2 trang trong lịch sử
            var baseURL = window.location.origin;
        } else {
            // Lấy URL gốc từ domain hiện tại (bao gồm cả localhost và hosting)
            var baseURL = window.location.origin;

            // Kiểm tra xem bạn đang ở localhost hay trên hosting
            if (baseURL.includes('localhost')) {
                // Nếu đang ở localhost, sử dụng đường dẫn tương ứng với localhost
                window.location.href = baseURL + "/mintoku_mobile/list-job/";
            } else {
                // Nếu không phải localhost, sử dụng đường dẫn cho hosting
                window.location.href = baseURL + "/list-job/";
            }
        }
    });
</script>
<?php
// Include WordPress footer
get_footer();
?>
