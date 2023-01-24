<?php

namespace Leadin\rest;

use Leadin\LeadinFilters;
use Leadin\auth\OAuth;

/**
 * Client for making requests against HubSpot API's.
 */
class HubSpotApiClient {

	/**
	 * Get the default headers that all requests to HubSpot API's usually need.
	 *
	 * @return array An Associate array of HTTP headers to send with all HubSpot Api requests.
	 */
	private static function get_default_headers() {
		return array( 'Content-Type' => 'application/json' );
	}

	/**
	 * Get the headers needed to authorise HTTP requests with an access token.
	 *
	 * @return array An associative array of HTTP headers for OAuth authorisation.
	 */
	private static function get_oauth_headers() {
		return array( 'Authorization' => 'Bearer ' . OAuth::get_access_token() );
	}

	/**
	 * Make a HTTP request against HubSpot API's
	 *
	 * @param string $api_path the api path to hit.
	 * @param string $method The type of HTTP request to make, GET/POST etc.
	 * @param array  $headers Any headers that should be added to the default array of headers sent with the request.
	 * @param string $body string for the http request body.
	 * @param string $cross_hublet if true the request will be not be hublet specific.
	 *
	 * @return array The response from HubSpot API's.
	 *
	 * @throws \Exception For any errors in making the API request and for any errors returned from the HubSpot API.
	 */
	public static function make_request( $api_path, $method, $headers = array(), $body = '', $cross_hublet = false ) {
		$api_root = LeadinFilters::get_leadin_base_api_url( $cross_hublet );
		$url      = $api_root . $api_path;

		$headers = array_merge(
			self::get_default_headers(),
			$headers
		);

		$request = wp_remote_request(
			$url,
			array(
				'method'  => $method,
				'headers' => $headers,
				'body'    => $body,
			)
		);

		if ( is_wp_error( $request ) ) {
			throw new \Exception( \json_encode( 'WP HTTP Error' ), 500 );
		}

		$response_code = wp_remote_retrieve_response_code( $request );
		if ( $response_code >= '400' ) {
			throw new \Exception( wp_remote_retrieve_body( $request ), $response_code );
		}

		return $request;
	}

	/**
	 * Makes a request against HubSpot api's using the connected portals OAuth access token.
	 *
	 * @param string $api_path API path to hit on api.hubspot.com.
	 * @param string $method Type of HTTP request to make, GET/POST etc.
	 * @param string $body Http request string body.
	 * @param string $cross_hublet if true the request will be not be hublet specific.
	 *
	 * @return array The response from HubSpot's api.
	 *
	 * @throws \Exception On failed HTTP requests.
	 */
	public static function authenticated_request( $api_path, $method, $body = '', $cross_hublet = false ) {
		return self::make_request(
			$api_path,
			$method,
			self::get_oauth_headers(),
			$body,
			$cross_hublet
		);
	}

	/**
	 * Makes a request to  HubSpot's OAuth service to refresh the OAuth access token.
	 *
	 * @param string $refresh_token Refresh token to use to refresh the access token.
	 *
	 * @return array The response from the OAuth refresh endpoint.
	 *
	 * @throws \Exception On failed HTTP refresh request.
	 */
	public static function refresh_access_token( $refresh_token ) {
		$path = "/wordpress/v1/oauth/refresh?refresh_token=$refresh_token";

		$refresh_request = self::make_request( $path, 'POST' );

		return json_decode( wp_remote_retrieve_body( $refresh_request ) );
	}

	/**
	 * Query contacts created after a particular timestamp.
	 *
	 * @param integer $timestamp timestamp to filter create date property.
	 *
	 * @param integer $limit max number of results.
	 */
	public static function get_contacts_from_timestamp( $timestamp, $limit = 10 ) {
		$path = '/crm/v3/objects/contacts/search';

		$js_timestamp = $timestamp * 1000;

		$payload = array(
			'filterGroups' => array(
				array(
					'filters' => array(
						array(
							'propertyName' => 'createdate',
							'operator'     => 'GTE',
							'value'        => $js_timestamp,
						),
					),
				),
			),
			'sort'         => array( 'createdate' ),
			'properties'   => array( 'createdate' ),
			'limit'        => $limit,
			'after'        => 0,
		);

		$contacts_request = self::authenticated_request(
			$path,
			'POST',
			json_encode( $payload )
		);

		return json_decode( wp_remote_retrieve_body( $contacts_request ) );
	}

	/**
	 * Make an API request to get the portal's details including the hublet it's in.
	 *
	 * @param integer $portal_id portal id.
	 */
	public function get_portal_details( $portal_id ) {
		$api_path     = "/account-info/v3/details?portalId=$portal_id";
		$cross_hublet = true;

		$response      = self::authenticated_request(
			$api_path,
			'GET',
			'',
			$cross_hublet
		);
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		return $response_body;
	}

}
