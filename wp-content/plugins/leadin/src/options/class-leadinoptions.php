<?php

namespace Leadin\options;

use Leadin\wp\Options;

/**
 * Class that wraps calls to store and retrieve options prefixed by "leadin_".
 */
class LeadinOptions extends Options {
	/**
	 * Class static declarations.
	 *
	 * @var String $prefix prefix added to option names.
	 */
	protected static $prefix = 'leadin';
}
