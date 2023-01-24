<?php

/***************************************************
 * Lost password function - used in Ajax action
 **************************************************/
if ( ! function_exists( 'kleo_lost_password_ajax' ) ) :
	function kleo_lost_password_ajax() {
		global $wpdb;
		$errors = array();
		if ( isset( $_POST ) ) {

			if ( empty( $_POST['email'] ) ) {
				echo wp_kses_post( __( '<strong>ERROR</strong>: The e-mail field is empty.', 'sweetdate' ) );
				die();
			} else {
				do_action( 'lostpassword_post' );
				// redefining user_login ensures we return the right case in the email
				$user_data = get_user_by( 'email', trim( $_POST['email'] ) );

				if ( ! isset( $user_data->user_email ) || strtolower( $user_data->user_email ) != strtolower( $_POST['email'] ) ) {
					echo wp_kses_post( __( '<strong>ERROR</strong>: Invalid  e-mail.', 'sweetdate' ) );
					die();
				} else {
					$user_login = $user_data->user_login;
					$user_email = $user_data->user_email;

					do_action( 'retrieve_password', $user_login );

					$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

					if ( ! $allow ) {
						esc_html_e( 'Password reset is not allowed for this user', 'default' );
					} else if ( is_wp_error( $allow ) ) {
						echo $allow;
					}

					// Generate something random for a password reset key.
					$key = wp_generate_password( 20, false );

					do_action( 'retrieve_password_key', $user_login, $key );

					// Now insert the key, hashed, into the DB.
					if ( empty( $wp_hasher ) ) {
						require_once ABSPATH . 'wp-includes/class-phpass.php';
						$wp_hasher = new PasswordHash( 8, true );
					}
					$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
					$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );

					$message = esc_html__( 'Someone has requested a password reset for the following account:', 'default' ) . "\r\n\r\n";
					$message .= network_home_url( '/' ) . "\r\n\r\n";
					$message .= sprintf( esc_html__( 'Username: %s', 'default' ), $user_login ) . "\r\n\r\n";
					$message .= esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.', 'default' ) . "\r\n\r\n";
					$message .= esc_html__( 'To reset your password, visit the following address:', 'default' ) . "\r\n\r\n";
					$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

					if ( is_multisite() ) {
						$blogname = $GLOBALS['current_site']->site_name;
					} else
						// The blogname option is escaped with esc_html on the way into the database in sanitize_option
						// we want to reverse this for the plain text arena of emails.
					{
						$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
					}

					$title = sprintf( __( '[%s] Password Reset', 'default' ), $blogname );

					/**
					 * Filter the subject of the password reset email.
					 *
					 * @since 2.8.0
					 *
					 * @param string $title Default email title.
					 */
					$title = apply_filters( 'retrieve_password_title', $title );
					/**
					 * Filter the message body of the password reset mail.
					 *
					 * @since 2.8.0
					 *
					 * @param string $message Default mail message.
					 * @param string $key The activation key.
					 */
					$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login );

					if ( $message && ! wp_mail( $user_email, $title, $message ) ) {
						echo "<span style='color:red'>" . esc_html__( "Failure! ", 'sweetdate' );
						echo esc_html__( "The e-mail could not be sent.", 'sweetdate' );
						echo "</span>";
						die();
					} else {
						echo "<span style='color:green'>" . esc_html__( "Email successfully sent!", 'sweetdate' ) . "</span>";
						die();
					}
				}
			}
		}
		die();
	}
endif;
add_action( "wp_ajax_kleo_lost_password", "kleo_lost_password_ajax" );
add_action( 'wp_ajax_nopriv_kleo_lost_password', 'kleo_lost_password_ajax' );

function kleo_lost_password_js() {
	if ( is_user_logged_in() ) {
		return;
	}
	?>
	<script type="text/javascript">
		/* Lost password ajax */
		jQuery(document).ready(function () {
			jQuery("#forgot_form #recover").on("click", function () {
				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						'action': 'kleo_lost_password',
						'email': jQuery("#forgot-email").val(),
						'g-recaptcha-response': jQuery('#forgot_panel [name="g-recaptcha-response"]').val()
					},
					success: function (data) {
						jQuery('#lost_result').html("<p>" + data + "</p>");
					},
					error: function () {
						jQuery('#lost_result').html('Sorry, an error occurred.').css('color', 'red');
					}

				});
				return false;
			});
		});
	</script>

	<?php
}

add_action( 'wp_footer', 'kleo_lost_password_js' );

/* -----------------------------------------------------------------------------
 * END Lost password section
 */


/***************************************************
 * TOP TOOLBAR - ADMIN BAR
 * Enable or disable the bar, depending of the theme option setting
 ***************************************************/
if ( sq_option( 'admin_bar', 1 ) == '0' ) {
	remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
	add_filter( 'show_admin_bar', '__return_false' );
}


/* Make x-profile fields translatable */
if ( ! function_exists( 'make_x_profile_fields_name_translable' ) ) {

	function make_x_profile_fields_name_translable( $fieldname ) {
		return __( $fieldname, 'sweetdate' );
	}

	add_filter( 'bp_get_the_profile_field_name', 'make_x_profile_fields_name_translable' );

}


/*
 * Ajax helper
*/
add_action( 'wp_ajax_mc_action', 'sweetdate_mc_action' );
add_action( 'wp_ajax_nopriv_mc_action', 'sweetdate_mc_action' );

if ( ! function_exists( 'sweetdate_mc_action' ) ) {

	function sweetdate_mc_action() {

		$api_key = sq_option( 'mailchimp_api' );

		if ( isset ( $_POST['mc_email'] ) && wp_verify_nonce( $_POST['nonce'], "mc_mail" ) ) {

			if ( isset ( $api_key ) && ! empty ( $api_key ) ) {


				$api_key = sq_option( 'mailchimp_api' );

				$name  = explode( " ", $_POST['yname'] );
				$fname = ( ! empty( $name[0] ) ? $name[0] : "" );
				unset( $name[0] );
				$lname = ( ! empty( $name ) ? join( " ", $name ) : "" );

				$list_id = $_POST['list'];
				$opt_in  = sq_option( 'mailchimp_opt_in', 'yes' ) == 'yes' ? 'pending' : 'subscribed';

				include_once( SWEETCORE_PATH . 'inc/Widgets/mailchimp/MCAPI.class.php' );
				$mcapi  = new \SVQ_MCAPI( $api_key );

				$result = $mcapi->post( "lists/" . $list_id . "/members", [
					'email_address' => $_POST['mc_email'],
					'status'        => $opt_in,
					"merge_fields"  => array(
						'FNAME' => $fname,
						'LNAME' => $lname
					)
				] );

				if ( is_array( $result ) && isset( $result['status'] ) && $result['status'] === $opt_in ) {
					// It worked!
					if ( $opt_in === 'pending' ) {
						$msg = esc_html__( 'Success!&nbsp; Check your inbox or spam folder for a message containing a confirmation link.', 'sweetdate' );
					} else {
						$msg = esc_html__( 'You have successfully subscribed.', 'sweetdate' );
					}
				} else {
					$msg = '<span style="color:red;"><b>' . esc_html__( 'Error:', 'sweetdate' ) . '</b>&nbsp; ' . esc_html__( 'This email was already subscribed or it is invalid.', 'sweetdate-core' ) . '</span>';
				}

				echo( $msg );
			}
		}
		die();
	}
}