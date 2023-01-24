<?php
namespace WprAddons\Modules\AuthorBox\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Author_Box extends Widget_Base {
	
	public function get_name() {
		return 'wpr-author-box';
	}

	public function get_title() {
		return esc_html__( 'Author Box', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-person';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'qq', 'author box', 'post', 'title' ];//tmp
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_author_box',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'author_arrange',
			[
				'label' => esc_html__( 'Arrange', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'vertical',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'wpr-author-box-arrange-'
			]
		);

		$this->add_control(
			'author_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'author_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_name',
			[
				'label' => esc_html__( 'Show Name', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_name_tag',
			[
				'label' => esc_html__( 'Name HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'h3',
				'condition' => [
					'author_name' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_name_links_to',
			[
				'label' => esc_html__( 'Links To', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'Nothing', 'wpr-addons' ),
					'website' => esc_html__( 'Website', 'wpr-addons' ),
					'posts' => esc_html__( 'Author Posts', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'author_name' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_link_tab',
			[
				'label' => esc_html__( 'Open in New Tab', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'author_name' => 'yes',
					'author_name_links_to!' => 'none',
				]
			]
		);

		$this->add_control(
			'author_title',
			[
				'label' => esc_html__( 'Show Title', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_title_text',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Writer & Blogger',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'h3',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_title_links_to',
			[
				'label' => esc_html__( 'Links To', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'Nothing', 'wpr-addons' ),
					'website' => esc_html__( 'Website', 'wpr-addons' ),
					'posts' => esc_html__( 'Author Posts', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_title_link_tab',
			[
				'label' => esc_html__( 'Open in New Tab', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'author_title' => 'yes',
					'author_title_links_to!' => 'none',
				]
			]
		);

		$this->add_control(
			'author_bio',
			[
				'label' => esc_html__( 'Show Biography', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'author_posts_link',
			[
				'label' => esc_html__( 'Show Author Posts Link', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'author_posts_link_text',
			[
				'label' => esc_html__( 'Posts Link Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'All Posts',
				'condition' => [
					'author_posts_link' => [ 'yes' ],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Avatar -----------
		$this->start_controls_section(
			'section_style_avatar',
			[
				'label' => esc_html__( 'Avatar', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'avatar_align',
			[
				'label' => esc_html__( 'Center Image Vertically', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'align-self: center;',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-image' => '{{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Image Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 48,
				],
				'range' => [
					'px' => [
						'min' => 16,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-image img' => 'width: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'avatar_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-author-box-arrange-vertical .wpr-author-box-image' => 'margin-bottom: {{SIZE}}px',
					'{{WRAPPER}}.wpr-author-box-arrange-left .wpr-author-box-image' => 'margin-right: {{SIZE}}px',
					'{{WRAPPER}}.wpr-author-box-arrange-right .wpr-author-box-image' => 'margin-left: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'avatar_border',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#222222',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-author-box-image img',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'avatar_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'avatar_shadow',
				'selector' => '{{WRAPPER}} .wpr-author-box-image',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Name -------------
		$this->start_controls_section(
			'section_style_name',
			[
				'label' => esc_html__( 'Name', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-name' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-author-box-name a' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-author-box-name'
			]
		);

		$this->add_responsive_control(
			'name_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-name' => 'margin-top: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'name_bot_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-name' => 'margin-bottom: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Title -------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-author-box-title a' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-author-box-title'
			]
		);

		$this->add_responsive_control(
			'title_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-title' => 'margin-top: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_bot_distance',
			[
				'label' => esc_html__( 'Bottom Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-title' => 'margin-bottom: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Biography --------
		$this->start_controls_section(
			'section_style_bio',
			[
				'label' => esc_html__( 'Biography', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'bio_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-bio' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bio_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-author-box-bio'
			]
		);

		$this->add_responsive_control(
			'bio_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-bio' => 'margin-bottom: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Author Posts Link
		$this->start_controls_section(
			'section_style_archive_link',
			[
				'label' => esc_html__( 'Author Posts Link', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_archive_link_style' );

		$this->start_controls_tab(
			'tab_grid_archive_link_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'archive_link_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'archive_link_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'archive_link_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_archive_link_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'archive_link_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-author-box-btn:hover a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'archive_link_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'archive_link_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'archive_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'archive_link_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-author-box-btn'
			]
		);

		$this->add_control(
			'archive_link_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'archive_link_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'archive_link_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'archive_link_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'archive_link_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// Get Author Info
		$id = get_the_author_meta( 'ID' );
		$avatar = get_avatar( $id, 264 );
		$name = get_the_author_meta( 'display_name' );
		$title = $settings['author_title_text'];
		$biography = get_the_author_meta( 'description' );
		$website = get_the_author_meta( 'user_url' );
		$archive_url = get_author_posts_url( $id );
		$author_link = 'website' === $settings['author_name_links_to'] ? $website : $archive_url;
		$link_target = 'yes' === $settings['author_link_tab'] ? '_blank' : '_self';
		$has_website = false;

		if ( 'website' === $settings['author_name_links_to'] && '' !== $website ) {
			$has_website = true;
		}

		// HTML
		echo '<div class="wpr-author-box">';

			// Avatar
			if ( '' !== $settings['author_avatar'] && false !== $avatar ) {
				echo '<div class="wpr-author-box-image">';
					if ( 'posts' === $settings['author_name_links_to'] || $has_website ) {
						echo '<a href="'. esc_url( $author_link ) .'" target="'. esc_attr($link_target) .'">'. $avatar .'</a>';
					} else {
						echo $avatar;
					}
				echo '</div>';
			}

			// Wrap All Text Blocks
			echo '<div class="wpr-author-box-text">';

			// Author Name
			if ( '' !== $settings['author_name'] && '' !== $name ) {
				echo '<'. $settings['author_name_tag'] .' class="wpr-author-box-name">';
					if ( 'posts' === $settings['author_name_links_to'] || $has_website ) {
						echo '<a href="'. esc_url( $author_link ) .'" target="'. esc_attr($link_target) .'">'. $name .'</a>';
					} else {
						echo $name;
					}
				echo '</'. $settings['author_name_tag'] .'>';
			}

			// Author Title
			if ( '' !== $settings['author_name'] && '' !== $title ) {
				echo '<'. $settings['author_title_tag'] .' class="wpr-author-box-title">';
					if ( 'posts' === $settings['author_title_links_to'] || $has_website ) {
						echo '<a href="'. esc_url( $author_link ) .'" target="'. esc_attr($link_target) .'">'. $title .'</a>';
					} else {
						echo $title;
					}
				echo '</'. $settings['author_title_tag'] .'>';
			}

			// Author Biography
			if ( '' !== $settings['author_bio'] && '' !== $biography ) {
				echo '<p class="wpr-author-box-bio">'. $biography .'</p>';
			}

			// Author Posts Link
			if ( '' !== $settings['author_posts_link'] ) {
				echo '<a href="'. esc_url( $archive_url ) .'" class="wpr-author-box-btn">';
					echo esc_html( $settings['author_posts_link_text'] );
				echo '</a>';
			}

			echo '</div>'; // End .wpr-author-box-text

		echo '</div>';
	}
	
}