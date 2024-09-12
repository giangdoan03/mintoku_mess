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
    /*
        * Make theme available for translation.
        * Translations can be filed in the /languages/ directory.
        * If you're building a theme based on mintokumobile, use a find and replace
        * to change 'mintokumobile' to the name of your theme in all the template files.
        */
    load_theme_textdomain('mintokumobile', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
        * Let WordPress manage the document title.
        * By adding theme support, we declare that this theme does not use a
        * hard-coded <title> tag in the document head, and expect WordPress to
        * provide it for us.
        */
    add_theme_support('title-tag');

    /*
        * Enable support for Post Thumbnails on posts and pages.
        *
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(
        array(
            'menu-1' => esc_html__('Primary', 'mintokumobile'),
        )
    );

    /*
        * Switch default core markup for search form, comment form, and comments
        * to output valid HTML5.
        */
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

    // Set up the WordPress core custom background feature.
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

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
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

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mintokumobile_content_width()
{
    $GLOBALS['content_width'] = apply_filters('mintokumobile_content_width', 640);
}

add_action('after_setup_theme', 'mintokumobile_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
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

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
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

function custom_rest_endpoint()
{
    register_rest_route('custom-api/v1', '/posts', array(
        'methods' => 'GET',
        'callback' => 'get_custom_posts',
    ));
}

function get_custom_posts()
{
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 5, // Number of posts to retrieve
    );

    $query = new WP_Query($args);
    $posts_data = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $posts_data[] = array(
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'featured_image' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
            );
        }
    }

    wp_reset_postdata();
    return $posts_data;
}

add_action('rest_api_init', 'custom_rest_endpoint');

// Thêm endpoint REST API để trả về URL ảnh
function my_custom_image_urls()
{
    // Lấy URL của ảnh placeholder từ thư mục chủ đề
    $placeholder_image = get_stylesheet_directory_uri() . '/images/placeholder-image.jpg';

    return new WP_REST_Response(array(
        'placeholder' => $placeholder_image
    ));
}

// Đăng ký endpoint REST API
add_action('rest_api_init', function () {
    register_rest_route('mytheme/v1', '/image-urls', array(
        'methods' => 'GET',
        'callback' => 'my_custom_image_urls',
    ));
});


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
        'rewrite' => array('slug' => 'vietnam'),
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
        'rewrite' => array('slug' => 'laos'),
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
        'rewrite' => array('slug' => 'cambodia'),
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
        'hierarchical' => true,
        'rewrite' => array('slug' => 'vietnam-provinces'),
    ));

    // Taxonomy cho Laos
    register_taxonomy('province_laos', 'laos', array(
        'labels' => array(
            'name' => __('Laos Provinces'),
            'singular_name' => __('Laos Province')
        ),
        'hierarchical' => true,
        'rewrite' => array('slug' => 'laos-provinces'),
    ));

    // Taxonomy cho Cambodia
    register_taxonomy('province_cambodia', 'cambodia', array(
        'labels' => array(
            'name' => __('Cambodia Provinces'),
            'singular_name' => __('Cambodia Province')
        ),
        'hierarchical' => true,
        'rewrite' => array('slug' => 'cambodia-provinces'),
    ));
}

add_action('init', 'create_province_taxonomies');


function create_year_taxonomy()
{

    // Đăng ký taxonomy cho Vietnam
    register_taxonomy('year_vietnam', 'vietnam', array(
        'label' => __('Year Vietnam', 'textdomain'),
        'rewrite' => array(
            'slug' => 'vietnam-year', // Điều chỉnh URL
            'with_front' => false,
        ),
        'hierarchical' => true,
    ));

    // Đăng ký taxonomy cho Laos
    register_taxonomy('year_laos', 'laos', array(
        'label' => __('Year Laos', 'textdomain'),
        'rewrite' => array(
            'slug' => 'laos-year', // Điều chỉnh URL
            'with_front' => false,
        ),
        'hierarchical' => false,
    ));

    // Đăng ký taxonomy cho Cambodia
    register_taxonomy('year_cambodia', 'cambodia', array(
        'label' => __('Year Cambodia', 'textdomain'),
        'rewrite' => array(
            'slug' => 'cambodia-year', // Điều chỉnh URL
            'with_front' => false,
        ),
        'hierarchical' => false,
    ));
}

add_action('init', 'create_year_taxonomy');


function filter_provinces_by_post_type($terms, $taxonomies, $args)
{
    if (!in_array('province', $taxonomies)) {
        return $terms;
    }

    $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';

    if (empty($post_type)) {
        return $terms;
    }

    $filtered_terms = array();
    foreach ($terms as $term) {
        $term_post_types = get_term_meta($term->term_id, 'associated_post_types', true);
        if (is_array($term_post_types) && in_array($post_type, $term_post_types)) {
            $filtered_terms[] = $term;
        }
    }

    return $filtered_terms;
}

add_filter('get_terms', 'filter_provinces_by_post_type', 10, 3);


function save_province_meta_data($term_id)
{
    if (isset($_POST['post_types'])) {
        $post_types = array_map('sanitize_text_field', $_POST['post_types']);
        update_term_meta($term_id, 'associated_post_types', $post_types);
    }
}

add_action('edited_province', 'save_province_meta_data');
add_action('create_province', 'save_province_meta_data');


function add_province_meta_box()
{
    add_meta_box(
        'province_meta_box',
        'Post Types',
        'render_province_meta_box',
        'province',
        'side',
        'default'
    );
}

add_action('admin_init', 'add_province_meta_box');

function render_province_meta_box($term)
{
    $post_types = get_term_meta($term->term_id, 'associated_post_types', true);
    $all_post_types = get_post_types(array('public' => true), 'objects');
    ?>
    <div id="post-types">
        <?php foreach ($all_post_types as $post_type) : ?>
            <input type="checkbox" name="post_types[]"
                   value="<?php echo esc_attr($post_type->name); ?>" <?php checked(in_array($post_type->name, (array)$post_types)); ?>>
            <?php echo esc_html($post_type->label); ?><br>
        <?php endforeach; ?>
    </div>
    <?php
}


// Hàm callback để hiển thị nội dung của meta box
function my_meta_box_callback($post)
{
    global $tab1_value, $tab2_value, $tab3_image_ids, $tab3_image_ids_string;

    // Thêm nonce field để bảo mật
    wp_nonce_field('my_meta_box_nonce_action', 'my_meta_box_nonce_name');

    // Lấy giá trị đã lưu trước đó
    $tab1_value = get_post_meta($post->ID, '_my_tab1_key', true);
    $tab2_value = get_post_meta($post->ID, '_my_tab2_key', true);
    // Lấy dữ liệu từ meta box
    $tab3_image_ids = get_post_meta($post->ID, '_my_tab3_image_ids', true);

    // Đảm bảo rằng $tab3_image_ids là một mảng
    if (!is_array($tab3_image_ids)) {
        $tab3_image_ids = array(); // Khởi tạo như một mảng rỗng nếu không phải mảng
    }

    // Chuyển đổi mảng thành chuỗi để hiển thị trong trường ẩn
    $tab3_image_ids_string = implode(',', $tab3_image_ids);

    // Bao gồm file template HTML
    include plugin_dir_path(__FILE__) . './metabox/meta-box-visa-template.php';
}


// Hàm callback để hiển thị nội dung của meta box
function my_additional_info_meta_box_callback($post)
{
    // Thêm nonce field để bảo mật
    wp_nonce_field('my_additional_info_meta_box_nonce_action', 'my_additional_info_meta_box_nonce_name');

    // Lấy giá trị đã lưu trước đó
    $additional_info = get_post_meta($post->ID, '_my_additional_info_key', true);

    // Hiển thị trình chỉnh sửa văn bản
    wp_editor($additional_info, 'my_additional_info_editor', array(
        'textarea_name' => 'my_additional_info_field',
        'media_buttons' => true, // Hiển thị nút tải lên media
        'teeny' => false,       // Hiển thị đầy đủ các tùy chọn
    ));
}


// Display salary meta box
function display_custom_salary_meta_box($post)
{
    $salary = get_post_meta($post->ID, get_post_type($post->ID) . '_salary', true);
    ?>
    <div class="custom-meta-box-content" id="<?php echo get_post_type($post->ID); ?>-salary-meta-box">
        <label for="job_salary">Salary:</label>
        <input type="text" id="job_salary" name="<?php echo get_post_type($post->ID); ?>_salary"
               value="<?php echo esc_attr($salary); ?>"/>
    </div>
    <?php
}


function display_custom_details_meta_box($post)
{
    $post_type = get_post_type($post);
    $selected_year = get_post_meta($post->ID, $post_type . '_year', true);

    ?>
    <div class="custom-meta-box-content" id="<?php echo $post_type; ?>-details-meta-box">
        <label for="<?php echo $post_type; ?>_year">Năm:</label><br>
        <?php
        $fixed_years = array(2024, 2025, 2026, 2027);
        foreach ($fixed_years as $year) {
            $checked_fixed_year = ($selected_year == $year) ? 'checked' : '';
            echo '<input type="radio" name="' . $post_type . '_year" value="' . $year . '" ' . $checked_fixed_year . '> ' . esc_html($year) . '<br>';
        }
        ?>
    </div>
    <?php
}


function display_custom_images_meta_box($post)
{
    $post_type = get_post_type($post);
    $images = get_post_meta($post->ID, $post_type . '_images', true);

    // Start output buffering
    ob_start();
    ?>
    <div class="custom-meta-box-content" id="<?php echo esc_attr($post_type); ?>-images-meta-box">
        <div id="<?php echo esc_attr($post_type); ?>-images-container"
             style="display: flex; flex-wrap: wrap; gap: 10px;">
            <?php
            if (!empty($images) && is_array($images)) {
                foreach ($images as $image) {
                    ?>
                    <div class="<?php echo esc_attr($post_type); ?>-image-item"
                         style="position: relative; display: inline-block;">
                        <img src="<?php echo esc_url($image); ?>"
                             style="max-width: 150px; height: auto; display: block;"/>
                        <input type="hidden" name="<?php echo esc_attr($post_type); ?>_images[]"
                               value="<?php echo esc_url($image); ?>"/>
                        <button class="remove-<?php echo esc_attr($post_type); ?>-image"
                                style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; cursor: pointer;">
                            Xóa
                        </button>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <button id="upload-<?php echo esc_attr($post_type); ?>-image-button" class="button">Upload Images</button>
    </div>
    <?php
    // Get the output buffer content and clean the buffer
    echo ob_get_clean();
}


function save_custom_meta_box_data($post_id)
{
    // Check permissions and auto-save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $post_type = get_post_type($post_id);

    // Save salary
    if (isset($_POST[$post_type . '_salary'])) {
        $salary = sanitize_text_field($_POST[$post_type . '_salary']);
        update_post_meta($post_id, $post_type . '_salary', $salary);
    } else {
        delete_post_meta($post_id, $post_type . '_salary');
    }

    $post_types = array('vietnam', 'laos', 'cambodia');

    foreach ($post_types as $post_type) {
        if (isset($_POST[$post_type . '_images'])) {
            update_post_meta($post_id, $post_type . '_images', $_POST[$post_type . '_images']);
        } else {
            delete_post_meta($post_id, $post_type . '_images');
        }
    }

    // Save provinces
    if (isset($_POST[$post_type . '_provinces'])) {
        $provinces = array_map('intval', $_POST[$post_type . '_provinces']);
        update_post_meta($post_id, $post_type . '_provinces', $provinces);
    } else {
        delete_post_meta($post_id, $post_type . '_provinces');
    }

    // Save countries
    if (isset($_POST[$post_type . '_countries'])) {
        $countries = array_map('intval', $_POST[$post_type . '_countries']);
        update_post_meta($post_id, $post_type . '_countries', $countries);
    } else {
        delete_post_meta($post_id, $post_type . '_countries');
    }

    foreach ($post_types as $post_type) {
        if (isset($_POST[$post_type . '_year'])) {
            $year = sanitize_text_field($_POST[$post_type . '_year']);
            update_post_meta($post_id, $post_type . '_year', $year);
        }
    }
    // Xử lý dữ liệu từ các tab
    if (isset($_POST['tab1_field'])) {
        $tab1_data = wp_kses_post($_POST['tab1_field']); // Xử lý dữ liệu từ trình soạn thảo văn bản
        update_post_meta($post_id, '_my_tab1_key', $tab1_data);
    }
    if (isset($_POST['tab2_field'])) {
        $tab2_data = sanitize_text_field($_POST['tab2_field']);
        update_post_meta($post_id, '_my_tab2_key', $tab2_data);
    }
    if (isset($_POST['tab3_image_ids'])) {
        $tab3_image_ids = array_map('intval', explode(',', $_POST['tab3_image_ids']));
        update_post_meta($post_id, '_my_tab3_image_ids', $tab3_image_ids);
    }
    // Xử lý và lưu dữ liệu
    if (isset($_POST['my_additional_info_field'])) {
        $additional_info = wp_kses_post($_POST['my_additional_info_field']);
        update_post_meta($post_id, '_my_additional_info_key', $additional_info);
    }
    // Lưu ID ảnh vào metadata
    $image_ids = isset($_POST['custom_image_ids']) ? array_map('intval', explode(',', $_POST['custom_image_ids'])) : [];

    update_post_meta($post_id, '_custom_image_ids', $image_ids);
}

add_action('save_post', 'save_custom_meta_box_data');


// Template hoặc shortcode để hiển thị danh sách post type
function display_post_types_list()
{
    $post_types = array('vietnam', 'laos', 'cambodia'); // Các post type cần liệt kê
    $output = '<ul>';

    foreach ($post_types as $post_type) {
        $post_type_obj = get_post_type_object($post_type);

        if ($post_type_obj) {
            // Tạo liên kết đến trang danh sách taxonomy tương ứng
            $output .= '<li><a href="' . esc_url(get_post_type_archive_link($post_type)) . '">' . esc_html($post_type_obj->label) . '</a></li>';
        }
    }

    $output .= '</ul>';

    return $output;
}

add_shortcode('post_types_list', 'display_post_types_list');


// filter by year
function custom_taxonomy_query($query)
{
    // Kiểm tra xem đây có phải là truy vấn chính trên trang taxonomy không
    if (!is_admin() && $query->is_main_query() && is_tax()) {
        // Lấy tên taxonomy hiện tại
        $current_taxonomy = get_queried_object()->taxonomy;

        // Xác định các taxonomy và các giá trị năm tương ứng
        $taxonomies = array(
            'province_vietnam' => 'year_vietnam',
            'province_laos' => 'year_laos',
            'province_cambodia' => 'year_cambodia',
        );

        // Kiểm tra nếu taxonomy hiện tại có trong danh sách
        if (array_key_exists($current_taxonomy, $taxonomies)) {
            $year_param = $taxonomies[$current_taxonomy];

            // Kiểm tra nếu có giá trị năm trong $_GET
            if (isset($_GET[$year_param]) && !empty($_GET[$year_param])) {
                $year = sanitize_text_field($_GET[$year_param]); // Làm sạch dữ liệu đầu vào

                // Thêm điều kiện meta query
                $meta_query = array(
                    array(
                        'key' => $year_param, // Tên custom field dựa trên taxonomy
                        'value' => $year, // Giá trị năm từ URL
                        'compare' => '=', // So sánh chính xác
                        'type' => 'NUMERIC', // Xác định kiểu dữ liệu
                    ),
                );

                // Đảm bảo rằng meta_query không bị xóa bởi các điều kiện mặc định của WordPress
                if (!isset($query->query_vars['meta_query'])) {
                    $query->set('meta_query', $meta_query);
                } else {
                    $query->query_vars['meta_query'][] = $meta_query;
                }
            }
        }
    }
}

add_action('pre_get_posts', 'custom_taxonomy_query');


function display_province_posts($post_type, $taxonomy_provinces, $fixed_years)
{
    // Lấy thông tin tỉnh thành từ URL
    $term = get_queried_object();

    // Kiểm tra xem có tỉnh thành không
    if (!$term || !is_a($term, 'WP_Term') || $term->taxonomy !== $taxonomy_provinces) {
        echo '<p>Không tìm thấy tỉnh thành.</p>';
        get_footer();
        exit;
    }

    // Hiển thị dropdown năm với các năm cố định
    $year_param = "{$post_type}_year";
    $selected_year = isset($_GET[$year_param]) ? sanitize_text_field($_GET[$year_param]) : '';

    ?>
    <form id="filter" method="GET" action="<?php echo esc_url(get_term_link($term, $taxonomy_provinces)); ?>">
        <label for="year-dropdown">Chọn Năm:</label>
        <select id="year-dropdown" name="<?php echo esc_attr($year_param); ?>"
                onchange="document.getElementById('filter').submit()">
            <option value="">Chọn năm</option>
            <?php foreach ($fixed_years as $year) : ?>
                <option value="<?php echo esc_attr($year); ?>" <?php selected($selected_year, $year); ?>>
                    <?php echo esc_html($year); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php
    // Query bài viết theo điều kiện đã chọn
    $args = array(
        'post_type' => $post_type,
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy_provinces,
                'field' => 'term_id',
                'terms' => $term->term_id,
            ),
        ),
        'meta_query' => array(),
        'posts_per_page' => -1, // Hiển thị tất cả các bài viết
    );

    // Nếu có năm được chọn, thêm điều kiện meta_query vào truy vấn
    if (!empty($selected_year)) {
        $args['meta_query'] = array(
            array(
                'key' => $year_param,
                'value' => $selected_year,
                'compare' => '=',
            ),
        );
    } else {
        // Nếu không có năm được chọn, chỉ cần hiển thị toàn bộ bài viết
        $args['meta_query'] = array(
            array(
                'key' => $year_param,
                'compare' => 'EXISTS',
            ),
        );
    }

    $query = new WP_Query($args);

    // Hiển thị bài viết
    if ($query->have_posts()) {
        echo '<div id="loading-spinner">';
        echo '<div class="spinner"></div>';
        echo '</div>';
        echo '<h1 class="title_list_job_province">Jobs tỉnh/thành ' . esc_html($term->name) . '</h1>';
        echo '<ul class="post-list scroll-container">';
        while ($query->have_posts()) {
            $query->the_post();
            $post_year = get_post_meta(get_the_ID(), $year_param, true);
            $display_year = !empty($post_year) ? esc_html($post_year) : 'N/A';
            $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://placehold.jp/3d4070/ffffff/200x200.png';
            $excerpt = get_the_excerpt(); // Lấy mô tả ngắn

            echo '<li class="post-item scroll-area">';
            echo '<div class="post-thumbnail"><img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '"></div>';
            echo '<div class="post-info">';
            echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a> <span class="label_year"> (' . $display_year . ')</span>';
            echo '<p class="post-excerpt">' . esc_html($excerpt) . '</p>'; // Hiển thị mô tả ngắn
            echo '</div>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Không tìm thấy bài viết nào cho tỉnh thành ' . esc_html($term->name) . '.</p>';
    }

    wp_reset_postdata();
}

function enqueue_custom_meta_box_scripts($hook_suffix)
{
    // Kiểm tra xem có phải là trang chỉnh sửa bài viết không
    if ($hook_suffix === 'post.php' || $hook_suffix === 'post-new.php') {
        // Đăng ký và đưa vào file JavaScript
        wp_enqueue_script(
            'custom-meta-box-tabs',
            get_template_directory_uri() . '/js/custom-meta-box-tabs.js', // Đảm bảo đường dẫn chính xác
            array('jquery'), // Phụ thuộc vào jQuery
            null,
            true
        );

        // Đăng ký và đưa vào file CSS nếu có
        wp_enqueue_style(
            'custom-meta-box-tabs-style',
            get_template_directory_uri() . '/css/custom-meta-box-tabs.css', // Đảm bảo đường dẫn chính xác
            array(),
            null
        );
    }
}

add_action('admin_enqueue_scripts', 'enqueue_custom_meta_box_scripts');


function my_enqueue_media_uploader()
{
    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'my_enqueue_media_uploader');


add_action('wp_ajax_remove_image', 'handle_remove_image');
function handle_remove_image()
{
    // Kiểm tra nonce và quyền truy cập
    if (!isset($_POST['image_id']) || !isset($_POST['post_id'])) {
        wp_send_json_error('Missing required parameters.');
    }

    $image_id = intval($_POST['image_id']);
    $post_id = intval($_POST['post_id']);

    // Lấy dữ liệu hiện tại từ meta box
    $image_ids = get_post_meta($post_id, '_my_tab3_image_ids', true);

    if (!is_array($image_ids)) {
        $image_ids = array();
    }

    // Xóa ảnh khỏi danh sách
    $image_ids = array_diff($image_ids, array($image_id));
    update_post_meta($post_id, '_my_tab3_image_ids', $image_ids);

    wp_send_json_success();
}

//add_meta_box để thêm metabox vào trang
function custom_image_metabox()
{
    add_meta_box(
        'custom_image_metabox_id',            // ID của metabox
        'Upload Ảnh',                         // Tiêu đề của metabox
        'render_custom_image_metabox',        // Hàm callback để render metabox
        ['post', 'page'],                     // Các post types mà metabox sẽ xuất hiện (có thể thêm 'custom_post_type')
        'side',                               // Vị trí của metabox ('normal', 'side', 'advanced')
        'default'                             // Độ ưu tiên của metabox ('default', 'high', 'low')
    );
}

add_action('add_meta_boxes', 'custom_image_metabox');

function render_custom_image_metabox($post)
{
    // Sử dụng nonce để bảo vệ dữ liệu
    wp_nonce_field(basename(__FILE__), 'custom_image_metabox_nonce');

    // Lấy dữ liệu hiện tại từ metadata (nếu có)
    $image_ids = get_post_meta($post->ID, '_custom_image_ids', true);

    // Ensure $image_ids is an array
    if (!is_array($image_ids)) {
        $image_ids = array(); // If $image_ids is not an array, make it an empty array
    }

    ?>
    <div id="custom-image-metabox-container">
        <input type="button" class="button" id="upload_images_button" value="Upload Ảnh">
        <ul id="image-preview">
            <?php
            if (!empty($image_ids)) {
                foreach ($image_ids as $image_id) {
                    echo '<li data-id="' . esc_attr($image_id) . '">';
                    echo wp_get_attachment_image($image_id, 'thumbnail');
                    echo '<a href="#" class="remove-image">Xóa</a>';
                    echo '</li>';
                }
            }
            ?>
        </ul>
        <input type="hidden" name="custom_image_ids" id="custom_image_ids"
               value="<?php echo esc_attr(implode(',', $image_ids)); ?>">
    </div>
    <?php
}

//hiển thị ảnh trên frontend bằng cách lấy dữ liệu từ metadata:
function display_custom_images($post_id)
{
    $image_ids = get_post_meta($post_id, '_custom_image_ids', true);

    if (!empty($image_ids)) {
        echo '<div class="custom_images_visa">';
        foreach ($image_ids as $image_id) {
            echo wp_get_attachment_image($image_id, 'full');
        }
        echo '</div>';
    }
}

function display_custom_images_ad_info($post_id)
{
    $image_ids = get_post_meta($post_id, '_my_additional_info_key', true);

    if (!empty($image_ids)) {
        echo '<div class="custom_images_info">';
        foreach ($image_ids as $image_id) {
            echo wp_get_attachment_image($image_id, 'full');
        }
        echo '</div>';
    }
}

add_action('after_setup_theme', 'my_custom_image_sizes');
function my_custom_image_sizes()
{
    add_image_size('thumbnail', 150, 150, true); // Ensure 'thumbnail' is defined
}


// Xử lý yêu cầu AJAX để tải trường đại học theo tỉnh
function load_universities()
{
    $province_id = isset($_POST['province_id']) ? intval($_POST['province_id']) : 0;
    $year = isset($_POST['year']) ? intval($_POST['year']) : 0; // Lấy year từ yêu cầu AJAX

    if ($province_id) {
        // Lấy các term con của tỉnh hiện tại (trường đại học)
        $args = array(
            'taxonomy' => 'province_vietnam', // Tên taxonomy cho tỉnh
            'hide_empty' => false,
            'parent' => $province_id, // Lọc các term con của tỉnh
        );

        $terms = get_terms($args);

        $university_options = '<option value="">Chọn trường đại học</option>';
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $university_options .= '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
            }
        } else {
            $university_options .= '<option value="">Không có trường đại học nào</option>';
        }

        // Truy vấn các job thuộc tỉnh đã chọn và lọc theo năm (year)
        $job_args = array(
            'post_type' => 'vietnam', // Loại bài viết là 'vietnam'
            'posts_per_page' => -1, // Lấy tất cả các job
            'tax_query' => array(
                'relation' => 'AND', // Tất cả các điều kiện phải đúng
                array(
                    'taxonomy' => 'province_vietnam', // Taxonomy của tỉnh
                    'field' => 'term_id',
                    'terms' => $province_id, // Lọc theo ID tỉnh
                ),
                // Thêm điều kiện lọc theo năm nếu có
                array(
                    'taxonomy' => 'year_vietnam',
                    'field' => 'slug', // Hoặc 'name' nếu bạn sử dụng tên term
                    'terms' => $year,
                    'operator' => 'IN'
                ),
// Nếu $year là số nguyên
//                array(
//                    'taxonomy' => 'year_vietnam',
//                    'field' => 'term_id',
//                    'terms' => $year,
//                    'operator' => 'IN'
//                ),
            ),
        );

        $job_query = new WP_Query($job_args);

        $job_list = '';
        if ($job_query->have_posts()) {
            $job_list .= '<ul>';
            while ($job_query->have_posts()) {
                $job_query->the_post();

                // Kiểm tra xem bài viết có ảnh đại diện hay không
                if (has_post_thumbnail()) {
                    $thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail', ['style' => 'width: 100px; height: 80px;']);
                } else {
                    $thumbnail = '<img src="https://placehold.jp/100x80.png" alt="Placeholder" style="width: 100px; height: 80px;">';
                }

                // Thêm ảnh đại diện vào danh sách bài viết
                $job_list .= '<li class="post-item">';
                $job_list .= '<a href="' . get_the_permalink() . '">';
                $job_list .= $thumbnail; // Hiển thị ảnh đại diện
                $job_list .= get_the_title(); // Hiển thị tiêu đề bài viết
                $job_list .= '</a>';
                $job_list .= '</li>';
            }
            $job_list .= '</ul>';
        } else {
            $job_list .= '<p>Không có job nào thuộc tỉnh này và năm đã chọn.</p>';
        }



        wp_reset_postdata();

        // Trả về response dạng JSON
        echo json_encode(array(
            'universities' => $university_options,
            'jobs' => $job_list
        ));
    }

    wp_die(); // Kết thúc yêu cầu AJAX
}

add_action('wp_ajax_load_universities', 'load_universities');
add_action('wp_ajax_nopriv_load_universities', 'load_universities');


function filter_posts_by_university()
{
    $university_id = isset($_POST['university_id']) ? intval($_POST['university_id']) : 0;
    $current_year = isset($_POST['year']) ? sanitize_text_field($_POST['year']) : '';

    if ($university_id && $current_year) {
        // Lấy các bài viết thuộc trường đại học và năm hiện tại
        $args = array(
            'post_type' => 'vietnam', // Hoặc post type của bạn nếu khác
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'province_vietnam', // Tên taxonomy cho trường đại học
                    'field' => 'term_id',
                    'terms' => $university_id,
                ),
                array(
                    'taxonomy' => 'year_vietnam', // Tên taxonomy cho năm
                    'field' => 'slug', // hoặc 'term_id' nếu bạn dùng ID
                    'terms' => $current_year,
                ),
            ),
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $output = '<ul>';
            while ($query->have_posts()) {
                $query->the_post();

                // Kiểm tra xem bài viết có ảnh đại diện hay không
                if (has_post_thumbnail()) {
                    $thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail', ['style' => 'width: 100px; height: 80px;']);
                } else {
                    $thumbnail = '<img src="https://placehold.jp/100x80.png" alt="Placeholder" style="width: 100px; height: 80px;">';
                }

                // Thêm ảnh đại diện và tiêu đề bài viết
                $output .= '<li class="post-item">';
                $output .= '<a href="' . get_permalink() . '">';
                $output .= $thumbnail; // Hiển thị ảnh đại diện
                $output .= get_the_title(); // Hiển thị tiêu đề bài viết
                $output .= '</a>';
                $output .= '</li>';
            }
            $output .= '</ul>';
            wp_reset_postdata();
        } else {
            $output = '<p>Không có bài viết nào.</p>';
        }


        echo $output;
    }

    wp_die(); // Kết thúc yêu cầu AJAX
}

add_action('wp_ajax_filter_posts_by_university', 'filter_posts_by_university');
add_action('wp_ajax_nopriv_filter_posts_by_university', 'filter_posts_by_university');


// Thêm vào functions.php
function add_custom_query_vars($vars)
{
    $vars[] = 'year';
    return $vars;
}

add_filter('query_vars', 'add_custom_query_vars');


// Hàm trả về danh sách province (taxonomy cấp 0)
add_action('wp_ajax_get_province_taxonomy', 'get_province_taxonomy');
add_action('wp_ajax_nopriv_get_province_taxonomy', 'get_province_taxonomy');
function get_province_taxonomy() {
    $post_type = sanitize_text_field($_GET['post_type']);
    $taxonomy = 'province_' . $post_type;

    if (!taxonomy_exists($taxonomy)) {
        wp_send_json_error(array('error' => 'Invalid taxonomy.'));
        return;
    }

    $args = array(
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
        'parent'     => 0, // Chỉ lấy các term cấp 0
    );

    $provinces = get_terms($args);

    if (is_wp_error($provinces)) {
        wp_send_json_error($provinces->get_error_messages());
    } else {
        wp_send_json($provinces);
    }
}

add_action('wp_ajax_get_university_taxonomy', 'get_university_taxonomy');
add_action('wp_ajax_nopriv_get_university_taxonomy', 'get_university_taxonomy');
function get_university_taxonomy() {
    $province_slug = sanitize_text_field($_GET['province_slug']);
    $taxonomy = 'province_vietnam'; // Taxonomy cấp 0

    if (!taxonomy_exists($taxonomy)) {
        wp_send_json_error(array('error' => 'Invalid taxonomy.'));
        return;
    }

    // Tìm term ID từ slug
    $term = get_term_by('slug', $province_slug, $taxonomy);
    if (!$term) {
        wp_send_json_error(array('error' => 'Invalid province slug.'));
        return;
    }
    $province_id = $term->term_id;

    // Lấy term cấp 1 từ taxonomy cấp 0
    $args = array(
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
        'parent'     => $province_id, // Lấy các term cấp 1
    );

    $universities = get_terms($args);

    if (is_wp_error($universities)) {
        wp_send_json_error(array('error' => $universities->get_error_messages()));
    } else {
        // Chuyển đổi dữ liệu thành slug và tên
        $result = array_map(function($university) {
            return array(
                'slug' => $university->slug,
                'name' => $university->name,
            );
        }, $universities);
        wp_send_json($result);
    }
}


function custom_frontend_query_group_by_year( $query ) {
    // Kiểm tra nếu không phải trang admin, là truy vấn chính và đang tìm kiếm post type vietnam
    if ( !is_admin() && $query->is_main_query() && is_post_type_archive('vietnam') ) {

        // Lấy giá trị 'province_vietnam' từ URL nếu có
        if ( isset( $_GET['university'] ) && !empty( $_GET['university'] ) ) {
            $province_slug = sanitize_text_field( $_GET['university'] );

            // Thêm điều kiện meta_query cho province_vietnam
            $meta_query = array(
                array(
                    'key'     => 'province_vietnam',
                    'value'   => $province_slug,
                    'compare' => '=',
                )
            );

            // Áp dụng meta_query vào truy vấn
            $query->set( 'meta_query', $meta_query );
            $query->set( 'posts_per_page', -1 ); // Lấy tất cả bài viết không giới hạn
        }
    }
}
add_action( 'pre_get_posts', 'custom_frontend_query_group_by_year' );

function display_grouped_by_year() {
    // Thực hiện WP_Query để lấy tất cả các bài viết
    $args = array(
        'post_type' => 'vietnam',
        'posts_per_page' => -1, // Lấy tất cả bài viết
        'meta_key' => 'year_vietnam', // Lấy bài viết theo custom field 'year_vietnam'
        'orderby' => 'meta_value_num', // Sắp xếp theo giá trị của custom field
        'order' => 'ASC', // Sắp xếp theo thứ tự tăng dần
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        $posts_by_year = array();

        // Nhóm các bài viết theo năm
        while ( $query->have_posts() ) {
            $query->the_post();
            $year = get_post_meta( get_the_ID(), 'year_vietnam', true );

            // Nếu chưa có nhóm cho năm này, khởi tạo mảng
            if ( ! isset( $posts_by_year[ $year ] ) ) {
                $posts_by_year[ $year ] = array();
            }

            // Thêm bài viết vào nhóm của năm hiện tại
            $posts_by_year[ $year ][] = get_the_ID();
        }

        // Hiển thị bài viết đã được nhóm theo năm
        foreach ( $posts_by_year as $year => $posts ) {
            echo '<h2>Năm ' . esc_html( $year ) . '</h2>';
            echo '<ul>';
            foreach ( $posts as $post_id ) {
                echo '<li><a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a></li>';
            }
            echo '</ul>';
        }

        wp_reset_postdata(); // Reset post data sau khi vòng lặp kết thúc
    } else {
        echo '<p>Không có bài viết nào.</p>';
    }
}


// Modify the query based on URL parameters on frontend
function custom_frontend_query_province( $query ) {
    // Kiểm tra nếu không phải trang admin, là truy vấn chính và đang tìm kiếm
    if ( !is_admin() && $query->is_main_query() && is_post_type_archive('vietnam') ) {

        // Lấy giá trị 'province_vietnam' từ URL nếu có
        if ( isset( $_GET['university'] ) && !empty( $_GET['university'] ) ) {
            $province_slug = sanitize_text_field( $_GET['university'] ); // Làm sạch dữ liệu đầu vào

            // Thêm điều kiện cho province_vietnam dựa trên slug
            $meta_query = array(
                array(
                    'key'     => 'province_vietnam', // Custom field 'province_vietnam'
                    'value'   => $province_slug,
                    'compare' => '=', // So sánh chính xác
                )
            );

            // Áp dụng meta_query vào truy vấn
            $query->set( 'meta_query', $meta_query );
        }
    }
}
add_action( 'pre_get_posts', 'custom_frontend_query_province' );
























