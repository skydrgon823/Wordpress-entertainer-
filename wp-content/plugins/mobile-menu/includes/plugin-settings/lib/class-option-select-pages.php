<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionSelectPages extends MobileMenuOptionSelect {

	public $defaultSecondarySettings = array(
		'default' => '0', // show this when blank
	);

	private static $allPages;

	/**
	 * Creates the options for the select input. Puts the options in $this->settings['options']
	 *
	 * @since 1.11
	 *
	 * @return void
	 */
	public function create_select_options() {
		// Remember the pages so as not to perform any more lookups
		if ( ! isset( self::$allPages ) ) {
			self::$allPages = get_pages();
		}

		$this->settings['options'] = array(
			'' => '— ' . __( 'Select', 'mobile-menu' ) . ' —'
		);

		// Print all the other pages
		foreach ( self::$allPages as $page ) {
			$title = $page->post_title;
			if ( empty( $title ) ) {
				$title = sprintf( __( 'Untitled %s', 'mobile-menu' ), '(ID #' . $page->ID . ')' );
			}
			$this->settings['options'][ $page->ID ] = $title;
		}
	}

	/*
	 * Display for options and meta
	 */
	public function display() {
		$this->create_select_options();
		parent::display();
	}

}
