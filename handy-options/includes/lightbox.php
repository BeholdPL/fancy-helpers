<?php

/** Galleries JS **/
function ho_gallery() {
    wp_enqueue_script( 'galleries', plugins_url( 'public/js/ho-galleries.js', __DIR__ ), array() );
  }
add_action( 'get_footer', 'ho_gallery' );
  
/** Lightbox JS **/
function ho_lightbox() {
    wp_enqueue_script( 'lightbox',  plugins_url( 'public/js/ho-fslightbox.js', __DIR__ ), array() );
}
add_action( 'get_footer', 'ho_lightbox' );