<?php
/**
 * Fancy Helpers Custom H1 Functionality
 *
 * This file contains functions to add and manage custom H1 tags for posts and pages.
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom H1 meta for all public post types
 */
function register_custom_h1_meta() {
    $post_types = get_post_types(array('public' => true));

    foreach ($post_types as $post_type) {
        register_post_meta($post_type, 'fancy_custom_h1', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        ));
    }
}
add_action('init', 'register_custom_h1_meta');

/**
 * Add custom H1 metabox to all public post types
 */
function add_custom_h1_metabox() {
    $post_types = get_post_types(array('public' => true));
    $meta_box_title = __('Custom H1', 'fancy-helpers');

    foreach ($post_types as $post_type) {
        add_meta_box(
            'fancy_custom_h1_metabox',
            $meta_box_title,
            'custom_h1_metabox_callback',
            $post_type,
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'add_custom_h1_metabox');

/**
 * Render custom H1 metabox content
 *
 * @param WP_Post $post The post object
 */
function custom_h1_metabox_callback($post) {
    wp_nonce_field('fancy_custom_h1_metabox', 'fancy_custom_h1_nonce');
    $value = get_post_meta($post->ID, 'fancy_custom_h1', true);
    $shortcode = '[custom-h1]';
    $tag = '{{custom-h1}}';
    
    ?>
    <label for="fancy_custom_h1_field"><?php esc_html_e('Type your custom H1 tag here:', 'fancy-helpers'); ?></label>
    <input type="text" id="fancy_custom_h1_field" name="fancy_custom_h1" value="<?php echo esc_attr($value); ?>" style="width:100%">
    <p class="description">
        <?php esc_html_e('Shortcode:', 'fancy-helpers'); ?> <span class="copyable" data-copy="<?php echo esc_attr($shortcode); ?>"><?php echo esc_html($shortcode); ?></span><br>
        <?php esc_html_e('Tag:', 'fancy-helpers'); ?> <span class="copyable" data-copy="<?php echo esc_attr($tag); ?>"><?php echo esc_html($tag); ?></span>
    </p>

    <script>
    jQuery(document).ready(function($) {
        function copyToClipboard(text) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(text).select();
            document.execCommand("copy");
            $temp.remove();

            var $notification = $('<div id="copy-notification"><?php esc_html_e('Copied to clipboard!', 'fancy-helpers'); ?></div>');
            $('body').append($notification);
            $notification.fadeIn().delay(2000).fadeOut(function() {
                $(this).remove();
            });
        }

        $('.copyable').on('click', function(e) {
            e.preventDefault();
            var textToCopy = $(this).data('copy');
            copyToClipboard(textToCopy);
        });
    });
    </script>
    <?php
}

/**
 * Save custom H1 meta when the post is saved
 *
 * @param int $post_id The ID of the post being saved
 */
function save_custom_h1_metabox($post_id) {
    if (!isset($_POST['fancy_custom_h1_nonce']) || !wp_verify_nonce($_POST['fancy_custom_h1_nonce'], 'fancy_custom_h1_metabox')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['fancy_custom_h1'])) {
        update_post_meta($post_id, 'fancy_custom_h1', sanitize_text_field($_POST['fancy_custom_h1']));
    }
}
add_action('save_post', 'save_custom_h1_metabox');

/**
 * Get the custom H1 value for a given post
 *
 * @param int|null $post_id The post ID (optional)
 * @return string The custom H1 value
 */
function fancy_helpers_get_custom_h1($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, 'fancy_custom_h1', true);
}

/**
 * Handle [custom-h1] shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string The custom H1 value
 */
function fancy_helpers_custom_h1_shortcode($atts) {
    $custom_h1 = fancy_helpers_get_custom_h1();
    return $custom_h1 ? esc_html($custom_h1) : '';
}
add_shortcode('custom-h1', 'fancy_helpers_custom_h1_shortcode');

/**
 * Start output buffering to replace {{custom-h1}} tag in the entire page content.
 */
function fancy_helpers_start_output_buffering() {
    ob_start('fancy_helpers_replace_custom_h1_tag');
}
add_action('wp_loaded', 'fancy_helpers_start_output_buffering');

/**
 * Replace {{custom-h1}} tag in the entire buffered content.
 *
 * @param string $content The buffered content.
 * @return string The modified content.
 */
function fancy_helpers_replace_custom_h1_tag($content) {
    $custom_h1 = fancy_helpers_get_custom_h1();
    $tag = '{{custom-h1}}';
    
    if (strpos($content, $tag) !== false && $custom_h1) {
        $replacement = esc_html($custom_h1);
        $content = str_replace($tag, $replacement, $content);
    }
    
    return $content;
}