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
    <div class="f-top">
        <div class="footer_left">
            <p><a href="<?php echo get_permalink(get_page_by_path('term-of-use')); ?>">Thỏa thuận sử dụng</a></p>
            <p><a href="https://vietnam-camcom.com/vi/privacy/">Quy định, Bảo mật</a></p>
            <p><a href="https://vietnam-camcom.com/vi/about/">Công ty</a></p>
        </div>
        <div class="footer_right">
            <p>© VIETNAM CAMCOM Co., Ltd</p>
        </div>
    </div>
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
        const btn_action_fixed = document.getElementById('btn_action_fixed');

        // Hiển thị hoặc ẩn nút khi cuộn
        window.addEventListener('scroll', function() {
            if (window.scrollY > 200) { // Hiển thị nút khi cuộn xuống 200px
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });

        window.addEventListener('scroll', function() {
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            var windowHeight = window.innerHeight;
            var documentHeight = document.documentElement.scrollHeight;
            var distanceToBottom = documentHeight - (scrollTop + windowHeight);

            var btnActionFixed = document.querySelector('.btn_action_fixed');

            if (btnActionFixed) { // Check if the element exists
                // Kiểm tra nếu cách đáy trang 60px
                if (distanceToBottom <= 40) {
                    btnActionFixed.classList.add('fixed-bottom-class'); // Thêm class khi gần đáy
                } else {
                    btnActionFixed.classList.remove('fixed-bottom-class'); // Xóa class khi không còn gần đáy
                }
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
