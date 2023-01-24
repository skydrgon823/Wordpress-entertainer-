<?php
/**
 * Review class.
 *
 * @since 1.1.4.5
 *
 * @package OMAPI
 * @author  Devin Vinson
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Review class.
 *
 * @since 1.1.4.5
 */
class OMAPI_Review {

	/**
	 * Holds the class object.
	 *
	 * @since 1.1.4.5
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.1.4.5
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.1.4.5
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.1.4.5
	 */
	public function __construct() {
		// If we are not in admin or admin ajax, return.
		if ( ! is_admin() ) {
			return;
		}

		// If user is not logged in, return.
		if ( ! is_user_logged_in() ) {
			return;
		}

		// If user cannot manage_options, return.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Set our object.
		$this->set();

		add_action( 'wp_ajax_omapi_dismiss_review', array( $this, 'dismiss_review' ) );

		// If user is in admin ajax or doing cron, return.
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			return;
		}

		// Review Notices
		add_action( 'admin_notices', array( $this, 'review' ) );
	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.1.4.5
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Add admin notices as needed for reviews.
	 *
	 * @since 1.1.6.1
	 */
	public function review() {
		$review = get_option( 'omapi_review' );

		// If already dismissed...
		if ( ! empty( $review['dismissed'] ) ) {

			if ( empty( $review['later'] ) ) {

				// Dismissed and no later, so do not show.
				return;
			}

			$delayed_less_than_month_ago = ! empty( $review['later'] ) && $review['time'] + ( 30 * DAY_IN_SECONDS ) > time();
			if ( $delayed_less_than_month_ago ) {

				// Delayed less than a month ago, so do not show.
				return;
			}
		}

		// Check the installation time and find if it's ok to show the review notice.
		$option = $this->base->get_option();

		$installed_less_than_week_ago = $option['installed'] + ( 7 * DAY_IN_SECONDS ) > time();

		if ( $installed_less_than_week_ago ) {

			// Do not show the review if the plugin was installed less than 1 week ago.
			return;
		}

		// We have a candidate! Output a review message.

		wp_enqueue_script( $this->base->plugin_slug . '-notice', plugins_url( 'assets/js/notice.js', OMAPI_FILE ), array( 'jquery' ), $this->base->version, true );
		OMAPI_Utils::add_inline_script(
			$this->base->plugin_slug . '-notice',
			'omNotice',
			array(
				'nonce' => wp_create_nonce( 'om-review-nonce' ),
			)
		);

		$this->base->output_view( 'review.php' );
	}

	/**
	 * Dismiss the review nag
	 *
	 * @since 1.1.6.1
	 */
	public function dismiss_review() {

		// Checking ajax nonce.
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'om-review-nonce' ) ) {
			wp_send_json_error();
		}

		$option = array(
			'time'      => time(),
			'dismissed' => true,
			'later'     => ! empty( $_POST['later'] ) && wp_validate_boolean( $_POST['later'] ),
		);

		$option['updated'] = update_option( 'omapi_review', $option );

		wp_send_json_success( $option );
	}
}
