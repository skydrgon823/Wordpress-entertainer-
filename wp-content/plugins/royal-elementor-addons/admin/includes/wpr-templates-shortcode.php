<?php

namespace WprAddons\Admin\Includes;

use Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPR_Templates_Shortcode setup
 *
 * @since 1.0
 */
class WPR_Templates_Shortcode {

	public function __construct() {
		add_shortcode( 'wpr-template', [ $this, 'shortcode' ] );

		add_action('elementor/element/after_section_start', [ $this, 'extend_shortcode' ], 10, 3 );
	}

	public function shortcode( $attributes = [] ) {
		if ( empty( $attributes['id'] ) ) {
			return '';
		}

		$edit_link = '<span class="wpr-template-edit-btn" data-permalink="'. get_permalink($attributes['id']) .'">Edit Template</span>';

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $attributes['id'] ) . $edit_link;
	}

	public function extend_shortcode( $section, $section_id, $args ) {
		if ( $section->get_name() == 'shortcode' && $section_id == 'section_shortcode' ) {
			$templates_select = [];

			// Get All Templates
			$templates = get_posts( [
				'post_type'   => array( 'elementor_library' ),
				'post_status' => array( 'publish' ),
				'meta_key' 	  => '_elementor_template_type',
				'meta_value'  => ['page', 'section'],
				'numberposts'  => -1
			] );

			if ( ! empty( $templates ) ) {
				foreach ( $templates as $template ) {
					$templates_select[$template->ID] = $template->post_title;
				}
			}

			$section->add_control(
				'select_template' ,
				[
					'label'        => esc_html__( 'Select Template', 'wpr-addons' ),
					'type'         => Elementor\Controls_Manager::SELECT2,
					'options'      => $templates_select,
				]
			);

			// Restore original Post Data
			wp_reset_postdata();
		}
	}

}