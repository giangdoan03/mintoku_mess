<?php get_header(); ?>

<div class="taxonomy-year">
    <h1><?php single_term_title(); ?></h1>

    <!-- Dropdown filter for province -->
    <div class="filter-container">
        <label for="province-filter">Chọn tỉnh:</label>
        <select id="province-filter" name="province">
            <option value="">Chọn tỉnh</option>
            <?php
            // Lấy danh sách các tỉnh từ taxonomy 'province_vietnam'
            $provinces = get_terms(array(
                'taxonomy' => 'province_vietnam',
                'hide_empty' => false,
                'parent' => 0, // Chỉ lấy các term cha
            ));

            foreach ($provinces as $province) {
                echo '<option value="' . esc_attr($province->term_id) . '">' . esc_html($province->name) . '</option>';
            }
            ?>
        </select>

        <!-- Dropdown filter for universities -->
        <label for="university-filter">Chọn trường đại học:</label>
        <select id="university-filter" name="university">
            <option value="">Chọn trường đại học</option>
            <!-- Options will be loaded via AJAX -->
        </select>
    </div>

    <div id="posts-container">
        <?php if (have_posts()) : ?>
            <ul class="year-posts">
                <?php while (have_posts()) : the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('thumbnail', ['style' => 'width: 100px; height: 80px;']); ?>
                            <?php else : ?>
                                <img src="https://placehold.jp/100x80.png" alt="Placeholder" style="width: 100px; height: 80px;">
                            <?php endif; ?>
                            <?php the_title(); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p><?php _e('No posts found.', 'textdomain'); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
