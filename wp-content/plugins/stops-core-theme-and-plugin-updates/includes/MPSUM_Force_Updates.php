<?php
if (!defined('ABSPATH')) die('No direct access.');

if (class_exists('MPSUM_Force_Updates')) return;

/**
 * Class MPSUM_Force_Updates
 *
 * Forces automatic updates to take place immediately
 */
class MPSUM_Force_Updates {

	/**
	 * MPSUM_Force_Updates constructor.
	 */
	private function __construct() {
		add_action('eum_advanced_headings', array($this, 'heading'), 97);
		add_action('eum_advanced_settings', array($this, 'settings'), 97);
	}

	/**
	 * Returns a singleton instance
	 *
	 * @return MPSUM_Force_Updates object
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
		printf('<div data-menu_name="force-updates">%s <span class="eum-advanced-menu-text">%s</span></div>', '<i class="material-icons">sync</i>', esc_html__('Force automatic updates', 'stops-core-theme-and-plugin-updates'));

	}

	/**
	 * Outputs feature settings
	 */
	public function settings() {
		Easy_Updates_Manager()->include_template('force-updates.php');
	}
}
