<?php
/**
 * Theme constants
 *
 * @package SweetDate
 */

define( 'KLEO_DOMAIN', 'sweetdate' );
define( 'SQUEEN_THEME_VERSION', '3.5.0' );
define( 'THEME_PATH', get_stylesheet_directory() );
define( 'THEME_URL', get_stylesheet_directory_uri() );
define( 'FRAMEWORK_URL', __DIR__ );
define( 'FRAMEWORK_PATH', __DIR__ );
define( 'FRAMEWORK_HTTP', get_template_directory_uri() . '/framework' );

// Theme options.
if ( ! function_exists( 'sq_option' ) ) {

	// array with theme options.
	$sq_options = get_option( KLEO_DOMAIN );

	/**
	 * Function to get options in front-end
	 *
	 * @param int $option The option we need from the DB
	 * @param string $default If $option doesn't exist in DB return $default value
	 *
	 * @return string
	 */
	function sq_option( $option = false, $default = null ) {
		if ( $option === false ) {
			return false;
		}
		global $sq_options;

		if ( isset( $sq_options[ $option ] ) && $sq_options[ $option ] !== '' ) {
			return $sq_options[ $option ];
		} else {
			if( ! isset( $default ) ) {
				return SQueen::instance()->get_default_option( $option );
			} else {
				return $default;
			}
		}
	}
}
