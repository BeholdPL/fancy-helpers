<?php

// General CSS fix on front 
function ho_mix_front_styles() {
	wp_enqueue_style( 'ho-mix-front-styles', plugins_url( 'public/css/ho-mix-front-styles.css', __DIR__ ), array() );
}
add_action( 'wp_enqueue_scripts', 'ho_mix_front_styles' );

// General CSS fix in Gutenberg 
function ho_gutenberg_styles() {
	wp_enqueue_style( 'hx-gutenberg-styles', plugins_url( 'admin/css/hx-gutenberg-styles.css', __DIR__ ), array() );
}
add_action( 'enqueue_block_editor_assets', 'ho_gutenberg_styles' );

// Settings styles
function hp_handy_options_settings_styles() {
    if (is_user_logged_in() && isset($_GET['page'])) {
        if ($_GET['page'] === 'ho-handy-options' || $_GET['page'] === 'ho-contacts-options' ) {
            wp_enqueue_style( 'hx-handy-settings-styles', plugins_url( '/admin/css/hx-handy-settings-styles.css', __DIR__ ), array() );
        }
    }
}
add_action( 'admin_enqueue_scripts', 'hp_handy_options_settings_styles' );

// Settings styles custom
function hp_handy_settings_styles_custom() {
    if (is_user_logged_in() && isset($_GET['page'])) {
        if ($_GET['page'] === 'ho-handy-options' || $_GET['page'] === 'ho-contacts-options' ) {
            wp_enqueue_style('hp-handy-settings-styles-custom', plugins_url('/admin/css/ho-handy-settings-styles-custom.css', __DIR__), array());
        }
    }
}
add_action('admin_enqueue_scripts', 'hp_handy_settings_styles_custom');

// Settings scripts
function hp_handy_settings_scripts() {
    if (is_user_logged_in() && isset($_GET['page'])) {
        if ($_GET['page'] === 'ho-handy-options') {
            wp_enqueue_script('hp-handy-settings-scripts', plugins_url('/admin/js/ho-handy-settings-scripts.js', __DIR__), array(), false, true);
        }
    }
}
add_action('admin_enqueue_scripts', 'hp_handy_settings_scripts');

// Shortcode scripts
function hp_handy_shortcode_scripts() {
    if (is_user_logged_in() && isset($_GET['page'])) {
        if ($_GET['page'] === 'ho-handy-options' || $_GET['page'] === 'ho-contacts-options' ) {
            wp_enqueue_script('hx-handy-shortcode-scripts', plugins_url('/admin/js/hx-handy-shortcode-scripts.js', __DIR__), array(), false, true);
        }
    }
}
add_action('admin_enqueue_scripts', 'hp_handy_shortcode_scripts');

// Mix gutenberg styles
function ho_mix_gutenberg_styles() {
    wp_enqueue_style('ho-mix-gutenberg-styles', plugins_url('/admin/css/ho-mix-gutenberg-styles.css', __DIR__), array());

}
add_action('admin_enqueue_scripts', 'ho_mix_gutenberg_styles');

// Home page edit shortcut
function ho_edit_homepage_adminbar_button($wp_admin_bar) {
    $homepage_id = get_option('page_on_front'); // Pobranie ID strony głównej
    $edit_url = get_edit_post_link($homepage_id); // Uzyskanie linku do edycji strony głównej

    if ($homepage_id && $edit_url) {
        $args = array(
            'id'    => 'edit_home_page',
            'title' => '<span class="ab-icon dashicons-admin-home"></span> Edit Home Page', // Używamy klasy Dashicon jako części tytułu
            'href'  => $edit_url, // Link do edycji strony głównej
            'meta'  => array(
                'class' => 'edit-home-page', // Klasa CSS dla przycisku (opcjonalnie)
                'title' => 'Edit Home Page', // Opis przycisku (opcjonalnie)
            ),
        );
        $wp_admin_bar->add_node($args);
    }
}
add_action('admin_bar_menu', 'ho_edit_homepage_adminbar_button', 999);

// Redirect to plugin settings after activate
function ho_plugin_activation_redirect($plugin) {
    if ('handy-options/handy-options.php' === $plugin) {
        wp_redirect(admin_url('options-general.php?page=ho-handy-options'));
        update_option('handy_options', array(
            'ho_enable_custom_h1_header' => 1,
            'ho_fix_blocksy_generateblocks' => 1,
            'ho_remove_versions' => 1,
            'ho_disable_themes_file_editor' => 1
        ));
        exit;
    }
}
add_action('activated_plugin', 'ho_plugin_activation_redirect');
register_activation_hook( __FILE__, 'ho_plugin_activation_redirect' );