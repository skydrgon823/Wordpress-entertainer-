<?php

namespace Leadin\admin\api;

use Leadin\LeadinFilters;
use Leadin\options\AccountOptions;
use Leadin\utils\RequestUtils;
use Leadin\rest\HubSpotApiClient;

/**
 * Update hublet API. Used to set the correct hublet after portal migration.
 */
class UpdateHubletApi extends ApiGenerator {
	/**
	 * Update Hublet API constructor. Adds the ajax hooks.
	 *
	 * @var String $endpoint API endpoint.
	 */
	public $endpoint = 'wp_ajax_leadin_update_hublet';

	/**
	 * Get's correct hublet and updates it in Options
	 */
	public function run() {
		$request_body = file_get_contents( 'php://input' );
		$data         = json_decode( $request_body, true );
		$hublet       = $data['hublet'];

		if ( ! $hublet ) {
			return RequestUtils::send_error_message( 'hublet is required', 400 );
		}
		AccountOptions::update_hublet( $hublet );
		RequestUtils::send_message( $hublet );
	}
}
