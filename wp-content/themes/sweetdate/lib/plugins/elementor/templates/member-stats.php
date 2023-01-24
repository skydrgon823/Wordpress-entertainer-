<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ElementorMemberStats extends Widget_Base {
	
	public function get_name() {
		return 'member-stats';
	}
	public function get_title() {
		return __( 'Member Stats', 'sweetdate' );
	}
	public function get_icon() {
		return 'eicon-number-field';
	}
	public function get_categories() {
		return [ 'sweetdate-elements' ];
	}
	
	public function get_fields() {
		return sq_bp_fields_array();
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_member_stats',
			[
				'label' => __( 'Settings', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'field_id',
			[
				'label' => __( 'Field Name', 'sweetdate' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_fields(),
				'title' => esc_html__( 'Select the field to get statistics by', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'value',
			[
				'label' => __( 'Field Value', 'sweetdate' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '', 'sweetdate' ),
				'title' => __( 'Value to get same members by. Example: Rome if the Field name is City .', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'online',
			[
				'label' => __( 'Get online users only', 'sweetdate' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'sweetdate' ),
				'label_on' => __( 'On', 'sweetdate' ),
				'default' => '',
				'return_value' => '1',
				'title' => __( 'Enable to count online users only', 'sweetdate' ),
			]
		);
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_member_stats_style',
			[
				'label' => __( 'Style', 'sweetdate' ),
			]
		);
		
		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);
		
		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#8b8b8b',
				'selectors' => [
					'{{WRAPPER}} .span.member-statistic' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Typography', 'elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} span.member-statistic',
			]
		);
		
		
		$this->end_controls_section();
		
	}
	protected function render() {
		$settings = $this->get_settings();
		$online = isset( $settings['online'] ) && $settings['online'] == 1 ? true : false;
		
		if ( ! function_exists( 'bp_is_active' ) ) {
			echo esc_html__( 'BuddyPress needs to be installed', 'sweetdate' );
			return;
		}
		
		echo '<span class="member-statistic">';
		
		if ( $online ) {
			echo bp_get_online_users( $settings['value'], $settings['field_id'] );
		} else {
			if ( $settings['field_id'] && $settings['value'] ) {
				echo sq_bp_member_stats( $settings['field_id'], $settings['value'] );
			} else {
				//get total member count
				echo bp_get_total_member_count();
			}
		}
		echo '</span>';
	}
	
}
