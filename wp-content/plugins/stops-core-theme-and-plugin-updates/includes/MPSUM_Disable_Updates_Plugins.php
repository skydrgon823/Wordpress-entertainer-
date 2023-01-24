<?php
/**
 * Disables all WordPress plugin updates.
 *
 * Disables all WordPress plugin updates.
 *
 * @package WordPress
 * @since   5.0.0
 */

 /**
  * Disable plugin updates
  * Credit - From https://wordpress.org/plugins/disable-wordpress-updates/
  */
class MPSUM_Disable_Updates_Plugins {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('admin_init', array($this, 'admin_init'));
		
		/*
		 * Disable Plugin Updates
		 * 2.8 to 3.0
		 */
		add_action('pre_transient_update_plugins', array($this, 'last_checked_now'), 10, 2);

		/*
		 * 3.0
		 */
		add_filter('pre_site_transient_update_plugins', array($this, 'last_checked_now'), 10, 2);
		
		/*
		 * Disable All Automatic Updates (3.7+)
		 */
		add_filter('auto_update_plugin', '__return_false');
		
	} //end constructor
	
	/**
	 * Initialize and load the plugin stuff
	 *
	 * @since  1.3
	 * @author scripts@schloebe.de
	 */
	function admin_init() {
		/*
		 * Disable Plugin Updates
		 * 2.8 to 3.0
		 */
		remove_action('load-plugins.php', 'wp_update_plugins');
		remove_action('load-update.php', 'wp_update_plugins');
		remove_action('admin_init', '_maybe_update_plugins');
		remove_action('wp_update_plugins', 'wp_update_plugins');
		wp_clear_scheduled_hook('wp_update_plugins');
		
		/*
		 * 3.0
		 */
		remove_action('load-update-core.php', 'wp_update_plugins');
		wp_clear_scheduled_hook('wp_update_plugins');

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
			delete_site_transient('eum_plugins_checked');
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
		$checked = get_site_transient('eum_plugins_checked');
		if ($checked) return $checked;
		remove_action('pre_transient_update_plugins', array($this, 'last_checked_now'), 10, 2);
		remove_filter('pre_site_transient_update_plugins', array($this, 'last_checked_now'), 10, 2);
		delete_site_transient($key);
		wp_update_plugins();
		$option = get_site_transient($key);
		include ABSPATH . WPINC . '/version.php';
		$current = new stdClass;
		$current->updates = array();
		$current->version_checked = $wp_version;// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable -- $wp_version is being populated via the version.php include
		$current->last_checked = time();
		if (isset($option->translations)) {
			$current->translations = $option->translations;
		}
		add_action('pre_transient_update_plugins', array($this, 'last_checked_now'), 10, 2);
		add_filter('pre_site_transient_update_plugins', array($this, 'last_checked_now'), 10, 2);
		set_site_transient('eum_plugins_checked', $current, 6 * 60 * 60);
		return $current;
	}
}
