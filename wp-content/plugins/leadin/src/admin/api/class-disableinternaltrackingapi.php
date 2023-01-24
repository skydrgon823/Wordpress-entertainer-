<?php

namespace Leadin\admin\api;

use Leadin\options\AccountOptions;
use Leadin\utils\RequestUtils;

/**
 * Disable Internal Tracking Api. Used to exclude internal users to appear in HS analytics
 */
class DisableInternalTrackingApi extends ApiGenerator {
	/**
	 * Disable Internal Tracking API constructor. Adds the ajax hooks.
	 *
	 * @var String $endpoint API endpoint.
	 */
	public $endpoint = 'wp_ajax_leadin_disable_internal_tracking';

	/**
	 * Get or set the disable internal tracking option
	 */
	public function run() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) ) {
			switch ( $_SERVER['REQUEST_METHOD'] ) {
				case 'GET':
					RequestUtils::send_message( AccountOptions::get_disable_internal_tracking() );
					break;
				case 'POST':
					$request_body = file_get_contents( 'php://input' );
					$data         = json_decode( $request_body, true );

					AccountOptions::update_disable_internal_tracking( $data );
					RequestUtils::send_message( '200 Ok' );
					break;
				default:
					RequestUtils::send_message( 'Method not supported.' );
					break;
			}
		}
	}
}
