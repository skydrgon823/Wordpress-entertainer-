<?php
if (!defined('ABSPATH')) die('No direct access.');

if (class_exists('MPSUM_Advanced_Premium')) return;

/**
 * Class MPSUM_Advanced_Premium
 *
 * Add an advanced tab for going premium
 */
class MPSUM_Advanced_Premium {

	/**
	 * MPSUM_Advanced_Premium constructor.
	 */
	public function __construct() {
		add_action('mpsum_admin_tab_premium', array($this, 'settings'));
	}

	/**
	 * Returns a singleton instance
	 *
	 * @return MPSUM_Advanced_Premium object
	 */
	public static function get_instance() {
		static $instance = null;
		if (null === $instance) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Outputs feature settings
	 */
	public function settings() {
		Easy_Updates_Manager()->include_template('advanced-premium.php');
	}
}
