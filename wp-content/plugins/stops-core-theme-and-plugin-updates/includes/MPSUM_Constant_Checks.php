<?php
if (!defined('ABSPATH')) die('No direct access.');

if (class_exists('MPSUM_CONSTANT_CHECKS')) return;

/**
 * Class MPSUM_CONSTANT_CHECKS
 *
 * Checks for wp-config constants that may disable the plugin.
 */
class MPSUM_CONSTANT_CHECKS {

	/**
	 * MPSUM_Reset_Options constructor.
	 */
	private function __construct() {
	}

	/**
	 * Returns a singleton instance
	 *
	 * @return MPSUM_Reset_Options object
	 */
	public static function get_instance() {
		static $instance = null;
		if (null === $instance) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Outputs feature heading
	 *
	 * @return bool true if there are constants that disable EUM, false if not.
	 */
	public function is_config_options_disabled() {
		if (defined('AUTOMATIC_UPDATER_DISABLED') && true === AUTOMATIC_UPDATER_DISABLED) {
			return true;
		}
		if (defined('WP_AUTO_UPDATE_CORE') && (false === WP_AUTO_UPDATE_CORE || 'minor' === WP_AUTO_UPDATE_CORE)) {
			return true;
		}
		return false;
	}
}
