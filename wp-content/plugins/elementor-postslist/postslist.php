<?php
/**
 * Plugin Name: Elementor Posts List
 * Description: Element plugin for posts by category.
 * Version:     1.0.0
 * Author:      DEXDEL TEAM
 * Text Domain: Elementor posts list
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ELEMENTOR_POSTSLIST__FILE__', __FILE__ );

define( 'ELEMENTOR_POSTSLIST_URL', plugins_url( '/', __FILE__ ) );
define( 'ELEMENTOR_POSTSLIST_PATH', plugin_dir_path( __FILE__ ) );

require_once __DIR__.'/inc/helper.php';

/**
 * Load Postslist Post
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function postslist_load() {
	// Load localization file
	load_plugin_textdomain( 'postslist' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'postslist_fail_load' );
		return;
	}

	// Check required version
	$elementor_version_required = '1.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'postslist_fail_load_out_of_date' );
		return;
	}

	// Require the main plugin file
	require( __DIR__ . '/plugin.php' );
}
add_action( 'plugins_loaded', 'postslist_load' );

function postslist_scripts(){
    wp_enqueue_style('postslist-style',ELEMENTOR_POSTSLIST_URL.'assets/css/style.css');

    /* animated postslist js file*/
    wp_enqueue_script('postslist-js',ELEMENTOR_POSTSLIST_URL.'assets/js/postslist.js', array('jquery'),'1.0', true);
}
add_action( 'wp_enqueue_scripts', 'postslist_scripts' );

function postslist_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Elementor Postslist is not working because you are using an old version of Elementor.', 'postslist' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'postslist' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}