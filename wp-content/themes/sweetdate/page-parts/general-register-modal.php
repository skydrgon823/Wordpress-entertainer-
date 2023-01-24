<div id="register_panel" class="reveal-modal">
	<div class="row">
		<div class="twelve columns">
			<h5><i class="icon-magic icon-large"></i> <?php esc_html_e( "CREATE ACCOUNT", 'sweetdate' ); ?>
				<span class="subheader right small-link">
					<a href="#" data-reveal-id="login_panel" class="radius secondary small button">
						<?php esc_html_e( "ALREADY HAVE AN ACCOUNT?", 'sweetdate' ); ?>
					</a>
				</span>
			</h5>
		</div>
		<form id="register_form" action="<?php if ( function_exists( 'bp_is_active' ) ) {
			bp_signup_page();
		} else {
			echo esc_url( home_url( 'wp-login.php?action=register' ) );
		} ?>" name="signup_form" method="post">
			<?php if ( function_exists( 'bp_is_active' ) && bp_is_active( 'xprofile' ) && bp_has_profile( array(
					'profile_group_id' => 1,
					'fetch_field_data' => false
				) )
			) { ?>

				<div class="six columns">
					<input type="text" id="reg-username" name="signup_username" class="inputbox" required
					       placeholder="<?php esc_html_e( "Username", 'sweetdate' ); ?>">
				</div>
				<div class="six columns">
					<input type="text" id="fullname" name="field_1" class="inputbox" required
					       placeholder="<?php esc_html_e( "Your full name", 'sweetdate' ); ?>">
				</div>
				<div class="twelve columns">
					<input type="text" id="reg-email" name="signup_email" class="inputbox" required
					       placeholder="<?php esc_html_e( "Your email", 'sweetdate' ); ?>">
				</div>
				<div class="six columns">
					<input type="password" id="reg-password" name="signup_password" class="inputbox" required
					       placeholder="<?php esc_html_e( "Desired password", 'sweetdate' ); ?>">
				</div>
				<div class="six columns">
					<input type="password" id="confirm_password" name="signup_password_confirm" class="inputbox"
					       required placeholder="<?php esc_html_e( "Confirm password", 'sweetdate' ); ?>">
				</div>

				<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids"
				       value="<?php bp_the_profile_field_ids(); ?>"/>
				<?php wp_nonce_field( 'bp_new_signup' ); ?>

			<?php } else { ?>
				<div class="twelve columns">
					<input type="text" id="reg-username" name="user_login" class="inputbox" required
					       placeholder="<?php esc_html_e( "Username", 'sweetdate' ); ?>">
				</div>
				<div class="twelve columns">
					<input type="text" id="reg-email" name="user_email" class="inputbox" required
					       placeholder="<?php esc_html_e( "Your email", 'sweetdate' ); ?>">
				</div>
			<?php } ?>

			<div class="twelve columns">

				<?php
				if ( sq_option( 'terms_page', '#' ) != "#" ) {
					$terms_page_id = sq_option( 'terms_page' );
					if ( $terms_page_id ) {
						/* WPML compatibility */
						if ( function_exists( 'icl_object_id' ) ) {
							$terms_page_id = icl_object_id( $terms_page_id, 'page', true );
						}
						$terms_link = get_permalink( $terms_page_id );
						if ( $terms_link ) {
							?>
							<p>
								<label>
									<input type="checkbox" name="tos_register" id="tos_register" class="tos_register">
									<small>
										<?php printf( __( 'I agree with the <a href="%s" target="_blank"><strong>terms and conditions</strong></a>.', 'sweetdate' ), $terms_link ); ?>
									</small>
								</label>
							</p>
						<?php
						}
					}
				}
				if ( sq_option( 'bp_plugins_hook', 0 ) ) {
					do_action( 'bp_before_registration_submit_buttons' );
				}
				?>

				<button type="submit" id="signup" name="signup_submit" class="radius alert button"><i
						class="icon-<?php echo apply_filters( 'kleo_register_button_icon', 'heart' ); ?>"></i>
					&nbsp;<?php esc_html_e( "CREATE MY ACCOUNT", 'sweetdate' ); ?></button> &nbsp;
				<?php do_action( 'fb_popup_register_button' ); ?>
			</div>
		</form>
	</div><!--end row-->
	<a href="#" class="close-reveal-modal">×</a>
</div>
