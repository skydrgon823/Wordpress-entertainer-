<?php

namespace Buddy_Builder\Widgets\Sitewide;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Form extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-sitewide-form';
	}

	public function get_title() {
		return esc_html__( 'Form', 'stax-buddy-builder' );
	}

	public function get_icon() {
		return 'sq-icon-bp_chat sq-widget-label';
	}

	public function get_categories() {
		return [ 'buddy-builder-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'container_section',
			[
				'label' => __( 'Form Container', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'container_background',
			[
				'label'     => __( 'Background', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bp-nouveau-activity-form' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} #bp-nouveau-activity-form',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} #bp-nouveau-activity-form',
			]
		);

		$this->add_control(
			'container_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #bp-nouveau-activity-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #bp-nouveau-activity-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_margin',
			[
				'label'      => __( 'Margin', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #bp-nouveau-activity-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'avatar_section',
			[
				'label' => __( 'Avatar', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'avatar_box_shadow',
				'selector' => '{{WRAPPER}} #bp-nouveau-activity-form #whats-new-avatar img',
			]
		);

		$this->add_control(
			'avatar_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #bp-nouveau-activity-form #whats-new-avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'textarea_section',
			[
				'label' => __( 'Textarea', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'textarea_box_shadow',
				'selector' => '{{WRAPPER}} #whats-new-textarea textarea',
			]
		);

		$this->start_controls_tabs( 'textarea_style_tabs' );

		$this->start_controls_tab(
			'textarea_style_normal_tab',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'textarea_background',
			[
				'label'     => __( 'Background', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #whats-new-textarea textarea' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'textarea_text',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #whats-new-textarea textarea' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'textarea_placeholder',
			[
				'label'     => __( 'Placeholder Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #whats-new-textarea textarea::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'textarea_style_focus_tab',
			[
				'label' => __( 'Focus', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'textarea_focus_background',
			[
				'label'     => __( 'Background', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #whats-new-textarea textarea:focus' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'textarea_focus_text',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #whats-new-textarea textarea:focus' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'textarea_focus_placeholder',
			[
				'label'     => __( 'Placeholder Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #whats-new-textarea textarea:focus::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'textarea_focus_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #whats-new-textarea textarea:focus' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'textarea_border',
				'selector' => '{{WRAPPER}} #whats-new-textarea textarea',
			]
		);

		$this->add_control(
			'textarea_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #whats-new-textarea textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'textarea_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #whats-new-textarea textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'post_in_section',
			[
				'label' => __( 'Post In Option', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Text Typography', 'stax-buddy-builder' ),
				'name'     => 'post_in_select_typography',
				'selector' => '{{WRAPPER}} #whats-new-post-in-box select, {{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete',
			]
		);

		$this->start_controls_tabs( 'post_in_select_state_style' );

		$this->start_controls_tab(
			'post_in_select_normal_style',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'post_in_select_bg_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #whats-new-post-in-box select'                       => 'background-color: {{VALUE}};',
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_in_select_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #whats-new-post-in-box select'                       => 'color: {{VALUE}};',
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_in_placeholder_color',
			[
				'label'     => __( 'Placeholder Input Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete::placeholder'           => 'color: {{VALUE}};',
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete:-ms-input-placeholder'  => 'color: {{VALUE}};',
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_in_select_arrow_color',
			[
				'label'     => __( 'Select Arrow Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #whats-new-post-in-box select' => 'background-image: linear-gradient(45deg, transparent 50%, {{VALUE}} 50%), linear-gradient(135deg, {{VALUE}} 50%, transparent 50%);',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'post_in_select_shadow',
				'selector' => '{{WRAPPER}} #whats-new-post-in-box select, {{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'post_in_select_focus_style',
			[
				'label' => __( 'Focus', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'post_in_select_focus_bg_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #whats-new-post-in-box select:focus'                       => 'background-color: {{VALUE}};',
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_in_select_focus_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #whats-new-post-in-box select:focus'                       => 'color: {{VALUE}};',
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_in_placeholder_focus_color',
			[
				'label'     => __( 'Placeholder Input Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete:focus::placeholder'           => 'color: {{VALUE}};',
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete:focus:-ms-input-placeholder'  => 'color: {{VALUE}};',
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete:focus::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_in_select_arrow_focus_color',
			[
				'label'     => __( 'Select Arrow Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #whats-new-post-in-box select:focus' => 'background-image: linear-gradient(45deg, transparent 50%, {{VALUE}} 50%), linear-gradient(135deg, {{VALUE}} 50%, transparent 50%);',
				],
			]
		);

		$this->add_control(
			'post_in_select_focus_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #whats-new-post-in-box select:focus'                       => 'border-color: {{VALUE}};',
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'post_in_select_focus_shadow',
				'selector' => '{{WRAPPER}} #whats-new-post-in-box:focus select, {{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'post_in_select_border',
				'selector'  => '{{WRAPPER}} #whats-new-post-in-box select, {{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_in_select_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #whats-new-post-in-box select'                       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_in_select_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #whats-new-post-in-box select'                                   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'#buddypress {{WRAPPER}} #whats-new-post-in-box-items #activity-autocomplete' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'post_btn_section',
			[
				'label' => __( 'Post Button', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_btn_typography',
				'label'    => __( 'Typography', 'stax-buddy-builder' ),
				'selector' => '{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-submit',
			]
		);

		$this->start_controls_tabs( 'post_btn_style_tabs' );

		$this->start_controls_tab(
			'post_btn_style_normal_tab',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'post_btn_background',
			[
				'label'     => __( 'Background', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-submit' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_btn_text_color',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-submit' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'post_btn_box_shadow',
				'selector' => '{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-submit',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'post_btn_style_hover_tab',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'post_btn_hover_background',
			[
				'label'     => __( 'Background', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-submit:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_btn_text_hover_color',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-submit:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_btn_hover_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-submit:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'post_btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-submit:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'post_btn_border',
				'selector' => '{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-submit',
			]
		);

		$this->add_control(
			'post_btn_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_btn_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cancel_btn_section',
			[
				'label' => __( 'Cancel Button', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cancel_btn_typography',
				'label'    => __( 'Typography', 'stax-buddy-builder' ),
				'selector' => '{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-reset',
			]
		);

		$this->start_controls_tabs( 'cancel_btn_style_tabs' );

		$this->start_controls_tab(
			'cancel_btn_style_normal_tab',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'cancel_btn_background',
			[
				'label'     => __( 'Background', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-reset' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cancel_btn_text_color',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-reset' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cancel_btn_box_shadow',
				'selector' => '{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-reset',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cancel_btn_style_hover_tab',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'cancel_btn_hover_background',
			[
				'label'     => __( 'Background', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-reset:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cancel_btn_text_hover_color',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-reset:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cancel_btn_hover_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-reset:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cancel_btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-reset:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'cancel_btn_border',
				'selector' => '{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-reset',
			]
		);

		$this->add_control(
			'cancel_btn_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-reset' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'cancel_btn_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #bp-nouveau-activity-form #aw-whats-new-reset' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		parent::render();
		if ( bpb_is_elementor_editor() ) {
			bpb_load_template( 'preview/sitewide-activity/form' );
		} else {
			if ( is_user_logged_in() ) {
				bp_get_template_part( 'activity/post-form' );
			}
		}
	}

}
