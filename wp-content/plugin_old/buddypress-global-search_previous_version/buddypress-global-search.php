<?php
/**
 * Plugin Name: BuddyPress Global Search
 * Plugin URI:  https://www.buddyboss.com/product/buddypress-global-search/
 * Description: Ajax powered global BuddyPress search
 * Author:      BuddyBoss
 * Author URI:  http://buddyboss.com
 * Version:     1.2.1
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
	exit;

/**
 * ========================================================================
 * CONSTANTS
 * ========================================================================
 */
// Codebase version
if (!defined('BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_VERSION')) {
	define('BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_VERSION', '1.2.1');
}

// Database version
if (!defined('BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DB_VERSION')) {
	define('BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DB_VERSION', 1);
}

// Directory
if (!defined('BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DIR')) {
	define('BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DIR', trailingslashit(plugin_dir_path(__FILE__)));
}

// Url
if (!defined('BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_URL')) {
	$plugin_url = plugin_dir_url(__FILE__);

	// If we're using https, update the protocol. Workaround for WP13941, WP15928, WP19037.
	if (is_ssl())
		$plugin_url = str_replace('http://', 'https://', $plugin_url);

	define('BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_URL', $plugin_url);
}

// File
if (!defined('BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_FILE')) {
	define('BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_FILE', __FILE__);
}

/**
 * ========================================================================
 * MAIN FUNCTIONS
 * ========================================================================
 */

/**
 * Main
 *
 * @return	void
 * @since	1.0.0
 */
function buddyboss_global_search_init() {
	global $bp, $buddyboss_global_search;

	$main_include = BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DIR . 'includes/main-class.php';

	try {
		if (file_exists($main_include)) {
			require( $main_include );
		} else {
			$msg = sprintf(__("Couldn't load main class at:<br/>%s", 'buddypress-global-search'), $main_include);
			throw new Exception($msg, 404);
		}
	} catch (Exception $e) {
		$msg = sprintf(__("<h1>Fatal error:</h1><hr/><pre>%s</pre>", 'buddypress-global-search'), $e->getMessage());
		echo $msg;
	}

	$buddyboss_global_search = BuddyBoss_Global_Search_Plugin::instance();
}

add_action('plugins_loaded', 'buddyboss_global_search_init');

/**
 * Must be called after hook 'plugins_loaded'
 *
 * @return	BuddyPress Global Search main/global object
 * @see		class BuddyBoss_Global_Search
 * @since	1.0.0
 */
function buddyboss_global_search() {
	global $buddyboss_global_search;

	return $buddyboss_global_search;
}

/**
 * Register BuddyBoss Menu Page
 */
if ( !function_exists( 'register_buddyboss_menu_page' ) ) {

	function register_buddyboss_menu_page() {
		// Set position with odd number to avoid confict with other plugin/theme.
		add_menu_page( 'BuddyBoss', 'BuddyBoss', 'manage_options', 'buddyboss-settings', '', buddyboss_global_search()->assets_url . '/images/logo.svg', 3 );

		// To remove empty parent menu item.
		add_submenu_page( 'buddyboss-settings', 'BuddyBoss', 'BuddyBoss', 'manage_options', 'buddyboss-settings' );
		remove_submenu_page( 'buddyboss-settings', 'buddyboss-settings' );
	}

	add_action( 'admin_menu', 'register_buddyboss_menu_page' );
}

?>
