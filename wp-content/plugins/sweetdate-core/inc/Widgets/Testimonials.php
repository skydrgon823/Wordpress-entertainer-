<?php

namespace Sweetcore\Widgets;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Testimonials extends \WP_Widget {

	/**
	 * Widget setup
	 */
	function __construct() {

		$widget_ops = array(
			'description' => esc_html__( 'Testimonials widget.', 'sweetdate' )
		);
		parent::__construct( 'kleo_testimonials', esc_html__( '(Sweet) Testimonials', 'sweetdate' ), $widget_ops );
	}

	/**
	 * Display widget
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$title     = apply_filters( 'widget_title', $instance['title'] );
		$limit     = $instance['limit'];
		$post_type = 'kleo-testimonials';

		echo $before_widget;

		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		$args = array(
			'posts_per_page' => $limit,
			'post_type'      => $post_type
		);
		query_posts( $args );

		?>

		<ul class="testimonials-carousel">
			<?php
			$count = 0;

			if ( have_posts() ) : while ( have_posts() ) : the_post();
				$count ++; ?>
				<li <?php if ( $count != 1 ) {
					echo 'class="hide-on-mobile" ';
				} ?>>
					<div class="quote-content">
						<i class="icon-quote-right iconq"></i>
						<?php the_content(); ?>
					</div>
					<div class="quote-author">
						<strong><?php the_title(); ?></strong>
						<span class="author-description"> - <?php the_cfield( 'author_description' ); ?></span>
					</div>
				</li>
			<?php endwhile; endif; ?>
			<?php wp_reset_query(); ?>

		</ul>

		<?php

		echo $after_widget;

	}

	/**
	 * Update widget
	 */
	function update( $new_instance, $old_instance ) {

		$instance          = $old_instance;
		$instance['title'] = esc_attr( $new_instance['title'] );
		$instance['limit'] = $new_instance['limit'];

		delete_transient( 'kleo_testimonials_' . $this->id );

		return $instance;

	}

	/**
	 * Widget setting
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => '',
			'limit' => 5,
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		$title    = esc_attr( $instance['title'] );
		$limit    = $instance['limit'];

		?>

		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'sweetdate' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
			       value="<?php echo $title; ?>"/>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Limit:', 'sweetdate' ); ?></label>
			<select class="widefat" name="<?php echo $this->get_field_name( 'limit' ); ?>"
			        id="<?php echo $this->get_field_id( 'limit' ); ?>">
				<?php for ( $i = 1; $i <= 20; $i ++ ) { ?>
					<option <?php selected( $limit, $i ) ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>

		<?php
	}

}
