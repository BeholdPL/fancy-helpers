<?php
/**
 * WordPress Security and other enhancements
 *
 * This file contains functions to enhance WordPress security by removing version information,
 * disabling theme editing, adding a custom admin bar button to edit the homepage and some other functions.
 */

/**
 * SECURITY ENHANCEMENTS
 */

// Remove WordPress version from various locations
remove_action('wp_head', 'wp_generator');

/**
 * Remove generator version
 */
function bhld_remove_generator_version($generator, $type) {
    return '';
}
add_filter('the_generator', 'bhld_remove_generator_version', 10, 2);

/**
 * Remove version query string from scripts and styles
 */
function bhld_remove_src_version($src) {
    // Check if it's a script or style source link and user is not logged in
    if (!is_user_logged_in() && strpos($src, '?ver=')) {
        // Split the source link into parts before and after the version
        $parts = explode('?ver=', $src, 2);

        // Keep only the part before the version
        return $parts[0];
    }
    
    return $src;
}
add_filter('script_loader_src', 'bhld_remove_src_version');
add_filter('style_loader_src', 'bhld_remove_src_version');

/**
 * Hide PHP version in HTTP response headers
 */
function bhld_hide_php_version() {
    return '';
}
add_filter('wp_generator', 'bhld_hide_php_version');

/**
 * Hide PHP version in generator meta tag
 */
function bhld_hide_php_version_meta() {
    return '';
}
add_filter('the_generator', 'bhld_hide_php_version_meta');

/**
 * Remove X-Powered-By header
 */
function bhld_remove_x_powered_by_header() {
    header_remove('X-Powered-By');
}
add_action('wp', 'bhld_remove_x_powered_by_header');

/**
 * Disable WordPress theme and plugin editors
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * ADMIN ENHANCEMENTS
 */

/**
 * Add 'Edit Home Page' button to admin bar
 */
function bhld_edit_homepage_adminbar_button($wp_admin_bar) {
    $homepage_id = get_option('page_on_front'); // Get homepage ID
    $edit_url = get_edit_post_link($homepage_id); // Get edit link for homepage

    if ($homepage_id && $edit_url) {
        $args = array(
            'id'    => 'edit_home_page',
            'title' => '<span class="ab-icon dashicons-admin-home"></span> Edit Home Page', // Use Dashicon as part of the title
            'href'  => $edit_url, // Link to edit homepage
            'meta'  => array(
                'class' => 'edit-home-page', // CSS class for the button (optional)
                'title' => 'Edit Home Page', // Button description (optional)
            ),
        );
        $wp_admin_bar->add_node($args);
    }
}
add_action('admin_bar_menu', 'bhld_edit_homepage_adminbar_button', 999);

/**
 * Some optpons for GenerateBlocks plugin
 */
$generateblocks_active = is_plugin_active('generateblocks/plugin.php');

if( $generateblocks_active ) {

    /**
     * Modify GenerateBlocks Global CSS Priority
     *
     * This function changes the priority of the GenerateBlocks global CSS
     * to ensure it loads after the main theme styles.
     */
    function modify_generateblocks_global_css_priority() {
        $generateblocks_css_path = '/wp-content/uploads/generateblocks/style-global.css';
        $full_path = get_home_url(null, $generateblocks_css_path);

        // Check if the file exists before registering and enqueuing
        if (file_exists(ABSPATH . $generateblocks_css_path)) {
            // Register the GenerateBlocks global style
            wp_register_style('generateblocks-global', $full_path, array('ct-main-styles'), filemtime(ABSPATH . $generateblocks_css_path), 'all');

            // Enqueue the GenerateBlocks global style with higher priority (11)
            wp_enqueue_style('generateblocks-global');
        }
    }
    add_action('wp_enqueue_scripts', 'modify_generateblocks_global_css_priority', 11);

    /**
     * GenerateBlocks .gb-container width fix for Blocksy
     *
     * This function changes width of the .gb-container
     * to theme default width from Blocksy
     */
    function add_custom_js_to_footer() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const targetElements = document.querySelectorAll('.entry-content > .gb-container.alignfull > .gb-container');
            targetElements.forEach(function(element) {
                element.setAttribute('data-gb-width', 'blocksy-width');
            });
        });
        </script>
        <?php
    }
    add_action('wp_footer', 'add_custom_js_to_footer');
    
    // function fancy_gb_fix() {
    //     wp_enqueue_style('fancy-gb-fix', FANCY_HELPERS_URL . 'public/css/fancy-gb-fix.min.css', array(), filemtime(FANCY_HELPERS_PATH . 'public/css/fancy-gb-fix.min.css'), false );
    // }
    // add_action('wp_enqueue_scripts', 'fancy_gb_fix');

   
    
    function add_preload_attribute($html, $handle, $href, $media) {
        // Check if this is the correct style
        if ($handle === 'fancy-gb-fix') {
            // Replace the standard <link> tag with a <link> tag with rel="preload"
            $html = str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html);
        }
        return $html;
    }
    add_filter('style_loader_tag', 'add_preload_attribute', 10, 4);

}

/**
 * Local ACF folder
 *
 */

// Check if the function is available
if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

// Check if ACF or ACF Pro plugins are active
if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) || is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {

    function acf_json_save_point( $path ) {
        $acf_json_dir = get_stylesheet_directory() . '/acf-json';

        // Create the directory if it does not exist
        if ( ! file_exists( $acf_json_dir ) ) {
            mkdir( $acf_json_dir, 0755, true );
        }

        return $acf_json_dir;
    }
    add_filter( 'acf/settings/save_json', 'acf_json_save_point' );

    function acf_json_load_point( $paths ) {
        $acf_json_dir = get_stylesheet_directory() . '/acf-json';

        if ( ! file_exists( $acf_json_dir ) ) {
            mkdir( $acf_json_dir, 0755, true );
        }

        $paths[] = $acf_json_dir;

        $acf_plugin_json_dir = plugin_dir_path( __FILE__ ) . 'includes/acf-json';
        if ( ! file_exists( $acf_plugin_json_dir ) ) {
            mkdir( $acf_plugin_json_dir, 0755, true );
        }
        $paths[] = $acf_plugin_json_dir;

        return $paths;
    }
    add_filter( 'acf/settings/load_json', 'acf_json_load_point' );

    /**
     * Force all ACF fields & options pages to be re-saved as JSON when the theme is switched.
     */
    function force_acf_json_resave() {
        if ( function_exists( 'acf_get_field_groups' ) ) {
            $acf_json_dir = get_stylesheet_directory() . '/acf-json';

            if ( ! file_exists( $acf_json_dir ) ) {
                mkdir( $acf_json_dir, 0755, true );
            }

            // Save field groups
            $field_groups = acf_get_field_groups();
            if ( ! empty( $field_groups ) ) {
                foreach ( $field_groups as $group ) {
                    acf_update_field_group( $group ); 
                }
            }

            // Save options pages data (if any)
            if ( function_exists( 'acf_get_options_page' ) ) {
                $options_pages = acf_get_options_page();
                if ( ! empty( $options_pages ) ) {
                    foreach ( $options_pages as $page ) {
                        $page_name = $page['menu_slug']; // Get the options page slug
                        $options = get_fields( $page_name );

                        if ( ! empty( $options ) ) {
                            file_put_contents( $acf_json_dir . "/options-{$page_name}.json", json_encode( $options, JSON_PRETTY_PRINT ) );
                        }
                    }
                }
            }
        }
    }
    add_action( 'after_switch_theme', 'force_acf_json_resave' );
}

/**
 * Current Year Shortcode
 *
 * This function returns the current year for use in a shortcode.
 *
 * @return string The current year (e.g., "2023")
 */
function fancy_current_year() {
    // Use gmdate to get the current year in GMT/UTC
    // esc_html is used for security to prevent XSS attacks
    return esc_html(gmdate('Y'));
}
// Register the shortcode [current_year] to use the fancy_current_year function
add_shortcode('current_year', 'fancy_current_year');

/**
 * Replace Current Year Tag
 *
 * This function replaces the {{current-year}} tag with the current year.
 *
 * @param string $content The content to search for the tag
 * @return string The content with the tag replaced
 */
function replace_current_year_tag($content) {
    // Get the current year using the fancy_current_year function
    $current_year = fancy_current_year();
    // Replace all occurrences of {{current-year}} with the actual year
    return str_replace('{{current-year}}', $current_year, $content);
}

// Apply the replacement to post content, text widgets, and blocks
add_filter('the_content', 'replace_current_year_tag');
add_filter('widget_text_content', 'replace_current_year_tag');
add_filter('render_block', 'replace_current_year_tag');

/**
 * Get Categories with Links
 *
 * This function retrieves the categories for the current post and returns them as linked HTML.
 *
 * @return string HTML string of linked categories or a "No categories" message
 */
function fancy_helpers_categories_with_links() {
    // Get categories for the current post
    $categories = get_the_category();

    // Check if the post has categories assigned
    if ( ! empty( $categories ) ) {
        $category_links = array();

        // Loop through each category and create links
        foreach ( $categories as $category ) {
            // Get the URL for the category
            $category_link = get_category_link( $category->term_id );
            // Sanitize the category name
            $category_name = esc_html( $category->name );

            // Add HTML link to the array
            $category_links[] = '<a href="' . esc_url( $category_link ) . '">' . $category_name . '</a>';
        }

        // Return category links, separated by commas
        return implode( ', ', $category_links );
    } else {
        // Return a translatable message if no categories are assigned
        return __('No categories', 'fancy-helpers');
    }
}