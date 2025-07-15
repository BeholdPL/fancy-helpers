<?php
/*
Plugin Name: Fancy Helpers
Description: A versatile WordPress plugin with various helper functions and customizations.
Version: 1.4.2
Author: BEHOLD Damian Paluszkiewicz
Requires at least: 6.4
Tested up to: 6.5.5
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://example.com/donate
*/

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Include WordPress plugin API
include_once ABSPATH . 'wp-admin/includes/plugin.php';

// Define plugin paths
define('FANCY_HELPERS_PATH', plugin_dir_path(__FILE__));
define('FANCY_HELPERS_URL', plugin_dir_url(__FILE__));

/**
 * Redirect to plugin settings page on activation and default settings
 */
function redirect_after_activation($plugin) {
    if($plugin == plugin_basename(__FILE__)) {
        // Set default options on activation
        $default_options = array(
            'enable_global_contacts' => 1,
            'disable_default_patterns' => 1,
            'use_site_logo_on_login' => 1,
            'delete_data_on_uninstall' => 0 
        );
        $existing_options = get_option('fancy_helpers_options', array());
        $merged_options = array_merge($default_options, $existing_options);
        update_option('fancy_helpers_options', $merged_options);
        
        exit(wp_redirect(admin_url('options-general.php?page=fancy-helpers')));
    }
}
add_action('activated_plugin', 'redirect_after_activation');

/**
 * Uninstall hook
 */
function fancy_helpers_uninstall() {
    $options = get_option('fancy_helpers_options', array());
    if (!empty($options['delete_data_on_uninstall'])) {
        delete_option('fancy_helpers_options');
        // Add any other cleanup tasks here
    }
}
register_uninstall_hook(__FILE__, 'fancy_helpers_uninstall');

// Include required files
require_once FANCY_HELPERS_PATH . 'includes/global-contacts.php';
require_once FANCY_HELPERS_PATH . 'includes/shortcodes-tags.php';
require_once FANCY_HELPERS_PATH . 'includes/template-tags.php';
require_once FANCY_HELPERS_PATH . 'includes/fancy-functions.php';
require_once FANCY_HELPERS_PATH . 'includes/updater.php';

/**
 * Enqueue mix admin styles
 */
function fancy_mix_admin_styles() {
    wp_enqueue_style('fancy-min-gutenberg-styles', FANCY_HELPERS_URL . 'admin/css/fancy-mix-gutenberg-styles.min.css', array(), filemtime(FANCY_HELPERS_PATH . 'admin/css/fancy-mix-gutenberg-styles.min.css'));
}
add_action('enqueue_block_editor_assets', 'fancy_mix_admin_styles');

/**
 * Enqueue mix front styles
 */
// function fancy_mix_front_styles() {
//     wp_enqueue_style(
//         'fancy-mix-front-styles', FANCY_HELPERS_URL . 'public/css/fancy-mix-front-styles.min.css', array(), filemtime(FANCY_HELPERS_PATH . 'public/css/fancy-mix-front-styles.min.css')
//     );
// }
// add_action('wp_enqueue_scripts', 'fancy_mix_front_styles');
function fancy_mix_inline_styles() {
    ?>
    <style>
        .menu li.menu-item,.menu li.page_item{justify-content:center}.menu>[data-submenu=right]>.sub-menu{left:unset!important}.italic{font-style:italic}.bold{font-weight:bold}b,strong{font-weight:900}.oblique{font-style:oblique}.mark-inherit mark{font-style:inherit;font-weight:inherit}.element-sticky-mobile{position:-webkit-sticky;position:sticky;top:calc(var(--admin-bar,0px) + var(--frame-size,0px) + var(--scroll-margin-top-offset,0px))}@media screen and (min-width:768px){.element-sticky-tablet{position:-webkit-sticky;position:sticky;top:calc(var(--admin-bar,0px) + var(--frame-size,0px) + var(--scroll-margin-top-offset,0px))}}@media screen and (min-width:1024px){.element-sticky-desktop{position:-webkit-sticky;position:sticky;top:calc(var(--admin-bar,0px) + var(--frame-size,0px) + var(--scroll-margin-top-offset,0px))}}@media screen and (max-width:781px){.mobile-reverse{flex-wrap:wrap-reverse!important}}@media screen and (min-width:1024px){.cols-2{column-count:2}.cols-3{column-count:2}}@media screen and (min-width:1512px){.cols-3{column-count:3}}.gallery-aspect-ratio-1-1 figure{aspect-ratio:1/1!important}.gallery-aspect-ratio-4-3 figure{aspect-ratio:4/3!important}.gallery-aspect-ratio-3-4 figure{aspect-ratio:3/4!important}.gallery-aspect-ratio-3-2 figure{aspect-ratio:3/2!important}.gallery-aspect-ratio-2-3 figure{aspect-ratio:2/3!important}.gallery-aspect-ratio-16-9 figure{aspect-ratio:16/9!important}.gallery-aspect-9-16 figure{aspect-ratio:9/16!important}
    </style>
    <?php
}
add_action('wp_body_open', 'fancy_mix_inline_styles');


/**
 * Enqueue Gutenberg metabox styles
 */
function fancy_gutenberg_metabox() {
    wp_enqueue_style('fancy-gutenberg-metabox', FANCY_HELPERS_URL . 'admin/css/fancy-gutenberg-metabox.min.css', array(), filemtime(FANCY_HELPERS_PATH . 'admin/css/fancy-gutenberg-metabox.min.css'));
}
add_action('enqueue_block_editor_assets', 'fancy_gutenberg_metabox');

/**
 * Enqueue lightbox scripts and functions
 */
function fancy_helpers_lightbox_scripts() {
    wp_enqueue_script('fancy-galleries', FANCY_HELPERS_URL . 'public/js/fancy-galleries.min.js', array(), filemtime(FANCY_HELPERS_PATH . 'public/js/fancy-galleries.min.js'), true);
    wp_enqueue_script('fancy-fslightbox', FANCY_HELPERS_URL . 'public/js/fancy-fslightbox.min.js', array(), filemtime(FANCY_HELPERS_PATH . 'public/js/fancy-fslightbox.min.js'), true);
}

// Add hover effect to gallery images
function fancy_helpers_gallery_hover_effects_front() {
    // Define the CSS for the hover effect
    $css = '.wp-block-gallery.addLightbox .wp-block-image{overflow:hidden}.wp-block-gallery.addLightbox .wp-block-image a{position:relative}.wp-block-gallery.addLightbox .wp-block-image a:after{display:flex;justify-content:center;align-items:center;color:#fff;display:inline-block;content:"";position:absolute;top:0;left:0;width:100%;height:100%;background-image:url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDQxNiA0MTYiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM6c2VyaWY9Imh0dHA6Ly93d3cuc2VyaWYuY29tLyIgc3R5bGU9ImZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoyOyI+CiAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgxLDAsMCwxLC00OCwtNDgpIj4KICAgICAgICA8cGF0aCBpZD0iTWF4aW1pemUiIGQ9Ik00NjQsNjRMNDY0LDE2MEM0NjQsMTY4LjgzNiA0NTYuODM2LDE3NiA0NDgsMTc2QzQzOS4xNjQsMTc2IDQzMiwxNjguODM2IDQzMiwxNjBMNDMyLDEwMi42MjRMMzMxLjMxMiwyMDMuMzEyQzMyOC4xODgsMjA2LjQzNiAzMjQuMDkyLDIwOCAzMjAsMjA4QzMxNS45MDgsMjA4IDMxMS44MTIsMjA2LjQzNiAzMDguNjg4LDIwMy4zMTJDMzAyLjQzNiwxOTcuMDYgMzAyLjQzNiwxODYuOTM2IDMwOC42ODgsMTgwLjY4OEw0MDkuMzc2LDgwTDM1Miw4MEMzNDMuMTY0LDgwIDMzNiw3Mi44MzYgMzM2LDY0QzMzNiw1NS4xNjQgMzQzLjE2NCw0OCAzNTIsNDhMNDQ4LDQ4QzQ1Ni44MzYsNDggNDY0LDU1LjE2NCA0NjQsNjRaTTIwMy4zMTIsMTgwLjY4OEwxMDIuNjI0LDgwTDE2MCw4MEMxNjguODM2LDgwIDE3Niw3Mi44MzYgMTc2LDY0QzE3Niw1NS4xNjQgMTY4LjgzNiw0OCAxNjAsNDhMNjQsNDhDNTUuMTY0LDQ4IDQ4LDU1LjE2NCA0OCw2NEw0OCwxNjBDNDgsMTY4LjgzNiA1NS4xNjQsMTc2IDY0LDE3NkM3Mi44MzYsMTc2IDgwLDE2OC44MzYgODAsMTYwTDgwLDEwMi42MjRMMTgwLjY4OCwyMDMuMzEyQzE4My44MTIsMjA2LjQzNiAxODcuOTA4LDIwOCAxOTIsMjA4QzE5Ni4wOTIsMjA4IDIwMC4xODgsMjA2LjQzNiAyMDMuMzEyLDIwMy4zMTJDMjA5LjU2NCwxOTcuMDY0IDIwOS41NjQsMTg2LjkzNiAyMDMuMzEyLDE4MC42ODhaTTQ0OCwzMzZDNDM5LjE2NCwzMzYgNDMyLDM0My4xNjQgNDMyLDM1Mkw0MzIsNDA5LjM3NkwzMzEuMzEyLDMwOC42ODhDMzI1LjA2LDMwMi40MzYgMzE0LjkzNiwzMDIuNDM2IDMwOC42ODgsMzA4LjY4OEMzMDIuNDQsMzE0Ljk0IDMwMi40MzYsMzI1LjA2NCAzMDguNjg4LDMzMS4zMTJMNDA5LjM3Niw0MzJMMzUyLDQzMkMzNDMuMTY0LDQzMiAzMzYsNDM5LjE2NCAzMzYsNDQ4QzMzNiw0NTYuODM2IDM0My4xNjQsNDY0IDM1Miw0NjRMNDQ4LDQ2NEM0NTYuODM2LDQ2NCA0NjQsNDU2LjgzNiA0NjQsNDQ4TDQ2NCwzNTJDNDY0LDM0My4xNjQgNDU2LjgzNiwzMzYgNDQ4LDMzNlpNMjAzLjMxMiwzMDguNjg4QzE5Ny4wNiwzMDIuNDM2IDE4Ni45MzYsMzAyLjQzNiAxODAuNjg4LDMwOC42ODhMODAsNDA5LjM3Nkw4MCwzNTJDODAsMzQzLjE2NCA3Mi44MzYsMzM2IDY0LDMzNkM1NS4xNjQsMzM2IDQ4LDM0My4xNjQgNDgsMzUyTDQ4LDQ0OEM0OCw0NTYuODM2IDU1LjE2NCw0NjQgNjQsNDY0TDE2MCw0NjRDMTY4LjgzNiw0NjQgMTc2LDQ1Ni44MzYgMTc2LDQ0OEMxNzYsNDM5LjE2NCAxNjguODM2LDQzMiAxNjAsNDMyTDEwMi42MjQsNDMyTDIwMy4zMTIsMzMxLjMxMkMyMDkuNTY0LDMyNS4wNjQgMjA5LjU2NCwzMTQuOTM2IDIwMy4zMTIsMzA4LjY4OFoiIHN0eWxlPSJmaWxsOndoaXRlO2ZpbGwtcnVsZTpub256ZXJvOyIvPgogICAgPC9nPgo8L3N2Zz4K);background-size:32px;background-repeat:no-repeat;background-position:center;background-color:rgba(0,0,0,.32);opacity:0;transition:opacity 0.3s ease}.wp-block-gallery.addLightbox .wp-block-image img{transition:transform 0.3s ease}.wp-block-gallery.addLightbox .wp-block-image a:hover img{transform:scale(1.04)}.wp-block-gallery.addLightbox .wp-block-image a:hover:after{opacity:1}';

    if (!empty($css)) {
        echo '<style>' . $css . '</style>';
    }
}

function fancy_herlpers_lightbox_class( $block_content, $block ) {
    // Check if this is a Gallery block
    if ( 'core/gallery' === $block['blockName'] ) {
        // Check if the "Link to Media File" option is enabled
        $has_link_to_media_file = isset( $block['attrs']['linkTo'] ) && 'media' === $block['attrs']['linkTo'];

        if ( $has_link_to_media_file ) {
            // Process the block's HTML
            $block_content = new WP_HTML_Tag_Processor( $block_content );
            
            // Find the first tag (should be 'div')
            $block_content->next_tag();
            
            // Add the 'addLightbox' class if it does not already exist
            if ( ! $block_content->has_class( 'addLightbox' ) ) {
                $block_content->add_class( 'addLightbox' );
            }
            
            // Updated HTML
            $block_content = $block_content->get_updated_html();
        }
    }

    return $block_content;
}

// Add CSS to the front-end footer
function fancy_helpers_gallery_aspect_ratio_front() {
    $options = get_option('fancy_helpers_options', array());
    $aspect_ratio = isset($options['gallery_aspect_ratio']) ? $options['gallery_aspect_ratio'] : 'default';
    if ($aspect_ratio !== 'default') {
        $css = '';
        switch ($aspect_ratio) {
            case '1:1':
                $css = '.wp-block-gallery .wp-block-image, .wp-block-gallery .wp-block-image a { aspect-ratio: 1 / 1; }';
                break;
            case '2:3':
                $css = '.wp-block-gallery .wp-block-image, .wp-block-gallery .wp-block-image a { aspect-ratio: 2 / 3; }';
                break;
            case '4:3':
                $css = '.wp-block-gallery .wp-block-image, .wp-block-gallery .wp-block-image a { aspect-ratio: 4 / 3; }';
                break;
            case '5:4':
                $css = '.wp-block-gallery .wp-block-image, .wp-block-gallery .wp-block-image a { aspect-ratio: 5 / 4; }';
                break;
            case '16:9':
                $css = '.wp-block-gallery .wp-block-image, .wp-block-gallery .wp-block-image a { aspect-ratio: 16 / 9; }';
                break;
            case '3:2':
                $css = '.wp-block-gallery .wp-block-image, .wp-block-gallery .wp-block-image a { aspect-ratio: 3 / 2; }';
                break;
            case '3:4':
                $css = '.wp-block-gallery .wp-block-image, .wp-block-gallery .wp-block-image a { aspect-ratio: 3 / 4; }';
                break;
            case '4:5':
                $css = '.wp-block-gallery .wp-block-image, .wp-block-gallery .wp-block-image a { aspect-ratio: 4 / 5; }';
                break;
            case '9:16':
                $css = '.wp-block-gallery .wp-block-image, .wp-block-gallery .wp-block-image a { aspect-ratio: 9 / 16; }';
                break;
        }
        if (!empty($css)) {
            echo '<style>' . $css . '</style>';
        }
    }
}

// Add CSS to the Gutenberg block editor
function fancy_helpers_gallery_aspect_ratio_front_gutenberg() {
    $options = get_option('fancy_helpers_options', array());
    $aspect_ratio = isset($options['gallery_aspect_ratio']) ? $options['gallery_aspect_ratio'] : 'default';
    if ($aspect_ratio !== 'default') {
        $css = '';
        switch ($aspect_ratio) {
            case '1:1':
                $css = '.wp-block-gallery.has-nested-images.is-cropped figure.wp-block-image:not(#individual-image) img { aspect-ratio: 1 / 1; }';
                break;
            case '4:3':
                $css = '.wp-block-gallery.has-nested-images.is-cropped figure.wp-block-image:not(#individual-image) img { aspect-ratio: 4 / 3; }';
                break;
            case '16:9':
                $css = '.wp-block-gallery.has-nested-images.is-cropped figure.wp-block-image:not(#individual-image) img { aspect-ratio: 16 / 9; }';
                break;
            case '7:5':
                $css = '.wp-block-gallery.has-nested-images.is-cropped figure.wp-block-image:not(#individual-image) img { aspect-ratio: 7 / 5; }';
                break;
            case '3:4':
                $css = '.wp-block-gallery.has-nested-images.is-cropped figure.wp-block-image:not(#individual-image) img { aspect-ratio: 3 / 4; }';
                break;
            case '9:16':
                $css = '.wp-block-gallery.has-nested-images.is-cropped figure.wp-block-image:not(#individual-image) img { aspect-ratio: 9 / 16; }';
                break;
            case '5:7':
                $css = '.wp-block-gallery.has-nested-images.is-cropped figure.wp-block-image:not(#individual-image) img { aspect-ratio: 5 / 7; }';
                break;
        }
        if (!empty($css)) {
            wp_add_inline_style('wp-edit-blocks', $css);
        }
    }
}

/**
 * Enqueue Rank Math icon styles
 */
function fancy_helpers_unify_rank_math_icon_scripts() {
    wp_enqueue_style('fancy-rank-math-icon', FANCY_HELPERS_URL . 'admin/css/fancy-rank-math-icon.min.css', array(), filemtime(FANCY_HELPERS_PATH . 'admin/css/fancy-rank-math-icon.min.css'));
}

/**
 * Enqueue ACF Extended form additional styles
 * 
 * This function checks if ACF Extended plugin is active before enqueuing the styles.
 * It uses the `class_exists()` function to check for the ACFE class, which is specific to ACF Extended.
 */
function fancy_helpers_acf_forms_styles() {
    // Check if ACF Extended is active
    if (class_exists('ACFE')) {
        // Enqueue the stylesheet only if ACF Extended is active
        wp_enqueue_style('fancy-acf-forms-styles', FANCY_HELPERS_URL . 'public/css/fancy-acf-forms.min.css', array(), filemtime(FANCY_HELPERS_PATH . 'public/css/fancy-acf-forms.min.css')
        );
    }
}

// Hook the function to WordPress' style enqueuing action
add_action('wp_enqueue_scripts', 'fancy_helpers_acf_forms_styles');


/**
 * Use site logo on login page
 */
function fancy_helpers_site_logo_on_login() {
    if (has_site_icon()) {
        $site_icon_url = get_site_icon_url();
        ?>
        <style type="text/css">
            .login h1 a {
                background-image: url(<?php echo esc_url($site_icon_url); ?>) !important;
                background-size: contain !important;
                width: 84px !important;
                height: 84px !important;
            }
        </style>
        <?php
    }
}

/**
 * Hide ACF in Dashboard
 */
// Function to manipulate the menu
function hide_acf_menu() {
    // Find the page name of ACF options in the menu
    $acf_page = 'edit.php?post_type=acf-field-group';

    // Remove the ACF options page from the menu
    remove_menu_page($acf_page);
}

/**
 * Disable default Gutenberg patterns
 */
function fancy_helpers_disable_default_patterns() {
    remove_theme_support('core-block-patterns');
    unregister_block_pattern_category('buttons');
    unregister_block_pattern_category('columns');
    unregister_block_pattern_category('gallery');
    unregister_block_pattern_category('header');
    unregister_block_pattern_category('text');
    unregister_block_pattern_category('posts');
    unregister_block_pattern_category('uncategorized');
    unregister_block_pattern('core/query-standard-posts');
    unregister_block_pattern('core/query-medium-posts');
    unregister_block_pattern('core/query-small-posts');
    unregister_block_pattern('core/query-grid-posts');
    unregister_block_pattern('core/query-large-title-posts');
    unregister_block_pattern('core/query-offset-posts');
    
    if (get_template() === 'blocksy') {
        unregister_block_pattern('blocksy/posts-layout-1');
        unregister_block_pattern('blocksy/posts-layout-2');
        unregister_block_pattern('blocksy/posts-layout-3');
        unregister_block_pattern('blocksy/posts-layout-4');
        unregister_block_pattern('blocksy/posts-layout-5');
        unregister_block_pattern('blocksy/tax-layout-1');
        unregister_block_pattern('blocksy/tax-layout-2');
        unregister_block_pattern('blocksy/tax-layout-3');
        unregister_block_pattern('blocksy/tax-layout-4');
        unregister_block_pattern('blocksy/tax-layout-5');
    }
}
add_action('init', 'fancy_helpers_disable_default_patterns');

/**
 * Initialize all plugin functionalities
 */
function fancy_helpers_init() {
    $options = get_option('fancy_helpers_options', array());
    $rank_math_active = is_plugin_active('seo-by-rank-math/rank-math.php');

    if (!empty($options['gallery_aspect_ratio'])) {
        add_action('wp_footer', 'fancy_helpers_gallery_aspect_ratio_front');
        add_action('enqueue_block_editor_assets', 'fancy_helpers_gallery_aspect_ratio_front_gutenberg');
    }

    if (!empty($options['enable_lightbox'])) {
        add_action('wp_enqueue_scripts', 'fancy_helpers_lightbox_scripts');
        add_action('wp_footer', 'fancy_helpers_gallery_hover_effects_front');
        add_filter( 'render_block', 'fancy_herlpers_lightbox_class', 10, 2 );
    }

    if (!empty($options['enable_custom_h1'])) {
        require_once FANCY_HELPERS_PATH . 'includes/custom-h1.php';
    }

    if (!empty($options['unify_rank_math_icon']) && $rank_math_active ) {
        add_action('admin_enqueue_scripts', 'fancy_helpers_unify_rank_math_icon_scripts');
        add_action('enqueue_block_editor_assets', 'fancy_helpers_unify_rank_math_icon_scripts');
    }

    if (!empty($options['use_patterns_in_menu'])) {
        require_once FANCY_HELPERS_PATH . 'includes/enable-patterns.php';
        fancy_helpers_patterns_in_dashboard();
    }

    if (!empty($options['disable_default_patterns'])) {
        add_action('init', 'fancy_helpers_disable_default_patterns', 9999);

    }

    if (!empty($options['use_site_logo_on_login'])) {
        add_action('login_enqueue_scripts', 'fancy_helpers_site_logo_on_login');
    }

    if (!empty($options['remove_orphans'])) {
        require_once FANCY_HELPERS_PATH . 'includes/orphans.php';
    }

    if (empty($options['show_acf_fields'])) {
        // Hook to run the function when the admin menu is loaded
        add_action('admin_menu', 'hide_acf_menu');
    }
}
add_action('init', 'fancy_helpers_init');

/**
 * Add plugin menu in admin panel
 */
function fancy_helpers_menu() {
    add_options_page(
        'Fancy Helpers Options',
        'Fancy Helpers',
        'manage_options',
        'fancy-helpers',
        'fancy_helpers_options_page'
    );
}
add_action('admin_menu', 'fancy_helpers_menu');

/**
 * Render options page
 */
function fancy_helpers_options_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $options = get_option('fancy_helpers_options', array());
    $rank_math_active = is_plugin_active('seo-by-rank-math/rank-math.php');
    $acf_active = is_plugin_active('advanced-custom-fields/acf.php');
    $acf_pro_active = is_plugin_active('advanced-custom-fields-pro/acf.php');

    $fancy_helpers_options = array(
        'enable_global_contacts' => array(
            'label' => __('Enable Global Contacts', 'fancy-helpers'),
            'description' => __('Enables global contacts functionality.', 'fancy-helpers'),
        ),
        'gallery_aspect_ratio' => array(
            'label' => __('Gallery default aspect ratio', 'fancy-helpers'),
            'description' => __('Select the default aspect ratio for gallery images.', 'fancy-helpers'),
            'type' => 'select',
            'options' => array(
                'default' => __('Default', 'fancy-helpers'),
                '1:1' => '1:1',
                '3:2' => '3:2',
                '4:3' => '4:3',
                '5:4' => '5:4',
                '16:9' => '16:9',
                '2:3' => '2:3',
                '3:4' => '3:4',
                '4:5' => '4:5',
                '9:16' => '9:16',
            ),
            'default' => 'default',
        ),
        'enable_lightbox' => array(
            'label' => __('Enable Lightbox', 'fancy-helpers'),
            'description' => __('Enables lightbox, made with fslightbox.js lite.', 'fancy-helpers'),
        ),
        'enable_custom_h1' => array(
            'label' => __('Enable custom H1', 'fancy-helpers'),
            'description' => __('Enables additional field for custom H1 tag.', 'fancy-helpers'),
        ),
        'unify_rank_math_icon' => array(
            'label' => __('Unify Rank Math Icon', 'fancy-helpers'),
            'description' => __('Disables scores in the Rank Math icon and makes the icon more uniform.', 'fancy-helpers'),
            'requires' => 'rank_math'
        ),
        'use_patterns_in_menu' => array(
            'label' => __('Show patterns in menu', 'fancy-helpers'),
            'description' => __('Enables patterns menu in dashboard.', 'fancy-helpers'),
        ),
        'disable_default_patterns' => array(
            'label' => __('Disable default patterns', 'fancy-helpers'),
            'description' => __('Disables defaults patterns in Patterns Library.', 'fancy-helpers'),
        ),
        'use_site_logo_on_login' => array(
            'label' => __('Use site logo on login page', 'fancy-helpers'),
            'description' => __('Display your site logo on the WordPress login page.', 'fancy-helpers'),
        ),
        'remove_orphans' => array(
            'label' => __('Remove orphans (beta)', 'fancy-helpers'),
            'description' => __('Automatically removes lone words at the end of text lines (works for both Polish and English languages simultaneously).', 'fancy-helpers'),
        ),
        'show_acf_fields' => array(
            'label' => __('Show ACF fields', 'fancy-helpers'),
            'description' => __('Show ACF menu in Dashboard.', 'fancy-helpers'),
            'requires' => array('acf', 'acf-pro')
        ),
        'delete_data_on_uninstall' => array(
            'label' => __('Delete plugin data on uninstall', 'fancy-helpers'),
            'description' => __('This will remove all plugin settings when uninstalling the plugin.', 'fancy-helpers'),
            'class' => 'delete-data-option'
        )
    );

    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div class="fancy-helpers-settings">
            <p><?php echo esc_html__('Configure the settings for Fancy Helpers plugin. Check the options you want to enable.', 'fancy-helpers'); ?></p>
            <form action="options.php" method="post">
                <?php settings_fields('fancy_helpers_options'); ?>
                <?php 
                foreach ($fancy_helpers_options as $option_name => $option_data): 
                    $is_disabled = false;
                    if (isset($option_data['requires'])) {
                        if ($option_data['requires'] == 'rank_math' && !$rank_math_active) {
                            $is_disabled = true;
                        } elseif ($option_data['requires'] == array('acf', 'acf-pro') && !$acf_active && !$acf_pro_active) {
                            $is_disabled = true;
                        }
                    }
                    $depends_on = isset($option_data['depends_on']) ? $option_data['depends_on'] : null;
                ?>
                    <div class="fancy-helpers-option <?php echo $is_disabled ? 'disabled' : ''; ?> <?php echo isset($option_data['class']) ? esc_attr($option_data['class']) : ''; ?> <?php echo esc_attr($option_class); ?>" <?php echo $depends_on ? 'data-depends-on="' . esc_attr($depends_on) . '"' : ''; ?>>
                        <label for="fancy_helpers_<?php echo esc_attr($option_name); ?>">
                            <?php if (($option_data['type'] ?? 'checkbox') === 'checkbox'): ?>
                                <input type="checkbox" id="fancy_helpers_<?php echo esc_attr($option_name); ?>" 
                                       name="fancy_helpers_options[<?php echo esc_attr($option_name); ?>]" 
                                       <?php checked(isset($options[$option_name]) ? $options[$option_name] : 0); ?> 
                                       value="1"
                                       <?php disabled($is_disabled); ?>>
                            <?php elseif ($option_data['type'] === 'select'): ?>
                                <select id="fancy_helpers_<?php echo esc_attr($option_name); ?>" 
                                        name="fancy_helpers_options[<?php echo esc_attr($option_name); ?>]"
                                        <?php disabled($is_disabled); ?>>
                                    <?php foreach ($option_data['options'] as $value => $label): ?>
                                        <option value="<?php echo esc_attr($value); ?>" <?php selected($options[$option_name] ?? $option_data['default'], $value); ?>>
                                            <?php echo esc_html($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                            <?php echo esc_html($option_data['label']); ?>
                        </label>
                        <?php if ($is_disabled): ?>
                            <span class="requires-plugin">
                                <?php 
                                if ($option_data['requires'] == 'rank_math') {
                                    echo esc_html__('(Requires Rank Math plugin)', 'fancy-helpers');
                                } elseif ($option_data['requires'] == array('acf', 'acf-pro')) {
                                    echo esc_html__('(Requires Advanced Custom Fields plugin)', 'fancy-helpers');
                                }
                                ?>
                            </span>
                        <?php endif; ?>
                        <p class="description"><?php echo esc_html($option_data['description']); ?></p>
                    </div>
                <?php endforeach; ?>
                <?php submit_button(esc_html__('Save Settings', 'fancy-helpers')); ?>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Initialize plugin settings
 */
function fancy_helpers_settings_init() {
    register_setting('fancy_helpers_options', 'fancy_helpers_options', 'fancy_helpers_sanitize_options');

    add_settings_section(
        'fancy_helpers_general_section',
        'General Settings',
        'fancy_helpers_general_section_callback',
        'fancy-helpers'
    );

    $options = array(
        'enable_global_contacts' => __('Enable Global Contacts', 'fancy-helpers'),
        'gallery_hover_effect' => __('Add hover effect', 'fancy-helpers'),
        'gallery_aspect_ratio' => __('Gallery default aspect ratio', 'fancy-helpers'),
        'enable_lightbox' => __('Enable Lightbox', 'fancy-helpers'),
        'enable_custom_h1' => __('Enable custom H1', 'fancy-helpers'),
        'unify_rank_math_icon' => __('Unify Rank Math Icon', 'fancy-helpers'),
        'use_patterns_in_menu' => __('Show patterns in menu', 'fancy-helpers'),
        'disable_default_patterns' => __('Disable default patterns', 'fancy-helpers'),
        'use_site_logo_on_login' => __('Use site logo on login page', 'fancy-helpers'),
        'remove_orphans' => __('Remove orphans (beta)', 'fancy-helpers'),
        'show_acf_fields' => __('Show ACF fields', 'fancy-helpers'),
        'delete_data_on_uninstall' => __('Delete plugin data on uninstall', 'fancy-helpers'),
    );

    foreach ($options as $option_name => $option_label) {
        add_settings_field(
            'fancy_helpers_' . $option_name,
            $option_label,
            'fancy_helpers_field_callback',
            'fancy-helpers',
            'fancy_helpers_general_section',
            array(
                'option_name' => $option_name,
                'label_for' => 'fancy_helpers_' . $option_name,
            )
        );
    }
}
add_action('admin_init', 'fancy_helpers_settings_init');

/**
 * Enqueue admin styles
 */
function fancy_helpers_enqueue_admin_styles($hook) {
    if ('settings_page_fancy-helpers' !== $hook) {
        return;
    }
    wp_enqueue_style('fancy-helpers-admin', FANCY_HELPERS_URL . 'admin/css/fancy-helpers-admin.min.css', array(), filemtime(FANCY_HELPERS_PATH . 'admin/css/fancy-helpers-admin.min.css'));
    
    // Add inline styles for the delete data option and gallery options
    $custom_css = "
        .fancy-helpers-option.delete-data-option {
            background-color: #ffeeee;
            border: 1px solid #ffcccc;
            padding: 10px;
            margin-top: 20px;
        }
        .fancy-helpers-option.delete-data-option label {
            color: #cc0000;
            font-weight: bold;
        }
    ";
    wp_add_inline_style('fancy-helpers-admin', $custom_css);
}
add_action('admin_enqueue_scripts', 'fancy_helpers_enqueue_admin_styles');

/**
 * Callback for fields
 */
function fancy_helpers_field_callback($args) {
    $options = get_option('fancy_helpers_options');
    $option_name = $args['option_name'];
    
    $is_disabled = false;
    if ($option_name === 'unify_rank_math_icon') {
        $is_disabled = !is_plugin_active('seo-by-rank-math/rank-math.php');
    } elseif ($option_name === 'show_acf_fields') {
        $is_disabled = !is_plugin_active('advanced-custom-fields/acf.php') && !is_plugin_active('advanced-custom-fields-pro/acf.php');
    }

    if ($option_name === 'gallery_aspect_ratio') {
        $aspect_ratios = array(
            'default' => __('Default', 'fancy-helpers'),
            '1:1' => '1:1',
            '4:3' => '4:3',
            '16:9' => '16:9',
            '7:5' => '7:5',
            '3:4' => '3:4',
            '9:16' => '9:16',
            '5:7' => '5:7',
        );
        echo '<select id="' . esc_attr($args['label_for']) . '" name="fancy_helpers_options[' . esc_attr($option_name) . ']"' . disabled($is_disabled, true, false) . '>';
        foreach ($aspect_ratios as $value => $label) {
            echo '<option value="' . esc_attr($value) . '" ' . selected($options[$option_name] ?? 'default', $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    } else {
        $checked = isset($options[$option_name]) ? $options[$option_name] : 0;
        echo '<input type="checkbox" id="' . esc_attr($args['label_for']) . '" name="fancy_helpers_options[' . esc_attr($option_name) . ']" ' . checked($checked, 1, false) . ' value="1"' . disabled($is_disabled, true, false) . '>';
    }
}

/**
 * Sanitize plugin options
 */
function fancy_helpers_sanitize_options($input) {
    $sanitized_input = array();
    $valid_options = array(
        'enable_global_contacts',
        'gallery_aspect_ratio',
        'enable_lightbox',
        'enable_custom_h1',
        'unify_rank_math_icon',
        'use_patterns_in_menu',
        'disable_default_patterns',
        'use_site_logo_on_login',
        'remove_orphans',
        'show_acf_fields',
        'delete_data_on_uninstall'
    );

    foreach ($valid_options as $option) {
        if ($option === 'gallery_aspect_ratio') {
            $valid_aspect_ratios = array('default', '1:1', '2:3', '4:3', '4:5', '16:9', '3:2', '3:4', '5:4', '9:16');
            $sanitized_input[$option] = in_array($input[$option], $valid_aspect_ratios) ? $input[$option] : 'default';
        } else {
            $sanitized_input[$option] = isset($input[$option]) ? 1 : 0;
        }
    }
    return $sanitized_input;
}

/**
 * Localisations
 */
function fancy_helpers_load_textdomain() {
    load_plugin_textdomain('fancy-helpers', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'fancy_helpers_load_textdomain');