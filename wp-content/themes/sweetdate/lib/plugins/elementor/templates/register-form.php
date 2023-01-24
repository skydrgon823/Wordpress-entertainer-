<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ElementorRegisterForm extends Widget_Base {
	
	public function get_name() {
		return 'register-form';
	}
	public function get_title() {
		return __( 'Register Form', 'sweetdate' );
	}
	public function get_icon() {
		return 'eicon-checkbox';
	}
	public function get_categories() {
		return [ 'sweetdate-elements' ];
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_register_form',
			[
				'label' => __( 'Settings', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'profiles',
			[
				'label' => __( 'Show Profiles', 'sweetdate' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'sweetdate' ),
				'label_on' => __( 'Yes', 'sweetdate' ),
				'default' => '1',
				'return_value' => '1'
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'sweetdate' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Create an Account', 'sweetdate' ),
				'placeholder' => __( 'Custom title', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'details',
			[
				'label' => __( 'Details', 'sweetdate' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __( 'Registering for this site is easy, just fill in the fields below and we will get a new account set up for you in no time', 'sweetdate' ),
			]
		);
		
		$this->add_control(
			'visibility',
			[
				'label' => __( 'Visibility', 'sweetdate' ),
				'type' => Controls_Manager::SELECT2,
				'options' => [
					'all' => 'Everyone',
					'logged-in' => 'Logged-in Users',
					'guest' => 'Guest users'
				],
				'default' => 'all',
			]
		);
		
		$this->end_controls_section();
	
	}
	protected function render() {
		$settings = $this->get_settings();
		if ( $settings['visibility'] == '' || $settings['visibility'] == 'all'
			|| ( $settings['visibility'] =='logged-in' && is_user_logged_in() )
		     || ( $settings['visibility'] == 'guest' && ! is_user_logged_in() )
		) {
			$shortcode = sprintf( '[kleo_register_form profiles="%s" title="%s" details="%s"]', $settings['profiles'], $settings['title'], $settings['details'] );
			echo do_shortcode( $shortcode );
		} elseif ( Plugin::$instance->editor->is_edit_mode() ) {
			echo '<div class="make-me-visible">Register Form. Visibility setting is preventing this to show element!</div>';
		}
	}
	
}
