<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class ElementorProfileSearch extends Widget_Base {

	public function get_name() {
		return 'profile-search';
	}

	public function get_title() {
		return __( 'Profile Search', 'sweetdate' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return [ 'sweetdate-elements' ];
	}

	public function get_forms() {
		$data  = [];
		$query = new \WP_Query( array( 'post_type' => 'bps_form' ) );

		while ( $query->have_posts() ) : $query->the_post();
			$data[ get_the_ID() ] = get_the_title();
		endwhile;
		wp_reset_postdata();

		return $data;

	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_profile_search',
			[
				'label' => __( 'Settings', 'sweetdate' ),
			]
		);

		$this->add_control(
			'form',
			[
				'label'   => __( 'Form', 'sweetdate' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->get_forms(),
				'default' => 'all',
			]
		);

		$this->add_control(
			'profiles',
			[
				'label'        => __( 'Show Profiles', 'sweetdate' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'No', 'sweetdate' ),
				'label_on'     => __( 'Yes', 'sweetdate' ),
				'default'      => '1',
				'return_value' => '1'
			]
		);

		$this->add_control(
			'details',
			[
				'label'   => __( 'Details', 'sweetdate' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => __( 'Serious dating with <strong>Sweet date</strong>.<br>Your perfect match is just a click away', 'sweetdate' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'label'    => __( 'Typography', 'elementor' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .form-header, {{WRAPPER}} .form-header p',
			]
		);

		$this->add_control(
			'visibility',
			[
				'label'   => __( 'Visibility', 'sweetdate' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => [
					'all'       => 'Everyone',
					'logged-in' => 'Logged-in Users',
					'guest'     => 'Guest users'
				],
				'default' => 'all',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();
		if ( $settings['visibility'] == '' || $settings['visibility'] == 'all'
		     || ( $settings['visibility'] == 'logged-in' && is_user_logged_in() )
		     || ( $settings['visibility'] == 'guest' && ! is_user_logged_in() )
		) {

			if ( $settings['form'] ) {
				$shortcode = sprintf( '[bps_form id=%s]', $settings['form'] );
			} else {
				$shortcode = '';
			}
			$class = '';
			if ( ! $settings['profiles'] ) {
				$class .= ' hide-notch';
			}

			echo '<div class="form-wrapper' . $class . '">';

			echo '<div class="form-header">';
			echo wp_kses_post( $settings['details'] );
			echo '</div>';

			echo do_shortcode( $shortcode );
			if ( $settings['profiles'] ) {
				echo '<div class="form-footer">';

				do_action( 'kleo_bps_before_carousel' );

				echo do_shortcode( '[kleo_members_carousel]' );
				echo '</div>';
			}

			echo '</div>';

		} elseif ( Plugin::$instance->editor->is_edit_mode() ) {
			echo '<div class="make-me-visible">Search Form. Visibility setting is preventing this to show element!</div>';
		}
	}

}
