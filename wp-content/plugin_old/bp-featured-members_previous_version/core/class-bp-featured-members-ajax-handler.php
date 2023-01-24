<?php
// Exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajax action handler.
 */
class BP_Featured_Members_Ajax_Action_Handler {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_bp_process_featured_members_status', array( $this, 'handle' ) );
	}

	/**
	 * Handle marking/removing as featured member.
	 */
	public function handle() {

		$user_id = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : 0;

		check_ajax_referer( 'bp-featured-members-toggle-' . $user_id );

		if ( ! bp_featured_members()->current_user_can_toggle_member_status() ) {
			wp_send_json_error( __( "You don't have permission.", 'bp-featured-members' ) );
		}

		$fm = bp_featured_members();

		if ( $fm->is_featured( $user_id ) ) {
			$success   = $fm->remove_user( $user_id );
			$btn_label = __( 'Set Featured', 'bp-featured-members' );
		} else {
			$success   = $fm->add_user( $user_id );
			$btn_label = __( 'Remove Featured', 'bp-featured-members' );
		}

		if ( $success ) {
			wp_send_json_success( array( 'btn_label' => $btn_label ) );
		} else {
			wp_send_json_error( 'Something went wrong!', 'bp-featured-members' );
		}
	}
}

new BP_Featured_Members_Ajax_Action_Handler();
