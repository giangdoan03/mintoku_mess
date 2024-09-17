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
        <div class="menu_language">
            <?php
            $current_language = apply_filters('wpml_current_language', NULL);
            $languages = apply_filters('wpml_active_languages', NULL, 'skip_missing=0');

            if (!empty($languages)) {
                echo '<div class="custom-dropdown">';
                echo '<button class="dropdown-button">';
                foreach ($languages as $language) {
                    if ($language['code'] == $current_language) {
                        $flag_url = esc_url($language['country_flag_url']);
                        echo '<img src="' . $flag_url . '" alt="' . esc_attr($language['native_name']) . ' flag" style="width: 20px; height: auto; margin-right: 5px;">';
                        echo esc_html($language['native_name']);
                    }
                }
                echo '</button>';
                echo '<div class="dropdown-content">';
                foreach ($languages as $language) {
                    $url = esc_url($language['url']);
                    $flag_url = esc_url($language['country_flag_url']); // WPML flag URL

                    echo '<a href="' . $url . '">';
                    echo '<img src="' . $flag_url . '" alt="' . esc_attr($language['native_name']) . ' flag" style="width: 20px; height: auto; margin-right: 5px;">';
                    echo esc_html($language['native_name']);
                    echo '</a>';
                }
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </header>


    <style>
        /* Custom dropdown container */
        .custom-dropdown {
            position: relative;
            display: inline-block;
            font-family: Arial, sans-serif;
        }

        /* Dropdown button */
        .dropdown-button {
            background-color: #0073aa; /* Modern blue color */
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .dropdown-button img {
            border-radius: 50%; /* Circular flag icon */
            margin-right: 8px;
        }

        .dropdown-button:hover {
            background-color: #005f8d; /* Darker blue on hover */
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        /* The dropdown content (hidden by default) */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            border-radius: 5px;
            min-width: 180px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            /*margin-top: 10px;*/
            overflow: hidden;
            transition: opacity 0.3s ease, transform 0.3s ease;
            opacity: 0;
            transform: translateY(10px);
            right: 0px;
        }

        /* Links inside the dropdown */
        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            background-color: transparent;
            transition: background-color 0.3s ease;
        }

        .dropdown-content a img {
            border-radius: 50%;
            margin-right: 10px;
            width: 20px;
            height: 20px;
        }

        /* Show the dropdown menu on hover */
        .custom-dropdown:hover .dropdown-content {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* Links hover effect */
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        /* Smooth dropdown animation */
        .custom-dropdown:hover .dropdown-content {
            opacity: 1;
            transform: translateY(0);
        }
        .menu_language {
            text-align: right;
        }


    </style>
