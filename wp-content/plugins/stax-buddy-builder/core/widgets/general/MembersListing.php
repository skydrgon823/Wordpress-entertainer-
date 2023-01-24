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

class MembersListing extends \Buddy_Builder\Widgets\Base {

	public function get_name() {
		return 'bpb-general-members-list';
	}

	public function get_title() {
		return esc_html__( 'Members List', 'stax-buddy-builder' );
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

		do_action( 'buddy_builder/widget/members-listing/settings', $this );

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
			'section_style',
			[
				'label' => __( 'Listing', 'stax-buddy-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'listing_no_border',
			[
				'label'     => __( 'View', 'stax-buddy-builder' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} #members-list' => 'border: 0;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'list_item_background',
				'label'    => __( 'Background', 'stax-buddy-builder' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} #members-list > li > .list-wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'listing_box_shadow',
				'selector' => '{{WRAPPER}} #members-list > li > .list-wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'listing_border',
				'selector' => '{{WRAPPER}} #members-list > li > .list-wrap',
			]
		);

		$this->add_control(
			'listing_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} #members-list > li > .list-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'listing_margin',
			[
				'label'      => __( 'Margin', 'stax-buddy-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #members-list > li > .list-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} #members-list > li > .list-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		parent::render();
		$settings = $this->get_settings_for_display();

		$current_component = static function () {
			return 'members';
		};

		add_filter( 'buddy_builder/has_template/pre', '__return_true' );

		$loop_classes = static function () use ( $settings ) {
			return [
				'item-list',
				'members-list',
				'bp-list',
				'grid',
				bpb_get_column_class( $settings['columns'] ),
				bpb_get_column_class( $settings['columns_tablet'], 'tablet' ),
				bpb_get_column_class( $settings['columns_mobile'], 'mobile' ),
			];
		};

		add_filter( 'bp_current_component', $current_component );
		add_filter( 'bp_nouveau_get_loop_classes', $loop_classes );

		apply_filters( 'buddy_builder/members-loop/before/template', $settings );

		add_filter( 'bp_members_pagination_count', '__return_zero' );
		add_filter( 'bp_get_members_pagination_links', '__return_zero' );
		?>

		<div id="buddypress" class="buddypress-wrap bp-dir-hori-nav members">
			<?php bp_nouveau_before_members_directory_content(); ?>

			<div class="screen-content">
				<div id="members-dir-list" class="members dir-list" data-bp-list="">
					<?php bp_get_template_part( 'members/members-loop' ); ?>
				</div>

				<?php bp_nouveau_after_members_directory_content(); ?>
			</div>
		</div>

		<?php
		remove_filter( 'bp_nouveau_get_loop_classes', $loop_classes );
		remove_filter( 'bp_current_component', $current_component );

		apply_filters( 'buddy_builder/members-loop/after/template', $settings );

		remove_filter( 'bp_members_pagination_count', '__return_zero' );
		remove_filter( 'bp_get_members_pagination_links', '__return_zero' );
		remove_filter( 'buddy_builder/has_template/pre', '__return_true' );
	}

}
