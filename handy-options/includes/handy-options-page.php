<?php

// Things
require_once plugin_dir_path( __FILE__ ) . '/helpers.php';

// Register new Options panel.
$panel_args = array(
    'title'           => esc_html__( 'Handy Options', 'handy-options' ),
    'option_name'     => 'handy_options',
    'slug'            => 'ho-handy-options',
    'user_capability' => 'manage_options',
    'docs_url'        => '',
    'tabs'            => array(
        'general' => esc_html__( 'General', 'handy-options' ),
        //'blocksy-theme' => esc_html__( 'Blocksy Theme', 'handy-options' ),
        'security' => esc_html__( 'Security', 'handy-options' ),
    ),
);

// Settings for Blocksy Theme
if ( is_plugin_active('blocksy-companion/blocksy-companion.php') || is_plugin_active('blocksy-companion-pro/blocksy-companion.php' )) {
    // Jeśli plugin 'Blocksy' lub 'Blocksy Pro' jest aktywny, dodajemy 'blocksy-theme' do tablicy $tabs
    $panel_args['tabs']['blocksy-theme'] = esc_html__('Blocksy Theme', 'handy-options');
}

// Performance settings for Apache or LiteSpeed
$server_software = $_SERVER['SERVER_SOFTWARE'];
$performance_tab_label = esc_html__('Performance', 'handy-options');

if (strpos($server_software, 'Apache') !== false || strpos($server_software, 'LiteSpeed') !== false) {
    $panel_args['tabs']['performance'] = $performance_tab_label;
}

$ordered_tabs = array(
    // Tworzenie nowej tablicy 'tabs' z uporządkowanymi kluczami
    'general',
    'blocksy-theme',
    'security'
);

$new_tabs = array();
foreach ($ordered_tabs as $key) {
    if (isset($panel_args['tabs'][$key])) {
        $new_tabs[$key] = $panel_args['tabs'][$key];
    }
}

// Aktualizacja tablicy 'tabs' w $panel_args
$panel_args['tabs'] = $new_tabs;

$panel_settings = array(
    // General
    'ho_enable_lightbox' => array(
        'label'       => esc_html__( 'Enable lightbox', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Enables lightbox, made with fslightbox.js lite.', 'handy-options' ),
        'tab'         => 'general',
    ),
    'ho_enable_css_animations' => array(
        'label'       => esc_html__( 'Enable animations', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Enables smart CSS animations.', 'handy-options' ),
        'tab'         => 'general',
    ),
    'ho_enable_custom_h1_header' => array(
        'label'       => esc_html__( 'Enable custom H1', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Enables additional field for custom H1 tag.', 'handy-options' ),
        'default'     => true,
        'tab'         => 'general',
    ),
    'ho_enable_contacts_settings' => array(
        'label'       => esc_html__( 'Enable contacts', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Enables contacts settings.', 'handy-options' ),
        'tab'         => 'general',
    ),
    'ho_enable_cookiehub_fix' => array(
        'label'       => esc_html__( 'Fix CookieHub buttons', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Fixes button text size when using CookieHub.', 'handy-options' ),
        'tab'         => 'general',
    ),
    'ho_redirect_login' => array(
        'label'       => esc_html__( 'Redirect login URL', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Use /login instead of /wp-admin or /wp-login.php.', 'handy-options' ),
        'tab'         => 'general',
    ),
    // Blocksy Theme
    'ho_enable_blocksy_mobile_menu_slide' => array(
        'label'       => esc_html__( 'Slide content with offcanvas', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Slide page content using "Side Panel Offcanvas"', 'handy-options' ),
        'tab'         => 'blocksy-theme',
    ),
    // Security
    'ho_remove_versions' => array(
        'label'       => esc_html__( 'Remove versions', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Displaying WordPress versions in headers provides information about the version being used, which can be exploited by potential attackers to identify vulnerabilities. Disabling the display of WordPress versions in headers helps prevent targeted attacks and enhances overall website security.', 'handy-options' ),
        'tab'         => 'security',
        'default'     => true,
    ),
    'ho_disable_XMLRPC' => array(
        'label'       => esc_html__( 'Disable XML-RPC', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'XML-RPC is a communication protocol that enables data exchange between different systems using XML. Disabling XML-RPC in WordPress can help enhance security because this protocol has been exploited in some attacks, such as remote login attempts, request spamming, and brute-force attack attempts.', 'handy-options' ),
        'tab'         => 'security',
    ),
    'ho_enable_x_content_type_options' => array(
        'label'       => esc_html__( 'Enable X-Content-Type-Options', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'X-Content-Type-Options is an HTTP header that provides protection against MIME sniffing attacks and enhances the security of web applications by controlling the types of content being transmitted.', 'handy-options' ),
        'tab'         => 'security',
    ),
    'ho_enable_x_frame_options' => array(
        'label'       => esc_html__( 'Enable X-Frame-Options', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'X-Frame-Options is an HTTP header used to control how a webpage can be embedded in frames on other websites. It enhances security by preventing potential risks associated with framing, such as clickjacking attacks.', 'handy-options' ),
        'tab'         => 'security',
    ),
    'ho_enable_x_xss_protection' => array(
        'label'       => esc_html__( 'Enable X-XSS Protection', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'X-XSS-Protection is an HTTP header that helps protect web applications against Cross-Site Scripting (XSS) attacks. By enabling this header, the browser activates built-in XSS protection mechanisms, effectively blocking or removing potentially malicious script code and enhancing the overall security of the application.', 'handy-options' ),
        'tab'         => 'security',
    ),
    'ho_enable_strict_transport_security' => array(
        'label'       => esc_html__( 'Enable Strict-Transport-Security', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Strict-Transport-Security (HSTS) is essential for security because it protects against Man-in-the-Middle attacks by ensuring a secure HTTPS connection between the browser and the server. It also eliminates the risk of using unsecured HTTP protocols, increasing user data confidentiality and building trust in the website.', 'handy-options' ),
        'tab'         => 'security',
    ),
    'ho_enable_referrer_policy' => array(
        'label'       => esc_html__( 'Enable Referrer-Policy', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Enabling the Referrer-Policy header is essential for protecting user privacy and minimizing the risk of information leakage on websites. By limiting or disabling the transmission of referrer data, it enhances security and ensures compliance with data protection regulations.', 'handy-options' ),
        'tab'         => 'security',
    ),
    'ho_enable_permissions_policy' => array(
        'label'       => esc_html__( 'Enable Permissions-Policy', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Enabling the `Permissions-Policy` header is important for security and user privacy, allowing control over access to functions and APIs on the website while complying with industry recommendations. It gives users greater control over their data and minimizes the risk of potential attacks.', 'handy-options' ),
        'tab'         => 'security',
    ),
    'ho_disable_windows_live_writer_link' => array(
        'label'       => esc_html__( 'Disable Windows Live Writer link', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Disabling the Windows Live Writer link is beneficial for security and improving the user experience. Removing this feature eliminates potential security vulnerabilities associated with the link, and users are not exposed to unnecessary options, leading to a cleaner and more streamlined interface.', 'handy-options' ),
        'tab'         => 'security',
    ),
    'ho_disable_rest_links' => array(
        'label'       => esc_html__( 'Disable REST links', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Removing links to the REST API from HTTP response headers improves security, limits potential attacks, and provides control over publicly available information on the website. However, it may complicate integration with external services, so careful consideration is essential before making the decision.', 'handy-options' ),
        'tab'         => 'security',
    ),
    'ho_disable_themes_file_editor' => array(
        'label'       => esc_html__( 'Disable themes file editor', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Disabling the themes file editor in WordPress is recommended for security reasons, to avoid potential attacks, code errors, and prevent loss of changes during theme updates. A better approach is to use secure methods for editing theme files at the server level or through a Child Theme.', 'handy-options' ),
        'tab'         => 'security ',
        'default'     => true,
    ),
);


// Dodaje opcje dla Rank Math
if ( is_plugin_active('seo-by-rank-math/rank-math.php') ) {
    $panel_settings['ho_unify_rank_math_icon'] = array(
        'label'       => esc_html__( 'Unify Rank Math icon in editor', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Disables scores in the Rank Math icon and makes the icon more uniform.', 'handy-options' ),
        'tab'         => 'general',
    );
}

// Dodaje opcje dla GenerateBlocks
if ( is_plugin_active('generateblocks/plugin.php') ) {
    $panel_settings['ho_fix_blocksy_generateblocks'] = array(
        'label'       => esc_html__( 'Fix GB compatibility', 'handy-options' ),
        'type'        => 'checkbox',
        'description' => esc_html__( 'Fixes GenerateBlocks compatibility with Blocksy Theme (BETA).', 'handy-options' ),
        'default'     => true,
        'tab'         => 'blocksy-theme',
    );
}

// Zmiana kolejności kluczy w tablicy
$ordered_settings = array(
    'ho_enable_lightbox',
    'ho_enable_css_animations',
    'ho_enable_custom_h1_header',
    'ho_enable_contacts_settings',
    'ho_unify_rank_math_icon',
    'ho_fix_blocksy_generateblocks',
    'ho_enable_blocksy_custom_content_spacing',
    'ho_blocksy_custom_content_spacing',
    'ho_enable_blocksy_mobile_menu_slide',
    'ho_enable_cookiehub_fix',
    'ho_redirect_login',
    'ho_remove_versions',
    'ho_disable_XMLRPC',
    'ho_enable_x_content_type_options',
    'ho_enable_x_frame_options',
    'ho_enable_x_xss_protection',
    'ho_enable_strict_transport_security',
    'ho_enable_referrer_policy',
    'ho_enable_permissions_policy',
    'ho_disable_windows_live_writer_link',
    'ho_disable_rest_links',
    'ho_disable_themes_file_editor'
);

// Tworzenie nowej tablicy z uporządkowanymi kluczami
$new_panel_settings = array();
foreach ($ordered_settings as $key) {
    if (isset($panel_settings[$key])) {
        $new_panel_settings[$key] = $panel_settings[$key];
    }
}

new HO_Options_Panel( $panel_args, $new_panel_settings, 'submenu_page' );

// Helper function for returning theme options.
function get_handy_options_option( $option_name = '', $default = '' ) {
    $options = get_option( 'handy_options' );
    return isset( $options[ $option_name ] ) ? $options[ $option_name ] : $default;
}

// Options
$plugin_configs = array(
    'ho_enable_lightbox' => 'lightbox.php',
    'ho_enable_css_animations' => 'animations.php',
    'ho_enable_custom_h1_header' => 'custom-h1.php',
    'ho_enable_contacts_settings' => 'contacts-options-page.php',
    'ho_disable_default_block_patterns_enabled' => 'disable-default-block-patterns.php',
    'ho_unify_rank_math_icon' => 'rank-math.php',
    'ho_fix_blocksy_generateblocks' => 'gb-general-fixes.php',
    'ho_enable_blocksy_mobile_menu_slide' => 'mobile-menu-container-slide.php',
    'ho_enable_cookiehub_fix' => 'cookiehub-fix.php',
     'ho_redirect_login' => 'login-redirect.php',
    'ho_remove_versions' => 'versions.php',
    'ho_disable_XMLRPC' => 'XMLRPC.php',
    'ho_enable_x_content_type_options' => 'x-content-type-options.php',
    'ho_enable_x_frame_options' => 'x-frame-options.php',
    'ho_enable_x_xss_protection' => 'x-xss-protection.php',
    'ho_enable_strict_transport_security' => 'strict-transport-security.php',
    'ho_enable_referrer_policy' => 'referrer-policy.php',
    'ho_enable_permissions_policy' => 'permissions-policy.php',
    'ho_disable_windows_live_writer_link' => 'windows-live-writer.php',
    'ho_disable_rest_links' => 'rest-links.php',
    'ho_disable_themes_file_editor' => 'themes-file-editor.php',
);

foreach ($plugin_configs as $config_option => $config_file) {
    $config_value = get_handy_options_option($config_option, '');
    if ($config_value) {
        include plugin_dir_path(__FILE__) . '/' . $config_file;
    }
}