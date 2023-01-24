<?php

namespace Leadin\utils;

/**
 * Static class containing methods used on ajax requests handling.
 */
class RequestUtils {
	/**
	 * Send JSON response.
	 *
	 * @param Mixed  $body response to send as JSON.
	 * @param Number $code http code to return.
	 */
	private static function send( $body, $code ) {
		wp_send_json( $body, intval( $code ) );
	}

	/**
	 * Send JSON response with 200 code.
	 *
	 * @param Mixed $body response to send as JSON.
	 */
	public static function send_json( $body = array() ) {
		self::send( $body, 200 );
	}

	/**
	 * Send error response with message
	 *
	 * @param String $error Message to be sent on te JSON.
	 * @param Number $code  Error code to be sent in the error. Default 400.
	 */
	public static function send_error_message( $error, $code = 400 ) {
		self::send(
			array(
				'error' => $error,
			),
			intval( $code )
		);
	}

	/**
	 * Send JSON response with a message key.
	 *
	 * @param String $message Message to be sent on te JSON.
	 */
	public static function send_message( $message ) {
		self::send(
			array(
				'message' => $message,
			),
			200
		);
	}
}
