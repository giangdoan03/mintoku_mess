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

// Kiểm tra và thiết lập taxonomy tương ứng với post_type
$taxonomies = array();
$university_name = '';

if ($post_type === 'vietnam') {
    $taxonomies = array(
        'year_vietnam' => $year,
        'province_vietnam' => $province_slug,
        'university_vietnam' => $university_slug,
        'company_vietnam' => $company_slug
    );

    if (!empty($university_slug)) {
        $university_term = get_term_by('slug', $university_slug, 'university_vietnam');
        if ($university_term) {
            $university_name = $university_term->name;
        }
    }
}

// Tạo mảng args cho WP_Query
$args = array(
    'post_type' => $post_type,
    'posts_per_page' => -1,
    'meta_key' => '_last_viewed', // Thêm điều kiện sắp xếp theo _last_viewed
    'orderby' => array(
        'meta_value' => 'DESC',  // Sắp xếp theo giá trị của _last_viewed trước
        'date' => 'DESC'         // Nếu không có _last_viewed, sắp xếp theo ngày đăng
    ),
    'order' => 'DESC', // Sắp xếp giảm dần (bài viết được xem gần đây nhất trước)
    'tax_query' => array('relation' => 'AND'),
);

// Thêm các taxonomy vào tax_query nếu có
foreach ($taxonomies as $taxonomy => $term) {
    if (!empty($term)) {
        $args['tax_query'][] = array(
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => $term,
        );
    }
}

// Truy vấn tất cả các bài viết theo điều kiện tìm kiếm
$query = new WP_Query($args);

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
$recommended_ids = wp_list_pluck($recommended_query->posts, 'ID');

// Tạo danh sách bài viết từ điều kiện tìm kiếm nhưng loại trừ bài recommended_work
$final_posts = array();
if ($query->have_posts()) {
    foreach ($query->posts as $post) {
        if (!in_array($post->ID, $recommended_ids)) {
            $final_posts[] = $post;
        }
    }
}

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
        <a href="<?php the_permalink(); ?>" class="job-item">
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
                                <div class="salary">
                                    <span class="label_text">時給</span> <span class="salary_text">1200 円</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Hiển thị ảnh đại diện bài viết -->
                    <div class="text_desc">
                        <div class="summary_job">
                            <p>金属を溶かして自動車の部品を作ります。ダイカストの機械に金型を取りつけます。
                                機械に、部品の材料となる金属を入れ、溶かします。溶けた金属を型に入れます。
                                金属を冷やして、自動車の部品をつくります。その他、マシニングやNC旋盤を使う仕事もあります。説明文説明文説明文説明文説明文。
                                説明文説明文説明文説明文説明文説明文説明文説明文説明文説明文説明文</p>
                        </div>
                    </div>
                </div>
                <p class="avatar_job">
                    <img class="<?php echo esc_attr($avatar_job); ?>" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>">
                </p>
            </div>
        </a>
    </li>
    <?php
}
?>

    <div id="content" class="page-list-job-filter">
        <div class="logo_mintoku_mess">
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo_mintoku_mess.png" alt="company mintoku mess">
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


        <!-- Hiển thị bài viết non recommended_work -->
        <?php if ($query->have_posts()) : ?>
            <div class="content_list_job_filter">
                <ul>
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <?php display_job_item(get_post(), false, false); // true: Hiển thị nhãn "おすすめ求人" ?>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>

<?php
wp_reset_postdata();
get_footer();
