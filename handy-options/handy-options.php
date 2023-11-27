<?php
/**
* Plugin Name: Handy Options
* Plugin URI: Adres URL Twojego Pluginu
* Description: Some handy options.
* Version: 1.1 --- beta 1
* Author: Damian P.
* Author URI: Adres URL Twojego Autora
* License: Licencja Twojego Pluginu
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Shortcodes
include plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php' ;

// Things
include plugin_dir_path( __FILE__ ) . 'includes/things.php';

// Additional options
include plugin_dir_path( __FILE__ ) . 'includes/handy-options-page.php';