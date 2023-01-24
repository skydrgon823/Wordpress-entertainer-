<?php
/**
 * Plugin Name: BuddyBuilder - BuddyPress Builder for Elementor
 * Description: Fully build and customize your BuddyPress community site using Elementor.
 * Plugin URI: https://staxwp.com/go/buddybuilder/
 * Author: StaxWP
 * Author URI: https://staxwp.com
 * Version: 1.5.0
 *
 * Text Domain: stax-buddy-builder
 */

define( 'BPB_VERSION', '1.5.0' );
define( 'BPB_HOOK_PREFIX', 'bpb_' );
define( 'BPB_ADMIN_PREFIX', 'buddy-builder-' );

define( 'BPB_FILE', __FILE__ );
define( 'BPB_BASE_URL', plugins_url( '/', BPB_FILE ) );
define( 'BPB_BASE_PATH', plugin_dir_path( BPB_FILE ) );
define( 'BPB_ASSETS_URL', BPB_BASE_URL . 'assets/' );
define( 'BPB_ADMIN_ASSETS_URL', BPB_BASE_URL . 'admin/assets/' );

if ( file_exists( BPB_BASE_PATH . 'vendor/autoload.php' ) ) {
	require BPB_BASE_PATH . 'vendor/autoload.php';
}

require_once BPB_BASE_PATH . 'core/Plugin.php';

/**
 * Returns the Plugin application instance.
 *
 * @return \Buddy_Builder\Plugin
 * @since 1.0.0
 */
function buddy_builder() {
	return \Buddy_Builder\Plugin::get_instance();
}

function buddybuilder_load_plugin_textdomain() {
	load_plugin_textdomain( 'stax-buddy-builder', false, basename( __DIR__ ) . '/languages/' );
}
add_action( 'plugins_loaded', 'buddybuilder_load_plugin_textdomain' );

/**
 * Initializes the Plugin application.
 *
 * @since 1.0.0
 */
buddy_builder();
