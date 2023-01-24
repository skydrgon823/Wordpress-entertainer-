<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// Disable indexing of CookieLawInfo Cookie data
echo '<!--googleoff: all-->';

if ( $notify_html == '' ) {
	return; } //if filter is applied
echo $notify_html;
$pop_out               = '';
$pop_content_html_file = plugin_dir_path( CLI_PLUGIN_FILENAME ) . 'public/views/cookie-law-info_popup_content.php';
if ( file_exists( $pop_content_html_file ) ) {
	include $pop_content_html_file;
}
?>
<div class="cli-modal" data-nosnippet="true" id="cliSettingsPopup" tabindex="-1" role="dialog" aria-labelledby="cliSettingsPopup" aria-hidden="true">
  <div class="cli-modal-dialog" role="document">
	<div class="cli-modal-content cli-bar-popup">
	  	<button type="button" class="cli-modal-close" id="cliModalClose">
			<svg class="" viewBox="0 0 24 24"><path d="M19 6.41l-1.41-1.41-5.59 5.59-5.59-5.59-1.41 1.41 5.59 5.59-5.59 5.59 1.41 1.41 5.59-5.59 5.59 5.59 1.41-1.41-5.59-5.59z"></path><path d="M0 0h24v24h-24z" fill="none"></path></svg>
			<span class="wt-cli-sr-only"><?php echo __( 'Close', 'cookie-law-info' ); ?></span>
	  	</button>
	  	<div class="cli-modal-body">
			<?php echo $pop_out;?>
	  	</div>
	  	<div class="cli-modal-footer">
			<div class="wt-cli-element cli-container-fluid cli-tab-container">
				<div class="cli-row">
					<div class="cli-col-12 cli-align-items-stretch cli-px-0">
						<div class="cli-tab-footer wt-cli-privacy-overview-actions">
						
							<?php if ( apply_filters( 'wt_cli_enable_settings_accept_btn', true ) === true ) : ?>
								<a id="wt-cli-privacy-save-btn" role="button" tabindex="0" data-cli-action="accept" class="wt-cli-privacy-btn cli_setting_save_button wt-cli-privacy-accept-btn cli-btn"><?php echo __( 'SAVE & ACCEPT', 'cookie-law-info' ); ?></a>
							<?php endif; ?>
						</div>
						<?php if ( apply_filters( 'wt_cli_enable_ckyes_branding', true ) === true ) : ?>
						<div class="wt-cli-ckyes-footer-section">
							<div class="wt-cli-ckyes-brand-logo"><?php echo __( 'Powered by', 'cookie-law-info' ); ?> <a target="_blank" href="https://www.cookieyes.com/"><img src="<?php echo CLI_PLUGIN_URL . 'public/images/logo-cookieyes.svg'; ?>"></a></div>
						</div>
						<?php endif; ?>

					</div>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>
<div class="cli-modal-backdrop cli-fade cli-settings-overlay"></div>
<div class="cli-modal-backdrop cli-fade cli-popupbar-overlay"></div>
<?php
// Re-enable indexing
echo '<!--googleon: all-->';
