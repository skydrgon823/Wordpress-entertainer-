<?php

namespace Leadin\admin;

use Leadin\options\AccountOptions;
use Leadin\wp\User;
use Leadin\utils\QueryParameters;
use Leadin\auth\OAuth;

/**
 * Handles portal connection to the plugin.
 */
class Connection {

	const CONNECT_KEYS = array(
		'access_token',
		'refresh_token',
		'expires_in',
		'portal_id',
		'portal_domain',
		'portal_name',
		'hublet',
	);

	const CONNECT_NONCE_ARG    = 'leadin_connect';
	const DISCONNECT_NONCE_ARG = 'leadin_disconnect';

	/**
	 * Returns true if a portal has been connected to the plugin
	 */
	public static function is_connected() {
		return ! empty( AccountOptions::get_portal_id() );
	}

	/**
	 * Returns true if existing portal is the same into a connect attempt
	 */
	public static function is_same_portal() {
		$connect_params = QueryParameters::get_parameters( self::CONNECT_KEYS, 'hubspot-nonce', self::CONNECT_NONCE_ARG );
		$portal_id      = $connect_params['portal_id'];
		return AccountOptions::get_portal_id() === $portal_id;
	}

	/**
	 * Returns true if the current request is for the plugin to connect to a portal
	 */
	public static function is_connection_requested() {
		$maybe_leadin_connect = QueryParameters::get_param( self::CONNECT_NONCE_ARG, 'hubspot-nonce', self::CONNECT_NONCE_ARG );
		$maybe_access_token   = QueryParameters::get_param( 'access_token', 'hubspot-nonce', self::CONNECT_NONCE_ARG );

		return isset( $maybe_leadin_connect ) && isset( $maybe_access_token );
	}

	/**
	 * Returns true if the current request is for the plugin to connect to a portal
	 */
	public static function is_new_portal() {
		$maybe_is_new_portal = QueryParameters::get_param( 'is_new_portal', 'hubspot-nonce', self::CONNECT_NONCE_ARG );

		return isset( $maybe_is_new_portal );
	}

	/**
	 * Returns true if the current request is to disconnect the plugin from the portal
	 */
	public static function is_disconnection_requested() {
		$maybe_leadin_disconnect = QueryParameters::get_param( self::DISCONNECT_NONCE_ARG, 'hubspot-nonce', self::DISCONNECT_NONCE_ARG );
		return isset( $maybe_leadin_disconnect );
	}
	/**
	 * Retrieves user ID and create new metadata
	 *
	 * @param Array $user_meta array of pairs metadata - value.
	 */
	private static function add_metadata( $user_meta ) {
		$wp_user    = wp_get_current_user();
		$wp_user_id = $wp_user->ID;
		foreach ( $user_meta as $key => $value ) {
			add_user_meta( $wp_user_id, $key, $value );
		}
	}

	/**
	 * Retrieves user ID and deletes a piece of the users meta data.
	 *
	 * @param String $meta_key is the key of the data you want to delete.
	 */
	private static function delete_metadata( $meta_key ) {
		$wp_user    = wp_get_current_user();
		$wp_user_id = $wp_user->ID;
		delete_user_meta( $wp_user_id, $meta_key );
	}

	/**
	 * Connect portal id, domain, name to WordPress options and HubSpot email to user meta data.
	 *
	 * @param Number $portal_id     HubSpot account id.
	 * @param String $portal_name   HubSpot account name.
	 * @param String $portal_domain HubSpot account domain.
	 * @param String $hs_user_email HubSpot user email.
	 * @param String $hublet        HubSpot account's hublet.
	 */
	public static function connect( $portal_id, $portal_name, $portal_domain, $hs_user_email, $hublet ) {
		self::disconnect();
		self::store_portal_info( $portal_id, $portal_name, $portal_domain, $hublet );
		self::add_metadata( array( 'leadin_email' => $hs_user_email ) );
	}

	/**
	 * Connect the plugin with OAuthorization. Storing OAuth tokens and metadata for the connected portal.
	 */
	public static function oauth_connect() {
		$connect_params = QueryParameters::get_parameters( self::CONNECT_KEYS, 'hubspot-nonce', self::CONNECT_NONCE_ARG );

		self::disconnect();
		self::store_portal_info(
			$connect_params['portal_id'],
			$connect_params['portal_name'],
			$connect_params['portal_domain'],
			$connect_params['hublet']
		);

		OAuth::authorize( $connect_params['access_token'], $connect_params['refresh_token'], $connect_params['expires_in'] );
	}

	/**
	 * Removes portal id and domain from the WordPress options.
	 */
	public static function disconnect() {
		self::delete_portal_info();

		$users = get_users( array( 'fields' => array( 'ID' ) ) );
		foreach ( $users as $user ) {
			delete_user_meta( $user->ID, 'leadin_email' );
			delete_user_meta( $user->ID, 'leadin_skip_review' );
			delete_user_meta( $user->ID, 'leadin_review_banner_last_call' );
			delete_user_meta( $user->ID, 'leadin_has_min_contacts' );
			delete_user_meta( $user->ID, 'leadin_track_consent' );
		}

		OAuth::deauthorize();
	}

	/**
	 * Store the portal metadata for connecting the plugin in the options table
	 *
	 * @param String $portal_id ID for connecting portal.
	 * @param String $portal_name Name of the connecting portal.
	 * @param String $portal_domain Domain for the connecting portal.
	 * @param String $hublet Hublet for the connecting portal.
	 */
	public static function store_portal_info( $portal_id, $portal_name, $portal_domain, $hublet ) {
		AccountOptions::add_portal_id( $portal_id );
		AccountOptions::add_account_name( $portal_name );
		AccountOptions::add_portal_domain( $portal_domain );
		AccountOptions::add_hublet( $hublet );
		AccountOptions::add_disable_internal_tracking();
	}

	/**
	 * Delete stored portal metadata for disconnecting the plugin from the options table
	 */
	private static function delete_portal_info() {
		AccountOptions::delete_portal_id();
		AccountOptions::delete_account_name();
		AccountOptions::delete_portal_domain();
		AccountOptions::delete_hublet();
		AccountOptions::delete_disable_internal_tracking();
	}
}
