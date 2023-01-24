<?php
if (!defined('ABSPATH')) die('No direct access.');

if (class_exists('MPSUM_Check_Theme_Install_Status')) return;

/**
 * Class MPSUM_Check_Plugin_Install_Status
 * Credit: https://github.com/WebDevStudios/WDS-Active-Plugin-Data
 */
class MPSUM_Check_Theme_Install_Status {

	/**
	 * List available themes
	 *
	 * @var array Available themes in /wp-content/themes/
	 *
	 * @since 8.0.1
	 */
	private $available_themes = array();
	
	/**
	 * List every theme for every site
	 *
	 * @var array Active themes list for every site
	 *
	 * @since 8.0.1
	 */
	private $all_sites_active_themes = array();

	/**
	 * List of sites.
	 *
	 * @var array List of sites
	 *
	 * @since 8.0.1
	 */
	private $sites = array();

	/**
	 * MPSUM_Check_Theme_Install_Status constructor.
	 */
	private function __construct() {
		if (is_multisite()) {
			$this->get_all_sites_active_themes();
		}
	}

	/**
	 * Returns a singleton instance
	 *
	 * @return MPSUM_Check_Theme_Install_Status object
	 */
	public static function get_instance() {
		static $instance = null;
		if (null === $instance) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Query for our available themes
	 *
	 * @since 8.0.1
	 */
	public function get_available_themes() {
		if (empty($this->available_themes)) {
			$this->available_themes = wp_get_themes();
		}
		return $this->available_themes;
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
	 * Get active themes list for every site and store to a transient
	 *
	 * @return array of all active site themes
	 *
	 * @since 8.0.1
	 */
	public function get_all_sites_active_themes() {
		$exists = get_site_transient('eum_all_sites_active_themes');
		if ($exists) {
			$this->all_sites_active_themes = $exists;
			return $this->all_sites_active_themes;
		}
		$sites = $this->get_sites();
		if (empty($sites)) {
			return;
		}
		foreach ($sites as $site) {
			$blog_id = absint($site->blog_id);
			$themes = wp_get_themes(array('blog_id' => $blog_id));
			$this->all_sites_active_themes[$blog_id] = array();
			if (!empty($themes)) {
				$this->all_sites_active_themes[$blog_id] = maybe_unserialize($themes);
			}
		}
		set_site_transient('eum_all_sites_active_themes', $this->all_sites_active_themes, 86400);
		return $this->all_sites_active_themes;
	}
}
