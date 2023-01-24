<?php

namespace Buddy_Builder\Widgets\ProfileGroup;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;

class Name extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-profile-group-name';
	}

	public function get_title() {
		return esc_html__( 'Name', 'stax-buddy-builder' );
	}

	public function get_icon() {
		return 'sq-icon-bp_name sq-widget-label';
	}

	public function get_categories() {
		return [ 'buddy-builder-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Username', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'name_align',
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
					'{{WRAPPER}} .bpb-group-name' => 'text-align: {{VALUE}};',
				],
				'default'   => '',
			]
		);

		$this->add_control(
			'name_text_color',
			[
				'label'     => __( 'Text Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bpb-group-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .bpb-group-name',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'name_text_shadow',
				'selector' => '{{WRAPPER}} .bpb-group-name',
			]
		);

		$this->add_responsive_control(
			'name_margin',
			[
				'label'      => __( 'Margin', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bpb-group-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		parent::render();
		if ( bpb_is_elementor_editor() ) {
			bpb_load_template( 'preview/profile-group/name' );
		} else {
			?>
			<div class="bpb-group-name">
				<?php bp_current_group_name(); ?>
			</div>
			<?php
		}
	}

}
