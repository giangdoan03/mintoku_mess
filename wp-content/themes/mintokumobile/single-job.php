<?php get_header(); ?>
<style>
    /* CSS cho slider lớn */
    .swiper-large-container {
        width: 100%;
        height: 100%;
    }

    .swiper-large-wrapper {
        display: flex;
        transition: transform 0.3s ease;
    }

    .swiper-large-slide {
        flex: 0 0 auto;
        width: 100%; /* Adjust as needed */
    }

    /* CSS cho slider nhỏ bên trong */
    .swiper-small-container {
        width: 100%;
        height: auto;
        max-width: 500px;
        overflow: hidden;
    }
    .swiper-small-slide {
        flex: 0 0 auto;
        width: 100%;
    }

    .swiper-pagination, .swiper-pagination-c {
        text-align: center;
        padding-top: 10px;
    }

    .swiper-button-prev, .swiper-button-next,
    .swiper-button-prev-c, .swiper-button-next-c {
        color: #000;
    }

    @media (max-width: 767px) {
        /*.swiper-large-container {*/
        /*    height: 300px; !* Adjust height for mobile if needed *!*/
        /*}*/
    }
</style>
<div id="taxonomy-terms-container"></div>
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
    case 'laos':
        $taxonomy = 'province_laos';
        break;
    case 'cambodia':
        $taxonomy = 'province_cambodia';
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

// Pass the data to JavaScript
?>

<div id="primary" class="content-area">
    <div id="main" class="site-main" role="main">
        <?php while (have_posts()) : the_post(); ?>
            <div <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header><!-- .entry-header -->
                <div id="slider_content_item">
                    <div class="swiper-large-container content-job-slider">
                        <div class="swiper-wrapper">
                            <!-- Slide 1: content_job_detail -->
                            <div class="swiper-slide content_job_detail">
                                <p> <?php the_title('<h1 class="entry-title">', '</h1>'); ?></p>
                                <?php
                                the_content();

                                // Hiển thị mức lương
                                $salary = get_post_meta(get_the_ID(), get_post_type() . '_salary', true);
                                if ($salary) {
                                    echo '<p><strong>' . __('Salary:', 'textdomain') . '</strong> ' . esc_html($salary) . '</p>';
                                }

                                // Hiển thị các tỉnh
                                $taxonomy = '';
                                switch (get_post_type()) {
                                    case 'vietnam':
                                        $taxonomy = 'province_vietnam';
                                        break;
                                    case 'laos':
                                        $taxonomy = 'province_laos';
                                        break;
                                    case 'cambodia':
                                        $taxonomy = 'province_cambodia';
                                        break;
                                }

                                if ($taxonomy) {
                                    $terms = get_the_terms(get_the_ID(), $taxonomy);
                                    if ($terms && !is_wp_error($terms)) {
                                        echo '<h2>' . __('Provinces') . '</h2><ul>';
                                        foreach ($terms as $term) {
                                            echo '<li>' . esc_html($term->name) . '</li>';
                                        }
                                        echo '</ul>';
                                    }
                                }

                                // Hiển thị các ảnh đã tải lên
                                $images = get_post_meta(get_the_ID(), get_post_type() . '_images', true);
                                if (!empty($images)) {
                                    echo '<h2>' . __('Images') . '</h2>';
                                    echo '<div class="swiper-small-container">';
                                    echo '<div class="swiper-wrapper">';
                                    foreach ($images as $image) {
                                        echo '<div class="swiper-slide">';
                                        echo '<img src="' . esc_url($image) . '" style="width: 100%; height: auto;" />';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                    echo '<div class="swiper-pagination-c"></div>';
                                    echo '<div class="swiper-button-next-c"></div>';
                                    echo '<div class="swiper-button-prev-c"></div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <!-- Slide 2: content_job_visa -->
                            <div class="swiper-slide content_job_visa">
                                <?php
                                $tab1_value = get_post_meta(get_the_ID(), '_my_tab1_key', true);
                                $tab2_value = get_post_meta(get_the_ID(), '_my_tab2_key', true);
                                $tab3_image_ids = get_post_meta($post->ID, '_custom_image_ids', true);

                                if (!empty($tab1_value)) {
                                    echo '<div class="tab1-content">';
                                    echo '<h2>Tab 1</h2>';
                                    echo wp_kses_post($tab1_value);
                                    echo '</div>';
                                } else {
                                    echo '<div class="tab1-content">';
                                    echo '<h2>Tab 1</h2>';
                                    echo '<p style="height: 200px">Chưa có nội dung</p>';
                                    echo '</div>';
                                }

                                if (!empty($tab2_value)) {
                                    echo '<div class="tab2-content">';
                                    echo '<h2>Tab 2</h2>';
                                    echo esc_html($tab2_value);
                                    echo '</div>';
                                } else {
                                    echo '<div class="tab2-content">';
                                    echo '<h2>Tab 2</h2>';
                                    echo '<p style="height: 200px">Chưa có nội dung</p>';
                                    echo '</div>';
                                }

                                if (!empty($tab3_image_ids)) {
                                    echo '<div class="tab3-content">';
                                    echo '<h2>Tab 3</h2>';
                                    if (function_exists('display_custom_images')) {
                                        display_custom_images(get_the_ID());
                                    }
                                    echo '</div>';
                                } else {
                                    echo '<div class="tab3-content">';
                                    echo '<h2>Tab 3</h2>';
                                    echo '<p style="height: 200px">Chưa có nội dung</p>';
                                    echo '</div>';
                                }

                                ?>
                            </div>

                            <!-- Slide 3: content_job_other -->
                            <div class="swiper-slide content_job_other">
                                <?php
                                $additional_info = get_post_meta(get_the_ID(), '_my_additional_info_key', true);
                                if (!empty($additional_info)) {
                                    echo '<div class="job_other_container">';
                                    echo '<h2>Additional Info</h2>';
                                    echo wp_kses_post($additional_info);
                                    echo '</div>';
                                } else {
                                    echo '<div class="job_other_container">';
                                    echo '<h2>Additional Info</h2>';
                                    echo '<p style="height: 500px">Chưa có nội dung</p>';
                                    echo '</div>';
                                }
                                ?>

                            </div>
                        </div>
                        <!-- Add Pagination and Navigation -->
                        <div class="swiper-pagination"></div>
                        <div class="button_Navigation">
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                </div>
            </div><!-- #post-<?php the_ID(); ?> -->
        <?php endwhile; ?>
    </div><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>

    var taxonomyTerms = <?php echo json_encode($terms_data); ?>;
    var previousPage = document.referrer;
    console.log('Previous Page URL:', previousPage); // For debugging
    console.log('Taxonomy Terms:', taxonomyTerms);

    // Function to display taxonomy terms
    function displayTaxonomyTerms() {
        var termsContainer = document.createElement('div');
        termsContainer.className = 'post-category';

        if (Array.isArray(taxonomyTerms)) {
            taxonomyTerms.forEach(function(term) {
                var link = document.createElement('a');
                link.href = term.link;
                link.textContent = term.name;
                termsContainer.appendChild(link);

                // Add a line break or other styling as needed
                termsContainer.appendChild(document.createElement('br'));
            });
        } else {
            termsContainer.textContent = 'No terms found.';
        }

        // Append the termsContainer to a specific element in the page
        document.getElementById('taxonomy-terms-container').appendChild(termsContainer);
    }

    // Call the function to display terms
    displayTaxonomyTerms();


    document.addEventListener('DOMContentLoaded', function () {
        var swiperP = new Swiper('.swiper-large-container', {
            loop: false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            slidesPerView: 1,
            on: {
                reachEnd: function () {
                    this.isAtEnd = true;

                    // Thêm lớp để vô hiệu hóa các lần vuốt tiếp theo
                    this.allowSlideNext = false;

                    // Đợi một chút rồi bật lại tính năng vuốt
                    setTimeout(() => {
                        this.allowSlideNext = true;
                    }, 500); // Thời gian 500ms để tạo hiệu ứng "bật lại"
                },
                touchEnd: function () {
                    if (this.isAtEnd && this.activeIndex === this.slides.length - 1) {
                        // Kiểm tra nếu người dùng đang cố vuốt tiếp sau khi đạt đến slide cuối
                        const translateX = this.translate;
                        const maxTranslateX = this.maxTranslate();
                        if (translateX < maxTranslateX) {
                            // Lấy liên kết danh mục đầu tiên mà bài viết này thuộc về
                            var firstCategoryLink = document.querySelector('.post-category a').href;

                            console.log('firstCategoryLink', firstCategoryLink)

                            // Kiểm tra nếu liên kết danh mục tồn tại
                            if (previousPage) {
                                window.location.href = previousPage;
                            } else {
                                // Nếu không có danh mục nào, quay lại trang trước đó
                                window.history.back();
                            }
                        }
                    }
                },


                slideChange: function () {
                    // Cập nhật URL với tham số của slide hiện tại
                    const slideIndex = this.activeIndex + 1; // +1 vì các chỉ số slide bắt đầu từ 0
                    const url = new URL(window.location.href);
                    url.hash = `#${slideIndex}`;
                    history.replaceState(null, null, url.toString());
                },
                slidePrevTransitionStart: function () {
                    this.isAtEnd = false;
                }
            }
        });

        var swiperC = new Swiper('.swiper-small-container', {
            loop: true,
            pagination: {
                el: '.swiper-pagination-c',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next-c',
                prevEl: '.swiper-button-prev-c',
            },
            slidesPerView: 1,
            spaceBetween: 10,
            breakpoints: {
                1024: {
                    slidesPerView: 1,
                    spaceBetween: 30,
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                480: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                }
            }
        });

        console.log('Swiper initialized with params:', swiperP.params, swiperC.params);

        // Khi trang được tải lại, kiểm tra tham số URL và chuyển đến slide tương ứng
        const hash = window.location.hash;
        if (hash) {
            const slideIndex = parseInt(hash.replace('#', '')) - 1; // Chuyển đổi từ chỉ số bắt đầu từ 1 sang 0
            if (!isNaN(slideIndex) && slideIndex >= 0 && slideIndex < swiperP.slides.length) {
                swiperP.slideTo(slideIndex);
            }
        }
    });




</script>
