<?php
/**
 * The header for our theme
 *
 * @package mintokumobile
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="header">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'mintokumobile'); ?></a>
    <header id="header_s" style="display: none">
        <div class="language-switcher">
            <div class="current-language">
                <span id="current-lang"></span>
                <i class="arrow-down"></i> <!-- Thêm mũi tên chỉ xuống -->
            </div>
            <ul class="language-list sub-menu">
                <?php
                if (function_exists('pll_the_languages')) {
                    pll_the_languages(array(
                        'show_flags' => 1, // Hiển thị cờ quốc gia
                        'show_names' => 1, // Hiển thị tên ngôn ngữ
                        'hide_if_empty' => 0, // Hiển thị ngôn ngữ ngay cả khi không có bản dịch
                        'display_names_as' => 'name', // Hiển thị tên ngôn ngữ dưới dạng slug
                    ));
                }
                ?>
            </ul>
        </div>
    </header>
</div>

<style>
    /* CSS cho phần header */
    #header_s {
        background-color: #f5f5f5;
        padding: 20px;
        display: flex;
        justify-content: flex-end;
    }

    /* CSS cho phần chuyển ngôn ngữ */
    .language-switcher {
        position: relative;
        display: inline-block;
        width: 200px; /* Độ rộng của dropdown */
        font-family: Arial, sans-serif;
        cursor: pointer;
    }

    /* Hiển thị ngôn ngữ hiện tại */
    .current-language {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Thêm mũi tên chỉ xuống */
    .arrow-down {
        border: solid black;
        border-width: 0 3px 3px 0;
        display: inline-block;
        padding: 3px;
        transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
    }

    /* Dropdown menu (ẩn ban đầu) */
    .language-list {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 5px;
        z-index: 100;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        padding: 0;
        margin: 0;
        list-style: none;
        /*max-height: 200px; !* Đặt giới hạn chiều cao cho dropdown *!*/
        overflow-y: auto; /* Thêm thanh cuộn nếu có quá nhiều mục */
    }

    /* Các mục trong dropdown */
    .language-list li {
        padding: 10px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #eee;
        transition: background-color 0.3s;
    }

    .language-list li:last-child {
        border-bottom: none;
    }

    .language-list li a {
        text-decoration: none;
        color: #333;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .language-list li a img {
        margin-right: 8px;
        width: 24px;
        height: auto;
    }

    /* Hiệu ứng hover */
    .language-list li:hover {
        background-color: #f1f1f1;
    }

    /* Hiển thị dropdown khi click */
    .language-switcher.open .language-list {
        display: block;
    }

    /* Kiểu cho ngôn ngữ hiện tại */
    .language-switcher .current-lang {
        background-color: #f5f5f5;
    }
</style>

<?php wp_footer(); ?>
<script type="text/javascript">
    // document.addEventListener("DOMContentLoaded", function() {
    //     // Gửi yêu cầu AJAX để lấy dữ liệu dịch từ bảng trong cơ sở dữ liệu
    //     jQuery.ajax({
    //         url: ajax_object.ajax_url,
    //         type: 'POST',
    //         data: {
    //             action: 'get_translation_json' // Tên action trong PHP
    //         },
    //         success: function(response) {
    //             if (response.success) {
    //                 var jsonData = JSON.parse(response.data.json_data);
    //                 console.log("Dữ liệu dịch từ cơ sở dữ liệu:", jsonData);
    //
    //                 // Duyệt qua tất cả các phần tử có thuộc tính data-translate
    //                 document.querySelectorAll('[data-translate]').forEach(function(el) {
    //                     var key = el.getAttribute('data-translate');
    //
    //                     // Kiểm tra và thay thế văn bản nếu có key trong JSON
    //                     if (jsonData[key]) {
    //                         el.innerText = jsonData[key];
    //                     }
    //                 });
    //             } else {
    //                 console.log("Lỗi khi tải dữ liệu JSON:", response.data.message);
    //             }
    //         },
    //         error: function() {
    //             console.log("Lỗi khi gửi yêu cầu AJAX.");
    //         }
    //     });
    // });
</script>

</body>
</html>
