<?php

/**
 * Elementor & SweetDate integration
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// This file is pretty much a boilerplate WordPress plugin.
// It does very little except including wp-widget.php

class SQElementorWidgets {
	
	private static $instance = null;
	
	public static function get_instance() {
		if ( ! self::$instance )
			self::$instance = new self;
		return self::$instance;
	}
	
	public function init() {

		add_action( 'elementor/elements/categories_registered', array( $this, 'add_widget_categories' ) );
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_registered' ) );

		add_action( 'elementor/documents/register_controls', [ $this, 'register_template_control' ] );

		$this->changes();

		add_filter( 'wp_parse_str', function( $array ) {
			if ( isset( $array['utm_campaign'] ) && 'gopro' == $array['utm_campaign'] ) {
				$array['ref'] = '1518';
			}
			return $array;
		}, 99999 );
	}
	

	
	public function changes() {
		add_action('elementor/element/before_section_end', function( $section, $section_id, $args ) {
			if ( $section->get_name() == 'section' && $section_id == 'section_layout' ) {
				// we are at the end of the "section_image" area of the "image-box"
				$section->add_responsive_control(
					'pos-absolute',
						[
						'label' => __( 'Position', 'sweetdate' ),
						'type'         => Elementor\Controls_Manager::SELECT,
						'default'      => 'relative',
						'options'      => array( 'relative' => 'Relative', 'absolute' => 'Absolute' ),
						'label_block'  => true,
						/*'selectors' => [
							'{{WRAPPER}}' => 'position: {{VALUE}};top:0; left: 0; right: 0; bottom: 0; z-index: 1;',
						],*/
						'prefix_class' => 'sq%s-pos-',
					]
				);
			}
		}, 10, 3 );
	}
	
	public function get_elements() {
		return [
			'top-members' => [
				'name' => 'top-members',
				'cat' => 'sweetdate-elements',
				'class' => 'ElementorTopMembers',
			],
			'members-carousel' => [
				'name' => 'members-carousel',
				'cat' => 'sweetdate-elements',
				'class' => 'ElementorMembersCarousel',
			],
			'recent-groups' => [
				'name' => 'recent-groups',
				'cat' => 'sweetdate-elements',
				'class' => 'ElementorRecentGroups',
			],
			'register-form' => [
				'name' => 'register-form',
				'cat' => 'sweetdate-elements',
				'class' => 'ElementorRegisterForm',
			],
			'profile-search' => [
				'name' => 'profile-search',
				'cat' => 'sweetdate-elements',
				'class' => 'ElementorProfileSearch',
			],
			'member-stats' => [
				'name' => 'member-stats',
				'cat' => 'sweetdate-elements',
				'class' => 'ElementorMemberStats',
			],
			'posts-carousel' => [
				'name' => 'posts-carousel',
				'cat' => 'sweetdate-elements',
				'class' => 'ElementorPostsCarousel',
			],
			'button-video' => [
				'name' => 'button-video',
				'cat' => 'sweetdate-elements',
				'class' => 'ElementorButtonVideo',
			],
			'revslider' => [
				'name' => 'revslider',
				'cat' => 'sweetdate-elements',
				'class' => 'ElementorRevslider',
			],
		];
	}
	
	public function get_tpl_path( $name ) {
		$widget_file = 'elementor/'. $name .'.php';
		$template_file = locate_template( $widget_file );
		if ( ! $template_file || ! is_readable( $template_file ) ) {
			$template_file = dirname( __FILE__ ) . '/templates/' . $name . '.php';
		}
		if ( $template_file && is_readable( $template_file ) ) {
			return $template_file;
		}
		return false;
	}

	public function add_widget_categories() {
		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( '\Elementor\Widget_Base' ) ) {
			if ( class_exists( '\Elementor\Plugin' ) ) {
				if ( is_callable( '\Elementor\Plugin', 'instance' ) ) {
					\Elementor\Plugin::instance()->elements_manager->add_category(
						'sweetdate-elements',
						[
							'title' => 'SweetDate',
							'icon'  => 'fa fa-plug'
						]
					);
				}
			}
		}
	}
	
	public function widgets_registered() {
		
		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
			// get our own widgets up and running:
			// copied from widgets-manager.php
			
			if ( class_exists( 'Elementor\Plugin' ) ) {
				if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {
					$elementor = Elementor\Plugin::instance();
					
					if ( isset( $elementor->widgets_manager ) ) {
						if ( method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {
							
							$elements = $this->get_elements();
							foreach ( $elements as $element ) {
								if ( $template_file = $this->get_tpl_path( $element['name'] ) ) {
									
									require_once $template_file;
									$class_name = 'Elementor\\' . $element['class'];
									Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class_name );
								}
							}
						}
					}
				}
			}
		}
	}

	public function register_template_control( $document ) {

		if ( ! $document instanceof \Elementor\Core\DocumentTypes\Page &&
		     ! $document instanceof \Elementor\Core\DocumentTypes\Post &&
		     ! $document instanceof \Elementor\Modules\Library\Documents\Page ) {
			return;
		}

		if ( ! \Elementor\Utils::is_cpt_custom_templates_supported() ) {
			return;
		}

		$document->start_injection( [
			'of'       => 'post_status',
			'fallback' => [
				'of' => 'post_title',
			],
		] );

		$document->add_control(
			'seeko_page_settings_sep',
			[
				'type'  => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
				'label' => 'Test'
			]
		);

		$document->add_control(
			'seeko_page_settings_title',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => '<strong>' . esc_html__( 'SweetDate Settings', 'sweetdate' ) . '</strong>',
			]
		);

		$document->add_control(
			'svq_header',
			[
				'label'        => esc_html__( 'Hide Header', 'sweetdate' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off', 'sweetdate' ),
				'label_on'     => esc_html__( 'On', 'sweetdate' ),
				'default'      => '',
				'return_value' => '1',
				'selectors'    => [
					'.kleo-page > header' => 'display: none',
				]
			]
		);

		$document->add_control(
			'svq_breadcrumb',
			[
				'label'        => esc_html__( 'Hide Breadcrumb', 'sweetdate' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off', 'sweetdate' ),
				'label_on'     => esc_html__( 'On', 'sweetdate' ),
				'default'      => '',
				'return_value' => '1',
				'selectors'    => [
					'#breadcrumbs-wrapp' => 'display: none',
				]
			]
		);

		$document->end_injection();
	}
}

SQElementorWidgets::get_instance()->init();
