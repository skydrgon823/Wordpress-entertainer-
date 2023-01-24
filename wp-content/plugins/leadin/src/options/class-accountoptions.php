<?php

namespace Leadin\options;

use Leadin\options\LeadinOptions;

/**
 * Class that wraps the functions to access options related to the HubSpot account.
 */
class AccountOptions extends LeadinOptions {
	const PORTAL_ID                 = 'portalId';
	const PORTAL_DOMAIN             = 'portal_domain';
	const ACCOUNT_NAME              = 'account_name';
	const HUBLET                    = 'hublet';
	const DISABLE_INTERNAL_TRACKING = 'disable_internal_tracking';

	/**
	 * Return portal id.
	 */
	public static function get_portal_id() {
		return self::get( self::PORTAL_ID );
	}

	/**
	 * Return portal's domain.
	 */
	public static function get_portal_domain() {
		return self::get( self::PORTAL_DOMAIN );
	}

	/**
	 * Return account name.
	 */
	public static function get_account_name() {
		return self::get( self::ACCOUNT_NAME );
	}

	/**
	 * Return option containing hublet info.
	 */
	public static function get_hublet() {
		return self::get( self::HUBLET );
	}

	/**
	 * Set portal id.
	 *
	 * @param Number $portal_id HubSpot portal id.
	 */
	public static function add_portal_id( $portal_id ) {
		return self::add( self::PORTAL_ID, $portal_id );
	}

	/**
	 * Set portal domain.
	 *
	 * @param String $domain domain.
	 */
	public static function add_portal_domain( $domain ) {
		return self::add( self::PORTAL_DOMAIN, $domain );
	}

	/**
	 * Set account name.
	 *
	 * @param String $name name.
	 */
	public static function add_account_name( $name ) {
		return self::add( self::ACCOUNT_NAME, $name );
	}

	/**
	 * Return option containing hublet info.
	 *
	 * @param String $hublet hublet.
	 */
	public static function add_hublet( $hublet ) {
		return self::add( self::HUBLET, $hublet );
	}

	/**
	 * Update the hublet
	 *
	 * @param String $hublet hublet.
	 */
	public static function update_hublet( $hublet ) {
		return self::update( self::HUBLET, $hublet );
	}

	/**
	 * Delete portal id.
	 */
	public static function delete_portal_id() {
		return self::delete( self::PORTAL_ID );
	}

	/**
	 * Delete portal domain.
	 */
	public static function delete_portal_domain() {
		return self::delete( self::PORTAL_DOMAIN );
	}

	/**
	 * Delete account name.
	 */
	public static function delete_account_name() {
		return self::delete( self::ACCOUNT_NAME );
	}

	/**
	 * Delete hublet
	 */
	public static function delete_hublet() {
		return self::delete( self::HUBLET );
	}

	/**
	 * Return option flag for disabling internal users to appear at HS analytics.
	 */
	public static function get_disable_internal_tracking() {
		return self::get( self::DISABLE_INTERNAL_TRACKING );
	}

	/**
	 * Set option containing flag for disabling internal users to appear at HS analytics.
	 */
	public static function add_disable_internal_tracking() {
		return self::add( self::DISABLE_INTERNAL_TRACKING, 0 );
	}

	/**
	 * Delete option flag for disabling internal tracking
	 */
	public static function delete_disable_internal_tracking() {
		return self::delete( self::DISABLE_INTERNAL_TRACKING );
	}

	/**
	 * Update option flag for disabling internal tracking
	 *
	 * @param int $value boolean flag.
	 */
	public static function update_disable_internal_tracking( $value ) {
		return self::update( self::DISABLE_INTERNAL_TRACKING, $value );
	}

}
