<?php
if (!defined('ABSPATH')) die('No direct access.');

if (class_exists('MPSUM_Check_Plugin_Install_Status')) return;

/**
 * Class MPSUM_Check_Plugin_Install_Status
 * Credit: https://github.com/WebDevStudios/WDS-Active-Plugin-Data
 */
class MPSUM_Check_Plugin_Install_Status {

	/**
	 * List available plugins
	 *
	 * @var array Available plugins in /wp-content/plugins/
	 *
	 * @since 8.0.1
	 */
	private $available_plugins = array();
	
	/**
	 * List every plugin for every site
	 *
	 * @var array Active plugins list for every site
	 *
	 * @since 8.0.1
	 */
	private $all_sites_active_plugins = array();

	/**
	 * List of sites.
	 *
	 * @var array List of sites
	 *
	 * @since 8.0.1
	 */
	private $sites = array();

	/**
	 * MPSUM_Check_Plugin_Install_Status constructor.
	 */
	private function __construct() {
		if (is_multisite()) {
			$this->get_all_sites_active_plugins();
		}
	}

	/**
	 * Returns a singleton instance
	 *
	 * @return MPSUM_Check_Plugin_Install_Status object
	 */
	public static function get_instance() {
		static $instance = null;
		if (null === $instance) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Query for our available plugins
	 *
	 * @since 8.0.1
	 */
	public function get_available_plugins() {
		if (empty($this->available_plugins)) {
			$this->available_plugins = get_plugins();
		}
		return $this->available_plugins;
	}

	/**
	 * Query for our sites
	 *
	 * @since 8.0.1
	 */
	public function get_sites() {
		$blog_count = get_blog_count();
		if (empty($this->sites)) {
			$this->sites = get_sites(array('deleted' => false, 'number' => $blog_count));
		}
		return $this->sites;
	}

	/**
	 * Get active plugins list for every site and store to a transient
	 *
	 * @return array of all active site plugins
	 *
	 * @since 8.0.1
	 */
	public function get_all_sites_active_plugins() {
		if (!empty($this->all_sites_active_plugins)) {
			return $this->all_sites_active_plugins;
		}
		$exists = get_site_transient('eum_all_sites_active_plugins');
		if ($exists) {
			$this->all_sites_active_plugins = $exists;
			return $this->all_sites_active_plugins;
		}
		$sites = $this->get_sites();
		if (empty($sites)) {
			return;
		}
		foreach ($sites as $site) {
			$blog_id = absint($site->blog_id);
			switch_to_blog($blog_id);
			$option = get_option('active_plugins');
			$this->all_sites_active_plugins[$blog_id] = array();
			$this->all_sites_active_plugins[$blog_id] = maybe_unserialize($option);
		}
		restore_current_blog();
		set_site_transient('eum_all_sites_active_plugins', $this->all_sites_active_plugins, 86400);
		return $this->all_sites_active_plugins;
	}

	/**
	 * Determines if a plugin is active on any site in the network
	 *
	 * @param string $plugin_file plugin to check
	 *
	 * @return bool
	 */
	public function is_plugin_active_on_any_site($plugin_file) {
		$active = false;
		foreach ($this->get_all_sites_active_plugins() as $plugins) {
			if (in_array($plugin_file, $plugins)) {
				$active = true;
			}
		}
		return $active;
	}
}
