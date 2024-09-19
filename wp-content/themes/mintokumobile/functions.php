<?php
/**
 * mintokumobile functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package mintokumobile
 */

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function mintokumobile_setup()
{

    load_theme_textdomain('mintokumobile', get_template_directory() . '/languages');

    add_theme_support('automatic-feed-links');

    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');

    register_nav_menus(
        array(
            'menu-1' => esc_html__('Primary', 'mintokumobile'),
        )
    );

    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );
    add_theme_support(
        'custom-background',
        apply_filters(
            'mintokumobile_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    add_theme_support(
        'custom-logo',
        array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        )
    );
}

add_action('after_setup_theme', 'mintokumobile_setup');

function mintokumobile_content_width()
{
    $GLOBALS['content_width'] = apply_filters('mintokumobile_content_width', 640);
}

add_action('after_setup_theme', 'mintokumobile_content_width', 0);


function mintokumobile_widgets_init()
{
    register_sidebar(
        array(
            'name' => esc_html__('Sidebar', 'mintokumobile'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here.', 'mintokumobile'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );
}

add_action('widgets_init', 'mintokumobile_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function mintokumobile_scripts()
{
    wp_enqueue_style('mintokumobile-style', get_stylesheet_uri(), array(), _S_VERSION);
    wp_style_add_data('mintokumobile-style', 'rtl', 'replace');

    wp_enqueue_script('mintokumobile-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'mintokumobile_scripts');


require get_template_directory() . '/inc/custom-header.php';


require get_template_directory() . '/inc/template-tags.php';


require get_template_directory() . '/inc/template-functions.php';

require get_template_directory() . '/inc/customizer.php';


if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

function enqueue_swiper_scripts()
{

    wp_enqueue_style('swiper-css', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.2.0/css/swiper.min.css');
    wp_enqueue_style('style-css', get_template_directory_uri() . '/css/style.css');
//    wp_enqueue_style('style-css-swiper', get_template_directory_uri() . '/css/swiper.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js');
    wp_enqueue_script('swiper-js', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.2.0/js/swiper.min.js');
    wp_enqueue_script('my-ajax-script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);

    // Truyền ajaxurl vào script
    wp_localize_script('my-ajax-script', 'myAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
    ));
}

add_action('wp_enqueue_scripts', 'enqueue_swiper_scripts');




// Đăng ký custom post types cho ba quốc gia
function create_custom_post_types()
{
    // Vietnam
    register_post_type('vietnam', array(
        'labels' => array(
            'name' => __('Vietnam Jobs'),
            'singular_name' => __('Vietnam Jobs')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'vietnam', 'with_front' => true),
//        'supports' => array('title', 'editor', 'thumbnail'),
        'supports' => array('title', 'thumbnail'),
    ));

    // Laos
    register_post_type('laos', array(
        'labels' => array(
            'name' => __('Laos Jobs'),
            'singular_name' => __('Laos Jobs')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'laos', 'with_front' => true),
        'supports' => array('title', 'editor', 'thumbnail'),
    ));

    // Cambodia
    register_post_type('cambodia', array(
        'labels' => array(
            'name' => __('Cambodia Jobs'),
            'singular_name' => __('Cambodia Jobs')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'cambodia', 'with_front' => true),
        'supports' => array('title', 'editor', 'thumbnail'),
    ));
}

add_action('init', 'create_custom_post_types');

// Đăng ký taxonomy riêng cho mỗi quốc gia
function create_province_taxonomies()
{
    // Taxonomy cho Vietnam
    register_taxonomy('province_vietnam', 'vietnam', array(
        'labels' => array(
            'name' => __('Vietnam Provinces'),
            'singular_name' => __('Vietnam Province')
        ),
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'province', 'with_front' => true),
    ));

    // Taxonomy cho Laos
    register_taxonomy('province_laos', 'laos', array(
        'labels' => array(
            'name' => __('Laos Provinces'),
            'singular_name' => __('Laos Province')
        ),
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'province', 'with_front' => true),
    ));

    // Taxonomy cho Cambodia
    register_taxonomy('province_cambodia', 'cambodia', array(
        'labels' => array(
            'name' => __('Cambodia Provinces'),
            'singular_name' => __('Cambodia Province')
        ),
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'province', 'with_front' => true),
    ));
}

add_action('init', 'create_province_taxonomies');


function create_year_taxonomy()
{

    // Đăng ký taxonomy cho Vietnam
    register_taxonomy('year_vietnam', 'vietnam', array(
        'label' => __('Year Vietnam', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'year_r', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => true,
    ));

    // Đăng ký taxonomy cho Laos
    register_taxonomy('year_laos', 'laos', array(
        'label' => __('Year Laos', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'year_r', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => false,
    ));

    // Đăng ký taxonomy cho Cambodia
    register_taxonomy('year_cambodia', 'cambodia', array(
        'label' => __('Year Cambodia', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'year_r', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => false,
    ));
}

add_action('init', 'create_year_taxonomy');



function create_university_taxonomy()
{

    // Đăng ký taxonomy cho Vietnam
    register_taxonomy('university_vietnam', 'vietnam', array(
        'label' => __('University Vietnam', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'university', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => true,
    ));

    // Đăng ký taxonomy cho Laos
    register_taxonomy('university_laos', 'laos', array(
        'label' => __('University Laos', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'university', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => false,
    ));

    // Đăng ký taxonomy cho Cambodia
    register_taxonomy('university_cambodia', 'cambodia', array(
        'label' => __('University Cambodia', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'university', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => false,
    ));
}

add_action('init', 'create_university_taxonomy');



// Thêm vào functions.php
add_filter('query_vars', 'add_custom_query_vars');
function add_custom_query_vars($vars) {
    $vars[] = 'year_r';
    $vars[] = 'province_vietnam';
    $vars[] = 'university';
    return $vars;
}


// Xử lý AJAX tìm kiếm bài viết
// Xử lý Ajax để nạp dữ liệu cho các trường chọn (province, university, year)
function ajax_load_filters() {
    $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';

    if ($post_type === 'vietnam') {
        // Lấy tất cả tỉnh, trường đại học, và năm cho post_type là 'vietnam'
        $provinces = get_terms(array(
            'taxonomy' => 'province_vietnam',
            'hide_empty' => false,
        ));

        $universities = get_terms(array(
            'taxonomy' => 'university_vietnam',
            'hide_empty' => false,
        ));

        $years = get_terms(array(
            'taxonomy' => 'year_vietnam',
            'hide_empty' => false,
        ));

        wp_send_json_success(array(
            'provinces' => $provinces,
            'universities' => $universities,
            'year_r' => $years,
        ));
    } else {
        wp_send_json_error('Không có dữ liệu.');
    }

    wp_die();
}
add_action('wp_ajax_nopriv_load_filters', 'ajax_load_filters');
add_action('wp_ajax_load_filters', 'ajax_load_filters');

// Xử lý Ajax để tìm kiếm bài viết theo các điều kiện
function ajax_search_posts() {
    // Lấy các tham số từ URL
    $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'vietnam';
    $province_slug = isset($_GET['province_slug']) ? sanitize_text_field($_GET['province_slug']) : '';
    $university_slug = isset($_GET['university_slug']) ? sanitize_text_field($_GET['university_slug']) : '';
    $year_slug = isset($_GET['year_slug']) ? sanitize_text_field($_GET['year_slug']) : '';


//    var_dump($post_type,$province_slug,$university_slug,$year_slug);
//    die();
    // Kiểm tra xem post_type có hợp lệ không
    if (!in_array($post_type, array('vietnam', 'laos', 'cambodia'))) {
        wp_send_json_error('Post type không hợp lệ.');
        wp_die();
    }

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'tax_query' => array(
            'relation' => 'AND',
        ),
    );

    if (!empty($province_slug)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'province_vietnam',
            'field' => 'slug',
            'terms' => $province_slug,
        );
    }

    if (!empty($university_slug)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'university_vietnam',
            'field' => 'slug',
            'terms' => $university_slug,
        );
    }

    if (!empty($year_slug)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'year_vietnam',
            'field' => 'slug',
            'terms' => $year_slug,
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $posts = array();
        while ($query->have_posts()) {
            $query->the_post();
            $posts[] = array(
                'title' => get_the_title(),
                'link' => get_permalink(),
            );
        }
        wp_send_json_success($posts);
    } else {
        wp_send_json_error('Không có bài viết nào.');
    }

    wp_die();
}
add_action('wp_ajax_nopriv_search_posts', 'ajax_search_posts');
add_action('wp_ajax_search_posts', 'ajax_search_posts');


function search_jobs() {
    $post_type = sanitize_text_field($_GET['region']);
    $province = sanitize_text_field($_GET['province']);
    $university = sanitize_text_field($_GET['university']);
    $year = sanitize_text_field($_GET['year_r']);
    $search_query = sanitize_text_field($_GET['search_query']);

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        's' => $search_query,
        'tax_query' => array(
            'relation' => 'AND',
        ),
    );

    if ($post_type === 'vietnam') {
        if (!empty($province)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'province_vietnam',
                'field' => 'slug',
                'terms' => $province,
            );
        }
        if (!empty($university)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'university_vietnam',
                'field' => 'slug',
                'terms' => $university,
            );
        }
        if (!empty($year)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'year_vietnam',
                'field' => 'slug',
                'terms' => $year,
            );
        }
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $posts = array();
        $province_from_url = sanitize_text_field($_GET['province']); // Lấy giá trị từ URL
        $post_type = sanitize_text_field($_GET['region']); // Lấy giá trị từ URL
        while ($query->have_posts()) {
            $query->the_post();
            $post_universities = wp_get_post_terms(get_the_ID(), 'university_vietnam', array("fields" => "names")); // Lấy tên university
            $post_university_slugs = wp_get_post_terms(get_the_ID(), 'university_vietnam', array("fields" => "slugs")); // Lấy slug university
            $post_years = wp_get_post_terms(get_the_ID(), 'year_vietnam', array("fields" => "names")); // Lấy tên năm
            $post_provinces = wp_get_post_terms(get_the_ID(), 'province_vietnam', array("fields" => "slugs")); // Lấy tên năm
            $posts[] = array(
                'title' => get_the_title(),
                'link' => get_permalink(),
                'university' => $post_universities[0], // Lấy tên university đầu tiên
                'university_slug' => $post_university_slugs[0], // Lấy slug university đầu tiên
                'year_vietnam' => $post_years, // Trả về danh sách year_vietnam
                'province' => $province_from_url,
                'region' => $post_type,
            );
        }
        wp_send_json_success($posts);
    }

    wp_send_json_error();


}
add_action('wp_ajax_search_jobs', 'search_jobs');
add_action('wp_ajax_nopriv_search_jobs', 'search_jobs');



function get_taxonomy_terms() {
    // Sanitize and retrieve the parameters
    $post_type = sanitize_text_field($_GET['region']);

    $selected_province = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
    $selected_university = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';
    $selected_year = isset($_GET['year_r']) ? sanitize_text_field($_GET['year_r']) : '';


    $taxonomy_data = array();

    if ($post_type === 'vietnam') {
        // Get province, university, and year taxonomies for Vietnam
        $taxonomy_data['provinces'] = get_terms(array(
            'taxonomy' => 'province_vietnam',
            'hide_empty' => false
        ));
        $taxonomy_data['universities'] = get_terms(array(
            'taxonomy' => 'university_vietnam',
            'hide_empty' => false
        ));
        $taxonomy_data['years'] = get_terms(array(
            'taxonomy' => 'year_vietnam',
            'hide_empty' => false
        ));

        // Filter provinces if a specific province is selected
        if ($selected_province) {
            $taxonomy_data['provinces'] = array_filter($taxonomy_data['provinces'], function($term) use ($selected_province) {
                return $term->slug === $selected_province;
            });
        }

        // Filter universities if a specific university is selected
        if ($selected_university) {
            $taxonomy_data['universities'] = array_filter($taxonomy_data['universities'], function($term) use ($selected_university) {
                return $term->slug === $selected_university;
            });
        }

        // Filter years if a specific year is selected
        if ($selected_year) {
            $taxonomy_data['years'] = array_filter($taxonomy_data['years'], function($term) use ($selected_year) {
                return $term->slug === $selected_year;
            });
        }
    }

    if (!empty($taxonomy_data)) {
        wp_send_json_success($taxonomy_data);
    } else {
        wp_send_json_error();
    }
}

add_action('wp_ajax_get_taxonomy_terms', 'get_taxonomy_terms');
add_action('wp_ajax_nopriv_get_taxonomy_terms', 'get_taxonomy_terms');


































