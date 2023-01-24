<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ElementorButtonVideo extends Widget_Base {
	
	public function get_name() {
		return 'video-button';
	}
	public function get_title() {
		return __( 'Button Video', 'sweetdate' );
	}
	public function get_icon() {
		return 'eicon-button';
	}
	
	public function get_categories() {
		return [ 'sweetdate-elements' ];
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_posts_carousel',
			[
				'label' => __( 'Settings', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'url',
			[
				'label' => __( 'URL', 'sweetdate' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'http://www.youtube.com/embed/FtquI061bag',
				'placeholder' => __( 'Youtube embed video link', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'style',
			[
				'label'   => __( 'Style', 'sweetdate' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'standard' => 'Primary color',
					'secondary' => 'Secondary color',
					'success' => 'Green',
					'alert' => 'Red'
				)
			]
		);
		
		$this->add_control(
			'size',
			[
				'label'   => __( 'Size', 'sweetdate' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'standard' => 'Standard',
					'large' => 'Large',
					'medium' => 'Medium',
					'small' => 'Small',
					'tiny' => 'Tiny'
				)
			]
		);
		
		$this->add_control(
			'round',
			[
				'label'   => __( 'Round', 'sweetdate' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'0' => 'No',
					'radius' => 'A bit Rounded',
					'round' => 'Rounded'
				),
			]
		);
		
		$this->add_control(
			'icon',
			[
				'label'   => __( 'Icon', 'sweetdate' ),
				'type'    => Controls_Manager::ICON,
				'icons'   => awesome_array(),
			]
		);
		
		$this->add_control(
			'icon_position',
			[
				'label'   => __( 'Icon position', 'sweetdate' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'before' => 'Before text',
					'after' => 'After text'
				),
			]
		);
		
		$this->add_control(
			'content',
			[
				'label' => __( 'Button text', 'sweetdate' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Button text', 'sweetdate' ),
			]
		);
		
		$this->end_controls_section();
	
	}
	protected function render() {
		$settings = $this->get_settings();
		
		$shortcode = sprintf( '[kleo_button_video url="%s" style="%s" size="%s" round="%s" icon="%s,%s"]%s[/kleo_button_video]',
			$settings['url'],
			$settings['style'],
			$settings['size'],
			$settings['round'],
			$settings['icon'],
			$settings['icon_position'],
			$settings['content']
		);
		echo do_shortcode( $shortcode );
	}
	
}
