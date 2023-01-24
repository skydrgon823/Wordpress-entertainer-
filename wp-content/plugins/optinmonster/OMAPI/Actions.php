<?php
/**
 * Actions class.
 *
 * @since 1.0.0
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Actions class.
 *
 * @since 1.0.0
 */
class OMAPI_Actions {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();

		// Add validation messages.
		add_action( 'admin_init', array( $this, 'maybe_fetch_missing_data' ), 99 );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.0.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * When the plugin is first installed
	 * Or Migrated from a pre-1.8.0 version
	 * We need to fetch some additional data
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function maybe_fetch_missing_data() {
		$creds   = $this->base->get_api_credentials();
		$option  = $this->base->get_option();
		$changed = false;

		// If we don't have an API Key yet, we can't fetch anything else.
		if ( empty( $creds['apikey'] ) && empty( $creds['user'] ) && empty( $creds['key'] ) ) {
			return;
		}

		// Fetch the userId and accountId, if we don't have them.
		if (
			empty( $option['userId'] )
			|| empty( $option['accountId'] )
			|| empty( $option['currentLevel'] )
			|| empty( $option['plan'] )
			|| empty( $creds['apikey'] )
		) {
			$result = OMAPI_Api::fetch_me( $option, $creds );

			if ( ! is_wp_error( $result ) ) {
				$changed = true;
				$option  = $result;
			}
		}

		// Fetch the SiteIds for this site, if we don't have them.
		if ( empty( $option['siteIds'] ) || empty( $option['siteId'] ) || $this->site_ids_are_numeric( $option['siteIds'] ) ) {

			$result = $this->base->sites->fetch();
			if ( ! is_wp_error( $result ) ) {
				$option  = array_merge( $option, $result );
				$changed = true;
			}
		}

		// Only update the option if we've changed something.
		if ( $changed ) {
			update_option( 'optin_monster_api', $option );
		}

	}


	/**
	 * In one version of the Plugin, we fetched the numeric SiteIds,
	 * But we actually needed the alphanumeric SiteIds.
	 *
	 * So we use this check to determine if we need to re-fetch Site Ids.
	 *
	 * @param array $site_ids Site ids to convert.
	 * @return bool True if the ids are numeric.
	 */
	protected function site_ids_are_numeric( $site_ids ) {
		foreach ( $site_ids as $id ) {
			if ( ! ctype_digit( (string) $id ) ) {
				return false;
			}
		}

		return true;
	}
}
