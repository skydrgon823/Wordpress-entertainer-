<?php if (!defined('ABSPATH')) die('No direct access.'); ?>
<div id="result"><?php
$reset_options = get_site_option('easy_updates_manager_reset');
if (isset($_GET['action']) && 'reset' == $_GET['action'] && false !== $reset_options && 'true' === $reset_options) {
?>
	<div class="updated eum-updated"><p>
	<?php
	esc_html_e('The plugin settings have now been reset.', 'stops-core-theme-and-plugin-updates');
	?>
	</p></div>
	<?php
	delete_site_option('easy_updates_manager_reset');
}
?></div>
<div id="eum-adv-settings-container">
	<div id="eum-adv-settings-menu" class="wp-clearfix">
		<div>
			<?php do_action('eum_advanced_headings'); ?>
		</div>
	</div>
	<div id="eum-adv-settings">
		<?php do_action('eum_advanced_settings'); ?>
	</div>
</div>
