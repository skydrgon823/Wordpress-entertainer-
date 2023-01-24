<?php
if (!defined('ABSPATH')) die('No direct access.');

if (class_exists('MPSUM_Admin_Advanced_Preview')) return;

/**
 * Adds a preview of the premium tabs in the advanced tab.
 */
class MPSUM_Admin_Advanced_Preview {

	/**
	 * MPSUM_Admin_Advanced_Preview constructor.
	 */
	private function __construct() {
		add_action('eum_advanced_headings', array($this, 'headings'), 190);
		add_action('eum_advanced_settings', array($this, 'settings'), 190);
	}

	/**
	 * Returns a singleton instance
	 *
	 * @return MPSUM_Admin_Advanced_Preview object
	 */
	public static function get_instance() {
		static $instance = null;
		if (null === $instance) {
			$instance = new self();
		}
		return $instance;
	}

	private function get_items() {
		$items = array(
			'auto-backup' => array(
				'label' => __('Automatic backup', 'stops-core-theme-and-plugin-updates'),
				'desc' => __('Takes an automatic backup before your website is updated via an integration with UpdraftPlus', 'stops-core-theme-and-plugin-updates'),
				'icon' => 'backup'
			),
			'auto-update-scheduling' => array(
				'label' => __('Automatic update scheduling', 'stops-core-theme-and-plugin-updates'),
				'desc' => __('Choose the most convenient time of day to run your automatic updates.', 'stops-core-theme-and-plugin-updates'),
				'icon' => 'schedule'
			),
			'delay-auto-updates' => array(
				'label' => __('Delay automatic updates', 'stops-core-theme-and-plugin-updates'),
				'desc' => __('Delays the deployment of available automatic updates by a set time, to prevent installing short-lived (e.g. buggy) updates.', 'stops-core-theme-and-plugin-updates'),
				'icon' => 'hourglass_empty'
			),
			'anonymize' => array(
				'label' => __('Anonymize updates', 'stops-core-theme-and-plugin-updates'),
				'desc' => __('Controls what is sent to the WordPress.org API; stop sending unnecessary/personal/analytics data.', 'stops-core-theme-and-plugin-updates'),
				'icon' => 'cloud_off'
			),
			'webhook' => array(
				'label' => __('Webhook', 'stops-core-theme-and-plugin-updates'),
				'desc' => __('Integrates with third-party services to allow automatic updates to be triggered via cron or tools like Zapier.', 'stops-core-theme-and-plugin-updates'),
				'icon' => 'all_out'
			),
			'version-control-protection' => array(
				'label' => __('Version control protection', 'stops-core-theme-and-plugin-updates'),
				'desc' => __("Prevent updates to themes and plugins under version control.", 'stops-core-theme-and-plugin-updates'),
				'icon' => 'code'
			),
			'unmaintained-plugins' => array(
				'label' => __('Unmaintained plugins', 'stops-core-theme-and-plugin-updates'),
				'desc' => __("Check for unmaintained plugins in the WordPress plugin directory.", 'stops-core-theme-and-plugin-updates'),
				'icon' => 'hourglass_empty'
			),
			'plugin-safe-mode' => array(
				'label' => __('Plugin safe mode', 'stops-core-theme-and-plugin-updates'),
				'desc' => __("Prevent updates that are not compatible with your current WordPress version or your server's PHP version.", 'stops-core-theme-and-plugin-updates'),
				'icon' => 'security'
			),
			'export-import' => array(
				'label' => __('Export / import settings', 'stops-core-theme-and-plugin-updates'),
				'desc' => __('Export your settings from one site to another for quicker setup.', 'stops-core-theme-and-plugin-updates'),
				'icon' => 'save'
			),
			'dead-plugins' => array(
				'label' => __('Dead plugins', 'stops-core-theme-and-plugin-updates'),
				'desc' => __('Runs a check and alerts you about plugins that have been removed from the WordPress directory.', 'stops-core-theme-and-plugin-updates'),
				'icon' => 'check_circle'
			),
			'white-label' => array(
				'label' => __('White-label', 'stops-core-theme-and-plugin-updates'),
				'desc' => __('Customize what branding and notices your clients see in the plugin.', 'stops-core-theme-and-plugin-updates'),
				'icon' => 'label'
			),
		);

		$items = apply_filters('mpsum_premium_items_list', $items);

		return is_array($items) ? $items : array();
	}

	/**
	 * Outputs feature heading
	 */
	public function headings() {
		foreach ($this->get_items() as $key => $item) {
			printf('<div class="premium-only" data-menu_name="advanced-premium-preview_'.$key.'">%s <span class="eum-advanced-menu-text">%s</span><span class="eum-advanced-menu-premium-only">%s</span></div>', '<i class="material-icons">'.$item['icon'].'</i>', $item['label'], __('Premium', 'stops-core-theme-and-plugin-updates'));
		}
	}

	/**
	 * Outputs feature settings
	 */
	public function settings() {
		foreach ($this->get_items() as $key => $item) {
			Easy_Updates_Manager()->include_template('advanced-premium-preview.php', false, array('key' => $key, 'item' => $item));
		}
	}
}
