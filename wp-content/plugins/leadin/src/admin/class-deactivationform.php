<?php

namespace Leadin\admin;

use Leadin\options\AccountOptions;
use \Leadin\includes\utils as utils;

/**
 * Class responsible for rendering the deactivation feedback form.
 */
class DeactivationForm {
	/**
	 * Class constructor, adds necessary hooks.
	 */
	public function __construct() {
		add_action( 'admin_footer', array( $this, 'add_feedback_form' ) );
	}

	/**
	 * Render deactivation feedback form on plugin page.
	 */
	public function add_feedback_form() {
		if ( get_current_screen()->id === 'plugins' ) {
			// handlers and logic are added via jQuery in the frontend.
			?>
				<div id="leadin-feedback-container" style="display: none;">
					<div class="leadin-feedback-header">
							<h2><?php echo esc_html( __( "We're sorry to see you go", 'leadin' ) ); ?></h2>
							<div class="leadin-modal-close">
								<svg class="leadin-close-svg" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
									<path class="leadin-close-path" d="M14.5,1.5l-13,13m0-13,13,13" transform="translate(-1 -1)"></path>
								</svg>
							</div>
					</div>
					<div class="leadin-feedback-body">
						<div>
							<strong>
								<?php echo esc_html( __( "If you have a moment, please let us know why you're deactivating the plugin.", 'leadin' ) ); ?>
							</strong>
						</div>
						<form id='leadin-deactivate-form' class="leadin-deactivate-form">
							<?php
							$radio_buttons = array(
								"I can't sign up or log in",
								'The plugin is impacting website performance',
								"The plugin isn't working",
								"The plugin isn't useful",
								'Temporarily disabling or troubleshooting',
							);

							$radio_button_translations = array(
								__( "I can't sign up or log in", 'leadin' ),
								__( 'The plugin is impacting website performance', 'leadin' ),
								__( "The plugin isn't working", 'leadin' ),
								__( "The plugin isn't useful", 'leadin' ),
								__( 'Temporarily disabling or troubleshooting', 'leadin' ),
							);

							$buttons_count = count( $radio_buttons );
							for ( $i = 0; $i < $buttons_count; $i++ ) {
								?>
									<div class="leadin-radio-input-container">
										<input
											type="radio"
											id="leadinFeedback<?php echo esc_attr( $i ); ?>"
											name="feedback"
											value="<?php echo esc_attr( $radio_buttons[ $i ] ); ?>"
											class="leadin-feedback-radio"
											required
										>
										<label for="leadinFeedback<?php echo esc_attr( $i ); ?>">
											<?php echo esc_html( $radio_button_translations[ $i ] ); ?>
										</label>
									</div>
								<?php
							}
							?>
							<div class="leadin-radio-input-container">
								<input type="radio" id="leadinFeedbackOther" name="feedback" value="Other" class="leadin-feedback-radio">
								<label for="leadinFeedbackOther"><?php echo esc_html( __( 'Other', 'leadin' ) ); ?></label>
							</div>
							<textarea name="details" class="leadin-feedback-text-area leadin-feedback-text-control" placeholder="<?php echo esc_html( __( 'Feedback...', 'leadin' ) ); ?>"></textarea>
							<input type="hidden" name="portal_id" value="<?php echo esc_html( AccountOptions::get_portal_id() ); ?>">

							<div>
								<strong>
									<?php echo esc_html( __( "Thank you for your feedback. If you would like to tell us more please add your email and we'll get in touch.", 'leadin' ) ); ?>
								</strong>
							</div>

							<input name="email" type="email" class="leadin-input" placeholder="<?php echo esc_attr( __( 'Email', 'leadin' ) ); ?>">

							<div class="leadin-button-container">
								<button type="submit" id="leadin-feedback-submit" class="leadin-button leadin-primary-button leadin-loader-button">
									<div class="leadin-loader-button-content">
										<?php echo esc_html( __( 'Submit & deactivate', 'leadin' ) ); ?>
									</div>
									<div class="leadin-loader"></div>
								</button>
								<button type="button" id="leadin-feedback-skip" class="leadin-button leadin-secondary-button">
									<?php echo esc_html( __( 'Skip & deactivate', 'leadin' ) ); ?>
								</button>
							</div>
						</form>
					</div>
				</div>
			<?php
		}
	}
}
