<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionSeparator extends MobileMenuOption {

	/*
	 * Display for options and meta
	 */
	public function display() {
		$this->echoOptionHeader();
		?>
		<hr />
		<?php
		$this->echoOptionFooter( false );
	}
}
