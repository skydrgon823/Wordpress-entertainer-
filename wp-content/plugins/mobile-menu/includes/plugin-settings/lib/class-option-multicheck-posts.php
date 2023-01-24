<?php

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
class MobileMenuOptionMulticheckPosts extends MobileMenuOptionMulticheck {

	public $defaultSecondarySettings = array(
		'options' => array(),
		'post_type' => 'post',
		'num' => -1,
		'post_status' => 'any',
		'orderby' => 'post_date',
		'order' => 'DESC',
		'select_all' => false
	);

	/*
	 * Display for options and meta
	 */
	public function display() {
		$args = array(
			'post_type' => $this->settings['post_type'],
			'posts_per_page' => $this->settings['num'],
			'post_status' => $this->settings['post_status'],
			'orderby' => $this->settings['orderby'],
			'order' => $this->settings['order'],
		);

		$posts = get_posts( $args );

		$this->settings['options'] = array();
		foreach ( $posts as $post ) {
			$title = $post->post_title;
			if ( empty( $title ) ) {
				$title = sprintf( __( 'Untitled %s', 'mobile-menu' ), '(ID #' . $post->ID . ')' );
			}
			$this->settings['options'][ $post->ID ] = $title;
		}

		parent::display();
	}

}
