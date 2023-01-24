<?php
/**
 * Utils class.
 *
 * @since 1.3.6
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Utils class.
 *
 * @since 1.3.6
 */
class OMAPI_Utils {

	/**
	 * Determines if given type is an inline type.
	 *
	 * @since  1.3.6
	 *
	 * @param  string $type Type to check
	 *
	 * @return boolean
	 */
	public static function is_inline_type( $type ) {
		return 'post' === $type || 'inline' === $type;
	}

	public static function item_in_field( $item, $fields, $field ) {
		return $item
			&& is_array( $fields )
			&& ! empty( $fields[ $field ] )
			&& in_array( $item, (array) $fields[ $field ] );
	}

	public static function field_not_empty_array( $fields, $field ) {
		if ( empty( $fields[ $field ] ) ) {
			return false;
		}

		$values = array_values( (array) $fields[ $field ] );
		$values = array_filter( $values );

		return ! empty( $values ) ? $values : false;
	}

	/**
	 * WordPress utility functions.
	 */

	public static function is_front_or_search() {
		return is_front_page() || is_home() || is_search();
	}

	public static function is_term_archive( $term_id, $taxonomy ) {
		if ( ! $term_id ) {
			return false;
		}
		return 'post_tag' === $taxonomy && is_tag( $term_id ) || is_tax( $taxonomy, $term_id );
	}

	/**
	 * Determines if AMP is enabled on the current request.
	 *
	 * @since 1.9.8
	 *
	 * @return bool True if AMP is enabled, false otherwise.
	 */
	public static function is_amp_enabled() {
		return ( function_exists( 'amp_is_request' ) && amp_is_request() )
			|| ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() );
	}

	/**
	 * Ensures a unique array.
	 *
	 * @since  1.9.10
	 *
	 * @param  array $val Array to clean.
	 *
	 * @return array       Cleaned array.
	 */
	public static function unique_array( $val ) {
		if ( empty( $val ) ) {
			return array();
		}

		$val = array_filter( $val );

		return array_unique( $val );
	}

	/**
	 * A back-compatible parse_url helper.
	 *
	 * @since 2.3.0
	 *
	 * @param  string $url URL to parse.
	 *
	 * @return array       The URL parts.
	 */
	public static function parse_url( $url ) {
		// NOTE: Error suppression is used as prior to PHP 5.3.3, an
		// E_WARNING would be generated when URL parsing failed.
		return function_exists( 'wp_parse_url' )
			? wp_parse_url( $url )
			: parse_url( $url ); // phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
	}

	/**
	 * Build Inline Data
	 *
	 * @since 2.3.0
	 *
	 * @param string $object_name Name for the JavaScript object. Passed directly, so it should be qualified JS variable.
	 * @param string $data        String containing the javascript to be added.
	 *
	 * @return string The formatted script string.
	 */
	public static function build_inline_data( $object_name, $data ) {
		return sprintf( 'var %s = %s;', $object_name, json_encode( $data ) );
	}

	/**
	 * Add Inline Script
	 *
	 * @since 2.3.0
	 *
	 * @see WP_Scripts::add_inline_script()
	 *
	 * @param string $handle      Name of the script to add the inline script to.
	 * @param string $object_name Name for the JavaScript object. Passed directly, so it should be qualified JS variable.
	 * @param string $data        String containing the javascript to be added.
	 * @param string $position    Optional. Whether to add the inline script before the handle
	 *                            or after. Default 'after'.
	 *
	 * @return bool True on success, false on failure.
	 */
	public static function add_inline_script( $handle, $object_name, $data, $position = 'before' ) {
		$payload = self::build_inline_data( $object_name, $data );

		wp_add_inline_script( $handle, $payload, $position );
	}
}
