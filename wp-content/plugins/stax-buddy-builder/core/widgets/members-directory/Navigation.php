<?php

namespace Buddy_Builder\Widgets\MembersDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Navigation extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-members-directory-navigation';
	}

	public function get_title() {
		return esc_html__( 'Members Navigation', 'stax-buddy-builder' );
	}

	public function get_icon() {
		return 'sq-icon-bp_menu sq-widget-label';
	}

	public function get_categories() {
		return [ 'buddy-builder-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Navigation', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nav_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'nav_shadow',
				'selector' => '{{WRAPPER}} nav',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'nav_border',
				'selector'  => '#buddypress {{WRAPPER}} nav',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'nav_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'#buddypress {{WRAPPER}} nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'#buddypress {{WRAPPER}} nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			[
				'label' => __( 'Navigation Item', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'nav_align',
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
					'{{WRAPPER}} nav ul, {{WRAPPER}} #item-nav ul' => 'text-align: {{VALUE}}; display: block;',
				],
				'default'   => 'left',
			]
		);

		$this->add_control(
			'nav_display',
			[
				'label'     => __( 'Display', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'inline-block',
				'options'   => [
					'inline-block' => __( 'Inline', 'stax-buddy-builder' ),
					'block'        => __( 'Block', 'stax-buddy-builder' ),
				],
				'selectors' => [
					'{{WRAPPER}} nav, {{WRAPPER}} #item-nav'                        => 'border-top: 0; border-bottom: 0; box-shadow: none;',
					'{{WRAPPER}} nav ul, {{WRAPPER}} #item-nav ul'                  => 'padding: 0; margin: 0; height: auto; display: block;',
					'{{WRAPPER}} nav ul li, {{WRAPPER}} #item-nav ul li'            => 'display: {{VALUE}}; float: none;',
					'{{WRAPPER}} nav ul li a, {{WRAPPER}} #item-nav ul li a'        => 'display: inline-block;',
					// '{{WRAPPER}} nav ul li .count, {{WRAPPER}} nav ul li .no-count' => 'display: inline-block; padding: 4px 0; min-width: 30px; text-align: center;'
				],
			]
		);

		$this->add_responsive_control(
			'nav_items_content_align',
			[
				'label'     => __( 'Content Align', 'stax-buddy-builder' ),
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
					'{{WRAPPER}} nav ul li a, {{WRAPPER}} #item-nav ul li a' => 'text-align: {{VALUE}};',
				],
				'default'   => 'center',
				'condition' => [
					'nav_display' => 'block',
				],
			]
		);

		$this->add_responsive_control(
			'nav_h_spacing',
			[
				'label'     => __( 'Spacing', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} nav ul li, {{WRAPPER}} #item-nav ul li'                       => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} nav ul li:last-child, {{WRAPPER}} #item-nav ul li:last-child' => 'margin-right: 0;',
				],
				'condition' => [
					'nav_display' => 'inline-block',
				],
			]
		);

		$this->add_control(
			'nav_item_width',
			[
				'label'     => __( 'Width', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} nav ul li a, {{WRAPPER}} #item-nav ul li a' => 'width: {{SIZE}}%;',
				],
				'condition' => [
					'nav_display' => 'block',
				],
			]
		);

		$this->add_control(
			'nav_v_spacing',
			[
				'label'     => __( 'Spacing', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} nav ul li, {{WRAPPER}} #item-nav ul li'                       => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} nav ul li:last-child, {{WRAPPER}} #item-nav ul li:last-child' => 'margin-bottom: 0;',
				],
				'condition' => [
					'nav_display' => 'block',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Text Typography', 'stax-buddy-builder' ),
				'name'     => 'nav_item_typography',
				'selector' => '{{WRAPPER}} nav ul li a',
			]
		);

		$this->start_controls_tabs( 'nav_item_style' );

		$this->start_controls_tab(
			'nav_item_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'nav_item_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} nav ul li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav ul li a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'nav_item_box_shadow',
				'selector' => '{{WRAPPER}} nav ul li a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_item_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'nav_item_hover_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} nav ul li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_hover_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav ul li a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_hover_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav ul li a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'nav_item_hover_box_shadow',
				'selector' => '{{WRAPPER}} nav ul li a:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_item_active',
			[
				'label' => __( 'Active', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'nav_item_active_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} nav ul li.selected a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_active_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav ul li.selected a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_active_border_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav ul li.selected a' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'nav_active_box_shadow',
				'selector' => '{{WRAPPER}} nav ul li.selected a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'nav_item_border',
				'selector'  => '{{WRAPPER}} nav ul li a',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'nav_item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} nav ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_item_padding',
			[
				'label'      => __( 'Items Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} nav ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_item_margin',
			[
				'label'      => __( 'Items Margin', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} nav ul li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_counter_style',
			[
				'label' => __( 'Item Counter', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nav_item_counter_spacing',
			[
				'label'     => __( 'Spacing', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.no-count' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Text Typography', 'stax-buddy-builder' ),
				'name'     => 'nav_item_counter_typography',
				'selector' => '{{WRAPPER}} nav ul li a span.count, {{WRAPPER}} nav ul li a span.no-count',
			]
		);

		$this->start_controls_tabs( 'tabs_nav_item_counter_style' );

		$this->start_controls_tab(
			'nav_item_counter_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'nav_count_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.no-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_count_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.no-count' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_item_counter_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'nav_item_counter_text_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} nav ul li a:hover span.count, {{WRAPPER}} #item-nav ul li a:hover span.count, {{WRAPPER}} #item-nav ul li a:hover span.no-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_counter_background_hover_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav ul li a:hover span.count, {{WRAPPER}} #item-nav ul li a:hover span.count, {{WRAPPER}} #item-nav ul li a:hover span.no-count' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_counter_border_hover_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} nav ul li a:hover span.count, {{WRAPPER}} #item-nav ul li a:hover span.count, {{WRAPPER}} #item-nav ul li a:hover span.no-count' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_item_counter_active',
			[
				'label' => __( 'Active', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'nav_item_counter_text_active_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} nav ul li.selected a span.count, {{WRAPPER}} #item-nav ul li.selected a span.count, {{WRAPPER}} #item-nav ul li.selected a span.no-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_counter_background_active_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav ul li.selected a span.count, {{WRAPPER}} #item-nav ul li.selected a span.count, {{WRAPPER}} #item-nav ul li.selected a span.no-count' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_counter_border_active_color',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} nav ul li.selected a span.count, {{WRAPPER}} #item-nav ul li.selected a span.count, {{WRAPPER}} #item-nav ul li.selected a span.no-count' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'nav_item_counter_border',
				'selector'  => '{{WRAPPER}} nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.no-count',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'nav_item_counter_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.no-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_item_counter_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.count, {{WRAPPER}} #item-nav ul li a span.no-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		parent::render();
		if ( bpb_is_elementor_editor() ) {
			bpb_load_template( 'preview/members-directory/navigation' );
		} elseif ( ! bp_nouveau_is_object_nav_in_sidebar() ) {
			bp_get_template_part( 'common/nav/directory-nav' );
		}
	}

}
