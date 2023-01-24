<?php
namespace WprAddons\Modules\Search\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Search extends Widget_Base {
		
	public function get_name() {
		return 'wpr-search';
	}

	public function get_title() {
		return esc_html__( 'Search', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-site-search';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'search', 'search widget' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-search-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_search_query() {
		$search_post_type = Utilities::get_custom_types_of( 'post', false );
		$search_post_type = array_merge( [ 'all' => esc_html__( 'All', 'wpr-addons' ) ], $search_post_type );

		foreach ( $search_post_type as $key => $value ) {
			if ( 'all' != $key ) {
				$search_post_type[$key] = $value .' (Pro)';
			}
		}

		$this->add_control(
			'search_query',
			[
				'label' => esc_html__( 'Select Query', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => $search_post_type,
				'default' => 'all',
			]
		);
	}

	protected function register_controls() {
		
		// Section: Search -----------
		$this->start_controls_section(
			'section_search',
			[
				'label' => esc_html__( 'Search', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_search_query();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'search_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Search Query</span> option is fully supported<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-search-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Search Query</span> option is fully supported<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}


		$this->add_control(
			'search_placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Search...', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_btn',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_btn_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inner',
				'options' => [
					'inner' => esc_html__( 'Inner', 'wpr-addons' ),
					'outer' => esc_html__( 'Outer', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-search-form-style-',
				'render_type' => 'template',
				'condition' => [
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_disable_click',
			[
				'label' => esc_html__( 'Disable Button Click', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpr-search-form-disable-submit-btn-',
				'condition' => [
					'search_btn_style' => 'inner',
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'text' => esc_html__( 'Text', 'wpr-addons' ),
					'icon' => esc_html__( 'Icon', 'wpr-addons' ),
				],
				'render_type' => 'template',
				'condition' => [
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Go',
				'condition' => [
					'search_btn_type' => 'text',
					'search_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_btn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'condition' => [
					'search_btn_type' => 'icon',
					'search_btn' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, Controls_Manager::RAW_HTML, 'search', [
			'Custom Search Query - Only Posts, Pages or Custom Post Types'
		] );

		// Styles
		// Section: Input ------------
		$this->start_controls_section(
			'section_style_input',
			[
				'label' => esc_html__( 'Input', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_input_colors' );

		$this->start_controls_tab(
			'tab_input_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9e9e9e',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-search-form-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-search-form-input-wrap'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus_colors',
			[
				'label' => esc_html__( 'Focus', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'input_focus_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9e9e9e',
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input:-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_focus_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}}.wpr-search-form-input-focus .wpr-search-form-input-wrap'
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'input_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-search-form-input',
			]
		);

		$this->add_control(
			'input_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
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
					'{{WRAPPER}} .wpr-search-form-input' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_size',
			[
				'label' => esc_html__( 'Border Size', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Button ------------
		$this->start_controls_section(
			'section_style_btn',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'search_btn' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_colors' );

		$this->start_controls_tab(
			'tab_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-search-form-submit',
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);


		$this->add_control(
			'btn_hv_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_hv_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_hv_border_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hv_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-search-form-submit:hover',
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'btn_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 125,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-style-outer .wpr-search-form-submit' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->add_control(
			'btn_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-search-form-style-outer.wpr-search-form-position-right .wpr-search-form-submit' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-search-form-style-outer.wpr-search-form-position-left .wpr-search-form-submit' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'search_btn_style' => 'outer',
				],
			]
		);

		$this->add_control(
			'btn_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'wpr-search-form-position-',
				'separator' => 'before',
			]
		);

		$this->add_control(
            'btn_typography_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-search-form-submit',
			]
		);

		$this->add_control(
			'btn_border_size',
			[
				'label' => esc_html__( 'Border Size', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-search-form-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section();
	}

	protected function render_search_submit_btn() {
		$settings = $this->get_settings();

		$this->add_render_attribute(
		'button', [
			'class' => 'wpr-search-form-submit',
			'type' => 'submit',
		]
		);

		if ( $settings['search_btn_disable_click'] ) {
			$this->add_render_attribute( 'button', 'disabled' );
		}

		if ( 'yes' === $settings['search_btn'] ) : ?>

		<button <?php echo $this->get_render_attribute_string( 'button' ); ?>>
			<?php if ( 'icon' === $settings['search_btn_type'] && '' !== $settings['search_btn_icon']['value'] ) : ?>
				<i class="<?php echo esc_attr( $settings['search_btn_icon']['value'] ); ?>"></i>
			<?php elseif( 'text' === $settings['search_btn_type'] && '' !== $settings['search_btn_text'] ) : ?>
				<?php echo esc_html( $settings['search_btn_text'] ); ?>
			<?php endif; ?>
		</button>

		<?php
		endif;
	}
	
	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		$this->add_render_attribute(
			'input', [
				'placeholder' => $settings['search_placeholder'],
				'class' => 'wpr-search-form-input',
				'type' => 'search',
				'name' => 's',
				'title' => esc_html__( 'Search', 'wpr-addons' ),
				'value' => get_search_query(),
			]
		);

		?>

		<form role="search" method="get" class="wpr-search-form" action="<?php echo home_url(); ?>">

			<div class="wpr-search-form-input-wrap elementor-clearfix">
				<input <?php echo $this->get_render_attribute_string( 'input' ); ?>>
				<?php
				if ( $settings['search_btn_style'] === 'inner' ) {
					$this->render_search_submit_btn();
				}
				?>
			</div>

			<?php

			if ( $settings['search_btn_style'] === 'outer' ) {
				$this->render_search_submit_btn();
			}

			?>
			
		</form>

		<?php

	}

}