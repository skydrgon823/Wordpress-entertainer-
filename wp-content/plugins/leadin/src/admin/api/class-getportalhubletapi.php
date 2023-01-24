<?php

namespace Leadin\admin\api;

use Leadin\LeadinFilters;
use Leadin\options\AccountOptions;
use Leadin\utils\RequestUtils;
use Leadin\rest\HubSpotApiClient;


/**
 * Get's hublet for portal.
 */
class GetPortalHubletApi extends ApiGenerator {
	/**
	 * Update Hublet API constructor. Adds the ajax hooks.
	 *
	 * @var String $endpoint API endpoint.
	 */
	public $endpoint = 'wp_ajax_leadin_get_portal_hublet';

	/**
	 * Get's correct hublet and returns it.
	 */
	public function run() {
		$portal_id      = AccountOptions::get_portal_id();
		$portal_details = HubSpotApiClient::get_portal_details( $portal_id );
		$hublet         = $portal_details['dataHostingLocation'];

		if ( ! $hublet ) {
			return RequestUtils::send_error_message( 'Failed to load hublet', 500 );
		}
		RequestUtils::send_json(
			array(
				'hublet' => $hublet,
			)
		);
	}
}
