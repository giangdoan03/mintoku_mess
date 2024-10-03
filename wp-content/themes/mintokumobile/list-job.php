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
            <h4><?php echo esc_html($university_name) . ' ' . esc_html($year); ?></h4>
        </div>
        <?php if ($query->have_posts()) : ?>
            <div class="content_list_job_filter">
                <ul>
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <li>
                            <?php
                            // Lấy URL của ảnh đại diện bài viết (featured image)
                            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                            // Sử dụng ảnh thay thế nếu không có ảnh đại diện
                            if (!$thumbnail_url) {
                                $thumbnail_url = 'https://placehold.co/600x400';
                            }

                            // Lấy các term của taxonomy company_vietnam liên quan đến bài viết
                            $company_terms = wp_get_post_terms(get_the_ID(), 'company_vietnam', array('fields' => 'all'));
                            $company_image_url = '';
                            $company_name = ''; // Biến để lưu tên công ty

                            // Kiểm tra và lấy ảnh từ term meta (nếu có)
                            if (!empty($company_terms)) {
                                $company_term_id = $company_terms[0]->term_id;
                                $company_name = $company_terms[0]->name; // Lấy tên của công ty

                                // Lấy ID của ảnh từ custom field 'company_image'
                                $company_image_id = get_term_meta($company_term_id, 'company_image', true);

                                // Nếu có ID của ảnh, chuyển đổi thành URL
                                if (!empty($company_image_id)) {
                                    $company_image_url = wp_get_attachment_url($company_image_id);
                                }

                                // Nếu không có URL của ảnh, sử dụng ảnh mặc định
                                if (!$company_image_url) {
                                    $company_image_url = 'https://placehold.co/100x100';
                                }
                            }

                            ?>
                            <a href="<?php the_permalink(); ?>" class="job-item">
                                <!-- Hiển thị ảnh đại diện bài viết -->
                                <p><img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>" style="max-width:100%; height:auto;"></p>

                                <!-- Hiển thị tiêu đề -->
                                <p><?php the_title(); ?></p>
                                <!-- Hiển thị ảnh từ taxonomy company_vietnam -->
                                <?php if ($company_image_url) : ?>
                                    <div class="company-info">
                                        <p class="logo_company">
                                            <img src="<?php echo esc_url($company_image_url); ?>" alt="Company Image">
                                        </p>
                                        <!-- Hiển thị tên công ty -->
                                        <?php if ($company_name) : ?>
                                            <p class="company_name"><?php echo esc_html($company_name); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
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
