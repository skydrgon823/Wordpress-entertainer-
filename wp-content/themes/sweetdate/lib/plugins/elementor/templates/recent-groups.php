<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ElementorRecentGroups extends Widget_Base {
	
	public function get_name() {
		return 'recent-groups';
	}
	public function get_title() {
		return __( 'Recent Groups', 'sweetdate' );
	}
	public function get_icon() {
		return 'eicon-gallery-grid';
	}
	public function get_categories() {
		return [ 'sweetdate-elements' ];
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_recent_groups',
			[
				'label' => __( 'Settings', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'max',
			[
				'label' => __( 'Number of groups', 'sweetdate' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '4', 'sweetdate' )
			]
		);
		
		$this->end_controls_section();
	
	}
	protected function render() {
		$settings = $this->get_settings();
		
		$shortcode = sprintf('[kleo_recent_groups max="%s"]', $settings['max']);
		echo do_shortcode( $shortcode );
		if ( Plugin::$instance->editor->is_edit_mode() ) {
			echo '<script>circularMembers()</script>';
		}
	}
	
}
