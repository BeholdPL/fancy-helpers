<?php

remove_action('wp_head', 'wp_generator');

function ho_remove_generator_version( $generator, $type ) {
	return '';
}
add_filter( 'the_generator', 'ho_remove_generator_version', 10, 2 );

function ho_remove_src_version( $src ) {
    // Sprawdź czy to jest link źródłowy do skryptu lub stylu
    if ( ! is_user_logged_in() && strpos( $src, '?ver=' ) ) {
        // Rozbij link źródłowy na część przed i po wersji
        $parts = explode( '?ver=', $src, 2 );

        // Zachowaj tylko część przed wersją
        $new_src = $parts[0];

        return $new_src;
    }
    
    return $src;
}

add_filter( 'script_loader_src', 'ho_remove_src_version' );
add_filter( 'style_loader_src', 'ho_remove_src_version' );


// Hide PHP version in HTTP response headers
function ho_hide_php_version() {
    return '';
}
add_filter('wp_generator', 'ho_hide_php_version');

// Hide PHP version in generator meta tag
function ho_hide_php_version_meta() {
    return '';
}
add_filter('the_generator', 'ho_hide_php_version_meta');

// Remove X-Powered-By header
function ho_remove_x_powered_by_header() {
    header_remove('X-Powered-By');
}
add_action('wp', 'ho_remove_x_powered_by_header');
