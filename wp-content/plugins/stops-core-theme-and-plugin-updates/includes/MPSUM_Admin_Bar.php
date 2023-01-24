<?php
if (!defined('ABSPATH')) die('No direct access.');

if (class_exists('MPSUM_Admin_Bar')) return;

/**
 * Class MPSUM_Admin_Bar
 *
 * Forces automatic updates to take place immediately
 */
class MPSUM_Admin_Bar {

	/**
	 * MPSUM_Admin_Bar constructor.
	 */
	private function __construct() {
		add_action('eum_advanced_headings', array($this, 'heading'), 97);
		add_action('eum_advanced_settings', array($this, 'settings'), 97);
		add_filter('eum_i18n', array($this, 'disable_admin_bar_i18n'));
	}

	/**
	 * Returns a singleton instance
	 *
	 * @return MPSUM_Admin_Bar object
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
	 * @param array $i18n Internalization array
	 *
	 * @return array Updated internalization array
	 */
	function disable_admin_bar_i18n($i18n) {
		$i18n['disable_admin_bar'] = __('Disable', 'stops-core-theme-and-plugin-updates');
		$i18n['enable_admin_bar'] = __('Enable', 'stops-core-theme-and-plugin-updates');
		return $i18n;
	}

	/**
	 * Outputs feature heading
	 */
	public function heading() {
		printf('<div data-menu_name="admin-bar">%s <span class="eum-advanced-menu-text">%s</span></div>', '<i class="material-icons">menu</i>', esc_html__('Admin bar menu display', 'stops-core-theme-and-plugin-updates'));

	}

	/**
	 * Outputs feature settings
	 */
	public function settings() {
		Easy_Updates_Manager()->include_template('admin-bar.php');
	}
}
