<?php
if (!defined('ABSPATH')) die('No direct access.');
echo '<div class="eum-advanced-settings-container force-updates">';

// Check for wp-config constants that disable force updates
if (defined('AUTOMATIC_UPDATER_DISABLED') && true == AUTOMATIC_UPDATER_DISABLED) {
	printf('<div class="mpsum-error mpsum-bold">%s</div>', esc_html__('Automatic updates are disabled. Please check your wp-config.php file for AUTOMATIC_UPDATER_DISABLED and remove the line.'));
}
if (defined('WP_AUTO_UPDATE_CORE') && false == WP_AUTO_UPDATE_CORE) {
	printf('<div class="mpsum-error mpsum-bold">%s</div>', esc_html__('Automatic updates for Core are disabled. Please check your wp-config.php file for WP_AUTO_UPDATE_CORE and remove the line.'));
}

// Check for options that also disable force updates
$options = MPSUM_Updates_Manager::get_options('core');

// Show a notice if all updates are disabled
if (isset($options['all_updates']) && 'off' == $options['all_updates']) {
	printf('<div class="mpsum-error mpsum-bold">%s</div>', esc_html__('All updates are disabled. Please re-enable all updates for force updates to work.'));
}

// Show a notice if automatic updates are off
if (!MPSUM_Utils::get_instance()->is_automatic_updates_enabled()) {
	printf('<div class="mpsum-error mpsum-bold">%s</div>', esc_html__('Automatic updates are off, so Force updates will not work.'));
}

// Show a warning if delay updates is above zero
if (isset($options['delay_updates']) && $options['delay_updates'] > 0) {
	printf('<div class="mpsum-notice mpsum-bold">%s</div>', esc_html__('Delayed updates are on, so some assets may not be updated automatically.'));
}

// Begin output
printf('<h3>%s</h3>', esc_html__('Force automatic updates', 'stops-core-theme-and-plugin-updates'));
printf('<div class="mpsum-notice mpsum-regular">%s</div>', esc_html__('Force updates will request automatic updates of your plugins, core, themes, and translations immediately. This is useful for debugging and checking that automatic updates are working as intended. By default, WordPress checks for updates every 12 hours. Running force updates will, if successful, cause updates to happen immediately.', 'stops-core-theme-and-plugin-updates'));
$utils = MPSUM_Utils::get_instance();
$updraftplus = $utils->is_installed('updraftplus');
if (true === $updraftplus['installed'] && true === $updraftplus['active']) {
	global $updraftplus_admin;
	if (is_a($updraftplus_admin, 'UpdraftPlus_Admin') && is_callable(array($updraftplus_admin, 'add_backup_scaffolding'))) {
		printf('<label><input type="checkbox" name="backup_force_updates" id="backup_force_updates" value="1" />%s</label>', __('Take a backup first (with UpdraftPlus)', 'stops-core-theme-and-plugin-updates'));
		$updraftplus_admin->add_backup_scaffolding(__('Take a backup before update', 'stops-core-theme-and-plugin-updates'), array($updraftplus_admin, 'backupnow_modal_contents'));
	}
} else {
	if (true === $updraftplus['installed'] && false === $updraftplus['active']) {
		$can_activate = is_multisite() ? current_user_can('manage_network_plugins') : current_user_can('activate_plugins');
		if ($can_activate) {
			$activate_link = is_multisite() ? network_admin_url('plugins.php?action=activate&plugin='.$updraftplus['name']) : self_admin_url('plugins.php?action=activate&plugin='.$updraftplus['name']);
			$url = esc_url(wp_nonce_url(
				$activate_link,
				'activate-plugin_'.$updraftplus['name']
			));
			$url_text = __('Follow this link to activate it.', 'stops-core-theme-and-plugin-updates');
			$anchor = "<a href=\"{$url}\">{$url_text}</a>";
		}
		$required_plugin = __('Take a backup with UpdraftPlus before updating.', 'stops-core-theme-and-plugin-updates');
		printf('<p id="eum-auto-backup-description">%s %s</p>', $required_plugin, $anchor);
	} else {
		if (current_user_can('install_plugins')) {
			$url = esc_url(wp_nonce_url(
				is_multisite() ? network_admin_url('update.php?action=install-plugin&plugin=updraftcentral') : self_admin_url('update.php?action=install-plugin&plugin=updraftplus'),
				'install-plugin_updraftplus'
			));
			$url_text = __('Follow this link to install it.', 'stops-core-theme-and-plugin-updates');
			$anchor = "<a href=\"{$url}\">{$url_text}</a>";
			$required_plugin = __('You can take backups using UpdraftPlus before updating.', 'stops-core-theme-and-plugin-updates');
			printf('<p id="eum-auto-backup-description">%s %s</p>', $required_plugin, $anchor);
		}
	}
}
printf('<p class="submit"><input type="submit" name="submit" id="force-updates" class="button button-primary" value="%s"></p>', esc_attr__('Force updates', 'stops-core-theme-and-plugin-updates'));
echo '</div>';
