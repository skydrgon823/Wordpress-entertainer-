<?php

namespace Buddy_Builder\Widgets\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Buddy_Builder\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class GroupsListing extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-general-groups-list';
	}

	public function get_title() {
		return esc_html__( 'Groups List', 'stax-buddy-builder' );
	}

	public function get_icon() {
		return 'sq-icon-bp_listing sq-widget-label';
	}

	public function get_categories() {
		return [ 'buddy-builder-elements' ];
	}

	protected function _register_controls() {
		if ( ! function_exists( 'bpb_is_pro' ) ) {
			$this->start_controls_section(
				'go_pro_section',
				[
					'label' => __( 'Go PRO', 'stax-buddy-builder' ),
				]
			);

			$this->add_control(
				'go_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => Plugin::get_instance()->go_pro_template(
						[
							'title'    => __( 'BuddyBuilder PRO', 'stax-buddy-builder' ),
							'messages' => [
								__( 'Power up up your listing with custom queries and templates.', 'stax-buddy-builder' ),
							],
							'link'     => 'https://staxwp.com/go/buddybuilder-pro',
						]
					),
				]
			);

			$this->end_controls_section();
		}

		do_action( 'buddy_builder/widget/groups-listing/settings', $this );

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Settings', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'           => __( 'Columns', 'stax-buddy-builder' ),
				'type'            => Controls_Manager::SELECT,
				'options'         => [
					'1' => __( 'One', 'stax-buddy-builder' ),
					'2' => __( 'Two', 'stax-buddy-builder' ),
					'3' => __( 'Three', 'stax-buddy-builder' ),
					'4' => __( 'Four', 'stax-buddy-builder' ),
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => '3',
				'tablet_default'  => '2',
				'mobile_default'  => '1',
			]
		);

		$this->add_responsive_control(
			'listing_v_spacing_one',
			[
				'label'      => __( 'Spacing', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .bp-list > li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'columns' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'listing_v_spacing_two',
			[
				'label'      => __( 'Vertical Spacing', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .grid-two > li' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'columns' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'listing_h_spacing_two',
			[
				'label'      => __( 'Horizontal Spacing', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'max' => 50,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .grid-two'      => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .grid-two > li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'columns' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'listing_v_spacing_three',
			[
				'label'      => __( 'Vertical Spacing', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .grid-three > li' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'columns' => '3',
				],
			]
		);

		$this->add_responsive_control(
			'listing_h_spacing_three',
			[
				'label'      => __( 'Horizontal Spacing', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'max' => 75,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .grid-three'      => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .grid-three > li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'columns' => '3',
				],
			]
		);

		$this->add_responsive_control(
			'listing_v_spacing_four',
			[
				'label'      => __( 'Vertical Spacing', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .grid-four > li' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'columns' => '4',
				],
			]
		);

		$this->add_responsive_control(
			'listing_h_spacing_four',
			[
				'label'      => __( 'Horizontal Spacing', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'max' => 50,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .grid-four'      => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .grid-four > li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'columns' => '4',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_groups_icons_content',
			[
				'label' => __( 'Groups Icon', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'public_group_icon_sate',
			[
				'label'        => __( 'Public group icon', 'stax-buddy-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'stax-buddy-builder' ),
				'label_off'    => __( 'Hide', 'stax-buddy-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'public_group_icon',
			[
				'label'     => __( 'Show Yes', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} .item-entry.public .item-avatar > a:after' => 'content: "\e912"; font-family: "sq-icons" !important;',
				],
				'condition' => [
					'public_group_icon_sate' => 'yes',
				],
			]
		);

		$this->add_control(
			'private_group_icon_sate',
			[
				'label'        => __( 'Private group icon', 'stax-buddy-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'stax-buddy-builder' ),
				'label_off'    => __( 'Hide', 'stax-buddy-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'private_group_icon',
			[
				'label'     => __( 'Show Yes', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} .item-entry.private .item-avatar > a:after' => 'content: "\e91d"; font-family: "sq-icons" !important',
				],
				'condition' => [
					'private_group_icon_sate' => 'yes',
				],
			]
		);

		$this->add_control(
			'hidden_group_icon_sate',
			[
				'label'        => __( 'Hidden group icon', 'stax-buddy-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'stax-buddy-builder' ),
				'label_off'    => __( 'Hide', 'stax-buddy-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'hidden_group_icon',
			[
				'label'     => __( 'Show Yes', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} .item-entry.hidden .item-avatar > a:after' => 'content: "\e91c"; font-family: "sq-icons" !important',
				],
				'condition' => [
					'hidden_group_icon_sate' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Listing Groups', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'list_item_background',
				'label'    => __( 'Background', 'stax-buddy-builder' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} #groups-list > li > .list-wrap',
			]
		);

		$this->start_controls_tabs( 'tabs_listing_style' );

		$this->start_controls_tab(
			'tab_listing_normal',
			[
				'label' => __( 'Normal', 'stax-buddy-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'listing_box_shadow',
				'selector' => '{{WRAPPER}} #groups-list > li > .list-wrap',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_listing_hover',
			[
				'label' => __( 'Hover', 'stax-buddy-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'listing_box_shadow_hover',
				'selector' => '{{WRAPPER}} #groups-list > li > .list-wrap:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'hr_listing',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'listing_border',
				'selector'  => '{{WRAPPER}} #groups-list > li > .list-wrap',
				'condition' => [
					'columns!' => '1',
				],
			]
		);

		$this->add_control(
			'listing_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #groups-list > li > .list-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'columns!' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'listing_padding',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #groups-list > li > .list-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'columns!' => '1',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'listing_border_one',
				'selector'  => '{{WRAPPER}} #groups-list > li',
				'condition' => [
					'columns' => '1',
				],
			]
		);

		$this->add_control(
			'listing_border_radius_one',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #groups-list > li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'columns' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'listing_padding_one',
			[
				'label'      => __( 'Padding', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #groups-list > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'columns' => '1',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_groups_icons_style',
			[
				'label' => __( 'Groups Icon', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'group_icon_color',
			[
				'label'     => __( 'Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .item-entry .item-avatar > a:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'group_icon_background_color',
			[
				'label'     => __( 'Background Color', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .item-entry .item-avatar > a:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'group_icon_border',
				'selector'  => '{{WRAPPER}} .item-entry .item-avatar > a:after',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'group_icon_v_position',
			[
				'label'      => __( 'Vertical position', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'separator'  => 'before',
				'range'      => [
					'%' => [
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 16,
				],
				'selectors'  => [
					'{{WRAPPER}} .item-entry .item-avatar > a:after' => 'top: calc({{SIZE}}{{UNIT}} - 0.75em);',
				],
			]
		);

		$this->add_control(
			'group_icon_h_position',
			[
				'label'      => __( 'Horizontal position', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 16,
				],
				'selectors'  => [
					'{{WRAPPER}} .item-entry .item-avatar > a:after' => 'right: calc({{SIZE}}{{UNIT}} - 0.75em);',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		parent::render();
		$settings = $this->get_settings_for_display();

		$current_component = static function () {
			return 'groups';
		};

		add_filter( 'buddy_builder/has_template/pre', '__return_true' );

		$loop_classes = static function () use ( $settings ) {
			return [
				'item-list',
				'groups-list',
				'bp-list',
				'grid',
				bpb_get_column_class( $settings['columns'] ),
				bpb_get_column_class( $settings['columns_tablet'], 'tablet' ),
				bpb_get_column_class( $settings['columns_mobile'], 'mobile' ),
			];
		};

		add_filter( 'bp_current_component', $current_component );
		add_filter( 'bp_nouveau_get_loop_classes', $loop_classes );

		apply_filters( 'buddy_builder/groups-loop/before/template', $settings );

		add_filter( 'bp_get_groups_pagination_count', '__return_zero' );
		add_filter( 'bp_get_groups_pagination_links', '__return_zero' );

		?>

		<div id="buddypress" class="buddypress-wrap bp-dir-hori-nav groups">
			<?php bp_nouveau_before_groups_directory_content(); ?>
			<?php bp_nouveau_template_notices(); ?>

			<div class="screen-content">
				<div id="groups-dir-list" class="groups dir-list" data-bp-list="">
					<?php bp_get_template_part( 'groups/groups-loop' ); ?>
				</div>

				<?php bp_nouveau_after_groups_directory_content(); ?>
			</div>
		</div>

		<?php
		remove_filter( 'bp_nouveau_get_loop_classes', $loop_classes );
		remove_filter( 'bp_current_component', $current_component );

		apply_filters( 'buddy_builder/groups-loop/after/template', $settings );

		remove_filter( 'bp_get_groups_pagination_count', '__return_zero' );
		remove_filter( 'bp_get_groups_pagination_links', '__return_zero' );
		remove_filter( 'buddy_builder/has_template/pre', '__return_true' );
	}

}
