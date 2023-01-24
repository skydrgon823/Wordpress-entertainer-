<?php

namespace Leadin\admin\api;

/**
 * Common utils for the API creation
 */
class ApiGenerator {

	/**
	 * Adds actions needed for a new API to reduce boilerplate
	 */
	public function __construct() {
		add_action( $this->endpoint, 'Leadin\admin\api\ApiMiddlewares::validate_nonce', 1 );
		add_action( $this->endpoint, 'Leadin\admin\api\ApiMiddlewares::admin_only', 2 );
		add_action( $this->endpoint, array( $this, 'run' ) );
	}
}
