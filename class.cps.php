<?php

class CPS {
	private static $initiated = false;

	public static function init() {
		if (!self::$initiated) {
			self::init_hooks();
		}
	}

	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		self::$initiated = true;
		add_action( 'wp_enqueue_scripts', array('CPS', 'cps_customize_css') );
	}

	public static function cps_customize_css() {
		$post_id = get_the_ID();
		if ( !empty( $post_id ) ) {
			$cps = get_post_meta( $post_id, 'cps', true );
			if ( !empty( $cps ) ) {
				wp_register_style('cps', plugin_dir_url( __FILE__ ) . 'post-style.css', array(), null, true);
				wp_enqueue_style('cps');
				wp_add_inline_style('cps', $cps);
			}
		}
	}
}
