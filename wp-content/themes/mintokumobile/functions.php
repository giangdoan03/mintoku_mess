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

function enqueue_swiper_script()
{
    // Enqueue Swiper CSS và các file style khác
    wp_enqueue_style('swiper-css', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.2.0/css/swiper.min.css');
    wp_enqueue_style('style-css', get_template_directory_uri() . '/css/style.css');

    // Enqueue Select2 CSS
    wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');

    // Enqueue Flag Icons CSS
    wp_enqueue_style('flag-icons-css', 'https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css');


    // Enqueue font awesome CSS
    wp_enqueue_style('awesomes-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue các file script
    wp_enqueue_script('jquery-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js');
    wp_enqueue_script('swiper-js', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.2.0/js/swiper.min.js');

    // Enqueue Select2 JS
    wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), null, true);

    // Enqueue script custom
    wp_enqueue_script('my-ajax-script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);

    // Truyền ajaxurl vào script với đúng handle
    wp_localize_script('my-ajax-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}

add_action('wp_enqueue_scripts', 'enqueue_swiper_script');


// Đăng ký custom post types cho ba quốc gia
function create_custom_post_types()
{
    // Vietnam
    register_post_type('vietnam', array(
        'labels' => array(
            'name' => __('Jobs'),
            'singular_name' => __('Jobs')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'vietnam', 'with_front' => true),
//        'supports' => array('title', 'editor', 'thumbnail'),
        'supports' => array('title', 'thumbnail'),
    ));

    // Laos
//    register_post_type('laos', array(
//        'labels' => array(
//            'name' => __('Laos Jobs'),
//            'singular_name' => __('Laos Jobs')
//        ),
//        'public' => true,
//        'has_archive' => true,
//        'rewrite' => array('slug' => 'laos', 'with_front' => true),
//        'supports' => array('title', 'editor', 'thumbnail'),
//    ));

    // Cambodia
//    register_post_type('cambodia', array(
//        'labels' => array(
//            'name' => __('Cambodia Jobs'),
//            'singular_name' => __('Cambodia Jobs')
//        ),
//        'public' => true,
//        'has_archive' => true,
//        'rewrite' => array('slug' => 'cambodia', 'with_front' => true),
//        'supports' => array('title', 'editor', 'thumbnail'),
//    ));
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

// Đăng ký taxonomy cho Company
function create_company_taxonomy()
{

    // Đăng ký taxonomy cho Vietnam
    register_taxonomy('company_vietnam', 'vietnam', array(
        'label' => __('Company Vietnam', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'company', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => true,
    ));

}

add_action('init', 'create_company_taxonomy');


// Thêm vào functions.php
add_filter('query_vars', 'add_custom_query_vars');
function add_custom_query_vars($vars)
{
    $vars[] = 'year_r';
    $vars[] = 'province_vietnam';
    $vars[] = 'university';
    return $vars;
}


// Xử lý Ajax để nạp dữ liệu cho các trường chọn (province, university, year)
function ajax_load_filters()
{
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
function ajax_search_posts()
{
    // Lấy các tham số từ URL
    $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'vietnam';
    $province_slug = isset($_GET['province_slug']) ? sanitize_text_field($_GET['province_slug']) : '';
    $university_slug = isset($_GET['university_slug']) ? sanitize_text_field($_GET['university_slug']) : '';
    $year_slug = isset($_GET['year_slug']) ? sanitize_text_field($_GET['year_slug']) : '';

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


function search_jobs()
{
    $post_type = sanitize_text_field($_GET['region']);
    $province_id = sanitize_text_field($_GET['province']);
    $university = sanitize_text_field($_GET['university']);
    $year = sanitize_text_field($_GET['year_r']);
    $company = sanitize_text_field($_GET['company']);
    $search_query = sanitize_text_field($_GET['search_query']);
    $province_slug = sanitize_text_field($_GET['province_slug']);

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        's' => $search_query,
        'tax_query' => array(
            'relation' => 'AND',
        ),
    );

    if ($post_type === 'vietnam') {
        if (!empty($province_id)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'province_vietnam',
                'field' => 'term_id',
                'terms' => array($province_id),
                'include_children' => false,
            );
        }

        if (!empty($university)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'university_vietnam',
                'field' => 'term_id',
                'terms' => array($university),
                'include_children' => false,
            );
        }

        if (!empty($year)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'year_vietnam',
                'field' => 'slug',
                'terms' => $year,
            );
        }

        if (!empty($company)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'company_vietnam',
                'field' => 'slug',
                'terms' => $company,
            );
        }
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $posts = array();
        $post_company = sanitize_text_field($_GET['company']);
        $province_slug = sanitize_text_field($_GET['province_slug']);

        while ($query->have_posts()) {
            $query->the_post();

            // Lấy tất cả các term liên quan đến university_vietnam
            $post_universities = wp_get_post_terms(get_the_ID(), 'university_vietnam', array("fields" => "all"));
            $filtered_university = null; // Biến để lưu trữ trường đại học duy nhất

            // Lọc các term theo meta key 'province_id'
            foreach ($post_universities as $term) {
                $term_province_id = get_term_meta($term->term_id, 'province_id', true); // Lấy giá trị province_id từ meta

                // Kiểm tra nếu province_id của term trùng với $province_id
                if ($term_province_id == $province_id) {
                    $filtered_university = $term; // Lấy trường đại học đúng province_id hiện tại
                    break; // Ngừng vòng lặp khi tìm thấy trường phù hợp
                }
            }

            // Nếu tìm thấy trường phù hợp, lấy tên và slug
            $university_name = $filtered_university ? $filtered_university->name : '';
            $university_slug = $filtered_university ? $filtered_university->slug : '';

            $post_years = wp_get_post_terms(get_the_ID(), 'year_vietnam', array("fields" => "names"));

            $post_company_slug = sanitize_text_field($_GET['company']);

            // Lấy term dựa trên slug
            $company_term = get_term_by('slug', $post_company_slug, 'company_vietnam');

            // Kiểm tra nếu tìm thấy term
            if ($company_term) {
                $post_company_name = $company_term->name; // Lấy tên của company
            }

            // Lấy thông tin về province_name dựa trên province_id
            $province_term = get_term_by('id', $province_id, 'province_vietnam');
            $province_name = $province_term ? $province_term->name : ''; // Lấy tên của province

            $posts[] = array(
                'title' => get_the_title(),
                'link' => get_permalink(),
                'university' => $university_name,
                'university_slug' => $university_slug,
                'year_vietnam' => $post_years,
                'province' => $province_id,
                'province_name' => $province_name, // Thêm province_name vào mảng trả về
                'province_slug' => $province_slug,
                'company_slug' => $post_company,
                'region' => $post_type,
                'company_name' => $post_company_name
            );
        }

        wp_send_json_success($posts);
    }


    wp_send_json_error();
}

add_action('wp_ajax_search_jobs', 'search_jobs');
add_action('wp_ajax_nopriv_search_jobs', 'search_jobs');


function get_taxonomy_terms()
{
    // Sanitize and retrieve the parameters
    $post_type = sanitize_text_field($_GET['region']);

    $selected_province = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
    $selected_university = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';
    $selected_company = isset($_GET['company']) ? sanitize_text_field($_GET['company']) : '';
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
        $taxonomy_data['company'] = get_terms(array(
            'taxonomy' => 'company_vietnam',
            'hide_empty' => false
        ));

        // Filter provinces if a specific province is selected
        if ($selected_province) {
            $taxonomy_data['provinces'] = array_filter($taxonomy_data['provinces'], function ($term) use ($selected_province) {
                return $term->slug === $selected_province;
            });
        }

        // Filter universities if a specific university is selected
        if ($selected_university) {
            $taxonomy_data['universities'] = array_filter($taxonomy_data['universities'], function ($term) use ($selected_university) {
                return $term->slug === $selected_university;
            });
        }

        // Filter years if a specific year is selected
        if ($selected_year) {
            $taxonomy_data['years'] = array_filter($taxonomy_data['years'], function ($term) use ($selected_year) {
                return $term->slug === $selected_year;
            });
        }

        // Filter years if a specific year is selected
        if ($selected_year) {
            $taxonomy_data['company'] = array_filter($taxonomy_data['company'], function ($term) use ($selected_year) {
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


// Thêm field để chọn province_id khi tạo term
function add_province_id_field_to_university_add()
{
    ?>
    <div class="form-field">
        <label for="province_id"><?php _e('Chọn Tỉnh'); ?></label>
        <select name="province_id" id="province_id">
            <option value="">Chọn tỉnh</option>
            <?php
            $provinces = get_terms(array('taxonomy' => 'province_vietnam', 'hide_empty' => false));
            foreach ($provinces as $province) {
                echo '<option value="' . esc_attr($province->term_id) . '">' . esc_html($province->name) . '</option>';
            }
            ?>
        </select>
    </div>
    <?php
}

add_action('university_vietnam_add_form_fields', 'add_province_id_field_to_university_add', 10, 2);


// Thêm field để chọn province_id khi chỉnh sửa term
function add_province_id_field_to_university_edit($term)
{
    // Lấy giá trị province_id đã được lưu cho term hiện tại
    $province_id = get_term_meta($term->term_id, 'province_id', true);
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="province_id"><?php _e('Chọn Tỉnh'); ?></label>
        </th>
        <td>
            <select name="province_id" id="province_id">
                <option value="">Chọn tỉnh</option>
                <?php
                $provinces = get_terms(array('taxonomy' => 'province_vietnam', 'hide_empty' => false));
                foreach ($provinces as $province) {
                    echo '<option value="' . esc_attr($province->term_id) . '" ' . selected($province->term_id, $province_id, false) . '>' . esc_html($province->name) . '</option>';
                }
                ?>
            </select>
        </td>
    </tr>
    <?php
}

add_action('university_vietnam_edit_form_fields', 'add_province_id_field_to_university_edit', 10, 2);


// Lưu lại province_id khi tạo hoặc chỉnh sửa term
function save_province_id_for_university($term_id)
{
    if (isset($_POST['province_id']) && !empty($_POST['province_id'])) {
        update_term_meta($term_id, 'province_id', intval($_POST['province_id']));
    } else {
        delete_term_meta($term_id, 'province_id'); // Xóa nếu không có giá trị được chọn
    }
}

add_action('created_university_vietnam', 'save_province_id_for_university', 10, 2);
add_action('edited_university_vietnam', 'save_province_id_for_university', 10, 2);


// Xử lý AJAX để lấy danh sách các trường đại học theo tỉnh
add_action('wp_ajax_get_universities_by_province', 'get_universities_by_province');
add_action('wp_ajax_nopriv_get_universities_by_province', 'get_universities_by_province');

function get_universities_by_province()
{
    // Kiểm tra nếu province_id được gửi từ yêu cầu AJAX
    if (isset($_POST['province_id']) && !empty($_POST['province_id'])) {
        $province_id = intval($_POST['province_id']);

//        $province_id = 49; // ID của tỉnh mà bạn muốn lấy các trường đại học
        $universities = get_terms(array(
            'taxonomy' => 'university_vietnam',
            'hide_empty' => false,
            'meta_query' => array(
                array(
                    'key' => 'province_id',
                    'value' => $province_id,
                    'compare' => '='
                )
            )
        ));

        if (!empty($universities)) {
            // Tạo một mảng chứa dữ liệu để trả về trong phản hồi AJAX
            $university_data = array();
            foreach ($universities as $university) {
                $university_data[] = array(
                    'id' => $university->term_id,
                    'name' => $university->name,
                    'slug' => $university->slug // Đảm bảo trả về slug
                );
            }

            // Gửi phản hồi thành công với danh sách trường đại học
            wp_send_json_success($university_data);
        } else {

            // Kiểm tra ID của tỉnh thành được gửi qua AJAX
            error_log('Province ID: ' . $province_id);
            // Nếu không có trường đại học nào, trả về thông báo lỗi
            wp_send_json_error('Không tìm thấy trường đại học nào.');
        }
    } else {
        // Gửi phản hồi lỗi nếu không có province_id
        wp_send_json_error('Tỉnh thành không hợp lệ.');
    }
}

function display_acf_recommended_work_slider($atts)
{
    // Lấy giá trị 'region' từ URL
    $region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';

    // Lấy giá trị 'university' từ URL (slug của taxonomy)
    $university = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';

    // Xác định post type dựa trên giá trị của 'region'
    switch ($region) {
        case 'vietnam':
            $post_type = 'vietnam';
            $taxonomy = 'company_vietnam'; // Taxonomy liên quan đến post type 'vietnam'
            break;
        case 'laos':
            $post_type = 'laos';
            $taxonomy = 'company_laos'; // Taxonomy cho post type 'laos'
            break;
        case 'cambodia':
            $post_type = 'cambodia';
            $taxonomy = 'company_cambodia'; // Taxonomy cho post type 'cambodia'
            break;
        default:
            $post_type = 'post'; // Loại post mặc định nếu không có region
            $taxonomy = ''; // Không có taxonomy nếu không có region
    }

    // Tạo mảng args cho WP_Query
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => 3, // Lấy tất cả bài viết
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'recommended_work', // Custom field 'recommended_work'
                'value' => 'recommended', // Giá trị 'recommended' được chọn
                'compare' => 'LIKE', // Sử dụng LIKE để so sánh
            ),
            array(
                'key' => 'recommended_work',
                'compare' => 'NOT EXISTS', // Bao gồm cả những bài không có 'recommended_work'
            ),
        ),
        'orderby' => array(
            'meta_value' => 'DESC', // Sắp xếp bài viết có 'recommended_work' lên đầu
            'date' => 'DESC', // Sau đó sắp xếp theo ngày đăng
        )
    );


    $query = new WP_Query($args);

    // Nếu có bài viết
    if ($query->have_posts()) {
        ob_start();
        ?>
        <div class="box_slider jobs_recommend" id="box_slider">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="swiper-slide">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                echo '<div class="avatar_job">';
                                // Kiểm tra nếu có ảnh đại diện bài viết
                                if (has_post_thumbnail()) {
                                    // Hiển thị ảnh đại diện của bài viết
                                    the_post_thumbnail('medium');
                                } else {
                                    // Hiển thị ảnh mặc định nếu không có ảnh đại diện
                                    echo '<img src="https://placehold.co/600x300" alt="Placeholder">';
                                }
                                echo '</div>';
                                ?>
                            </a>
                            <p class="title_job"><?php the_title(); ?></p>
                            <div class="content_p">
                                <div class="job_info">
                                    <div class="text_desc">
                                        <div class="hashtag">
                                            <a class="tag_item" href="#">土日祝休み</a>
                                            <a class="tag_item" href="#">昇給賞与あり</a>
                                            <a class="tag_item" href="#">個室あり</a>
                                            <a class="tag_item" href="#">夜勤あり</a>
                                        </div>
                                        <div class="salary">
                                            <span class="label_text">時給</span> <span class="salary_text">1200 円</span>
                                        </div>
                                    </div>
                                    <?php
                                    // Kiểm tra nếu có ảnh đại diện từ taxonomy
                                    $terms = get_the_terms(get_the_ID(), $taxonomy);
                                    if ($terms && !is_wp_error($terms)) {
                                        // Chỉ lấy term đầu tiên (nếu có nhiều term)
                                        $term = $terms[0];

                                        // Lấy ACF field (nếu đã tạo) từ taxonomy 'company_vietnam'
                                        $company_logo = get_field('company_image', $term); // 'company_logo' là custom field cho ảnh
                                        if ($company_logo) {
                                            echo '<div class="logo_company logo_company_recommended">';
                                            echo '<img src="' . esc_url($company_logo['url']) . '" alt="' . esc_attr($company_logo['alt']) . '">';
                                            echo '<p>' . esc_html($term->name) . '</p>'; // Hiển thị tên công ty
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="summary_job">
                                    <p>賞与昇給あり♪人気の土日祝休み♪ワンルーム社宅完備！インドネシアの先輩が活躍中！
                                        体力に自信のある先輩スタッフが活躍しています。
                                        手に職をつけたい方や、塗装の技術を磨きたい方が活躍しています。</p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

            </div>
            <div class="bl_dot">
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    } else {
        return '<p>No recommended work found.</p>';
    }
}

add_shortcode('acf_recommended_work_slider', 'display_acf_recommended_work_slider');


function enqueue_swiper_assets_for_acf()
{
//    // Swiper CSS
//    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
//
//    // Swiper JS
//    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), null, true);

    // Custom JS để khởi tạo Swiper
    wp_add_inline_script('swiper-js', "
        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('#box_slider .swiper-container', {
                slidesPerView: 3, // Số slide mặc định
                spaceBetween: 10, // Khoảng cách giữa các slide
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    // Khi màn hình >= 640px
                    640: {
                        slidesPerView: 1, // Hiển thị 1 slide trên màn hình nhỏ
                        spaceBetween: 10,
                    },
                    // Khi màn hình >= 768px
                    768: {
                        slidesPerView: 1, // Hiển thị 2 slide trên màn hình tablet
                        spaceBetween: 20,
                    },
                    // Khi màn hình >= 1024px
                    1024: {
                        slidesPerView: 3, // Hiển thị 3 slide trên màn hình lớn
                        spaceBetween: 30,
                    }
                },
                loop: true
            });

        });
    ");
}

add_action('wp_enqueue_scripts', 'enqueue_swiper_assets_for_acf');


// Xóa meta box mặc định của taxonomy
function remove_taxonomy_meta_box($taxonomy)
{
    remove_meta_box($taxonomy . 'div', 'vietnam', 'side');
}

// Hàm thêm meta box tùy chỉnh chung
function add_custom_taxonomy_meta_box($taxonomy, $label)
{
    add_meta_box(
        $taxonomy . '_taxonomy_meta_box', // ID của meta box
        $label, // Tiêu đề của meta box
        function ($post) use ($taxonomy) {
            render_custom_taxonomy_meta_box($post, $taxonomy);
        }, // Hàm render nội dung
        'vietnam', // Post type
        'side', // Vị trí
        'default' // Độ ưu tiên
    );
}

// Hàm render nội dung cho meta box
function render_custom_taxonomy_meta_box($post, $taxonomy)
{
    // Lấy danh sách các terms của taxonomy
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ));

    // Lấy các terms đã được gán cho bài viết hiện tại
    $selected_terms = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));

    // Ô tìm kiếm
    echo '<input type="text" id="search_' . esc_attr($taxonomy) . '_taxonomy" placeholder="Tìm kiếm..." style="width: 100%; margin-bottom: 10px;">';

    // Hiển thị danh sách checkbox các terms của taxonomy
    echo '<div id="' . esc_attr($taxonomy) . '-taxonomy-list">';
    if (!empty($terms)) {
        foreach ($terms as $term) {
            $checked = in_array($term->term_id, $selected_terms) ? 'checked="checked"' : '';
            echo '<div>';
            echo '<input style="margin-top: 0" type="checkbox" name="selected_' . esc_attr($taxonomy) . '_taxonomy[]" value="' . esc_attr($term->term_id) . '" id="' . esc_attr($taxonomy) . '_term_' . esc_attr($term->term_id) . '" ' . $checked . '>';
            echo '<label for="' . esc_attr($taxonomy) . '_term_' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</label>';
            echo '</div>';
        }
    } else {
        echo '<p>Không có mục nào được tìm thấy.</p>';
    }
    echo '</div>';

    // Nonce field để bảo mật
    wp_nonce_field('save_' . esc_attr($taxonomy) . '_meta_box', esc_attr($taxonomy) . '_meta_box_nonce');
}

// Hàm lưu dữ liệu được chọn từ meta box
function save_custom_taxonomy_meta_box($post_id, $taxonomy)
{
    // Kiểm tra bảo mật với nonce field
    if (!isset($_POST[$taxonomy . '_meta_box_nonce']) || !wp_verify_nonce($_POST[$taxonomy . '_meta_box_nonce'], 'save_' . $taxonomy . '_meta_box')) {
        return;
    }

    // Kiểm tra quyền chỉnh sửa bài viết
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Lưu các giá trị được chọn (terms đã được chọn từ checkbox)
    if (isset($_POST['selected_' . $taxonomy . '_taxonomy']) && is_array($_POST['selected_' . $taxonomy . '_taxonomy'])) {
        $selected_terms = array_map('intval', $_POST['selected_' . $taxonomy . '_taxonomy']);
        wp_set_object_terms($post_id, $selected_terms, $taxonomy);
    } else {
        wp_set_object_terms($post_id, array(), $taxonomy);
    }
}

// Hàm chung để thêm script tìm kiếm
function add_custom_search_script($taxonomy)
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#search_<?php echo esc_js($taxonomy); ?>_taxonomy').on('keyup', function () {
                var keyword = $(this).val().toLowerCase();
                $('#<?php echo esc_js($taxonomy); ?>-taxonomy-list div').each(function () {
                    var term = $(this).text().toLowerCase();
                    if (term.indexOf(keyword) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
    <?php
}

// Xóa meta box mặc định của cả 'university_vietnam' và 'company_vietnam'
function remove_taxonomy_meta_boxes()
{
    remove_taxonomy_meta_box('university_vietnam');
    remove_taxonomy_meta_box('company_vietnam');
}

add_action('admin_menu', 'remove_taxonomy_meta_boxes');

// Thêm các meta box tùy chỉnh
function add_all_custom_meta_boxes()
{
    add_custom_taxonomy_meta_box('university_vietnam', __('Chọn trường đại học', 'textdomain'));
    add_custom_taxonomy_meta_box('company_vietnam', __('Chọn công ty', 'textdomain'));
}

add_action('add_meta_boxes', 'add_all_custom_meta_boxes');

// Lưu dữ liệu cho từng taxonomy
function save_all_taxonomy_meta_boxes($post_id)
{
    save_custom_taxonomy_meta_box($post_id, 'university_vietnam');
    save_custom_taxonomy_meta_box($post_id, 'company_vietnam');
}

add_action('save_post', 'save_all_taxonomy_meta_boxes');

// Thêm script tìm kiếm cho cả hai taxonomy
function add_all_search_scripts()
{
    add_custom_search_script('university_vietnam');
    add_custom_search_script('company_vietnam');
}

add_action('admin_footer', 'add_all_search_scripts');


// Hàm xử lý yêu cầu AJAX và trả về dữ liệu dịch từ bảng trong cơ sở dữ liệu
function my_get_translation_json()
{
    global $wpdb;

    // Lấy ngôn ngữ hiện tại (sử dụng Polylang hoặc WPML)
    $current_language = function_exists('pll_current_language') ? pll_current_language() : 'en'; // Mặc định là tiếng Anh

    // Lấy dữ liệu JSON từ bảng wp_json_data
    $table_name = $wpdb->prefix . 'json_data'; // Tên bảng
    $json_data = $wpdb->get_var("SELECT json_content FROM $table_name LIMIT 1"); // Lấy dữ liệu JSON đầu tiên trong bảng

    if ($json_data) {
        // Chuyển đổi JSON từ chuỗi sang mảng PHP
        $decoded_json = json_decode($json_data, true);

        // Kiểm tra nếu ngôn ngữ hiện tại có trong dữ liệu JSON
        if (isset($decoded_json[$current_language])) {
            $translation = $decoded_json[$current_language]; // Lấy bản dịch cho ngôn ngữ hiện tại
            wp_send_json_success(array('json_data' => json_encode($translation)));
        } else {
            wp_send_json_error(array('message' => 'Không có bản dịch cho ngôn ngữ hiện tại'));
        }
    } else {
        wp_send_json_error(array('message' => 'Không có dữ liệu JSON trong cơ sở dữ liệu'));
    }
}

add_action('wp_ajax_get_translation_json', 'my_get_translation_json');
add_action('wp_ajax_nopriv_get_translation_json', 'my_get_translation_json'); // Cho phép người dùng chưa đăng nhập cũng có thể lấy dữ liệu


if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Cài đặt chung',
        'menu_title' => 'Cài đặt chung',
        'menu_slug' => 'global-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

// Thêm một cột mới cho custom field 'recommended_work'
// Thêm cột mới cho custom field 'recommended_work' vào vị trí thứ 3
function add_recommended_work_column($columns) {
    // Tạo mảng mới để sắp xếp lại thứ tự các cột
    $new_columns = array();

    // Lặp qua các cột hiện tại và thêm cột mới vào vị trí mong muốn
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Đặt cột 'recommended_work' sau cột 'title' (hoặc sau cột thứ 2 khác)
        if ($key == 'title') {
            $new_columns['recommended_work'] = __('Recommended Work', 'textdomain'); // Đặt cột ở vị trí thứ 3
        }
    }

    return $new_columns;
}
add_filter('manage_vietnam_posts_columns', 'add_recommended_work_column');

// Hiển thị giá trị của custom field 'recommended_work' trong cột vừa thêm
function show_recommended_work_column($column, $post_id) {
    if ($column === 'recommended_work') {
        $recommended = get_post_meta($post_id, 'recommended_work', true);

        // Kiểm tra xem giá trị trả về có phải là mảng không
        if (is_array($recommended)) {
            // Xử lý mảng (ví dụ: nối các giá trị lại để hiển thị)
            echo implode(', ', $recommended);
        } elseif ($recommended) {
            // Nếu không phải mảng, kiểm tra và hiển thị giá trị
            if ($recommended === 'recommended') {
                echo '<span style="color: green;">Checked</span>'; // Hiển thị "Checked" nếu giá trị là 'recommended'
            } else {
                echo '<span style="color: orange;">' . esc_html($recommended) . '</span>'; // Hiển thị giá trị khác nếu có
            }
        } else {
            echo '<span style="color: red;">Not Checked</span>'; // Hiển thị "Not Checked" nếu không có giá trị
        }
    }
}


add_action('manage_vietnam_posts_custom_column', 'show_recommended_work_column', 10, 2);

// Làm cho cột 'recommended_work' sắp xếp được
function sortable_recommended_work_column($columns) {
    $columns['recommended_work'] = 'recommended_work';
    return $columns;
}
add_filter('manage_edit-vietnam_sortable_columns', 'sortable_recommended_work_column');

// Xử lý sắp xếp theo giá trị custom field 'recommended_work'
function sort_recommended_work_column($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    // Kiểm tra xem có đang ở màn hình post type 'vietnam' không
    global $pagenow;
    if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'vietnam') {

        // Sắp xếp các bài viết theo giá trị 'recommended_work'
        // Nếu người dùng chọn sắp xếp theo cột 'recommended_work'
        if (isset($_GET['orderby']) && $_GET['orderby'] == 'recommended_work') {
            $query->set('meta_key', 'recommended_work');
            $query->set('orderby', array(
                'meta_value' => 'DESC',  // Sắp xếp bài có recommended lên đầu
                'date' => 'DESC'  // Sau đó sắp xếp theo ngày đăng
            ));
        }

        // Nếu không có tùy chọn sắp xếp nào, mặc định sắp xếp theo 'recommended_work'
        if (!isset($_GET['orderby'])) {
            $query->set('meta_key', 'recommended_work');
            $query->set('orderby', array(
                'meta_value' => 'DESC',  // Sắp xếp bài có recommended lên đầu
                'date' => 'DESC'  // Sau đó sắp xếp theo ngày đăng
            ));
        }
    }
}
add_action('pre_get_posts', 'sort_recommended_work_column');







































