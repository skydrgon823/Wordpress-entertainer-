<?php

namespace Sweetcore\Widgets;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Support_Box extends \WP_Widget {

	/**
	 * Widget setup
	 */
	function __construct() {

		$widget_ops = array(
			'description' => esc_html__( 'Support information.', 'sweetdate' )
		);
		parent::__construct( 'kleo_support_box', esc_html__( '(Sweet) Support box', 'sweetdate' ), $widget_ops );
	}

	/**
	 * Display widget
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$title = apply_filters( 'widget_title', $instance['title'] );

		$textarea    = $instance['textarea'];
		$link        = $instance['link'];
		$button_text = $instance['button_text'];
		echo $before_widget;

		echo '<div class="panel">';
		if ( ! empty( $title ) ) {
			echo $before_title . '<i class="icon-question-sign"></i> ' . $title . $after_title;
		}

		?>

		<?php if ( $textarea ) : ?>
			<p><?php echo $textarea; ?></p>
		<?php endif; ?>

		<?php if ( $link ) : ?>
			<a target="_blank" class="small button radius secondary" href="<?php echo $link; ?>"><i
					class="icon-angle-right"></i> <?php echo $button_text; ?></a>
		<?php endif;

		echo '</div>';

		echo $after_widget;

	}

	/**
	 * Update widget
	 */
	function update( $new_instance, $old_instance ) {

		$instance                = $old_instance;
		$instance['title']       = esc_attr( $new_instance['title'] );
		$instance['textarea']    = $new_instance['textarea'];
		$instance['link']        = $new_instance['link'];
		$instance['button_text'] = $new_instance['button_text'];

		return $instance;

	}

	/**
	 * Widget setting
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults    = array(
			'title'       => '',
			'textarea'    => '',
			'link'        => '',
			'button_text' => '',
		);
		$instance    = wp_parse_args( (array) $instance, $defaults );
		$title       = esc_attr( $instance['title'] );
		$textarea    = $instance['textarea'];
		$link        = $instance['link'];
		$button_text = $instance['button_text'];
		?>

		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'sweetdate' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
			       value="<?php echo $title; ?>"/>
		</p>

		<p>
			<textarea class='widefat' name="<?php echo $this->get_field_name( 'textarea' ); ?>">
				<?php echo $textarea; ?>
			</textarea>
		</p>

		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e( 'Button link', 'sweetdate' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'link' ); ?>"
			       name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo $link; ?>"/>&nbsp;
		</p>

		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button text', 'sweetdate' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'button_text' ); ?>"
			       name="<?php echo $this->get_field_name( 'button_text' ); ?>" type="text"
			       value="<?php echo $button_text; ?>"/>&nbsp;
		</p>

		<?php
	}

}
