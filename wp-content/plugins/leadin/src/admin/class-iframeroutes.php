<?php

namespace Leadin\admin;

use Leadin\admin\Connection;
use Leadin\admin\MenuConstants;
use Leadin\options\AccountOptions;

/**
 * Class for building iframe routes
 */
class IframeRoutes {

	/**
	 * Get the iframe route based on plugin state
	 *
	 * @param boolean $skip_just_connected if true skips returning the just connected path.
	 *
	 * Returns the oauth path for the iframe.
	 */
	public static function get_oauth_path( $skip_just_connected = false ) {
		if ( Routing::is_oauth_expired() ) {
			return self::get_expired_path();
		} elseif ( ! $skip_just_connected && Routing::has_just_connected_with_oauth() ) {
			return self::get_just_connected_path();
		}

		return '';
	}

	/**
	 * Get the base path for the iframe when plugin is connected
	 */
	private static function get_base_connected_path() {
		$portal_id = AccountOptions::get_portal_id();
		return "/wordpress-plugin-ui/$portal_id";
	}

	/**
	 * Get the iframe route for showing the session expired screen.
	 */
	private static function get_expired_path() {
		return self::get_base_connected_path() . '/expired';
	}

	/**
	 * Get the iframe route for showing the just connected screen.
	 */
	private static function get_just_connected_path() {
		return self::get_base_connected_path() . '/connected';
	}
}
