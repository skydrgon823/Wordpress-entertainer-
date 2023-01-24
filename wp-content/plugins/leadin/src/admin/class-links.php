<?php

namespace Leadin\admin;

use Leadin\LeadinFilters;
use Leadin\options\AccountOptions;
use Leadin\admin\MenuConstants;
use Leadin\admin\utils\Background;
use Leadin\wp\User;
use Leadin\utils\QueryParameters;
use Leadin\utils\Versions;
use Leadin\auth\OAuth;
use Leadin\admin\Connection;
use Leadin\admin\Impact;
use Leadin\admin\AdminConstants;
use Leadin\includes\utils as utils;

/**
 * Class containing all the functions to generate links to HubSpot.
 */
class Links {
	/**
	 * Deprecated for OAuth2 routes
	 *
	 * Get a map of <admin_page, url>
	 * Where
	 * - admin_page is a string
	 * - url is either a string or another map <route, string_url>, both strings
	 */
	public static function get_routes_mapping() {
		$portal_id      = AccountOptions::get_portal_id();
		$reporting_page = "/wordpress-plugin-ui/$portal_id/reporting";
		$user_guide     = "/wordpress-plugin-ui/$portal_id/onboarding/start";

		return array(
			MenuConstants::ROOT       => $user_guide,
			MenuConstants::USER_GUIDE => $user_guide,
			MenuConstants::REPORTING  => $reporting_page,
			MenuConstants::CHATFLOWS  => array(
				''         => "/chatflows/$portal_id",
				'settings' => "/live-messages-settings/$portal_id",
			),
			MenuConstants::CONTACTS   => "/contacts/$portal_id",
			MenuConstants::LISTS      => array(
				''        => "/contacts/$portal_id/objectLists/all",
				'default' => "/contacts/$portal_id/objectLists",
				'lists'   => "/contacts/$portal_id/lists",
			),
			MenuConstants::FORMS      => "/forms/$portal_id",
			MenuConstants::EMAIL      => array(
				''    => "/email/$portal_id",
				'cms' => "/content/$portal_id/create/email",
			),
			MenuConstants::SETTINGS   => array(
				''      => "/wordpress-plugin-ui/$portal_id/settings",
				'forms' => "/settings/$portal_id/marketing/form",
			),
			MenuConstants::PRICING    => "/pricing/$portal_id/marketing",
		);
	}

	/**
	 * Get page name from the current page id.
	 * E.g. "hubspot_page_leadin_forms" => "forms"
	 */
	private static function get_page_id() {
		$screen_id = get_current_screen()->id;
		return preg_replace( '/^(hubspot_page_|toplevel_page_)/', '', $screen_id );
	}

	/**
	 * Get the parsed `leadin_route` from the query string.
	 */
	private static function get_iframe_route() {
		$iframe_route = QueryParameters::get_param_array( 'leadin_route', 'hubspot-route' );
		return is_array( $iframe_route ) ? $iframe_route : array();
	}

	/**
	 * Get the parsed `leadin_search` from the query string.
	 */
	private static function get_iframe_search_string() {
		return QueryParameters::get_param( 'leadin_search', 'hubspot-route' );
	}

	/**
	 * Return query string from object
	 *
	 * @param array $arr query parameters to stringify.
	 */
	private static function http_build_query( $arr ) {
		if ( ! is_array( $arr ) ) {
			return '';
		}
		return http_build_query( $arr, null, ini_get( 'arg_separator.output' ), PHP_QUERY_RFC3986 );
	}

	/**
	 * Validate static version.
	 *
	 * @param String $version version of the static bundle.
	 */
	private static function is_static_version_valid( $version ) {
		preg_match( '/static-\d+\.\d+/', $version, $match );
		return ! empty( $match );
	}

	/**
	 * Return a string query parameters to add to the iframe src.
	 */
	public static function get_query_params() {
		$config_array = AdminConstants::get_hubspot_query_params_array();
		\array_merge( $config_array, Impact::get_params() );

		return self::http_build_query( $config_array );
	}

	/**
	 * Get background iframe src.
	 */
	public static function get_background_iframe_src() {
		$portal_id     = AccountOptions::get_portal_id();
		$portal_id_url = '';

		if ( Connection::is_connected() ) {
			$portal_id_url = "/$portal_id";
		}

		$query = '';

		return LeadinFilters::get_leadin_base_url() . "/wordpress-plugin-ui$portal_id_url/background?$query" . self::get_query_params();
	}

	/**
	 * Return login link to redirect to when the user isn't authenticated in HubSpot
	 */
	public static function get_login_url() {
		$portal_id = AccountOptions::get_portal_id();
		return LeadinFilters::get_leadin_base_url() . "/wordpress-plugin-ui/$portal_id/login?" . self::get_query_params();
	}

	/**
	 * Returns the url for the connection page
	 */
	private static function get_connection_src() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$portal_id = Connection::is_connected() ? AccountOptions::get_portal_id() : ( isset( $_GET['leadin_connect'] ) ? filter_var( wp_unslash( $_GET['leadin_connect'] ), FILTER_VALIDATE_INT ) : 0 );
		return LeadinFilters::get_leadin_base_url() . "/wordpress-plugin-ui/onboarding/connect?portalId=$portal_id&" . self::get_query_params();
	}

	/**
	 * This function computes the right iframe src based on query params and current page.
	 *
	 * The `page` query param is used as a key to get the url from the get_routes_mapping
	 * This query param will get ignored if $page_id function param is present
	 * The `leadin_route[]` query params are added to the url
	 *
	 * e.g.:
	 * ?page=leadin_forms&leadin_route[]=foo&leadin_route[]=bar will redirect to /forms/$portal_id/foo/bar
	 *
	 * If the value of get_routes_mapping is an array, the first value of `leadin_route` will be used as key.
	 * If the key isn't found, it will fall back to ''
	 *
	 * e.g.:
	 * ?page=leadin_settings&leadin=route[]=forms&leadin_route[]=bar will redirect to /settings/$portal_id/forms/bar
	 * ?page=leadin_settings&leadin=route[]=foo&leadin_route[]=bar will redirect to /wordpress_plugin_ui/$portal_id/settings/foo/bar
	 *
	 * @param String $page_id is used as a key to get the url from the get_routes_mapping by overriding the query param.
	 *
	 * Returns the right iframe src.
	 */
	public static function get_iframe_src( $page_id = '' ) {
		$leadin_onboarding     = 'leadin_onboarding';
		$leadin_new_portal     = 'leadin_new_portal';
		$browser_search_string = '';

		// Old Connection flow.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['leadin_connect'] ) ) {
			$extra = '';
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['is_new_portal'] ) ) {
				$extra = '&isNewPortal=true';
				set_transient( $leadin_new_portal, 'true' );
			}
			return self::get_connection_src() . $extra;
		}

		if ( get_transient( $leadin_onboarding ) ) {
			delete_transient( $leadin_onboarding );
			$browser_search_string = '&justConnected=true';
			if ( get_transient( $leadin_new_portal ) ) {
				delete_transient( $leadin_new_portal );
				$browser_search_string = $browser_search_string . '&isNewPortal=true';
			}
		}

		$sub_routes_array      = self::get_iframe_route();
		$inframe_search_string = self::get_iframe_search_string();
		$browser_search_string = $browser_search_string . $inframe_search_string;

		if ( empty( AccountOptions::get_portal_id() ) ) {
			$wp_user    = wp_get_current_user();
			$wp_user_id = $wp_user->ID;
			set_transient( $leadin_onboarding, 'true' );
			$route = '/wordpress-plugin-ui/onboarding';
		} else {
			if ( '' === $page_id ) {
				$page_id = self::get_page_id();
			}

			$routes = self::get_routes_mapping();

			$route = IframeRoutes::get_oauth_path( MenuConstants::PRICING === $page_id );
			if ( empty( $route ) && isset( $routes[ $page_id ] ) ) {
				$route = $routes[ $page_id ];

				if ( \is_array( $route ) && isset( $sub_routes_array[0] ) ) {
					$first_sub_route = $sub_routes_array[0];

					if ( isset( $route[ $first_sub_route ] ) ) {
						$route = $route[ $first_sub_route ];
						array_shift( $sub_routes_array );
					}
				}

				if ( \is_array( $route ) ) {
					$route = $route[''];
				}
			}
		}

		$sub_routes = join( '/', $sub_routes_array );
		$sub_routes = empty( $sub_routes ) ? $sub_routes : "/$sub_routes";
		// Query string separator "?" may have been added to the URL already.
		$add_separator           = strpos( $sub_routes, '?' ) ? '&' : '?';
		$additional_query_params = apply_filters( 'leadin_query_params', '' );

		return LeadinFilters::get_leadin_base_url() . "$route$sub_routes" . $add_separator . self::get_query_params() . $browser_search_string . $additional_query_params;
	}
}
