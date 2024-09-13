<?php
get_header(); // Gọi header của theme

// Lấy tham số university và year từ URL
$university_slug = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';
$year_slug = isset($_GET['year']) ? sanitize_text_field($_GET['year']) : '';

// Câu lệnh truy vấn cho WP_Query
$args = array(
    'post_type' => 'vietnam', // Hoặc tên post type của bạn
    'posts_per_page' => -1,
    'post_status' => 'publish',
);

$tax_query = array('relation' => 'AND');

if ($university_slug) {
    // Lấy term của trường đại học trong taxonomy
    $university_term = get_term_by('slug', $university_slug, 'province_vietnam');

    if ($university_term) {
        // Truy vấn bài viết thuộc trường đại học
        $tax_query[] = array(
            'taxonomy' => 'province_vietnam',
            'field'    => 'slug',
            'terms'    => $university_term->slug,
            'operator' => 'IN',
        );
    }
}

if ($year_slug) {
    // Lấy term của năm trong taxonomy
    $year_term = get_term_by('slug', $year_slug, 'year_vietnam');

    if ($year_term) {
        // Thêm điều kiện lọc theo năm
        $tax_query[] = array(
            'taxonomy' => 'year_vietnam',
            'field'    => 'slug',
            'terms'    => $year_term->slug,
            'operator' => 'IN',
        );
    }
}

if (!empty($tax_query)) {
    $args['tax_query'] = $tax_query;
}

// Thực hiện truy vấn
$query = new WP_Query($args);

// Lấy tất cả các term từ taxonomy 'year_vietnam'
$years = get_terms(array(
    'taxonomy' => 'year_vietnam',
    'hide_empty' => false,
));

$grouped_posts = array();

if ($years && !is_wp_error($years)) {
    // Khởi tạo mảng nhóm bài viết theo năm
    foreach ($years as $year) {
        $grouped_posts[$year->slug] = array();
    }

    // Phân loại các bài viết theo năm
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_year_terms = wp_get_post_terms(get_the_ID(), 'year_vietnam');

            foreach ($post_year_terms as $term) {
                if (isset($grouped_posts[$term->slug])) {
                    $grouped_posts[$term->slug][] = get_post();
                }
            }
        }
    }

    // Xóa các nhóm năm không có bài viết
    foreach ($grouped_posts as $year_slug => $posts) {
        if (empty($posts)) {
            unset($grouped_posts[$year_slug]);
        }
    }
}
?>

<div class="search-results">
    <?php if ($grouped_posts) : ?>
        <h2>Kết quả tìm kiếm</h2>
        <?php foreach ($grouped_posts as $year_slug => $posts) : ?>
            <h3><?php echo ucfirst($year_slug); ?></h3>
            <ul>
                <?php foreach ($posts as $post) : setup_postdata($post); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <p><?php the_excerpt(); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Không tìm thấy kết quả nào.</p>
    <?php endif; ?>
</div>

<?php
wp_reset_postdata(); // Đặt lại dữ liệu post
get_footer(); // Gọi footer của theme
?>
