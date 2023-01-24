<?php

namespace Leadin\auth;

/**
 * Encrypting/decrypting OAuth credentials
 * Adapted from https://felix-arntz.me/blog/storing-confidential-data-in-wordpress/
 */
class OAuthCrypto {

	/**
	 * Return the key to use in encrypting/decrypting OAuth credentials
	 */
	private static function get_key() {
		if ( defined( 'LOGGED_IN_KEY' ) ) {
			return LOGGED_IN_KEY;
		}

		return '';
	}

	/**
	 * Return the salt to use in encrypting/decrypting OAuth credentials
	 */
	private static function get_salt() {
		if ( defined( 'LOGGED_IN_SALT' ) ) {
			return LOGGED_IN_SALT;
		}

		return '';
	}

	/**
	 * Given a value, encrypt it if the openssl extension is loaded and we have a valid key/salt
	 *
	 * @param string $value Value to encrypt.
	 *
	 * @return string Encrypted value
	 */
	public static function encrypt( $value ) {
		if ( ! extension_loaded( 'openssl' ) ||
		empty( self::get_key() ) ||
		empty( self::get_salt() ) ) {
			return $value;
		}

		$method             = 'aes-256-ctr';
		$init_vector_length = openssl_cipher_iv_length( $method );
		$init_vector        = openssl_random_pseudo_bytes( $init_vector_length );

		$raw_value = openssl_encrypt( $value . self::get_salt(), $method, self::get_key(), 0, $init_vector );
		if ( ! $raw_value ) {
			return false;
		}

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return base64_encode( $init_vector . $raw_value );
	}

	/**
	 * Decrpyt a given value
	 *
	 * @param string $value the encrypted value to decrypt.
	 *
	 * @return string The decrypted value
	 */
	public static function decrypt( $value ) {
		if ( ! extension_loaded( 'openssl' ) ||
		empty( self::get_key() ) ||
		empty( self::get_salt() ) ) {
			return $value;
		}

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		$raw_value = base64_decode( $value, true );

		$method             = 'aes-256-ctr';
		$init_vector_length = openssl_cipher_iv_length( $method );
		$init_vector        = substr( $raw_value, 0, $init_vector_length );

		$raw_value = substr( $raw_value, $init_vector_length );

		$value = openssl_decrypt( $raw_value, $method, self::get_key(), 0, $init_vector );
		if ( ! $value || substr( $value, - strlen( self::get_salt() ) ) !== self::get_salt() ) {
			return false;
		}

		return substr( $value, 0, - strlen( self::get_salt() ) );
	}
}
