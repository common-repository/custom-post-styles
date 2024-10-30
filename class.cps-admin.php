<?php

class CPS_Admin {
	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		self::$initiated = true;
		add_action( 'add_meta_boxes', array('CPS_Admin','cps_add_post_meta_boxes') );
		add_action( 'save_post', array('CPS_Admin', 'cps_save_post_class_meta'), 10, 2 );
	}

	public static function cps_add_post_meta_boxes() {

		add_meta_box(
			'cps-post',
			'Custom Post Styles',    // Title
			array('CPS_Admin','cps_meta_box'),
			'post',
			'normal',
			'default'
		);
	}

	/* Display the post meta box. */
	public static function cps_meta_box($object, $box) { ?>

		<?php wp_nonce_field( basename( __FILE__ ), 'cps_nonce' ); ?>

		<textarea class="widefat" style="border:none;box-shadow:none;font-family:monospace;" type="text" name="cps-post" id="cps-post" rows="20"><?php echo esc_attr( get_post_meta( $object->ID, 'cps', true ) ); ?></textarea>
	<?php }


	/* Save the meta box's post metadata. */
	public static function cps_save_post_class_meta( $post_id, $post ) {

		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['cps_nonce'] ) || !wp_verify_nonce( $_POST['cps_nonce'], basename( __FILE__ ) ) )
			return $post_id;

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;

		/* Get the posted data and sanitize it for use as an HTML class. */
		$new_meta_value = ( isset( $_POST['cps-post'] ) ? $_POST['cps-post'] : '' );

		/* Get the meta key. */
		$meta_key = 'cps';

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );
	}

}
