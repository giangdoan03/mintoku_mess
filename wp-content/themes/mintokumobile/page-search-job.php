<?php
/* Template Name: Trang Tìm kiếm */
get_header(); ?>

<main id="main" class="page-form-search xxxxxxxxxx">
    <section class="search-form">
        <h1>Tìm kiếm tỉnh/thành phố</h1>
        <form id="search-form" action="<?php echo esc_url(home_url('/')); ?>" method="get">
            <label for="province_vietnam">Chọn tỉnh/thành phố:</label>
            <select name="province_vietnam" id="province_vietnam">
                <option value="">Chọn tỉnh/thành phố</option>
                <?php
                $terms = get_terms(array(
                    'taxonomy'   => 'province_vietnam',
                    'hide_empty' => false,
                    'parent'     => 0,
                ));

                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                    }
                }
                ?>
            </select>

            <label for="university">Chọn trường đại học:</label>
            <select name="university" id="university">
                <option value="">Chọn trường đại học</option>
                <!-- Options sẽ được nạp bằng AJAX -->
            </select>

            <label for="year">Chọn năm:</label>
            <select name="year" id="year">
                <option value="">Chọn năm</option>
                <?php
                $years = get_terms(array(
                    'taxonomy'   => 'year_vietnam',
                    'hide_empty' => false,
                ));

                if (!empty($years) && !is_wp_error($years)) {
                    foreach ($years as $year) {
                        echo '<option value="' . esc_attr($year->slug) . '">' . esc_html($year->name) . '</option>';
                    }
                }
                ?>
            </select>

            <button type="submit">Tìm kiếm</button>
        </form>
    </section>
</main>

<script>
    jQuery(document).ready(function($) {
        $('#province_vietnam').change(function() {
            var provinceSlug = $(this).val();

            console.log('provinceSlug', provinceSlug)


            if (provinceSlug) {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'GET',
                    data: {
                        action: 'load_universities',
                        province_slug: provinceSlug
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#university').html('<option value="">Chọn trường đại học</option>');
                            response.data.forEach(function(university) {
                                $('#university').append('<option value="' + university.term_slug + '">' + university.name + '</option>');
                            });
                        } else {
                            console.error('Error loading universities:', response.data);
                            $('#university').html('<option value="">Chọn trường đại học</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', error);
                        $('#university').html('<option value="">Chọn trường đại học</option>');
                    }
                });
            } else {
                $('#university').html('<option value="">Chọn trường đại học</option>');
            }
        });
    });
</script>

<?php get_footer(); ?>
