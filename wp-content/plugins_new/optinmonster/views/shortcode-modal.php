<div id="optin-monster-modal-backdrop" class="optin-monster-modal-inline" style="display: none"></div>
<div id="optin-monster-modal-wrap" class="optin-monster-modal-inline" style="display: none">
	<form id="optin-monster-modal" tabindex="-1">
		<div id="optin-monster-modal-title">
			<span class="optin-monster-modal-inline-item"><?php esc_html_e( 'Insert OptinMonster Campaign', 'optin-monster-api' ); ?></span>
			<span class="optin-monster-modal-monsterlink-item"><?php esc_html_e( 'Insert/Edit Link to an OptinMonster Campaign', 'optin-monster-api' ); ?></span>
			<button type="button" id="optin-monster-modal-close"><span class="screen-reader-text"><?php esc_html_e( 'Close', 'optin-monster-api' ); ?></span></button>
		</div>
		<div id="optin-monster-modal-inner">
			<div id="optin-monster-modal-options">
				<div class="optin-monster-modal-inline-item">
					<?php
					if ( ! empty( $data['campaigns']['inline'] ) ) {
						printf( '<p><label for="optin-monster-modal-select-inline-campaign">%s</label></p>', esc_html__( 'Select and display your email marketing form or smart call-to-action campaign', 'optin-monster-api' ) );
						echo '<select id="optin-monster-modal-select-inline-campaign">';
						foreach ( $data['campaigns']['inline'] as $slug => $name ) {
							printf( '<option value="%s">%s</option>', $slug, esc_html( $name ) );
						}
						echo '</select>';
						echo '<p class="optin-monster-modal-notice">';
						printf(
							wp_kses( /* translators: %s - OptinMonster documentation URL. */
								__( 'Or <a href="%s" target="_blank" rel="noopener noreferrer">create a new inline campaign</a> to embed in this post', 'optin-monster-api' ),
								array(
									'a' => array(
										'href'   => array(),
										'rel'    => array(),
										'target' => array(),
									),
								)
							),
							$data['templatesUri'] . '&type=inline'
						);
						echo '</p>';
					} else {
						echo '<p>';
						printf(
							wp_kses(
								/* translators: %s - OptinMonster Templates page. */
								__( 'Whoops, you haven\'t created an inline campaign yet. Want to <a href="%s">give it a go</a>?', 'optin-monster-api' ),
								array(
									'a' => array(
										'href' => array(),
									),
								)
							),
							$data['templatesUri'] . '&type=inline'
						);
						echo '</p>';
					}
					?>
				</div>
				<div class="optin-monster-modal-monsterlink-item">
					<?php
					if ( ! empty( $data['campaigns']['other'] ) ) {
						printf( '<p><label for="optin-monster-modal-select-campaign">%s</label></p>', esc_html__( 'Select a Click to Load Campaign to link.', 'optin-monster-api' ) );
						echo '<select id="optin-monster-modal-select-campaign">';
						foreach ( $data['campaigns']['other'] as $slug => $name ) {
							printf( '<option value="%s">%s</option>', $slug, esc_html( $name ) );
						}
						echo '</select>';
						echo '<p class="optin-monster-modal-notice">';
						printf(
							wp_kses( /* translators: %s - OptinMonster documentation URL. */
								__( 'Or <a href="%s" target="_blank" rel="noopener noreferrer">create a new Click to Load Campaign</a>.', 'optin-monster-api' ),
								array(
									'a' => array(
										'href'   => array(),
										'rel'    => array(),
										'target' => array(),
									),
								)
							),
							$data['templatesUri'] . '&type=popup'
						);
						echo '</p>';
					} else {
						echo '<p>';
						printf(
							wp_kses(
								/* translators: %s - OptinMonster Templates page. */
								__( 'Whoops, you haven\'t created a popup campaign yet. Want to <a href="%s">give it a go</a>?', 'optin-monster-api' ),
								array(
									'a' => array(
										'href' => array(),
									),
								)
							),
							$data['templatesUri'] . '&type=popup'
						);
						echo '</p>';
					}
					?>
				</div>
			</div>
		</div>
		<div class="submitbox">
			<div id="optin-monster-modal-cancel">
				<a class="submitdelete deletion" href="#"><?php esc_html_e( 'Cancel', 'optin-monster-api' ); ?></a>
			</div>
			<?php if ( ! empty( $data['campaigns']['inline'] ) || ! empty( $data['campaigns']['other'] ) ) : ?>
				<div id="optin-monster-modal-update">
					<button class="button button-primary optin-monster-modal-monsterlink-item" id="optin-monster-modal-submit"><?php esc_html_e( 'Link Campaign', 'optin-monster-api' ); ?></button>
					<button class="button button-primary optin-monster-modal-inline-item" id="optin-monster-modal-submit-inline"><?php esc_html_e( 'Add Campaign', 'optin-monster-api' ); ?></button>
				</div>
			<?php endif; ?>
		</div>
	</form>
</div>
