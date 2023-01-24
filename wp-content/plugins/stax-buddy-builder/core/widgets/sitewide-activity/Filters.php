<?php

namespace Buddy_Builder\Widgets\Sitewide;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Filters extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-sitewide-filters';
	}

	public function get_title() {
		return esc_html__( 'Filters', 'stax-buddy-builder' );
	}

	public function get_icon() {
		return 'sq-icon-bp_filter sq-widget-label';
	}

	public function get_categories() {
		return [ 'buddy-builder-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'container_style',
			[
				'label' => __( 'Container', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
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
			'rss_style',
			[
				'label' => __( 'RSS', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'rss_color',
			[
				'label'     => __( 'Rss Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .feed a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rss_font_size',
			[
				'label'     => __( 'Rss Size', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 100,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .feed a:before' => 'font-size: {{VALUE}}px;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sorting_style',
			[
				'label' => __( 'Sorting', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Text Typography', 'stax-buddy-builder' ),
				'name'     => 'filters_sorting_select_typography',
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
				'name'     => 'sorting_select_border',
				'selector' => '{{WRAPPER}} .select-wrap',
			]
		);

		$this->add_control(
			'sorting_select_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
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
					'{{WRAPPER}} #activity-filter-by' => 'padding: {{TOP}}{{UNIT}} calc({{RIGHT}}{{UNIT}} + 32px) {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .select-arrow'       => 'padding: {{TOP}}{{UNIT}} 10px {{BOTTOM}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'search_style',
			[
				'label' => __( 'Search', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'search_box_clear_icon_color',
			[
				'label'     => __( 'Clear Icon', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} #dir-activity-search-form #dir-activity-search::-ms-clear'                    => 'visibility: hidden;',
					'{{WRAPPER}} #dir-activity-search-form #dir-activity-search::-webkit-search-cancel-button' => 'visibility: hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Text Typography', 'stax-buddy-builder' ),
				'name'     => 'search_box_typography',
				'selector' => '{{WRAPPER}} #dir-activity-search-form #dir-activity-search',
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
					'{{WRAPPER}} #dir-activity-search-form' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} #dir-activity-search-form #dir-activity-search' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} #dir-activity-search-form #dir-activity-search::placeholder'           => 'color: {{VALUE}};',
					'{{WRAPPER}} #dir-activity-search-form #dir-activity-search:-ms-input-placeholder'  => 'color: {{VALUE}};',
					'{{WRAPPER}} #dir-activity-search-form #dir-activity-search::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_box_shadow',
				'selector' => '{{WRAPPER}} #dir-activity-search-form',
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
					'{{WRAPPER}} #dir-activity-search-form:hover' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} #dir-activity-search-form:hover #dir-activity-search' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} #dir-activity-search-form:hover #dir-activity-search::placeholder'           => 'color: {{VALUE}};',
					'{{WRAPPER}} #dir-activity-search-form:hover #dir-activity-search:-ms-input-placeholder'  => 'color: {{VALUE}};',
					'{{WRAPPER}} #dir-activity-search-form:hover #dir-activity-search::-ms-input-placeholder' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} #dir-activity-search-form:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_box_hover_shadow',
				'selector' => '{{WRAPPER}} #dir-activity-search-form:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'search_box_border',
				'selector' => '{{WRAPPER}} #dir-activity-search-form',
			]
		);

		$this->add_control(
			'search_box_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #dir-activity-search-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} #dir-activity-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'search_btn_style',
			[
				'label' => __( 'Search Button', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} #dir-activity-search-submit' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_submit_button_color',
			[
				'label'     => __( 'Icon Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #dir-activity-search-submit .dashicons' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_submit_button_shadow',
				'selector' => '{{WRAPPER}} #dir-activity-search-submit',
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
					'{{WRAPPER}} #dir-activity-search-submit:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_submit_button_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} #dir-activity-search-submit:hover' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} #dir-activity-search-submit:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_submit_button_hover_shadow',
				'selector' => '{{WRAPPER}} #dir-activity-search-submit:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'search_submit_button_border',
				'selector'  => '{{WRAPPER}} #dir-activity-search-submit',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_submit_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #dir-activity-search-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} #dir-activity-search-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		parent::render();
		if ( bpb_is_elementor_editor() ) {
			bpb_load_template( 'preview/sitewide-activity/filters' );
		} else {
			bp_nouveau_template_notices();
			bp_get_template_part( 'common/search-and-filters-bar' );
		}
	}

}
