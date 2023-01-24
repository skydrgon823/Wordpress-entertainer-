<?php
/**
 * Custom option
 *
 * @package Mobile Menu Settings
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}

/**
 * Custom option class
 *
 * @since 1.0
 */
class MobileMenuOptionCustom extends MobileMenuOption {

	/**
	 * Default settings specific to this option
	 * @var array
	 */
	public $defaultSecondarySettings = array(
		'custom' => '', // Custom HTML
	);

	/**
	 * Display for options and meta
	 */
	public function display() {
		if ( ! empty( $this->settings['name'] ) ) {

			$this->echoOptionHeader();
			echo $this->settings['custom'];
			$this->echoOptionFooter( false );

		} else {

			$this->echoOptionHeaderBare();
			echo $this->settings['custom'];
			$this->echoOptionFooterBare( false );

		}
	}

}
