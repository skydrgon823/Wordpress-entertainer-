<?php

namespace Leadin\rest;

use Leadin\auth\OAuth;
use Leadin\LeadinFilters;
use Leadin\admin\AdminFilters;
use Leadin\rest\HubSpotApiClient;

/**
 * Basic rest endpoint to proxy json requests to and from the HubSpot API's
 */
class LeadinRestApi {

	const WHITELISTED_URLS = array(
		'/leadin/v1/settings?',
		'/contacts/v1/lists?',
		array( 'regex' => '/^\/crm\/v3\/objects\/contacts\??(?:\/[0-9]*\?){0,1}/i' ),
		array( 'regex' => '/^\/forms\/v2\/forms\??(?:&?[^=&]*=[^=&]*)*/i' ),
		'/cosemail/v1/emails/listing?',
		'/wordpress/v1/proxy/live-chat-status?',
		'/wordpress/v1/meetings/links?',
		'/wordpress/v1/meetings/user?',
		'/usercontext/v1/external/actions?',
		'/usercontext-app/v1/external/onboarding/tasks/wordpress_plugin_inexperienced?',
		'/usercontext-app/v1/external/onboarding/progress/wordpress_plugin_inexperienced?',
		'/usercontext-app/v1/external/onboarding/tasks/wp-connect-website-mocked/skip?',
		'/usercontext-app/v1/external/onboarding/tasks/wordpress-marketing-demo/skip?',
		'/usercontext-app/v1/external/onboarding/tasks/wordpress-academy-lesson/skip?',
		'/usercontext-app/v1/external/onboarding/tasks/import-contacts/skip?',
		'/usercontext-app/v1/external/onboarding/tasks/invite-your-team/skip?',
		'/usercontext-app/v1/external/onboarding/tasks/visit-hubspot-marketplace/skip?',
	);

	/**
	 * Class constructor, registering rest endpoints.
	 */
	public function __construct() {
		add_action(
			'rest_api_init',
			array( $this, 'register_routes' )
		);
	}

	/**
	 * Register all the routes for the leadin REST Api service
	 */
	public function register_routes() {
		self::register_leadin_route(
			'/proxy(?P<path>.*)',
			\WP_REST_Server::ALLMETHODS,
			array( $this, 'proxy_request' )
		);

		self::register_leadin_route(
			'/healthcheck',
			\WP_REST_Server::READABLE,
			array( $this, 'healthcheck_request' )
		);

		self::register_leadin_route(
			'/oauth-token',
			\WP_REST_Server::READABLE,
			array( $this, 'oauth_token_request' )
		);
	}

	/**
	 * Register a route with given parameters
	 *
	 * @param string $path The path for the route to register the service on. Route gets namespaced with leadin/v1.
	 * @param string $methods Comma seperated list of methods allowed for this route.
	 * @param array  $callback Method to execute when this endpoint is requested.
	 */
	public function register_leadin_route( $path, $methods, $callback ) {
		register_rest_route(
			'leadin/v1',
			$path,
			array(
				'methods'             => $methods,
				'callback'            => $callback,
				'permission_callback' => array( $this, 'verify_permissions' ),
			)
		);
	}

	/**
	 * Proxy the request from the frontend to the HubSpot api's User is authenticated via nonce
	 * and permissions are checked in the proxy_permissions callback.
	 *
	 * @param array $request Request to proxy forward.
	 *
	 * @return \WP_REST_Response Response object to return from this endpoint.
	 */
	public function proxy_request( $request ) {
		$proxy_url = $request->get_params()['proxyUrl'];
		if ( $proxy_url ) {
			$regex = array_filter(
				self::WHITELISTED_URLS,
				function( $value ) use ( $proxy_url ) {
					return is_array( $value ) && preg_match( $value['regex'], $proxy_url );
				}
			);
			if ( ! in_array( $proxy_url, self::WHITELISTED_URLS, true ) && empty( $regex ) ) {
				return new \WP_REST_Response( $proxy_url . ' not found.', 404 );
			}
			if ( substr( $proxy_url, -1 ) === '?' ) {
				$proxy_url = substr( $proxy_url, 0, -1 );
			}

			try {
				$proxy_request = HubSpotApiClient::authenticated_request( $proxy_url, $request->get_method(), $request->get_body() );
			} catch ( \Exception $e ) {
				return new \WP_REST_Response( json_decode( $e->getMessage() ), $e->getCode() );
			}

			$response_code = wp_remote_retrieve_response_code( $proxy_request );
			$response_body = wp_remote_retrieve_body( $proxy_request );

			return new \WP_REST_Response( json_decode( $response_body ), $response_code );
		}
	}

	/**
	 * Callback for healtcheck endpoint.
	 *
	 * @return string OK response message.
	 */
	public function healthcheck_request() {
		return 'OK';
	}

	/**
	 * Permissions required by user to execute the request. User permissions are already
	 * verified by nonce 'wp_rest' automatically.
	 *
	 * @return bool true if the user has adequate permissions for this proxy endpoint.
	 */
	public function verify_permissions() {
		return current_user_can( AdminFilters::apply_view_plugin_menu_capability() );
	}

	/**
	 * Make an API request to validate the HubSpot access token and return the scopes.
	 */
	public function oauth_token_request() {
		$token    = OAuth::get_access_token();
		$api_path = "/oauth/v1/access-tokens/$token";

		try {
			$request = HubSpotApiClient::authenticated_request( $api_path, 'GET' );
		} catch ( \Exception $e ) {
			return new \WP_REST_Response( json_decode( $e->getMessage() ), $e->getCode() );
		}

		$response_code = wp_remote_retrieve_response_code( $request );
		$response_body = \json_decode( wp_remote_retrieve_body( $request ) );
		$return_body   = array(
			'scopes' => $response_body->scopes,
		);

		return new \WP_REST_Response( $return_body, $response_code );
	}
}
