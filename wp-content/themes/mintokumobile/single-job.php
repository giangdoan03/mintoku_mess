<?php get_header(); ?>
<div id="taxonomy-terms-container"></div>
<?php
// Get the current post type
$post_type = get_post_type();

$taxonomy = ''; // Initialize taxonomy variable
$terms_data = []; // Array to hold term data

// Determine the taxonomy based on the post type
switch ($post_type) {
    case 'vietnam':
        $taxonomy = 'province_vietnam';
        break;
    case 'laos':
        $taxonomy = 'province_laos';
        break;
    case 'cambodia':
        $taxonomy = 'province_cambodia';
        break;
    default:
        $taxonomy = ''; // Set a default value if needed
        break;
}

if ($taxonomy) {
    $terms = get_the_terms(get_the_ID(), $taxonomy);
    if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $terms_data[] = [
                'name' => esc_html($term->name),
                'link' => esc_url(get_term_link($term))
            ];
        }
    }
}

?>

<div id="primary" class="content-area">
    <?php
    // Kiểm tra xem field 'content_job' có tồn tại và có dữ liệu không
    if( have_rows('content_job') ): ?>

        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                // Lặp qua từng hàng của Repeater field
                while ( have_rows('content_job') ) : the_row();

                    // Lấy giá trị của sub-field WYSIWYG Editor
                    $wysiwyg_content = get_sub_field('job_detail'); // Thay 'job_detail' bằng tên của sub-field bạn đã tạo
                    ?>
                    <div class="swiper-slide">
                        <?php echo $wysiwyg_content; // Hiển thị nội dung WYSIWYG ?>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Nếu bạn muốn thêm các nút điều hướng -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

            <!-- Nếu bạn muốn thêm phân trang -->
            <div class="swiper-pagination"></div>
        </div>


    <?php else: ?>
        <p>No content available</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>

    var taxonomyTerms = <?php echo json_encode($terms_data); ?>;
    var previousPage = document.referrer;

    document.addEventListener('DOMContentLoaded', function () {
        var swiperP = new Swiper('.swiper-container', {
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            slidesPerView: 1,
        });


        // console.log('Swiper initialized with params:', swiperP.params, swiperC.params);

        // Khi trang được tải lại, kiểm tra tham số URL và chuyển đến slide tương ứng
        const hash = window.location.hash;
        if (hash) {
            const slideIndex = parseInt(hash.replace('#', '')) - 1; // Chuyển đổi từ chỉ số bắt đầu từ 1 sang 0
            if (!isNaN(slideIndex) && slideIndex >= 0 && slideIndex < swiperP.slides.length) {
                swiperP.slideTo(slideIndex);
            }
        }
    });




</script>
