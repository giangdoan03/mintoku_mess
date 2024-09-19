<?php
/**
 * Template Name: Danh Sách Công Việc
 */

get_header();

// Lấy các tham số từ URL
$year = isset($_GET['year_r']) ? sanitize_text_field($_GET['year_r']) : '';
$post_type = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
$province = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
$university = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';

// Kiểm tra và thiết lập taxonomy tương ứng với post_type
$taxonomies = array();

if ($post_type === 'vietnam') {
    $taxonomies = array(
        'year_vietnam' => $year,
        'province_vietnam' => $province,
        'university_vietnam' => $university
    );
} elseif ($post_type === 'laos') {
    $taxonomies = array(
        'year_laos' => $year,
        'province_laos' => $province,
        'university_laos' => $university
    );
} elseif ($post_type === 'cambodia') {
    $taxonomies = array(
        'year_cambodia' => $year,
        'province_cambodia' => $province,
        'university_cambodia' => $university
    );
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
    <div class="page-list-job-filter">
        <?php
        if ($query->have_posts()) : ?>
            <div class="title_list_job_filter">
                <h4 data-translate="title_list_job_filter">Danh sách công việc<?php
                    if (!empty($year)) {
                        echo ' năm ' . esc_html($year);
                    }
                    if (!empty($university)) {
                        echo ' của trường ' . esc_html($university);
                    }
                    ?>
                </h4>
            </div>
            <div class="content_list_job_filter">
                <ul>
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php else : ?>
            <p>Không có công việc nào phù hợp.</p>
        <?php endif; ?>
    </div>

    <script>

        async function fetchTranslations() {
            try {
                const response = await fetch('<?php echo get_template_directory_uri(); ?>/js/translations.json');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            } catch (error) {
                console.error('Error fetching translations:', error);
            }
        }

        async function setLanguage(language) {
            try {
                const translations = await fetchTranslations();
                document.querySelectorAll('[data-translate]').forEach(element => {
                    const key = element.getAttribute('data-translate');
                    if (translations[language] && translations[language][key]) {
                        element.textContent = translations[language][key];
                    }
                });
            } catch (error) {
                console.error('Error setting language:', error);
            }
        }
        jQuery(document).ready(function ($) {
            const language = '<?php echo ICL_LANGUAGE_CODE; ?>'; // PHP variable
            setLanguage(language);
        })
    </script>
<?php
wp_reset_postdata();
get_footer();
