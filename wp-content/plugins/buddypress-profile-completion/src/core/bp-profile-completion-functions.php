<?php
/**
 * Core functions file
 *
 * @package BP_Profile_Completion
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get setting
 *
 * @param string $setting Setting name.
 *
 * @return mixed
 */
function bpprocn_get_option( $setting ) {

	$settings = get_option( 'bpprocn_settings', bpprocn_get_default_options() );

	return isset( $settings[ $setting ] ) ? $settings[ $setting ] : null;
}

/**
 * Get default options.
 *
 * @return array
 */
function bpprocn_get_default_options() {

	$defaults = array(
		'required_criteria'                  => array(
			'all_req_fields'    => 'all_req_fields',
			'req_profile_photo' => 'req_profile_photo',
			'req_profile_cover' => 'req_profile_cover',
		),
		'restrict_access_to_profile_only'    => 1,
		'show_profile_incomplete_message'    => 1,
		'required_fields_incomplete_message' => __( 'Please fill all required profile fields.', 'buddypress-profile-completion' ),
		'profile_photo_incomplete_message'   => __( 'Please upload your profile photo!', 'buddypress-profile-completion' ),
		'profile_cover_incomplete_message'   => __( 'Please upload your profile cover!', 'buddypress-profile-completion' ),
		'enable_whitelisted_roles'           => 0,
		'whitelisted_roles'                  => array( 'administrator' => 'administrator' ),
		'enable_whitelisted_member_types'    => 0,
		'whitelisted_member_types'           => 0,
	);

	return $defaults;
}

/**
 * Check if all required fields is mandatory or not for profile completion.
 *
 * @return bool
 */
function bpprocn_is_required_fields_required() {
	$required_criteria = bpprocn_get_option( 'required_criteria' );

	return in_array( 'all_req_fields', (array) $required_criteria, true );
}

/**
 * Check if profile photo is mandatory or not for profile completion.
 *
 * @return bool
 */
function bpprocn_is_profile_photo_required() {
	// Return false if member avatar upload in not enabled.
	if ( bp_disable_avatar_uploads() ) {
		return false;
	}

	$required_criteria = bpprocn_get_option( 'required_criteria' );

	return in_array( 'req_profile_photo', (array) $required_criteria, true );
}

/**
 * Check if profile cover is mandatory or not for profile completion.
 *
 * @return bool
 */
function bpprocn_is_profile_cover_required() {
	// Return false if member cover upload in not enabled.
	if ( bp_disable_cover_image_uploads() ) {
		return false;
	}

	$required_criteria = bpprocn_get_option( 'required_criteria' );

	return in_array( 'req_profile_cover', (array) $required_criteria, true );
}

/**
 * Check if restrict access to profile only enabled or not.
 *
 * @return bool
 */
function bpprocn_is_profile_restriction_enabled() {
	return bpprocn_get_option( 'restrict_access_to_profile_only' );
}

/**
 * Check weather enable show profile message or not.
 *
 * @return bool
 */
function bpprocn_show_profile_incomplete_message() {
	return bpprocn_get_option( 'show_profile_incomplete_message' );
}

/**
 * Check if user has incomplete profile?
 *
 * @param int $user_id user id.
 *
 * @return bool
 */
function bpprocn_has_incomplete_profile( $user_id ) {

	if ( get_user_meta( $user_id, '_has_complete_profile', true ) ) {
		return false;
	}

	return true;
}

/**
 * Get roles
 *
 * @return array
 */
function bpprocn_get_roles() {
	$roles = get_editable_roles();

	foreach ( $roles as $role => $detail ) {
		$user_roles[ $role ] = $detail['name'];
	}

	return $user_roles;
}

/**
 * Get roles
 *
 * @return array
 */
function bpprocn_get_member_types() {
	$registered_member_types = bp_get_member_types( array(), 'object' );

	$member_types = array();
	foreach ( $registered_member_types as $type => $member_type_obj ) {
		$member_types[ $type ] = $member_type_obj->labels['singular_name'];
	}

	return $member_types;
}

/**
 * Check if user has whitelisted roles or not.
 *
 * @param int $user_id User id.
 *
 * @return bool
 */
function bpprocn_user_has_whitelisted_roles( $user_id ) {
	$user = get_user_by( 'ID', $user_id );

	$whitelisted_roles = bpprocn_get_option( 'whitelisted_roles' );
	$whitelisted_roles = $whitelisted_roles ? $whitelisted_roles : array();

	$has_roles = false;
	if ( $user && array_intersect( $user->roles, $whitelisted_roles ) ) {
		$has_roles = true;
	}

	return $has_roles;
}

/**
 * Check if user has whitelisted member types or not.
 *
 * @param int $user_id User id.
 *
 * @return bool
 */
function bpprocn_user_has_whitelisted_member_types( $user_id ) {
	$member_types = bp_get_member_type( $user_id, false );

	$whitelisted_member_types = bpprocn_get_option( 'whitelisted_member_types' );
	$whitelisted_member_types = $whitelisted_member_types ? $whitelisted_member_types : array();

	$has_member_types = false;
	if ( $member_types && array_intersect( $member_types, $whitelisted_member_types ) ) {
		$has_member_types = true;
	}

	return $has_member_types;
}

/**
 * Check if user is whitelisted or not.
 *
 * @param int $user_id User id.
 *
 * @return bool
 */
function bpprocn_is_user_whitelisted( $user_id ) {
	$is_whitelisted = false;

	if ( bpprocn_get_option( 'enable_whitelisted_roles' ) ) {
		$is_whitelisted = bpprocn_user_has_whitelisted_roles( $user_id );
	}

	if ( ! $is_whitelisted && bpprocn_get_option( 'enable_whitelisted_member_types' ) ) {
		$is_whitelisted = bpprocn_user_has_whitelisted_member_types( $user_id );
	}

	return apply_filters( 'bpprocn_user_whitelisted', $is_whitelisted, $user_id );
}

/**
 * Fallback to user has avatar
 *
 * @param int $user_id User id.
 *
 * @return bool
 */
function bpprocn_user_has_avatar( $user_id ) {
	$has_avatar = false;

	if ( function_exists( 'leira_letter_avatar' ) && leira_letter_avatar()->is_active() ) {
		$compatibility = leira_letter_avatar()->get_loader()->get( 'compatibility' );

		remove_filter( 'bp_core_avatar_default', array( $compatibility, 'bp_core_avatar_default' ) );

		$has_avatar = bp_get_user_has_avatar( $user_id );

		add_filter( 'bp_core_avatar_default', array( $compatibility, 'bp_core_avatar_default' ), 10, 2 );
	} else {
		$has_avatar = bp_get_user_has_avatar( $user_id );
	}

	return $has_avatar;
}