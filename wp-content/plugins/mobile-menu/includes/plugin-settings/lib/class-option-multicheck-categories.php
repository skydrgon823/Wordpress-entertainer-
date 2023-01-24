<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionMulticheckCategories extends MobileMenuOptionMulticheck {

	public $defaultSecondarySettings = array(
		'options' => array(),
		'orderby' => 'name',
		'order' => 'ASC',
		'taxonomy' => 'category',
		'hide_empty' => false,
		'show_count' => false,
		'select_all' => false
	);

	/*
	 * Display for options and meta
	 */
	public function display() {
		$args = array(
			'orderby' => $this->settings['orderby'],
			'order' => $this->settings['order'],
			'taxonomy' => $this->settings['taxonomy'],
			'hide_empty' => $this->settings['hide_empty'] ? '1' : '0',
		);

		$categories = get_categories( $args );

		$this->settings['options'] = array();
		foreach ( $categories as $category ) {
			$this->settings['options'][ $category->term_id ] = $category->name . ( $this->settings['show_count'] ? ' (' . $category->count . ')' : '' );
		}

		parent::display();
	}

}
