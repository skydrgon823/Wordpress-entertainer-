<?php

namespace Leadin\admin\api;

use Leadin\admin\Connection;
use Leadin\utils\RequestUtils;

/**
 * Disconnect Api, used to clean portal id and domain from the WordPress options.
 */
class DisconnectApi extends ApiGenerator {
	/**
	 * Disconnect API constructor. Adds the ajax hooks.
	 *
	 * @var String $endpoint API endpoint.
	 */
	public $endpoint = 'wp_ajax_leadin_disconnect_ajax';

	/**
	 * Disconnect Api runner. Removes portal id and domain from the WordPress options.
	 */
	public function run() {
		if ( Connection::is_connected() ) {
			Connection::disconnect();
			RequestUtils::send_message( 'Success' );
		} else {
			RequestUtils::send_message( 'No leadin_portal_id found, cannot disconnect' );
		}
	}
}
