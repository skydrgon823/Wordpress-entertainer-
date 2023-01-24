<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionTextarea extends MobileMenuOption {

	public $defaultSecondarySettings = array(
		'placeholder' => '', // show this when blank
		'is_code' => false, // if true, a more code-like font will be used
		'sanitize_callbacks' => array(),
	);

	/*
	 * Display for options and meta
	 */
	public function display() {
		$this->echoOptionHeader( true );
		printf("<textarea class='large-text %s' name=\"%s\" placeholder=\"%s\" id=\"%s\" rows='10' cols='50'>%s</textarea>",
			$this->settings['is_code'] ? 'code' : '',
			$this->getID(),
			$this->settings['placeholder'],
			$this->getID(),
			esc_textarea( stripslashes( $this->getValue() ) )
		);
		$this->echoOptionFooter( false );
	}

	public function cleanValueForSaving( $value ) {
		if ( ! empty( $this->settings['sanitize_callbacks'] ) ) {
			foreach ( $this->settings['sanitize_callbacks'] as $callback ) {
				$value = call_user_func_array( $callback, array( $value, $this ) );
			}
		}

		return $value;
	}

}
