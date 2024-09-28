<?php get_header(); ?>
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

?>

<div id="primary" class="content-area">
    <?php
    // Get the current language
    $current_language = apply_filters('wpml_current_language', NULL);

    // Determine the correct custom field names based on the language
    $content_field = 'cong_viec'; // English field
    $sub_field = 'chi_tiet'; // Sub-field for English content

    // Check if the field has content
    if (have_rows($content_field)): ?>

        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                // Loop through the rows of the selected field
                while (have_rows($content_field)) : the_row();

                    // Get the sub-field content
                    $wysiwyg_content = get_sub_field($sub_field);
                    ?>
                    <div class="swiper-slide">
                        <?php echo $wysiwyg_content; // Display the WYSIWYG content ?>
                    </div>
                <?php endwhile; ?>
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
        <div class="message_link">
            <?php
            $messenger_link = get_field('messenger_link_facebook', 'option');
            if ($messenger_link) {
                echo '<a target="_blank" href="' . esc_url($messenger_link) . '" target="_blank"><i class="fab fa-facebook-messenger"></i>Ứng tuyển qua Messenger</a>';
            }
            ?>
        </div>

    <?php else: ?>
        <p>No content available</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>

<!--<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>-->
<script>

    var taxonomyTerms = <?php echo json_encode($terms_data); ?>;
    var previousPage = document.referrer;

    document.addEventListener('DOMContentLoaded', function () {
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

        // Đảm bảo rằng Swiper cập nhật chiều cao sau khi tải xong nội dung
        // swiperP.on('slideChange', function () {
        //     swiperP.updateAutoHeight();
        // });

        // Check URL hash and navigate to the appropriate slide
        const hash = window.location.hash;
        if (hash) {
            const slideIndex = parseInt(hash.replace('#', '')) - 1;
            if (!isNaN(slideIndex) && slideIndex >= 0 && slideIndex < swiperP.slides.length) {
                swiperP.slideTo(slideIndex);
            }
        }
    });
</script>
