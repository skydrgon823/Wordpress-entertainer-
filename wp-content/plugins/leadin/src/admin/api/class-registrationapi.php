<?php

namespace Leadin\admin\api;

use Leadin\admin\Connection;
use Leadin\utils\Validator;
use Leadin\utils\RequestUtils;

/**
 * Registration API, used to store the portal id and the domain as a WordPress option.
 */
class RegistrationApi extends ApiGenerator {
	/**
	 * Registration API constructor. Adds the ajax hooks.
	 *
	 * @var String $endpoint API endpoint.
	 */
	public $endpoint = 'wp_ajax_leadin_registration_ajax';

	/**
	 * Registration API runner. It validates the portal id and domain and stores them as WordPress options.
	 */
	public function run() {
		$request_body  = file_get_contents( 'php://input' );
		$data          = json_decode( $request_body, true );
		$portal_id     = intval( $data['portalId'] );
		$portal_domain = $data['domain'];
		$portal_name   = $data['accountName'];
		$hs_user_email = $data['userEmail'];
		$hublet        = $data['hublet'];

		if ( empty( $portal_id ) ) {
			RequestUtils::send_error_message( 'Registration missing required fields' );
		}

		Connection::connect( $portal_id, $portal_name, $portal_domain, $hs_user_email, $hublet );

		RequestUtils::send_message( 'Success' );
	}
}
