<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ElementorTopMembers extends Widget_Base {
	
	public function get_name() {
		return 'top-members';
	}
	public function get_title() {
		return __( 'Top members', 'sweetdate' );
	}
	public function get_icon() {
		return 'eicon-person';
	}
	public function get_categories() {
		return [ 'sweetdate-elements' ];
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_top_members',
			[
				'label' => __( 'Color settings', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'tabs_color',
			[
				'label' => __( 'Member tabs color', 'sweetdate' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'div#main {{WRAPPER}} a.members-switch' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Member title color', 'sweetdate' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'div#main {{WRAPPER}} .item-title a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'meta_color',
			[
				'label' => __( 'Meta color', 'sweetdate' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'div#main {{WRAPPER}} .item-meta span.activity' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
            'per_page',
            [
                'label' => __( 'Total Members', 'sweetdate' ),
                'type' => Controls_Manager::TEXT,
                'default' => '6'
            ]
        );
        
		$this->end_controls_section();
	
	}
	protected function render() {
        $settings = $this->get_settings();

        if ( function_exists( 'bp_is_active' ) ) {
			echo do_shortcode('[kleo_top_members number='.$settings['per_page'].']');
		} else {
			echo esc_html__( 'BuddyPress needs to be installed', 'sweetdate' );
		}
		
	}
	
}
