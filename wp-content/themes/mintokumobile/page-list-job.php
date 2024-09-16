<?php
/* Template Name: page list job */
?>

<?php
get_header(); ?>

<main id="main" class="page-form-search">
    <section class="search-form">
        <div>
            <h1>Tìm kiếm công việc</h1>

            <!-- Post Type Dropdown -->
            <label for="post_type">Chọn loại bài viết:</label>
            <select name="region" id="post_type">
                <option value="">Chọn loại bài viết:</option>
                <option value="vietnam">Việt Nam</option>
                <option value="laos">Lào</option>
                <option value="cambodia">Campuchia</option>
            </select>
            <span id="post_type_error" style="color: red; display: none;">Vui lòng chọn loại bài viết.</span>

            <!-- Province Dropdown -->
            <label for="province">Chọn tỉnh/thành:</label>
            <select name="province" id="province" disabled>
                <option value="">Chọn tỉnh/thành:</option>
            </select>

            <!-- University Dropdown -->
            <label for="university">Chọn trường đại học:</label>
            <select name="university" id="university" disabled>
                <option value="">Chọn trường đại học:</option>
            </select>

            <!-- Year Dropdown -->
            <label for="year_r">Chọn năm:</label>
            <select name="year_r" id="year_r" disabled>
                <option value="">Chọn năm:</option>
            </select>

            <!-- Search Query -->
            <!--        <label for="search_query">Tìm kiếm từ khóa:</label>-->
            <input type="hidden" id="search_query" name="search_query"/>

            <!-- Search Button -->
            <button id="search_button">Tìm kiếm</button>
        </div>
        <!-- Results Section -->
        <div id="search-results"></div>
    </section>
</main>

<script>

    // Perform the search based on selected filters
    jQuery(document).ready(function ($) {
        // Function to update the taxonomy dropdown based on post type
        function updateProvinceDropdown(postType, selectedProvince, selectedUniversity, selectedYear) {
            var provinceDropdown = $('#province');
            var universityDropdown = $('#university');
            var yearDropdown = $('#year_r');

            provinceDropdown.prop('disabled', true).html('<option value="">Loading...</option>');
            universityDropdown.prop('disabled', true).html('<option value="">Loading...</option>');
            yearDropdown.prop('disabled', true).html('<option value="">Loading...</option>');

            if (!postType) {
                provinceDropdown.html('<option value="">Chọn tỉnh/thành:</option>');
                universityDropdown.html('<option value="">Chọn trường đại học:</option>');
                yearDropdown.html('<option value="">Chọn năm:</option>');
                return;
            }

            // Fetch taxonomy terms based on the selected post type
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'GET',
                data: {
                    action: 'get_taxonomy_terms',
                    region: postType
                },
                success: function (response) {
                    if (response.success) {
                        provinceDropdown.prop('disabled', false).html('<option value="">Chọn tỉnh/thành:</option>');
                        universityDropdown.prop('disabled', false).html('<option value="">Chọn trường đại học:</option>');
                        yearDropdown.prop('disabled', false).html('<option value="">Chọn năm:</option>');

                        response.data.provinces.forEach(function (term) {
                            var isSelected = term.slug === selectedProvince ? 'selected' : '';
                            provinceDropdown.append('<option value="' + term.slug + '" ' + isSelected + '>' + term.name + '</option>');
                        });

                        response.data.universities.forEach(function (term) {
                            var isSelected = term.slug === selectedUniversity ? 'selected' : '';
                            universityDropdown.append('<option value="' + term.slug + '" ' + isSelected + '>' + term.name + '</option>');
                        });

                        response.data.years.forEach(function (term) {
                            var isSelected = term.slug === selectedYear ? 'selected' : '';
                            yearDropdown.append('<option value="' + term.slug + '" ' + isSelected + '>' + term.name + '</option>');
                        });
                        // Sau khi dropdown được cập nhật, thực hiện tìm kiếm nếu URL có tham số
                        performSearch();
                    } else {
                        provinceDropdown.html('<option value="">Không có tỉnh/thành nào.</option>');
                        universityDropdown.html('<option value="">Không có trường đại học nào.</option>');
                        yearDropdown.html('<option value="">Không có năm nào.</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', error);
                    provinceDropdown.html('<option value="">Có lỗi xảy ra.</option>');
                    universityDropdown.html('<option value="">Có lỗi xảy ra.</option>');
                    yearDropdown.html('<option value="">Có lỗi xảy ra.</option>');
                }
            });
        }


        // ====================================================


        // Function to add query parameters to the URL
        function updateUrlParams(param, value) {
            var url = new URL(window.location.href);
            if (value) {
                url.searchParams.set(param, value);
            } else {
                url.searchParams.delete(param); // Xóa tham số nếu không có giá trị
            }
            window.history.replaceState({}, '', url);
        }

        // Khi người dùng chọn giá trị từ dropdown, cập nhật URL
        $('#post_type').on('change', function () {
            updateUrlParams('region', $(this).val());
        });
        $('#province').on('change', function () {
            updateUrlParams('province', $(this).val());
        });
        $('#university').on('change', function () {
            updateUrlParams('university', $(this).val());
        });
        $('#year_r').on('change', function () {
            updateUrlParams('year_r', $(this).val());
        });

        // Function to perform search
        function performSearch() {
            // Lấy giá trị trực tiếp từ các input hiện tại
            var postType = $('#post_type').val();
            var province = $('#province').val();
            var university = $('#university').val();
            var year_r = $('#year_r').val();
            var searchQuery = $('#search_query').val();

            // Kiểm tra nếu không chọn loại bài viết
            if (!postType) {
                $('#post_type_error').show();
                return; // Dừng lại nếu không chọn loại bài viết
            } else {
                $('#post_type_error').hide();
            }


            // Kiểm tra nếu không chọn loại bài viết
            if (!postType) {
                // Hiển thị thông báo lỗi
                $('#post_type_error').show();
                return; // Dừng lại nếu không chọn loại bài viết
            } else {
                // Ẩn thông báo lỗi nếu có giá trị hợp lệ
                $('#post_type_error').hide();
            }

            // Update the URL with the search parameters
            updateUrlParams({
                region: postType,
                province: province,
                university: university,
                year_r: year_r,
                search_query: searchQuery
            });

            // Perform AJAX search
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'GET',
                data: {
                    action: 'search_jobs',
                    region: postType,
                    province: province,
                    university: university,
                    year_r: year_r,
                    search_query: searchQuery
                },
                success: function (response) {
                    if (response.success) {
                        var resultHtml = '';
                        response.data.forEach(function (post) {
                            resultHtml += '<p><a href="' + post.link + '">' + post.title + '</a></p>';
                        });
                        $('#search-results').html(resultHtml);
                    } else {
                        $('#search-results').html('<p>Không có bài viết nào.</p>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', error);
                    $('#search-results').html('<p>Có lỗi xảy ra khi tìm kiếm.</p>');
                }
            });
        }

        // Update dropdowns when post type changes
        $('#post_type').change(function () {
            var selectedPostType = $(this).val();
            updateProvinceDropdown(selectedPostType);
        });

        // Get URL parameters on page load
        var urlParams = new URLSearchParams(window.location.search);
        var postType = urlParams.get('region') || '';
        var province = urlParams.get('province') || '';
        var university = urlParams.get('university') || '';
        var year_r = urlParams.get('year_r') || '';

        // Set the form fields based on URL parameters
        $('#post_type').val(postType);

        // If there's a post type in the URL, load the dropdowns and preselect values
        if (postType) {
            updateProvinceDropdown(postType, province, university, year_r);
        }

        // Perform search on button click
        $('#search_button').click(function () {
            performSearch();
        });

    });
</script>

<?php get_footer(); ?>