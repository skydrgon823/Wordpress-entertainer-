<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ElementorRevslider extends Widget_Base {
	
	public function get_name() {
		return 'revslider';
	}
	public function get_title() {
		return __( 'Revolution Slider', 'sweetdate' );
	}
	public function get_icon() {
		return 'eicon-slider-3d';
	}
	public function get_categories() {
		return [ 'sweetdate-elements' ];
	}
	
	public function get_fields() {
		$sliders = kleo_revslide_sliders();
		if ( empty( $sliders ) ) {
			return [ 'No Sliders found' ];
		} else {
			return $sliders;
		}
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_register_form',
			[
				'label' => __( 'Settings', 'sweetdate' ),
			]
		);
		
		
		$this->add_control(
			'slider',
			[
				'label' => __( 'Select slider', 'sweetdate' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_fields(),
			]
		);
		
		$this->end_controls_section();
	
	}
	protected function render() {
		$settings = $this->get_settings();
	
		if ( $settings['slider'] ) {
			if ( class_exists( 'RevSlider' ) ) {
				echo do_shortcode( '[rev_slider alias="' . $settings['slider'] . '"]' );
			} else {
				echo esc_html__( 'Revolution Slider plugin needs to be installed', 'sweetdate' );
			}
		}
	}
	
}
