<?php

function custom_login_redirect() {
    // Check if the current URL is /login
    if( $_SERVER['REQUEST_URI'] === '/login' ) {
        // Redirect to the login page
        wp_redirect(wp_login_url());
        exit();
    }
}
add_action('template_redirect', 'custom_login_redirect');