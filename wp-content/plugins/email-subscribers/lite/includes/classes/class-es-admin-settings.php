<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Admin Settings
 *
 * @package    Email_Subscribers
 * @subpackage Email_Subscribers/admin
 */
class ES_Admin_Settings {

	public static $instance;

	public $subscribers_obj;

	public function __construct() {
		add_filter( 'ig_es_registered_email_sending_settings', array( $this, 'show_cron_info' ) );

		// Start-IG-Code.
		// Add setting for plugin usage tracking
		add_filter( 'ig_es_registered_settings', array( $this, 'show_usage_tracking_optin_setting' ), 30 );
		// End-IG-Code.
	}

	public function es_settings_callback() {

		$submitted     = ig_es_get_request_data( 'submitted' );
		$submit_action = ig_es_get_request_data( 'submit_action' );

		if ( 'submitted' === $submitted && 'ig-es-save-admin-settings' === $submit_action ) {

			$nonce = ig_es_get_request_data( 'update-settings' );
			if ( ! wp_verify_nonce( $nonce, 'update-settings' ) ) {
				$message = __( 'You do not have permission to update settings', 'email-subscribers' );
				ES_Common::show_message( $message, 'error' );
			} else {

				$options = ig_es_get_data( $_POST );

				$options = apply_filters( 'ig_es_before_save_settings', $options );

				$options['ig_es_disable_wp_cron']                           = isset( $options['ig_es_disable_wp_cron'] ) ? $options['ig_es_disable_wp_cron'] : 'no';
				$options['ig_es_track_email_opens']                         = isset( $options['ig_es_track_email_opens'] ) ? $options['ig_es_track_email_opens'] : 'no';
				$options['ig_es_enable_ajax_form_submission']               = isset( $options['ig_es_enable_ajax_form_submission'] ) ? $options['ig_es_enable_ajax_form_submission'] : 'no';
				$options['ig_es_enable_welcome_email']                      = isset( $options['ig_es_enable_welcome_email'] ) ? $options['ig_es_enable_welcome_email'] : 'no';
				$options['ig_es_notify_admin']                              = isset( $options['ig_es_notify_admin'] ) ? $options['ig_es_notify_admin'] : 'no';
				$options['ig_es_enable_cron_admin_email']                   = isset( $options['ig_es_enable_cron_admin_email'] ) ? $options['ig_es_enable_cron_admin_email'] : 'no';
				$options['ig_es_delete_plugin_data']                        = isset( $options['ig_es_delete_plugin_data'] ) ? $options['ig_es_delete_plugin_data'] : 'no';
				$options['ig_es_run_cron_on']                               = isset( $options['ig_es_run_cron_on'] ) ? $options['ig_es_run_cron_on'] : 'monday';
				$options['ig_es_run_cron_time']                             = isset( $options['ig_es_run_cron_time'] ) ? $options['ig_es_run_cron_time'] : '4pm';
				// Start-IG-Code.
				// Show option to enable/disable tracking if user isn't a premium user and trial is not valid i.e. has expired.
				if ( ! ES()->is_premium() && ! ES()->is_trial_valid() ) {
					$options['ig_es_allow_tracking'] = isset( $options['ig_es_allow_tracking'] ) ? $options['ig_es_allow_tracking'] : 'no';
				}
				$options['ig_es_powered_by'] = isset( $options['ig_es_powered_by'] ) ? $options['ig_es_powered_by'] : 'no';
				// End-IG-Code.

				$text_fields_to_sanitize = array(
					'ig_es_from_name',
					'ig_es_admin_emails',
					'ig_es_email_type',
					'ig_es_optin_type',
					'ig_es_post_image_size',
					'ig_es_track_email_opens',
					'ig_es_enable_ajax_form_submission',
					'ig_es_enable_welcome_email',
					'ig_es_welcome_email_subject',
					'ig_es_confirmation_mail_subject',
					'ig_es_notify_admin',
					'ig_es_admin_new_contact_email_subject',
					'ig_es_enable_cron_admin_email',
					'ig_es_cron_admin_email_subject',
					'ig_es_cronurl',
					'ig_es_hourly_email_send_limit',
					'ig_es_disable_wp_cron',
				);

				$textarea_fields_to_sanitize = array(
					'ig_es_unsubscribe_link_content',
					'ig_es_subscription_success_message',
					'ig_es_subscription_error_messsage',
					'ig_es_unsubscribe_success_message',
					'ig_es_unsubscribe_error_message',
					'ig_es_welcome_email_content',
					'ig_es_confirmation_mail_content',
					'ig_es_admin_new_contact_email_content',
					'ig_es_cron_admin_email',
					'ig_es_blocked_domains',
					'ig_es_form_submission_success_message',
				);

				$email_fields_to_sanitize = array(
					'ig_es_from_email',
				);

				foreach ( $options as $key => $value ) {
					if ( substr( $key, 0, 6 ) === 'ig_es_' ) {

						$value = stripslashes_deep( $value );

						if ( in_array( $key, $text_fields_to_sanitize, true ) ) {
							$value = sanitize_text_field( $value );
						} elseif ( in_array( $key, $textarea_fields_to_sanitize, true ) ) {
							$value = wp_kses_post( $value );
						} elseif ( in_array( $key, $email_fields_to_sanitize, true ) ) {
							$value = sanitize_email( $value );
						}

						update_option( $key, wp_unslash( $value ), false );
					}
				}

				do_action( 'ig_es_after_settings_save', $options );

				$message = __( 'Settings saved successfully!' );
				$status  = 'success';
				ES_Common::show_message( $message, $status );
			}
		}
		$allowedtags = ig_es_allowed_html_tags_in_esc();

		?>

		<div class="wrap pt-4 font-sans">
			<span>
				<h2 class="wp-heading-inline text-3xl font-bold leading-9 text-gray-700 sm:truncate pb-1"><?php esc_html_e( 'Settings', 'email-subscribers' ); ?></h2>
			</span>
			</header>
		</span>
		<div><hr class="wp-header-end"></div>
		<form action="" method="post" id="email_tabs_form" class="sticky bg-white rounded-lg shadow">
			<div class="flex flex-wrap mt-7">
				<?php
				settings_fields( 'email_subscribers_settings' );
				$es_settings_tabs = array(
					'general'             => array(
						'icon' => '<svg class="w-6 h-6 inline -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
						'name' => __( 'General', 'email-subscribers' ),
					),
					'signup_confirmation' => array(
						'icon' => '<svg class="w-6 h-6 inline -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>',
						'name' => __( 'Notifications', 'email-subscribers' ),
					),
					'email_sending'       => array(
						'icon' => '<svg class="w-6 h-6 inline -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
						'name' => __( 'Email Sending', 'email-subscribers' ),
					),
					'security_settings'   => array(
						'icon' => '<svg class="w-6 h-6 inline -mt-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>',
						'name' => __( 'Security', 'email-subscribers' ),
					),
				);
				$es_settings_tabs = apply_filters( 'ig_es_settings_tabs', $es_settings_tabs );
				?>
				<div id="es-settings-menu" class="w-1/5 pt-4 leading-normal text-gray-800 border-r border-gray-100">
					<div class="z-20 my-2 mt-0 bg-white shadow es-menu-list lg:block lg:my-0 lg:border-transparent lg:shadow-none lg:bg-transparent" style="top:6em;" id="menu-content">
						<ul id="menu-nav" class="py-2 list-reset md:py-0">
							<?php
							foreach ( $es_settings_tabs as $key => $value ) {
								?>
								<li id="menu-content" class="h-10 py-1 mx-2 border border-transparent rounded settings-menu-change md:my-2 hover:rounded-lg hover:border-gray-200">
									<a href="#tabs-<?php echo esc_attr( $key ); ?>" id="menu-content-change" class="block px-4 pt-1 text-base font-medium text-gray-600 no-underline align-middle hover:text-gray-800"><?php echo wp_kses( $value['icon'], $allowedtags ); ?>&nbsp;<span class="pl-0.5"><?php echo esc_html( $value['name'] ); ?></span></a></li>
									<?php
							}
							?>
							</ul>
						</div>
					</div>

					<div class="w-4/5" id="es-menu-tab-content">
						<?php
						$settings = self::get_registered_settings();
						foreach ( $settings as $key => $value ) {
							?>
							<div id="tabs-<?php echo esc_attr( $key ); ?>" class="setting-content"><?php $this->render_settings_fields( $value ); ?></div>
							<?php
						}
						?>

					</div>
				</div>
			</form>
		</div>
		<?php
	}

	public static function get_registered_settings() {

		$general_settings = array(

			'sender_information'                    => array(
				'id'         => 'sender_information',
				'name'       => __( 'Sender', 'email-subscribers' ),
				'sub_fields' => array(
					'from_name'  => array(
						'id'          => 'ig_es_from_name',
						'name'        => __( 'Name', 'email-subscribers' ),
						'desc'        => __( 'The "from" name people will see when they receive emails.', 'email-subscribers' ),
						'type'        => 'text',
						'placeholder' => __( 'Name', 'email-subscribers' ),
						'default'     => '',
					),

					'from_email' => array(
						'id'          => 'ig_es_from_email',
						'name'        => __( 'Email', 'email-subscribers' ),
						'desc'        => __( 'The "from" email address for all emails.', 'email-subscribers' ),
						'type'        => 'text',
						'placeholder' => __( 'Email Address', 'email-subscribers' ),
						'default'     => '',
					),
				),
			),

			'admin_email'                           => array(
				'id'      => 'ig_es_admin_emails',
				'name'    => __( 'Admin emails', 'email-subscribers' ),
				'info'    => __( 'Who should be notified about system events like "someone subscribed", "campaign sent" etc?', 'email-subscribers' ),
				'type'    => 'text',
				'desc'    => __( 'You can enter multiple email addresses - separate them with comma', 'email-subscribers' ),
				'default' => '',
			),

			'ig_es_optin_type'                      => array(
				'id'      => 'ig_es_optin_type',
				'name'    => __( 'Opt-in type', 'email-subscribers' ),
				'info'    => '',
				'desc'    => __( 'Single = confirm subscribers as they subscribe.<br> Double = send a confirmation email and require clicking on a link to confirm subscription.', 'email-subscribers' ),
				'type'    => 'select',
				'options' => ES_Common::get_optin_types(),
				'default' => '',
			),

			// Start-IG-Code.
			'ig_es_post_image_size'                 => array(
				'id'      => 'ig_es_post_image_size',
				'name'    => __( 'Image size', 'email-subscribers' ),
				'info'    => __( 'Image to use in Post Notification emails' ),
				'type'    => 'select',
				'options' => ES_Common::get_image_sizes(),
				/* translators: %s: Keyword */
				'desc'    => sprintf( __( '%s keyword will use this image size. Use full size only if your template design needs it. Thumbnail should work well otherwise.', 'email-subscribers' ), '{{POSTIMAGE}}' ),
				'default' => 'full',
			),
			// End-IG-Code.

			'ig_es_enable_ajax_form_submission'     => array(
				'id'      => 'ig_es_enable_ajax_form_submission',
				'name'    => __( 'Enable AJAX subscription form submission', 'email-subscribers' ),
				'info'    => __( 'Enabling this will let users to submit their subscription form without page reload using AJAX call.', 'email-subscribers' ),
				'type'    => 'checkbox',
				'default' => 'no',
			),

			'ig_es_track_email_opens'               => array(
				'id'      => 'ig_es_track_email_opens',
				'name'    => __( 'Track opens', 'email-subscribers' ),
				'info'    => __( 'Do you want to track when people view your emails? (We recommend keeping it enabled)', 'email-subscribers' ),
				'type'    => 'checkbox',
				'default' => 'yes',
			),

			'ig_es_form_submission_success_message' => array(
				'type'         => 'textarea',
				'options'      => false,
				'placeholder'  => '',
				'supplemental' => '',
				'default'      => '',
				'id'           => 'ig_es_form_submission_success_message',
				'name'         => __( 'Subscription success message', 'email-subscribers' ),
				'info'         => __( 'This message will show when a visitor successfully subscribes using the form.', 'email-subscribers' ),
				'desc'         => '',
			),

			'ig_es_unsubscribe_link_content'        => array(
				'type'         => 'textarea',
				'options'      => false,
				'placeholder'  => '',
				'supplemental' => '',
				'default'      => '',
				'id'           => 'ig_es_unsubscribe_link_content',
				'name'         => __( 'Unsubscribe text in email footer:', 'email-subscribers' ),
				'info'         => __( 'All emails will include this text in the footer so people can unsubscribe if they want.', 'email-subscribers' ),
				/* translators: %s: List of Keywords */
				'desc'         => sprintf( __( 'Use %s keyword to add unsubscribe link.', 'email-subscribers' ), '{{UNSUBSCRIBE-LINK}}' ),
			),

			'subscription_messages'                 => array(
				'id'         => 'subscription_messages',
				'name'       => __( 'Double opt-in subscription messages:', 'email-subscribers' ),
				'info'       => __( 'Page and messages to show when people click on the link in a subscription confirmation email.', 'email-subscribers' ),
				'sub_fields' => array(
					'ig_es_subscription_success_message' => array(
						'type'         => 'textarea',
						'options'      => false,
						'placeholder'  => '',
						'supplemental' => '',
						'default'      => __( 'You have been subscribed successfully!', 'email-subscribers' ),
						'id'           => 'ig_es_subscription_success_message',
						'name'         => __( 'Message on successful subscription', 'email-subscribers' ),
						'desc'         => __( 'Show this message if contact is successfully subscribed from double opt-in (confirmation) email', 'email-subscribers' ),
					),

					'ig_es_subscription_error_messsage'  => array(
						'type'         => 'textarea',
						'options'      => false,
						'placeholder'  => '',
						'supplemental' => '',
						'default'      => __( 'Oops.. Your request couldn\'t be completed. This email address seems to be already subscribed / blocked.', 'email-subscribers' ),
						'id'           => 'ig_es_subscription_error_messsage',
						'name'         => __( 'Message when subscription fails', 'email-subscribers' ),
						'desc'         => __( 'Show this message if any error occured after clicking confirmation link from double opt-in (confirmation) email.', 'email-subscribers' ),
					),

				),
			),

			'unsubscription_messages'               => array(
				'id'         => 'unsubscription_messages',
				'name'       => __( 'Unsubscribe messages', 'email-subscribers' ),
				'info'       => __( 'Page and messages to show when people click on the unsubscribe link in an email\'s footer.', 'email-subscribers' ),
				'sub_fields' => array(

					'ig_es_unsubscribe_success_message' => array(
						'type'         => 'textarea',
						'options'      => false,
						'placeholder'  => '',
						'supplemental' => '',
						'default'      => __( 'Thank You, You have been successfully unsubscribed. You will no longer hear from us.', 'email-subscribers' ),
						'id'           => 'ig_es_unsubscribe_success_message',
						'name'         => __( 'Message on unsubscribe success', 'email-subscribers' ),
						'desc'         => __( 'Once contact clicks on unsubscribe link, he/she will be redirected to a page where this message will be shown.', 'email-subscribers' ),
					),

					'ig_es_unsubscribe_error_message'   => array(
						'type'         => 'textarea',
						'options'      => false,
						'placeholder'  => '',
						'supplemental' => '',
						'default'      => __( 'Oops.. There was some technical error. Please try again later or contact us.', 'email-subscribers' ),
						'id'           => 'ig_es_unsubscribe_error_message',
						'name'         => __( 'Message when unsubscribe fails', 'email-subscribers' ),
						'desc'         => __( 'Show this message if any error occured after clicking on unsubscribe link.', 'email-subscribers' ),
					),
				),
			),

			// Start-IG-Code.
			'ig_es_powered_by'                      => array(
				'id'      => 'ig_es_powered_by',
				'name'    => __( 'Share Icegram', 'email-subscribers' ),
				'info'    => __( 'Show "Powered By" link in the unsubscription form' ),
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			// End-IG-Code.

			'ig_es_delete_plugin_data'              => array(
				'id'      => 'ig_es_delete_plugin_data',
				'name'    => __( 'Delete plugin data on uninstall', 'email-subscribers' ),
				'info'    => __( 'Be careful with this! When enabled, it will remove all lists, campaigns and other data if you uninstall the plugin.', 'email-subscribers' ),
				'type'    => 'checkbox',
				'default' => 'no',
			),

		);

		$general_settings = apply_filters( 'ig_es_registered_general_settings', $general_settings );

		$signup_confirmation_settings = array(

			'worflow_migration_notice' => array(
				'id'   => 'worflow_migration_notice',
				'type' => 'html',
				'html' => self::get_workflow_migration_notice_html(),
			),
		);

		$signup_confirmation_settings = apply_filters( 'ig_es_registered_signup_confirmation_settings', $signup_confirmation_settings );

		$cron_url_setting_desc = '';

		if ( ES()->is_trial_valid() || ES()->is_premium() ) {
			$cron_url_setting_desc = '<span class="es-send-success es-icon"></span>' . esc_html__( ' We will take care of it. You don\'t need to visit this URL manually.', 'email-subscribers' );
		} else {
			/* translators: %s: Link to Icegram documentation */
			$cron_url_setting_desc = sprintf( __( "You need to visit this URL to send email notifications. Know <a href='%s' target='_blank'>how to run this in background</a>", 'email-subscribers' ), 'https://www.icegram.com/documentation/es-how-to-schedule-cron-emails-in-cpanel/?utm_source=es&utm_medium=in_app&utm_campaign=view_docs_help_page' );
		}

		$cron_url_setting_desc .= '<div class="mt-2.5 ml-1"><a class="hover:underline text-sm font-medium" href=" ' . esc_url( 'https://www.icegram.com/documentation/how-to-configure-email-sending-in-email-subscribers?utm_source=in_app&utm_medium=setup_email_sending&utm_campaign=es_doc' ) . '" target="_blank">' . esc_html__( 'How to configure Email Sending', 'email-subscribers' ) . '→</a></div>';

		$pepipost_api_key_defined = ES()->is_const_defined( 'pepipost', 'api_key' );

		$email_sending_settings = array(
			'ig_es_cronurl'                 => array(
				'type'         => 'text',
				'placeholder'  => '',
				'supplemental' => '',
				'default'      => '',
				'readonly'     => 'readonly',
				'id'           => 'ig_es_cronurl',
				'name'         => __( 'Cron URL', 'email-subscribers' ),
				'desc'         => $cron_url_setting_desc,
			),
			'ig_es_disable_wp_cron'         => array(
				'type'         => 'checkbox',
				'placeholder'  => '',
				'supplemental' => '',
				'default'      => 'no',
				'id'           => 'ig_es_disable_wp_cron',
				'name'         => __( 'Disable Wordpress Cron', 'email-subscribers' ),
				'info'         => __( 'Enable this option if you do not want Email Subscribers to use WP Cron to send emails.', 'email-subscribers' ),
			),

			'ig_es_cron_interval'           => array(
				'id'      => 'ig_es_cron_interval',
				'name'    => __( 'Send emails at most every', 'email-subscribers' ),
				'type'    => 'select',
				'options' => ES()->cron->cron_intervals(),
				'desc'    => __( 'Optional if a real cron service is used', 'email-subscribers' ),
				'default' => IG_ES_CRON_INTERVAL,
			),

			'ig_es_hourly_email_send_limit' => array(
				'type'         => 'number',
				'placeholder'  => '',
				'supplemental' => '',
				'default'      => 50,
				'id'           => 'ig_es_hourly_email_send_limit',
				'name'         => __( 'Maximum emails to send in an hour', 'email-subscribers' ),
				'desc'         => __( 'Total emails your host can send in an hour.', 'email-subscribers' ),
			),

			'ig_es_max_email_send_at_once'  => array(
				'type'         => 'number',
				'placeholder'  => '',
				'supplemental' => '',
				'default'      => IG_ES_MAX_EMAIL_SEND_AT_ONCE,
				'id'           => 'ig_es_max_email_send_at_once',
				'name'         => __( 'Maximum emails to send at once', 'email-subscribers' ),
				'desc'         => __( 'Maximum emails you want to send on every cron request.', 'email-subscribers' ),
			),

			'ig_es_test_send_email'         => array(
				'type'         => 'html',
				/* translators: %s: Spinner image path */
				'html'         => sprintf( '<input id="es-test-email" type="email" class="mt-3 mb-1 border-gray-400 form-input h-9"/><input type="submit" name="submit" id="es-send-test" class="ig-es-primary-button" value="Send Email"><span class="es_spinner_image_admin" id="spinner-image" style="display:none"><img src="%s" alt="Loading..."/></span>', ES_PLUGIN_URL . 'lite/public/images/spinner.gif' ),
				'placeholder'  => '',
				'supplemental' => '',
				'default'      => '',
				'id'           => 'ig_es_test_send_email',
				'name'         => __( 'Send test email', 'email-subscribers' ),
				'desc'         => __( 'Enter email address to send test email.', 'email-subscribers' ),
			),

			'ig_es_mailer_settings'         => array(
				'type'         => 'html',
				'sub_fields'   => array(
					'mailer'                  => array(
						'id'   => 'ig_es_mailer_settings[mailer]',
						'name' => __( 'Select Mailer', 'email-subscribers' ),
						'type' => 'html',
						'html' => self::mailers_html(),
						'desc' => '',
					),
					'ig_es_pepipost_api_key'  => array(
						'type'         => $pepipost_api_key_defined ? 'text' : 'password',
						'options'      => false,
						'placeholder'  => '',
						'supplemental' => '',
						'default'      => '',
						'id'           => 'ig_es_mailer_settings[pepipost][api_key]',
						'name'         => __( 'Pepipost API key', 'email-subscribers' ),
						'desc'         => $pepipost_api_key_defined ? ES()->get_const_set_message( 'pepipost', 'api_key' ) : '',
						'class'        => 'pepipost',
						'disabled'     => $pepipost_api_key_defined ? 'disabled' : '',
						'value'        => $pepipost_api_key_defined ? '******************' : '',
					),
					'ig_es_pepipost_docblock' => array(
						'type' => 'html',
						'html' => self::pepipost_doc_block(),
						'id'   => 'ig_es_pepipost_docblock',
						'name' => '',
					),
				),
				'placeholder'  => '',
				'supplemental' => '',
				'default'      => '',
				'id'           => 'ig_es_mailer_settings',
				'name'         => __( 'Select a mailer to send mail', 'email-subscribers' ),
				'desc'         => '',
			),
		);

		$email_sending_settings = apply_filters( 'ig_es_registered_email_sending_settings', $email_sending_settings );

		$security_settings = array(
			'blocked_domains' => array(
				'id'      => 'ig_es_blocked_domains',
				'name'    => __( 'Blocked domain(s)', 'email-subscribers' ),
				'type'    => 'textarea',
				'info'    => __( 'Seeing spam signups from particular domains? Enter domains names (one per line) that you want to block here.', 'email-subscribers' ),
				'default' => '',
				'rows'    => 3,
			),

		);

		$security_settings = apply_filters( 'ig_es_registered_security_settings', $security_settings );

		$es_settings = array(
			'general'             => $general_settings,
			'signup_confirmation' => $signup_confirmation_settings,
			'email_sending'       => $email_sending_settings,
			'security_settings'   => $security_settings,
		);

		return apply_filters( 'ig_es_registered_settings', $es_settings );
	}

	public function field_callback( $arguments, $id_key = '' ) {
		$field_html = '';
		if ( 'ig_es_cronurl' === $arguments['id'] ) {
			$value = ES()->cron->url();
		} else {
			if ( ! empty( $arguments['option_value'] ) ) {
				preg_match( '(\[.*$)', $arguments['id'], $m );
				$n     = explode( '][', $m[0] );
				$n     = str_replace( '[', '', $n );
				$n     = str_replace( ']', '', $n );
				$count = count( $n );
				$id    = '';
				foreach ( $n as $key => $val ) {
					if ( '' == $id ) {
						$id = ! empty( $arguments['option_value'][ $val ] ) ? $arguments['option_value'][ $val ] : '';
					} else {
						$id = ! empty( $id[ $val ] ) ? $id[ $val ] : '';
					}
				}
				$value = $id;
			} else {
				$value = get_option( $arguments['id'] ); // Get the current value, if there is one
			}
		}

		if ( ! $value ) { // If no value exists
			$value = ! empty( $arguments['default'] ) ? $arguments['default'] : ''; // Set to our default
		}

		$uid         = ! empty( $arguments['id'] ) ? $arguments['id'] : '';
		$type        = ! empty( $arguments['type'] ) ? $arguments['type'] : '';
		$placeholder = ! empty( $arguments['placeholder'] ) ? $arguments['placeholder'] : '';
		$readonly    = ! empty( $arguments['readonly'] ) ? $arguments['readonly'] : '';
		$html        = ! empty( $arguments['html'] ) ? $arguments['html'] : '';
		$id_key      = ! empty( $id_key ) ? $id_key : $uid;
		$class       = ! empty( $arguments['class'] ) ? $arguments['class'] : '';
		$rows        = ! empty( $arguments['rows'] ) ? $arguments['rows'] : 12;
		$disabled    = ! empty( $arguments['disabled'] ) ? 'disabled="' . $arguments['disabled'] . '"' : '';
		$value       = ! empty( $arguments['value'] ) ? $arguments['value'] : $value;

		// Check which type of field we want
		switch ( $arguments['type'] ) {
			case 'text': // If it is a text field
				$field_html = sprintf( '<input name="%1$s" id="%2$s" placeholder="%4$s" value="%5$s" %6$s class="%7$s form-input h-9 mt-2 mb-1 text-sm border-gray-400 w-3/5" %8$s/>', $uid, $id_key, $type, $placeholder, $value, $readonly, $class, $disabled );
				break;
			case 'password': // If it is a text field
				$field_html = sprintf( '<input name="%1$s" id="%2$s" type="%3$s" placeholder="%4$s" value="%5$s" %6$s class="form-input h-9 mt-2 mb-1 text-sm border-gray-400 w-3/5 %7$s" %8$s/>', $uid, $id_key, $type, $placeholder, $value, $readonly, $class, $disabled );
				break;

			case 'number': // If it is a number field
				$field_html = sprintf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" %5$s min="0" class="w-2/5 mt-2 mb-1 text-sm border-gray-400 h-9 " %6$s/>', $uid, $type, $placeholder, $value, $readonly, $disabled );
				break;

			case 'email':
				$field_html = sprintf( '<input name="%1$s" id="%2$s" type="%3$s" placeholder="%4$s" value="%5$s" class="%6$s form-input w-2/3 mt-2 mb-1 h-9 text-sm border-gray-400 w-3/5" %7$s/>', $uid, $id_key, $type, $placeholder, $value, $class, $disabled );
				break;

			case 'textarea':
				$field_html = sprintf( '<textarea name="%1$s" id="%2$s" placeholder="%3$s" size="100" rows="%6$s" cols="58" class="%5$s form-textarea text-sm w-2/3 mt-3 mb-1 border-gray-400 w-3/5" %7$s>%4$s</textarea>', $uid, $id_key, $placeholder, $value, $class, $rows, $disabled );
				break;

			case 'file':
				$field_html = '<input type="text" id="logo_url" name="' . $uid . '" value="' . $value . '" class="w-2/3 w-3/5 mt-2 mb-1 text-sm border-gray-400 form-input h-9' . $class . '"/> <input id="upload_logo_button" type="button" class="button" value="Upload Logo" />';
				break;

			case 'checkbox':
				$field_html = '<label for="' . $id_key . '" class="inline-flex items-center mt-4 mb-1 cursor-pointer">
			<span class="relative">';

				if ( ! $disabled ) {
					$field_html .= '<input id="' . $id_key . '"  type="checkbox" name="' . $uid . '"  value="yes" ' . checked( $value, 'yes', false ) . ' class="absolute w-0 h-0 mt-6 opacity-0 es-check-toggle ' . $class . '" />';
				}

				$field_html .= $placeholder . '</input>
			<span class="es-mail-toggle-line"></span>
			<span class="es-mail-toggle-dot"></span>	
			</span>
			</label>';
				break;

			case 'select':
				if ( ! empty( $arguments['options'] ) && is_array( $arguments['options'] ) ) {
					$options_markup = '';
					foreach ( $arguments['options'] as $key => $label ) {
						$options_markup .= sprintf(
							'<option value="%s" %s>%s</option>',
							$key,
							selected( $value, $key, false ),
							$label
						);
					}
					$field_html = sprintf( '<select name="%1$s" id="%2$s" class="%4$s form-select rounded-lg w-2/5 h-9 mt-2 mb-1 border-gray-400" %5$s>%3$s</select>', $uid, $id_key, $options_markup, $class, $disabled );
				}
				break;

			case 'html':
			default:
				$field_html = $html;
				break;
		}

		// If there is help text
		if ( ! empty( $arguments['desc'] ) ) {
			$helper      = $arguments['desc'];
			$field_html .= sprintf( '<p class="field-desciption mb-2 text-xs italic font-normal leading-snug text-gray-500 helper %s"> %s</p>', $class, $helper ); // Show it
		}

		return $field_html;
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function render_settings_fields( $fields ) {
		$html  = "<table class='mt-4 mr-4 overflow-hidden bg-white rounded-lg lg:mx-5 xl:mx-7'>";
		$html .= '<tbody>';
		foreach ( $fields as $key => $field ) {
			if ( ! empty( $field['name'] ) ) {
				$html .= "<tr class='py-4 ml-4 border-b border-gray-100 '><th scope='row' class='block pt-3 pb-8 pr-4 ml-6 text-left pt-7'><span class='pb-2 text-sm font-semibold text-gray-600'>";
				$html .= $field['name'];

				if ( ! empty( $field['is_premium'] ) ) {
					$html .= '</span><a class="ml-1" href="' . $field['link'] . '" target="_blank"><span class="premium-icon"></span></a>';
				}

				// If there is help text
				if ( ! empty( $field['info'] ) ) {
					$helper = $field['info'];
					$html  .= '<br />' . sprintf( '<p class="mt-1 text-xs italic font-normal leading-snug text-gray-500">%s</p>', $helper ); // Show it
				}
				$button_html = '<tr>';

				$html .= '</th>';
			}

			$html .= "<td class='w-4/6 py-2 pl-5 bg-white rounded-lg '>";

			if ( ! empty( $field['upgrade_desc'] ) ) {
				$html .= "<div class='flex'><div class='flex-none w-2/5'>";
			}

			if ( ! empty( $field['sub_fields'] ) ) {
				$option_key = '';
				foreach ( $field['sub_fields'] as $field_key => $sub_field ) {
					if ( strpos( $sub_field['id'], '[' ) ) {
						$parts = explode( '[', $sub_field['id'] );
						if ( $option_key !== $parts[0] ) {
							$option_value = get_option( $parts[0] );
							$option_key   = $parts[0];
						}
						$sub_field['option_value'] = is_array( $option_value ) ? $option_value : '';
					}
					$class = ( ! empty( $sub_field['class'] ) ) ? $sub_field['class'] : '';
					$html .= ( reset( $field['sub_fields'] ) !== $sub_field ) ? '<p class="pt-1></p>' : '';
					$html .= '<div class="es_sub_headline ' . $class . ' pt-4" ><strong>' . $sub_field['name'] . '</strong>';
					if ( ! empty( $sub_field['tooltip_text'] ) ) {
						$tooltip_html = ES_Common::get_tooltip_html( $sub_field['tooltip_text'] );
						$html        .= $tooltip_html;
					}
					$html .= '</div>';
					$html .= $this->field_callback( $sub_field, $field_key );
				}
			} else {
				$html .= $this->field_callback( $field );
			}

			if ( ! empty( $field['upgrade_desc'] ) ) {
				$upsell_info = array(
					'upgrade_title'  => $field['upgrade_title'],
					'pricing_url'    => $field['link'],
					'upsell_message' => $field['upgrade_desc'],
					'cta_html'       => false,
				);
				$html       .= '</div> <div class="w-3/5">';
				$html       .= ES_Common::upsell_description_message_box( $upsell_info, false );
				$html       .= '</div>';
			}

			$html .= '</td></tr>';
		}

		$button_html = empty( $button_html ) ? '<tr>' : $button_html;

		$nonce_field = wp_nonce_field( 'update-settings', 'update-settings', true, false );
		$html       .= $button_html . "<td class='es-settings-submit-btn'>";
		$html       .= '<input type="hidden" name="submitted" value="submitted" />';
		$html       .= '<input type="hidden" name="submit_action" value="ig-es-save-admin-settings" />';
		$html       .= $nonce_field;
		$html       .= '<input type="submit" name="submit" class="mx-6 my-2 cursor-pointer ig-es-primary-button" value="' . __( 'Save Settings', 'email-subscribers' ) . '">';
		$html       .= '</td></tr>';
		$html       .= '</tbody>';
		$html       .= '</table>';

		$allowedtags = ig_es_allowed_html_tags_in_esc();
		add_filter( 'safe_style_css', 'ig_es_allowed_css_style' );
		echo wp_kses( $html, $allowedtags );
	}

	/**
	 * Prepare Mailers Setting
	 *
	 * @return string
	 *
	 * @modify 4.3.12
	 */
	public static function mailers_html() {
		$html                     = '';
		$es_email_type            = get_option( 'ig_es_email_type', '' );
		$selected_mailer_settings = get_option( 'ig_es_mailer_settings', array() );

		$selected_mailer = '';
		if ( ! empty( $selected_mailer_settings ) && ! empty( $selected_mailer_settings['mailer'] ) ) {
			$selected_mailer = $selected_mailer_settings['mailer'];
		} else {
			$php_email_type_values = array(
				'php_html_mail',
				'php_plaintext_mail',
				'phpmail',
			);

			if ( in_array( $es_email_type, $php_email_type_values, true ) ) {
				$selected_mailer = 'phpmail';
			}
		}

		$pepipost_doc_block = '';

		$mailers = array(
			'wpmail'   => array(
				'name' => 'WP Mail',
				'logo' => ES_PLUGIN_URL . 'lite/admin/images/wpmail.png',
			),
			'phpmail'  => array(
				'name' => 'PHP mail',
				'logo' => ES_PLUGIN_URL . 'lite/admin/images/phpmail.png',
			),
			'pepipost' => array(
				'name'     => 'Pepipost',
				'logo'     => ES_PLUGIN_URL . 'lite/admin/images/pepipost.png',
				'docblock' => $pepipost_doc_block,
			),
		);

		$mailers = apply_filters( 'ig_es_mailers', $mailers );

		$selected_mailer = ( array_key_exists( $selected_mailer, $mailers ) ) ? $selected_mailer : 'wpmail';

		foreach ( $mailers as $key => $mailer ) {
			$html .= '<label class="inline-flex items-center cursor-pointer">';
			$html .= '<input type="radio" class="absolute w-0 h-0 opacity-0 es_mailer" name="ig_es_mailer_settings[mailer]" value="' . $key . '" ' . checked( $selected_mailer, $key, false ) . '></input>';

			if ( ! empty( $mailer['is_premium'] ) ) {
				$html .= '<a href="' . $mailer['url'] . '" target="_blank">';
			}

			$html .= '<div class="mt-4 mr-4 border border-gray-200 rounded-lg shadow-md es-mailer-logo">
			<div class="border-0 es-logo-wrapper">
			<img src="' . $mailer['logo'] . '" alt="Default (none)">
			</div><p class="mb-2 inline-block">'
			. $mailer['name'] . '</p>';

			if ( ! empty( $mailer['is_premium'] ) ) {
				$html .= '<span class="premium-icon"></span>';
			}

			$html .= '</div>';

			if ( ! empty( $mailer['is_premium'] ) ) {
				$html .= '</a>';
			}

			$html .= '</label>';
		}

		return $html;
	}

	public static function pepipost_doc_block() {
		$html = '';

		$url = ES_Common::get_utm_tracking_url(
			array(
				'url'        => 'https://www.icegram.com/email-subscribers-integrates-with-pepipost',
				'utm_medium' => 'pepipost_doc',
			)
		);

		ob_start();
		do_action('ig_es_before_get_pepipost_doc_block');
		?>
		<div class="es_sub_headline ig_es_docblock ig_es_pepipost_div_wrapper pepipost">
			<ul>
				<li><a class="" href="https://app.pepipost.com/index.php/signup/icegram?fpr=icegram" target="_blank"><?php esc_html_e( 'Signup for Pepipost', 'email-subscribers' ); ?></a></li>
				<li><?php esc_html_e( 'How to find', 'email-subscribers' ); ?> <a href="https://developers.pepipost.com/api/getstarted/overview?utm_source=icegram&utm_medium=es_inapp&utm_campaign=pepipost" target="_blank"> <?php esc_html_e( 'Pepipost API key', 'email-subscribers' ); ?></a></li>
				<li><a href="<?php echo esc_url( $url ); ?>" target="_blank"><?php esc_html_e( 'Why to choose Pepipost', 'email-subscribers' ); ?></a></li>
			</ul>
		</div>

		<?php
		do_action('ig_es_after_get_pepipost_doc_block');
		$html = ob_get_clean();

		return $html;
	}

	/**
	 * Register ES cron info
	 *
	 * @return array $email_sending_settings ES send email settings.
	 *
	 * @since 4.4.9
	 */
	public function show_cron_info( $email_sending_settings ) {
		$es_cron_enabled = ES()->cron->is_wp_cron_enable();
		if ( $es_cron_enabled ) {
			$es_cron_info           = array(
				'ig_es_cron_info' => array(
					'id'   => 'ig_es_cron_info',
					'name' => __( 'Cron Info', 'email-subscribers' ),
					'type' => 'html',
					'html' => self::render_cron_info_html(),
				),
			);
			$email_sending_settings = ig_es_array_insert_after( $email_sending_settings, 'ig_es_cronurl', $es_cron_info );
		}

		return $email_sending_settings;
	}

	/**
	 * Render ES cron info html
	 *
	 * @return false|string
	 *
	 * @since 4.4.9
	 */
	public static function render_cron_info_html() {
		$site_crons = get_option( 'cron' );

		if ( empty( $site_crons ) ) {
			return;
		}

		$es_cron_enabled = ES()->cron->is_wp_cron_enable();

		$es_crons_data  = array();
		$es_cron_events = array(
			'ig_es_cron',
			'ig_es_cron_worker',
			'ig_es_cron_auto_responder',
			'ig_es_summary_automation',
		);

		$cron_schedules = wp_get_schedules();
		$time_offset    = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		$date_format    = get_option( 'date_format' );
		$time_format    = get_option( 'time_format' );

		foreach ( $site_crons as $next_scheduled_time => $scheduled_crons ) {
			if ( ! empty( $scheduled_crons ) && is_array( $scheduled_crons ) ) {
				foreach ( $scheduled_crons as $cron_event => $cron_data ) {
					if ( ! in_array( $cron_event, $es_cron_events, true ) ) {
						continue;
					}
					foreach ( $cron_data as $cron_info ) {
						if ( ! empty( $cron_info['schedule'] ) ) {
							$cron_schedule                = $cron_info['schedule'];
							$cron_interval                = ! empty( $cron_schedules[ $cron_schedule ]['interval'] ) ? $cron_schedules[ $cron_schedule ]['interval'] : 0;
							$es_crons_data[ $cron_event ] = array(
								'cron_interval'       => $cron_interval,
								'next_scheduled_time' => $next_scheduled_time,
							);
						}
					}
				}
			}
		}

		$html = '';
		if ( ! empty( $es_crons_data ) ) {
			ob_start();
			?>
			<table class="min-w-full rounded-lg">
				<thead>
				<tr class="bg-blue-50 text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
					<th class="px-5 py-4"><?php echo esc_html__( 'Event', 'email-subscribers' ); ?></th>
					<th class="px-2 py-4 text-center"><?php echo esc_html__( 'Interval', 'email-subscribers' ); ?></th>
					<th class="px-2 py-4 text-center"><?php echo esc_html__( 'Next Execution', 'email-subscribers' ); ?></th>
				</tr>
				</thead>
				<tbody class="bg-blue-50">
				<?php
				foreach ( $es_cron_events as $cron_event ) {
					$cron_interval       = '';
					$next_scheduled_time = '';
					if ( ! empty( $es_crons_data[ $cron_event ] ) ) {
						$es_cron_data        = $es_crons_data[ $cron_event ];
						$cron_interval       = $es_cron_data['cron_interval'];
						$next_scheduled_time = $es_cron_data['next_scheduled_time'];
					} else {
						if ( 'ig_es_cron_auto_responder' === $cron_event ) {
							wp_schedule_event( floor( time() / 300 ) * 300 - 120, 'ig_es_cron_interval', 'ig_es_cron_auto_responder' );
						} elseif ( 'ig_es_cron_worker' === $cron_event ) {
							wp_schedule_event( floor( time() / 300 ) * 300, 'ig_es_cron_interval', 'ig_es_cron_worker' );
						} elseif ( 'ig_es_cron' === $cron_event ) {
							wp_schedule_event( strtotime( 'midnight' ) - 300, 'hourly', 'ig_es_cron' );
						}
						$next_scheduled_time = wp_next_scheduled( $cron_event );
						if ( 'ig_es_cron' === $cron_event ) {
							$cron_interval = 3600; // Hourly interval for ig_es_cron.
						} else {
							$cron_interval = ES()->cron->get_cron_interval();
						}
					}
					if ( empty( $cron_interval ) || empty( $next_scheduled_time ) ) {
						continue;
					}
					?>
					<tr class="border-b border-gray-200">
						<td class="pl-8 py-4 ">
							<div class="flex items-center">
								<div class="flex-shrink-0">
									<span class="text-sm leading-5 font-medium text-center text-gray-800"><?php echo esc_html( $cron_event ); ?></span>
								</div>
							</div>
						</td>
						<td class="whitespace-no-wrap text-center">
							<?php
								echo esc_html( ig_es_get_human_interval( $cron_interval ) );
							?>
						</td>
						<td class="whitespace-no-wrap text-center">
							<?php /* translators: %s: Next scheduled time */ ?>
							<b><?php echo esc_html( sprintf( __( 'In %s', 'email-subscribers' ), human_time_diff( time(), $next_scheduled_time ) ) ); ?></b><br>
							<span title="<?php echo esc_attr( 'UTC: ' . date_i18n( $date_format . ' ' . $time_format, $next_scheduled_time ) ); ?>">
								<?php echo esc_html( date_i18n( $date_format . ' ' . $time_format, $next_scheduled_time + $time_offset ) ); ?>
							</span>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php
			$html = ob_get_clean();
		}

		return $html;
	}

	/**
	 * Add setting for plugin usage tracking
	 *
	 * @param array $es_settings
	 *
	 * @return array $es_settings
	 *
	 * @since 4.7.7
	 */
	public function show_usage_tracking_optin_setting( $es_settings ) {

		// Show option to enable/disable tracking if user isn't a premium user and trial is not valid i.e. has expired.
		if ( ! ES()->is_premium() && ! ES()->is_trial_valid() ) {

			$allow_tracking = array(
				'ig_es_allow_tracking' => array(
					'id'      => 'ig_es_allow_tracking',
					'name'    => __( 'Plugin usage tracking', 'email-subscribers' ),
					'type'    => 'checkbox',
					'default' => 'no',
					'info'    => __( 'Help us to improve Email Subscribers by opting in to share non-sensitive plugin usage data.', 'email-subscribers' ),
				),
			);

			$general_fields = $es_settings['general'];

			$general_fields = ig_es_array_insert_after( $general_fields, 'ig_es_intermediate_unsubscribe_page', $allow_tracking );

			$es_settings['general'] = $general_fields;
		}

		return $es_settings;
	}

	/**
	 * Render User Permission Settings
	 *
	 * @return false|string
	 *
	 * @since 4.2.0
	 */
	public static function get_workflow_migration_notice_html() {
		ob_start();
		$workflow_url = admin_url( 'admin.php?page=es_workflows' );
		?>
		<style>
			#tabs-signup_confirmation .es-settings-submit-btn {
				display: none;
			}
		</style>
		<p class="pb-2 text-sm font-normal text-gray-500">
			<?php echo esc_html__( 'Now you can control all your notifications through workflows.', 'email-subscribers' ); ?>
			<?php 
				/* translators: 1. Anchor start tag 2. Anchor end tag */
				echo sprintf( esc_html__( 'Click %1$shere%2$s to go to workflows.', 'email-subscribers' ), '<a href="' . esc_url( $workflow_url ) . '" class="text-indigo-600" target="_blank">', '</a>' );
			?>
		</p>
		<?php
		$html = ob_get_clean();
		return $html;
	}
}
