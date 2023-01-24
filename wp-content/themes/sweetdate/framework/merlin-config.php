<?php
/**
 * Merlin WP configuration file.
 *
 * @package @@pkg.name
 * @version @@pkg.version
 * @author  @@pkg.author
 * @license @@pkg.license
 */

if ( ! class_exists( 'Merlin' ) ) {
	return;
}

/**
 * Set directory locations, text strings, and other settings for Merlin WP.
 */
$wizard = new Merlin(
	// Configure Merlin with custom settings.
	$config = array(
		'directory'			=> 'framework/inc',						// Location where the 'merlin' directory is placed.
		'demo_directory'		=> 'lib/inc/demo/',					// Location where the theme demo files exist.
		'merlin_url'			=> 'theme-setup',					// Customize the page URL where Merlin WP loads.
		'child_action_btn_url'		=> 'https://codex.wordpress.org/Child_Themes',  // The URL for the 'child-action-link'.
		'help_mode'			=> false,					// Set to true to turn on the little wizard helper.
		'dev_mode'			=> true,					// Set to true if you're testing or developing.
		'branding'			=> false,					// Set to false to remove Merlin WP's branding.
	),
	// Text strings.
	$strings = array(
		'admin-menu' 			=> esc_html__( 'Theme Setup' , 'sweetdate' ),
		'title%s%s%s%s' 		=> esc_html__( '%s%s Themes &lsaquo; Theme Setup: %s%s' , 'sweetdate' ),

		'return-to-dashboard' 		=> esc_html__( 'Return to the dashboard' , 'sweetdate' ),

		'btn-skip' 			=> esc_html__( 'Skip' , 'sweetdate' ),
		'btn-next' 			=> esc_html__( 'Next' , 'sweetdate' ),
		'btn-start' 			=> esc_html__( 'Start' , 'sweetdate' ),
		'btn-no' 			=> esc_html__( 'Cancel' , 'sweetdate' ),
		'btn-plugins-install' 		=> esc_html__( 'Install' , 'sweetdate' ),
		'btn-child-install' 		=> esc_html__( 'Install' , 'sweetdate' ),
		'btn-content-install' 		=> esc_html__( 'Install' , 'sweetdate' ),
		'btn-import' 			=> esc_html__( 'Import' , 'sweetdate' ),
		'btn-license-activate' 		=> esc_html__( 'Activate' , 'sweetdate' ),

		'welcome-header%s' 		=> esc_html__( 'Welcome to %s' , 'sweetdate' ),
		'welcome-header-success%s' 	=> esc_html__( 'Hi. Welcome back' , 'sweetdate' ),
		'welcome%s' 			=> esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.' , 'sweetdate' ),
		'welcome-success%s' 		=> esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.' , 'sweetdate' ),

		'child-header' 			=> esc_html__( 'Install Child Theme' , 'sweetdate' ),
		'child-header-success' 		=> esc_html__( 'You\'re good to go!' , 'sweetdate' ),
		'child' 			=> esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.' , 'sweetdate' ),
		'child-success%s' 		=> esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.' , 'sweetdate' ),
		'child-action-link' 		=> esc_html__( 'Learn about child themes' , 'sweetdate' ),
		'child-json-success%s' 		=> esc_html__( 'Awesome. Your child theme has already been installed and is now activated.' , 'sweetdate' ),
		'child-json-already%s' 		=> esc_html__( 'Awesome. Your child theme has been created and is now activated.' , 'sweetdate' ),

		'plugins-header' 		=> esc_html__( 'Install Plugins' , 'sweetdate' ),
		'plugins-header-success' 	=> esc_html__( 'You\'re up to speed!' , 'sweetdate' ),
		'plugins' 			=> esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.' , 'sweetdate' ),
		'plugins-success%s' 		=> esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.' , 'sweetdate' ),
		'plugins-action-link' 		=> esc_html__( 'Advanced' , 'sweetdate' ),

		'import-header' 		=> esc_html__( 'Import Content' , 'sweetdate' ),
		'import' 			=> esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.' , 'sweetdate' ),
		'import-action-link' 		=> esc_html__( 'Advanced' , 'sweetdate' ),

		'license-header%s' 		=> esc_html__( 'Activate %s' , 'sweetdate' ),
		'license' 			=> esc_html__( 'Add your license key to activate one-click updates and theme support.' , 'sweetdate' ),
		'license-action-link' 		=> esc_html__( 'More info' , 'sweetdate' ),

		'license-link-1'            	=> wp_kses( sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Explore WordPress', 'sweetdate' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
		'license-link-2'            	=> wp_kses( sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://themebeans.com/contact/', esc_html__( 'Get Theme Support', 'sweetdate' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
		'license-link-3'           	=> wp_kses( sprintf( '<a href="'.admin_url( 'customize.php' ).'" target="_blank">%s</a>', esc_html__( 'Start Customizing', 'sweetdate' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),

		'ready-header' 			=> esc_html__( 'All done. Have fun!' , 'sweetdate' ),
		'ready%s' 			=> esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.' , 'sweetdate' ),
		'ready-action-link' 		=> esc_html__( 'Extras' , 'sweetdate' ),
		'ready-big-button' 		=> esc_html__( 'View your website' , 'sweetdate' ),

		'ready-link-1'            	=> wp_kses( sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Explore WordPress', 'sweetdate' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
		'ready-link-2'            	=> wp_kses( sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://seventhqueen.com/support/', esc_html__( 'Get Theme Support', 'sweetdate' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
		'ready-link-3'           	=> wp_kses( sprintf( '<a href="'.admin_url( 'admin.php?page=sweetdate_options' ).'" target="_blank">%s</a>', esc_html__( 'Start Customizing', 'sweetdate' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
	)
);


function merlin_bp_add_custom_fields() {
	bp_add_custom_fields( false );
}