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
    <div class="container">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if (have_rows('job_info')): ?>
                    <?php while (have_rows('job_info')): the_row(); ?>

                        <?php if (get_row_layout() == 'slide_1'): ?>
                            <div class="slide slide-1 swiper-slide">
                                <?php
                                // Lấy nội dung của Flexible Content
                                $slides = get_field('job_info'); // 'job_info' là tên của Flexible Content field chứa các slide

                                // Kiểm tra nếu có các slide
                                if( $slides ) {
                                    // Lặp qua các layout của Flexible Content
                                    foreach( $slides as $slide ) {
                                        // Kiểm tra nếu layout là slide_2
                                        if( $slide['acf_fc_layout'] === 'slide_1' ) {
                                            // Lặp qua các field từ noi_dung_1 đến noi_dung_15
                                            for( $i = 1; $i <= 5; $i++ ) {
                                                $field_name = 'noi_dung_' . $i;

                                                // Lấy thông tin chi tiết của field (bao gồm cả label)
                                                $field_object = get_sub_field_object($field_name);

                                                if( !empty($slide[$field_name]) && $field_object ) {
                                                    // Hiển thị label và value của các trường noi_dung_1 -> noi_dung_15
                                                    echo '<div class="noi-dung-field">';
                                                    echo '<strong>' . esc_html($field_object['label']) . ':</strong> '; // Hiển thị label
                                                    echo '<p>' . esc_html($slide[$field_name]) . '</p>'; // Hiển thị giá trị của field
                                                    echo '</div>';
                                                }
                                            }
                                        }
                                    }
                                }
                                ?>
                            </div>

                        <?php elseif (get_row_layout() == 'slide_2'): ?>
                            <div class="slide slide-2 swiper-slide">
                                <?php
                                // Lấy nội dung của Flexible Content
                                $slides = get_field('job_info'); // 'job_info' là tên của Flexible Content field chứa các slide

                                // Kiểm tra nếu có các slide
                                if( $slides ) {
                                    // Lặp qua các layout của Flexible Content
                                    foreach( $slides as $slide ) {
                                        // Kiểm tra nếu layout là slide_2
                                        if( $slide['acf_fc_layout'] === 'slide_2' ) {
                                            // Lặp qua các field từ noi_dung_1 đến noi_dung_15
                                            for( $i = 1; $i <= 15; $i++ ) {
                                                $field_name = 'noi_dung_' . $i;

                                                // Lấy thông tin chi tiết của field (bao gồm cả label)
                                                $field_object = get_sub_field_object($field_name);

                                                if( !empty($slide[$field_name]) && $field_object ) {
                                                    // Hiển thị label và value của các trường noi_dung_1 -> noi_dung_15
                                                    echo '<div class="noi-dung-field">';
                                                    echo '<strong>' . esc_html($field_object['label']) . ':</strong> '; // Hiển thị label
                                                    echo '<p>' . esc_html($slide[$field_name]) . '</p>'; // Hiển thị giá trị của field
                                                    echo '</div>';
                                                }
                                            }
                                        }
                                    }
                                }
                                ?>


                            </div>

                        <?php elseif (get_row_layout() == 'slide_3'): ?>
                            <div class="slide slide-3 swiper-slide">

                                <?php
                                // Lấy nội dung của Flexible Content hoặc Repeater Field
                                $slides = get_field('job_info'); // 'job_info' là tên field Flexible Content chứa các slide

                                // Kiểm tra nếu có các slide
                                if( $slides ) {
                                    // Lặp qua các layout của Flexible Content
                                    foreach( $slides as $slide ) {
                                        // Kiểm tra nếu layout là slide_3
                                        if( isset($slide['acf_fc_layout']) && $slide['acf_fc_layout'] === 'slide_3' ) {
                                            // Lấy URL video từ slide_3
                                            $video_url = isset($slide['video_url']) ? $slide['video_url'] : '';

                                            // Nếu có URL video
                                            if( !empty($video_url) ) {
                                                // Sử dụng parse_url để phân tích cú pháp URL
                                                $parsed_url = parse_url($video_url);

                                                // Kiểm tra nếu URL chứa tham số truy vấn (query) và có `v` (ID video)
                                                if( isset($parsed_url['query']) ) {
                                                    parse_str($parsed_url['query'], $query_params);

                                                    // Kiểm tra nếu `v` (video ID) tồn tại trong tham số truy vấn
                                                    if( isset($query_params['v']) && !empty($query_params['v']) ) {
                                                        // Lấy ID video
                                                        $video_id = $query_params['v'];

                                                        // Xây dựng URL nhúng
                                                        $video_embed_url = 'https://www.youtube.com/embed/' . $video_id;

                                                        // Hiển thị iframe video
                                                        ?>
                                                        <div class="video-container">
                                                            <iframe width="560" height="315" src="<?php echo esc_url($video_embed_url); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        // Trường hợp thiếu ID video, hiển thị thông báo lỗi
                                                        echo '<p>URL không hợp lệ. Không tìm thấy ID video.</p>';
                                                    }
                                                } else {
                                                    // Trường hợp URL không chứa tham số query
                                                    echo '<p>URL không hợp lệ. Không chứa ID video.</p>';
                                                }
                                            }
                                        }
                                    }
                                }
                                ?>

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

            <!-- Pagination -->
            <div class="box_btn_pagination">
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
    <div class="message_link">
        <?php
        $messenger_link = get_field('messenger_link_facebook', 'option');
        if ($messenger_link) {
            echo '<a target="_blank" href="' . esc_url($messenger_link) . '"><i class="fab fa-facebook-messenger"></i>Ứng tuyển qua Messenger</a>';
        }
        ?>
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
    // let currentSlide = 0;
    // showSlides(currentSlide);
    //
    // function changeSlide(n) {
    //     currentSlide += n;
    //     const totalSlides = document.getElementsByClassName('item_slide').length;
    //     if (currentSlide >= totalSlides) {
    //         currentSlide = 0; // Quay lại slide đầu tiên
    //     }
    //     if (currentSlide < 0) {
    //         currentSlide = totalSlides - 1; // Quay lại slide cuối cùng
    //     }
    //     showSlides(currentSlide);
    // }

    // function showSlides(n) {
    //     const slides = document.getElementsByClassName('item_slide');
    //     const thumbnails = document.querySelectorAll('.thumbnail img');
    //
    //     for (let i = 0; i < slides.length; i++) {
    //         slides[i].style.display = "none";
    //         thumbnails[i].classList.remove('active');
    //     }
    //
    //     slides[n].style.display = "block";
    //     thumbnails[n].classList.add('active');
    // }

</script>
