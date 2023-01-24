<?php

namespace Buddy_Builder\Widgets\Sitewide;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Content extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-sitewide-content';
	}

	public function get_title() {
		return esc_html__( 'Content', 'stax-buddy-builder' );
	}

	public function get_icon() {
		return 'sq-icon-bp_section sq-widget-label';
	}

	public function get_categories() {
		return [ 'buddy-builder-elements' ];
	}

	protected function _register_controls() {

		do_action( 'buddy_builder/widget/sitewide-activity/content/settings', $this );

		$this->start_controls_section(
			'activity_item_container_section',
			[
				'label' => __( 'Activity Item Container', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'container_background',
				'label'    => __( 'Background', 'stax-buddy-builder' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .activity > ul.activity-list > li',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .activity > ul.activity-list > li',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .activity > ul.activity-list > li',
			]
		);

		$this->add_control(
			'container_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .activity > ul.activity-list > li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .activity > ul.activity-list > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'spacing_items',
			[
				'label'     => __( 'Spacing items', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .activity > ul.activity-list > li'            => 'margin-top: 0; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .activity > ul.activity-list > li:last-child' => 'margin-bottom: 0;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'load_more_section',
			[
				'label'      => __( 'Load More Button', 'stax-buddy-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [],
			]
		);

		$this->add_control(
			'base_style',
			[
				'label'     => __( 'Base Style', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} .load-more'   => 'background-color: transparent; border: none; margin: 0;',
					'{{WRAPPER}} .load-newest' => 'background-color: transparent; border: none; margin: 0;',
				],
			]
		);

		$this->add_control(
			'load_more_btn_display_type',
			[
				'label'     => __( 'Display', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'block',
				'options'   => [
					'inline-block' => __( 'Inline', 'stax-buddy-builder' ),
					'block'        => __( 'Block', 'stax-buddy-builder' ),
				],
				'selectors' => [
					'{{WRAPPER}} .load-more a' => 'display: block;',
				],
			]
		);

		$this->add_responsive_control(
			'load_more_btn_align',
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
					'{{WRAPPER}} .load-more' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'load_more_btn_display_type' => 'inline-block',
				],
				'default'   => '',
			]
		);

		$this->add_control(
			'load_more_btn_display_inline_block',
			[
				'label'     => __( 'Base Style', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} .load-more a' => 'display: inline-block;',
				],
				'condition' => [
					'load_more_btn_display_type' => 'inline-block',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'load_more_btn_typography',
				'label'    => __( 'Typography', 'stax-buddy-builder' ),
				'selector' => '{{WRAPPER}} .load-more a, {{WRAPPER}} .load-newest a',
			]
		);

		$this->start_controls_tabs( 'load_more_btn_style_tabs' );

		$this->start_controls_tab(
			'load_more_btn_style_normal_tab',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'load_more_btn_background',
			[
				'label'     => __( 'Background', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .load-more a'   => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .load-newest a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'load_more_btn_text_color',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .load-more a'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .load-newest a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'load_more_btn_style_hover_tab',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_control(
			'load_more_btn_hover_background',
			[
				'label'     => __( 'Background', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .load-more a:hover'   => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .load-newest a:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'load_more_btn_hover_text_color',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .load-more a:hover'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .load-newest a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'load_more_btn_hover_border',
			[
				'label'     => __( 'Border Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .load-more a:hover'   => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .load-newest a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'load_more_btn_hover_shadow',
				'selector' => '#buddypress {{WRAPPER}} .load-more a:hover, #buddypress {{WRAPPER}} .load-newest a:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'load_more_btn_border',
				'selector'  => '{{WRAPPER}} .load-more a, {{WRAPPER}} .load-newest a',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'load_more_btn_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .load-more a'   => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .load-newest a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'load_more_btn_shadow',
				'selector' => '#buddypress {{WRAPPER}} .load-more a, #buddypress {{WRAPPER}} .load-newest a',
			]
		);

		$this->add_responsive_control(
			'load_more_btn_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .load-more a'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .load-newest a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		parent::render();
		if ( bpb_is_elementor_editor() ) {
			bpb_load_template( 'preview/sitewide-activity/content' );
		} else {
			bp_nouveau_activity_hook( 'before_directory', 'list' );

			?>
			<div id="activity-stream" class="activity" data-bp-list="activity">
				<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-activity-loading' ); ?></div>
			</div>
			<?php

			bp_nouveau_after_activity_directory_content();
		}
	}

	public function render_plain_content() {
		return '';
	}

}
