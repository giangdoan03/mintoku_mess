<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mintokumobile
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'mintokumobile' ); ?></a>

    <header id="header_s">
        <?php
        $current_language = apply_filters('wpml_current_language', NULL);
        $languages = apply_filters('wpml_active_languages', NULL, 'skip_missing=0');

        if (!empty($languages)) {
            echo '<ul class="language-switcher">';
            foreach ($languages as $language) {
                $url = $language['url'];
                $flag_url = $language['country_flag_url']; // WPML flag URL

                if ($language['code'] != $current_language) {
                    echo '<li><a href="' . esc_url($url) . '">';
                    echo '<img src="' . esc_url($flag_url) . '" alt="' . esc_attr($language['native_name']) . ' flag" style="width: 20px; height: auto; margin-right: 5px;">'; // Flag icon
                    echo esc_html($language['native_name']);
                    echo '</a></li>';
                } else {
                    echo '<li class="active">';
                    echo '<img src="' . esc_url($flag_url) . '" alt="' . esc_attr($language['native_name']) . ' flag" style="width: 20px; height: auto; margin-right: 5px;">'; // Flag icon
                    echo esc_html($language['native_name']);
                    echo '</li>';
                }
            }
            echo '</ul>';
        }
        ?>
    </header>

