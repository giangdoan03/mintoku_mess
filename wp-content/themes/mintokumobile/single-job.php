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
                                <div id="main-slider" class="main-slider">
                                    <div class="slider-items">
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

                                                    // Nếu có video, hiển thị video trong slider item đầu tiên
                                                    if( !empty($video_url) ) {
                                                        // Lấy ID video từ URL
                                                        $parsed_url = parse_url($video_url);
                                                        if( isset($parsed_url['query']) ) {
                                                            parse_str($parsed_url['query'], $query_params);
                                                            if( isset($query_params['v']) && !empty($query_params['v']) ) {
                                                                $video_id = $query_params['v'];
                                                                $video_embed_url = 'https://www.youtube.com/embed/' . $video_id;
                                                                ?>
                                                                <div class="slider-item">
                                                                    <iframe width="100%" height="315" src="<?php echo esc_url($video_embed_url); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                                </div>
                                                                <?php
                                                            }
                                                        }
                                                    }

                                                    // Lấy gallery image array từ field image_job
                                                    $gallery_images = isset($slide['image_job']) ? $slide['image_job'] : array();

                                                    // Nếu có hình ảnh, hiển thị chúng trong slider
                                                    if( !empty($gallery_images) ) {
                                                        foreach( $gallery_images as $image ) {
                                                            $image_url = $image['url'];
                                                            $alt_text = $image['alt'];
                                                            ?>
                                                            <div class="slider-item">
                                                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($alt_text); ?>" />
                                                            </div>
                                                            <?php
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
                                    <div class="thumbnail-items">
                                        <?php
                                        // Tạo thumbnail cho video và hình ảnh
                                        if( $slides ) {
                                            foreach( $slides as $slide ) {
                                                if( isset($slide['acf_fc_layout']) && $slide['acf_fc_layout'] === 'slide_3' ) {
                                                    $video_url = isset($slide['video_url']) ? $slide['video_url'] : '';

                                                    // Tạo thumbnail cho video
                                                    if( !empty($video_url) ) {
                                                        $parsed_url = parse_url($video_url);
                                                        if( isset($query_params['v']) && !empty($query_params['v']) ) {
                                                            $video_id = $query_params['v'];
                                                            // Lấy ảnh thumbnail YouTube
                                                            $video_thumb = 'https://img.youtube.com/vi/' . $video_id . '/0.jpg';
                                                            ?>
                                                            <div class="thumbnail-item" data-index="0">
                                                                <img src="<?php echo esc_url($video_thumb); ?>" alt="Video thumbnail" />
                                                            </div>
                                                            <?php
                                                        }
                                                    }

                                                    // Tạo thumbnail cho gallery images
                                                    $gallery_images = isset($slide['image_job']) ? $slide['image_job'] : array();
                                                    $index = 1; // Video là item đầu tiên (index 0), ảnh bắt đầu từ 1
                                                    if( !empty($gallery_images) ) {
                                                        foreach( $gallery_images as $image ) {
                                                            $image_thumb_url = $image['sizes']['thumbnail'];
                                                            ?>
                                                            <div class="thumbnail-item" data-index="<?php echo $index; ?>">
                                                                <img src="<?php echo esc_url($image_thumb_url); ?>" alt="Image thumbnail" />
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

    jQuery(document).ready(function($) {
        // Kiểm tra nếu có video và hiển thị video thay vì gallery
        if ($('.video-container iframe').length) {
            // Nếu có video thì hiển thị video và ẩn gallery
            $('.video-container').show();
            $('.gallery-container').hide();
        } else {
            // Nếu không có video thì hiển thị gallery
            $('.gallery-container').show();
            $('.video-container').hide();
        }
    });


    document.addEventListener("DOMContentLoaded", function() {
        const sliderItems = document.querySelectorAll('.slider-item');
        const thumbnailItems = document.querySelectorAll('.thumbnail-item');

        // Set initial active slide
        let activeIndex = 0;
        sliderItems[activeIndex].style.transform = `translateX(0)`;
        thumbnailItems[activeIndex].classList.add('active');

        // Function to switch slides
        function switchSlide(index) {
            // Remove active class from all thumbnails
            thumbnailItems.forEach(item => item.classList.remove('active'));

            // Add active class to clicked thumbnail
            thumbnailItems[index].classList.add('active');

            // Move the slider to the correct slide
            const offset = -index * 100; // 100% for each slide
            sliderItems.forEach(item => {
                item.style.transform = `translateX(${offset}%)`;
            });

            activeIndex = index; // Update active index
        }

        // Add click event to thumbnails
        thumbnailItems.forEach((item, index) => {
            item.addEventListener('click', function() {
                switchSlide(index);
            });
        });
    });



</script>
