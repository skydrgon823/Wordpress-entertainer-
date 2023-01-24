<?php
/**
Plugin Name: Buddypress Shortcodes
Plugin URI:  http://wordpress.webimetry.com/plugins/buddypress-shortcodes/
Description: Buddypress Shortcodes plugin adds functionality of Shortcodes to Buddypress.
Version: 1.2
Author: Webimetry Solutions
Author URI: http://www.webimetry.com/
License: GPL2
*/

require_once( plugin_dir_path( __FILE__ ) .'/inc/settings.php' );

function webim_buddypress_shortcodes_init() {
/* ============================================================= */
/* Set the paths needed by the plugin. */
/* ============================================================= */	
require_once( plugin_dir_path( __FILE__ ) .'/inc/shortcodes.php' );


/* ============================================================= */
/* Add action */
/* ============================================================= */
add_action('media_buttons', 'webim_form',15);
add_action('admin_enqueue_scripts', 'webim_admin_enqueue');
add_action('wp_print_styles', 'owl_css_register_styles');
add_action('wp_print_scripts', 'owl_js_register_scripts');

/* ============================================================= */
/* Admin Styling */
/* ============================================================= */

function webim_admin_enqueue() {
// Register admin scripts and styles
wp_register_style ('shortcodes-admin-styling', plugin_dir_url( __FILE__ ) . 'css/admin-style.css');
wp_register_script ('shortcodes-admin-script', plugin_dir_url( __FILE__ ) . 'js/admin-custom.js');

// Enqueue admin scripts and styles
wp_enqueue_style ('shortcodes-admin-styling');
wp_enqueue_script('shortcodes-admin-script');	
}

// Create media button and menu
function webim_form(){

	if( is_admin() ) {

		$screen = get_current_screen();
		
		if( $screen->base !== 'dashboard' ) {
	
			$out  = '<ul class="webim-shortcode-menu">' . "\n";
			$out .= '<li><a href="'. plugin_dir_url( __FILE__ ) .'inc/form.php?width=640&height=450" class="thickbox"><img src="' . plugins_url( 'inc/images/icon.png', __FILE__ ) . '" > </a></li>' . "\n";
			$out .= '</ul>';
				 echo $out;
						
		}
	}
}

	// END media button	

/* ============================================================= */
/* OWL CSS Files for Front End */
/* ============================================================= */	 
function owl_css_register_styles() {
	wp_register_style('css-owl', plugins_url('/buddypress-shortcodes/css/owl.css'));
	wp_enqueue_style('css-owl');
}
/* ============================================================= */
/* OWL JS Files for Front End*/
/* ============================================================= */	 
function owl_js_register_scripts() {
	wp_register_script('js-owl', plugins_url('/buddypress-shortcodes/js/owl.js'));
    wp_enqueue_script('js-owl');
}
}
add_action( 'bp_init', 'webim_buddypress_shortcodes_init' );
?>