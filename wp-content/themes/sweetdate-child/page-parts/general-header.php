<?php
/**
 * General header section
 *
 * @package SweetDate
 */

?>

<!-- HEADER SECTION ================================================ -->
<header>
	<div class="header-bg clearfix">

		<?php if ( sq_option( 'social_top' ) == 1 ) : ?>
			<!--Top links-->
			<div class="top-links">
				<div class="row">
					<ul class="no-bullet">
						<li class="nine columns">
							<?php do_action( 'kleo_before_top_links' ); ?>

							<?php if ( sq_option( 'phone_on_top' ) ): ?>
								<?php if ( sq_option( 'owner_phone' ) ): ?>
									<a class="phone-top"
									   href="tel:<?php echo esc_attr( sq_option( 'owner_phone' ) ); ?>">
										<i class="icon-phone"></i>
										&nbsp; <?php echo esc_attr( sq_option( 'owner_phone' ) ); ?>
									</a>
									&nbsp;&nbsp;
								<?php endif; ?>
							<?php endif; ?>

							<?php if ( sq_option( 'owner_email' ) ): ?>
								<a class="mail-top" href="mailto:<?php echo esc_attr( sq_option( 'owner_email' ) ); ?>">
									<i class="icon-envelope"></i>
									&nbsp; <?php echo esc_attr( sq_option( 'owner_email' ) ); ?>
								</a>
							<?php endif; ?></li>
						<li class="three columns hide-for-small">
							<?php if ( sq_option( 'twitter' ) || sq_option( 'facebook' ) || sq_option( 'instagram' )
							           || sq_option( 'youtube' ) || sq_option( 'googleplus' ) || sq_option( 'pinterest' )
							           || sq_option( 'linkedin' || has_action( 'kleo_extra_social_icons' ) )
							) : ?>
								<?php esc_html_e( "Find us on", 'sweetdate' ); ?>: &nbsp;
								<?php if ( sq_option( 'twitter' ) ): ?>
									<a href="<?php echo esc_url( sq_option( 'twitter' ) ); ?>"
									   class="has-tip tip-bottom"
									   data-width="210" target="_blank"
									   title="<?php esc_attr_e( "Follow us on", 'sweetdate' ); ?> Twitter">
										<i class="icon-twitter icon-large"></i>
									</a>
								<?php endif; ?>
								<?php if ( sq_option( 'facebook' ) ): ?>
									<a href="<?php echo esc_url( sq_option( 'facebook' ) ); ?>"
									   class="has-tip tip-bottom"
									   data-width="210" target="_blank"
									   title="<?php esc_attr_e( "Find us on", 'sweetdate' ); ?> Facebook">
										<i class="icon-facebook icon-large"></i>
									</a>
								<?php endif; ?>
								<?php if ( sq_option( 'instagram' ) ): ?>
									<a href="<?php echo esc_url( sq_option( 'instagram' ) ); ?>"
									   class="has-tip tip-bottom"
									   data-width="210" target="_blank"
									   title="<?php esc_attr_e( "Follow us on", 'sweetdate' ); ?> Instagram">
										<i class="icon-instagram icon-large"></i>
									</a>
								<?php endif; ?>
								<?php if ( sq_option( 'youtube' ) ): ?>
									<a href="<?php echo esc_url( sq_option( 'youtube' ) ); ?>"
									   class="has-tip tip-bottom"
									   data-width="210" target="_blank"
									   title="<?php esc_attr_e( "Follow us on", 'sweetdate' ); ?> Youtube">
										<i class="icon-youtube icon-large"></i>
									</a>
								<?php endif; ?>
								<?php if ( sq_option( 'googleplus' ) ): ?>
									<a href="<?php echo esc_url( sq_option( 'googleplus' ) ); ?>"
									   class="has-tip tip-bottom"
									   data-width="210" target="_blank"
									   title="<?php esc_attr_e( "Find us on", 'sweetdate' ); ?> Google+">
										<i class="icon-google-plus icon-large"></i>
									</a>
								<?php endif; ?>
								<?php if ( sq_option( 'pinterest' ) ): ?>
									<a href="<?php echo esc_url( sq_option( 'pinterest' ) ); ?>"
									   class="has-tip tip-bottom"
									   data-width="210" target="_blank"
									   title="<?php esc_attr_e( "Pin us on", 'sweetdate' ); ?> Pinterest">
										<i class="icon-pinterest icon-large"></i>
									</a>
								<?php endif; ?>
								<?php if ( sq_option( 'linkedin' ) ): ?>
									<a href="<?php echo esc_url( sq_option( 'linkedin' ) ); ?>"
									   class="has-tip tip-bottom"
									   data-width="210" target="_blank"
									   title="<?php esc_attr_e( "Find us on", 'sweetdate' ); ?> LinkedIn">
										<i class="icon-linkedin icon-large"></i>
									</a>
								<?php endif; ?>

								<?php do_action( 'kleo_extra_social_icons' ); ?>
							<?php endif; ?>
						</li>
					</ul>
				</div>
			</div>
			<!--end top-links-->
		<?php endif; ?>

		<div id="header">
			<div class="row">
				<!-- Logo -->
				<div class="four columns mobile-two-three">
					<div id="logo"><?php bloginfo( 'name' ); ?>
						<a href="<?php echo get_home_url(); ?>">
							<img id="logo_img"
							     src="<?php echo esc_url( sq_option( 'logo', get_template_directory_uri() . '/assets/images/logo.png' ) ); ?>"
							      alt="<?php bloginfo( 'name' ); ?>">
						</a>
					</div>
				</div>				
				<div class="eight columns hide-for-medium hide-for-xlarge mobile-three-fourth">						
							<!--Login buttons-->
						<ul class="button-group radius right">
						<?php if ( is_user_logged_in() ): ?>

							<?php if ( function_exists( 'bp_is_active' ) ): ?>
								<?php
								$profile_menu = array();
								if ( bp_is_active( 'activity' ) ) {
									$profile_menu['activity'] = '<li><a href="' . esc_url( bp_loggedin_user_domain() ) . 'activity/">' . esc_html__( "Activity", "buddypress" ) . '</a></li>';
								}

								if ( bp_is_active( 'messages' ) ) {
									$profile_menu['messages'] = '<li><a href="' . esc_url( bp_loggedin_user_domain() ) . 'messages/">' . esc_html__( "Messages", "buddypress" ) . ' <small class="label">' . messages_get_unread_count() . '</small></a></li>';
								}

								if ( bp_is_active( 'friends' ) ) {
									$profile_menu['friends'] = '<li><a href="' . esc_url( bp_loggedin_user_domain() ) . 'friends/requests">' . esc_html__( "Friend requests", 'sweetdate' ) . ' <small class="label">' . bp_friend_get_total_requests_count() . '</small></a></li>';
								}

								if ( bp_is_active( 'groups' ) ) {
									$profile_menu['groups'] = '<li><a href="' . esc_url( bp_loggedin_user_domain() ) . 'groups/">' . esc_html__( "Groups", "buddypress" ) . '</a></li>';
								}

								if ( bp_is_active( 'settings' ) ) {
									$profile_menu['settings'] = '<li><a href="' . esc_url( bp_loggedin_user_domain() ) . 'settings/">' . esc_html__( "Settings", "buddypress" ) . '</a></li>';
								}

								$profile_menu = apply_filters( 'header_profile_dropdown', $profile_menu );
								?>

								<li class="relative btn-profile">
									<?php if ( ! empty( $profile_menu ) ) { ?>
									<div href="#" class="tiny secondary button split dropdown"
									     data-options="is_hover:true">
										<?php } ?>

										<a href="<?php bp_loggedin_user_link(); ?>"
										   class="tiny secondary button radius">
											<i class="icon-user hide-for-medium-down"></i>
											<?php esc_html_e( "PROFILE", 'sweetdate' ); ?>
										</a>
										<span></span>
										<div class="kleo-notifications kleoset">
											<?php if ( bp_is_active( 'messages' ) && messages_get_unread_count() > 0 ) { ?>
												<a
												href="<?php echo esc_url( bp_loggedin_user_domain() ) . 'messages/'; ?>"
												data-width="210"
												title="<?php esc_attr_e( "New messages", 'sweetdate' ); ?>"
												class="kleo-message-count has-tip tip-left"><?php echo messages_get_unread_count(); ?></a><?php } ?><?php if ( bp_is_active( 'friends' ) && bp_friend_get_total_requests_count() > 0 ): ?>
												<a href="<?php echo esc_url( bp_loggedin_user_domain() ) . 'friends/requests'; ?>"
												   data-width="210"
												   title="<?php esc_attr_e( "Friend requests", 'sweetdate' ); ?>"
												   class="kleo-friends-req has-tip tip-right">
													<?php echo bp_friend_get_total_requests_count(); ?>
												</a>
											<?php endif; ?>
										</div>
										<ul>
											<?php
											if ( ! empty( $profile_menu ) ) {
												foreach ( $profile_menu as $prm ) {
													echo $prm; // PHPCS: XSS ok.
												}
											}
											?>
										</ul>

										<?php if ( ! empty( $profile_menu ) ) { ?>
									</div>
								<?php } ?>

								</li>
							<?php endif; ?>

							<li class="userlogout">
								<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?> "
								   class="tiny button radius btn-logout">
									<i class="icon-off hide-for-medium-down"></i>
									<?php esc_html_e( "LOG OUT", 'sweetdate' ); ?>
								</a>
							</li>

						<?php else: ?>

							<li class="header-login-button">
								<a href="#" data-reveal-id="login_panel" class="tiny secondary button radius">
									<i class="icon-signin hide-for-medium-down"></i>
									<?php esc_html_e( "LOGIN", 'sweetdate' ); ?>
								</a>
							</li>

							<?php if ( get_option( 'users_can_register' ) ) { ?>
								<li class="header-register-button">
									<a href="https://vendorflock.com/register/" class="tiny button radius">
										<i class="icon-user hide-for-medium-down"></i>
										<?php esc_html_e( "SIGN UP", 'sweetdate' ); ?>
									</a>
								</li>
							<?php } ?>

						<?php endif; ?>
					</ul>
				<!--end login buttons-->
				</div>
				<!--end logo-->

				<!-- Login/Register/Forgot username/password Modal forms
					-  Hidden by default to be opened through modal-->
					
				<!-- Main Navigation -->
				<div class="eight columns hide-xs hide-for-small">
					<div class="contain-to-grid <?php if ( sq_option( 'sticky_menu', 1 ) == 1 ) { echo ' sticky';} ?>">
						<nav class="top-bar">
							<a href="<?php echo esc_url( get_home_url() ); ?>" class="small-logo"><img
									src="<?php echo esc_url( sq_option( 'small_logo', get_template_directory_uri() . '/assets/images/small_logo.png' ) ); ?>"
									height="43" alt="<?php bloginfo( 'name' ); ?>"></a>
							<ul>
								<!-- Toggle Button Mobile -->
								<li class="name">
									<h1><a href="#"><?php esc_html_e( "Please select your page", 'sweetdate' ); ?></a>
									</h1>
								</li>
								<li class="toggle-topbar"><a href="#"><i class="icon-reorder"></i></a></li>
								<!-- End Toggle Button Mobile -->
							</ul>

							<section><!-- Nav Section -->
								<?php wp_nav_menu( array(
									'container'      => false,
									'menu_class'     => 'left',
									'theme_location' => 'primary',
									'fallback_cb'    => 'sweetdate_main_nav',
									'walker'         => new sweetdate_walker_nav_menu
								) );
								?>
							
							</section><!-- End Nav Section -->
							
						</nav>
							<!--Login buttons-->
						<div class="desktopmenu">
						<ul class="button-group radius right">
						<?php if ( is_user_logged_in() ): ?>

							<?php if ( function_exists( 'bp_is_active' ) ): ?>
								<?php
								$profile_menu = array();
								if ( bp_is_active( 'activity' ) ) {
									$profile_menu['activity'] = '<li><a href="' . esc_url( bp_loggedin_user_domain() ) . 'activity/">' . esc_html__( "Activity", "buddypress" ) . '</a></li>';
								}

								if ( bp_is_active( 'messages' ) ) {
									$profile_menu['messages'] = '<li><a href="' . esc_url( bp_loggedin_user_domain() ) . 'messages/">' . esc_html__( "Messages", "buddypress" ) . ' <small class="label">' . messages_get_unread_count() . '</small></a></li>';
								}

								if ( bp_is_active( 'friends' ) ) {
									$profile_menu['friends'] = '<li><a href="' . esc_url( bp_loggedin_user_domain() ) . 'friends/requests">' . esc_html__( "Friend requests", 'sweetdate' ) . ' <small class="label">' . bp_friend_get_total_requests_count() . '</small></a></li>';
								}

								if ( bp_is_active( 'groups' ) ) {
									$profile_menu['groups'] = '<li><a href="' . esc_url( bp_loggedin_user_domain() ) . 'groups/">' . esc_html__( "Groups", "buddypress" ) . '</a></li>';
								}

								if ( bp_is_active( 'settings' ) ) {
									$profile_menu['settings'] = '<li><a href="' . esc_url( bp_loggedin_user_domain() ) . 'settings/">' . esc_html__( "Settings", "buddypress" ) . '</a></li>';
								}

								$profile_menu = apply_filters( 'header_profile_dropdown', $profile_menu );
								?>

								<li class="relative btn-profile">

									<?php if ( ! empty( $profile_menu ) ) { ?>
									<div href="#" class="tiny secondary button split dropdown"
									     data-options="is_hover:true">
										<?php } ?>

										<a href="<?php bp_loggedin_user_link(); ?>"
										   class="tiny secondary button radius">
											<i class="icon-user hide-for-medium-down"></i>
											<?php esc_html_e( "PROFILE", 'sweetdate' ); ?>
										</a>
										<span></span>
										<div class="kleo-notifications kleoset">
											<?php if ( bp_is_active( 'messages' ) && messages_get_unread_count() > 0 ) { ?>
												<a
												href="<?php echo esc_url( bp_loggedin_user_domain() ) . 'messages/'; ?>"
												data-width="210"
												title="<?php esc_attr_e( "New messages", 'sweetdate' ); ?>"
												class="kleo-message-count has-tip tip-left"><?php echo messages_get_unread_count(); ?></a><?php } ?><?php if ( bp_is_active( 'friends' ) && bp_friend_get_total_requests_count() > 0 ): ?>
												<a href="<?php echo esc_url( bp_loggedin_user_domain() ) . 'friends/requests'; ?>"
												   data-width="210"
												   title="<?php esc_attr_e( "Friend requests", 'sweetdate' ); ?>"
												   class="kleo-friends-req has-tip tip-right">
													<?php echo bp_friend_get_total_requests_count(); ?>
												</a>
											<?php endif; ?>
										</div>
										<ul>
											<?php
											if ( ! empty( $profile_menu ) ) {
												foreach ( $profile_menu as $prm ) {
													echo $prm; // PHPCS: XSS ok.
												}
											}
											?>
										</ul>

										<?php if ( ! empty( $profile_menu ) ) { ?>
									</div>
								<?php } ?>

								</li>
							<?php endif; ?>

							<li class="userlogout">
								<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?> "
								   class="tiny button radius btn-logout">
									<i class="icon-off hide-for-medium-down"></i>
									<?php esc_html_e( "LOG OUT", 'sweetdate' ); ?>
								</a>
							</li>

						<?php else: ?>

							<li class="header-login-button">
								<a href="#" data-reveal-id="login_panel" class="tiny secondary button radius">
									<i class="icon-signin hide-for-medium-down"></i>
									<?php esc_html_e( "LOGIN", 'sweetdate' ); ?>
								</a>
							</li>

							<?php if ( get_option( 'users_can_register' ) ) { ?>
								<li class="header-register-button">
									<a href="https://vendorflock.com/register/" class="tiny button radius">
										<i class="icon-user hide-for-medium-down"></i>
										<?php esc_html_e( "SIGN UP", 'sweetdate' ); ?>
									</a>
								</li>
							<?php } ?>

						<?php endif; ?>
					</ul>
					</div>
				<!--end login buttons-->
				
					</div><!--end contain-to-grid sticky-->
				</div>
				<!-- end Main Navigation -->

					
				

			</div><!--end row-->

			<?php if ( sq_option( 'home_rev', 0 ) == 1 && function_exists( 'putRevSlider' ) ) : /*Fix for Safari on MacOs */ ?>

		</div><!--end #header-->

	<?php endif; ?>

		<div class="row just-after-header">
			<?php
			/**
			 * after_header_content
			 *
			 * @hooked render_user_search - only if is Front-page Template
			 */
			do_action( 'after_header_content' );
			?>
		</div>

		<?php if ( sq_option( 'home_rev', 0 ) == 0 ) : /*Fix for Safari on MacOs */ ?>

	</div><!--end #header-->

	<?php endif; ?>

	<?php
	/**
	 * kleo_after_header
	 *
	 */
	do_action( 'kleo_after_header' );
	?>
	</div><!--end header-bg-->
</header>
<!--END HEADER SECTION-->
