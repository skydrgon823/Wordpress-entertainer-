<?php

namespace Sweetcore\Widgets;

class Twitter extends \WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => esc_html__( 'Add a customized twitter widget to your site.', 'sweetdate' ) );
		parent::__construct( 'kleo_twitter', esc_html__( '(Sweet) Twitter Widget', 'sweetdate' ), $widget_ops );

		require_once( SWEETCORE_PATH . 'inc/Widgets/twitter/twitter-feed-for-developers.php' );
		//enqueue twitter script
		add_action( 'wp_enqueue_scripts', array( $this, 'twitter_javascript' ) );
	}

	function twitter_javascript() {
		wp_enqueue_script( 'jquery-tweet' );
	}


	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$numb  = $instance['numb'];

		echo $before_widget;

// Display the widget title
		if ( $title ) {
			echo $before_title . '<i class="icon-twitter"></i> ' . $title . $after_title;
		}

		$opt_args = array(
			'trim_user'       => false,
			'exclude_replies' => false,
			'include_rts'     => true
		);
		$tweets   = getTweets( $numb, false, $opt_args );
		if ( is_array( $tweets ) && ! isset( $tweets['error'] ) ) {

// to use with intents
			echo "<div class='twitter_wrap'>";
			echo "<ul class='tweet_list'>";

			foreach ( $tweets as $tweet ) {

				echo '<li class="clearfix">';
				echo '<div class="tweet_item">';
				echo '<div class="tweet_content">';
				$user = $tweet['user'];


// Tweet author name
				if ( array_key_exists( 'name', $user ) ) {
					$name = $user['name'];
				}
// Tweet author @username
				if ( array_key_exists( 'screen_name', $user ) ) {
					$screen_name = $user['screen_name'];
				}

				if ( ! $name ) {
					$name = 'YOURUSERNAME';
				}
				if ( ! $screen_name ) {
					$screen_name = 'YOURUSERNAME';
				}


				if ( $tweet['text'] ) {
					$the_tweet = $tweet['text'];

					if ( is_array( $tweet['entities']['user_mentions'] ) ) {
						foreach ( $tweet['entities']['user_mentions'] as $key => $user_mention ) {
							$the_tweet = preg_replace(
								'/@' . $user_mention['screen_name'] . '/i',
								'<a href="http://www.twitter.com/' . $user_mention['screen_name'] . '" target="_blank">@' . $user_mention['screen_name'] . '</a>',
								$the_tweet );
						}
					}

					if ( is_array( $tweet['entities']['hashtags'] ) ) {
						foreach ( $tweet['entities']['hashtags'] as $key => $hashtag ) {
							$the_tweet = preg_replace(
								'/#' . $hashtag['text'] . '/i',
								'<a href="https://twitter.com/search?q=%23' . $hashtag['text'] . '&amp;src=hash" target="_blank">#' . $hashtag['text'] . '</a>',
								$the_tweet );
						}
					}

					if ( is_array( $tweet['entities']['urls'] ) ) {
						foreach ( $tweet['entities']['urls'] as $key => $link ) {
							$the_tweet = preg_replace(
								'`' . $link['url'] . '`',
								'<a href="' . $link['url'] . '" target="_blank">' . $link['url'] . '</a>',
								$the_tweet );
						}
					}

					echo '<div class="tweet_txt">' . $the_tweet . '</div>';


					echo '
    <span class="tweet_time">
    <a href="https://twitter.com/' . $screen_name . '/status/' . $tweet['id_str'] . '" target="_blank">
    ' . date( 'd M', strtotime( $tweet['created_at'] ) ) . '
    </a>
    </span>';

				}
				echo '</div>';
				echo '</div>';
				echo '</li>';
			}
			echo "</ul>";
			echo "</div>";
		} else {
			if ( isset( $tweets['error'] ) ) {
				echo $tweets['error'];
			} else {
				esc_html_e( 'No tweets found', 'sweetdate' );
			}
		}

		echo $after_widget;

	}   // display the widget

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

//Strip tags from title and name to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['numb']  = strip_tags( $new_instance['numb'] );

		return $instance;
	}   // update the widget

	function form( $instance ) {
//Set up some default widget settings.
		$defaults = array(
			'title'     => __( 'Latest Tweets', KLEO_DOMAIN ),
			'numb'      => '3',
			'show_info' => true
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

// Widget Title: Text Input  ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', KLEO_DOMAIN ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"/>
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'numb' ); ?>"><?php esc_html_e( 'Number of Tweets:', KLEO_DOMAIN ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'numb' ); ?>" class="widefat"
			       name="<?php echo $this->get_field_name( 'numb' ); ?>" value="<?php echo $instance['numb']; ?>"/>
		</p>
	<?php }  // and of course the form for the widget options
}
