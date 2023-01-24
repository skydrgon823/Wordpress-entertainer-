<?php

/**
 * Uninstall Feedback
 *
 * @link
 * @since 2.5.0
 *
 * @package  Cookie_Law_Info
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Cookie_Law_Info_Uninstall_Feedback {

	protected $api_url         = '';
	protected $current_version = CLI_VERSION;
	protected $auth_key        = 'cookielawinfo_uninstall_1234#';
	protected $plugin_id       = CLI_POST_TYPE;
	protected $plugin_file     = CLI_PLUGIN_FILENAME; // plugin main file
	public function __construct() {
		$this->api_url = 'https://feedback.webtoffee.com/wp-json/' . $this->plugin_id . '/v1/uninstall';

		add_action( 'admin_footer', array( $this, 'deactivate_scripts' ) );
		add_action( 'wp_ajax_' . $this->plugin_id . '_submit_uninstall_reason', array( $this, 'send_uninstall_reason' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( $this->plugin_file ), array( $this, 'plugin_action_links' ) );
	}
	public function plugin_action_links( $links ) {
		if ( array_key_exists( 'deactivate', $links ) ) {
			$links['deactivate'] = str_replace( '<a', '<a class="' . $this->plugin_id . '-deactivate-link"', $links['deactivate'] );
		}
		return $links;
	}
	private function get_uninstall_reasons() {

		$reasons = array(

			array(
				'id'          => 'did-not-work-as-expected',
				'text'        => __( 'The plugin didn\'t work as expected', 'cookie-law-info' ),
				'type'        => 'textarea',
				'placeholder' => __( 'How can we make our plugin better?', 'cookie-law-info' ),
			),
			array(
				'id'          => 'cookie-scanner-issue',
				'text'        => __( 'Issues with cookie scanner', 'cookie-law-info' ),
				'type'        => 'textarea',
				'placeholder' => __(
					'Describe the challenges that you faced while using our Cookie Scanner.&#10;Eg:- Scan did not find all cookies.',
					'cookie-law-info'
				),
			),
			array(
				'id'          => 'not-have-that-feature',
				'text'        => __( 'The plugin is great, but I need specific feature that you don\'t support', 'cookie-law-info' ),
				'type'        => 'textarea',
				'placeholder' => __( 'Could you tell us more about that feature?', 'cookie-law-info' ),
			),
			array(
				'id'          => 'conflict-theme-plugin',
				'text'        => __( 'A conflict with another plugin or theme', 'cookie-law-info' ),
				'type'        => 'textarea',
				'placeholder' => __( 'Specify whether you are having issues with the back-end or front-end functionalities. Enter your site URL to help us fix the plugin/theme conflicts.', 'cookie-law-info' ),
			),
			array(
				'id'    => 'translation-issues',
				'text'  => __( 'Translation issues', 'cookie-law-info' ),
				'child' => array(
					array(
						'id'          => 'incorrect-misssing-translation',
						'text'        => __( 'Incorrect/missing translation', 'cookie-law-info' ),
						'type'        => 'textarea',
						'placeholder' => __( 'Name the language and specify the string with incorrect translation.', 'cookie-law-info' ),
					),
					array(
						'id'          => 'could-not-translate',
						'text'        => __( 'Unable to translate my dynamic content e.g, cookie message, button text etc', 'cookie-law-info' ),
						'type'        => 'textarea',
						'placeholder' => __( 'Name the language and the translator plugin that you are using', 'cookie-law-info' ),
					),
				),
			),
            array(
				'id'          => 'found-better-plugin',
				'text'        => __( 'I found a better plugin', 'cookie-law-info' ),
				'type'        => 'text',
				'placeholder' => __( 'Which plugin?', 'cookie-law-info' ),
			),
			array(
				'id'   => 'upgrade-to-pro',
				'text' => __( 'Upgrade to pro', 'cookie-law-info' ),
			),
            array(
				'id'   => 'temporary-deactivation',
				'text' => __( 'It’s a temporary deactivation', 'cookie-law-info' ),
			),
			array(
				'id'          => 'other',
				'text'        => __( 'Other', 'cookie-law-info' ),
				'type'        => 'textarea',
				'placeholder' => __( 'Please describe your issue in detail.', 'cookie-law-info' ),
			),
		);

		return $reasons;
	}
	public function generate_reason_html() {
	}
	public function deactivate_scripts() {
		global $pagenow;
		if ( 'plugins.php' != $pagenow ) {
			return;
		}
		$reasons = $this->get_uninstall_reasons();
		?>
		<div class="<?php echo $this->plugin_id; ?>-modal" id="<?php echo $this->plugin_id; ?>-modal">
			<div class="<?php echo $this->plugin_id; ?>-modal-wrap">
				<div class="<?php echo $this->plugin_id; ?>-modal-header">
					<h3><?php _e( 'If you have a moment, please let us know why you are deactivating:', 'cookie-law-info' ); ?></h3>
				</div>
				<div class="<?php echo $this->plugin_id; ?>-modal-body">
					<ul class="reasons">
					<?php
					foreach ( $reasons as $reason ) :
											$data_type   = ( isset( $reason['type'] ) ? 'data-type="' . esc_attr( $reason['type'] ) . '"' : '' );
											$placeholder = ( isset( $reason['type'] ) ? 'data-placeholder="' . esc_attr( $reason['placeholder'] ) . '"' : '' );
											$childs      = ( isset( $reason['child'] ) && is_array( $reason['child'] ) ) ? $reason['child'] : array();
						?>
							<li <?php echo $data_type; ?> <?php echo $placeholder; ?>>
								<label><input type="radio" name="selected-reason" value="<?php echo $reason['id']; ?>"><?php echo $reason['text']; ?></label>
								<?php if ( ! empty( $childs ) ) : ?>
									<ul class="<?php echo $this->plugin_id . '-sub-reasons'; ?>">
										<?php
										foreach ( $childs as $child ) :
													$data_type   = ( isset( $child['type'] ) ? 'data-type="' . esc_attr( $child['type'] ) . '"' : '' );
													$placeholder = ( isset( $child['type'] ) ? 'data-placeholder="' . esc_attr( $child['placeholder'] ) . '"' : '' );
											?>
											<li <?php echo $data_type; ?> <?php echo $placeholder; ?>>
												<label><input type="radio" name="selected-reason" value="<?php echo $child['id']; ?>"><?php echo $child['text']; ?></label>
											<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							   
							</li>

					<?php endforeach; ?>
					</ul>
					<div class="wt-uninstall-feedback-privacy-policy">
						<?php _e( "We do not collect any personal data when you submit this form. It's your feedback that we value.", 'cookie-law-info' ); ?>
						<a href="https://www.webtoffee.com/privacy-policy/" target="_blank"><?php _e( 'Privacy Policy', 'cookie-law-info' ); ?></a>
					</div>
				</div>
				<div class="<?php echo $this->plugin_id; ?>-modal-footer">

					<a class="button-primary" href="https://www.webtoffee.com/support/" target="_blank">
						<span class="dashicons dashicons-external" style="margin-top:3px;"></span>
						<?php _e( 'Go to support', 'cookie-law-info' ); ?></a>
					<button class="button-primary <?php echo $this->plugin_id; ?>-model-submit"><?php _e( 'Submit & Deactivate', 'cookie-law-info' ); ?></button>
					<button class="button-secondary <?php echo $this->plugin_id; ?>-model-cancel"><?php _e( 'Cancel', 'cookie-law-info' ); ?></button>
					<a href="#" style="color: #737373;" class="dont-bother-me"><?php _e( 'I rather wouldn\'t say', 'cookie-law-info' ); ?></a>
				</div>
			</div>
		</div>
		<style type="text/css">
			.<?php echo $this->plugin_id; ?>-modal {
				position: fixed;
				z-index: 99999;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				background: rgba(0, 0, 0, 0.5);
				display: none;
			}

			.<?php echo $this->plugin_id; ?>-modal.modal-active {
				display: block;
			}

			.<?php echo $this->plugin_id; ?>-modal-wrap {
				width: 50%;
				position: relative;
				margin: 10% auto;
				background: #fff;
			}

			.<?php echo $this->plugin_id; ?>-modal-header {
				border-bottom: 1px solid #eee;
				padding: 8px 20px;
			}

			.<?php echo $this->plugin_id; ?>-modal-header h3 {
				line-height: 150%;
				margin: 0;
			}

			.<?php echo $this->plugin_id; ?>-modal-body {
				padding: 5px 20px 20px 20px;
			}

			.<?php echo $this->plugin_id; ?>-modal-body .input-text,
			.<?php echo $this->plugin_id; ?>-modal-body textarea {
				width: 75%;
			}

			.<?php echo $this->plugin_id; ?>-modal-body .reason-input {
				margin-top: 5px;
				margin-left: 20px;
			}

			.<?php echo $this->plugin_id; ?>-modal-footer {
				border-top: 1px solid #eee;
				padding: 12px 20px;
				text-align: left;
			}

			.<?php echo $this->plugin_id; ?>-sub-reasons {
				display: none;
				padding-left: 20px;
				padding-top: 10px;
				padding-bottom: 4px;
			}

			.wt-uninstall-feedback-privacy-policy {
				text-align: left;
				font-size: 12px;
				color: #aaa;
				line-height: 14px;
				margin-top: 20px;
				font-style: italic;
			}

			.wt-uninstall-feedback-privacy-policy a {
				font-size: 11px;
				color: #4b9cc3;
				text-decoration-color: #99c3d7;
			}
		</style>
		<script type="text/javascript">
			(function($) {
				$(function() {
					var plugin_id = '<?php echo $this->plugin_id; ?>';
					var modal = $('#' + plugin_id + '-modal');
					var deactivateLink = '';
					$('a.' + plugin_id + '-deactivate-link').click(function(e) {
						e.preventDefault();
						modal.addClass('modal-active');
						deactivateLink = $(this).attr('href');
						modal.find('a.dont-bother-me').attr('href', deactivateLink).css('float', 'right');
					});
					modal.on('click', 'button.' + plugin_id + '-model-cancel', function(e) {
						e.preventDefault();
						modal.removeClass('modal-active');
					});
					modal.on('click', 'input[type="radio"]', function() {
						var parent = $(this).parents('li:first');
						if (parent.find('ul').length > 0) {
							$('.' + plugin_id + '-sub-reasons').hide();
							parent.find('ul').show();
						} else {
							modal.find('.reason-input').remove();
							var inputType = parent.data('type'),
								inputPlaceholder = parent.data('placeholder'),
								reasonInputHtml = '<div class="reason-input">' + (('text' === inputType) ? '<input type="text" class="input-text" size="40" />' : '<textarea rows="5" cols="45"></textarea>') + '</div>';
							if (inputType === 'textarea' || inputType === 'text') {
								parent.append($(reasonInputHtml));
								parent.find('input, textarea').attr('placeholder', inputPlaceholder).focus();
							}
						}

					});

					modal.on('click', 'button.' + plugin_id + '-model-submit', function(e) {
						e.preventDefault();
						var button = $(this);
						if (button.hasClass('disabled')) {
							return;
						}
						var $radio = $('input[type="radio"]:checked', modal);
						var $selected_reason = $radio.parents('li:first'),
							$input = $selected_reason.find('textarea, input[type="text"]');

						$.ajax({
							url: ajaxurl,
							type: 'POST',
							data: {
								action: plugin_id + '_submit_uninstall_reason',
								reason_id: (0 === $radio.length) ? 'none' : $radio.val(),
								reason_info: (0 !== $input.length) ? $input.val().trim() : ''
							},
							beforeSend: function() {
								button.addClass('disabled');
								button.text('Processing...');
							},
							complete: function() {
								window.location.href = deactivateLink;
							}
						});
					});
				});
			}(jQuery));
		</script>
		<?php
	}

	public function send_uninstall_reason() {
		global $wpdb;
		if ( ! isset( $_POST['reason_id'] ) ) {
			wp_send_json_error();
		}
		// $current_user = wp_get_current_user();
		$data = array(
			'reason_id'                   => sanitize_text_field( $_POST['reason_id'] ),
			'plugin'                      => $this->plugin_id,
			'auth'                        => $this->auth_key,
			'date'                        => gmdate( 'M d, Y h:i:s A' ),
			'url'                         => '',
			'user_email'                  => '',
			'reason_info'                 => isset( $_REQUEST['reason_info'] ) ? trim( stripslashes( $_REQUEST['reason_info'] ) ) : '',
			'software'                    => $_SERVER['SERVER_SOFTWARE'],
			'php_version'                 => phpversion(),
			'mysql_version'               => $wpdb->db_version(),
			'wp_version'                  => get_bloginfo( 'version' ),
			'wc_version'                  => ( ! defined( 'WC_VERSION' ) ) ? '' : WC_VERSION,
			'locale'                      => get_locale(),
			'multisite'                   => is_multisite() ? 'Yes' : 'No',
			$this->plugin_id . '_version' => $this->current_version,
		);
		// Write an action/hook here in webtoffe to recieve the data
		$resp = wp_remote_post(
			$this->api_url,
			array(
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => false,
				'body'        => $data,
				'cookies'     => array(),
			)
		);
		wp_send_json_success();
	}
}
new Cookie_Law_Info_Uninstall_Feedback();
