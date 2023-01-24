<?php

namespace Sweetcore\Widgets;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class About_Us extends \WP_Widget {

	/**
	 * Widget setup
	 */
	function __construct() {
		$widget_ops = array(
			'description' => esc_html__( 'Text and contact information.', 'sweetdate' )
		);
		parent::__construct( 'kleo_about_us', esc_html__( '(Sweet) About us', 'sweetdate' ), $widget_ops );
	}

	/**
	 * Display widget
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$title = apply_filters( 'widget_title', $instance['title'] );

		$textarea = $instance['textarea'];
		$social   = $instance['social'];
		$contact  = $instance['contact'];

		echo $before_widget;

		if ( ! empty( $title ) ) {
			echo $before_title . '<i class="icon-' . apply_filters( 'kleo_widget_aboutus_icon', 'heart' ) . '"></i> ' . $title . $after_title;
		}
		?>

		<?php if ( $textarea ) : ?>
            <p><?php echo $textarea; ?></p>
		<?php endif; ?>

		<?php if ( $contact == true && ( sq_option( 'owner_email' ) || sq_option( 'owner_phone' ) ) ) : ?>
            <p>
				<?php if ( sq_option( 'owner_email' ) ): ?>
                    <i class="icon-envelope"></i> &nbsp;<a
                            href="mailto:<?php echo sq_option( 'owner_email' ); ?>"><?php echo sq_option( 'owner_email' ); ?></a>
                    <br>&nbsp;
				<?php endif; ?>
				<?php if ( sq_option( 'owner_phone' ) ): ?>
                    <i class="icon-mobile-phone icon-large"></i> &nbsp;&nbsp;<a
                            href="#"><?php echo sq_option( 'owner_phone' ); ?></a>
				<?php endif; ?>
            </p>
		<?php endif; ?>

		<?php if ( $social == true ) : ?>
            <p class="footer-social-icons"><?php _e( "Stay in touch", 'sweetdate' ); ?>:<br>
				<?php if ( sq_option( 'twitter' ) ): ?>
                    <a href="<?php echo sq_option( 'twitter' ); ?>" class="has-tip tip-top" data-width="210"
                       title="<?php _e( "Follow us on", 'sweetdate' ); ?> Twitter"><i
                                class="icon-twitter-sign icon-2x"></i></a>
				<?php endif; ?>
				<?php if ( sq_option( 'facebook' ) ): ?>
                    <a href="<?php echo sq_option( 'facebook' ); ?>" class="has-tip tip-top" data-width="210"
                       title="<?php _e( "Find us on", 'sweetdate' ); ?> Facebook"><i
                                class="icon-facebook-sign icon-2x"></i></a>
				<?php endif; ?>

	            <?php if ( sq_option( 'instagram' ) ): ?>
                    <a href="<?php echo esc_url( sq_option( 'instagram' ) ); ?>"
                       class="has-tip tip-top"
                       data-width="210" target="_blank"
                       title="<?php esc_attr_e( "Follow us on", 'sweetdate' ); ?> Instagram">
                        <i class="icon-instagram icon-large"></i>
                    </a>
	            <?php endif; ?>

	            <?php if ( sq_option( 'youtube' ) ): ?>
                    <a href="<?php echo esc_url( sq_option( 'youtube' ) ); ?>"
                       class="has-tip tip-top"
                       data-width="210" target="_blank"
                       title="<?php esc_attr_e( "Follow us on", 'sweetdate' ); ?> Youtube">
                        <i class="icon-youtube icon-large"></i>
                    </a>
	            <?php endif; ?>
                
				<?php if ( sq_option( 'googleplus' ) ): ?>
                    <a href="<?php echo sq_option( 'googleplus' ); ?>" class="has-tip tip-top" data-width="210"
                       title="<?php _e( "Find us on", 'sweetdate' ); ?> Google+"><i
                                class="icon-google-plus-sign icon-2x"></i></a>
				<?php endif; ?>
				<?php if ( sq_option( 'pinterest' ) ): ?>
                    <a href="<?php echo sq_option( 'pinterest' ); ?>" class="has-tip tip-top" data-width="210"
                       title="<?php _e( "Pin us on", 'sweetdate' ); ?> Pinterest"><i
                                class="icon-pinterest-sign icon-2x"></i></a>
				<?php endif; ?>
				<?php if ( sq_option( 'linkedin' ) ): ?>
                    <a href="<?php echo sq_option( 'linkedin' ); ?>" class="has-tip tip-top" data-width="210"
                       title="<?php _e( "Find us on", 'sweetdate' ); ?> LinkedIn"><i
                                class="icon-linkedin-sign icon-2x"></i></a>
				<?php endif; ?>

				<?php do_action( 'kleo_extra_social_icons' ); ?>
            </p>
		<?php endif; ?>

		<?php

		echo $after_widget;

	}

	/**
	 * Update widget
	 */
	function update( $new_instance, $old_instance ) {

		$instance             = $old_instance;
		$instance['title']    = esc_attr( $new_instance['title'] );
		$instance['textarea'] = $new_instance['textarea'];
		$instance['social']   = $new_instance['social'];
		$instance['contact']  = $new_instance['contact'];

		return $instance;

	}

	/**
	 * Widget setting
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title'    => '',
			'textarea' => '',
			'social'   => true,
			'contact'  => true,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title    = esc_attr( $instance['title'] );
		if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'icl_translate' ) ) {
			$textarea = icl_translate( 'sweetdate', 'about_us_widget_text', $instance['textarea'] );
		} else {
			$textarea = $instance['textarea'];
		}
		$social  = $instance['social'];
		$contact = $instance['contact'];
		?>

        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'sweetdate' ); ?></label>
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
                    for="<?php echo esc_attr( $this->get_field_id( 'contact' ) ); ?>"><?php _e( 'Display Email/Phone?', 'sweetdate' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'contact' ); ?>"
                   name="<?php echo $this->get_field_name( 'contact' ); ?>" type="checkbox"
                   value="1" <?php checked( '1', $contact ); ?> />&nbsp;
        </p>

        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'social' ) ); ?>"><?php _e( 'Display Social icons?', 'sweetdate' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'social' ); ?>"
                   name="<?php echo $this->get_field_name( 'social' ); ?>" type="checkbox"
                   value="1" <?php checked( '1', $social ); ?> />&nbsp;
        </p>

		<?php
	}

}
