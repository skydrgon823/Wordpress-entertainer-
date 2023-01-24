<?php
if (!defined('ABSPATH')) die('No direct access.');

if (class_exists('MPSUM_Reset_Options')) return;

/**
 * Class MPSUM_Reset_Options
 *
 * Resets plugin options as if freshly installed
 */
class MPSUM_Reset_Options {

	/**
	 * MPSUM_Reset_Options constructor.
	 */
	private function __construct() {
		add_action('eum_advanced_headings', array($this, 'heading'), 99);
		add_action('eum_advanced_settings', array($this, 'settings'), 99);
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
	 */
	public function heading() {
		printf('<div data-menu_name="reset-options">%s <span class="eum-advanced-menu-text">%s</span></div>', '<i class="material-icons">delete_outline</i>', esc_html__('Reset options', 'stops-core-theme-and-plugin-updates'));
	}

	/**
	 * Outputs feature settings
	 */
	public function settings() {
		Easy_Updates_Manager()->include_template('reset-options.php');
	}
}
