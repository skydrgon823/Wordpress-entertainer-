<?php
/**
 * Disables all core WordPress updates.
 *
 * @package WordPress
 * @since 5.0.0
 */

/**
 * Disable WordPress core updates
 * Credit - From https://wordpress.org/plugins/disable-wordpress-updates/
 */
class MPSUM_Disable_Updates_WordPress {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('admin_init', array($this, 'admin_init'));


		/*
		 * Disable Core Updates
		 * 2.8 to 3.0
		 */
		add_filter('pre_transient_update_core', array($this, 'last_checked_now'), 10, 2);

		/*
		 * 3.0
		 */
		add_filter('pre_site_transient_update_core', array($this, 'last_checked_now'), 10, 2);


		/*
		 * Disable All Automatic Updates
		 * 3.7+
		 *
		 * @author	sLa NGjI's @ slangji.wordpress.com
		 */
		add_filter('allow_minor_auto_core_updates', '__return_false');
		add_filter('allow_major_auto_core_updates', '__return_false');
		add_filter('allow_dev_auto_core_updates', '__return_false');
		add_filter('auto_update_core', '__return_false');
		add_filter('wp_auto_update_core', '__return_false');
		add_filter('auto_core_update_send_email', '__return_false');
		add_filter('send_core_update_notification_email', '__return_false');
		add_filter('automatic_updates_send_debug_email', '__return_false');

	} //end constructor

	/**
	 * Initialize and load the plugin stuff
	 *
	 * @since 		1.3
	 * @author 		scripts@schloebe.de
	 */
	function admin_init() {
		/*
		 * Disable Core Updates
		 * 2.8 to 3.0
		 */
		remove_action('wp_version_check', 'wp_version_check');
		remove_action('admin_init', '_maybe_update_core');
		wp_clear_scheduled_hook('wp_version_check');


		/*
		 * 3.0
		 */
		wp_clear_scheduled_hook('wp_version_check');


		/*
		 * 3.7+
		 */
		remove_action('wp_maybe_auto_update', 'wp_maybe_auto_update');
		remove_action('admin_init', 'wp_maybe_auto_update');
		remove_action('admin_init', 'wp_auto_update_core');
		wp_clear_scheduled_hook('wp_maybe_auto_update');

		add_action('upgrader_process_complete', array($this, 'maybe_clear_transient'), 10, 2);
	}

	/**
	 * Fires when the auto updater is complete
	 *
	 * @param WP_Upgrader $wp_upgrader WP Upgrder Instance
	 * @param array       $update_type Type of upgrade it's doing
	 */
	public function maybe_clear_transient($wp_upgrader, $update_type) {
		if (isset($update_type['type']) && 'translation' === $update_type['type']) {
			delete_site_transient('eum_core_checked');
		}
	}

	/**
	 * Last checked core updates
	 *
	 * @param string $transient Specify transients
	 * @param string $key       Name of the transient
	 * @return object
	 */
	public function last_checked_now($transient, $key) {
		$checked = get_site_transient('eum_core_checked');
		if ($checked) return $checked;
		remove_action('pre_transient_update_core', array($this, 'last_checked_now'), 10, 2);
		remove_filter('pre_site_transient_update_core', array($this, 'last_checked_now'), 10, 2);
		delete_site_transient($key);
		wp_version_check();
		$option = get_site_transient($key);
		include ABSPATH . WPINC . '/version.php';
		$current = new stdClass;
		$current->updates = array();
		$current->version_checked = $wp_version;// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable -- $wp_version is being populated via the version.php include
		$current->last_checked = time();
		if (isset($option->translations)) {
			$current->translations = $option->translations;
		}
		add_action('pre_transient_update_core', array($this, 'last_checked_now'), 10, 2);
		add_filter('pre_site_transient_update_core', array($this, 'last_checked_now'), 10, 2);
		set_site_transient('eum_core_checked', $current, 6 * 60 * 60);
		return $current;
	}
}
