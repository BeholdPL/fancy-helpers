<?php

// Things
require_once plugin_dir_path( __FILE__ ) . '/helpers.php';

// Register new Options panel.
$panel_args = array(
    'title'           => esc_html__( 'Contacts', 'handy-options' ),
    'option_name'     => 'contacts_options',
    'slug'            => 'ho-contacts-options',
    'user_capability' => 'manage_options',
    'docs_url'        => '',
    'position'        => 80,  
    'menu_page_icon'  => 'dashicons-phone',
    'tabs'            => array(
        'tab-1' => esc_html__( 'Phones and e-mails', 'handy-options' ),
        'tab-2' => esc_html__( 'Adresses and work hours', 'handy-options' ),
        'tab-3' => esc_html__( 'Social media', 'handy-options' ),
        'tab-4' => esc_html__( 'Delivery services', 'handy-options' ),
    ),
);

$panel_settings = array(


    // #### Phones and e-mails ####

    // Phones
    'ho_contact_phone_1' => array(
        'label'       => esc_html__( 'Phone number 1', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_contact_info platform="phone" id="1" type="url"]', 'handy-options' ),
        'tab'         => 'tab-1',
    ),
    'ho_contact_phone_2' => array(
        'label'       => esc_html__( 'Phone number 2', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_contact_info platform="phone" id="2" type="url"]', 'handy-options' ),
        'tab'         => 'tab-1',
    ),
    'ho_contact_phone_3' => array(
        'label'       => esc_html__( 'Phone number 3', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'     => esc_html__( '[ho_contact_info platform="phone" id="3" type="url"]', 'handy-options' ),
        'tab'         => 'tab-1',
    ),

    // E-mails
    'ho_contact_email_1' => array(
        'label'       => esc_html__( 'E-mail adress 1', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_contact_info platform="email" id="1" type="url"]', 'handy-options' ),
        'tab'         => 'tab-1',
    ),
    'ho_contact_email_2' => array(
        'label'       => esc_html__( 'E-mail adress 2', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_contact_info platform="email" id="2" type="url"]', 'handy-options' ),
        'tab'         => 'tab-1',
    ),
    'ho_contact_email_3' => array(
        'label'       => esc_html__( 'E-mail adress 3', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_contact_info platform="email" id="3" type="url"]', 'handy-options' ),
        'tab'         => 'tab-1',
    ),

    // #### Working hours ####
    'ho_contact_working_hours_mon_fri' => array(
        'label'       => esc_html__( 'Mon. - Fri.', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_working_hours day="mon_fri"]', 'handy-options' ),
        'tab'         => 'tab-2',
    ),
    'ho_contact_working_hours_mon' => array(
        'label'       => esc_html__( 'Monday', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_working_hours day="mon"]', 'handy-options' ),
        'tab'         => 'tab-2',
    ),
    'ho_contact_working_hours_tue' => array(
        'label'       => esc_html__( 'Tuesday', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_working_hours day="tue"]', 'handy-options' ),
        'tab'         => 'tab-2',
    ),
    'ho_contact_working_hours_wed' => array(
        'label'       => esc_html__( 'Wednesday', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_working_hours day="wed"]', 'handy-options' ),
        'tab'         => 'tab-2',
    ),
    'ho_contact_working_hours_thu' => array(
        'label'       => esc_html__( 'Thursday', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_working_hours day="thu"]', 'handy-options' ),
        'tab'         => 'tab-2',
    ),
    'ho_contact_working_hours_fri' => array(
        'label'       => esc_html__( 'Friday', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_working_hours day="fri"]', 'handy-options' ),
        'tab'         => 'tab-2',
    ),
    'ho_contact_working_hours_sat' => array(
        'label'       => esc_html__( 'Saturday', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_working_hours day="sat"]', 'handy-options' ),
        'tab'         => 'tab-2',
    ),
    'ho_contact_working_hours_sun' => array(
        'label'       => esc_html__( 'Sunday', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( '', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_working_hours day="sun"]', 'handy-options' ),
        'tab'         => 'tab-2',
    ),
    
    // #### Social Media ####
    'ho_contact_social_media_facebook' => array(
        'label'       => esc_html__( 'Facebook', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'Name', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="facebook" type="name"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_facebook_url' => array(
        'label'       => esc_html__( '', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'URL', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="facebook" type="url"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_instagram' => array(
        'label'       => esc_html__( 'Instagram', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'Name', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="instagram" type="name"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_instagram_url' => array(
        'label'       => esc_html__( '', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'URL', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="instagram" type="url"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_linkedin' => array(
        'label'       => esc_html__( 'LinkedIn', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'Name', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="linkedin" type="name"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_linkedin_url' => array(
        'label'       => esc_html__( '', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'URL', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="linkedin" type="url"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_tiktok' => array(
        'label'       => esc_html__( 'TikTok', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'Name', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="tiktok" type="name"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_tiktok_url' => array(
        'label'       => esc_html__( '', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'URL', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="tiktok" type="url"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_youtube' => array(
        'label'       => esc_html__( 'YouTube', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'Name', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="youtube" type="name"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_youtube_url' => array(
        'label'       => esc_html__( '', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'URL', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="youtube" type="url"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_vimeo' => array(
        'label'       => esc_html__( 'Vimeo', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'Name', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="vimeo" type="name"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_vimeo_url' => array(
        'label'       => esc_html__( '', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'URL', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="vimeo" type="url"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_x' => array(
        'label'       => esc_html__( 'X', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'Name', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="x" type="name"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),
    'ho_contact_social_media_x_url' => array(
        'label'       => esc_html__( '', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'URL', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_social_media platform="x" type="url"]', 'handy-options' ),
        'tab'         => 'tab-3',
    ),

    // #### Delivery services ####
    'ho_contact_delivery_services_uber_eats' => array(
        'label'       => esc_html__( 'Uber Eats', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'Name', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_delivery_services platform="uber_eats" type="name"]', 'handy-options' ),
        'tab'         => 'tab-4',
    ),
    'ho_contact_delivery_services_uber_eats_url' => array(
        'label'       => esc_html__( '', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'URL', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_delivery_services platform="uber_eats" type="url"]', 'handy-options' ),
        'tab'         => 'tab-4',
    ),
    'ho_contact_delivery_services_glovo' => array(
        'label'       => esc_html__( 'Glovo', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'Name', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_delivery_services platform="glovo" type="name"]', 'handy-options' ),
        'tab'         => 'tab-4',
    ),
    'ho_contact_delivery_services_glovo_url' => array(
        'label'       => esc_html__( '', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'URL', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_delivery_services platform="glovo" type="url"]', 'handy-options' ),
        'tab'         => 'tab-4',
    ),
    'ho_contact_delivery_services_pyszne' => array(
        'label'       => esc_html__( 'Pyszne.pl', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'Name', 'handy-options' ),
        'shortcode'   => esc_html__( '[bhld-delivery-services platform="pyszne" type="name"]', 'handy-options' ),
        'tab'         => 'tab-4',
    ),
    'ho_contact_delivery_services_pyszne_url' => array(
        'label'       => esc_html__( '', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'URL', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_delivery_services platform="pyszne" type="url"]', 'handy-options' ),
        'tab'         => 'tab-4',
    ),
    'ho_contact_delivery_services_wolt' => array(
        'label'       => esc_html__( 'Wolt', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'Name', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_delivery_services platform="wolt" type="name"]', 'handy-options' ),
        'tab'         => 'tab-4',
    ),
    'ho_contact_delivery_services_wolt_url' => array(
        'label'       => esc_html__( '', 'handy-options' ),
        'type'        => 'text',
        'description' => esc_html__( 'URL', 'handy-options' ),
        'shortcode'   => esc_html__( '[ho_delivery_services platform="wolt" type="url"]', 'handy-options' ),
        'tab'         => 'tab-4',
    )
);

new HO_Options_Panel( $panel_args, $panel_settings, 'menu_page' );

// Helper function for returning theme options.
function get_contact_option( $option_name = '', $default = '' ) {
    $options = get_option( 'contacts_options' );
    return isset( $options[ $option_name ] ) ? $options[ $option_name ] : $default;
}

// Phones and e-mails
function ho_contact_info_shortcode($atts) {
    // Filtrowanie i walidacja atrybutów
    $platform = isset($atts['platform']) ? strtolower(sanitize_text_field($atts['platform'])) : '';
    $type = isset($atts['type']) ? strtolower(sanitize_text_field($atts['type'])) : '';
    $id = isset($atts['id']) ? intval($atts['id']) : 1;

    // Walidacja
    $allowed_plafforms = array('phone', 'email');
    $allowed_types = array('name', 'url');

    if (!in_array($platform, $allowed_plafforms, true)) {
        return esc_html__( 'Invalid platfom value', 'handy-options' );
    }
    if (!in_array($type, $allowed_types, true)) {
        return esc_html__( 'Invalid type value', 'handy-options' );
    }

    $option_name = 'ho_contact_' . $platform . '_' . $id;

    // Pobieranie zawartości atrybutów tylko raz
    $link_content = get_contact_option($option_name, '');
    $link_title = $link_content;
    $link = str_replace(' ', '', $link_content);

    if ($platform === 'phone' && $type === 'url') {
        // Sanitacja link_content przed użyciem
        $custom_url = esc_attr($link);
        return '<a href="tel:' . $custom_url . '">' . esc_html($link_title) . '</a>';
    } elseif ($platform === 'email' && $type === 'url') {
        // Sanitacja link_content przed użyciem
        $custom_url = esc_attr($link);
        return '<a href="mailto:' . $custom_url . '">' . esc_html($link_title) . '</a>';
    }

    // Sanitacja link_content przed użyciem
    $shortcode = esc_html($link_content);
    if ($shortcode) {
        return $shortcode;
    }
}
add_shortcode('ho_contact_info', 'ho_contact_info_shortcode');

// Working hours
function ho_contact_working_hours_shortcode($atts) {

    // Walidacja
    $allowed_days = array('mon_fri', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
    
    if (!isset($atts['day']) || !in_array(strtolower($atts['day']), $allowed_days)) {
        return esc_html__( 'Invalid day value', 'handy-options' );
    }

    $day = strtolower($atts['day']);
    $option_name = 'ho_contact_working_hours_' . $day;
    $working_hours = get_contact_option($option_name, '');

    return $working_hours;
}
add_shortcode('ho_working_hours', 'ho_contact_working_hours_shortcode');

// Social media
function ho_social_media_shortcode($atts) {
    $platform = isset($atts['platform']) ? sanitize_text_field($atts['platform']) : '';
    $type = isset($atts['type']) ? sanitize_text_field($atts['type']) : 'name';

    // Walidacja
    $allowed_types = array('name', 'url');
    $allowed_plafforms = array('facebook', 'instagram', 'linkedin', 'tiktok', 'youtube', 'vimeo', 'x');

    if (!in_array($type, $allowed_types, true)) {
        return esc_html__( 'Invalid type value', 'handy-options' );
    }
    if (!in_array($platform, $allowed_plafforms, true)) {
        return esc_html__( 'Invalid platforme value', 'handy-options' );
    }

    $option_name = 'ho_contact_social_media_' . $platform;

    // Użyj odpowiedniej funkcji do oczyszczenia danych, aby uniknąć wstrzykiwania kodu.
    if ($type === 'url') {
        $link = esc_url(get_contact_option($option_name . '_url'));
        $link_title = esc_html(get_contact_option($option_name, ''));
        return '<a href="' . $link . '" target="_blank" rel="noopener noreferrer">' . $link_title . '</a>';
    } else {
        $shortcode = get_contact_option($option_name, '');
        return esc_html($shortcode);
    }
}

add_shortcode('ho_social_media', 'ho_social_media_shortcode');

// Delivery services
function ho_delivery_services_shortcode($atts) {
    $platform = isset($atts['platform']) ? sanitize_text_field($atts['platform']) : '';
    $type = isset($atts['type']) ? sanitize_text_field($atts['type']) : 'name';

    // Walidacja
    $allowed_types = array('name', 'url');

    if (!in_array($type, $allowed_types, true)) {
        return esc_html__( 'Invalid type value', 'handy-options' );
    }

    $option_name = 'ho_contact_delivery_services_' . $platform;

    // Użyj odpowiedniej funkcji do oczyszczenia danych, aby uniknąć wstrzykiwania kodu.
    if ($type === 'url') {
        $link = esc_url(get_contact_option($option_name . '_url'));
        $link_title = esc_html(get_contact_option($option_name, ''));
        return '<a href="' . $link . '" target="_blank" rel="noopener noreferrer">' . $link_title . '</a>';
    } else {
        $shortcode = get_contact_option($option_name, '');
        return esc_html($shortcode);
    }
}

add_shortcode('ho_delivery_services', 'ho_delivery_services_shortcode');

// Shortcode scripts
require_once plugin_dir_path( __FILE__ ) . '/shortcode-scripts.php';