<?php

/** Animations JS **/
function ho_animations_js() {
    wp_enqueue_script( 'ho-animations', plugins_url( 'public/js/ho-animations.js', __DIR__ ) );
}
add_action( 'get_footer', 'ho_animations_js' );

/** Animations CSS **/
function ho_animations_css() {
  wp_enqueue_style( 'ho-animations', plugins_url( 'public/css/ho-animations.css', __DIR__  ) );
}
add_action( 'get_footer', 'ho_animations_css' );