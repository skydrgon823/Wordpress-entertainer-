<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionColor extends MobileMenuOption {

	/**
	 * Default settings
	 * @var array
	 */
	public $defaultSecondarySettings = array(

		/**
		 * (Optional) If true, an additional control will become available in the color picker for adjusting the alpha/opacity value of the color. You can get rgba colors with the option.
		 *
		 * @since 1.9
		 * @var boolean
		 */
		'alpha' => false,
	);

	function __construct( $settings, $owner ) {
		parent::__construct( $settings, $owner );
		mm_add_action_once( 'admin_enqueue_scripts', array( $this, 'enqueueColorPickerScript' ) );
		mm_add_action_once( 'admin_footer', array( $this, 'startColorPicker' ) );
	}


	/**
	 * Display for options and meta
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function display() {

		$this->echoOptionHeader();

		printf( '<input class="mm-colorpicker" type="text" name="%s" id="%s" value="%s" data-default-color="%s" data-custom-width="0" %s/>',
			esc_attr( $this->getID() ),
			esc_attr( $this->getID() ),
			esc_attr( $this->getValue() ),
			esc_attr( $this->getValue() ),
			! empty( $this->settings['alpha'] ) ? "data-alpha='true'" : '' // Used by wp-color-picker-alpha
		);

		$this->echoOptionFooter();
	}


	/**
	 * Enqueue the colorpicker scripts
	 *
	 * @since 1.9
	 *
	 * @return void
	 */
	public function enqueueColorPickerScript($hook) {
		
		if ( $hook == 'toplevel_page_mobile-menu-options' ) {
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker-alpha', MobileMenuOptions::getURL( '../js/min/wp-color-picker-alpha-min.js', __FILE__ ), array( 'wp-color-picker' ), WP_MOBILE_MENU_VERSION );
		}
	}


	/**
	 * Load the javascript to init the colorpicker
	 *
	 * @since 1.9
	 *
	 * @return void
	 */
	public function startColorPicker($hook) {
	
			
			?>
			<script>

			wpColorPicker_i18n = {"clear":"Clear","defaultString":"Default","pick":"Select Color","current":"Current Color"};
			wpColorPickerL10n = wpColorPicker_i18n;

			jQuery(document).ready(function() {
				'use strict';
				if ( typeof jQuery.fn.wpColorPicker !== 'undefined' ) {
					jQuery('.mm-colorpicker').wpColorPicker();
				}
			});
			</script>
		
	<?php  
	}

}
