<?php

namespace Leadin\wp;

/**
 * Static function that wraps WordPress utility functions for the WordPress site.
 */
class Website {

	/**
	 * Return homepage url.
	 */
	public static function get_url() {
		return get_home_url();
	}

	/**
	 * Return WordPress ajax url.
	 */
	public static function get_ajax_url() {
		return admin_url( 'admin-ajax.php' );
	}

	/**
	 * Return the "stylesheet" option
	 */
	public static function get_theme() {
		return Options::get( 'stylesheet' );
	}
}
