<?php

namespace Sweetcore\Widgets;

class Search_Form extends \WP_Widget {

	/**
	 * Widget setup
	 */
	function __construct() {

		$widget_ops = array(
			'description' => esc_html__( 'Members search form', 'sweetdate' )
		);
		parent::__construct( 'kleo_bp_profile_search', esc_html__( '(Sweet) Members search form', 'sweetdate' ), $widget_ops );
	}

	/**
	 * Display widget
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$title = apply_filters( 'widget_title', $instance['title'] );

		$textarea      = isset( $instance['textarea'] ) ? $instance['textarea'] : "";
		$show_profiles = isset( $instance['show_profiles'] ) ? $instance['show_profiles'] : false;

		echo $before_widget;

		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		kleo_bp_search_form( $show_profiles, $textarea );

		echo $after_widget;

	}

	/**
	 * Update widget
	 */
	function update( $new_instance, $old_instance ) {

		$instance                  = $old_instance;
		$instance['title']         = esc_attr( $new_instance['title'] );
		$instance['textarea']      = $new_instance['textarea'];
		$instance['show_profiles'] = $new_instance['show_profiles'];

		return $instance;

	}

	/**
	 * Widget setting
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults      = array(
			'title'         => '',
			'textarea'      => '',
			'show_profiles' => false,
		);
		$instance      = wp_parse_args( (array) $instance, $defaults );
		$title         = esc_attr( $instance['title'] );
		$textarea      = $instance['textarea'];
		$show_profiles = $instance['show_profiles'];
		?>

		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'sweetdate' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
			       value="<?php echo $title; ?>"/>
		</p>

		<p>
			<label><?php esc_html_e( "Text before search form", 'sweetdate' ); ?></label>
			<textarea class='widefat'
			          name="<?php echo $this->get_field_name( 'textarea' ); ?>"><?php echo $textarea; ?></textarea>
		</p>

		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'show_profiles' ) ); ?>">
				<?php esc_html_e( 'Show profiles carousel', 'sweetdate' ); ?>
			</label>
			<input id="<?php echo $this->get_field_id( 'show_profiles' ); ?>"
			       name="<?php echo $this->get_field_name( 'show_profiles' ); ?>" type="checkbox"
			       value="1" <?php checked( '1', $show_profiles ); ?> />&nbsp;
		</p>


		<?php
	}

}