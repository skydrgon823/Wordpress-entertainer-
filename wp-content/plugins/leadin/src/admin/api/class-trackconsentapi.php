<?php

namespace Leadin\admin\api;

use Leadin\admin\AdminUserMetaData;
use Leadin\utils\RequestUtils;

/**
 * Track Consent API, used to store user consent on HubSpot anonymous tracking.
 */
class TrackConsentApi extends ApiGenerator {
	/**
	 * Track Consent API constructor. Adds the ajax hooks.
	 *
	 * @var String $endpoint API endpoint.
	 */
	public $endpoint = 'wp_ajax_leadin_track_consent';

	/**
	 * Track Consent API runner. It stores the value at User Metadata.
	 */
	public function run() {
		$request_body = file_get_contents( 'php://input' );
		$data         = json_decode( $request_body, true );
		if ( ! empty( $data['canTrack'] ) ) {
			$consent = $data['canTrack'];
			AdminUserMetaData::set_track_consent( $consent );
		}

		RequestUtils::send_message( AdminUserMetaData::get_track_consent() );
	}
}
