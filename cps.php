<?php
/**
 * @package custom-post-styles
 */
/*
Plugin Name: Custom Post Styles
Description: Allows you to add custom css styles to posts
Version: 1.0.1
Author: Cernocky
Author URI: www.daliborcernocky.com
License: GPLv2 or later
Text Domain: custom-post-styles
*/

// Make sure we don't expose any info if called directly
if( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'CPS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( CPS__PLUGIN_DIR . 'class.cps.php' );
add_action( 'init', array( 'CPS', 'init' ) );

if( is_admin() ) {
	require_once( CPS__PLUGIN_DIR . 'class.cps-admin.php' );
	add_action( 'init', array( 'CPS_Admin', 'init' ) );
}
