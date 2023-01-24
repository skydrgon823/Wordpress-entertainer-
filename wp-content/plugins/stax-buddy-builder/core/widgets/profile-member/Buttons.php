<?php

namespace Buddy_Builder\Widgets\ProfileMember;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Buttons extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-profile-member-buttons';
	}

	public function get_title() {
		return esc_html__( 'Action Buttons', 'stax-buddy-builder' );
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
					'{{WRAPPER}} .member-header-actions' => 'justify-content: {{VALUE}}; align-items: {{VALUE}}',
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
					'{{WRAPPER}} .member-header-actions' => 'display: flex; flex-wrap: wrap;',
					'{{WRAPPER}} .member-header-actions a, {{WRAPPER}} .member-header-actions button' => 'display: inline-flex;',
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
					'{{WRAPPER}} .member-header-actions a, {{WRAPPER}} .member-header-actions button' => 'display: inline-block;',
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
					'{{WRAPPER}} .member-header-actions' => 'text-align: {{VALUE}};',
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
					'{{WRAPPER}} .member-header-actions div'            => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .member-header-actions div:last-child' => 'margin-right: 0;',
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
					'{{WRAPPER}} .member-header-actions div' => 'margin-top: calc({{SIZE}}{{UNIT}} / 2); margin-bottom: calc({{SIZE}}{{UNIT}} / 2);',
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
					'{{WRAPPER}} .member-header-actions div'            => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .member-header-actions div:last-child' => 'margin-bottom: 0;',
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
					'{{WRAPPER}} .member-header-actions div a, {{WRAPPER}} .member-header-actions div button' => 'min-width: {{SIZE}}%;',
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
				'selector' => '{{WRAPPER}} .member-header-actions a, {{WRAPPER}} .member-header-actions button',
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
					'{{WRAPPER}} .member-header-actions a, {{WRAPPER}} .member-header-actions button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions a, {{WRAPPER}} .member-header-actions button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .member-header-actions a, {{WRAPPER}} .member-header-actions button',
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
					'{{WRAPPER}} .member-header-actions a:hover, {{WRAPPER}} .member-header-actions button:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions a:hover, {{WRAPPER}} .member-header-actions button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_hover_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions a:hover, {{WRAPPER}} .member-header-actions button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_hover_shadow',
				'selector' => '{{WRAPPER}} .member-header-actions a:hover, {{WRAPPER}} .member-header-actions button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .member-header-actions a, {{WRAPPER}} .member-header-actions button',
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
					'{{WRAPPER}} .member-header-actions a, {{WRAPPER}} .member-header-actions button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .member-header-actions a, {{WRAPPER}} .member-header-actions button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_add_friendship_button_style',
			[
				'label' => __( 'Add Friendship button', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_add_friendship_button_style' );

		$this->start_controls_tab(
			'tab_add_friendship_button_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'add_friendship_button_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.add' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_friendship_button_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.add' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_friendship_button_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.add' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'add_friendship_button_box_shadow',
				'selector' => '{{WRAPPER}} .member-header-actions .generic-button .friendship-button.add',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_add_friendship_button_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'add_friendship_button_text_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.add:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_friendship_button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.add:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_friendship_button_border_hover_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.add:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'add_friendship_button_box_hover_shadow',
				'selector' => '{{WRAPPER}} .member-header-actions .generic-button .friendship-button.add:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_friendship_request_button_style',
			[
				'label' => __( 'Cancel Friendship Request button', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_friendship_request_button_style' );

		$this->start_controls_tab(
			'tab_friendship_request_button_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'friendship_request_button_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.requested' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'friendship_request_button_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.requested' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'friendship_request_button_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.requested' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'friendship_request_button_box_shadow',
				'selector' => '{{WRAPPER}} .member-header-actions .generic-button .friendship-button.requested',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_friendship_request_button_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'friendship_request_button_text_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.requested:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'friendship_request_button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.requested:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'friendship_request_button_border_hover_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.requested:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'friendship_request_button_box_hover_shadow',
				'selector' => '{{WRAPPER}} .member-header-actions .generic-button .friendship-button.requested:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cancel_friendship_button_style',
			[
				'label' => __( 'Cancel Friendship button', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_cancel_friendship_button_style' );

		$this->start_controls_tab(
			'tab_cancel_friendship_button_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'cancel_friendship_button_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.remove' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cancel_friendship_button_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.remove' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cancel_friendship_button_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.remove' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cancel_friendship_button_box_shadow',
				'selector' => '{{WRAPPER}} .member-header-actions .generic-button .friendship-button.remove',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_cancel_friendship_button_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'cancel_friendship_button_text_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.remove:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cancel_friendship_button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.remove:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cancel_friendship_button_border_hover_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .member-header-actions .generic-button .friendship-button.remove:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cancel_friendship_button_box_hover_shadow',
				'selector' => '{{WRAPPER}} .member-header-actions .generic-button .friendship-button.remove:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {
		parent::render();
		if ( bpb_is_elementor_editor() ) {
			bpb_load_template( 'preview/profile-member/buttons' );
		} else {
			bp_nouveau_member_header_buttons( [ 'container_classes' => [ 'member-header-actions' ] ] );
		}
	}

}
