<?php get_header(); ?>

<div class="archive-container">
    <?php
    $post_type = get_post_type(); // Lấy post type hiện tại

    var_dump($post_type); // Kiểm tra giá trị của $post_type

    if (is_post_type_archive(['vietnam', 'laos', 'cambodia'])) {
        // Hiển thị tiêu đề cho post type
        echo '<h1>' . post_type_archive_title('', false) . '</h1>';

        // Lấy danh sách các taxonomy (year_) tương ứng với post type hiện tại
        $taxonomy_map = array(
            'vietnam' => 'year_vietnam',
            'laos' => 'year_laos',
            'cambodia' => 'year_cambodia',
        );

        $year_taxonomy = isset($taxonomy_map[$post_type]) ? $taxonomy_map[$post_type] : '';

        if ($year_taxonomy) {
            // Lấy các terms trong taxonomy year_ tương ứng
            $terms = get_terms(array(
                'taxonomy' => $year_taxonomy,
                'hide_empty' => false,
            ));

            if (!empty($terms) && !is_wp_error($terms)) {
                echo '<ul class="year-list">';
                foreach ($terms as $term) {
                    // Lấy số lượng bài viết thuộc về term hiện tại
                    $term_post_count = new WP_Query(array(
                        'post_type' => $post_type,
                        'tax_query' => array(
                            array(
                                'taxonomy' => $year_taxonomy,
                                'field' => 'term_id',
                                'terms' => $term->term_id,
                            ),
                        ),
                    ));

                    // Đếm số lượng bài viết
                    $post_count = $term_post_count->found_posts;

                    // Hiển thị năm và số lượng công việc
                    echo '<li><a href="' . get_term_link($term) . '">' . esc_html($term->name) . ' - (' . $post_count . ' job' . ($post_count > 1 ? 's' : '') . ')</a></li>';

                    // Reset WP Query
                    wp_reset_postdata();
                }
                echo '</ul>';
            } else {
                echo '<p>' . __('Không có năm nào để hiển thị.', 'textdomain') . '</p>';
            }
        }
    } else {
        echo '<p>' . __('Post type không được hỗ trợ.', 'textdomain') . '</p>';
    }
    ?>
</div>

<?php get_footer(); ?>
