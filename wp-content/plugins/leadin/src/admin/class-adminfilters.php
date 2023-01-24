<?php
namespace Leadin\admin;

use Leadin\options\HubspotOptions;

/**
 * Class containing all the filters used for the admin side of the plugin.
 */
class AdminFilters {
	/**
	 * Apply leadin_view_plugin_menu_capability filter.
	 */
	public static function apply_view_plugin_menu_capability() {
		return apply_filters( 'leadin_view_plugin_menu_capability', 'edit_posts' );
	}

	/**
	 * Apply leadin_connect_plugin_capability filter.
	 */
	public static function apply_connect_plugin_capability() {
		return apply_filters( 'leadin_connect_plugin_capability', 'manage_options' );
	}
}
