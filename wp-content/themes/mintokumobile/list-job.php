<?php
/**
 * Template Name: Danh sách việc được tìm thấy
 */

get_header();

// Lấy các tham số từ URL
$year = isset($_GET['year_r']) ? sanitize_text_field($_GET['year_r']) : '';
$post_type = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
$province_slug = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
$university_slug = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';
$company_slug = isset($_GET['company']) ? sanitize_text_field($_GET['company']) : '';

// Khởi tạo biến $taxonomies và $university_name
$taxonomies = array();
$university_name = '';

// Thiết lập điều kiện cho từng post_type
if ($post_type === 'vietnam') {
    $taxonomies = array(
        'year_vietnam' => $year,
        'province_vietnam' => $province_slug,
        'university_vietnam' => $university_slug,
        'company_vietnam' => $company_slug
    );

    // Lấy tên của trường đại học nếu có university_slug
    if (!empty($university_slug)) {
        $university_term = get_term_by('slug', $university_slug, 'university_vietnam');
        if ($university_term) {
            $university_name = $university_term->name;
        }
    }
}

// Tạo mảng args cho WP_Query với điều kiện post_type
$args = array(
    'post_type' => $post_type,
    'posts_per_page' => -1, // Hiển thị tất cả bài viết
    'tax_query' => array('relation' => 'AND'), // Sử dụng AND để yêu cầu tất cả các điều kiện
);

// Thêm các taxonomy vào tax_query nếu có giá trị
foreach ($taxonomies as $taxonomy => $term) {
    if (!empty($term)) {
        $args['tax_query'][] = array(
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => $term,
        );
    }
}

// Truy vấn tất cả bài viết theo điều kiện tìm kiếm
$query = new WP_Query($args);

// Khởi tạo mảng lưu trữ bài viết có và không có _last_viewed
$posts_with_last_viewed = array();
$posts_without_last_viewed = array();

// Phân loại bài viết sau khi truy vấn
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();

        // Kiểm tra xem bài viết có _last_viewed hay không
        $last_viewed = get_post_meta(get_the_ID(), '_last_viewed', true);

        if (!empty($last_viewed)) {
            // Nếu có _last_viewed, đẩy vào mảng có _last_viewed
            $posts_with_last_viewed[] = $post;
        } else {
            // Nếu không có _last_viewed, đẩy vào mảng không có _last_viewed
            $posts_without_last_viewed[] = $post;
        }
    }
}

// Gộp hai mảng lại, đưa bài viết có _last_viewed lên đầu
$final_posts = array_merge($posts_with_last_viewed, $posts_without_last_viewed);

// Reset post data sau truy vấn
wp_reset_postdata();

$args_recommended = array(
    'post_type' => $post_type,
    'meta_key' => '_last_viewed',
    'orderby' => 'meta_value',
    'order' => 'DESC',
    'posts_per_page' => 3,
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'recommended_work',
            'value' => 'recommended', // Giá trị của checkbox
            'compare' => 'LIKE', // Sử dụng LIKE để tìm chính xác giá trị
        ),
        array(
            'key' => '_last_viewed',
            'compare' => 'EXISTS', // Đảm bảo bài viết có _last_viewed
        )
    ),
);

// Truy vấn bài viết recommended_work
$recommended_query = new WP_Query($args_recommended);

// Lưu trữ ID các bài recommended_work
//$recommended_ids = wp_list_pluck($recommended_query->posts, 'ID');
//
//// Tạo danh sách bài viết từ điều kiện tìm kiếm nhưng loại trừ bài recommended_work
//$final_posts = array();
//if ($query->have_posts()) {
//    foreach ($query->posts as $post) {
//        if (!in_array($post->ID, $recommended_ids)) {
//            $final_posts[] = $post;
//        }
//    }
//}

// Hàm hiển thị bài viết
function display_job_item($post, $block_post = false, $block_post_recommended = false) {
    setup_postdata($post);

    // Lấy URL ảnh đại diện
    $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'medium');
    if (!$thumbnail_url) {
        $thumbnail_url = 'https://placehold.co/600x400';
    }

    // Lấy các term của taxonomy company_vietnam
    $company_terms = wp_get_post_terms($post->ID, 'company_vietnam', array('fields' => 'all'));
    $company_image_url = '';
    $company_name = '';

    if (!empty($company_terms)) {
        $company_term_id = $company_terms[0]->term_id;
        $company_name = $company_terms[0]->name;

        $company_image_id = get_term_meta($company_term_id, 'company_image', true);
        if (!empty($company_image_id)) {
            $company_image_url = wp_get_attachment_url($company_image_id);
        }
        if (!$company_image_url) {
            $company_image_url = 'https://placehold.co/100x100';
        }
    }

    // Xác định class của div .company-info
    $company_info_class = $block_post_recommended ? 'company-info company-info-recommended' : 'company-info';
    $text_info_job = $block_post_recommended ? 'text_info_job text_info_job_recommended' : 'text_info_job';
    $avatar_job = $block_post_recommended ? 'avatar_job' : 'avatar_job avatar_job_not_recommended';

    // Hiển thị HTML của bài viết
    ?>
    <li>
        <div class="job-item">
            <p class="title_job">
                <?php if ($block_post_recommended) : ?>
                    <span class="label_job_recommended">おすすめ求人</span>
                <?php endif; ?>
                <span class="text"><?php the_title(); ?></span>
            </p>
            <div class="job_content">
                <div class="<?php echo esc_attr($text_info_job); ?>">
                    <?php if ($company_image_url) : ?>
                        <div class="<?php echo esc_attr($company_info_class); ?>">
                            <div class="bl_logo">
                                <p class="logo_company">
                                    <img src="<?php echo esc_url($company_image_url); ?>" alt="Company Image">
                                </p>
                                <!-- Hiển thị tên công ty -->
                                <?php if ($company_name) : ?>
                                    <p class="company_name"><?php echo esc_html($company_name); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="box_w">
                                <div class="hashtag">
                                    <span class="tag_item">土日祝休み</span>
                                    <span class="tag_item">昇給賞与あり</span>
                                    <span class="tag_item">個室あり</span>
                                    <span class="tag_item">夜勤あり</span>
                                </div>
<!--                                <div class="salary">-->
<!--                                    <span class="label_text">時給</span> <span class="salary_text">1200 円</span>-->
<!--                                </div>-->
                                <div class="salary">
                                    <span class="label_text">Lương</span>
                                    <span class="salary_text">
                                          <?php
                                          // Lấy nội dung của Flexible Content
                                          $slides = get_field('job_info'); // 'job_info' là tên của Flexible Content field chứa các slide

                                          // Gọi hàm và truyền tên field cần lấy
                                          $noi_dung_1 = lay_noi_dung_field($slides, 'noi_dung_1');

                                          // In ra nội dung của noi_dung_1
                                          echo $noi_dung_1;
                                          ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Hiển thị ảnh đại diện bài viết -->
                    <div class="text_desc">
                        <?php
                        // Lấy giá trị của custom field ACF
                        $short_job_description = get_field('short_job_description');

                        // Kiểm tra nếu trường này có giá trị
                        if (!empty($short_job_description)) : ?>
                            <div class="summary_job">
                                <p><?php echo esc_html($short_job_description); ?></p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="">

                </div>
                <p class="avatar_job">
                    <div class="has_label label_jobs_recommended">
                        <img class="<?php echo esc_attr($avatar_job); ?>" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php
                        $post_id = get_the_ID();
                        display_job_info_fields($post_id);
                        ?>
                    </div>
                </p>
            </div>
            <div class="btn_detail">
                <div class="btn_content">
                    <a href="<?php the_permalink(); ?>">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
    </li>
    <?php
}
?>

    <div id="content" class="page-list-job-filter">
        <div class="logo_mintoku_mess">
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo_mintoku_mess.png" alt="company mintoku mess">
            <div class="title_job_area">
                <p><?php echo $university_name; ?></p>
            </div>
        </div>
        <div class="block_jobs_recommended">
            <div class="container">
                <div class="border_black">
                    <div class="border_purple">
                        <?php echo do_shortcode('[acf_recommended_work_slider]'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hiển thị bài viết recommended_work -->
        <?php if ($recommended_query->have_posts()) : ?>
            <div class="content_list_job_filter">
                <ul>
                    <?php while ($recommended_query->have_posts()) : $recommended_query->the_post(); ?>
                        <?php display_job_item(get_post(), false, true); // true: Hiển thị nhãn "おすすめ求人" ?>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($final_posts)) : ?>
            <div class="content_list_job_filter bl_list_qr">
                <ul>
                    <?php foreach ($final_posts as $post) : ?>
                        <?php
                        setup_postdata($post);
                        // Sử dụng hàm `display_job_item` để hiển thị bài viết
                        display_job_item(get_post(), false, false); // true: Hiển thị nhãn "おすすめ求人"
                        ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p>Không tìm thấy bài viết nào.</p>
        <?php endif; ?>


    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>

<?php
wp_reset_postdata();
get_footer();
