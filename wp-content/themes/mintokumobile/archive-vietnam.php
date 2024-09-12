<?php
get_header();

$province = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
$university = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';

// Thiết lập điều kiện query
$args = array(
    'post_type' => 'vietnam',  // Hoặc post type tương ứng
    'posts_per_page' => -1,    // Hiển thị tất cả các job
);

// Nếu có province, thêm điều kiện lọc theo taxonomy province
if ($province) {
    $args['tax_query'][] = array(
        'taxonomy' => 'province_vietnam',
        'field'    => 'slug',
        'terms'    => $province,
    );
}

// Nếu có university, thêm điều kiện lọc theo taxonomy university
if ($university) {
    $args['tax_query'][] = array(
        'taxonomy' => 'university_vietnam',  // Thay bằng taxonomy tương ứng của post type
        'field'    => 'slug',
        'terms'    => $university,
    );
}

$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        get_template_part('template-parts/content', get_post_type());
    endwhile;
else :
    echo '<p>Không tìm thấy job nào.</p>';
endif;

wp_reset_postdata();
get_footer();
?>
