<?php
/**
 * Ajax class.
 *
 * @since 1.0.0
 *
 * @package OMAPI
 * @author  Thomas Griffin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajax class.
 *
 * @since 1.0.0
 */
class OMAPI_Ajax {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Set our object.
		$this->set();

		// Load non-WordPress style ajax requests.
		// phpcs:ignore Generic.Commenting.Todo.TaskFound
		// TODO move all of this to RestApi, and use rest api for these requests!
		if ( isset( $_REQUEST['optin-monster-ajax-route'] ) && $_REQUEST['optin-monster-ajax-route'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_REQUEST['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				add_action( 'init', array( $this, 'ajax' ), 999 );
			}
		}
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.0.0
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Callback to process external ajax requests.
	 *
	 * @since 1.0.0
	 */
	public function ajax() {

		switch ( $_REQUEST['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			case 'mailpoet':
				$this->base->mailpoet->handle_ajax_call();
				break;
			default:
				break;
		}
	}
}
