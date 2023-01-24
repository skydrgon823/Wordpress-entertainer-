<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionMulticheck extends MobileMenuOption {

	public $defaultSecondarySettings = array(
		'options' => array(),
		'select_all' => false,
	);

	/**
	 * Constructor
	 *
	 * @param array  $settings Option settings
	 * @param string $owner    Namespace
	 *
	 * @since 1.11
	 */
	function __construct( $settings, $owner ) {
		parent::__construct( $settings, $owner );

		mm_add_action_once( 'admin_enqueue_scripts', array( $this, 'load_select_scripts' ) );
		mm_add_action_once( 'customize_controls_enqueue_scripts', array( $this, 'load_select_scripts' ) );

	}


	/*
	 * Display for options and meta
	 */
	public function display() {
		$this->echoOptionHeader( true );

		echo '<fieldset>';

		$savedValue = $this->getValue();

		if ( ! empty( $this->settings['select_all'] ) ) {
			$select_all_label = __( 'Select All' );
			if ( is_string(  $this->settings['select_all'] ) ) {
				$select_all_label = $this->settings['select_all'];
			}
			printf('<label style="margin-bottom: 1em !important;"><input class="tf_checkbox_selectall" type="checkbox" /> %s </label><br>',
				esc_html( $select_all_label )
			);
		}

		foreach ( $this->settings['options'] as $value => $label ) {

			printf('<label for="%s"><input id="%s" type="checkbox" name="%s[]" value="%s" %s/> %s</label><br>',
				$this->getID() . $value,
				$this->getID() . $value,
				$this->getID(),
				esc_attr( $value ),
				checked( in_array( $value, $savedValue ), true, false ),
				$label
			);
		}

		echo '</fieldset>';

		$this->echoOptionFooter( false );

	}

	/**
	 * Load the multicheck-selectall script
	 *
	 * @since 1.11
	 * @return void
	 */
	public function load_select_scripts() {

		wp_enqueue_script( 'mm-multicheck-select-all', MobileMenuOptions::getURL( '../js/multicheck-select-all.js', __FILE__ ), array( 'jquery' ), WP_MOBILE_MENU_VERSION, true );

	}

	public function cleanValueForSaving( $value ) {
		if ( empty( $value ) ) {
			return array();
		}
		if ( is_serialized( $value ) ) {
			return $value;
		}
		// CSV
		if ( is_string( $value ) ) {
			$value = explode( ',', $value );
		}
		return serialize( $value );
	}

	public function cleanValueForGetting( $value ) {
		if ( empty( $value ) ) {
			return array();
		}
		if ( is_array( $value ) ) {
			return $value;
		}
		if ( is_serialized( $value ) ) {
			return unserialize( $value );
		}
		if ( is_string( $value ) ) {
			return explode( ',', $value );
		}
	}

}
