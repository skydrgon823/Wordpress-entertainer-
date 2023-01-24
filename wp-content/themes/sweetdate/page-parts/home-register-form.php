<?php
/* Hide the starting and ending columns if used as shortcode */
global $bp_reg_form_show_cols, $bp_reg_form_show_carousel, $bp_reg_form_title, $bp_reg_form_details;
if ( ! isset( $bp_reg_form_show_cols ) ) { ?>
	<div class="twelve columns">
	<div class="row">
	<div class="five columns">
<?php } ?>

	<div class="form-wrapper">
		<div class="form-header">
			<h4 class="white-text">
				<?php
				/* if set via shortcode */
				if ( isset( $bp_reg_form_title ) ) {
					echo wp_kses_post( $bp_reg_form_title );
				} else {
					esc_html_e( "Create an Account", 'sweetdate' );
				}
				?>
			</h4>
			<p class="reg-form-details">
				<?php
				/* if set via shortcode */
				if ( isset( $bp_reg_form_details ) ) {
					echo wp_kses_post( $bp_reg_form_details );
				} else {
					esc_html_e( "Registering for this site is easy, just fill in the fields below and we will get a new account set up for you in no time.", 'sweetdate' );
				}
				?>
			</p>
		</div>
		<!--Search form-->
		<form id="register_form_front" action="<?php if ( function_exists( 'bp_is_active' ) ) {
			bp_signup_page();
		} else {
			echo esc_url( home_url( 'wp-login.php?action=register' ) );
		} ?>" method="post" class="custom form-search">

			<div class="row">
				<div class="five mobile-four columns">
					<label class="right inline"><?php esc_html_e( "Username", 'sweetdate' ); ?>:</label>
				</div>
				<div class="seven mobile-four columns">
					<input name="signup_username" required="required" type="text"
					       placeholder="<?php esc_attr_e( "Required", 'sweetdate' ); ?>">
				</div>
			</div><!--end row-->

			<div class="row">
				<div class="five mobile-four columns">
					<label class="right inline"><?php esc_html_e( "Email Address", 'sweetdate' ); ?>:</label>
				</div>
				<div class="seven mobile-four columns">
					<input name="signup_email" type="text" required="required"
					       placeholder="<?php esc_attr_e( "Required", 'sweetdate' ); ?>">
				</div>
			</div><!--end row-->


			<div class="row">
				<div class="five mobile-four columns">
					<label class="right inline"><?php esc_html_e( "Password", 'sweetdate' ); ?>:</label>
				</div>
				<div class="three mobile-two columns">
					<input type="password" name="signup_password" required="required"
					       placeholder="<?php esc_attr_e( "Required", 'sweetdate' ); ?>">
				</div>
				<div class="one mobile-one columns text-center">
					<label class="inline"></label>
				</div>
				<div class="three mobile-two columns">
					<input type="password" name="signup_password_confirm" required="required"
					       placeholder="<?php esc_attr_e( "Confirm", 'sweetdate' ); ?>">
				</div>
			</div><!--end row-->

			<?php do_action( 'kleo_register_form_extra' ); ?>

			<div class="row">
				<div class="seven offset-by-five columns">
					<button class="button radius front-form-button"><i class="icon-user"></i>
						&nbsp;<?php _e( "Sign Up", 'sweetdate' ); ?></button>
					<?php do_action( 'fb_popup_register_button_front' ); ?>
				</div>
			</div>
			<span class="notch"></span>
		</form>
		<!--end search form-->

		<?php
		/* Show latest profiles carousel */
		$show_carousel = false;
		if ( function_exists( 'bp_is_active' ) ) {
			if ( isset( $bp_reg_form_show_carousel ) ) {
				if ( $bp_reg_form_show_carousel == 1 ) {
					$show_carousel = true;
				}
			} elseif ( sq_option( 'home_search_members', 1 ) == 1 ) {
				$show_carousel = true;
			}
		}

		if ( $show_carousel ): ?>
			<!--Form footer-->
			<div class="form-footer">

				<?php do_action( 'kleo_bps_before_carousel' ); ?>

				<?php echo do_shortcode( '[kleo_members_carousel]' ); ?>

			</div><!--end form-footer-->


		<?php else: ?>
			<?php $main_color_rgb = hexToRGB( sq_option( 'button_bg_color_hover' ) ); ?>
			<div class="form-footer"
			     style="border-left:none;border-right: none;padding:0;background: <?php echo sq_option( 'button_bg_color' ); ?>; <?php echo 'border-bottom: 10px solid rgba(' . $main_color_rgb['r'] . ', ' . $main_color_rgb['g'] . ', ' . $main_color_rgb['b'] . ', 0.3)'; ?>"></div>
		<?php endif; ?>

	</div><!--end form-wrapper-->

<?php if ( ! isset( $bp_reg_form_show_cols ) ) { ?>
	</div>
	</div><!--end row-->
	</div><!--end twelve-->
<?php } ?>