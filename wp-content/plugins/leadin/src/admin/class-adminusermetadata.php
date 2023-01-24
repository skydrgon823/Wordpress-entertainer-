<?php

namespace Leadin\admin;

use Leadin\wp\User;

/**
 * Handles metadata for users in the admin area.
 */
class AdminUserMetaData {

	const SKIP_REVIEW             = 'leadin_skip_review';
	const REVIEW_BANNER_LAST_CALL = 'leadin_review_banner_last_call';
	const HAS_MIN_CONTACTS        = 'leadin_has_min_contacts';
	const TRACK_CONSENT           = 'leadin_track_consent';

	/**
	 * Set SKIP_REVIEW meta data for a user.
	 *
	 * @param int $skip_epoch Epoch time of when the review was skipped.
	 */
	public static function set_skip_review( $skip_epoch ) {
		return User::set_metadata( self::SKIP_REVIEW, $skip_epoch );
	}

	/**
	 * Get SKIP_REVIEW meta data for a user.
	 */
	public static function get_skip_review() {
		return User::get_metadata( self::SKIP_REVIEW );
	}

	/**
	 * Set REVIEW_BANNER_LAST_CALL meta data for a user.
	 *
	 * @param int $skip_epoch Epoch time of when the review was skipped.
	 */
	public static function set_review_banner_last_call( $skip_epoch ) {
		return User::set_metadata( self::REVIEW_BANNER_LAST_CALL, $skip_epoch );
	}

	/**
	 * Get REVIEW_BANNER_LAST_CALL meta data for a user.
	 */
	public static function get_review_banner_last_call() {
		return User::get_metadata( self::REVIEW_BANNER_LAST_CALL );
	}

	/**
	 * Set HAS_MIN_CONTACTS meta data for a user.
	 *
	 * @param bool $value Boolean to see if contacts already been fetched and fulfill threshold.
	 */
	public static function set_has_min_contacts( $value ) {
		return User::set_metadata( self::HAS_MIN_CONTACTS, $value );
	}

	/**
	 * Get HAS_MIN_CONTACTS meta data for a user.
	 */
	public static function get_has_min_contacts() {
		return User::get_metadata( self::HAS_MIN_CONTACTS );
	}

	/**
	 * Set TRACK_CONSENT meta data for a user.
	 *
	 * @param bool $consent User consent to anonymous tracking.
	 */
	public static function set_track_consent( $consent ) {
		return User::set_metadata( self::TRACK_CONSENT, $consent );
	}

	/**
	 * Get TRACK_CONSENT meta data for a user.
	 */
	public static function get_track_consent() {
		return User::get_metadata( self::TRACK_CONSENT );
	}

}
