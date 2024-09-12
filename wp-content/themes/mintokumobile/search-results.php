<?php
/**
 * Template Name: Search Job Results
 */

get_header();
?>

<form id="post-type-form" method="get" action="<?php echo home_url('/search-job/'); ?>">
    <label for="post_type">Chọn Post Type:</label>
    <select name="post_type" id="post_type">
        <option value="">Chọn Post Type</option>
        <option value="vietnam" <?php selected( 'vietnam', isset($_GET['post_type']) ? $_GET['post_type'] : '' ); ?>>Vietnam</option>
        <option value="laos" <?php selected( 'laos', isset($_GET['post_type']) ? $_GET['post_type'] : '' ); ?>>Laos</option>
        <option value="cambodia" <?php selected( 'cambodia', isset($_GET['post_type']) ? $_GET['post_type'] : '' ); ?>>Cambodia</option>
    </select>

    <label for="province">Chọn Province:</label>
    <select name="province" id="province">
        <option value="">Chọn Province</option>
    </select>

    <label for="university">Chọn Trường Đại Học:</label>
    <select name="university" id="university">
        <option value="">Chọn Trường Đại Học</option>
    </select>

    <input type="submit" value="Submit">
</form>

<script>
    document.getElementById('post_type').addEventListener('change', function() {
        var postType = this.value;
        var provinceSelect = document.getElementById('province');
        var universitySelect = document.getElementById('university');

        provinceSelect.innerHTML = '<option value="">Chọn Province</option>';
        universitySelect.innerHTML = '<option value="">Chọn Trường Đại Học</option>';

        if (postType) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '<?php echo admin_url('admin-ajax.php'); ?>?action=get_province_taxonomy&post_type=' + postType, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (Array.isArray(response)) {
                            response.forEach(function(province) {
                                var option = document.createElement('option');
                                option.value = province.slug;
                                option.text = province.name;
                                provinceSelect.appendChild(option);
                            });
                        } else {
                            console.error('Expected an array but received:', response);
                        }
                    } catch (e) {
                        console.error('Failed to parse JSON:', e);
                    }
                } else {
                    console.error('Failed to load provinces:', xhr.status, xhr.statusText);
                }
            };
            xhr.send();
        }

        updateURL();
    });

    document.getElementById('province').addEventListener('change', function() {
        var provinceSlug = this.value;
        var universitySelect = document.getElementById('university');

        universitySelect.innerHTML = '<option value="">Chọn Trường Đại Học</option>';

        if (provinceSlug) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '<?php echo admin_url('admin-ajax.php'); ?>?action=get_university_taxonomy&province_slug=' + provinceSlug, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (Array.isArray(response)) {
                            response.forEach(function(university) {
                                var option = document.createElement('option');
                                option.value = university.slug;
                                option.text = university.name;
                                universitySelect.appendChild(option);
                            });
                        } else {
                            console.error('Expected an array but received:', response);
                        }
                    } catch (e) {
                        console.error('Failed to parse JSON:', e);
                    }
                } else {
                    console.error('Failed to load universities:', xhr.status, xhr.statusText);
                }
            };
            xhr.send();
        }

        updateURL();
    });

    document.getElementById('university').addEventListener('change', function() {
        updateURL();
    });

    function updateURL() {
        var postType = document.getElementById('post_type').value;
        var province = document.getElementById('province').value;
        var university = document.getElementById('university').value;

        var params = new URLSearchParams();

        if (postType) {
            params.set('post_type', postType);
        }
        if (province) {
            params.set('province', province);
        }
        if (university) {
            params.set('university', university);
        }

        var baseUrl = '<?php echo home_url('/search-job/'); ?>';
        var newUrl = baseUrl + '?' + params.toString();
        history.replaceState(null, null, newUrl);
    }
</script>

<?php
// Xử lý kết quả tìm kiếm
$post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';
$province_slug = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
$university_slug = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';

if ($post_type === 'vietnam') {
    $taxonomy_province = 'province_vietnam';
    $taxonomy_university = 'university_vietnam';

    // Lấy các tỉnh cấp 0 và cấp 1
    $province_terms = get_terms(array(
        'taxonomy' => $taxonomy_province,
        'hide_empty' => false,
        'parent' => 0
    ));

    $child_province_terms = get_terms(array(
        'taxonomy' => $taxonomy_province,
        'hide_empty' => false,
        'parent' => $province_slug
    ));

    // Xây dựng WP_Query
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'tax_query' => array(
            'relation' => 'AND',
        ),
    );

    if ($province_slug) {
        $args['tax_query'][] = array(
            'taxonomy' => $taxonomy_province,
            'field'    => 'slug',
            'terms'    => array_merge(
                wp_list_pluck($province_terms, 'slug'),
                wp_list_pluck($child_province_terms, 'slug')
            ),
        );
    }

    if ($university_slug) {
        $args['tax_query'][] = array(
            'taxonomy' => $taxonomy_university,
            'field'    => 'slug',
            'terms'    => $university_slug,
        );
    }

    // Chạy query
    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            the_title('<h2>', '</h2>');
            the_excerpt();
        endwhile;
    else :
        echo '<p>Không tìm thấy bài viết nào.</p>';
    endif;

    wp_reset_postdata();
} else {
    echo '<p>Vui lòng chọn loại bài viết hợp lệ.</p>';
}

get_footer();
?>
