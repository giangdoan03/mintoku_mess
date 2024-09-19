<?php
/* Template Name: page list job */
?>

<?php
get_header(); ?>

<main id="main" class="page-form-search">
    <section class="search-form">
        <div>
            <h1 data-translate="search_job">Tìm kiếm công việc</h1>

            <!-- Post Type Dropdown -->
            <label for="post_type" data-translate="select_post_type">Chọn loại bài viết:</label>
            <select name="region" id="post_type">
                <option value="" data-translate="select_post_type" ><?php echo esc_html__('Chọn loại bài viết:', 'text-domain'); ?></option>
                <option value="vietnam"><?php echo esc_html__('Việt Nam', 'text-domain'); ?></option>
                <option value="laos"><?php echo esc_html__('Lào', 'text-domain'); ?></option>
                <option value="cambodia"><?php echo esc_html__('Campuchia', 'text-domain'); ?></option>
            </select>
            <span id="post_type_error" style="color: red; display: none;" data-translate="error_no_post_type"></span>

            <!-- Province Dropdown -->
            <label for="province" data-translate="select_province">Chọn tỉnh/thành:</label>
            <select name="province" id="province" disabled>
                <option value="" data-translate="select_province"><?php echo esc_html__('Chọn tỉnh/thành:', 'text-domain'); ?></option>
            </select>

            <!-- University Dropdown -->
            <label for="university" data-translate="select_university">Chọn trường đại học:</label>
            <select name="university" id="university" disabled>
                <option value="" data-translate="select_university"><?php echo esc_html__('Chọn trường đại học:', 'text-domain'); ?></option>
            </select>

            <!-- Year Dropdown -->
<!--            <label for="year_r" data-translate="select_year">Chọn năm:</label>-->
<!--            <select name="year_r" id="year_r" disabled>-->
<!--                <option value="" data-translate="select_year">--><?php //echo esc_html__('Chọn năm:', 'text-domain'); ?><!--</option>-->
<!--            </select>-->

            <!-- Search Query -->
            <input type="hidden" id="search_query" name="search_query"/>

            <!-- Search Button -->
            <button id="search_button" data-translate="search">Tìm kiếm</button>
        </div>
        <!-- Results Section -->
        <div id="search-results"></div>
    </section>
</main>

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

    // Perform the search based on selected filters
    jQuery(document).ready(function ($) {
        const language = '<?php echo ICL_LANGUAGE_CODE; ?>'; // PHP variable
        setLanguage(language);
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
// Function to perform search
        function performSearch() {
            var postType = $('#post_type').val();
            var province = $('#province').val();
            var university = $('#university').val();
            var year_r = $('#year_r').val();
            var searchQuery = $('#search_query').val();

            if (!postType) {
                $('#post_type_error').show();
                return;
            } else {
                $('#post_type_error').hide();
            }

            // Update URL parameters
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
                        var groupedResults = {};

                        // Nhóm các bài viết theo year_vietnam và university
                        response.data.forEach(function (post) {
                            console.log('item',post)
                            post.year_vietnam.forEach(function (year) {
                                if (!groupedResults[post.university]) {
                                    groupedResults[post.university] = [];
                                }
                                groupedResults[post.university].push({
                                    year: year,
                                    link: post.link,
                                    university_slug: post.university_slug,
                                    province: post.province, // Lấy slug của province
                                    region: post.region // Lấy slug của province
                                });
                            });
                        });

                        var resultHtml = '';
                        var baseURL;
                        // Xác định nếu đang ở localhost
                        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                            baseURL = window.location.origin + '/mintoku_mobile'; // Đối với môi trường localhost
                        } else {
                            baseURL = window.location.origin; // Đối với môi trường sản xuất
                        }

                        // Nếu bạn đang ở môi trường hosting và muốn bỏ phần '/mintoku_mobile', cập nhật biến này
                        // var baseURL = window.location.origin;
                        // Đối tượng để lưu các link đã gặp
                        var seenLinks = {};

                        for (var university in groupedResults) {
                            if (groupedResults.hasOwnProperty(university)) {
                                groupedResults[university].forEach(function (item) {
                                    // Tạo URL đầy đủ
                                    var customLink = baseURL + '/jobs/?year_r=' + item.year + '&region=' + item.region +
                                        '&province=' + item.province +
                                        '&university=' + item.university_slug;

                                    // Kiểm tra xem link đã tồn tại chưa
                                    if (!seenLinks[customLink]) {
                                        seenLinks[customLink] = true; // Đánh dấu link đã gặp

                                        resultHtml += '<p><a href="' + customLink + '">' + university + ' - ' + item.year + '</a></p>';
                                    }
                                });
                            }
                        }

                        $('#search-results').html(resultHtml);



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
