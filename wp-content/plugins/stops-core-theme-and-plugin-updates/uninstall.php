<?php
/**
 * Uninstall script
 *
 * Uninstall script for Easy Updates Manager.
 *
 * @package WordPress
 * @since 5.0.0
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit();
}

// Only wipe settings if the alternative version of the plugin isn't installed
$isPremium = file_exists(__DIR__ . '/premium.php');
$isFree = !$isPremium;

// We use the active_plugins array of active plugins instead of the original plugin directories, as sometimes users rename their directories
$active_plugins = get_option('active_plugins');
$all_plugins = get_plugins();
foreach ($all_plugins as $key => $value) {
	if (!in_array($key, $active_plugins)) {
		unset($all_plugins[$key]);
	}
}

foreach ($all_plugins as $plugin) {
	if ('Easy Updates Manager Premium' == $plugin['Name']) $premiumActive = true;
	if ('Easy Updates Manager' == $plugin['Name']) $freeActive = true;
}

if (($isPremium && !$freeActive) || ($isFree && !$premiumActive)) {
	delete_option('_disable_updates');
	delete_site_option('_disable_updates');
	delete_option('MPSUM');
	delete_site_option('MPSUM');
	delete_option('mpsum_log_table_version');
	delete_site_option('mpsum_log_table_version');
	delete_site_transient('MPSUM_PLUGINS');
	delete_site_transient('MPSUM_THEMES');
	delete_site_option('easy_updates_manager_dismiss_dash_notice_until');
	delete_site_option('easy_updates_manager_dismiss_eum_notice_until');
	delete_site_option('easy_updates_manager_dismiss_page_notice_until');
	delete_site_option('easy_updates_manager_dismiss_season_notice_until');
	delete_site_option('easy_updates_manager_dismiss_survey_notice_until');
	delete_site_option('easy_updates_manager_dismiss_constant_notices');
	delete_site_transient('eum_plugin_updates');
	delete_site_transient('eum_theme_updates');
	delete_site_transient('eum_core_updates');
	delete_site_option('eum_email_flood_control');
	wp_clear_scheduled_hook('eum_notification_updates_weekly');
	wp_clear_scheduled_hook('eum_notification_updates_monthly');
	wp_clear_scheduled_hook('eum_clear_logs');
	delete_site_transient('mpsum_version_numbers');
	delete_site_option('easy_updates_manager_webhook');
	delete_site_option('eum_active_pre_restore_plugins');
	delete_site_option('eum_active_pre_restore_plugins_multisite');
	delete_site_transient('eum_all_sites_active_plugins');
	delete_site_transient('eum_all_sites_active_themes');
	delete_site_option('easy_updates_manager_enable_notices');
	delete_site_option('easy_updates_manager_name');
	delete_site_option('easy_updates_manager_author');
	delete_site_option('easy_updates_manager_url');

	// For logs removal
	global $wpdb;
	$tablename = $wpdb->base_prefix . 'eum_logs';
	$sql = "drop table if exists $tablename";
	$wpdb->query($sql);
	delete_site_option('mpsum_log_table_version');

	// Remove Plugin Check Options and Transients
	delete_site_transient('eum_plugins_removed_from_directory');
	if (is_multisite()) {
		$options_sql = "delete from {$wpdb->sitemeta} where meta_key like 'eum_plugin_removed_%'";
		$wpdb->query($options_sql);
	} else {
		$options_sql = "delete from {$wpdb->options} where option_name like 'eum_plugin_removed_%'";
		$wpdb->query($options_sql);
	}

	// Remove Safe Mode Options and Transients
	if (is_multisite()) {
		$safe_mode_sql = "delete from {$wpdb->sitemeta} where meta_key like '%eum_plugin_safe_mode_%'";
		$wpdb->query($safe_mode_sql);
	} else {
		$safe_mode_sql = "delete from {$wpdb->options} where option_name like '%eum_plugin_safe_mode_%'";
		$wpdb->query($safe_mode_sql);
	}

	// Remove Unmaintained Plugin Transients from the database
	if (is_multisite()) {
		$unmaintained_plugin_sql = "delete from {$wpdb->sitemeta} where meta_key like '%eum_plugin_unmaintained_%'";
		$wpdb->query($unmaintained_plugin_sql);
	} else {
		$unmaintained_plugin_sql = "delete from {$wpdb->options} where option_name like '%eum_plugin_unmaintained_%'";
		$wpdb->query($unmaintained_plugin_sql);
	}

	// Remove transients when someone disables plugin, theme, or core updates
	delete_site_transient('eum_core_checked');
	delete_site_transient('eum_themes_checked');
	delete_site_transient('eum_plugins_checked');
}
