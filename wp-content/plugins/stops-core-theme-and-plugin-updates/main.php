<?php
// @codingStandardsIgnoreStart
/*
Plugin Name: Easy Updates Manager
Plugin URI: https://easyupdatesmanager.com
Description: Manage and disable WordPress updates, including core, plugin, theme, and automatic updates - Works with Multisite and has built-in logging features.
Author: Easy Updates Manager Team
Version: 9.0.8
Author URI: https://easyupdatesmanager.com
Contributors: kidsguide, ronalfy
Text Domain: stops-core-theme-and-plugin-updates
Domain Path: /languages
Updates: true
Network: true
*/
// @codingStandardsIgnoreEnd

if (!defined('ABSPATH')) die('No direct access allowed');

if (!defined('EASY_UPDATES_MANAGER_VERSION')) define('EASY_UPDATES_MANAGER_VERSION', '9.0.8');

if (!defined('EASY_UPDATES_MANAGER_MAIN_PATH')) define('EASY_UPDATES_MANAGER_MAIN_PATH', plugin_dir_path(__FILE__));
if (!defined('EASY_UPDATES_MANAGER_URL')) define('EASY_UPDATES_MANAGER_URL', plugin_dir_url(__FILE__));
if (!defined('EASY_UPDATES_MANAGER_SITE_URL')) define('EASY_UPDATES_MANAGER_SITE_URL', 'https://easyupdatesmanager.com/');
if (!defined('EASY_UPDATES_MANAGER_SLUG')) define('EASY_UPDATES_MANAGER_SLUG', plugin_basename(__FILE__));

if (!class_exists('MPSUM_Updates_Manager')) {
	/**
	 * Main plugin class
	 *
	 * Initializes auto-loader, internationalization, and plugin dependencies.
	 */
	class MPSUM_Updates_Manager {

		/**
		 * Holds the class instance.
		 *
		 * @since 5.0.0
		 * @access static
		 * @var MPSUM_Updates_Manager $instance
		 */
		private static $instance = null;

		/**
		 * Stores the plugin's options
		 *
		 * @since 5.0.0
		 * @access static
		 * @var array $options
		 */
		private static $options = false;

		/**
		 * Template directories
		 *
		 * @var string
		 */
		private $template_directories;

		/**
		 * Notice class instance
		 *
		 * @var object
		 */
		protected static $notices_instance = null;

		// Minimum PHP version required to run this plugin
		const PHP_REQUIRED = '5.4';
		// Minimum WP version required to run this plugin
		const WP_REQUIRED = '5.1';

		/**
		 * Retrieve a class instance.
		 *
		 * Retrieve a class instance.
		 *
		 * @since 5.0.0
		 * @access static
		 *
		 * @return MPSUM_Updates_Manager Instance of the class.
		 */
		public static function get_instance() {
			if (null == self::$instance) {
				self::$instance = new self;
			}
			return self::$instance;
		} //end get_instance

		/**
		 * Retrieve the plugin basename.
		 *
		 * Retrieve the plugin basename.
		 *
		 * @since 5.0.0
		 * @access static
		 *
		 * @return string plugin basename
		 */
		public static function get_plugin_basename() {
			return EASY_UPDATES_MANAGER_SLUG;
		}

		/**
		 * Get the WordPress version
		 *
		 * @return String - the version
		 */
		public function get_wordpress_version() {
			static $got_wp_version = false;
			if (!$got_wp_version) {
				global $wp_version;
				@include(ABSPATH.WPINC.'/version.php');
				$got_wp_version = $wp_version;
			}
			return $got_wp_version;
		}


		/**
		 * Class constructor.
		 *
		 * Set up internationalization, auto-loader, and plugin initialization.
		 *
		 * @since 5.0.0
		 * @access private
		 */
		private function __construct() {
			$has_errors = false;

			if (version_compare(PHP_VERSION, self::PHP_REQUIRED, '<')) {
				add_action('admin_notices', array($this, 'admin_notice_insufficient_php'));
				if (is_multisite()) {
					add_action('network_admin_notices', array($this, 'admin_notice_insufficient_php'));
				}
				$has_errors = true;
			}

			include ABSPATH.WPINC.'/version.php';
			if (version_compare($wp_version, self::WP_REQUIRED, '<')) {// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				add_action('admin_notices', array($this, 'admin_notice_insufficient_wp'));
				if (is_multisite()) {
					add_action('network_admin_notices', array($this, 'admin_notice_insufficient_wp'));
				}
				$has_errors = true;
			}
			if (!$has_errors) {
				spl_autoload_register(array($this, 'loader'));
				add_action('init', array($this, 'init'));
				add_action('plugins_loaded', array($this, 'plugins_loaded'));
				add_action('admin_init', array($this, 'admin_init'));
				add_action('wp_ajax_easy_updates_manager_ajax', array($this, 'easy_updates_manager_ajax_handler'));
				new MPSUM_UpdraftCentral();
				register_deactivation_hook(__FILE__, array($this, 'deactivation_hook'));
			}
		} //end constructor

		/**
		 * Run code during the init action.
		 *
		 * Run code during the init action.
		 *
		 * @since 6.2.5
		 * @access public
		 */
		public function init() {
			/* Localization Code */
			load_plugin_textdomain('stops-core-theme-and-plugin-updates', false, dirname(plugin_basename(__FILE__)) . '/languages/');

			// Logging
			$options = MPSUM_Updates_Manager::get_options('core');
			if (!isset($options['logs'])) {
				$options['logs'] = 'on';
				MPSUM_Updates_Manager::update_options($options, 'core');
			}
			if ('on' === $options['logs']) {
				MPSUM_Logs::run();
			}

			MPSUM_Admin_Ajax::get_instance();
		}

		/**
		 * Return the absolute path to an asset.
		 *
		 * Return the absolute path to an asset based on a relative argument.
		 *
		 * @since 5.0.0
		 * @access static
		 *
		 * @param string $path Relative path to the asset.
		 * @return string Absolute path to the relative asset.
		 */
		public static function get_plugin_dir($path = '') {
			$dir = rtrim(plugin_dir_path(__FILE__), '/');
			if (!empty($path) && is_string($path))
				$dir .= '/' . ltrim($path, '/');
			return $dir;
		}

		/**
		 * Return the web path to an asset.
		 *
		 * Return the web path to an asset based on a relative argument.
		 *
		 * @since 5.0.0
		 * @access static
		 *
		 * @param string $path Relative path to the asset.
		 * @return string Web path to the relative asset.
		 */
		public static function get_plugin_url($path = '') {
			$dir = rtrim(plugin_dir_url(__FILE__), '/');
			if (!empty($path) && is_string($path))
				$dir .= '/' . ltrim($path, '/');
			return $dir;
		}

		/**
		 * Retrieve the plugin's options
		 *
		 * Retrieve the plugin's options based on context
		 *
		 * @since 5.0.0
		 * @access static
		 *
		 * @param  string $context      Context to retrieve options for.	 This is used as an array key.
		 * @param  bool	  $force_reload Whether to retrieve cached options or forcefully retrieve from the database.
		 * @return array All options if no context, or associative array if context is set.	 Empty array if no options.
		 */
		public static function get_options($context = '', $force_reload = false) {
			// Try to get cached options
			$options = self::$options;
			if (false === $options || true === $force_reload) {
				$options = get_site_option('MPSUM', false, false);
			}

			if (false === $options) {
				$options = self::maybe_migrate_options();
			}

			if ('advanced' === $context) {
				$options = self::maybe_migrate_excluded_users_options($options);
			}

			// Migrate to new UI
			$options = self::maybe_migrate_ui_options($options);

			// Store options
			if (!is_array($options)) {
				$options = array();
			}

			// Assign options for caching
			self::$options = $options;

			// Attempt to get context
			if (!empty($context) && is_string($context)) {
				if (array_key_exists($context, $options)) {
					return (array) $options[$context];
				} else {
					return array();
				}
			}

			return $options;
		} //get_options

		/**
		 * Auto-loads classes.
		 *
		 * Auto-load classes that belong to this plugin.
		 *
		 * @since 5.0.0
		 * @access private
		 *
		 * @param string $class_name The name of the class.
		 */
		private function loader($class_name) {
			if (class_exists($class_name, false) || false === strpos($class_name, 'MPSUM')) {
				return;
			}
			$file = MPSUM_Updates_Manager::get_plugin_dir("includes/{$class_name}.php");
			if (file_exists($file)) {
				include_once($file);
			}
		}

		/**
		 * Determine whether to migrate options from an older version of the plugin.
		 *
		 * Migrate old options to new plugin format.
		 *
		 * @since 5.0.0
		 * @access private
		 *
		 * @return bool|array  false if no migration, associative array of options if migration successful
		 */
		public static function maybe_migrate_options() {
			$options = false;
			$original_options = get_option('_disable_updates', false);

			if (false !== $original_options && is_array($original_options)) {
				$options = array(
					'core' => array(),
					'plugins' => array(),
					'themes' => array()
				);
				// Global WP Updates
				if (isset($original_options['all']) && "1" === $original_options['all']) {
					$options['core']['all_updates'] = 'off';
				}
				// Global Plugin Updates
				if (isset($original_options['plugin']) && "1" === $original_options['plugin']) {
					$options['core']['plugin_updates'] = 'off';
				}
				// Global Theme Updates
				if (isset($original_options['theme']) && "1" === $original_options['theme']) {
					$options['core']['theme_updates'] = 'off';
				}
				// Global Core Updates
				if (isset($original_options['core']) && "1" === $original_options['core']) {
					$options['core']['core_updates'] = 'off';
				}
				// Global Individual Theme Updates
				if (isset($original_options['it']) && "1" === $original_options['it']) {
					if (isset($original_options['themes']) && is_array($original_options['themes'])) {
						$options['themes'] = $original_options['themes'];
					}
				}
				// Global Individual Plugin Updates
				if (isset($original_options['ip']) && "1" === $original_options['ip']) {
					if (isset($original_options['plugins']) && is_array($original_options['plugins'])) {
						$options['plugins'] = $original_options['plugins'];
					}
				}
				// Browser Nag
				if (isset($original_options['bnag']) && "1" === $original_options['bnag']) {
					$options['core']['misc_browser_nag'] = 'off';
				}
				// WordPress Version
				if (isset($original_options['wpv']) && "1" === $original_options['wpv']) {
					$options['core']['misc_wp_footer'] = 'off';
				}
				// Translation Updates
				if (isset($original_options['auto-translation-updates']) && "1" === $original_options['auto-translation-updates']) {
					$options['core']['automatic_translation_updates'] = 'off';
				}
				// Translation Updates
				if (isset($original_options['auto-core-emails']) && "1" === $original_options['auto-core-emails']) {
					$options['core']['notification_core_update_emails'] = 'off';
				}
				// Automatic Updates
				if (isset($original_options['abup']) && "1" === $original_options['abup']) {
					$options['core']['automatic_major_updates'] = 'off';
					$options['core']['automatic_minor_updates'] = 'off';
					$options['core']['automatic_plugin_updates'] = 'off';
					$options['core']['automatic_theme_updates'] = 'off';
				}

				delete_option('_disable_updates');
				delete_site_option('_disable_updates');
				MPSUM_Updates_Manager::update_options($options);
			}
			return $options;
		}

		/**
		 * Migrates from the legacy UI options to the new UI options.
		 *
		 * @param array $options Array of plugin options
		 *
		 * @return array Updated array of plugin options
		 */
		public static function maybe_migrate_ui_options($options) {

			$new_options = array();
			// Migrate WordPress Core options.
			if (isset($options['core']['automatic_major_updates']) && isset($options['core']['automatic_minor_updates'])) {
				if ('on' === $options['core']['automatic_major_updates'] && 'on' === $options['core']['automatic_minor_updates']) {
					$new_options['core']['core_updates'] = 'automatic';
				} elseif ('on' === $options['core']['automatic_minor_updates']) {
					$new_options['core']['core_updates'] = 'automatic_minor';
				} elseif ('on' === $options['core']['automatic_major_updates']) {
					$new_options['core']['core_updates'] = 'automatic';
				} elseif ('off' === $options['core']['automatic_major_updates'] || 'off' === $options['core']['automatic_minor_updates']) {
					$new_options['core']['core_updates'] = 'automatic_off';
				}
				unset($options['core']['automatic_major_updates']);
			}
			if (isset($options['core']['core_updates']) && 'off' === $options['core']['core_updates']) {
				$new_options['core']['core_updates'] = 'off';
			}
			if (isset($new_options['core']['core_updates'])) {
				$options['core']['core_updates'] = $new_options['core']['core_updates'];
			}

			// Migrate Plugin Options.
			if (isset($options['core']['automatic_plugin_updates'])) {
				if ('on' === $options['core']['automatic_plugin_updates']) {
					$new_options['core']['plugin_updates'] = 'automatic';
				} elseif ('custom' === $options['core']['automatic_plugin_updates']) {
					$new_options['core']['plugin_updates'] = 'individual';
				} elseif ('off' === $options['core']['automatic_plugin_updates']) {
					$new_options['core']['plugin_updates'] = 'automatic_off';
				}
				unset($options['core']['automatic_plugin_updates']);
			}
			if (isset($options['core']['plugin_updates']) && 'off' === $options['core']['plugin_updates']) {
				$new_options['core']['plugin_updates'] = 'off';
			}
			if (isset($new_options['core']['plugin_updates'])) {
				$options['core']['plugin_updates'] = $new_options['core']['plugin_updates'];
			}

			// Migrate Theme Options.
			if (isset($options['core']['automatic_theme_updates'])) {
				if ('on' === $options['core']['automatic_theme_updates']) {
					$new_options['core']['theme_updates'] = 'automatic';
				} elseif ('custom' === $options['core']['automatic_updates']) {
					$new_options['core']['theme_updates'] = 'individual';
				} elseif ('off' === $options['core']['automatic_theme_updates']) {
					$new_options['core']['theme_updates'] = 'automatic_off';
				}
				unset($options['core']['automatic_theme_updates']);
			}
			if (isset($options['core']['theme_updates']) && 'off' === $options['core']['theme_updates']) {
				$new_options['core']['theme_updates'] = 'off';
			}
			if (isset($new_options['core']['theme_updates'])) {
				$options['core']['theme_updates'] = $new_options['core']['theme_updates'];
			}

			// Migrate Translation Options.
			if (isset($options['core']['automatic_translation_updates'])) {
				if ('on' === $options['core']['automatic_translation_updates']) {
					$new_options['core']['translation_updates'] = 'automatic';
				} elseif ('off' === $options['core']['automatic_translation_updates']) {
					$new_options['core']['translation_updates'] = 'automatic_off';
				}
				unset($options['core']['automatic_translation_updates']);
			}
			if (isset($options['core']['translation_updates']) && 'off' === $options['core']['translation_updates']) {
				$new_options['core']['translation_updates'] = 'off';
			}
			if (isset($new_options['core']['translation_updates'])) {
				$options['core']['translation_updates'] = $new_options['core']['translation_updates'];
			}

			return $options;
		}

		/**
		 * Migrates `excluded_users` option to `advanced` context
		 *
		 * @param array $options Array of plugin options
		 *
		 * @return array Updated array of plugin options
		 */
		public static function maybe_migrate_excluded_users_options($options) {
			if (isset($options['advanced']['excluded_users'])) {
				if (isset($options['excluded_usders'])) {
					unset($options['excluded_users']);
				}
				return $options;
			}
			if (isset($options['excluded_users'])) {
				$options['advanced']['excluded_users'] = $options['excluded_users'];
				unset($options['excluded_users']);
			} elseif (isset($options['advanced']['excluded_users']) && isset($options['excluded_users'])) {
				$options['advanced']['excluded_users'] = $options['excluded_users'];
			} else {
				$options['advanced']['excluded_users'] = array();
			}
			return $options;
		}

		/**
		 * Initialize the plugin and its dependencies.
		 *
		 * Initialize the plugin and its dependencies.
		 *
		 * @since 5.0.0
		 * @access public
		 * @see __construct
		 * @internal Uses plugins_loaded action
		 */
		public function plugins_loaded() {

			// Skip disable updates if a user is excluded
			$disable_updates_skip = false;
			if (current_user_can('update_plugins')) {
				$current_user = wp_get_current_user();
				$current_user_id = $current_user->ID;
				$advanced_options = MPSUM_Updates_Manager::get_options('advanced');
				$excluded_users = isset($advanced_options['excluded_users']) ? $advanced_options['excluded_users'] : array();
				if (in_array($current_user_id, $excluded_users)) {
					$disable_updates_skip = true;
				}
			}
			if (false === $disable_updates_skip) {
				MPSUM_Disable_Updates::run();
			}

			$not_doing_ajax = (!defined('DOING_AJAX') || !DOING_AJAX);
			$not_admin_disabled = true;
			if (defined('MPSUM_DISABLE_ADMIN')) {
				$not_admin_disabled = MPSUM_DISABLE_ADMIN ? false : true;
			}
			if (current_user_can('manage_options') && $not_doing_ajax && $not_admin_disabled && !$disable_updates_skip) {
				MPSUM_Admin::run();
			}
			$this->check_premium();
		}

		/**
		 * Checks if this is the premium version and loads it. It also ensures that if the free version is installed then it is disabled with an appropriate error message.
		 *
		 *  @returns true if premium, false if not
		 */
		private function check_premium() {

			// Run Premium loader if it exists
			if (file_exists(EASY_UPDATES_MANAGER_MAIN_PATH . 'premium.php') && !class_exists('MPSUM_Premium')) {
				include_once(EASY_UPDATES_MANAGER_MAIN_PATH . 'premium.php');
			}

			$utils = MPSUM_Utils::get_instance();
			$utils->maybe_deactivate_free_version();
		}

		/**
		 * This is a notice to show users that premium is installed
		 */
		public function show_admin_notice_premium() {
			echo '<div id="eum-premium-installed-warning" class="error"><p>'.__('Easy Updates Manager (Free) has been de-activated, because Easy Updates Manager Premium is active.', 'stops-core-theme-and-plugin-updates').'</p></div>';
			if (isset($_GET['activate'])) unset($_GET['activate']);
		}

		/**
		 * Checks whether this is premium plugin or not
		 *
		 * @return Boolean - returns true if premium otherwise false
		 */
		public function is_premium() {
			if (file_exists(__DIR__ . '/premium.php')) return true;
			return false;
		}

		/**
		 * Save plugin options.
		 *
		 * Saves the plugin options based on context.  If no context is provided, updates all options.
		 *
		 * @since 5.0.0
		 * @access static
		 *
		 * @param array	 $options Associative array of plugin options.
		 * @param string $context Array key of which options to update
		 */
		public static function update_options($options = array(), $context = '') {
			$options_to_save = self::get_options();

			if (!empty($context) && is_string($context)) {
				$options_to_save[$context] = $options;
			} else {
				$options_to_save = $options;
			}
			if (is_multisite()) {
				update_site_option('MPSUM', $options_to_save);
			} else {
				update_option('MPSUM', $options_to_save);
			}
			self::$options = $options_to_save;
		}

		/**
		 * Saves option based on single site / multi site setup
		 *
		 * @param string $option Option key to be updated
		 * @param mixed  $value  Option value to be updated
		 */
		public static function update_option($option, $value) {
			if (is_multisite()) {
				update_site_option($option, $value);
			} else {
				update_option($option, $value);
			}
		}

		/**
		 * Get object of Easy_Updates_Manager_Notices
		 *
		 * @return object object of Easy_Updates_Manager_Notices
		 */
		public static function get_notices() {
			if (empty(self::$notices_instance)) {
				if (!class_exists('Easy_Updates_Manager_Notices')) include_once(EASY_UPDATES_MANAGER_MAIN_PATH.'includes/easy-updates-manager-notices.php');
				self::$notices_instance = new Easy_Updates_Manager_Notices();
			}
			return self::$notices_instance;
		}

		/**
		 * Gets an array of plugins active on either the current site, or site-wide
		 *
		 * @return array - a list of plugin paths (relative to the plugin directory)
		 */
		private function get_active_plugins() {

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
		 * This function checks whether a specific plugin is installed, and returns information about it
		 *
		 * @param  string $name Specify "Plugin Name" to return details about it.
		 * @return array        Returns an array of details such as if installed, the name of the plugin and if it is active.
		 */
		public function is_installed($name) {

			// Needed to have the 'get_plugins()' function
			if (!function_exists('get_plugins')) {
				include_once(ABSPATH.'wp-admin/includes/plugin.php');
			}

			// Gets all plugins available
			$get_plugins = get_plugins();

			$active_plugins = $this->get_active_plugins();
			$plugin_info = array();
			$plugin_info['installed'] = false;
			$plugin_info['active'] = false;


			// Loops around each plugin available.
			foreach ($get_plugins as $key => $value) {
				// If the plugin name matches that of the specified name, it will gather details.
				if ($value['Name'] != $name) continue;
				$plugin_info['installed'] = true;
				$plugin_info['name'] = $key;
				$plugin_info['version'] = $value['Version'];
				if (in_array($key, $active_plugins)) {
					$plugin_info['active'] = true;
				}
				break;
			}
			return $plugin_info;
		}

		/**
		 * Adds actions which fired on admin_init action hook
		 *
		 * @return void
		 */
		public function admin_init() {
			$pagenow = $GLOBALS['pagenow'];

			$this->register_template_directories();

			// Check for WP Constants that disable updates and display a notice.
			$upgrade_constants = MPSUM_Constant_Checks::get_instance();
			if ($upgrade_constants->is_config_options_disabled()) {
				$upgrade_constant_notice = get_site_option('easy_updates_manager_dismiss_constant_notices', 0);
				if (!$upgrade_constant_notice) {
					add_action('all_admin_notices', array($this, 'show_autoupdate_constant_warning'));
				}
			}

			// Add filters to overwrite auto update UI in WP 5.5
			add_filter('plugin_auto_update_setting_html', array($this, 'eum_plugin_auto_update_setting_html'), 10, 3);
			add_filter('theme_auto_update_setting_html', array($this, 'eum_theme_auto_update_setting_html'), 10, 3);
			add_filter('theme_auto_update_setting_template', array($this, 'eum_auto_update_setting_template'));

			if ('index.php' != $pagenow) return;
			if (current_user_can('update_plugins') || (defined('EASY_UPDATES_MANAGER_FORCE_DASHNOTICE') && EASY_UPDATES_MANAGER_FORCE_DASHNOTICE)) {
				$dismissed_until = get_site_option('easy_updates_manager_dismiss_dash_notice_until', 0);
				if (isset($_GET['page']) && 'mpsum-update-options' == $_GET['page']) {
					$dismissed_until = get_site_option('easy_updates_manager_dismiss_eum_notice_until', 0);
				}
				$installed = $installed_for = true;
				if (file_exists(EASY_UPDATES_MANAGER_MAIN_PATH . 'index.html')) {
					$installed = filemtime(EASY_UPDATES_MANAGER_MAIN_PATH . 'index.html');
					$installed_for = (time() - $installed);
				}
				$is_eum_admin = false;
				if (isset($_GET['page']) && 'mpsum-update-options' === $_GET['page']) {
					$is_eum_admin = true;
				}
				if (!$is_eum_admin) {
					add_action('all_admin_notices', array($this, 'maybe_show_admin_notice_upgraded'));
				}

				if (!$is_eum_admin) return;
				if (($installed && time() > $dismissed_until && $installed_for < (14 * 86400) && !defined('EASY_UPDATES_MANAGER_NOADS_B')) || (defined('EASY_UPDATES_MANAGER_FORCE_DASHNOTICE') && EASY_UPDATES_MANAGER_FORCE_DASHNOTICE)) {
					add_action('all_admin_notices', array($this, 'show_admin_notice_upgraded'));
				} else {
					$enable_notices = get_site_option('easy_updates_manager_enable_notices', 'on');
					if ('on' === $enable_notices) {
						add_action('all_admin_notices', array($this->get_notices(), 'do_notice'));
					}
				}
			}
		}

		/**
		 * This function will overwrite the default auto update UI on the plugins page in the WordPress dashboard in WordPress 5.5+, depending on the options setup in EUM will depend on the output
		 *
		 * @param string $html        - the HTML to filter
		 * @param string $plugin_file - the plugin file the HTML is for
		 * @return string - the filtered HTML auto update UI
		 */
		public function eum_plugin_auto_update_setting_html($html, $plugin_file) {
			return $this->eum_entity_auto_update_setting_html($html, 'plugin', $plugin_file, false);
		}

		/**
		 * This function will overwrite the default auto update UI on the themes page on a multisite in the WordPress dashboard in WordPress 5.5+, depending on the options setup in EUM will depend on the output
		 *
		 * @param string $html       - the HTML to filter
		 * @param string $stylesheet - the theme file the HTML is for
		 * @return string - the filtered HTML auto update UI
		 */
		public function eum_theme_auto_update_setting_html($html, $stylesheet) {
			return $this->eum_entity_auto_update_setting_html($html, 'theme', $stylesheet, false);
		}

		/**
		 * This function will overwrite the default auto update UI on the themes page for single sites in the WordPress dashboard in WordPress 5.5+ depending on the options setup in EUM will depend on the output
		 *
		 * @param string $template - the theme template
		 *
		 * @return string - returns a filtered theme template
		 */
		public function eum_auto_update_setting_template($template) {
			return $this->eum_entity_auto_update_setting_html($template, 'theme', '', true);
		}

		/**
		 * This function will overwrite the default auto update UI on the passed in entity page for single and multi sites in the WordPress dashboard in WordPress 5.5+ depending on the options setup in EUM will depend on the output
		 *
		 * @param string  $html        - the HTML to filter
		 * @param string  $entity      - the entity type (theme/plugin)
		 * @param string  $entity_file - the entity file the HTML is for
		 * @param boolean $template    - if this should be returned in template format or not (single site themes currently uses this)
		 *
		 * @return string - returns a filtered HTML string or template
		 */
		private function eum_entity_auto_update_setting_html($html, $entity, $entity_file, $template) {
			$entity_option = 'plugin' == $entity ? 'plugins' : 'themes';
			$core_options = MPSUM_Updates_Manager::get_options('core');
			$entity_options = MPSUM_Updates_Manager::get_options($entity_option);
			$entity_automatic_options = MPSUM_Updates_Manager::get_options($entity_option.'_automatic');
			$url = MPSUM_Admin::get_url();
			
			// Allow white-labelling
			$eum_white_label = apply_filters('eum_whitelabel_name', __('Easy Updates Manager', 'stops-core-theme-and-plugin-updates'));

			if (!isset($core_options['plugin_updates']) || !isset($core_options['theme_updates'])) {
				$html = '<a href="'.$url.'">'.sprintf(__('Managed by %s.', 'stops-core-theme-and-plugin-updates'), $eum_white_label).'</a>';
				return $html;
			}

			$updates = 'plugin' == $entity ? $core_options['plugin_updates'] : $core_options['theme_updates'];

			if ('automatic' == $updates) {
				$html = '<a href="'.$url.'">'.sprintf(__('Managed by %s.', 'stops-core-theme-and-plugin-updates'), $eum_white_label).'</a>';
				if ($template) return $html;
			} elseif ('on' == $updates) {
				$html = '<a href="'.$url.'">'.sprintf(__('Managed by %s (%s).', 'stops-core-theme-and-plugin-updates'), $eum_white_label, __('on', 'stops-core-theme-and-plugin-updates')).'</a>';
				if ($template) return $html;
			} elseif ('off' == $updates) {
				$html = '<a href="'.$url.'">'.sprintf(__('Disabled in %s.', 'stops-core-theme-and-plugin-updates'), $eum_white_label).'</a>';
				if ($template) return $html;
			} elseif ('automatic_off' == $updates) {
				$html = '<a href="'.$url.'">'.sprintf(__('Disabled in %s.', 'stops-core-theme-and-plugin-updates'), $eum_white_label).'</a>';
				if ($template) return $html;
			} elseif ('individual' == $updates && !$template) {
				
				$html = '<a href="'.$url.'">'.sprintf(__('Managed by %s.', 'stops-core-theme-and-plugin-updates'), $eum_white_label).'</a>';

				if (!empty($entity_options)) {
					foreach ($entity_options as $ent) {
						if ($ent == $entity_file) $html = '<a href="'.$url.'">'.sprintf(__('Disabled in %s.', 'stops-core-theme-and-plugin-updates'), $eum_white_label).'</a>';
					}
				}

				if (!empty($entity_automatic_options)) {
					foreach ($entity_automatic_options as $ent) {
						if ($ent == $entity_file) $html = '<a href="'.$url.'">'.sprintf(__('Managed by %s.', 'stops-core-theme-and-plugin-updates'), $eum_white_label).'</a>';
					}
				}
			} elseif ('individual' == $updates && $template) {
				
				$entity_options_string = '';
				$entity_automatic_options_string = '';
				$entity_options_html = '';
				$entity_automatic_options_html = '';

				if (!empty($entity_options)) {
					$last = count($entity_options) - 1;
					foreach ($entity_options as $key => $ent) {
						$entity_options_string .= "'".$ent."'";
						if ($last != $key) $entity_options_string .= ',';
					}
					$entity_options_html = '<a href="'.$url.'">'.sprintf(__('Disabled in %s.', 'stops-core-theme-and-plugin-updates'), $eum_white_label).'</a>';
				}

				if (!empty($entity_automatic_options)) {
					$last = count($entity_automatic_options) - 1;
					foreach ($entity_automatic_options as $key => $theme) {
						$entity_automatic_options_string .= "'".$theme."'";
						if ($last != $key) $entity_automatic_options_string .= ',';
					}
					$entity_automatic_options_html = '<a href="'.$url.'">'.sprintf(__('Managed by %s.', 'stops-core-theme-and-plugin-updates'), $eum_white_label).'</a>';
				}

				$entity_not_set_html = '<a href="'.$url.'">'.sprintf(__('Managed by %s.', 'stops-core-theme-and-plugin-updates'), $eum_white_label).'</a>';

				return "<# if ([".$entity_options_string."].includes(data.id)) { #>
					$entity_options_html
				<# } else if ([".$entity_automatic_options_string."].includes(data.id)) { #>
					$entity_automatic_options_html
				<# } else { #>
					$entity_not_set_html
				<# } #>";
			}

			return $html;
		}
		
		/**
		 * Display Constant Warnings
		 *
		 * @return void
		 */
		public function show_autoupdate_constant_warning() {
			$this->include_template('notices/dashboard-constant-warning.php');
		}
		/**
		 * Display welcome dashboard
		 *
		 * @return void
		 */
		public function maybe_show_admin_notice_upgraded() {
			$time = get_site_option('easy_updates_manager_dismiss_dash_notice_until');
			$enable_notices = get_site_option('easy_updates_manager_enable_notices', 'on');
			$new_time = time() . '';
			if ($new_time > $time && 'on' === $enable_notices) {
				$this->include_template('notices/thanks-for-using-main-dash.php');
			}
		}
		/**
		 * Display welcome dashboard
		 *
		 * @return void
		 */
		public function show_admin_notice_upgraded() {
			$enable_notices = get_site_option('easy_updates_manager_enable_notices', 'on');
			if ('on' === $enable_notices) {
				$this->include_template('notices/thanks-for-using-main-dash.php');
			}
		}

		/**
		 * Gives capability which required to dismiss notices or welcome banner by ajax
		 *
		 * @return string capability
		 */
		public function capability_required() {
			return apply_filters('easy_updates_manager_capability_required', 'manage_options');
		}

		/**
		 * Ajax handling function for dismiss notices and welcome dashboard
		 *
		 * @return void
		 */
		public function easy_updates_manager_ajax_handler() {
			$nonce = empty($_POST['nonce']) ? '' : $_POST['nonce'];

			if (!wp_verify_nonce($nonce, 'easy-updates-manager-ajax-nonce') || empty($_POST['subaction'])) die('Security check');

			$subaction = $_POST['subaction'];

			if (!current_user_can($this->capability_required())) die('Security check');

			$results = array();

			// Some commands that are available via AJAX only.
			if ('dismiss_eum_notice_until' == $subaction) {
				update_site_option('easy_updates_manager_dismiss_eum_notice_until', (time() + 183 * 86400));
			} elseif ('dismiss_dash_notice_until' == $subaction) {
				update_site_option('easy_updates_manager_dismiss_dash_notice_until', (time() + 366 * 86400));
			} elseif ('dismiss_page_notice_until' == $subaction) {
				update_site_option('easy_updates_manager_dismiss_page_notice_until', (time() + 84 * 86400));
			} elseif ('dismiss_season_notice_until' == $subaction) {
				update_site_option('easy_updates_manager_dismiss_season_notice_until', (time() + 84 * 86400));
			} elseif ('dismiss_survey_notice_until' == $subaction) {
				update_site_option('easy_updates_manager_dismiss_survey_notice_until', (time() + 366 * 86400));
			} elseif ('dismiss_constant_notices' == $subaction) {
				update_site_option('easy_updates_manager_dismiss_constant_notices', true);
			}

			wp_send_json($results);
		}

		/**
		 * Gives template directory path
		 *
		 * @return string template directory path
		 */
		public function get_templates_dir() {
			return apply_filters('easy_updates_manager_templates_dir', wp_normalize_path(EASY_UPDATES_MANAGER_MAIN_PATH.'/templates'));
		}

		/**
		 * Gives Template URL
		 *
		 * @return string template url
		 */
		public function get_templates_url() {
			return apply_filters('easy_updates_manager_templates_url', EASY_UPDATES_MANAGER_URL.'templates');
		}

		/**
		 * Includes Template
		 *
		 * @param string  $path                   template path
		 * @param boolean $return_instead_of_echo If It is true, This function returns template string intead of printing otherwise template will be printed
		 * @param array   $extract_these          Extract variables which needs to pass template
		 * @return string|void If $return_instead_of_echo parameter is true, It returns template string otherwise returns nothing
		 */
		public function include_template($path, $return_instead_of_echo = false, $extract_these = array()) {
			if ($return_instead_of_echo) ob_start();

			if (preg_match('#^([^/]+)/(.*)$#', $path, $matches)) {
				$prefix = $matches[1];
				$suffix = $matches[2];
				if (isset($this->template_directories[$prefix])) {
					$template_file = $this->template_directories[$prefix].'/'.$suffix;
				}
			}

			if (!isset($template_file)) {
				$template_file = EASY_UPDATES_MANAGER_MAIN_PATH.'templates/'.$path;
			}

			$template_file = apply_filters('easy_updates_manager_template', $template_file, $path);

			do_action('easy_updates_manager_before_template', $path, $template_file, $return_instead_of_echo, $extract_these);

			if (!file_exists($template_file)) {
				error_log("Easy Updates Manager: template not found: ".$template_file);
				echo __('Error:', 'stops-core-theme-and-plugin-updates').' '.__('template not found', 'stops-core-theme-and-plugin-updates')." (".$path.")";
			} else {
				extract($extract_these);
				$easy_updates_manager = $this; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- This is used in the template file
				$easy_updates_manager_notices = $this->get_notices();// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- This is used in the template file
				include $template_file;
			}

			do_action('easy_updates_manager_after_template', $path, $template_file, $return_instead_of_echo, $extract_these);

			if ($return_instead_of_echo) return ob_get_clean();
		}

		/**
		 * Build a list of template directories (stored in self::$template_directories)
		 */
		private function register_template_directories() {

			$template_directories = array();

			$templates_dir = $this->get_templates_dir();

			if ($dh = opendir($templates_dir)) {
				while (($file = readdir($dh)) !== false) {
					if ('.' == $file || '..' == $file) continue;
					if (is_dir($templates_dir.'/'.$file)) {
						$template_directories[$file] = $templates_dir.'/'.$file;
					}
				}
				closedir($dh);
			}

			// Optimal hook for most extensions to hook into.
			$this->template_directories = apply_filters('easy_updates_manager_template_directories', $template_directories);

		}

		/**
		 * This will customize a URL with a correct Affiliate link
		 * This function can be update to suit any URL as longs as the URL is passed
		 *
		 * @param String  $url					  - URL to be check to see if it an updraftplus match.
		 * @param String  $text					  - Text to be entered within the href a tags.
		 * @param String  $html					  - Any specific HTML to be added.
		 * @param String  $class				  - Specify a class for the href (including the attribute label)
		 * @param Boolean $return_instead_of_echo - if set, then the result will be returned, not echo-ed.
		 *
		 * @return String|void
		 */
		public function easy_updates_manager_url($url, $text, $html = '', $class = '', $return_instead_of_echo = false) {
			// Check if the URL is UpdraftPlus.
			if (false !== strpos($url, '//updraftplus.com')) {
				// Set URL with Affiliate ID.
				$url = $url.'?ref='.$this->get_notices()->get_affiliate_id().'&source=eum';

				// Apply filters.
				$url = apply_filters('easy_updates_manager_updraftplus_com_link', $url);
			}
			// Return URL - check if there is HTML such as images.
			if ('' != $html) {
				$result = '<a '.$class.' href="'.esc_attr($url).'">'.$html.'</a>';
			} else {
				$result = '<a '.$class.' href="'.esc_attr($url).'">'.htmlspecialchars($text).'</a>';
			}
			if ($return_instead_of_echo) return $result;
			echo $result;
		}

		/**
		 * Runs upon the WP action admin_notices if the PHP version is too low
		 */
		public function admin_notice_insufficient_php() {
			$this->show_admin_warning(
				__('Higher PHP version required', 'stops-core-theme-and-plugin-updates'),
				sprintf(__('The %s plugin requires %s version %s or higher - your current version is only %s.', 'stops-core-theme-and-plugin-updates'), 'Easy Updates Manager', 'PHP', self::PHP_REQUIRED, PHP_VERSION),
				'notice-error'
			);
		}

		/**
		 * Runs upon the WP action admin_notices if the WP version is too low
		 */
		public function admin_notice_insufficient_wp() {
			include ABSPATH.WPINC.'/version.php';
			$this->show_admin_warning(
				__('Higher WordPress version required', 'stops-core-theme-and-plugin-updates'),
				sprintf(__('The %s plugin requires %s version %s or higher - your current version is only %s.', 'stops-core-theme-and-plugin-updates'), 'Easy Updates Manager', 'WordPress', self::WP_REQUIRED, $wp_version), // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				'notice-error'
			);
		}

		/**
		 * Shows a dismissible warning notice admin dashboard
		 *
		 * @param string $title   Title of the warning message
		 * @param string $message Warning message in detail
		 * @param string $class   Style class name for warning
		 */
		private function show_admin_warning($title, $message, $class = 'notice-error') {
			?>
			<div class="notice is-dismissible <?php echo $class; ?>">
				<p>
					<?php if (!empty($title)) :?>
						<strong>
							<?php echo $title; ?>
						</strong>
					<?php endif;?>
					<span>
						<?php echo $message; ?>
					</span>
				</p>
			</div>
			<?php
		}

		/**
		 * Clears scheduled cron and resets to WordPress default on plugin deactivation
		 */
		public function deactivation_hook() {
			if (class_exists('MPSUM_Update_Cron')) {
				$cron = MPSUM_Update_Cron::get_instance();
				$cron->clear_wordpress_crons();
				$cron->set_default_cron();
			}
		}
	}
}

if (!function_exists('Easy_Updates_Manager')) {
	/**
	 * Initializes plugin and returns main class instance
	 *
	 * @return MPSUM_Updates_Manager
	 */
	function Easy_Updates_Manager() {
		return MPSUM_Updates_Manager::get_instance();
	}
}

Easy_Updates_Manager();
