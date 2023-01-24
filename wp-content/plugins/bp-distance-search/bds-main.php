<?php
/*
Plugin Name: BP Distance Search
Plugin URI: http://www.dontdream.it/bp-distance-search/
Description: Adds a Google Place Autocomplete profile field type for BuddyPress, and enables search by distance with BP Profile Search.
Version: 1.3
Author: Andrea Tarantini
Author URI: http://www.dontdream.it/
Text Domain: bp-distance-search
*/

add_action ('admin_notices', 'bds_no_buddypress');
function bds_no_buddypress ()
{
?>
	<div class="notice notice-error is-dismissible">
		<p><?php _e('BP Distance Search requires BuddyPress with the Extended Profiles component activated.', 'bp-distance-search'); ?></p>
	</div>
<?php
}

add_action ('bp_include', 'bds_buddypress');
function bds_buddypress ()
{
	if (bp_is_active ('xprofile'))
	{
		remove_action ('admin_notices', 'bds_no_buddypress');
		include 'bds-location.php';
	}
}
