<?php
/* Template Name: Trang Tìm kiếm */
get_header(); ?>

<main id="main" class="page-form-search xxxxxxxxxx">
    <section class="search-form">
        <h1>Tìm kiếm bài viết</h1>
        <label for="post_type">Chọn quốc gia:</label>
        <select name="post_type" id="post_type">
            <option value="">Chọn quốc gia</option>
            <option value="vietnam">Việt Nam</option>
            <option value="laos">Lào</option>
            <option value="cambodia">Campuchia</option>
        </select>

        <label for="province_vietnam">Chọn tỉnh/thành phố:</label>
        <select name="province_vietnam" id="province_vietnam">
            <option value="">Chọn tỉnh/thành phố</option>
        </select>

        <label for="university_vietnam">Chọn trường đại học:</label>
        <select name="university_vietnam" id="university_vietnam">
            <option value="">Chọn trường đại học</option>
        </select>

        <label for="year_vietnam">Chọn năm:</label>
        <select name="year_vietnam" id="year_vietnam">
            <option value="">Chọn năm</option>
        </select>

        <!-- Nơi hiển thị kết quả -->
        <div id="search-results"></div>
    </section>
</main>



<script>
    jQuery(document).ready(function($) {
        // Hàm thực hiện tìm kiếm và tải bài viết
        function performSearch() {
            var postType = $('#post_type').val();  // Lấy giá trị post_type
            var provinceSlug = $('#province_vietnam').val(); // Lấy slug của tỉnh
            var universitySlug = $('#university_vietnam').val(); // Lấy slug của trường đại học
            var yearSlug = $('#year_vietnam').val(); // Lấy slug của năm

            // Thực hiện Ajax để tải bài viết
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'GET',
                data: {
                    action: 'search_posts',
                    post_type: postType,
                    province_slug: provinceSlug,
                    university_slug: universitySlug,
                    year_slug: yearSlug
                },
                success: function(response) {
                    if (response.success) {
                        var resultHtml = '';
                        response.data.forEach(function(post) {
                            resultHtml += '<p><a href="' + post.link + '">' + post.title + '</a></p>';
                        });
                        $('#search-results').html(resultHtml);
                    } else {
                        $('#search-results').html('<p>Không có bài viết nào.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', error);
                    $('#search-results').html('<p>Có lỗi xảy ra khi tìm kiếm.</p>');
                }
            });
        }

        // Khi chọn quốc gia
        $('#post_type').change(function() {
            var postType = $(this).val();
            localStorage.setItem('post_type', postType); // Lưu giá trị post_type vào localStorage

            if (postType === 'vietnam') {
                // Nạp dữ liệu tỉnh, trường đại học, và năm khi chọn Việt Nam
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'GET',
                    data: {
                        action: 'load_filters',
                        post_type: postType
                    },
                    success: function(response) {
                        if (response.success) {
                            // Nạp dữ liệu tỉnh vào dropdown
                            $('#province_vietnam').html('<option value="">Chọn tỉnh/thành phố</option>');
                            response.data.provinces.forEach(function(province) {
                                $('#province_vietnam').append('<option value="' + province.slug + '">' + province.name + '</option>');
                            });

                            // Nạp dữ liệu trường đại học vào dropdown
                            $('#university_vietnam').html('<option value="">Chọn trường đại học</option>');
                            response.data.universities.forEach(function(university) {
                                $('#university_vietnam').append('<option value="' + university.slug + '">' + university.name + '</option>');
                            });

                            // Nạp dữ liệu năm vào dropdown
                            $('#year_vietnam').html('<option value="">Chọn năm</option>');
                            response.data.years.forEach(function(year) {
                                $('#year_vietnam').append('<option value="' + year.slug + '">' + year.name + '</option>');
                            });

                            // Hiển thị tất cả các bài viết của Việt Nam
                            performSearch();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', error);
                    }
                });
            } else {
                $('#province_vietnam').html('<option value="">Chọn tỉnh/thành phố</option>');
                $('#university_vietnam').html('<option value="">Chọn trường đại học</option>');
                $('#year_vietnam').html('<option value="">Chọn năm</option>');
                $('#search-results').html('');
            }
        });

        // Trigger tìm kiếm khi người dùng thay đổi bất kỳ lựa chọn nào
        $('#province_vietnam, #university_vietnam, #year_vietnam').change(function() {
            performSearch();
        });

        // Khôi phục trạng thái từ localStorage khi tải trang
        var savedPostType = localStorage.getItem('post_type');
        if (savedPostType) {
            $('#post_type').val(savedPostType).trigger('change');
        }
    });
</script>

<?php get_footer(); ?>
