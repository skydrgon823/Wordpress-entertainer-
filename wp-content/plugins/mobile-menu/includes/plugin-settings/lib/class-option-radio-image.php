<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionRadioImage extends MobileMenuOption {

	public $defaultSecondarySettings = array(
		'options' => array(),
		'is_font_icon' => false
	);

	function __construct( $settings, $owner ) {
		parent::__construct( $settings, $owner );
	}

	/*
	 * Display for options and meta
	 */
	public function display() {
		if ( empty( $this->settings['options'] ) ) {
			return;
		}
		if ( $this->settings['options'] == array() ) {
			return;
		}

		$this->echoOptionHeader();

		// Get the correct value, since we are accepting indices in the default setting
		$value = $this->getValue();
		if ($this->settings['is_font_icon']){
			$template = '<label id="%s"><input id="%s" type="radio" name="%s" value="%s" %s/> <span class="mm-radio-image-font-icon %s"></span></label>';
		} else {
			$template = '<label id="%s"><input id="%s" type="radio" name="%s" value="%s" %s/> <img src="%s" /></label>';
		}

		// print the images
		foreach ( $this->settings['options'] as $key => $imageURL ) {
			if ( $value == '' ) {
				$value = $key;
			}
			printf( $template,
				$this->getID() . $key,
				$this->getID() . $key,
				$this->getID(),
				esc_attr( $key ),
				checked( $value, $key, false ),
				esc_attr( $imageURL )
			);
		}

		$this->echoOptionFooter();
	}

	// Save the index of the selected option
	public function cleanValueForSaving( $value ) {
		if ( ! is_array( $this->settings['options'] ) ) {
			return $value;
		}
		// if the key above is zero, we will get a blank value
		if ( $value == '' ) {
			$keys = array_keys( $this->settings['options'] );
			return $keys[0];
		}
		return $value;
	}

	// The value we should return is a key of one of the options
	public function cleanValueForGetting( $value ) {
		if ( ! empty( $this->settings['options'] ) && $value == '' ) {
			$keys = array_keys( $this->settings['options'] );
			return $keys[0];
		}
		return $value;
	}

}
