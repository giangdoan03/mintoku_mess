<?php
/**
 * Plugin Name: Edit JSON Plugin
 * Description: Plugin cho phép chỉnh sửa và lưu file JSON từ trang quản trị WordPress.
 * Version: 1.0
 * Author: Giang Đoàn
 */

// Tạo bảng để lưu dữ liệu JSON khi kích hoạt plugin
function my_custom_json_plugin_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'json_data'; // Đặt tên bảng

    $charset_collate = $wpdb->get_charset_collate();

    // Tạo câu lệnh SQL để tạo bảng
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        json_content LONGTEXT NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Gọi dbDelta để chạy câu lệnh SQL
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'my_custom_json_plugin_activate' );

// Tạo menu quản trị tùy chỉnh
function my_custom_json_page() {
    add_menu_page(
        'JSON Languages', // Tiêu đề trang
        'JSON Languages', // Tiêu đề menu
        'manage_options', // Quyền người dùng
        'edit-json', // Slug của trang
        'my_custom_json_page_content', // Hàm callback hiển thị nội dung
        'dashicons-edit', // Icon menu
        20 // Vị trí menu
    );
}
add_action( 'admin_menu', 'my_custom_json_page' );

// Hàm hiển thị nội dung trang quản trị và xử lý form
// Hàm hiển thị nội dung trang quản trị và xử lý form
function my_custom_json_page_content() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'json_data';

    // Lấy JSON từ cơ sở dữ liệu (dòng mới nhất)
    $json_data = $wpdb->get_var( "SELECT json_content FROM $table_name ORDER BY id DESC LIMIT 1" );

    // Nếu $json_data là null, đặt nó thành chuỗi rỗng để tránh lỗi
    $json_data = $json_data !== null ? $json_data : '';

    ?>
    <div class="wrap">
        <h1>Edit file JSON</h1>
        <form id="json-editor-form" method="POST">
            <textarea id="json_data" name="json_data" style="width:100%;height:80vh;"><?php echo esc_textarea($json_data); ?></textarea>
            <br><br>
            <input type="submit" name="save_json" class="button button-primary" value="Lưu JSON">
        </form>
        <div id="json-save-response"></div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#json-editor-form').on('submit', function(e) {
                e.preventDefault(); // Ngăn chặn việc submit form truyền thống

                var jsonData = $('#json_data').val();

                $.ajax({
                    url: ajaxurl, // URL AJAX của WordPress
                    type: 'POST',
                    data: {
                        action: 'save_json_data', // Tên action trong PHP
                        json_data: jsonData
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#json-save-response').html('<div class="updated"><p>' + response.data.message + '</p></div>');
                        } else {
                            $('#json-save-response').html('<div class="error"><p>' + response.data.message + '</p></div>');
                        }
                    }
                });
            });
        });
    </script>
    <?php
}

// Hàm xử lý lưu JSON qua AJAX vào cơ sở dữ liệu
function my_save_json_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'json_data'; // Tên bảng

    // Kiểm tra quyền người dùng
    if ( !current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Bạn không có quyền thực hiện hành động này.' ) );
    }

    // Nhận dữ liệu từ AJAX request
    if ( isset( $_POST['json_data'] ) ) {
        $new_json_data = stripslashes( $_POST['json_data'] );

        // Kiểm tra tính hợp lệ của JSON
        $json_decoded = json_decode($new_json_data, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            // Kiểm tra xem có bản ghi trong bảng không
            $existing_entry = $wpdb->get_var( "SELECT id FROM $table_name LIMIT 1" );

            if ($existing_entry) {
                // Nếu có bản ghi, cập nhật bản ghi đó
                $wpdb->update(
                    $table_name,
                    array(
                        'json_content' => $new_json_data, // Dữ liệu mới
                    ),
                    array( 'id' => $existing_entry ), // Điều kiện để cập nhật (dựa trên id)
                    array( '%s' ), // Loại dữ liệu cho json_content
                    array( '%d' ) // Loại dữ liệu cho id
                );
                wp_send_json_success( array( 'message' => 'Dữ liệu đã được cập nhật thành công!' ) );
            } else {
                // Nếu không có bản ghi, chèn mới dữ liệu
                $wpdb->insert(
                    $table_name,
                    array(
                        'json_content' => $new_json_data, // Lưu JSON
                    ),
                    array('%s') // Loại dữ liệu: chuỗi
                );
                wp_send_json_success( array( 'message' => 'Dữ liệu đã được chèn mới thành công!' ) );
            }
        } else {
            wp_send_json_error( array( 'message' => 'Lỗi: Dữ liệu JSON không hợp lệ!' ) );
        }
    } else {
        wp_send_json_error( array( 'message' => 'Dữ liệu JSON bị thiếu!' ) );
    }
}


add_action( 'wp_ajax_save_json_data', 'my_save_json_data' );

// Hàm để đọc JSON từ cơ sở dữ liệu và trả về frontend
function my_get_json_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'json_data'; // Tên bảng

    // Lấy dữ liệu JSON mới nhất từ bảng
    $json_data = $wpdb->get_var( "SELECT json_content FROM $table_name ORDER BY id DESC LIMIT 1" );

    if ($json_data !== null) {
        wp_send_json_success( array( 'json_data' => $json_data ) );
    } else {
        wp_send_json_error( array( 'message' => 'Không có dữ liệu JSON nào.' ) );
    }
}

add_action( 'wp_ajax_get_json_data', 'my_get_json_data' );
add_action( 'wp_ajax_nopriv_get_json_data', 'my_get_json_data' ); // Cho phép người dùng chưa đăng nhập cũng có thể lấy dữ liệu
