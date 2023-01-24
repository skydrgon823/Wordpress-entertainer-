<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionRadio extends MobileMenuOption {

	public $defaultSecondarySettings = array(
		'options' => array(),
	);

	/*
	 * Display for options and meta
	 */
	public function display() {
		$this->echoOptionHeader( true );

		echo '<fieldset>';

		foreach ( $this->settings['options'] as $value => $label ) {
			printf('<label for="%s"><input id="%s" type="radio" name="%s" value="%s" %s/> %s</label><br>',
				$this->getID() . $value,
				$this->getID() . $value,
				$this->getID(),
				esc_attr( $value ),
				checked( $this->getValue(), $value, false ),
				$label
			);
		}

		echo '</fieldset>';

		$this->echoOptionFooter( false );
	}
}
