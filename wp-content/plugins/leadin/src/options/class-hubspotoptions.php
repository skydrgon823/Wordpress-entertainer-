<?php

namespace Leadin\options;

use Leadin\wp\Options;

/**
 * Class that wraps the functions to access generic options.
 */
class HubspotOptions extends Options {
	const WPE_TEMPLATE = 'wpe_template';

	/**
	 * Return option containing WPEngine templates.
	 */
	public static function get_wpe_template() {
		return self::get( self::WPE_TEMPLATE );
	}
}
