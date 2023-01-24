<?php

namespace Sweetcore;

/*
 * Post types creation class
 * 
 */


class Post_Types {

	private $labels;

	public function __construct() {
		$this->labels                 = array( 'testimonials' );
		$this->labels['testimonials'] = array(
			'singular' => esc_html__( 'Testimonial', 'sweetdate' ),
			'plural'   => esc_html__( 'Testimonials', 'sweetdate' ),
			'menu'     => esc_html__( 'Testimonials', 'sweetdate' )
		);

		add_action( 'init', array( $this, 'setup_testimonials_post_type' ), 100 );
	}

	/**
	 * Setup testimonials post type
	 * @since  1.0
	 * @return void
	 */
	public function setup_testimonials_post_type() {

		$args = array(
			'labels'             => $this->get_labels( 'testimonials', $this->labels['testimonials']['singular'], $this->labels['testimonials']['plural'], $this->labels['testimonials']['menu'] ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => esc_attr( apply_filters( 'kleo_testimonials_slug', 'testimonials' ) ) ),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20, // Below "Pages"
			'supports'           => array( 'title', 'editor' )
		);

		register_post_type( 'kleo-testimonials', $args );
	} // End setup_testimonials_post_type()


	/**
	 * Create the labels to be used in post type creation
	 * @since  1.0
	 *
	 * @param  string $token The post type for which to setup labels
	 * @param  string $singular Label for singular post type
	 * @param  string $plural Label for plural post type
	 * @param  string $menu Menu item label
	 *
	 * @return array            Labels array
	 */
	private function get_labels( $token, $singular, $plural, $menu ) {
		$labels = array(
			'name'               => sprintf( _x( '%s', 'post type general name', 'sweetdate' ), $plural ),
			'singular_name'      => sprintf( _x( '%s', 'post type singular name', 'sweetdate' ), $singular ),
			'add_new'            => sprintf( esc_html__( 'Add New %s', 'sweetdate' ), $singular ),
			'add_new_item'       => sprintf( esc_html__( 'Add New %s', 'sweetdate' ), $singular ),
			'edit_item'          => sprintf( esc_html__( 'Edit %s', 'sweetdate' ), $singular ),
			'new_item'           => sprintf( esc_html__( 'New %s', 'sweetdate' ), $singular ),
			'all_items'          => sprintf( esc_html__( 'All %s', 'sweetdate' ), $plural ),
			'view_item'          => sprintf( esc_html__( 'View %s', 'sweetdate' ), $singular ),
			'search_items'       => sprintf( esc_html__( 'Search %s', 'sweetdate' ), $plural ),
			'not_found'          => sprintf( esc_html__( 'No %s found', 'sweetdate' ), strtolower( $plural ) ),
			'not_found_in_trash' => sprintf( esc_html__( 'No %s found in Trash', 'sweetdate' ), strtolower( $plural ) ),
			'parent_item_colon'  => '',
			'menu_name'          => $menu
		);

		return $labels;
	} // End get_labels()

}
