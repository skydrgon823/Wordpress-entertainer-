<?php

namespace Leadin\admin\utils;

use Leadin\wp\Website;
use Leadin\wp\User;

/**
 * Class containing utility functions for the device id.
 */
class DeviceId {
	/**
	 * Return device id, composed by "$site_url:$user_id"
	 */
	public static function get() {
		$site_url = Website::get_url();
		$user_id  = get_current_user_id();
		return hash( 'sha256', "$site_url:$user_id" );
	}
}
