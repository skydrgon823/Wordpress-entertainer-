<?php
/*
Plugin Name: BP Profile Search
Plugin URI: http://www.dontdream.it/bp-profile-search/
Description: Member search and member directories for BuddyPress or the BuddyBoss platform.
Version: 5.4
Author: Andrea Tarantini
Author URI: http://www.dontdream.it/
Text Domain: bp-profile-search
*/

define ('BPS_VERSION', '5.4');
define ('BPS_PLUGIN_BASENAME', plugin_basename (__FILE__));

add_action ('admin_notices', 'bps_no_buddypress');
function bps_no_buddypress ()
{
?>
	<div class="notice notice-error is-dismissible">
		<p><?php _e('BP Profile Search requires BuddyPress or the BuddyBoss platform.', 'bp-profile-search'); ?></p>
	</div>
<?php
}

add_action ('bp_include', 'bps_buddypress');
function bps_buddypress ()
{
	remove_action ('admin_notices', 'bps_no_buddypress');
	include 'bps-start.php';
}

add_action ('in_plugin_update_message-'. BPS_PLUGIN_BASENAME, 'bps_update_message', 10, 2);
function bps_update_message ($plugin_data, $response)
{
}

function bps_platform ()
{
	static $platform;
	if (isset ($platform))  return $platform;

	include_once ABSPATH. 'wp-admin/includes/plugin.php';
	
	if (is_plugin_active ('buddyboss-platform/bp-loader.php'))
		$platform = 'buddyboss';
	else if (is_plugin_active ('buddypress/bp-loader.php'))
		$platform = 'buddypress';

	return $platform;
}
