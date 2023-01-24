<?php
/**
 * Profile completion class
 *
 * @package BP_Profile_Completion
 */

namespace BP_Profile_Completion\Core;

// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Profile_Completion_Helper
 */
class BP_Profile_Completion_Helper {

	/**
	 * Please do not set it. It ise used for temporary notice.
	 *
	 * @var string
	 */
	private $notice = '';

	/**
	 * Boot class
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Setup hooks.
	 */
	public function setup() {

		//add_filter( 'bp_settings_admin_nav', '__return_empty_array' );

		// check on login for the profile completion.
		add_action( 'wp_login', array( $this, 'on_login_check' ), 0 );

		// check on account activation for the profile complete.
		// add_action( 'bp_core_activated_user', array( $this, 'on_profile_update' ) );

		// check on profile update for the profile completion.
		add_action( 'xprofile_updated_profile', array( $this, 'on_profile_update' ), 0 );

		// ON AVATAR UPLOAD
		// On avatar delete
		// Record on new avatar upload & check for profile completion.
		add_action( 'xprofile_avatar_uploaded', array( $this, 'log_uploaded' ) );
		add_action( 'xprofile_cover_image_uploaded', array( $this, 'log_cover_uploaded' ) );

		// on avatar delete, remove the log and mark profile incomplete.
		add_action( 'bp_core_delete_existing_avatar', array( $this, 'log_deleted' ) );
		add_action( 'xprofile_cover_image_deleted', array( $this, 'log_cover_deleted' ) );

		// Show teh notice.
		add_action( 'bp_template_redirect', array( $this, 'check_profile_completion_state' ) );

		add_filter( 'bp_force_profile_completion_skip_check', array( $this, 'pmpro_compat' ) );

		add_action( 'bpavmod_avatar_restored', array( $this, 'on_restore' ), 10, 2 );
	}

	/**
	 * Check profile completion on login.
	 *
	 * @param string $user_login username.
	 */
	public function on_login_check( $user_login ) {

		$user = get_user_by( 'login', $user_login );

		if ( ! $user ) {
			return;
		}

		// In future, we should force check completeness, instead of the meta based check.
		$this->check_on_update( $user->ID );
	}

	/**
	 * On profile update, force recheck of the completeness.
	 *
	 * @param int $user_id user id.
	 */
	public function on_profile_update( $user_id ) {
		// force recheck.
		$this->mark_incomplete_profile( $user_id );
		delete_user_meta( $user_id, '_has_required_field_data' );
		$this->check_on_update( $user_id );
	}

	/**
	 * Checks for profile update and triggers profile completed action.
	 *
	 * @param int $user_id user id.
	 */
	public function check_on_update( $user_id ) {
		$this->show_notice_and_redirect( $user_id );
	}

	/**
	 * On New Avatar Upload, add the user meta to reflect that user has uploaded an avatar
	 *
	 * @param int $user_id user whose avatar changed.
	 */
	public function log_uploaded( $user_id ) {
		bp_update_user_meta( $user_id, '_has_avatar', 1 );
		$this->mark_incomplete_profile( $user_id ); // always force recheck.
		$this->check_on_update( $user_id );
	}

	/**
	 * On New profile cover Upload, add the user meta to reflect that user has uploaded an cover
	 *
	 * @param int $user_id user whose avatar changed.
	 */
	public function log_cover_uploaded( $user_id ) {
		bp_update_user_meta( $user_id, '_has_profile_cover', 1 );
		$this->mark_incomplete_profile( $user_id ); // always force recheck.
		$this->check_on_update( $user_id );
	}

	/**
	 * On Delete Avatar, delete the user meta to reflect the change
	 *
	 * @param array $args see args array.
	 */
	public function log_deleted( $args ) {

		if ( $args['object'] != 'user' ) {
			return;
		}

		$user_id = empty( $args['item_id'] ) ? 0 : absint( $args['item_id'] );

		if ( ! $user_id ) {
			if ( bp_is_user() && ( bp_is_my_profile() || is_super_admin() ) ) {
				$user_id = bp_displayed_user_id();
			} else {
				$user_id = bp_loggedin_user_id();
			}
		}

		// we are sure it was user avatar delete
		// remove the log from user meta.
		bp_delete_user_meta( $user_id, '_has_avatar' );

		$this->mark_incomplete_profile( $user_id );
	}

	/**
	 * On New Avatar Upload, add the user meta to reflect that user has uploaded an avatar
	 *
	 * @param int $user_id user whose avatar changed.
	 */
	public function log_cover_deleted( $user_id ) {
		bp_delete_user_meta( $user_id, '_has_profile_cover' );

		$this->mark_incomplete_profile( $user_id );
	}

	/**
	 * Check's user's profile completion state.
	 */
	public function check_profile_completion_state() {

		if ( ! is_user_logged_in() ) {
			return;
		}

		$this->show_notice_and_redirect( bp_loggedin_user_id() );
	}

	/**
	 * Check for profile completion and add notice.
	 *
	 * @param int $user_id user id.
	 */
	private function show_notice_and_redirect( $user_id ) {

		if ( ! $this->has_incomplete_profile( $user_id ) ) {
			// the profile is complete.
			return;
		}

		if ( is_super_admin( $user_id ) ) {
			return; // No need to force super admin.
		}

		// Filter to skip profile completion check.
		if ( apply_filters( 'bp_force_profile_completion_skip_check', false ) ) {
			return;
		}

		$incomplete = true;
		// consider that we have the fields and avatar available by default.
		$has_fields = $has_photo = $has_cover = true;

		if ( bpprocn_is_required_fields_required() ) {
			$has_fields = $this->has_required_field_data( $user_id );
		}

		if ( bpprocn_is_profile_photo_required() ) {
			$has_photo = $this->has_uploaded_avatar( $user_id );
		}

		if ( bpprocn_is_profile_cover_required() ) {
			$has_cover = $this->has_uploaded_cover( $user_id );
		}

		$redirect_url = bp_core_get_user_domain( $user_id ) . bp_get_profile_slug();

		// this might have happened magically(most probably someone update profile by code).
		if ( $has_cover && $has_photo && $has_fields ) {
			$this->mark_complete_profile( $user_id );
			$incomplete = false;
			bp_core_add_message( __( 'Profile completed successfully.', 'bp-profile-completion' ), 'success' );
			do_action( 'bpprocn_user_profile_completed', $user_id );
		} elseif ( ! $has_fields ) {
			$this->notice = bpprocn_get_option( 'required_fields_incomplete_message' );
			$redirect_url = $redirect_url . '/edit/';
		} elseif ( ! $has_photo ) {
			$this->notice = bpprocn_get_option( 'profile_photo_incomplete_message' );
			$redirect_url = $redirect_url . '/change-avatar/';
		} elseif ( ! $has_cover ) {
			$this->notice = bpprocn_get_option( 'profile_cover_incomplete_message' );
			$redirect_url = $redirect_url . '/change-cover-image/';
		}

		if ( $incomplete ) {
			if ( bpprocn_show_profile_incomplete_message() && $this->notice ) {
				bp_core_add_message( $this->notice, 'error' );
			}

			if ( ! defined( 'DOING_AJAX' ) && bpprocn_is_profile_restriction_enabled() && ! bp_is_user_profile() ) {
				bp_core_redirect( apply_filters_ref_array( 'buddypress_profile_completion_redirect', array(
					$redirect_url,
					$has_fields,
					$has_photo,
					$has_cover,
				) ) );
			}
		}
	}

	/**
	 * Mark profile incomplete.
	 *
	 * @param int $user_id user id.
	 *
	 * @return bool
	 */
	public function has_incomplete_profile( $user_id ) {
		if ( get_user_meta( $user_id, '_has_complete_profile', true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Mark profile as incomplete.
	 *
	 * @param int $user_id user id.
	 *
	 * @return bool
	 */
	public function mark_incomplete_profile( $user_id ) {
		return delete_user_meta( $user_id, '_has_complete_profile' );
	}

	/**
	 * Check if user has incomplete profile field data.
	 *
	 * @param int $user_id user id.
	 *
	 * @return bool|int
	 */
	public function mark_complete_profile( $user_id ) {
		return update_user_meta( $user_id, '_has_complete_profile', 1 );
	}

	/**
	 * Check if user has uploaded avatar or not.
	 *
	 * @param int $user_id User id.
	 *
	 * @return bool|mixed
	 */
	public function has_uploaded_avatar( $user_id ) {
		// assuming that you are using force profile photo plugin that allows tracking of the user avatar.
		$has_avatar = bp_get_user_meta( $user_id, '_has_avatar', true );

		if ( ! $has_avatar ) {
			$has_avatar = bpprocn_user_has_avatar( $user_id );// fallback.
		}

		return apply_filters( 'buddypress_profile_completion_has_uploaded_avatar', $has_avatar, $user_id );
	}

	/**
	 * Check if user has uploaded cover or not.
	 *
	 * @param int $user_id User id.
	 *
	 * @return bool|mixed
	 */
	public function has_uploaded_cover( $user_id ) {
		$has_cover = bp_get_user_meta( $user_id, '_has_profile_cover', true );

		if ( ! $has_cover ) {
			$has_cover = bp_attachments_get_user_has_cover_image( $user_id );
		}

		return apply_filters( 'buddypress_profile_completion_has_uploaded_cover', $has_cover, $user_id );
	}

	/**
	 * Check if all required fields is complete.
	 *
	 * @param int $user_id User id.
	 *
	 * @return bool
	 */
	public function has_required_field_data( $user_id ) {
		global $wpdb;

		$table = buddypress()->profile->table_name_data;

		$has_fields_complete = get_user_meta( $user_id, '_has_required_field_data', true );

		if ( $has_fields_complete ) {
			return $has_fields_complete; // no need to test further.
		}

		$required_fields    = self::get_all_required_profile_fields( $user_id );

		// No required field, so profile should be considered complete.
		if ( empty( $required_fields ) ) {
			update_user_meta( $user_id, '_has_required_field_data', 1 );

			return true;
		}

		$fields_list = '(' . join( ',', $required_fields ) . ')';

		$query = $wpdb->prepare( "SELECT field_id, value  FROM {$table} WHERE user_id = %d AND field_id IN {$fields_list}", $user_id );

		$profile_entries = $wpdb->get_results( $query );

		$complete = false;
		// not all fields are set.
		// shortcut.
		if ( count( $profile_entries ) != count( $required_fields ) ) {
			// unset flag.
			delete_user_meta( $user_id, '_has_required_field_data' );
		} else {
			$complete = true;
			// it might be complete but is the value actually not empty?
			foreach ( $profile_entries as $profile_entry ) {
				$value = maybe_unserialize( $profile_entry->value );
				if ( empty( $value ) ) {
					$complete = false;
					break;
				}
			}
			if ( $complete ) {
				update_user_meta( $user_id, '_has_required_field_data', 1 );
			}
		}

		return apply_filters( 'buddypress_profile_completion_has_required_fields_complete', $complete, $user_id );
	}

	/**
	 * Get field details
	 *
	 * @param int $user_id user id.
	 *
	 * @return array|object|null
	 */
	public static function get_required_field_details( $user_id ) {
		global $wpdb;

		$table = buddypress()->profile->table_name_fields;

		$query = $wpdb->prepare( "SELECT * FROM {$table} WHERE is_required = %d", 1 );

		$fields = $wpdb->get_results( $query );

		$member_types = bp_get_member_type( $user_id, false );
		if ( empty( $member_types ) ) {
			$member_types = array( 'null' );
		}

		$member_types_fields = \BP_XProfile_Field::get_fields_for_member_type( $member_types );
		if ( ! empty( $member_types_fields ) ) {
			$required_fields = array();
			foreach ( $fields as $field ) {
				if ( isset( $member_types_fields[ $field->id ] ) ) {
					$required_fields[] = $field;

				}
			}

			return $required_fields;
		}

		return $fields;
	}

	/**
	 * Get all required field ids.
	 *
	 * @param int $user_id user id.
	 *
	 * @return array
	 */
	public static function get_all_required_profile_fields( $user_id ) {
		$fields = self::get_required_field_details( $user_id );

		if ( empty( $fields ) ) {
			return array();
		}

		return wp_list_pluck( $fields, 'id' );
	}

	/**
	 * Add compatibility for PMPro.
	 *
	 * @param bool $skip Skip.
	 *
	 * @return bool
	 */
	public function pmpro_compat($skip ) {
		global $pmpro_core_pages;

		if ( bp_is_user() ) {
			return $skip;
		}

		if ( ! function_exists( 'pmpro_is_checkout' ) || empty( $pmpro_core_pages ) || ! is_array( $pmpro_core_pages ) ) {
			return $skip;
		}

		// is it PMpro's page, allow.
		if ( pmpro_is_checkout() || is_page( $pmpro_core_pages ) ) {
			$skip = true;
		}

		return $skip;
	}

	/**
	 * On restored
	 *
	 * @param int    $item_id Item id.
	 * @param string $item_type Item type.
	 */
	public function on_restore( $item_id, $item_type ) {

		if ( 'user' != $item_type ) {
			return;
		}

		bp_update_user_meta( $item_id, '_has_avatar', 1 );
		$this->mark_incomplete_profile( $item_id ); // always force recheck.
	}
}
