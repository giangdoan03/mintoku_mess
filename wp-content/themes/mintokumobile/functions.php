<?php
/**
 * mintokumobile functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package mintokumobile
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function mintokumobile_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on mintokumobile, use a find and replace
		* to change 'mintokumobile' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'mintokumobile', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'mintokumobile' ),
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
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'mintokumobile_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mintokumobile_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mintokumobile_content_width', 640 );
}
add_action( 'after_setup_theme', 'mintokumobile_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mintokumobile_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'mintokumobile' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'mintokumobile' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'mintokumobile_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function mintokumobile_scripts() {
	wp_enqueue_style( 'mintokumobile-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'mintokumobile-style', 'rtl', 'replace' );

	wp_enqueue_script( 'mintokumobile-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mintokumobile_scripts' );

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
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

function enqueue_swiper_scripts() {

    wp_enqueue_style('swiper-css', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.2.0/css/swiper.min.css');
    wp_enqueue_style('style-css', get_template_directory_uri() . '/css/style.css');
//    wp_enqueue_style('style-css-swiper', get_template_directory_uri() . '/css/swiper.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js');
    wp_enqueue_script('swiper-js', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.2.0/js/swiper.min.js');
//    wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/script.js');
}
add_action('wp_enqueue_scripts', 'enqueue_swiper_scripts');

function custom_rest_endpoint() {
    register_rest_route('custom-api/v1', '/posts', array(
        'methods' => 'GET',
        'callback' => 'get_custom_posts',
    ));
}

function get_custom_posts() {
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
function my_custom_image_urls() {
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
function create_custom_post_types() {
    // Vietnam
    register_post_type('vietnam', array(
        'labels' => array(
            'name' => __('Vietnam Jobs'),
            'singular_name' => __('Vietnam Jobs')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'vietnam'),
        'supports' => array('title', 'editor', 'thumbnail'),
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
function create_province_taxonomies() {
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


function create_year_taxonomy() {
    register_taxonomy('year', array('vietnam', 'laos', 'cambodia'), array(
        'labels' => array(
            'name' => __('Years'),
            'singular_name' => __('Year')
        ),
        'hierarchical' => false,
        'rewrite' => array('slug' => 'year'),
    ));
}
add_action('init', 'create_year_taxonomy');




function filter_provinces_by_post_type($terms, $taxonomies, $args) {
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



function save_province_meta_data($term_id) {
    if (isset($_POST['post_types'])) {
        $post_types = array_map('sanitize_text_field', $_POST['post_types']);
        update_term_meta($term_id, 'associated_post_types', $post_types);
    }
}
add_action('edited_province', 'save_province_meta_data');
add_action('create_province', 'save_province_meta_data');



function add_province_meta_box() {
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

function render_province_meta_box($term) {
    $post_types = get_term_meta($term->term_id, 'associated_post_types', true);
    $all_post_types = get_post_types(array('public' => true), 'objects');
    ?>
    <div id="post-types">
        <?php foreach ($all_post_types as $post_type) : ?>
            <input type="checkbox" name="post_types[]" value="<?php echo esc_attr($post_type->name); ?>" <?php checked(in_array($post_type->name, (array) $post_types)); ?>>
            <?php echo esc_html($post_type->label); ?><br>
        <?php endforeach; ?>
    </div>
    <?php
}


// Add meta boxes
function add_custom_meta_box() {
    $post_types = array('vietnam', 'laos', 'cambodia'); // List of post types

    foreach ($post_types as $post_type) {
        add_meta_box(
            $post_type . '_salary_meta_box', // ID of meta box
            'Salary', // Title of meta box
            'display_custom_salary_meta_box', // Function to display meta box
            $post_type, // Custom post type
            'normal', // Context (normal)
            'high' // Priority
        );

        add_meta_box(
            $post_type . '_images_meta_box', // ID of meta box
            'Images', // Title of meta box
            'display_custom_images_meta_box', // Function to display meta box
            $post_type, // Custom post type
            'normal', // Context (normal)
            'high' // Priority
        );

        add_meta_box(
            $post_type . '_details_meta_box', // ID of meta box
            'Details', // Title of meta box
            'display_custom_details_meta_box', // Function to display meta box
            $post_type, // Custom post type
            'normal', // Context (normal)
            'default' // Priority
        );
    }
}
add_action('add_meta_boxes', 'add_custom_meta_box');



// Display salary meta box
function display_custom_salary_meta_box($post) {
    // Get current salary value
    $salary = get_post_meta($post->ID, get_post_type($post->ID) . '_salary', true);

    // Display the input field for salary
    ?>
    <label for="job_salary">Salary:</label>
    <input type="text" id="job_salary" name="<?php echo get_post_type($post->ID); ?>_salary" value="<?php echo esc_attr($salary); ?>" />
    <?php
}


function display_custom_details_meta_box($post) {
    // Lấy tên post type
    $post_type = get_post_type($post);

    // Định nghĩa các taxonomy cho các post type khác nhau
    $taxonomy_map = array(
        'vietnam' => 'province_vietnam',
        'laos' => 'province_laos',
        'cambodia' => 'province_cambodia'
    );

    // Lấy giá trị hiện tại của custom fields
    $selected_countries = get_post_meta($post->ID, $post_type . '_countries', true);
    $selected_provinces = get_post_meta($post->ID, $post_type . '_provinces', true);
    $selected_year = get_post_meta($post->ID, $post_type . '_year', true);

    $selected_countries = !empty($selected_countries) ? (array) $selected_countries : array();
    $selected_provinces = !empty($selected_provinces) ? (array) $selected_provinces : array();

    // Lấy taxonomy cho tỉnh theo post type
    $province_taxonomy = isset($taxonomy_map[$post_type]) ? $taxonomy_map[$post_type] : '';

//    if ($province_taxonomy) {
//        // Hiển thị các tỉnh thuộc taxonomy hiện tại
//        echo '<label for="job_provinces">Tỉnh:</label><br>';
//        $all_provinces = get_terms(array(
//            'taxonomy' => $province_taxonomy,
//            'hide_empty' => false,
//        ));
//        if (!is_wp_error($all_provinces)) {
//            foreach ($all_provinces as $province) {
//                if (is_a($province, 'WP_Term')) {
//                    $checked_province = in_array($province->term_id, $selected_provinces) ? 'checked' : '';
//                    echo '<input type="checkbox" name="' . $post_type . '_provinces[]" value="' . $province->term_id . '" ' . $checked_province . '> ' . esc_html($province->name) . '<br>';
//                }
//            }
//        }
//    }

    // Hiển thị lựa chọn năm cố định (2024, 2025)
    echo '<label for="job_year_fixed">Năm:</label><br>';
    $fixed_years = array(2024, 2025, 2026, 2027);

    foreach ($fixed_years as $year) {
        $checked_fixed_year = ($selected_year == $year) ? 'checked' : '';
        echo '<input type="radio" name="' . $post_type . '_year" value="' . $year . '" ' . $checked_fixed_year . '> ' . esc_html($year) . '<br>';
    }
}




function display_custom_images_meta_box($post) {
    // Lấy tên post type
    $post_type = get_post_type($post);

    // Lấy giá trị hiện tại của custom field
    $images = get_post_meta($post->ID, $post_type . '_images', true);

    // Hiển thị giao diện tải lên ảnh
    ?>
    <div id="<?php echo $post_type; ?>-images-container" style="display: flex; flex-wrap: wrap; gap: 10px;">
        <?php
        // Hiển thị các ảnh đã tải lên
        if (!empty($images)) {
            foreach ($images as $image) {
                echo '<div class="' . $post_type . '-image-item" style="position: relative; display: inline-block;">';
                echo '<img src="' . esc_url($image) . '" style="max-width: 150px; height: auto; display: block;" />';
                echo '<input type="hidden" name="' . $post_type . '_images[]" value="' . esc_url($image) . '" />';
                echo '<button class="remove-' . $post_type . '-image" style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; cursor: pointer;">Remove</button>';
                echo '</div>';
            }
        }
        ?>
    </div>
    <button id="upload-<?php echo $post_type; ?>-image-button" class="button">Upload Images</button>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Upload image button
            $('#upload-<?php echo $post_type; ?>-image-button').click(function(e) {
                e.preventDefault();
                var frame = wp.media({
                    title: 'Select or Upload Images',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: true
                }).on('select', function() {
                    var attachments = frame.state().get('selection').toJSON();
                    $.each(attachments, function(index, attachment) {
                        var imageUrl = attachment.url;
                        $('#<?php echo $post_type; ?>-images-container').append(
                            '<div class="' + '<?php echo $post_type; ?>' + '-image-item" style="position: relative; display: inline-block;">' +
                            '<img src="' + imageUrl + '" style="max-width: 150px; height: auto; display: block;" />' +
                            '<input type="hidden" name="<?php echo $post_type; ?>_images[]" value="' + imageUrl + '" />' +
                            '<button class="remove-<?php echo $post_type; ?>-image" style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; cursor: pointer;">Remove</button>' +
                            '</div>'
                        );
                    });
                }).open();
            });

            // Remove image button
            $(document).on('click', '.remove-<?php echo $post_type; ?>-image', function(e) {
                e.preventDefault();
                $(this).closest('.<?php echo $post_type; ?>-image-item').remove();
            });
        });
    </script>
    <?php
}


function save_custom_meta_box_data($post_id) {
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

    // Save images
    if (isset($_POST[$post_type . '_images'])) {
        $images = array_map('esc_url_raw', $_POST[$post_type . '_images']);
        update_post_meta($post_id, $post_type . '_images', $images);
    } else {
        delete_post_meta($post_id, $post_type . '_images');
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

    // Save year
    if (isset($_POST[$post_type . '_year'])) {
        update_post_meta($post_id, $post_type . '_year', intval($_POST[$post_type . '_year']));
    } else {
        delete_post_meta($post_id, $post_type . '_year');
    }
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



function custom_taxonomy_query( $query ) {
    // Kiểm tra xem đây có phải là truy vấn chính trên trang taxonomy không
    if ( !is_admin() && $query->is_main_query() && is_tax() ) {
        // Lấy tên taxonomy hiện tại
        $current_taxonomy = get_queried_object()->taxonomy;

        // Xác định các taxonomy và các giá trị năm tương ứng
        $taxonomies = array(
            'province_vietnam' => 'vietnam_year',
            'province_laos'    => 'laos_year',
            'province_cambodia'=> 'cambodia_year',
        );

        // Kiểm tra nếu taxonomy hiện tại có trong danh sách
        if ( array_key_exists( $current_taxonomy, $taxonomies ) ) {
            $year_param = $taxonomies[$current_taxonomy];

            // Kiểm tra nếu có giá trị năm trong $_GET
            if ( isset( $_GET[$year_param] ) && !empty( $_GET[$year_param] ) ) {
                $year = sanitize_text_field( $_GET[$year_param] ); // Làm sạch dữ liệu đầu vào

                // Thêm điều kiện meta query
                $meta_query = array(
                    array(
                        'key'     => $year_param, // Tên custom field dựa trên taxonomy
                        'value'   => $year, // Giá trị năm từ URL
                        'compare' => '=', // So sánh chính xác
                        'type'    => 'NUMERIC', // Xác định kiểu dữ liệu
                    ),
                );

                // Đảm bảo rằng meta_query không bị xóa bởi các điều kiện mặc định của WordPress
                if ( ! isset( $query->query_vars['meta_query'] ) ) {
                    $query->set( 'meta_query', $meta_query );
                } else {
                    $query->query_vars['meta_query'][] = $meta_query;
                }
            }
        }
    }
}
add_action( 'pre_get_posts', 'custom_taxonomy_query' );


function display_province_posts($post_type, $taxonomy_provinces, $fixed_years) {
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
        <select id="year-dropdown" name="<?php echo esc_attr($year_param); ?>" onchange="document.getElementById('filter').submit()">
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
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ),
        ),
        'meta_query' => array(),
        'posts_per_page' => -1, // Hiển thị tất cả các bài viết
    );

    // Nếu có năm được chọn, thêm điều kiện meta_query vào truy vấn
    if (!empty($selected_year)) {
        $args['meta_query'] = array(
            array(
                'key'   => $year_param,
                'value' => $selected_year,
                'compare' => '=',
            ),
        );
    } else {
        // Nếu không có năm được chọn, chỉ cần hiển thị toàn bộ bài viết
        $args['meta_query'] = array(
            array(
                'key'     => $year_param,
                'compare' => 'EXISTS',
            ),
        );
    }

    $query = new WP_Query($args);

    // Hiển thị bài viết
    if ($query->have_posts()) {
        echo '<h1>Jobs tỉnh/thành ' . esc_html($term->name) . '</h1>';
        echo '<ul class="post-list scroll-container">';
        while ($query->have_posts()) {
            $query->the_post();
            $post_year = get_post_meta(get_the_ID(), $year_param, true);
            $display_year = !empty($post_year) ? esc_html($post_year) : 'N/A';
            $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : 'https://placehold.jp/3d4070/ffffff/200x200.png';
            $excerpt = get_the_excerpt(); // Lấy mô tả ngắn

            echo '<li class="post-item scroll-area">';
            echo '<div class="post-thumbnail"><img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '"></div>';
            echo '<div class="post-info">';
            echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a> (' . $display_year . ')';
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






