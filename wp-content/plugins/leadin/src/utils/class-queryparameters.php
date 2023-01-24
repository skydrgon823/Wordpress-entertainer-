<?php

namespace Leadin\utils;

/**
 * Class containing utility functions for the sanitizing and getting query parameters.
 */
class QueryParameters {

	/**
	 * Return a text sanitized, unslashed query parameter by its key, validated with a nonce.
	 *
	 * @param String $key Key for the query parameter to return.
	 * @param String $nonce_action  Name of the nonce action to verify the given nonce against.
	 * @param String $nonce_arg Query parmeter the nonce is in.
	 */
	public static function get_param( $key, $nonce_action, $nonce_arg = '_wpnonce' ) {
		if (
			isset( $_GET[ $key ] ) &&
			isset( $_GET[ $nonce_arg ] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( ( $_GET[ $nonce_arg ] ) ) ), $nonce_action )
		) {
			return sanitize_text_field( wp_unslash( ( $_GET[ $key ] ) ) );
		}

		return null;
	}

	/**
	 * Return an array sanitized, unslashed query parameter by its key, validated with a nonce.
	 *
	 * @param String $key Key for the query parameter to return.
	 * @param String $nonce_action  Name of the nonce action to verify the given nonce against.
	 * @param String $nonce_arg Query parmeter the nonce is in.
	 */
	public static function get_param_array( $key, $nonce_action, $nonce_arg = '_wpnonce' ) {
		if (
			isset( $_GET[ $key ] ) &&
			isset( $_GET[ $nonce_arg ] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( ( $_GET[ $nonce_arg ] ) ) ), $nonce_action )
		) {
			return array_map( 'sanitize_text_field', wp_unslash( $_GET[ $key ] ) );
		}

		return array();
	}

	/**
	 * Return an associative array of query param keys and values given an array of keys
	 * that are sanitized and validated against a nonce
	 *
	 * @param String $keys Array of keys to fetch query parameter values for.
	 * @param String $nonce_action  Name of the nonce action to verify the given nonce against.
	 * @param String $nonce_arg Query parmeter the nonce is in.
	 */
	public static function get_parameters( $keys, $nonce_action, $nonce_arg = '_wpnonce' ) {
		$query_params = array_reduce(
			$keys,
			function( $result, $key ) use ( $nonce_arg, $nonce_action ) {
				$query_param = QueryParameters::get_param( $key, $nonce_action, $nonce_arg );

				$result[ $key ] = $query_param;
				return $result;
			},
			array()
		);

		return $query_params;
	}
}

