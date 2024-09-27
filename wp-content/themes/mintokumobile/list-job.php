<?php
/**
 * Template Name: Danh sách việc được tìm thấy
 */

get_header();

// Lấy các tham số từ URL
$year = isset($_GET['year_r']) ? sanitize_text_field($_GET['year_r']) : '';
$post_type = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
$province_slug = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : ''; // Lấy slug của province từ URL
$university_slug = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';
$company_slug = isset($_GET['company']) ? sanitize_text_field($_GET['company']) : ''; // Lấy slug của công ty từ URL

// Kiểm tra và thiết lập taxonomy tương ứng với post_type
$taxonomies = array();
$university_name = '';

// Xác định taxonomy và lấy tên university từ slug
if ($post_type === 'vietnam') {
    $taxonomies = array(
        'year_vietnam' => $year,
        'province_vietnam' => $province_slug, // Tìm theo slug của tỉnh
        'university_vietnam' => $university_slug,
        'company_vietnam' => $company_slug // Tìm theo slug của công ty
    );

    if (!empty($university_slug)) {
        $university_term = get_term_by('slug', $university_slug, 'university_vietnam');
        if ($university_term) {
            $university_name = $university_term->name;
        }
    }
} elseif ($post_type === 'laos') {
    $taxonomies = array(
        'year_laos' => $year,
        'province_laos' => $province_slug, // Tìm theo slug của tỉnh
        'university_laos' => $university_slug,
        'company_laos' => $company_slug // Tìm theo slug của công ty
    );

    if (!empty($university_slug)) {
        $university_term = get_term_by('slug', $university_slug, 'university_laos');
        if ($university_term) {
            $university_name = $university_term->name;
        }
    }
} elseif ($post_type === 'cambodia') {
    $taxonomies = array(
        'year_cambodia' => $year,
        'province_cambodia' => $province_slug, // Tìm theo slug của tỉnh
        'university_cambodia' => $university_slug,
        'company_cambodia' => $company_slug // Tìm theo slug của công ty
    );

    if (!empty($university_slug)) {
        $university_term = get_term_by('slug', $university_slug, 'university_cambodia');
        if ($university_term) {
            $university_name = $university_term->name;
        }
    }
}

// Tạo mảng args cho WP_Query
$args = array(
    'post_type' => $post_type,
    'posts_per_page' => -1, // Giới hạn số bài viết trả về (thay đổi số lượng tùy ý)
    'tax_query' => array('relation' => 'AND'),
    'meta_query' => array(
        array(
            'key' => 'recommended_work', // Tên custom field
            'value' => 'recommended',    // Giá trị của custom field
            'compare' => 'LIKE'
        )
    )
);

// Thêm các taxonomy vào tax_query nếu có
foreach ($taxonomies as $taxonomy => $term) {
    if (!empty($term)) {
        $args['tax_query'][] = array(
            'taxonomy' => $taxonomy,
            'field' => 'slug', // Sử dụng 'slug' để tìm theo slug
            'terms' => $term,
        );
    }
}

// Thực hiện WP_Query
$query = new WP_Query($args);
?>
    <div id="content" class="page-list-job-filter">
        <div class="title_list_job_filter">
            <h4><?php echo $university_name . ' ' . $year; ?>
            </h4>
        </div>
        <h4 class="title_recommended">
            Công việc đề xuất
        </h4>
        <?php
        if ($query->have_posts()) : ?>

            <div class="content_list_job_filter">
                <ul>
                    <?php echo do_shortcode('[acf_recommended_work_slider]'); ?>
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <li>
                            <?php
                            // Get the featured image URL
                            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                            // Use a placeholder if no featured image is set
                            if (!$thumbnail_url) {
                                $thumbnail_url = 'https://placehold.co/600x400';
                            }
                            ?>
                            <a href="<?php the_permalink(); ?>">
                                <p><img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>" style="max-width:100%; height:auto;"></p>
                                <p><?php the_title(); ?></p>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php else : ?>
            <p>Không có công việc nào phù hợp.</p>
        <?php endif; ?>
    </div>

    <!-- JavaScript remains the same -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
    <script>
        // document.addEventListener('DOMContentLoaded', function () {
        //     const listItems = document.querySelectorAll('.page-list-job-filter li');
        //
        //     listItems.forEach(item => {
        //         const hammer = new Hammer(item);
        //
        //         hammer.on('swipeleft', function () {
        //             window.location.href = item.querySelector('a').href;
        //         });
        //     });
        // });
    </script>

<?php
wp_reset_postdata();
get_footer();
