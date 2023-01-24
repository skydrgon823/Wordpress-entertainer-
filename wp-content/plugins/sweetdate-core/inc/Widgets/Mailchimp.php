<?php

namespace Sweetcore\Widgets;

/**
 * Navigation Menu widget class
 *
 * @since 3.0.0
 */
class Mailchimp extends \WP_Widget {

	public $api_key;
	public $opt_in;

	function __construct() {
		$widget_ops = array( 'description' => esc_html__( 'Mailchimp newsletter subscribe form.', 'sweetdate' ) );
		parent::__construct( 'kleo_mailchimp', esc_html__( '(Sweet) Mailchimp Newsletter', 'sweetdate' ), $widget_ops );
		$this->api_key = trim( sq_option( 'mailchimp_api', '' ) );
	}

	function widget( $args, $instance ) {

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		if ( is_super_admin() && ! isset( $instance['mailchimp_list'] ) ) {
			echo '<p class="alert alert-warning" role="alert">' .
			     esc_html__( 'Warning! Your subscription form will not show up until you will select a Mailchimp list for your subscribers.', 'sweetdate-core' ) . '</p>';

			return;
		} elseif ( ! isset( $instance['mailchimp_list'] ) ) {
			return;
		}

		//ajax fallback
		if ( isset ( $_POST['mc_email'] ) && $instance['mailchimp_list'] == $_POST['list'] ) {

			if ( isset ( $this->api_key ) && ! empty ( $this->api_key ) ) {

				include_once( SWEETCORE_PATH . 'inc/Widgets/mailchimp/MCAPI.class.php' );

				$mcapi = new \SVQ_MCAPI( $this->api_key );

				$list_id = $instance['mailchimp_list'];
				$opt_in  = sq_option( 'mailchimp_opt_in', 'yes' ) == 'yes' ? 'pending' : 'subscribed';

				$result = $mcapi->post( "lists/" . $list_id . "/members", [
					'email_address' => $_POST['mc_email'],
					'status'        => $opt_in,
					"merge_fields"  => array(
						"NAME" => "{$_POST['yname']}",
					)
				] );

				if ( is_array( $result ) && isset( $result['status'] ) && $result['status'] === $opt_in ) {
					// Everything ok
					if ( $opt_in === 'pending' ) {
						$msg = esc_html__( 'Success!&nbsp; Check your inbox or spam folder for a message containing a confirmation link.', 'sweetdate' );
					} else {
						$msg = esc_html__( 'You have successfully subscribed.', 'sweetdate' );
					}

					$msg = '<span style="color:green;">' . $msg . '</span>';
				} else {
					// An error occurred, return error message
					$msg = '<span style="color:red;"><b>' . esc_html__( 'Error:', 'sweetdate' ) . '</b>&nbsp; ' .
					       esc_html__( 'This email was already subscribed or it is invalid.', 'sweetdate-core' ) . '</span>';
				}
			}
		}

		echo $args['before_widget'];
		echo '<div class="panel">';
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . '<i class="icon-thumbs-up"></i> ' . $instance['title'] . $args['after_title'];
		}

		//Before text
		if ( ! empty( $instance['mailchimp_before_text'] ) ) :
			echo '<p>' . $instance['mailchimp_before_text'] . '</p>';
		endif;


		echo '
		  <!--Newsletter form-->
		  <form id="newsletter-form" name="newsletter-form" data-url="' . trailingslashit( home_url() ) . '" method="post" class="row newsletter-form">
			<input type="hidden" id="list" class="mc_list" name="list" value="' . $instance['mailchimp_list'] . '" />
			<div class="' . ( isset( $args['id'] ) && ( strpos( $args['id'], 'footer' ) !== false ) ? "four" : "twelve" ) . ' columns">
			  <div class="row collapse">
				<div class="two mobile-one columns">
						<span class="prefix"><i class="icon-user"></i></span>
				</div>
				<div class="ten mobile-three columns">
						<input type="text" class="mc_yname" name="yname" id="yname" placeholder="' . esc_html__( "Your name", 'sweetdate' ) . '" required>
				</div>
			  </div>
			</div>
			<div class="' . ( isset( $args['id'] ) && ( strpos( $args['id'], 'footer' ) !== false ) ? "five" : "twelve" ) . ' columns">
			  <div class="row collapse">
				<div class="two mobile-one columns">
						<span class="prefix"><i class="icon-envelope"></i></span>
				</div>
				<div class="ten mobile-three columns">
						<input type="email" name="mc_email" class="mc_email" id="mc_email" placeholder="' . esc_html__( "Your email", 'sweetdate' ) . '" required>
				</div>
			  </div>
			</div>
			<div class="' . ( isset( $args['id'] ) && ( strpos( $args['id'], 'footer' ) !== false ) ? "three" : "six" ) . ' columns">
				<p><button type="submit" id="newsletter-submit" name="newsletter-submit" class="small radius button expand">' . esc_html__( "JOIN US", 'sweetdate' ) . '</button></p>
			</div>
			<div class="twelve column">

			  <div><small id="result" class="mc_result">' . ( isset ( $msg ) ? $msg : '' ) . '</small></div>';

		// After text
		if ( ! empty( $instance['mailchimp_after_text'] ) ) {
			echo $instance['mailchimp_after_text'];
		}

		echo '</div>
		  </form><!--end newsletter-form-->';


		$nonce = wp_create_nonce( "mc_mail" );
		echo "<script type='text/javascript'>
			jQuery(document).ready(function($) {  
				// Prepare the Newsletter and send data to Mailchimp
				
				$('.newsletter-form').each(function() {
					$(this).submit(function() {
						var container = $(this);
							$.ajax({
									url: ajaxurl,
									type: 'POST',
									data: {
											action: 'mc_action',
											mc_email: $('.mc_email', container).attr('value'),
											yname: $('.mc_yname', container).attr('value'),
											list: $('.mc_list', container).attr('value'),
											nonce: '" . $nonce . "'
									},
									success: function(data){
											$('.mc_result', container).html(data).css('color', 'green');
									},
									error: function() {
											$('.mc_result', container).html('Sorry, an error occurred.').css('color', 'red');
									}

							});
							return false;
					});
				});


			});
		</script>";
		echo '</div><!--end panel-->';
		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance['title']                 = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['mailchimp_before_text'] = stripslashes( $new_instance['mailchimp_before_text'] );
		$instance['mailchimp_after_text']  = stripslashes( $new_instance['mailchimp_after_text'] );
		$instance['mailchimp_list']        = $new_instance['mailchimp_list'];
		$instance['opt_in']                = $new_instance['opt_in'];

		return $instance;
	}

	function form( $instance ) {
		$title                 = isset( $instance['title'] ) ? $instance['title'] : '';
		$mailchimp_before_text = isset( $instance['mailchimp_before_text'] ) ? $instance['mailchimp_before_text'] : '';
		$mailchimp_after_text  = isset( $instance['mailchimp_after_text'] ) ? $instance['mailchimp_after_text'] : '';
		$mailchimp_list        = isset( $instance['mailchimp_list'] ) ? $instance['mailchimp_list'] : '';
		$opt_in                = isset( $instance['opt_in'] ) ? $instance['opt_in'] : '';

		if ( ! function_exists( 'curl_init' ) ) {
			echo esc_html__( 'Curl is not enabled. Please contact your hosting company and ask them to enable CURL.', 'sweetdate' );

			return;
		}

		if ( ! isset ( $this->api_key ) || empty ( $this->api_key ) ) {
			echo esc_html__( 'You need to enter your MailChimp API_KEY in theme options before using this widget.', 'sweetdate' );

			return;
		}

		include_once( SWEETCORE_PATH . 'inc/Widgets/mailchimp/MCAPI.class.php' );

		$lists = [];
		if ( ! empty ( $this->api_key ) ) {
			$mcapi = new \SVQ_MCAPI( $this->api_key );
			$lists = $mcapi->get( 'lists' );
		}

		?>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'sweetdate' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>"/>
		</p>

		<p>
			<label
				for="<?php echo $this->get_field_id( 'mailchimp_list' ); ?>"><?php esc_html_e( 'Select List:', 'sweetdate' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'mailchimp_list' ); ?>"
			        name="<?php echo $this->get_field_name( 'mailchimp_list' ); ?>">
				<?php
				if ( ! empty( $lists ) && isset( $lists['lists'] ) && is_array( $lists['lists'] ) ) {
					foreach ( $lists['lists'] as $list ) {
						$selected = ( isset( $mailchimp_list ) && $mailchimp_list == $list['id'] ) ? ' selected="selected" ' : '';
						?>
						<option
							<?php echo $selected; ?>value="<?php echo $list['id']; ?>"><?php echo $list['name']; ?></option>
						<?php
					}
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'opt_in' ); ?>">
				<?php esc_html_e( 'Double opt-in:', 'sweetdate' ); ?>
			</label>
			<select id="<?php echo $this->get_field_id( 'opt_in' ); ?>"
			        name="<?php echo $this->get_field_name( 'opt_in' ); ?>">
				<option <?php if ( isset( $opt_in ) && $opt_in == 'yes' ) {
					echo 'selected="selected"';
				} ?> value="yes"><?php _e( "Yes" ); ?></option>
				<option <?php if ( isset( $opt_in ) && $opt_in == 'no' ) {
					echo 'selected="selected"';
				} ?> value="no"><?php _e( "No" ); ?></option>
			</select>
		</p>

		<p>
		<div><label for="<?php echo $this->get_field_id( 'mailchimp_before_text' ); ?>">
				<?php echo esc_html__( 'Text before form :', 'sweetdate' ); ?>
			</label>
		</div>
		<div><textarea class="widefat" id="<?php echo $this->get_field_id( 'mailchimp_before_text' ); ?>"
		               name="<?php echo $this->get_field_name( 'mailchimp_before_text' ); ?>"
		               rows="5"><?php echo $mailchimp_before_text; ?></textarea></div>
		</p>
		<p>
		<div><label for="<?php echo $this->get_field_id( 'mailchimp_after_text' ); ?>">
				<?php echo esc_html__( ' Text after form:', 'sweetdate' ); ?>
			</label>
		</div>
		<div><textarea class="widefat" id="<?php echo $this->get_field_id( 'mailchimp_after_text' ); ?>"
		               name="<?php echo $this->get_field_name( 'mailchimp_after_text' ); ?>"
		               rows="5"><?php echo $mailchimp_after_text; ?></textarea></div>
		</p>

		<?php
	}
}

