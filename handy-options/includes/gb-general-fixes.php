<?php

// Generate Blocks CSS fix on front 
function ho_gb_front_fix() {
  if ( is_plugin_active( 'generateblocks/plugin.php' ) ) {
    wp_enqueue_style( 'gb-front-fix', plugins_url( 'public/css/ho-gb-front-fix.css', __DIR__ ), array() );
  }
}
add_action( 'wp_enqueue_scripts', 'ho_gb_front_fix' );

// Generate Blocks CSS fix in Gutenberg 
function ho_gb_gutenberg_fix() {
  wp_enqueue_style( 'ho-gb-gutenberg-fix', plugins_url( 'admin/css/ho-gb-gutenberg-fix.css', __DIR__ ), array( 'generateblocks' ) );
}
add_action( 'enqueue_block_editor_assets', 'ho_gb_gutenberg_fix' );

// Generate Blocks heading fix
function gb_gutenberg_heading_fix() {
  // Sprawdź, czy jesteśmy w edytorze Gutenberg
  if (function_exists('wp_enqueue_script') && function_exists('get_current_screen') && get_current_screen()->is_block_editor()) {
      wp_enqueue_script( 'gb-gutenberg-heading-fix', plugins_url( 'admin/js/ho-gb-gutenberg-heading-fix.js', __DIR__ ), array(), false, false);
  }
}
add_action('enqueue_block_editor_assets', 'gb_gutenberg_heading_fix');