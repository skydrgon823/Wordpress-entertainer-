<?php

namespace Leadin\wp;

/**
 * Static function that wraps the WordPress user functions.
 */
class User {
	/**
	 * Return the first role of the current user. If unauthenticated, return 'visitor'.
	 */
	public static function get_role() {
		global $current_user;

		if ( is_user_logged_in() ) {
			$user_roles = $current_user->roles;
			$user_role  = array_shift( $user_roles );
		} else {
			$user_role = 'visitor';
		}

		return $user_role;
	}

	/**
	 * Return true if the current user has the `manage_options` capability.
	 */
	public static function is_admin() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Set metadata for current user
	 *
	 * @param String $key metadata key to store data in.
	 * @param String $data metadata value to store.
	 */
	public static function set_metadata( $key, $data ) {
		update_user_meta( get_current_user_id(), $key, $data );
	}

	/**
	 * Fetch metadata stored for this user by key
	 *
	 * @param String $key metadata to retrieve by the key.
	 */
	public static function get_metadata( $key ) {
		return get_user_meta( get_current_user_id(), $key, true );
	}

	/**
	 * Delete metadata associated with this user
	 *
	 * @param String $key key to delete metadata for.
	 */
	public static function delete_metadata( $key ) {
		delete_user_meta( get_current_user_id(), $key );
	}
}
