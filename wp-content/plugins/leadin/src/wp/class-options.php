<?php

namespace Leadin\wp;

/**
 * Class that wraps calls to store and retrieve options.
 */
class Options {
	/**
	 * Class static declarations.
	 *
	 * @var String $prefix prefix added to option names.
	 */
	protected static $prefix = '';

	/**
	 * Prepend prefix to the given key.
	 *
	 * @param String $key string to which to prepend the prefix.
	 */
	private static function create_key( $key ) {
		$prefix = static::$prefix;
		return empty( $prefix ) ? $key : $prefix . '_' . $key;
	}

	/**
	 * Return the option.
	 *
	 * @param String $key option's key.
	 * @param Array  ...$args arguments to pass to get_option.
	 */
	public static function get( $key, ...$args ) {
		return get_option( static::create_key( $key ), ...$args );
	}

	/**
	 * Create an option if it does not exist.
	 *
	 * @param String $key option's key.
	 * @param Array  ...$args arguments to pass to get_option.
	 */
	public static function add( $key, ...$args ) {
		return add_option( static::create_key( $key ), ...$args );
	}

	/**
	 * Update an option. Create it if it does not exist.
	 *
	 * @param String $key option's key.
	 * @param Array  ...$args arguments to pass to get_option.
	 */
	public static function update( $key, ...$args ) {
		return update_option( static::create_key( $key ), ...$args );
	}

	/**
	 * Delete an option if it exists.
	 *
	 * @param String $key option's key.
	 * @param Array  ...$args arguments to pass to get_option.
	 */
	public static function delete( $key, ...$args ) {
		return delete_option( static::create_key( $key ), ...$args );
	}
}
