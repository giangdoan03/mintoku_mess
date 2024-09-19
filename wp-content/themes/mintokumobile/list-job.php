<?php
/**
 * Template Name: Danh Sách Công Việc
 */

get_header();

// Lấy các tham số từ URL
$year = isset($_GET['year_r']) ? sanitize_text_field($_GET['year_r']) : '';
$post_type = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
$province = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
$university_slug = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';
$label = isset($_GET['label']) ? sanitize_text_field($_GET['label']) : '';

// Kiểm tra và thiết lập taxonomy tương ứng với post_type
$taxonomies = array();
$university_name = '';

// Xác định taxonomy và lấy tên university từ slug
if ($post_type === 'vietnam') {
    $taxonomies = array(
        'year_vietnam' => $year,
        'province_vietnam' => $province,
        'university_vietnam' => $university_slug
    );

    if (!empty($university_slug)) {
        $university_term = get_term_by('slug', $university_slug, 'university_vietnam'); // Thay 'university_vietnam' bằng taxonomy của bạn
        if ($university_term) {
            $university_name = $university_term->name;
        }
    }
} elseif ($post_type === 'laos') {
    $taxonomies = array(
        'year_laos' => $year,
        'province_laos' => $province,
        'university_laos' => $university_slug
    );

    if (!empty($university_slug)) {
        $university_term = get_term_by('slug', $university_slug, 'university_laos'); // Thay 'university_laos' bằng taxonomy của bạn
        if ($university_term) {
            $university_name = $university_term->name;
        }
    }
} elseif ($post_type === 'cambodia') {
    $taxonomies = array(
        'year_cambodia' => $year,
        'province_cambodia' => $province,
        'university_cambodia' => $university_slug
    );

    if (!empty($university_slug)) {
        $university_term = get_term_by('slug', $university_slug, 'university_cambodia'); // Thay 'university_cambodia' bằng taxonomy của bạn
        if ($university_term) {
            $university_name = $university_term->name;
        }
    }
}

// Tạo mảng args cho WP_Query
$args = array(
    'post_type' => $post_type,
    'posts_per_page' => -1,
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

// Thực hiện WP_Query
$query = new WP_Query($args);
?>
    <div id="content" class="page-list-job-filter">
        <?php
        if ($query->have_posts()) : ?>
            <div class="title_list_job_filter">
                <h4><?php echo $university_name . ' ' . $year; ?>
                </h4>
            </div>
            <div class="content_list_job_filter">
                <ul>
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
                                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>" style="max-width:100%; height:auto;">
                                <?php the_title(); ?>
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
        document.addEventListener('DOMContentLoaded', function () {
            const listItems = document.querySelectorAll('.page-list-job-filter li');

            listItems.forEach(item => {
                const hammer = new Hammer(item);

                hammer.on('swipeleft', function () {
                    window.location.href = item.querySelector('a').href;
                });
            });
        });
    </script>

<?php
wp_reset_postdata();
get_footer();
