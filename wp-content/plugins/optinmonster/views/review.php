<div class="notice notice-info is-dismissible om-review-notice">
	<div class="om-notice-wrap">
		<h3><?php esc_html_e( 'Are you enjoying OptinMonster?', 'optin-monster-api' ); ?></h3>
		<p style="margin-bottom:0">
			<a href="#" class="button button-primary om-review-btns" data-res="yes" rel="noopener"><?php esc_html_e( 'Yes!', 'optin-monster-api' ); ?> 🙂</a>
			<a href="#" class="button button-secondary om-review-btns" data-res="no" target="_blank" rel="noopener"><?php esc_html_e( 'Not Really!', 'optin-monster-api' ); ?></a>
			<?php if ( ! $this->get_api_credentials() ) : ?>
				<a href="https://optinmonster.com/?utm_source=WordPress&utm_campaign=Plugin&utm_medium=ReviewNotice" class="om-dismiss-review-notice om-dismiss-review-notice-delay button button-secondary" target="_blank" rel="noopener">
					<?php esc_html_e( 'What is OptinMonster?', 'optin-monster-api' ); ?>
				</a>
			<?php endif; ?>
		</p>
	</div>
	<br>
	<div class="om-notice-review">
		<div class="om-steps om-step-yes" style="display: none">
			<p><?php esc_html_e( 'That\'s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'optin-monster-api' ); ?></p>
			<p><strong>~ Thomas Griffin<br><?php printf( esc_html__( 'Co-Founder of %1$s', 'optin-monster-api' ), 'OptinMonster' ); ?></strong></p>
			<p>
				<a href="https://wordpress.org/support/plugin/optinmonster/reviews/?filter=5#new-post" class="om-dismiss-review-notice button button-primary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Ok, you deserve it', 'optin-monster-api' ); ?></a>&nbsp;&nbsp;
				<a href="#" class="om-dismiss-review-notice om-dismiss-review-notice-delay" rel="noopener noreferrer"><?php esc_html_e( 'Nope, maybe later!', 'optin-monster-api' ); ?></a>&nbsp;&nbsp;
				<a href="#" class="om-dismiss-review-notice" rel="noopener noreferrer"><?php esc_html_e( 'I already did!', 'optin-monster-api' ); ?></a>
			</p>
		</div>
		<div class="om-steps om-step-no" style="display: none">
			<p><?php printf( esc_html__( 'We\'re sorry to hear you aren\'t enjoying %1$s. We would love a chance to improve. Could you take a minute and let us know what we can do better?', 'optin-monster-api' ), 'OptinMonster' ); ?></p>
			<p>
				<a href="https://optinmonster.com/plugin-review-feedback/" class="om-dismiss-review-notice button button-primary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Give feedback', 'optin-monster-api' ); ?></a>&nbsp;&nbsp;
				<a href="#" class="om-dismiss-review-notice" rel="noopener noreferrer"><?php esc_html_e( 'No thanks!', 'optin-monster-api' ); ?></a>
			</p>
		</div>
	</div>
</div>
