<?php
/**
 * Plugin Name: SweetDate Core
 * Description: Adds core features to SweetDate theme.

 * Author: SeventhQueen.com
 * Version: 1.1.0
 * Author URI: https://seventhqueen.com
 *
 * Text Domain: sweetdate-core
 *
 * @package Sweetdate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SWEETCORE_VERSION', '1.1.0' );

define( 'SWEETCORE__FILE__', __FILE__ );
define( 'SWEETCORE_PLUGIN_BASE', plugin_basename( SWEETCORE__FILE__ ) );
define( 'SWEETCORE_PATH', plugin_dir_path( SWEETCORE__FILE__ ) );
define( 'SWEETCORE_URL', plugins_url( '/', SWEETCORE__FILE__ ) );

define( 'SWEETCORE_MODULES_PATH', plugin_dir_path( SWEETCORE__FILE__ ) . 'inc/modules' );
define( 'SWEETCORE_ASSETS_PATH', SWEETCORE_PATH . 'assets/' );
define( 'SWEETCORE_ASSETS_URL', SWEETCORE_URL . 'assets/' );

add_action( 'plugins_loaded', 'sweetcore_load_plugin_textdomain' );

require SWEETCORE_PATH . 'inc/Plugin.php';

/**
 * Load textdomain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function sweetcore_load_plugin_textdomain() {
	load_plugin_textdomain( 'sweetdate-core' );
}
