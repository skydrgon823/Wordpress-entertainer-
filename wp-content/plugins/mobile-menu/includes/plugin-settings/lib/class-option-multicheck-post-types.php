<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionMulticheckPostTypes extends MobileMenuOptionMulticheck {

	public $defaultSecondarySettings = array(
		'options' => array(),
		'public' => true,
		'value' => 'all',
		'slug' => true,
		'select_all' => false
	);

	/*
	 * Display for options and meta
	 */
	public function display() {

		// Fetch post types.
		$post_types = mm_get_post_types( $this->settings['public'], $this->settings['value'] );

		$this->settings['options'] = array();
		foreach ( $post_types as $post_type ) {

			$slug = $post_type->name;

			$slugname = true == $this->settings['slug'] ? ' (' . $slug . ')' : '';

			$name = $post_type->name;
			if ( ! empty( $post_type->labels->singular_name ) ) {
				$name = $post_type->labels->singular_name . $slugname;
			}

			$this->settings['options'][ $slug ] = $name;
		}

		parent::display();
	}

}
