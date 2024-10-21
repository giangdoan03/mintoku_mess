<?php get_header(); ?>
<?php
// Get the current post type
$post_type = get_post_type();

$taxonomy = ''; // Initialize taxonomy variable
$terms_data = []; // Array to hold term data

// Determine the taxonomy based on the post type
switch ($post_type) {
    case 'vietnam':
        $taxonomy = 'province_vietnam';
        break;
    default:
        $taxonomy = ''; // Set a default value if needed
        break;
}

if ($taxonomy) {
    $terms = get_the_terms(get_the_ID(), $taxonomy);
    if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $terms_data[] = [
                'name' => esc_html($term->name),
                'link' => esc_url(get_term_link($term))
            ];
        }
    }
}

?>

<div id="page-single-job" class="page_single_job">
    <div class="">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if (have_rows('job_info')): ?>
                    <?php while (have_rows('job_info')): the_row(); ?>

                        <?php if (get_row_layout() == 'slide_1'): ?>
                            <div class="slide slide-1 swiper-slide">
                                <div class="logo_mintoku_mess">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo_slide_1.png"
                                         alt="company mintoku mess">
                                </div>
                                <?php
                                // Lấy URL ảnh đại diện
                                $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'medium');
                                if (!$thumbnail_url) {
                                    $thumbnail_url = 'https://placehold.co/600x400';
                                }

                                // Lấy các term của taxonomy company_vietnam
                                $company_terms = wp_get_post_terms($post->ID, 'company_vietnam', array('fields' => 'all'));
                                $company_image_url = '';
                                $company_name = '';

                                if (!empty($company_terms)) {
                                    $company_term_id = $company_terms[0]->term_id;
                                    $company_name = $company_terms[0]->name;

                                    $company_image_id = get_term_meta($company_term_id, 'company_image', true);
                                    if (!empty($company_image_id)) {
                                        $company_image_url = wp_get_attachment_url($company_image_id);
                                    }
                                    if (!$company_image_url) {
                                        $company_image_url = 'https://placehold.co/100x100';
                                    }
                                }

                                ?>
                                <div class="avatar_job">
                                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>">
                                </div>

                                <div class="job_info_slide_1">
                                    <div class="job-item">
                                        <p class="title_job">
                                            <?php the_title(); ?>
                                        </p>
                                        <div class="job_content">
                                            <div class="container">
                                                <div class="text_info_job">
                                                    <?php if ($company_image_url) : ?>
                                                        <div class="company-info ">
                                                            <div class="bl_logo">
                                                                <p class="logo_company">
                                                                    <img src="<?php echo esc_url($company_image_url); ?>"
                                                                         alt="Company Image">
                                                                </p>
                                                                <!-- Hiển thị tên công ty -->
                                                                <?php if ($company_name) : ?>
                                                                    <p class="company_name"><?php echo esc_html($company_name); ?></p>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="box_w">
                                                                <div class="hashtag">
                                                                    <span class="tag_item">土日祝休み</span>
                                                                    <span class="tag_item">昇給賞与あり</span>
                                                                    <span class="tag_item">個室あり</span>
                                                                    <span class="tag_item">夜勤あり</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="salary">
                                                            <span class="label_text">Lương</span>
                                                            <span class="salary_text">
                                                             <?php
                                                             // Lấy nội dung của Flexible Content
                                                             $slides = get_field('job_info'); // 'job_info' là tên của Flexible Content field chứa các slide

                                                             // Gọi hàm và truyền tên field cần lấy
                                                             $noi_dung_1 = lay_noi_dung_field($slides, 'noi_dung_1');

                                                             // In ra nội dung của noi_dung_1
                                                             echo $noi_dung_1;
                                                             ?>

                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <!-- Hiển thị ảnh đại diện bài viết -->
                                                    <div class="text_desc">
                                                        <?php
                                                        // Lấy giá trị của custom field ACF
                                                        $short_job_description = get_field('short_job_description');

                                                        // Kiểm tra nếu trường này có giá trị
                                                        if (!empty($short_job_description)) : ?>
                                                            <div class="summary_job">
                                                                <p><?php echo esc_html($short_job_description); ?></p>
                                                            </div>
                                                        <?php endif; ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                // Lấy nội dung của Flexible Content
                                $slides = get_field('job_info'); // 'job_info' là tên của Flexible Content field chứa các slide

                                // Kiểm tra nếu có các slide
                                if ($slides) {
                                    // Lặp qua các layout của Flexible Content
                                    foreach ($slides as $slide) {
                                        // Kiểm tra nếu layout là slide_1
                                        if ($slide['acf_fc_layout'] === 'slide_1') {

                                            // Lấy thông tin chi tiết cho từng trường noi_dung_1 đến noi_dung_5 không dùng vòng lặp
                                            $noi_dung_1 = get_sub_field_object('noi_dung_1');
                                            $noi_dung_2 = get_sub_field_object('noi_dung_2');
                                            $noi_dung_3 = get_sub_field_object('noi_dung_3');
                                            $noi_dung_4 = get_sub_field_object('noi_dung_4');
                                            $noi_dung_5 = get_sub_field_object('noi_dung_5');

                                            echo '<div class="container">';

                                            // Hiển thị từng nội dung với điều kiện nội dung có giá trị
                                            if (!empty($slide['noi_dung_1']) && $noi_dung_1) {
                                                echo '<div class="noi-dung-field">';
                                                echo '<strong>' . esc_html($noi_dung_1['label']) . ':</strong> '; // Hiển thị label
                                                echo '<p>' . esc_html($slide['noi_dung_1']) . '</p>'; // Hiển thị giá trị của field
                                                echo '</div>';
                                            }

                                            if (!empty($slide['noi_dung_2']) && $noi_dung_2) {
                                                echo '<div class="noi-dung-field">';
                                                echo '<strong>' . esc_html($noi_dung_2['label']) . ':</strong> '; // Hiển thị label
                                                echo '<p>' . esc_html($slide['noi_dung_2']) . '</p>'; // Hiển thị giá trị của field
                                                echo '</div>';
                                            }

                                            if (!empty($slide['noi_dung_3']) && $noi_dung_3) {
                                                echo '<div class="noi-dung-field">';
                                                echo '<strong>' . esc_html($noi_dung_3['label']) . ':</strong> '; // Hiển thị label
                                                echo '<p>' . esc_html($slide['noi_dung_3']) . '</p>'; // Hiển thị giá trị của field
                                                echo '</div>';
                                            }

                                            if (!empty($slide['noi_dung_4']) && $noi_dung_4) {
                                                echo '<div class="noi-dung-field">';
                                                echo '<strong>' . esc_html($noi_dung_4['label']) . ':</strong> '; // Hiển thị label
                                                echo '<p>' . esc_html($slide['noi_dung_4']) . '</p>'; // Hiển thị giá trị của field
                                                echo '</div>';
                                            }

                                            if (!empty($slide['noi_dung_5']) && $noi_dung_5) {
                                                echo '<div class="noi-dung-field">';
                                                echo '<strong>' . esc_html($noi_dung_5['label']) . ':</strong> '; // Hiển thị label
                                                echo '<p>' . esc_html($slide['noi_dung_5']) . '</p>'; // Hiển thị giá trị của field
                                                echo '</div>';
                                            }

                                            echo '</div>'; // Đóng container
                                        }
                                    }
                                }
                                ?>

                            </div>

                        <?php elseif (get_row_layout() == 'slide_2'): ?>
                            <div class="slide slide-2 swiper-slide">
                                <div class="container">
                                    <?php
                                    // Lấy nội dung của Flexible Content
                                    $slides = get_field('job_info'); // 'job_info' là tên của Flexible Content field chứa các slide

                                    // Kiểm tra nếu có các slide
                                    if ($slides) {
                                        // Lặp qua các layout của Flexible Content
                                        foreach ($slides as $slide) {
                                            // Kiểm tra nếu layout là slide_2
                                            if ($slide['acf_fc_layout'] === 'slide_2') {
                                                // Lặp qua các field từ noi_dung_1 đến noi_dung_15
                                                for ($i = 1; $i <= 15; $i++) {
                                                    $field_name = 'noi_dung_' . $i;

                                                    // Lấy thông tin chi tiết của field (bao gồm cả label)
                                                    $field_object = get_sub_field_object($field_name);

                                                    if (!empty($slide[$field_name]) && $field_object) {
                                                        // Hiển thị label và value của các trường noi_dung_1 -> noi_dung_15
                                                        echo '<div class="noi-dung-field">';
                                                        echo '<strong>' . esc_html($field_object['label']) . ':</strong> '; // Hiển thị label
                                                        echo '<p class="custom-textarea-content">' . esc_html($slide[$field_name]) . '</p>'; // Hiển thị giá trị của field
                                                        echo '</div>';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php elseif (get_row_layout() == 'slide_3'): ?>
                            <div class="slide slide-3 swiper-slide">
                                <div class="slide_mix">
                                    <div id="main-slider" class="main-slider">
                                        <div class="slider-items">
                                            <?php
                                            // Lấy nội dung của Flexible Content hoặc Repeater Field
                                            $slides = get_field('job_info'); // 'job_info' là tên field Flexible Content chứa các slide

                                            // Kiểm tra nếu có các slide
                                            if ($slides) {
                                                $index = 0; // Bắt đầu từ 0
                                                // Lặp qua các layout của Flexible Content
                                                foreach ($slides as $slide) {
                                                    // Kiểm tra nếu layout là slide_3
                                                    if (isset($slide['acf_fc_layout']) && $slide['acf_fc_layout'] === 'slide_3') {
                                                        ?>
                                                        <?php
                                                        // Lấy gallery image array từ field image_job
                                                        $gallery_images = isset($slide['image_job']) ? $slide['image_job'] : array();
                                                        // Nếu có hình ảnh, hiển thị chúng trong slider
                                                        if (!empty($gallery_images)) {
                                                            foreach ($gallery_images as $image) {
                                                                $image_url = $image['url'];
                                                                $alt_text = $image['alt'];
                                                                ?>
                                                                <div class="slider-item" data-index="<?php echo $index; ?>">
                                                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($alt_text); ?>"/>
                                                                </div>
                                                                <?php $index++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <!-- Thumbnail slider -->
                                    <div id="thumbnail-slider" class="thumbnail-slider">
                                        <div class="container">
                                            <div class="thumbnail-items-wrapper">
                                                <div class="thumbnail-items">
                                                    <?php
                                                    // Tạo thumbnail cho video và hình ảnh
                                                    $video_shown = false; // Biến để kiểm tra xem video đã được hiển thị chưa
                                                    if ($slides) {
                                                        $index = 0; // Bắt đầu từ 0
                                                        foreach ($slides as $slide) {
                                                            if (isset($slide['acf_fc_layout']) && $slide['acf_fc_layout'] === 'slide_3') {

                                                                // Tạo thumbnail cho gallery images
                                                                $gallery_images = isset($slide['image_job']) ? $slide['image_job'] : array();
                                                                if (!empty($gallery_images)) {
                                                                    foreach ($gallery_images as $image) {
                                                                        $image_thumb_url = $image['sizes']['thumbnail'];
                                                                        ?>
                                                                        <div class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>"
                                                                             data-index="<?php echo $index; ?>">
                                                                            <img src="<?php echo esc_url($image_thumb_url); ?>" alt="Image thumbnail"/>
                                                                        </div>
                                                                        <?php
                                                                        $index++;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php elseif (get_row_layout() == 'slide_4'): ?>
                            <div class="slide slide-4 swiper-slide">
                                <div class="container">
                                    <div style="height: 100vh;display: flex; align-items: center; position: relative; bottom: 150px;">
                                        <?php
                                        // Lấy nội dung của Flexible Content hoặc Repeater Field
                                        $job_field = get_field('job_info'); // 'job_info' là tên field Flexible Content chứa các slide

                                        // Kiểm tra nếu có job_field và layout 'slide_4' tồn tại
                                        if ($job_field) {
                                            // Tìm layout 'slide_4'
                                            foreach ($job_field as $slide) {
                                                if ($slide['acf_fc_layout'] === 'slide_4') {
                                                    // Kiểm tra và lấy 'video_url' từ 'slide_4'
                                                    if (!empty($slide['video_url'])) {
                                                        $video_url = esc_js($slide['video_url']); // Thoát URL video an toàn
                                                        ?>
                                                        <script type="text/javascript">
                                                            var Eviry = Eviry || {};
                                                            Eviry.Player || (Eviry.Player = {});
                                                            Eviry.Player.embedkey = "<?php echo $video_url; ?>";
                                                        </script>
                                                        <script type="text/javascript" src="https://d1euehvbqdc1n9.cloudfront.net/001/eviry/js/eviry.player.min.js"></script>
                                                        <?php
                                                        break; // Dừng vòng lặp khi đã tìm thấy và hiển thị video_url
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>


                        <?php endif; ?>

                    <?php endwhile; ?>

                <?php endif; ?>
            </div>

            <!-- Navigation buttons -->
            <div class="box_btn_navigation">
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>
    <!--    <i class="fab fa-facebook-messenger">-->
    <div class="btn_action_fixed">
<!--        <div class="chk_">-->
<!--            <input type="checkbox" id="agree" name="agree" value="Bike">-->
<!--            <label for="agree">利用規約に同意する</label><br>-->
<!--        </div>-->
        <div class="btn_action">
            <div class="message_link">
                <?php
                $messenger_link = get_field('messenger_link_facebook', 'option');
                if ($messenger_link) {
                    echo '<a target="_blank" href="' . esc_url($messenger_link) . '">Ứng tuyển</a>';
                }
                ?>
            </div>
            <div class="back_page">
                <a href="#" id="backButton">Quay lại</a>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>

<script>

    var taxonomyTerms = <?php echo json_encode($terms_data); ?>;
    var previousPage = document.referrer;

    document.addEventListener('DOMContentLoaded', function () {
        // Khởi tạo slider chính (swiper)
        var swiperP = new Swiper('.swiper-container', {
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoHeight: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            slidesPerView: 1,
        });

    });


    document.addEventListener("DOMContentLoaded", function () {
        const sliderItems = document.querySelectorAll('.slider-item'); // Các ảnh lớn
        const thumbnailItems = document.querySelectorAll('.thumbnail-item'); // Các thumbnail
        let currentPosition = 0;

        // Hàm cập nhật vị trí của slider chính khi nhấp vào thumbnail hoặc khi trang load
        function updateSlider(index) {
            // Check if sliderItems is defined and contains elements
            if (!sliderItems || sliderItems.length === 0) {
                console.error("sliderItems is not defined or empty");
                return;
            }

            // Ensure the index is within bounds
            if (index < 0 || index >= sliderItems.length) {
                console.error("Invalid index:", index);
                return;
            }

            // Hide all slider items
            sliderItems.forEach((item) => {
                item.style.display = 'none';
            });

            // Show the corresponding large image
            sliderItems[index].style.display = 'block';

            // Highlight the corresponding thumbnail
            thumbnailItems.forEach((item, i) => {
                item.classList.toggle('active', i === index);
            });

            // Update the current position of the slider
            currentPosition = index;
        }

        // Sự kiện click cho từng thumbnail
        thumbnailItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                updateSlider(index); // Cập nhật slider chính khi nhấp vào thumbnail
            });
        });

        // Khi load trang, active item đầu tiên nếu chưa có class active
        if (!document.querySelector('.thumbnail-item.active')) {
            updateSlider(0); // Hiển thị và kích hoạt thumbnail đầu tiên nếu chưa có cái nào được active
        }
    });

    // $(document).ready(function () {
    //     // Khi trang tải, disable link
    //     $('.message_link a').addClass('disabled-link');
    //
    //     // Bắt sự kiện khi checkbox được click
    //     $('#agree').on('change', function () {
    //         if ($(this).is(':checked')) {
    //             // Nếu checkbox được chọn, enable link
    //             $('.message_link a').removeClass('disabled-link');
    //         } else {
    //             // Nếu checkbox không được chọn, disable link
    //             $('.message_link a').addClass('disabled-link');
    //         }
    //     });
    // });

    document.getElementById('backButton').addEventListener('click', function() {
        if (window.history.length > 1) {
            window.history.go(-1); // Quay lại 2 trang trong lịch sử
            var baseURL = window.location.origin;
        } else {
            // Lấy URL gốc từ domain hiện tại (bao gồm cả localhost và hosting)
            var baseURL = window.location.origin;

            // Kiểm tra xem bạn đang ở localhost hay trên hosting
            if (baseURL.includes('localhost')) {
                // Nếu đang ở localhost, sử dụng đường dẫn tương ứng với localhost
                window.location.href = baseURL + "/mintoku_mobile/list-job/";
            } else {
                // Nếu không phải localhost, sử dụng đường dẫn cho hosting
                window.location.href = baseURL + "/list-job/";
            }
        }
    });


</script>
