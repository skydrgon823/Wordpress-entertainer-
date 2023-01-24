<?php
/**
 * Elementor Widget.
 *
 *
 * @since 1.0.0
 */
class Mobile_Menu_El_Menu_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mobile_menu_left_button';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Mobile Left Button', 'mobile-menu' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general, mobile' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'mobile_menu_section',
			[
				'label' => __( 'Settings', 'mobile-menu' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		
		$this->add_control(
			'btn_color',
			[
				'label'   => __( 'Icon Color', 'mobile-menu' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => __( '#0070c9', 'mobile-menu' ),
			]
		);
				
		$this->add_control(

			'btn_size',
			[
				'label'       => __( 'Font Size', 'mobile-menu' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => __( '0', 'mobile-menu' ),
				'default'     => '25',
			]
		);

		$this->add_control(

			'btn_padding',
			[
				'label'       => __( 'Padding', 'mobile-menu' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => array('top' =>'10', 'right' => '25', 'bottom' => '10', 'left' => '25', 'isLinked' => false),
				'selectors' => [
					'{{WRAPPER}} .pws-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		global $mobile_menu_instance;
		$plugin_settings = MobileMenuOptions::getInstance( 'mobmenu' );
		$menu_type   = 'left';
		$menu_text   = $plugin_settings->getOption( $menu_type . '_menu_text' );
		$icon_action = $plugin_settings->getOption( $menu_type . '_menu_icon_action' );
		$icon_target = $plugin_settings->getOption( $menu_type . '_icon_url_target' );
		$icon_url    = $plugin_settings->getOption( $menu_type . '_icon_url' );
		$icon_font   = $plugin_settings->getOption( $menu_type . '_menu_icon_font' );
		$icon        = $plugin_settings->getOption( $menu_type . '_menu_icon' );
		$icon_new    = $plugin_settings->getOption( $menu_type . '_menu_icon_new' );
		$animation   = $plugin_settings->getOption( $menu_type . '_menu_icon_animation' );
		$close_icon  = $plugin_settings->getOption( 'close_icon_font' );
		$mobile_menu_instance->mobmenu_core->frontend_enqueue_scripts();
		echo $mobile_menu_instance->mobmenu_core->load_mobile_menu_html( $menu_type, $menu_text, $icon_action, $icon_target, $icon_url, $icon_font, $icon, $icon_new, $animation, $close_icon);

	}

}
