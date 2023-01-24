<?php

namespace Leadin\admin;

use Leadin\LeadinFilters;
use Leadin\admin\Links;
use Leadin\admin\Connection;
use Leadin\options\AccountOptions;

/**
 * Class responsible for the custom functionalities inside the plugins.php page.
 */
class PluginActionsManager {
	/**
	 * Class constructor, adds the necessary hooks.
	 */
	public function __construct() {
		add_filter( 'plugin_action_links_leadin/leadin.php', array( $this, 'add_plugin_settings_link' ) );
		add_filter( 'plugin_action_links_leadin/leadin.php', array( $this, 'leadin_plugin_advanced_features_link' ) );
	}

	/**
	 * Adds setting link for Leadin to plugins management page.
	 *
	 * @param   array $links Return the links for the settings page.
	 * @return  array
	 */
	public function add_plugin_settings_link( $links ) {
		if ( Connection::is_connected() ) {
			$page = 'leadin_settings';
		} else {
			$page = 'leadin';
		}
		$url           = get_admin_url( get_current_blog_id(), "admin.php?page=$page" );
		$settings_link = '<a href="' . $url . '">' . __( 'Settings', 'leadin' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Adds upgrade link for Leadin to plugins management page
	 *
	 * @param   array $links Return the links for the upgrade page.
	 * @return  array
	 */
	public function leadin_plugin_advanced_features_link( $links ) {
		if ( Connection::is_connected() ) {
			$portal_id              = AccountOptions::get_portal_id();
			$url                    = LeadinFilters::get_leadin_base_url() . '/pricing/' . $portal_id . '/marketing?' . Links::get_query_params();
			$advanced_features_link = '<a class="hubspot-menu-pricing" target="_blank" rel="noopener" href="' . esc_attr( $url ) . '">' . esc_html( __( 'Upgrade', 'leadin' ) ) . '</a>';
			array_push( $links, $advanced_features_link );
		}
		return $links;
	}
}
