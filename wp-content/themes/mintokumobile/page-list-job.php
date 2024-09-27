<?php
/* Template Name: page list job */
?>

<?php
get_header(); ?>

<main id="main" class="page-form-search" <?php body_class('fade-in'); ?>>
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
            <?php
            // Lấy giá trị 'province' từ URL nếu có
            $selected_slug = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
            ?>

            <select id="province-select">
                <option value="">Chọn tỉnh</option>
                <?php
                $provinces = get_terms(array(
                    'taxonomy' => 'province_vietnam',
                    'hide_empty' => false
                ));
                foreach ($provinces as $province):
                    // Kiểm tra nếu slug từ URL khớp với slug của term
                    $selected = ($province->slug === $selected_slug) ? 'selected' : '';
                    ?>
                    <option value="<?php echo esc_attr($province->term_id); ?>" data-slug="<?php echo esc_attr($province->slug); ?>" <?php echo $selected; ?>>
                        <?php echo esc_html($province->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="university" data-translate="select_university">Chọn trường đại học:</label>
            <select id="university-select">
                <option value="">Chọn trường đại học</option>
                <!-- Trường đại học sẽ được load vào đây thông qua AJAX -->
            </select>

            <!-- Year Dropdown -->
<!--            <label for="year_r" data-translate="select_year">Chọn năm:</label>-->
<!--            <select name="year_r" id="year_r" disabled>-->
<!--                <option value="" data-translate="select_year">--><?php //echo esc_html__('Chọn năm:', 'text-domain'); ?><!--</option>-->
<!--            </select>-->

            <!-- University Dropdown -->
            <label for="company" data-translate="select_company">Chọn công ty:</label>
            <select name="company" id="company" disabled>
                <option value="" data-translate="select_company"><?php echo esc_html__('Chọn công ty:', 'text-domain'); ?></option>
                <!-- Các tùy chọn công ty sẽ được thêm thông qua AJAX -->
            </select>

            <!-- Search Query -->
            <input type="hidden" id="search_query" name="search_query"/>

            <!-- Search Button -->
            <button id="search_button" data-translate="search">Tìm kiếm</button>
        </div>
        <hr>
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
        console.log('xxxxxxxxxx')
        //const language = '<?php //echo ICL_LANGUAGE_CODE; ?>//'; // PHP variable
        //setLanguage(language);
        // Function to update the taxonomy dropdown based on post type
        function updateProvinceDropdown(postType, selectedProvince, selectedUniversity, selectedYear, selectedCompany) {
            var provinceDropdown = $('#province');
            var universityDropdown = $('#university');
            var yearDropdown = $('#year_r');
            var companyDropdown = $('#company');

            provinceDropdown.prop('disabled', true).html('<option value="">Loading...</option>');
            universityDropdown.prop('disabled', true).html('<option value="">Loading...</option>');
            yearDropdown.prop('disabled', true).html('<option value="">Loading...</option>');
            companyDropdown.prop('disabled', true).html('<option value="">Loading...</option>');

            if (!postType) {
                provinceDropdown.html('<option value="">Chọn tỉnh/thành:</option>');
                universityDropdown.html('<option value="">Chọn trường đại học:</option>');
                yearDropdown.html('<option value="">Chọn năm:</option>');
                companyDropdown.html('<option value="">Chọn công ty:</option>');
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
                        companyDropdown.prop('disabled', false).html('<option value="">Chọn công ty:</option>');

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

                        response.data.company.forEach(function (term) {
                            var isSelected = term.slug === selectedCompany ? 'selected' : '';
                            companyDropdown.append('<option value="' + term.slug + '" ' + isSelected + '>' + term.name + '</option>');
                        });

                        // Sau khi dropdown được cập nhật, thực hiện tìm kiếm nếu URL có tham số
                        performSearch();
                    } else {
                        provinceDropdown.html('<option value="">Không có tỉnh/thành nào.</option>');
                        universityDropdown.html('<option value="">Không có trường đại học nào.</option>');
                        yearDropdown.html('<option value="">Không có năm nào.</option>');
                        companyDropdown.html('<option value="">Không có công ty nào.</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', error);
                    provinceDropdown.html('<option value="">Có lỗi xảy ra.</option>');
                    universityDropdown.html('<option value="">Có lỗi xảy ra.</option>');
                    yearDropdown.html('<option value="">Có lỗi xảy ra.</option>');
                    companyDropdown.html('<option value="">Có lỗi xảy ra.</option>');
                }
            });
        }


        // ====================================================


        // Function to add query parameters to the URL
        function updateUrlParams(param, value, clearParams = []) {
            var url = new URL(window.location.href);

            // Set or delete the main parameter
            if (value) {
                url.searchParams.set(param, value);
            } else {
                url.searchParams.delete(param);
            }

            // Clear other parameters if specified
            clearParams.forEach(function (p) {
                url.searchParams.delete(p);
            });

            window.history.replaceState({}, '', url);
        }


        // Khi người dùng chọn giá trị từ dropdown, cập nhật URL
        $('#post_type').on('change', function () {
            var postType = $(this).val();
            updateUrlParams('region', postType, ['province', 'university']); // Clear province and university when post type is changed
            updateProvinceDropdown(postType); // Update the province dropdown based on the selected post type
        });
        $('#province-select').on('change', function () {
            var provinceId = $(this).val(); // Lấy giá trị ID của tỉnh
            var provinceSlug = $(this).find('option:selected').data('slug'); // Lấy giá trị slug

            console.log('Province ID:', provinceId); // Hiển thị ID của tỉnh
            console.log('Province Slug:', provinceSlug); // Hiển thị slug của tỉnh

            updateUrlParams('province', provinceSlug, ['university']); // Cập nhật URL với slug của tỉnh
            performSearch(); // Thực hiện tìm kiếm
        });

        // Lắng nghe sự kiện khi người dùng chọn trường đại học
        $('#university-select').on('change', function () {
            var universityId = $(this).val(); // Lấy ID của trường đại học
            var universitySlug = $(this).find('option:selected').data('slug'); // Lấy slug của trường đại học

            console.log('University ID:', universityId);
            console.log('University Slug:', universitySlug);

            // Gọi hàm updateUrlParams với slug của trường đại học
            updateUrlParams('university', universitySlug);
        });
        $('#year_r').on('change', function () {
            updateUrlParams('year_r', $(this).val());
        });

        $('#company').on('change', function () {
            updateUrlParams('company', $(this).val());
        });

        // Function to perform search
        function performSearch() {
            // Hàm lấy giá trị từ URL

            var postType = $('#post_type').val();
            var province = $('#province-select').val();
            var university = $('#university-select').val();
            var year_r = $('#year_r').val();
            var company = $('#company').val();
            var searchQuery = $('#search_query').val();

            var selectedOptionProvince = $('#province-select option:selected');
            var selectedSlugProvince = selectedOptionProvince.data('slug'); // Lấy giá trị của data-slug
            console.log('selectedSlug',selectedSlugProvince)

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
                company: company,
                province_slug: selectedSlugProvince,
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
                    company: company,
                    province_slug: selectedSlugProvince,
                    search_query: searchQuery
                },
                success: function (response) {
                    if (response.success) {
                        var groupedResults = {};

                        // Nhóm các bài viết theo year_vietnam và university
                        response.data.forEach(function (post) {
                            post.year_vietnam.forEach(function (year) {
                                if (!groupedResults[post.university]) {
                                    groupedResults[post.university] = [];
                                }
                                groupedResults[post.university].push({
                                    year: year,
                                    link: post.link,
                                    university_slug: post.university_slug,
                                    province: post.province, // Lấy slug của province
                                    region: post.region, // Lấy slug của province
                                    label: post.university, // Lấy slug của province
                                    province_slug: post.province_slug, // Lấy slug của province
                                    company: company,
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
                                    if (university && university !== 'null' && item.year && item.year !== 'null') {
                                        if (item.university_slug && item.university_slug !== 'null') {
                                            if (item.company && item.company !== 'null') {
                                                var customLink = baseURL + '/jobs/?year_r=' + item.year +
                                                    '&region=' + item.region +
                                                    '&province=' + item.province_slug +
                                                    '&university=' + item.university_slug +
                                                    '&company=' + item.company;
                                                if (!seenLinks[customLink]) {
                                                    seenLinks[customLink] = true;
                                                    resultHtml += '<p><a href="' + customLink + '">' + university + ' - ' + item.year + ' - ' + item.company + '</a></p>';
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        }


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
        var company = urlParams.get('company') || '';

        // Set the form fields based on URL parameters
        $('#post_type').val(postType);

        // If there's a post type in the URL, load the dropdowns and preselect values
        if (postType) {
            updateProvinceDropdown(postType, province, university, year_r, company);
        }

        // Perform search on button click
        $('#search_button').click(function () {
            performSearch();
        });

    });

    jQuery(document).ready(function($) {

        // Kích hoạt Select2 trên dropdown company
        $('#company').select2({
            placeholder: "Chọn công ty", // Văn bản placeholder
            allowClear: true // Cho phép người dùng xóa lựa chọn
        });


        // Hàm lấy giá trị từ URL
        function getUrlParam(param) {
            var urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Lấy giá trị từ URL
        var selectedProvinceSlug = getUrlParam('province'); // Lấy slug tỉnh từ URL
        var selectedUniversitySlug = getUrlParam('university'); // Lấy slug trường từ URL

        // Nếu đã có giá trị province trên URL, tự động load danh sách trường đại học
        if (selectedProvinceSlug) {
            console.log('Selected Province Slug from URL:', selectedProvinceSlug);

            // Lấy ID của tỉnh dựa trên slug trong dropdown (dựa vào data-slug)
            var provinceOption = $('#province-select option[data-slug="' + selectedProvinceSlug + '"]');
            if (provinceOption.length > 0) {
                var provinceId = provinceOption.val(); // Lấy ID của tỉnh

                // Chọn tỉnh trong dropdown
                $('#province-select').val(provinceId);

                // Gửi AJAX yêu cầu để lấy danh sách các trường đại học
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>', // URL tới admin-ajax.php
                    type: 'POST',
                    data: {
                        action: 'get_universities_by_province', // Tên action bạn sẽ tạo trong PHP
                        province_id: provinceId
                    },
                    success: function(response) {
                        // Xóa các option hiện có trong dropdown 'university-select'
                        $('#university-select').empty();
                        $('#university-select').append('<option value="">Chọn trường đại học</option>');

                        // Thêm các option mới từ kết quả AJAX
                        if (response.success) {
                            $.each(response.data, function(index, university) {
                                // Thêm các trường đại học vào dropdown
                                $('#university-select').append('<option value="' + university.id + '" data-slug="' + university.slug + '">' + university.name + '</option>');
                            });

                            // Nếu có giá trị university trên URL, đánh selected cho trường đó
                            if (selectedUniversitySlug) {
                                $('#university-select option[data-slug="' + selectedUniversitySlug + '"]').prop('selected', true);
                            }
                        } else {
                            alert('Không tìm thấy trường đại học nào.');
                        }
                    },
                    error: function() {
                        alert('Đã xảy ra lỗi trong quá trình xử lý.');
                    }
                });
            }
        }

        // Khi người dùng thay đổi tỉnh, thực hiện AJAX để load lại danh sách trường đại học
        $('#province-select').on('change', function() {
            var provinceId = $(this).val(); // Lấy ID của tỉnh đã chọn
            console.log('provinceId', provinceId);

            // Gửi AJAX yêu cầu để lấy danh sách các trường đại học
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>', // URL tới admin-ajax.php
                type: 'POST',
                data: {
                    action: 'get_universities_by_province', // Tên action bạn sẽ tạo trong PHP
                    province_id: provinceId
                },
                success: function(response) {
                    // Xóa các option hiện có trong dropdown 'university-select'
                    $('#university-select').empty();
                    $('#university-select').append('<option value="">Chọn trường đại học</option>');

                    // Thêm các option mới từ kết quả AJAX
                    if (response.success) {
                        $.each(response.data, function(index, university) {
                            $('#university-select').append('<option value="' + university.id + '" data-slug="' + university.slug + '">' + university.name + '</option>');
                        });
                    } else {
                        alert('Không tìm thấy trường đại học nào.');
                    }
                },
                error: function() {
                    alert('Đã xảy ra lỗi trong quá trình xử lý.');
                }
            });
        });
    });

</script>

<?php get_footer(); ?>
