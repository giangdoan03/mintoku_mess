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
    'posts_per_page' => -1, // Lấy tất cả các bài viết
    'tax_query' => array('relation' => 'AND'),
    'meta_query' => array(
        array(
            'key' => 'recommended_work', // Custom field 'recommended_work'
            'compare' => 'EXISTS', // Kiểm tra xem custom field có tồn tại hay không
        )
    ),
    'orderby' => array(
        'meta_value' => 'DESC', // Sắp xếp bài viết có 'recommended_work' lên đầu
        'date' => 'DESC', // Sau đó sắp xếp theo ngày đăng
    ),
    'meta_key' => 'recommended_work', // Sử dụng meta_key để sắp xếp theo giá trị này trước
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
        <div class="logo_mintoku_mess">
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo_mintoku_mess.png" alt="company mintoku mess">
        </div>
<!--        <div class="title_list_job_filter">-->
<!--            <h4>--><?php //echo esc_html($university_name) . ' ' . esc_html($year); ?><!--</h4>-->
<!--        </div>-->
        <div class="block_jobs_recommended">
            <div class="container">
                <div class="border_black">
                    <div class="border_purple">
                        <?php echo do_shortcode('[acf_recommended_work_slider]'); ?>
                    </div>
                </div>
            </div>
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
                                <!-- Hiển thị tiêu đề -->
                                <p class="title_job">
                                    <?php
                                    // Lấy giá trị của custom field 'recommended_work'
                                    $recommended = get_post_meta(get_the_ID(), 'recommended_work', false); // Trả về mảng giá trị

                                    // Kiểm tra nếu mảng lồng có giá trị đầu tiên là 'recommended'
                                    if (is_array($recommended) && isset($recommended[0][0]) && $recommended[0][0] === 'recommended') {
                                        echo '<span class="label_job_recommended">おすすめ求人</span>';
                                    }
                                    echo '<span>';
                                    // Hiển thị tiêu đề bài viết
                                    the_title();
                                    echo '</span>';
                                    ?>
                                </p>



                                <div class="job_content">
                                    <div class="text_info_job">
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
                                        <!-- Hiển thị ảnh đại diện bài viết -->
                                        <div class="text_desc">
                                            <div class="hashtag">
                                                <span class="tag_item">土日祝休み</span>
                                                <span class="tag_item">昇給賞与あり</span>
                                                <span class="tag_item">個室あり</span>
                                                <span class="tag_item">夜勤あり</span>
                                            </div>
                                            <div class="salary">
                                                <span class="label_text">時給</span> <span class="salary_text">1200 円</span>
                                            </div>
                                            <div class="summary_job">
                                                <p>金属を溶かして自動車の部品を作ります。ダイカストの機械に金型を取りつけます。
                                                    機械に、部品の材料となる金属を入れ、溶かします。溶けた金属を型に入れます。
                                                    金属を冷やして、自動車の部品をつくります。その他、マシニングやNC旋盤を使う仕事もあります。説明文説明文説明文説明文説明文。
                                                    説明文説明文説明文説明文説明文説明文説明文説明文説明文説明文説明文</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="avatar_job"><img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>" style="max-width:100%; height:auto;"></p>
                                    <!-- Hiển thị ảnh từ taxonomy company_vietnam -->
                                </div>
                            </a>

                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php else : ?>
            <p>Không có công việc nào phù hợp.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
<?php
wp_reset_postdata();
get_footer();
