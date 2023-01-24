<?php
if (!defined('ABSPATH')) die('No direct access.');

if (class_exists('MPSUM_Exclude_Users')) return;

/**
 * Class MPSUM_Exclude_Users
 *
 * Handles user exclusion for plugin settings
 */
class MPSUM_Exclude_Users {

	/**
	 * MPSUM_Exclude_Users constructor.
	 */
	private function __construct() {
		add_action('eum_advanced_headings', array($this, 'heading'));
		add_action('eum_advanced_settings', array($this, 'settings'));
	}

	/**
	 * Returns a singleton instance
	 *
	 * @return MPSUM_Exclude_Users object
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
	 */
	public function heading() {
		printf('<div data-menu_name="exclude-users" class="active-menu">%s <span class="eum-advanced-menu-text">%s</span></div>', '<i class="material-icons">stop_screen_share</i>', esc_html__('Exclude users', 'stops-core-theme-and-plugin-updates'));
	}

	/**
	 * Outputs feature settings
	 */
	public function settings() {
		Easy_Updates_Manager()->include_template('exclude-users.php');
	}
}
