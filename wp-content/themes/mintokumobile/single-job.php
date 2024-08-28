<?php get_header(); ?>
<style>
    .swiper-container {
        width: 100%;
        height: 100%;
    }

    .swiper-wrapper {
        display: flex;
        transition: transform 0.3s ease;
    }

    .swiper-slide {
        flex: 0 0 auto;
        width: 100%; /* Adjust as needed */
    }

    @media (max-width: 767px) {
        .swiper-container {
            height: 300px; /* Adjust height for mobile if needed */
        }
    }

</style>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php
        while ( have_posts() ) :
            the_post();

            // Lấy tên post type hiện tại
            $post_type = get_post_type();

            // Hiển thị tiêu đề
            the_title( '<h1 class="entry-title">', '</h1>' );

            // Hiển thị nội dung
            the_content();

            // Hiển thị mức lương trên frontend
            $salary = get_post_meta(get_the_ID(), get_post_type() . '_salary', true);
            if ($salary) {
                echo '<p><strong>' . __('Salary:', 'textdomain') . '</strong> ' . esc_html($salary) . '</p>';
            }

            // Hiển thị taxonomy liên quan đến post type
            if ($post_type == 'vietnam') {
                $taxonomy = 'province_vietnam';
            } elseif ($post_type == 'laos') {
                $taxonomy = 'province_laos';
            } elseif ($post_type == 'cambodia') {
                $taxonomy = 'province_cambodia';
            }

            // Hiển thị các tỉnh
            if (!empty($taxonomy)) {
                $terms = get_the_terms(get_the_ID(), $taxonomy);
                if ($terms && !is_wp_error($terms)) {
                    echo '<h2>' . __('Provinces') . '</h2>';
                    echo '<ul>';
                    foreach ($terms as $term) {
                        echo '<li>' . esc_html($term->name) . '</li>';
                    }
                    echo '</ul>';
                }
            }

            // Hiển thị các ảnh đã tải lên
            $images = get_post_meta(get_the_ID(), $post_type . '_images', true);
            if (!empty($images)) {
                echo '<h2>' . __('Images') . '</h2>';
                echo '<div class="swiper-container">';
                echo '<div class="swiper-wrapper">';
                foreach ($images as $image) {
                    echo '<div class="swiper-slide slider-item">';
                    echo '<img src="' . esc_url($image) . '" style="width: 100%; height: auto;" />';
                    echo '</div>';
                }
                echo '</div>';
                echo '<div class="swiper-pagination"></div>';
                echo '<div class="swiper-button-next"></div>';
                echo '<div class="swiper-button-prev"></div>';
                echo '</div>';
            }

        endwhile; // End of the loop.
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var swiper = new Swiper('.swiper-container', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            slidesPerView: 4,
            breakpoints: {
                1024: {
                    slidesPerView: 3, // Show 3 slides on desktop
                    spaceBetween: 30, // Space between slides
                },
                768: {
                    slidesPerView: 1, // Show 2 slides on tablets
                    spaceBetween: 20,
                },
                0: {
                    slidesPerView: 1, // Show 1 slide on mobile
                    spaceBetween: 10,
                }
            }
        });

        console.log('Swiper initialized with params:', swiper.params);
    });



</script>
