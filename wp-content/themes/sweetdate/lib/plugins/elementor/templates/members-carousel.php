<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ElementorMembersCarousel extends Widget_Base {
	
	public function get_name() {
		return 'members-carousel';
	}
	public function get_title() {
		return __( 'Members Carousel', 'sweetdate' );
	}
	public function get_icon() {
		return 'eicon-slideshow';
	}
	
	public function get_categories() {
		return [ 'sweetdate-elements' ];
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_members_carousel',
			[
				'label' => __( 'Settings', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'type',
			[
				'label' => __( 'Type', 'sweetdate' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'newest',
				'options' => [
					'newest' => __( 'Newest', 'sweetdate' ),
					'active' => __( 'Most Active', 'sweetdate' ),
					'popular' => __( 'Most Popular', 'sweetdate' ),
				]
			]
		);
		
		$this->add_control(
			'total',
			[
				'label' => __( 'Total', 'sweetdate' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '12', 'sweetdate' ),
				'placeholder' => __( 'Total members', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'width',
			[
				'label' => __( 'Image width', 'sweetdate' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '94', 'sweetdate' ),
				'placeholder' => __( 'Pixels', 'sweetdate' ),
			]
		);
		
		$this->end_controls_section();
	
	}
	protected function render() {
		$settings = $this->get_settings();
		
		$shortcode = sprintf('[kleo_members_carousel type="%s" total="%s" width="%s"]', $settings['type'], $settings['total'], $settings['width'] );
		echo do_shortcode( $shortcode );
		if ( Plugin::$instance->editor->is_edit_mode() ) {
			echo '<script>profilesCarousel();</script>';
		}
	}
	/*protected function _content_template() {
		$this->render();
	}*/
	
}
