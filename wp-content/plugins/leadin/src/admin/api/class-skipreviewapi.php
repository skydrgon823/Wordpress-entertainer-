<?php

namespace Leadin\admin\api;

use Leadin\admin\AdminUserMetaData;

/**
 * Disconnect Api, used to clean portal id and domain from the WordPress options.
 */
class SkipReviewApi extends ApiGenerator {
	/**
	 * Skip Review API constructor. Adds the ajax hooks.
	 *
	 * @var String $endpoint API endpoint.
	 */
	public $endpoint = 'wp_ajax_leadin_skip_review';

	/**
	 * Skip Review. Sets SKIP_REVIEW meta data for a user with current datetime.
	 */
	public function run() {
		AdminUserMetaData::set_skip_review( time() );
	}
}
