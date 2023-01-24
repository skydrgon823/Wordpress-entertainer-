<?php
if (!defined('ABSPATH')) die('No direct access.');
$easy_updates_manager_url = 'https://easyupdatesmanager.com/buy/?utm=eum-premium-tab';
$updraftcentral_url = 'https://updraftplus.com/updraftcentral/?utm=eum-premium-tab';
$updraftplus_url = 'https://updraftplus.com/?utm=eum-premium-tab';
$easy_updates_manager_downloads = '200,000';
?>
<div class="advanced-premium">
	<h3><?php _e('Get Easy Updates Manager Premium', 'stops-core-theme-and-plugin-updates'); ?></h3>
	<p class="mpsum-medium"><?php
	
		_e('Get many more features with Easy Updates Manager Premium.', 'stops-core-theme-and-plugin-updates');
		
		printf(' '.__('Check out the video and %s below, or %s', 'stops-core-theme-and-plugin-updates'), '<a href="#mpsum-advanced-premium-features">'.__('feature list', 'stops-core-theme-and-plugin-updates').'</a>', '<a href="' . $easy_updates_manager_url . '">'.__('go to our store.', 'stops-core-theme-and-plugin-updates').'</a>');
		
	?></p>
	<?php
	// @codingStandardsIgnoreStart
	?>
	<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/289883791?color=df6926&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>
	<p class="mpsum-medium mpsum-caption"><a href="<?php echo esc_attr($easy_updates_manager_url); ?>"><?php _e('Find out about the advantages of upgrading to Easy Updates Manager Premium', 'stops-core-theme-and-plugin-updates'); ?></a></p>
	<div class="mpsum-advanced-premium-features" id="mpsum-advanced-premium-features">
		<h3><?php esc_html_e('Premium features include:', 'stops-core-theme-and-plugin-updates');?></h3>
		<ul class="mpsum-advanced-premium-list">
			<li><strong><?php _e('Safe mode', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php _e("Prevent updates that are not compatible with your current WordPress version or your server's PHP version.", 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php _e('Scheduling updates', 'stops-core-theme-and-plugin-updates');?></strong><span class="mpsum-list-description"><?php _e('Choose the most convenient time to run your automatic updates.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php _e('External logging', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php _e('Sends alert when updates have taken place.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php _e('Anonymization', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php _e("Controls what is sent to the WordPress.org API; stop sending unnecessary/analytics data.", 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php _e('Delayed updates', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php _e('Delays automatic updates by a set time to prevent installing short-lived (e.g. buggy) updates.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php _e('Automatic backups', 'stops-core-theme-and-plugin-updates');?></strong><span class="mpsum-list-description"><?php printf(__('Takes a backup before your website is updated via an integration with %s', 'stops-core-theme-and-plugin-updates'), '<a href="'.esc_attr($updraftplus_url).'">UpdraftPlus</a>'); ?></span></li>
			<li><strong>UpdraftCentral</strong><span class="mpsum-list-description"><?php printf(__('Fully integrates with %s for centralized remote control.', 'stops-core-theme-and-plugin-updates'), '<a href="'.$updraftcentral_url.'">UpdraftCentral</a>'); ?></span></li>
			<li><strong><?php _e('Log clearing', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php _e('Automatically prune your logs via scheduled deletion of older entries.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php _e('Import/export', 'stops-core-theme-and-plugin-updates');?></strong><span class="mpsum-list-description"><?php _e('Export your settings from one site to another for quicker setup.', 'stops-core-theme-and-plugin-updates');?></span></li>
			<li><strong><?php _e('E-mail notifications', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php _e('Send weekly or monthly reports of pending updates.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php _e('White-label', 'stops-core-theme-and-plugin-updates'); ?></strong> <?php _e('Customize what branding and notices your clients see in the plugin settings.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php _e('Check plugins', 'stops-core-theme-and-plugin-updates');?></strong><span class="mpsum-list-description"><?php _e('Runs a check for plugins that have been removed from the WordPress directory.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php _e('Webhook', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php _e('Integrates with third-party services to allow automatic updates to be triggered via cron or tools like Zapier.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php _e('Export logs', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php _e('Exports logs for your chosen date range for printing or a CSV/JSON for auditing.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
		</ul>
		<strong><?php _e('All with premium support, and more planned!', 'stops-core-theme-and-plugin-updates'); ?></strong> <a href="<?php echo $easy_updates_manager_url; ?>"><?php _e('Go to our store to get it.', 'stops-core-theme-and-plugin-updates'); ?></a>
	</div>
	<div class="eum-button-cta">
		<a href="<?php echo $easy_updates_manager_url; ?>"><?php _e('Get Premium Today!', 'stops-core-theme-and-plugin-updates'); ?></a>
	</div>
</div><!-- advanced-premium -->
<?php
	// @codingStandardsIgnoreEnd