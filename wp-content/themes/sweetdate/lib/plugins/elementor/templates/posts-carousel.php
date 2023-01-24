<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ElementorPostsCarousel extends Widget_Base {
	
	public function get_name() {
		return 'posts-carousel';
	}
	public function get_title() {
		return esc_html__( 'Posts Carousel', 'sweetdate' );
	}
	public function get_icon() {
		return 'eicon-slideshow';
	}
	
	public function get_categories() {
		return [ 'sweetdate-elements' ];
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_posts_carousel',
			[
				'label' => esc_html__( 'Settings', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'cat',
			[
				'label' => esc_html__( 'Categories', 'sweetdate' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Comma separated categories names', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'limit',
			[
				'label' => esc_html__( 'Limit', 'sweetdate' ),
				'type' => Controls_Manager::TEXT,
				'default' => '9',
				'placeholder' => esc_html__( 'Number of posts', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'post_types',
			[
				'label' => esc_html__( 'Post types', 'sweetdate' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'post',
			]
		);
		
		$this->add_control(
			'post_formats',
			[
				'label'   => esc_html__( 'Post formats', 'sweetdate' ),
				'type'    => Controls_Manager::SELECT2,
				'multiple' => 'true',
				'options' => [
					'image'  => esc_html__( 'Image', 'sweetdate' ),
					'gallery' => esc_html__( 'Gallery', 'sweetdate' ),
					'video'  => esc_html__( 'Video', 'sweetdate' ),
					'audio'  => esc_html__( 'Audio', 'sweetdate' ),
				]
			]
		);
		
		$this->end_controls_section();
	
	}
	protected function render() {
		$settings = $this->get_settings();
		
		$post_formats = 'all';
		if ( ! empty( $settings['post_formats'] ) ) {
			$post_formats = implode( ',', $settings['post_formats'] );
		}
		
		$shortcode = sprintf('[kleo_posts_carousel cat="%s" limit="%s" post_types="%s" post_formats="%s"]', $settings['cat'], $settings['limit'], $settings['post_types'], $post_formats );
		echo do_shortcode( $shortcode );
		if ( Plugin::$instance->editor->is_edit_mode() ) {
			echo '<script>storiesCarousel();</script>';
		}
	}
	
}
