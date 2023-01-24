<?php

namespace Buddy_Builder\Widgets\GroupsDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Filters extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-groups-directory-filters';
	}

	public function get_title() {
		return esc_html__( 'Groups Filters', 'stax-buddy-builder' );
	}

	public function get_icon() {
		return 'sq-icon-bp_filter sq-widget-label';
	}

	public function get_categories() {
		return [ 'buddy-builder-elements' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Content', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_display',
			[
				'label'     => __( 'Show & Order', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => [
					'1'  => __( 'List Toggle > Search > Sort', 'stax-buddy-builder' ),
					'2'  => __( 'List Toggle > Sort > Search', 'stax-buddy-builder' ),
					'3'  => __( 'List Toggle > Search', 'stax-buddy-builder' ),
					'4'  => __( 'List Toggle > Sort', 'stax-buddy-builder' ),
					'5'  => __( 'List Toggle', 'stax-buddy-builder' ),
					'6'  => __( 'Search > Sort > List Toggle', 'stax-buddy-builder' ),
					'7'  => __( 'Search > List Toggle > Sort', 'stax-buddy-builder' ),
					'8'  => __( 'Search > List Toggle', 'stax-buddy-builder' ),
					'9'  => __( 'Search > Sort', 'stax-buddy-builder' ),
					'10' => __( 'Search', 'stax-buddy-builder' ),
					'11' => __( 'Sort > List Toggle > Search', 'stax-buddy-builder' ),
					'12' => __( 'Sort > Search > List Toggle', 'stax-buddy-builder' ),
					'13' => __( 'Sort > Search', 'stax-buddy-builder' ),
					'14' => __( 'Sort > List Toggle', 'stax-buddy-builder' ),
					'15' => __( 'Sort', 'stax-buddy-builder' ),

				],
				'selectors' => [
					'{{WRAPPER}} .subnav-filters'        => 'display: flex; justify-content: space-between; align-items: center; margin: 0;',
					'{{WRAPPER}} .subnav-filters:before' => 'display: none;',
					'{{WRAPPER}} .subnav-filters:after'  => 'display: none;',
				],
			]
		);

		$this->add_control(
			'content_display_1',
			[
				'label'     => __( 'List Toggle > Search > Sort', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'order: 1;',
					'{{WRAPPER}} .subnav-search'     => 'order: 2;',
					'{{WRAPPER}} .component-filters' => 'order: 3;',
				],
			]
		);

		$this->add_control(
			'content_display_2',
			[
				'label'     => __( 'List Toggle > Sort > Search', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '2',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'order: 1;',
					'{{WRAPPER}} .subnav-search'     => 'order: 3;',
					'{{WRAPPER}} .component-filters' => 'order: 2;',
				],
			]
		);

		$this->add_control(
			'content_display_3',
			[
				'label'     => __( 'List Toggle > Search', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '3',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'order: 1;',
					'{{WRAPPER}} .subnav-search'     => 'order: 2;',
					'{{WRAPPER}} .component-filters' => 'display: none;',
				],
			]
		);

		$this->add_control(
			'content_display_4',
			[
				'label'     => __( 'List Toggle > Sort', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '4',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'order: 1;',
					'{{WRAPPER}} .subnav-search'     => 'display: none;',
					'{{WRAPPER}} .component-filters' => 'order: 2;',
				],
			]
		);

		$this->add_control(
			'content_display_5',
			[
				'label'     => __( 'List Toggle', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '5',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'width: 100%; float: none; display: block;',
					'{{WRAPPER}} .subnav-search'     => 'display: none;',
					'{{WRAPPER}} .component-filters' => 'display: none;',
				],
			]
		);

		$this->add_control(
			'content_display_6',
			[
				'label'     => __( 'Search > Sort > List Toggle', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '6',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'order: 3;',
					'{{WRAPPER}} .subnav-search'     => 'order: 1;',
					'{{WRAPPER}} .component-filters' => 'order: 2;',
				],
			]
		);

		$this->add_control(
			'content_display_7',
			[
				'label'     => __( 'Search > List Toggle > Sort', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '7',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'order: 2;',
					'{{WRAPPER}} .subnav-search'     => 'order: 1;',
					'{{WRAPPER}} .component-filters' => 'order: 3;',
				],
			]
		);

		$this->add_control(
			'content_display_8',
			[
				'label'     => __( 'Search > List Toggle', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '8',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'order: 2;',
					'{{WRAPPER}} .subnav-search'     => 'order: 1;',
					'{{WRAPPER}} .component-filters' => 'display: none;',
				],
			]
		);

		$this->add_control(
			'content_display_9',
			[
				'label'     => __( 'Search > Sort', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '9',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'display: none;',
					'{{WRAPPER}} .subnav-search'     => 'order: 1;',
					'{{WRAPPER}} .component-filters' => 'order: 2;',
				],
			]
		);

		$this->add_control(
			'content_display_10',
			[
				'label'     => __( 'Search', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'display: none;',
					'{{WRAPPER}} .subnav-search'     => 'width: 100%; float: none; display: block;',
					'{{WRAPPER}} .component-filters' => 'display: none;',
				],
			]
		);

		$this->add_control(
			'content_display_11',
			[
				'label'     => __( 'Sort > List Toggle > Search', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '11',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'order: 2;',
					'{{WRAPPER}} .subnav-search'     => 'order: 3;',
					'{{WRAPPER}} .component-filters' => 'order: 1;',
				],
			]
		);

		$this->add_control(
			'content_display_12',
			[
				'label'     => __( 'Sort > Search > List Toggle', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '12',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'order: 3;',
					'{{WRAPPER}} .subnav-search'     => 'order: 2;',
					'{{WRAPPER}} .component-filters' => 'order: 1;',
				],
			]
		);

		$this->add_control(
			'content_display_13',
			[
				'label'     => __( 'Sort > Search', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '13',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'display: none;',
					'{{WRAPPER}} .subnav-search'     => 'order: 2;',
					'{{WRAPPER}} .component-filters' => 'order: 1;',
				],
			]
		);

		$this->add_control(
			'content_display_14',
			[
				'label'     => __( 'Sort > List Toggle', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '14',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'order: 2;',
					'{{WRAPPER}} .subnav-search'     => 'display: none;',
					'{{WRAPPER}} .component-filters' => 'order: 1;',
				],
			]
		);

		$this->add_control(
			'content_display_15',
			[
				'label'     => __( 'Sort', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'condition' => [
					'content_display' => '15',
				],
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type'  => 'display: none;',
					'{{WRAPPER}} .subnav-search'     => 'display: none;',
					'{{WRAPPER}} .component-filters' => 'width: 100%; float: none; display: block;',
				],
			]
		);

		$this->add_control(
			'content_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .subnav-filters' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'content_shadow',
				'selector' => '{{WRAPPER}} .subnav-filters',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'content_border',
				'selector'  => '{{WRAPPER}} .subnav-filters',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .subnav-filters' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .subnav-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'list_toggle_box_style',
			[
				'label'      => __( 'List Toggle', 'stax-buddy-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'and',
					'terms'    => [

						[
							'name'     => 'content_display',
							'operator' => '!in',
							'value'    => [
								'9',
								'10',
								'13',
								'15',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'toggle_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'toggle_state_style' );

		$this->start_controls_tab(
			'toggle_normal_tab',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'toggle_color',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type .bpb-list-mode span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bpb-listing-type .bpb-grid-mode span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_hover_tab',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'toggle_hover_color',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type .bpb-list-mode:hover span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bpb-listing-type .bpb-grid-mode:hover span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_active_tab',
			[
				'label' => __( 'Active', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'toggle_active_color',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bpb-listing-type .bpb-list-mode.bpb-active span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bpb-listing-type .bpb-grid-mode.bpb-active span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'toggle_container_border',
				'selector'  => '{{WRAPPER}} .bpb-listing-type',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_container_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .bpb-listing-type' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_container_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bpb-listing-type' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_search_box_style',
			[
				'label'      => __( 'Search Box', 'stax-buddy-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'content_display',
							'operator' => '!in',
							'value'    => [
								'4',
								'5',
								'14',
								'15',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'search_box_clear_icon_color',
			[
				'label'     => __( 'Clear Icon', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-form #dir-groups-search::-ms-clear'                    => 'visibility: hidden;',
					'{{WRAPPER}} #dir-groups-search-form #dir-groups-search::-webkit-search-cancel-button' => 'visibility: hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Text Typography', 'stax-buddy-builder' ),
				'name'     => 'search_box_typography',
				'selector' => '{{WRAPPER}} #dir-groups-search-form #dir-groups-search',
			]
		);

		$this->start_controls_tabs( 'search_box_state_style' );

		$this->start_controls_tab(
			'search_box_normal_tab',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'search_box_background',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-form' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_box_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-form #dir-groups-search' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_box_placeholder_color',
			[
				'label'     => __( 'Placeholder Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-form #dir-groups-search::placeholder'           => 'color: {{VALUE}};',
					'{{WRAPPER}} #dir-groups-search-form #dir-groups-search:-ms-input-placeholder'  => 'color: {{VALUE}};',
					'{{WRAPPER}} #dir-groups-search-form #dir-groups-search::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_box_shadow',
				'selector' => '{{WRAPPER}} #dir-groups-search-form',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'search_box_hover_tab',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'search_box_hover_background',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-form:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_box_text_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-form:hover #dir-groups-search' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_box_placeholder_hover_color',
			[
				'label'     => __( 'Placeholder Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-form:hover #dir-groups-search::placeholder'           => 'color: {{VALUE}};',
					'{{WRAPPER}} #dir-groups-search-form:hover #dir-groups-search:-ms-input-placeholder'  => 'color: {{VALUE}};',
					'{{WRAPPER}} #dir-groups-search-form:hover #dir-groups-search::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_box_hover_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-form:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_box_hover_shadow',
				'selector' => '{{WRAPPER}} #dir-groups-search-form:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'search_box_border',
				'selector'  => '{{WRAPPER}} #dir-groups-search-form',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_box_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #dir-groups-search-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_box_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #dir-groups-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_search_button_style',
			[
				'label'      => __( 'Search Button', 'stax-buddy-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'content_display',
							'operator' => '!in',
							'value'    => [
								'4',
								'5',
								'14',
								'15',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'search_submit_button_spacing',
			[
				'label'     => __( 'Left Space', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-submit' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'search_submit_button_state_style' );

		$this->start_controls_tab(
			'search_submit_button_normal_style',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'search_submit_button_bg_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-submit' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_submit_icon_color',
			[
				'label'     => __( 'Icon Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-submit .dashicons:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_submit_button_shadow',
				'selector' => '{{WRAPPER}} #dir-groups-search-submit',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'search_submit_button_hover_style',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'search_submit_button_hover_bg_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-submit:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_submit_icon_hover_color',
			[
				'label'     => __( 'Icon Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-submit:hover .dashicons:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_submit_button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #dir-groups-search-submit:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_submit_button_hover_shadow',
				'selector' => '{{WRAPPER}} #dir-groups-search-submit:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'search_submit_button_border',
				'selector'  => '{{WRAPPER}} #dir-groups-search-submit',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_submit_button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #dir-groups-search-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_submit_button_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #dir-groups-search-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sorting_style',
			[
				'label'      => __( 'Sorting', 'stax-buddy-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'content_display',
							'operator' => '!in',
							'value'    => [
								'3',
								'5',
								'8',
								'10',
							],
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Text Typography', 'stax-buddy-builder' ),
				'name'     => 'sorting_select_typography',
				'selector' => '{{WRAPPER}} .select-wrap select',
			]
		);

		$this->start_controls_tabs( 'sorting_select_state_style' );

		$this->start_controls_tab(
			'sorting_select_normal_style',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'sorting_select_bg_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .select-wrap' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sorting_select_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .select-wrap select' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sorting_select_icon_color',
			[
				'label'     => __( 'Icon Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .select-wrap .select-arrow:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'sorting_select_shadow',
				'selector' => '{{WRAPPER}} .select-wrap',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'sorting_select_hover_style',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'sorting_select_hover_bg_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .select-wrap:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sorting_select_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .select-wrap:hover select' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sorting_select_hover_icon_color',
			[
				'label'     => __( 'Icon Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .select-wrap:hover .select-arrow:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sorting_select_hover_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .select-wrap:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'sorting_select_hover_shadow',
				'selector' => '{{WRAPPER}} .select-wrap:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'sorting_select_border',
				'selector'  => '{{WRAPPER}} .select-wrap',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sorting_select_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .select-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sorting_select_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #groups-order-by' => 'padding: {{TOP}}{{UNIT}} calc({{RIGHT}}{{UNIT}} + 32px) {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .select-arrow'    => 'padding: {{TOP}}{{UNIT}} 10px {{BOTTOM}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		parent::render();
		$settings = $this->get_settings_for_display();

		if ( bpb_is_elementor_editor() ) {
			bpb_load_template( 'preview/groups-directory/filters' );
		} else {
			$has_list_toggle = ! in_array( $settings['content_display'], [ 9, 10, 13, 15 ], false );

			if ( $has_list_toggle ) {
				add_filter( 'buddy_builder/widget/filters/list_toggle/enabled', '__return_true' );
			}

			bp_get_template_part( 'common/search-and-filters-bar' );
		}
	}

}
