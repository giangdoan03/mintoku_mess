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
                                <?php if (have_rows('mo_ta')): ?>
                                    <?php while (have_rows('mo_ta')): the_row(); ?>
                                        <div class="mo-ta-item">
                                            <h3><?php the_sub_field('tieu_de'); ?></h3>
                                            <p><?php the_sub_field('noi_dung'); ?></p>
                                        </div>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </div>

                        <?php elseif (get_row_layout() == 'slide_2'): ?>
                            <div class="slide slide-2 swiper-slide">
                                <?php if (have_rows('yeu_cau')): ?>
                                    <?php while (have_rows('yeu_cau')): the_row(); ?>
                                        <div class="mo-ta-item">
                                            <h3><?php the_sub_field('tieu_de'); ?></h3>
                                            <p><?php the_sub_field('noi_dung'); ?></p>
                                        </div>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </div>

                        <?php elseif (get_row_layout() == 'slide_3'): ?>
                            <div class="slide slide-3 swiper-slide">

                                <div class="slideshow-container">
                                    <!-- Slide 2 - Video YouTube -->
                                    <div class="item_slide">
                                        <?php if ($video = get_sub_field('video')): ?>
                                            <?php
                                            // Lấy URL của video YouTube từ iframe
                                            preg_match("/embed\/([^\"]+)/", $video, $matches);
                                            $video_id = $matches[1];

                                            // URL của ảnh thumbnail
                                            $thumbnail_url = "https://img.youtube.com/vi/$video_id/maxresdefault.jpg";
                                            ?>

                                            <!-- Hiển thị ảnh preview và khi click vào thì hiện iframe -->
                                            <div class="item_slide">
                                                <div class="video-thumbnail" style="position: relative; cursor: pointer;">
                                                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="Video Thumbnail" style="width: 100%; height: auto;">
                                                    <!-- Nút Play (biểu tượng) trên ảnh preview -->
                                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                                        <img src="path_to_play_button_image.png" alt="Play Button" style="width: 64px; height: 64px;">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Nút Play sẽ thay đổi thành iframe khi người dùng click -->
                                            <script>
                                                document.querySelector('.video-thumbnail').addEventListener('click', function() {
                                                    // Thay thế ảnh preview bằng iframe video YouTube
                                                    this.innerHTML = '<?php echo addslashes($video); ?>';
                                                });
                                            </script>
                                        <?php endif; ?>

                                        <?php if ($image_job = get_sub_field('image_job')): ?>
                                            <?php foreach ($image_job as $image): ?>
                                               <div class="item_slide">
                                                   <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>"/>
                                               </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p>No images found.</p>
                                        <?php endif; ?>
                                    </div>

                                    <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
                                    <a class="next" onclick="changeSlide(1)">&#10095;</a>
                                </div>
                                <div class="thumbnails">
                                    <?php if ($image_job = get_sub_field('image_job')): ?>
                                        <?php foreach ($image_job as $index => $image): // Sử dụng $index để lấy vị trí của từng hình ảnh ?>
                                            <div class="thumbnail" onclick="showSlides(<?php echo $index; ?>)">
                                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p>No images found.</p>
                                    <?php endif; ?>
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
    let currentSlide = 0;
    showSlides(currentSlide);

    function changeSlide(n) {
        currentSlide += n;
        const totalSlides = document.getElementsByClassName('item_slide').length;
        if (currentSlide >= totalSlides) {
            currentSlide = 0; // Quay lại slide đầu tiên
        }
        if (currentSlide < 0) {
            currentSlide = totalSlides - 1; // Quay lại slide cuối cùng
        }
        showSlides(currentSlide);
    }

    function showSlides(n) {
        const slides = document.getElementsByClassName('item_slide');
        const thumbnails = document.querySelectorAll('.thumbnail img');

        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
            thumbnails[i].classList.remove('active');
        }

        slides[n].style.display = "block";
        thumbnails[n].classList.add('active');
    }

</script>
