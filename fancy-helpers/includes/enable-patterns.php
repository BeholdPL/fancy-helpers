<?php
/**
 * Fancy Helpers Patterns Plugin
 *
 * This plugin adds custom functionality for managing patterns in WordPress,
 * including a custom admin menu, custom columns, and disabling default Gutenberg patterns.
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add patterns functionality to the WordPress dashboard
 */
function fancy_helpers_patterns_in_dashboard() {
    add_action('admin_menu', 'bhld_blocks_patterns_admin_menu');
    add_filter('manage_wp_block_posts_columns', 'bhld_sync_info_column');
    add_action('manage_wp_block_posts_custom_column', 'bhld_sync_info_column_content', 10, 2);
}
add_action('init', 'fancy_helpers_patterns_in_dashboard');

/**
 * Add a custom menu item for Patterns in the WordPress admin
 */
function bhld_blocks_patterns_admin_menu() {
    add_menu_page(
        __('Patterns', 'handy-patterns'),
        __('Patterns', 'handy-patterns'),
        'edit_posts',
        'edit.php?post_type=wp_block',
        '',
        'data:image/svg+xml;base64,' . base64_encode(get_patterns_svg_icon()),
        60
    );
}

/**
 * Get the SVG icon for the Patterns menu item
 *
 * @return string The SVG icon as a string
 */
function get_patterns_svg_icon() {
    return '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
    <svg width="100%" height="100%" viewBox="0 0 192 157" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
        <g transform="matrix(1,0,0,1,-247.946,-249.444)">
            <g transform="matrix(0.418605,0,0,0.418605,236.783,220.421)">
                <path d="M469.333,122.667L256,122.667C247.223,122.667 240,115.444 240,106.667C240,97.889 247.223,90.667 256,90.667L469.333,90.667C478.111,90.667 485.333,97.889 485.333,106.667C485.333,115.444 478.111,122.667 469.333,122.667Z" style="fill:rgb(155,162,166);fill-rule:nonzero;"/>
            </g>
            <g transform="matrix(0.418605,0,0,0.418605,236.783,220.421)">
                <path d="M384,208L256,208C247.223,208 240,200.777 240,192C240,183.223 247.223,176 256,176L384,176C392.777,176 400,183.223 400,192C400,200.777 392.777,208 384,208Z" style="fill:rgb(155,162,166);fill-rule:nonzero;"/>
            </g>
            <g transform="matrix(0.418605,0,0,0.418605,236.783,220.421)">
                <path d="M469.333,336L256,336C247.223,336 240,328.777 240,320C240,311.223 247.223,304 256,304L469.333,304C478.111,304 485.333,311.223 485.333,320C485.333,328.777 478.111,336 469.333,336Z" style="fill:rgb(155,162,166);fill-rule:nonzero;"/>
            </g>
            <g transform="matrix(0.418605,0,0,0.418605,236.783,220.421)">
                <path d="M384,421.333L256,421.333C247.223,421.333 240,414.111 240,405.333C240,396.556 247.223,389.333 256,389.333L384,389.333C392.777,389.333 400,396.556 400,405.333C400,414.111 392.777,421.333 384,421.333Z" style="fill:rgb(155,162,166);fill-rule:nonzero;"/>
            </g>
            <g transform="matrix(0.418605,0,0,0.418605,236.783,220.421)">
                <path d="M186.667,149.333C186.667,105.18 150.82,69.333 106.667,69.333C62.513,69.333 26.667,105.18 26.667,149.333C26.667,193.487 62.513,229.333 106.667,229.333C150.82,229.333 186.667,193.487 186.667,149.333Z" style="fill:rgb(155,162,166);"/>
            </g>
            <g transform="matrix(0.418605,0,0,0.418605,236.783,220.421)">
                <path d="M186.667,362.667C186.667,318.513 150.82,282.667 106.667,282.667C62.513,282.667 26.667,318.513 26.667,362.667C26.667,406.82 62.513,442.667 106.667,442.667C150.82,442.667 186.667,406.82 186.667,362.667Z" style="fill:rgb(155,162,166);"/>
            </g>
        </g>
    </svg>';
}

/**
 * Add a custom column to the wp_block list view
 *
 * @param array $columns The existing columns
 * @return array The modified columns
 */
function bhld_sync_info_column($columns) {
    $new_columns = array();

    foreach ($columns as $column_key => $column_value) {
        if ($column_key !== 'date') {
            $new_columns[$column_key] = $column_value;
        }
    }

    $new_columns['pattern_sync_status'] = __('Sync Status', 'handy-patterns');
    return $new_columns;
}

/**
 * Fill the content of the custom column
 *
 * @param string $column_name The name of the column
 * @param int $post_id The ID of the current post
 */
function bhld_sync_info_column_content($column_name, $post_id) {
    if ($column_name === 'pattern_sync_status') {
        $status = get_post_meta($post_id, 'wp_pattern_sync_status', true);
        echo $status === 'unsynced' 
            ? esc_html__('Not Synced', 'handy-patterns') 
            : '<span style="font-weight: 700; color: #7A00DF">' . esc_html__('Synced', 'handy-patterns') . '</span>';
    }
}

/**
 * Disable default Gutenberg patterns
 */
function fancy_helpers_disable_default_gutenberg_patterns() {
    remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', 'fancy_helpers_disable_default_gutenberg_patterns');