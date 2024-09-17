<?php /* Template Name: Trang chủ */ ?>
<?php
$placeholder_image = get_stylesheet_directory_uri() . '/images/mintokumesse_logo.png';

// Lấy URL động của trang 'list-job'
$list_job_url = get_permalink(get_page_by_path('list-job'));
?>
<?php get_header(); ?>
<div class="container" id="page_content">
    <div class="logo_job">
        <img src="<?php echo esc_url($placeholder_image); ?>" alt="">
    </div>
    <div class="wrap-content">
        <div><h3 data-translate="job_title">求人</h3></div>
        <div class="menu">
            <div class="menu_item">
                <a href="<?php echo esc_url($list_job_url); ?>" data-translate="jobs_link">求人</a>
            </div>
            <div class="menu_item">
                <!-- Sử dụng link động cho 動画コンテンツ -->
                <a href="#" data-translate="video_content_link">動画コンテンツ</a>
            </div>
            <div class="menu_item">
                <a href="#" data-translate="academy_link">キャムテックアカデミー</a>
            </div>
            <div class="menu_item">
                <a href="#" data-translate="forum_link">質問掲示板</a>
            </div>
        </div>
    </div>
</div>

<script>
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

    // Perform the search based on selected filters
    jQuery(document).ready(function ($) {
        const language = '<?php echo ICL_LANGUAGE_CODE; ?>'; // PHP variable
        setLanguage(language);
    });
</script>

<?php get_footer(); ?>
