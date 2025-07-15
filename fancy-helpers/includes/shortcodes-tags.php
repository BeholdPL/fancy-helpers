<?php
/**
 * Fancy Helpers Shortcodes and Tags
 *
 * This code provides various shortcodes and content filters for managing
 * and displaying contact information, business hours, and social media links.
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * HOURS FUNCTIONALITY
 */

/**
 * Shortcode for displaying business hours
 *
 * Usage: [contacts_hours day="mon"]
 */
function fancy_hours_shortcode($atts) {
    $atts = shortcode_atts(array('day' => 'mon'), $atts, 'contacts_hours');

    $options = get_option('fancy_helpers_contacts_hours_options');
    $hours = isset($options[$atts['day']]) ? esc_html($options[$atts['day']]) : '';

    return $hours;
}
add_shortcode('contacts_hours', 'fancy_hours_shortcode');

/**
 * Start output buffering to replace contact hours tags in the entire page content, except in the admin panel.
 */
function fancy_helpers_start_contact_hours_buffering() {
    if (!is_admin()) {
        ob_start('fancy_replace_contact_tags');
    }
}
add_action('wp_loaded', 'fancy_helpers_start_contact_hours_buffering');

/**
 * Replace contact hours tags in the entire buffered content.
 *
 * @param string $content The buffered content.
 * @return string The modified content.
 */
function fancy_replace_contact_tags($content) {
    $options = get_option('fancy_helpers_contacts_hours_options');
    
    $pattern = '/{{contacts\.hours-(mon|tue|wed|thu|fri|sat|sun|mon_fri)}}/';
    
    $content = preg_replace_callback($pattern, function($matches) use ($options) {
        $day = $matches[1];
        return isset($options[$day]) ? esc_html($options[$day]) : '';
    }, $content);

    return $content;
}

/**
 * ADDRESSES FUNCTIONALITY
 */

/**
 * Shortcode for displaying addresses
 *
 * Usage: [contacts_address label="main"]
 */
function fancy_helpers_address_shortcode($atts) {
    $atts = shortcode_atts(array('label' => ''), $atts, 'contacts_address');

    $options = get_option('fancy_helpers_contacts_addresses_options');
    $addresses = isset($options['addresses']) ? $options['addresses'] : array();

    foreach ($addresses as $address) {
        if ($address['name'] === $atts['label']) {
            return wp_kses_post(nl2br($address['address']));
        }
    }
    return '';
}
add_shortcode('contacts_address', 'fancy_helpers_address_shortcode');

/**
 * Start output buffering to replace address tags in the entire page content, except in the admin panel.
 */
function fancy_helpers_start_address_tags_buffering() {
    if (!is_admin()) {
        ob_start('fancy_helpers_replace_address_tags');
    }
}
add_action('wp_loaded', 'fancy_helpers_start_address_tags_buffering');

/**
 * Replace address tags in the entire buffered content.
 *
 * @param string $content The buffered content.
 * @return string The modified content.
 */
function fancy_helpers_replace_address_tags($content) {
    $options = get_option('fancy_helpers_contacts_addresses_options');
    $addresses = isset($options['addresses']) ? $options['addresses'] : array();

    foreach ($addresses as $address) {
        $formatted_address = trim($address['address']);

        $formatted_address = esc_html($formatted_address);
        $formatted_address = str_replace(array("\r\n", "\r", "\n"), '<br>', $formatted_address);

        $tags = array(
            '{{contacts.address-' . esc_attr($address['name']) . '}}' => $formatted_address
        );

        $content = str_replace(array_keys($tags), $tags, $content);
    }

    return $content;
}

/**
 * PHONES FUNCTIONALITY
 */

/**
 * Shortcode for displaying phone numbers
 *
 * Usage: [contacts_phone label="main" format="default"]
 * Usage: [contacts_phone label="main" format="tel"]
 * Usage: [contacts_phone_tel label="main"]
 */
function fancy_helpers_phone_shortcode($atts) {
    $atts = shortcode_atts(array(
        'label' => '',
        'format' => 'default',
    ), $atts, 'contacts_phone');

    $options = get_option('fancy_helpers_contacts_phones_options');
    $phones = isset($options['phones']) ? $options['phones'] : array();

    foreach ($phones as $phone) {
        if ($phone['name'] === $atts['label']) {
            if ($atts['format'] === 'tel') {
                return 'tel:' . preg_replace('/[^0-9+]/', '', $phone['number']);
            }
            return esc_html($phone['number']);
        }
    }
    return '';
}
add_shortcode('contacts_phone', 'fancy_helpers_phone_shortcode');

/**
 * New shortcode for displaying phone numbers with tel prefix
 *
 * Usage: [contacts_phone_tel label="main"]
 */
function fancy_helpers_phone_tel_shortcode($atts) {
    $atts = shortcode_atts(array(
        'label' => '',
    ), $atts, 'contacts_phone_tel');

    $options = get_option('fancy_helpers_contacts_phones_options');
    $phones = isset($options['phones']) ? $options['phones'] : array();

    foreach ($phones as $phone) {
        if ($phone['name'] === $atts['label']) {
            return 'tel:' . preg_replace('/[^0-9+]/', '', $phone['number']);
        }
    }
    return '';
}
add_shortcode('contacts_phone_tel', 'fancy_helpers_phone_tel_shortcode');

/**
 * Start output buffering to replace phone tags in the entire page content, except in the admin panel.
 */
function fancy_helpers_start_phone_tags_buffering() {
    if (!is_admin()) {
        ob_start('fancy_helpers_replace_phone_tags');
    }
}
add_action('wp_loaded', 'fancy_helpers_start_phone_tags_buffering');

/**
 * Replace phone tags in the entire buffered content.
 *
 * @param string $content The buffered content.
 * @return string The modified content.
 */
function fancy_helpers_replace_phone_tags($content) {
    $options = get_option('fancy_helpers_contacts_phones_options');
    $phones = isset($options['phones']) ? $options['phones'] : array();

    foreach ($phones as $phone) {
        $tag = '{{contacts.phone-' . $phone['name'] . '}}';
        $content = str_replace($tag, esc_html($phone['number']), $content);

        $tel_tag = '{{contacts.phone-tel-' . $phone['name'] . '}}';
        $content = str_replace($tel_tag, 'tel:' . preg_replace('/[^0-9+]/', '', $phone['number']), $content);
    }

    return $content;
}

/**
 * EMAILS FUNCTIONALITY
 */

/**
 * Shortcode for displaying email addresses
 *
 * Usage: [contacts_email label="info" format="default"]
 * Usage: [contacts_email label="info" format="mailto"]
 * Usage: [contacts_email_mailto label="info"]
 */
function fancy_helpers_email_shortcode($atts) {
    $atts = shortcode_atts(array(
        'label' => '',
        'format' => 'default',
    ), $atts, 'contacts_email');

    $options = get_option('fancy_helpers_contacts_emails_options');
    $emails = isset($options['emails']) ? $options['emails'] : array();

    foreach ($emails as $email) {
        if ($email['name'] === $atts['label']) {
            if ($atts['format'] === 'mailto') {
                return 'mailto:' . esc_attr($email['address']);
            }
            return esc_html($email['address']);
        }
    }
    return '';
}
add_shortcode('contacts_email', 'fancy_helpers_email_shortcode');

/**
 * New shortcode for displaying email addresses with mailto prefix
 *
 * Usage: [contacts_email_mailto label="info"]
 */
function fancy_helpers_email_mailto_shortcode($atts) {
    $atts = shortcode_atts(array(
        'label' => '',
    ), $atts, 'contacts_email_mailto');

    $options = get_option('fancy_helpers_contacts_emails_options');
    $emails = isset($options['emails']) ? $options['emails'] : array();

    foreach ($emails as $email) {
        if ($email['name'] === $atts['label']) {
            return 'mailto:' . esc_attr($email['address']);
        }
    }
    return '';
}
add_shortcode('contacts_email_mailto', 'fancy_helpers_email_mailto_shortcode');

/**
 * Start output buffering to replace email tags in the entire page content, except in the admin panel.
 */
function fancy_helpers_start_email_tags_buffering() {
    if (!is_admin()) {
        ob_start('fancy_helpers_replace_email_tags');
    }
}
add_action('wp_loaded', 'fancy_helpers_start_email_tags_buffering');

/**
 * Replace email tags in the entire buffered content.
 *
 * @param string $content The buffered content.
 * @return string The modified content.
 */
function fancy_helpers_replace_email_tags($content) {
    $options = get_option('fancy_helpers_contacts_emails_options');
    $emails = isset($options['emails']) ? $options['emails'] : array();

    foreach ($emails as $email) {
        $tag = '{{contacts.email-' . $email['name'] . '}}';
        $content = str_replace($tag, esc_html($email['address']), $content);

        $mailto_tag = '{{contacts.email-mailto-' . $email['name'] . '}}';
        $content = str_replace($mailto_tag, 'mailto:' . esc_attr($email['address']), $content);
    }

    return $content;
}

/**
 * SOCIAL MEDIA FUNCTIONALITY
 */

/**
 * Shortcode for displaying social media information
 *
 * Usage: [contacts_social label="facebook" field="url"]
 */
function fancy_helpers_social_shortcode($atts) {
    $atts = shortcode_atts(array(
        'label' => '',
        'field' => 'url', // Domyślnie zwraca pełny URL, może zwracać też 'name' lub 'domain'
    ), $atts, 'contacts_social');

    $options = get_option('fancy_helpers_contacts_social_media_options');
    $social_media = isset($options['social_media']) ? $options['social_media'] : array();

    foreach ($social_media as $platform) {
        if ($platform['name'] === $atts['label']) {
            if ($atts['field'] === 'name') {
                return esc_html($platform['displayname']);
            } elseif ($atts['field'] === 'url') {
                return esc_url($platform['url']);
            } elseif ($atts['field'] === 'domain') {
                return esc_html(preg_replace('#^https?://#', '', rtrim($platform['url'], '/')));
            }
        }
    }

    return '';
}

add_shortcode('contacts_social', 'fancy_helpers_social_shortcode');

/**
 * Start output buffering to replace social media tags in the entire page content, except in the admin panel.
 */
function fancy_helpers_start_social_tags_buffering() {
    if (!is_admin()) {
        ob_start('fancy_helpers_replace_social_tags');
    }
}
add_action('wp_loaded', 'fancy_helpers_start_social_tags_buffering');

/**
 * Replace social media tags in the entire buffered content.
 *
 * @param string $content The buffered content.
 * @return string The modified content.
 */
function fancy_helpers_replace_social_tags($content) {
    $options = get_option('fancy_helpers_contacts_social_media_options');
    $social_media = isset($options['social_media']) ? $options['social_media'] : array();

    foreach ($social_media as $platform) {
        $remove_protocol = function($url) {
            return preg_replace('#^https?://#', '', rtrim($url, '/'));
        };

        $tags = array(
            '{{contacts.social-' . $platform['name'] . '}}' => esc_html($remove_protocol($platform['url'])),
            '{{contacts.social-' . $platform['name'] . '-url}}' => esc_html($platform['url']),
            '{{contacts.social-' . $platform['name'] . '-name}}' => esc_html($platform['displayname']),
            '{{contacts.social-' . $platform['name'] . '-domain}}' => esc_html($remove_protocol($platform['url']))
        );

        $content = str_replace(array_keys($tags), $tags, $content);
    }

    return $content;
}

/**
 * CUSTOM FIELDS FUNCTIONALITY
 */

/**
 * Shortcode for displaying custom field information
 *
 * Usage: [contacts_custom label="Nr konta"]
 */
function fancy_helpers_custom_shortcode($atts) {
    $atts = shortcode_atts(array('label' => ''), $atts, 'contacts_custom');

    $options = get_option('fancy_helpers_contacts_customs_options');
    $customs = isset($options['customs']) ? $options['customs'] : array();

    foreach ($customs as $custom) {
        if ($custom['name'] === $atts['label']) {
            return esc_html($custom['value']);
        }
    }
    return '';
}
add_shortcode('contacts_custom', 'fancy_helpers_custom_shortcode');

/**
 * Start output buffering to replace custom field tags in the entire page content, except in the admin panel.
 */
function fancy_helpers_start_custom_tags_buffering() {
    if (!is_admin()) {
        ob_start('fancy_helpers_replace_custom_tags');
    }
}
add_action('wp_loaded', 'fancy_helpers_start_custom_tags_buffering');

/**
 * Replace custom field tags in the entire buffered content.
 *
 * @param string $content The buffered content.
 * @return string The modified content.
 */
function fancy_helpers_replace_custom_tags($content) {
    $options = get_option('fancy_helpers_contacts_customs_options');
    $customs = isset($options['customs']) ? $options['customs'] : array();

    foreach ($customs as $custom) {
        $tag = '{{contacts.custom-' . $custom['name'] . '}}';
        $content = str_replace($tag, esc_html($custom['value']), $content);
    }

    return $content;
}

/**
 * LEGACY FUNCTIONALITY
 */

/**
 * Replace legacy tags in content for backward compatibility
 */
function fancy_helpers_replace_legacy_tags($content) {
    global $post;
    
    if (is_singular() && isset($post->ID)) {
        $pattern = '/{{field\.bhld-header-h1}}/';
        $replacement = get_post_meta($post->ID, 'bhld-header-h1', true);
        
        $content = preg_replace($pattern, esc_html($replacement), $content);
    }
    
    return $content;
}
add_filter('the_content', 'fancy_helpers_replace_legacy_tags');
add_filter('widget_text_content', 'fancy_helpers_replace_legacy_tags');
add_filter('render_block', 'fancy_helpers_replace_legacy_tags'); 
