<?php
if (!defined('ABSPATH')) die('No direct access.');
/**
 * Easy Updates Manager utility class.
 */
class MPSUM_Utils {

	/**
	 * MPSUM_Utils constructor
	 */
	private function __construct() {

	}

	/**
	 * Returns instance of singleton pattern
	 *
	 * @return MPSUM_Utils
	 */
	public static function get_instance() {
		static $instance = null;
		if (null === $instance) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Returns whether the URL is a WordPress API call
	 *
	 * @param string $url The URL to be checked
	 *
	 * @return true if WP API, false if not
	 */
	public static function is_wp_api($url) {
		$parsed_url = parse_url($url);
		if (isset($parsed_url['host']) && 'api.wordpress.org' === strtolower($parsed_url['host'])) return true;
		return false;
	}

	/**
	 * Validates email addresses and returns an error if not valid
	 *
	 * @since 9.0.0
	 *
	 * @param string $email_addresses (can be comma separated)
	 *
	 * @return array Return an 'errors' key (boolean) and an 'original_emails' key (string) with original passed email addresses. 'emails' key (array) contains validated email addresses.
	 */
	public static function validate_emails($email_addresses) {
		$emails       = explode(',', $email_addresses);
		$email_errors = false;
		foreach ($emails as &$email) {
			$email = trim($email);
			if (!is_email($email)) {

				// Email error. Get out.
				$email_errors = true;
				break;
			}
		}
		return array(
			'errors'          => $email_errors,
			'original_emails' => $email_addresses,
			'emails'          => $emails,
		);
	}

	/**
	 * This function checks whether a specific plugin is installed, and returns information about it
	 *
	 * @since 8.0.1
	 *
	 * @param  string $slug Specify plugin slug
	 *
	 * @return array        Returns an array of details such as if installed, the name of the plugin and if it is active.
	 */
	public function is_installed($slug) {

		// Needed to have the 'get_plugins()' function
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');

		// Gets all plugins available
		$get_plugins = get_plugins();

		$active_plugins = $this->get_active_plugins();
		$plugin_info = array();
		$plugin_info['installed'] = false;
		$plugin_info['active']    = false;

		// Loops around each plugin available.
		foreach ($get_plugins as $key => $value) {
			// If the plugin name matches that of the specified name, it will gather details.
			if ($value['TextDomain'] != $slug) {
				continue;
			}
			$plugin_info['installed'] = true;
			$plugin_info['name']      = $key;
			$plugin_info['version']   = $value['Version'];
			if (in_array($key, $active_plugins)) {
				$plugin_info['active'] = true;
			}
			break;
		}

		return $plugin_info;
	}

	/**
	 * Gets an array of plugins active on either the current site, or site-wide
	 *
	 * @since 8.0.1
	 *
	 * @return array - a list of plugin paths (relative to the plugin directory)
	 */
	public function get_active_plugins() {

		// Gets all active plugins on the current site
		$active_plugins = get_option('active_plugins');

		if (is_multisite()) {
			$network_active_plugins = get_site_option('active_sitewide_plugins');
			if (!empty($network_active_plugins)) {
				$network_active_plugins = array_keys($network_active_plugins);
				$active_plugins = array_merge($active_plugins, $network_active_plugins);
			}
		}

		return $active_plugins;
	}

	/**
	 * Gets an array of plugins active on either the current site or passed blog id
	 *
	 * @since 8.0.1
	 *
	 * @param $int $blog_id Blog ID of site if on multisite
	 *
	 * @return array - a list of plugin paths (relative to the plugin directory)
	 */
	public function get_single_site_active_plugins($blog_id = 1) {
		if (is_multisite()) switch_to_blog($blog_id);
		// Gets all active plugins on the current site
		$active_plugins = get_option('active_plugins', array());
		if (is_multisite()) restore_current_blog();
		return $active_plugins;
	}

	/**
	 * Gets an array of plugins active for the network
	 *
	 * @since 8.0.1
	 *
	 * @return array - a list of plugin paths (relative to the plugin directory)
	 */
	public function get_network_active_plugins() {
		$network_active_plugins = get_site_option('active_sitewide_plugins', array());
		$network_active_plugins = array_keys($network_active_plugins);
		return $network_active_plugins;
	}

	/**
	 * Checks to see if auomatic updates are on or off
	 *
	 * @since 8.0.1
	 *
	 * @return bool - true if automatic updates are on, false if not
	 */
	public function is_automatic_updates_enabled() {
		$options = MPSUM_Updates_Manager::get_options('core');

		// Check automatic update options
		if (isset($options['automatic_development_updates'], $options['automatic_major_updates'], $options['automatic_minor_updates'], $options['automatic_plugin_updates'], $options['automatic_theme_updates'], $options['automatic_translation_updates'])) {
			// Check to see if off is on for all updates
			if ('off' == $options['automatic_development_updates'] && 'off' == $options['automatic_major_updates'] && 'off' == $options['automatic_minor_updates'] && 'off' == $options['automatic_plugin_updates'] && 'off' == $options['automatic_theme_updates'] && 'off' == $options['automatic_translation_updates']) {
				return false;
			}
		}

		// Check core update options
		if (isset($options['core_updates'], $options['plugin_updates'], $options['theme_updates'], $options['translation_updates'])) {
			if ('off' == $options['core_updates'] && 'off' == $options['plugin_updates'] && 'off' == $options['theme_updates'] && 'off' == $options['translation_updates']) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Checks to see what emails to send to
	 *
	 * @since 8.0.1
	 *
	 * @return mixed - Can be a string with an email address or an array of email addresses
	 */
	public function get_emails() {
		// Prepare E-mail Addresses to send to
		$core_options = MPSUM_Updates_Manager::get_options('core');
		$email_addresses = isset($core_options['email_addresses']) ? $core_options['email_addresses'] : array();
		$email_addresses_to_override = array();
		$emails_to_send = '';
		foreach ($email_addresses as $emails) {
			if (is_email($emails)) {
				$email_addresses_to_override[] = $emails;
			}
		}
		if (!empty($email_addresses_to_override)) {
			$emails_to_send = $email_addresses_to_override;
		} else {
			if (is_multisite()) {
				$emails_to_send = get_site_option('admin_email');
			} else {
				$emails_to_send = get_option('admin_email');
			}
		}
		return $emails_to_send;
	}

	/**
	 * Checks for free and premium version of EUM and disables free version if both are available
	 *
	 * @since 8.0.1
	 */
	public function maybe_deactivate_free_version() {
		if (!function_exists('get_plugins')) include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		$get_plugins = get_plugins();
		$free_available = $free_plugin_active = $premium_available = $premium_plugin_active = $free_slug = $premium_slug = false;// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- Both $free_available and $premium_available are used and being set but Ci has flagged this as unused.  Its fine to ignore.
		foreach ($get_plugins as $key => $value) {
			if ('Easy Updates Manager' === $value['Name']) {
				$free_available = true;
				$free_slug = $key;
				if (is_multisite()) {
					if (is_plugin_active_for_network($free_slug)) {
						$free_plugin_active = true;
					}
				} else {
					if (is_plugin_active($free_slug)) {
						$free_plugin_active = true;
					}
				}
			}
			if ('Easy Updates Manager Premium' === $value['Name']) {
				$premium_available = true;
				$premium_slug = $key;
				if (is_multisite()) {
					if (is_plugin_active_for_network($premium_slug)) {
						$premium_plugin_active = true;
					}
				} else {
					if (is_plugin_active($premium_slug)) {
						$premium_plugin_active = true;
					}
				}
			}
		}
		if ($premium_plugin_active && $free_plugin_active) {
			deactivate_plugins($free_slug);
			add_action('admin_notices', array(MPSUM_Updates_Manager::get_instance(), 'show_admin_notice_premium'));
			add_action('network_admin_notices', array(MPSUM_Updates_Manager::get_instance(), 'show_admin_notice_premium'));
		}
	}

	/**
	 * Checks to see if ai plugin is alive in the file system.
	 *
	 * @since 9.0.0
	 *
	 * @param string $plugin_file Plugin relative to the plugin's directory.
	 *
	 * @return true if plugin exists, false if not
	 */
	public function plugin_exists( $plugin_file ) {
		$plugin_dir = WP_PLUGIN_DIR;
		if (file_exists($plugin_dir . '/' . $plugin_file)) {
			return true;
		}
		return false;
	}
}
