<?php

namespace Buddy_Builder\Widgets\ProfileMember;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Avatar extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-profile-member-avatar';
	}

	public function get_title() {
		return esc_html__( 'Avatar', 'stax-buddy-builder' );
	}

	public function get_icon() {
		return 'sq-icon-bp_avatar sq-widget-label';
	}

	public function get_categories() {
		return [ 'buddy-builder-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Avatar', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'avatar_align',
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
					'{{WRAPPER}}'     => 'text-align: {{VALUE}};',
					'{{WRAPPER}} img' => 'display: inline-block;',
				],
				'default'   => '',
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label'   => __( 'Size', 'stax-buddy-builder' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'thumb' => __( 'Thumb', 'stax-buddy-builder' ),
					'full'  => __( 'Full', 'stax-buddy-builder' ),
				],
				'default' => 'full',
			]
		);

		$this->add_responsive_control(
			'avatar_width',
			[
				'label'     => __( 'Width', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .avatar' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tab_avatar_style' );

		$this->start_controls_tab(
			'tab_avatar_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'avatar_box_shadow',
				'selector' => '{{WRAPPER}} .avatar',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_avatar_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'avatar_box_shadow_hover',
				'selector' => '{{WRAPPER}} .avatar:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'avatar_border',
				'selector'  => '{{WRAPPER}} .avatar',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'avatar_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		parent::render();
		if ( bpb_is_elementor_editor() ) {
			bpb_load_template( 'preview/profile-member/avatar' );
		} else {
			$settings = $this->get_settings_for_display();
			$args     = 'type=' . $settings['avatar_size'];
			?>
			<div class="elementor-image-bpb-avatar">
				<a href="<?php bp_displayed_user_link(); ?>">
					<?php bp_displayed_user_avatar( $args ); ?>
				</a>
			</div>
			<?php
		}
	}

}
