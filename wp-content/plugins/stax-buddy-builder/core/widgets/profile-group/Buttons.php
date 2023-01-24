<?php

namespace Buddy_Builder\Widgets\ProfileGroup;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Buttons extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-profile-group-buttons';
	}

	public function get_title() {
		return esc_html__( 'Buttons', 'stax-buddy-builder' );
	}

	public function get_icon() {
		return 'sq-icon-bp_actions sq-widget-label';
	}

	public function get_categories() {
		return [ 'buddy-builder-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_general_buttons_style',
			[
				'label' => __( 'General Buttons', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_display',
			[
				'label'   => __( 'Display', 'stax-buddy-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline-block',
				'options' => [
					'inline-block' => __( 'Inline', 'stax-buddy-builder' ),
					'block'        => __( 'Block', 'stax-buddy-builder' ),
				],
			]
		);

		$this->add_responsive_control(
			'buttons_inline_align',
			[
				'label'     => __( 'Alignment', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Left', 'stax-buddy-builder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'stax-buddy-builder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'stax-buddy-builder' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action' => 'justify-content: {{VALUE}}; align-items: {{VALUE}}',
				],
				'default'   => '',
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->add_control(
			'button_display_inline',
			[
				'label'     => __( 'Display inline', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'inline-block',
				'condition' => [
					'button_display' => 'inline-block',
				],
				'selectors' => [
					'{{WRAPPER}} .groups-meta' => 'display: flex; flex-wrap: wrap;',
					'{{WRAPPER}} .groups-meta a, {{WRAPPER}} .groups-meta button' => 'display: inline-flex;',
				],
			]
		);

		$this->add_control(
			'button_display_block',
			[
				'label'     => __( 'Display block', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'block',
				'condition' => [
					'button_display' => 'block',
				],
				'selectors' => [
					'{{WRAPPER}} .groups-meta a, {{WRAPPER}} .groups-meta button' => 'display: inline-block;',
				],
			]
		);

		$this->add_responsive_control(
			'buttons_block_align',
			[
				'label'     => __( 'Alignment', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'stax-buddy-builder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'stax-buddy-builder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'stax-buddy-builder' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .groups-meta' => 'text-align: {{VALUE}};',
				],
				'default'   => '',
				'condition' => [
					'button_display' => 'block',
				],
			]
		);

		$this->add_control(
			'button_inline_h_spacing',
			[
				'label'     => __( 'Horizontal Spacing', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action div' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .groups-meta.action div:last-child' => 'margin-right: 0;',
				],
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->add_control(
			'button_inline_v_spacing',
			[
				'label'     => __( 'Vertical Spacing', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action div' => 'margin-top: calc({{SIZE}}{{UNIT}} / 2); margin-bottom: calc({{SIZE}}{{UNIT}} / 2);',
				],
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->add_control(
			'button_block_v_spacing',
			[
				'label'     => __( 'Spacing', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action div' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .groups-meta.action div:last-child' => 'margin-bottom: 0;',
				],
				'condition' => [
					'button_display' => 'block',
				],
			]
		);

		$this->add_control(
			'button_width',
			[
				'label'     => __( 'Width', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action div a, {{WRAPPER}} .groups-meta.action div button' => 'min-width: {{SIZE}}%;',
				],
				'condition' => [
					'button_display' => 'block',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .groups-meta.action button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .groups-meta.action button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'button_text_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action button:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_hover_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_hover_shadow',
				'selector' => '{{WRAPPER}} .groups-meta.action button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .groups-meta.action button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .groups-meta.action button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .groups-meta.action button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_join_group_button_style',
			[
				'label' => __( 'Join Group button', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_join_group_button_style' );

		$this->start_controls_tab(
			'tab_join_group_button_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'join_group_button_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .join-group' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'join_group_button_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .join-group' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'join_group_button_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .join-group' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'join_group_button_box_shadow',
				'selector' => '{{WRAPPER}} .groups-meta.action .join-group',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_join_group_button_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'join_group_button_text_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .join-group:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'join_group_button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .join-group:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'join_group_button_border_hover_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .join-group:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'join_group_button_box_hover_shadow',
				'selector' => '{{WRAPPER}} .groups-meta.action .join-group:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_leave_group_button_style',
			[
				'label' => __( 'Leave Group button', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_leave_group_button_style' );

		$this->start_controls_tab(
			'tab_leave_group_button_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'leave_group_button_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .leave-group' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'leave_group_button_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .leave-group' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'leave_group_button_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .leave-group' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'leave_group_button_box_shadow',
				'selector' => '{{WRAPPER}} .groups-meta.action .leave-group',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_leave_group_button_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'leave_group_button_text_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .leave-group:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'leave_group_button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .leave-group:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'leave_group_button_border_hover_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .leave-group:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'leave_group_button_box_hover_shadow',
				'selector' => '{{WRAPPER}} .groups-meta.action .leave-group:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_request_membership_button_style',
			[
				'label' => __( 'Request Membership button', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_request_membership_button_style' );

		$this->start_controls_tab(
			'tab_request_membership_button_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'request_membership_button_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .request-membership' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'request_membership_button_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .request-membership' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'request_membership_button_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .request-membership' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'request_membership_button_box_shadow',
				'selector' => '{{WRAPPER}} .groups-meta.action .request-membership',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_request_membership_button_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'request_membership_button_text_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .request-membership:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'request_membership_button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .request-membership:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'request_membership_button_border_hover_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .request-membership:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'request_membership_button_box_hover_shadow',
				'selector' => '{{WRAPPER}} .groups-meta.action .request-membership:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_request_sent_button_style',
			[
				'label' => __( 'Request Sent button', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_request_sent_button_style' );

		$this->start_controls_tab(
			'tab_request_sent_button_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'request_sent_button_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .membership-requested' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'request_sent_button_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .membership-requested' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'request_sent_button_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .membership-requested' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'request_sent_button_box_shadow',
				'selector' => '{{WRAPPER}} .groups-meta.action .membership-requested',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_request_sent_button_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'request_sent_button_text_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .membership-requested:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'request_sent_button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .membership-requested:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'request_sent_button_border_hover_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .groups-meta.action .membership-requested:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'request_sent_button_box_hover_shadow',
				'selector' => '{{WRAPPER}} .groups-meta.action .membership-requested:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {
		parent::render();
		if ( bpb_is_elementor_editor() ) {
			bpb_load_template( 'preview/profile-group/buttons' );
		} else {
			bp_nouveau_group_header_buttons();
		}
	}

}
