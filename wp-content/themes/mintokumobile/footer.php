<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mintokumobile
 */

?>

<footer id="footer_s">
    <!-- Nút Back to Top -->
    <a href="#" id="back-to-top" title="Back to Top">&uarr;</a>

    <!-- Nút Back chỉ hiển thị nếu không phải trang chủ -->
    <?php if (!is_front_page()) : ?>
        <a href="javascript:history.back()" id="back-button" title="Back">&larr;</a>
    <?php endif; ?>
</footer><!-- #footer_s -->
</div><!-- #page -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('back-to-top');

        // Hiển thị hoặc ẩn nút khi cuộn
        window.addEventListener('scroll', function() {
            if (window.scrollY > 200) { // Hiển thị nút khi cuộn xuống 200px
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });

        // Xử lý sự kiện khi nút được nhấp
        backToTopButton.addEventListener('click', function(event) {
            event.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' }); // Cuộn mượt mà lên đầu trang
        });
    });

</script>
<?php wp_footer(); ?>

</body>
</html>
