<?php

namespace Buddy_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Base
 *
 * @package Buddy_Builder
 */
class Base extends Singleton {

	/**
	 * @var string
	 */
	protected $current_slug;

	/**
	 * @return string
	 */
	public function set_page_slug() {
		return $this->current_slug;
	}

	/**
	 * Get page slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return BPB_ADMIN_PREFIX . $this->current_slug;
	}

	/**
	 * @return array
	 */
	public function set_wrapper_classes() {
		return [ $this->current_slug ];
	}

}
