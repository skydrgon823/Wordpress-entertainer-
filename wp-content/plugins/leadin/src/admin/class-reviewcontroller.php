<?php

namespace Leadin\admin;

use Leadin\rest\HubSpotApiClient;
use Leadin\options\LeadinOptions;
use Leadin\admin\AdminUserMetaData;

/**
 * Class responsible for controlling if review banner should show.
 */
class ReviewController {

	const CONTACTS_CREATED_SINCE_ACTIVATION = 5;
	const REVIEW_BANNER_INTRO_PERIOD        = 15;
	const DAYS_SINCE_LAST_FETCH             = 1;

	/**
	 * Checks if user has enough contacts created since plugin activation.
	 */
	public static function has_contacts_created_since_activation() {
		if ( self::has_min_contacts() ) {
			return true;
		} elseif ( ! self::should_fetch_contacts() ) {
			return false;
		}
		try {
			AdminUserMetaData::set_review_banner_last_call( time() );
			$contacts         = HubSpotApiClient::get_contacts_from_timestamp( LeadinOptions::get( 'activation_time' ) );
			$has_min_contacts = count( $contacts->results ) >= self::CONTACTS_CREATED_SINCE_ACTIVATION;
			AdminUserMetaData::set_has_min_contacts( $has_min_contacts );
			return $has_min_contacts;
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/**
	 * Check to see if current time is after introductary period.
	 */
	public static function is_after_introductary_period() {
		$activation_time = new \DateTime();
		$activation_time->setTimestamp( LeadinOptions::get( 'activation_time' ) );
		$diff = $activation_time->diff( new \DateTime() );
		return $diff->days >= self::REVIEW_BANNER_INTRO_PERIOD;
	}

	/**
	 * Check SKIP_REVIEW meta data for a user.
	 */
	public static function is_reviewed_or_skipped() {
		return ! empty( AdminUserMetaData::get_skip_review() );
	}

	/**
	 * Check if contacts have been fetched at the current day.
	 */
	public static function should_fetch_contacts() {
		$last_call_ts = AdminUserMetaData::get_review_banner_last_call();
		if ( ! empty( $last_call_ts ) ) {
			return true;
		}
		$last_call_date = new \DateTime();
		$last_call_date->setTimestamp( strtotime( $last_call_ts ) );
		$diff = $last_call_date->diff( new \DateTime() );
		return $diff->days >= self::DAYS_SINCE_LAST_FETCH;
	}

	/**
	 * Check if contacts minimun have already been fulfilled .
	 */
	public static function has_min_contacts() {
		return AdminUserMetaData::get_has_min_contacts();
	}

}
