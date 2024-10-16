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
<div class="b_footer">
</div>
<footer id="footer_s">
    <!-- Nút Back to Top -->
    <a href="#" id="back-to-top" title="Back to Top">&uarr;</a>

    <!-- Nút Back chỉ hiển thị nếu không phải trang chủ -->
</footer><!-- #footer_s -->
</div><!-- #page -->

<?php wp_footer(); ?>
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

    // Hàm tải và áp dụng bản dịch
    async function fetchTranslations() {
        try {
            const response = await fetch('<?php echo get_template_directory_uri(); ?>/js/translations.json');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        } catch (error) {
            console.error('Error fetching translations:', error);
        }
    }

    async function setLanguage(language) {
        try {
            const translations = await fetchTranslations();
            document.querySelectorAll('[data-translate]').forEach(element => {
                const key = element.getAttribute('data-translate');
                if (translations[language] && translations[language][key]) {
                    element.textContent = translations[language][key];
                }
            });
        } catch (error) {
            console.error('Error setting language:', error);
        }
    }

</script>
</body>
</html>
