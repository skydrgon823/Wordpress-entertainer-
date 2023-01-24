<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 */

if ( ! function_exists( 'kleo_metaboxes' ) ) {

	add_filter( 'cmb_meta_boxes', 'kleo_metaboxes' );
	/**
	 * Define the metabox and field configurations.
	 *
	 * @param  array $meta_boxes
	 *
	 * @return array
	 */
	function kleo_metaboxes( array $meta_boxes ) {

		if ( ! function_exists( 'kleo_revslide_sliders' ) ) {
			return;
		}

		// Start with an underscore to hide fields from custom fields list
		$prefix = '_kleo_';

		$revslides = array();
		foreach ( kleo_revslide_sliders() as $k => $v ) {
			$revslides[ $k ]['name']  = $v;
			$revslides[ $k ]['value'] = $k;
		}

		$meta_boxes[] = array(
			'id'         => 'general_settings',
			'title'      => 'General settings',
			'pages'      => array( 'post', 'page' ), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			'fields'     => array(
				array(
					'name' => 'Media',
					'desc' => '',
					'id'   => 'kleomedia',
					'type' => 'tab'
				),
				array(
					'name'  => 'Slider',
					'desc'  => 'Upload an image or enter an URL.',
					'id'    => $prefix . 'slider',
					'type'  => 'file_repeat',
					'allow' => 'url'
				),
				array(
					'name' => 'Video embed',
					'desc' => wp_kses_post( __( 'Enter a youtube or vimeo URL. Supports services listed at <a target="_blank" href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'sweetdate' ) ),
					'id'   => $prefix . 'embed',
					'type' => 'oembed',
				),
				array(
					'name' => 'Audio',
					'desc' => esc_html__( 'Upload you audio file', 'sweetdate' ),
					'id'   => $prefix . 'audio',
					'type' => 'file',
				),
				array(
					'name' => 'Display settings',
					'desc' => '',
					'id'   => 'kleodisplay',
					'type' => 'tab'
				),
				array(
					'name'  => 'Hide the title',
					'desc'  => 'Check to hide the title when displaying the post/page',
					'id'    => $prefix . 'title_checkbox',
					'type'  => 'checkbox',
					'value' => '1'
				),
				array(
					'name'  => 'Centered text',
					'desc'  => 'Check to have centered text on this page',
					'id'    => $prefix . 'centered_text',
					'type'  => 'checkbox',
					'value' => '1'
				),
				array(
					'name'  => 'Hide post meta',
					'desc'  => 'Check to hide the post meta when displaying a post',
					'id'    => $prefix . 'meta_checkbox',
					'type'  => 'checkbox',
					'value' => '1'
				),
				array(
					'name' => 'Header settings',
					'desc' => '',
					'id'   => 'kleoheader',
					'type' => 'tab'
				),
				array(
					'name'  => 'Hide breadcrumb',
					'desc'  => 'Check to hide the breadcrumb section',
					'id'    => $prefix . 'hide_breadcrumb',
					'type'  => 'checkbox',
					'value' => '1'
				),
				array(
					'name'    => 'Revolution slider',
					'desc'    => 'Select the Slider to show in header',
					'id'      => $prefix . 'revolution_slider',
					'type'    => 'select',
					'options' => $revslides
				),
				array(
					'name'  => 'Transparent header',
					'desc'  => 'If enabled, the page content will start from the very top of the page and the header will go over the content.',
					'id'    => $prefix . 'rev_transparent',
					'type'  => 'checkbox',
					'value' => '1'
				),

			),
		);

		$meta_boxes[] = array(
			'id'         => 'testimonials_metabox',
			'title'      => esc_html__( 'Testimonial - Author description', 'sweetdate' ),
			'pages'      => array( 'kleo-testimonials' ), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => false, // Show field names on the left
			'fields'     => array(
				array(
					'name' => 'Author description',
					'desc' => '',
					'id'   => $prefix . 'author_description',
					'type' => 'text',
				),
			)
		);

		// Add other metaboxes as needed

		return $meta_boxes;
	}
}

if ( ! function_exists( 'initialize_meta_boxes' ) ) {
	add_action( 'init', 'initialize_meta_boxes', 9998 );

	/**
	 * Initialize the metabox class.
	 */
	function initialize_meta_boxes() {

		if ( ! class_exists( 'cmb_Meta_Box' ) ) {
			require_once 'init.php';
		}

	}
}
