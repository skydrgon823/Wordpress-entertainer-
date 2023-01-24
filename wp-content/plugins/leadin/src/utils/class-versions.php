<?php

namespace Leadin\utils;

/**
 * Static class containing all the utility functions related to versioning.
 */
class Versions {
	/**
	 * Return the given version until the patch version
	 * eg: 6.4.2.1-beta => 6.4.2
	 *
	 * @param String $version version.
	 */
	private static function parse_version( $version ) {
		preg_match( '/^\d+(\.\d+){0,2}/', $version, $match );
		if ( empty( $match ) ) {
			return '';
		}
		return $match[0];
	}

	/**
	 * Return the current WordPress version.
	 */
	public static function get_wp_version() {
		global $wp_version;
		return self::parse_version( $wp_version );
	}

	/**
	 * Return the current PHP version.
	 */
	public static function get_php_version() {
		return self::parse_version( phpversion() );
	}

	/**
	 * Return true if the current PHP version is supported.
	 */
	public static function is_php_version_supported() {
		return version_compare( phpversion(), LEADIN_REQUIRED_PHP_VERSION, '<' );
	}

	/**
	 * Return true if the current WordPress version is supported.
	 */
	public static function is_wp_version_supported() {
		global $wp_version;
		return version_compare( $wp_version, LEADIN_REQUIRED_WP_VERSION, '<' );
	}

	/**
	 * Return true if a given version is less than the supported version
	 *
	 * @param String $version Given version to check.
	 * @param String $version_to_compare The version number to test the given version against.
	 */
	public static function is_version_less_than( $version, $version_to_compare ) {
		return version_compare( $version, $version_to_compare, '<' );
	}
}
