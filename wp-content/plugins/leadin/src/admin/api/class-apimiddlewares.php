<?php

namespace Leadin\admin\api;

use Leadin\wp\User;

/**
 * Contains all the middleware functions for AJAX Api's
 */
class ApiMiddlewares {
	/**
	 * Middleware used to validate the nonce passed with the request body.
	 * The nonce has to be passed as a `_ajax_nonce` query parameter, and it will be checked against the `hubspot-ajax` nonce.
	 */
	public static function validate_nonce() {
		$valid = check_ajax_referer( 'hubspot-ajax', false, false );
		if ( ! $valid ) {
			wp_die( '{ "error": "CSRF token missing or invalid" }', 403 );
		}
	}

	/**
	 * Middleware that only allows admin.
	 */
	public static function admin_only() {
		if ( ! User::is_admin() ) {
			wp_die( '{ "error": "Insufficient permissions" }', '', 403 );
		}
	}
}
