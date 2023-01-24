<?php
/**
 * Add Template Library Module.
 *
 * @package Buddy_Builder
 * @since 1.0.0
 */

namespace Buddy_Builder\Template;

use Buddy_Builder\Library\Documents\BuddyPress;
use Buddy_Builder\Templates;
use Elementor\Core\Base\Document;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

/**
 * Buddy_Builder template library module.
 *
 * Buddy_Builder template library module handler class is responsible for registering and fetching
 * Buddy_Builder templates.
 *
 * @since 1.0.0
 */
class Module {

	const IMPORT_KEY         = 'bpb_import';
	const IMPORT_NONCE_KEY   = 'bpb-ajax-import';
	const TEMPLATE_ID_PREFIX = 'buddy_builder_';

	/**
	 * API URL.
	 *
	 * Holds the URL of the API.
	 *
	 * @access private
	 * @static
	 *
	 * @var string API URL.
	 */
	private static $api_url = 'https://demo.staxwp.com/elementor-buddybuilder/wp-json/stax-feed/v1/templates/%s';

	/**
	 * Initialize Buddy_Builder template library module.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Add buddy_builder categories to the list.
		if ( defined( '\Elementor\Api::LIBRARY_OPTION_KEY' ) ) {
			// Sort before jet elements prepend its categories.
			add_filter( 'option_' . \Elementor\Api::LIBRARY_OPTION_KEY, [ $this, 'add_categories' ], 5 );
		}

		// Register Buddy_Builder source.
		Plugin::instance()->templates_manager->register_source( __NAMESPACE__ . '\Source_Buddy_Builder' );

		// Register proper AJAX actions for Buddy_Builder templates.
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ], 20 );

		add_action( 'admin_init', [ $this, 'import_starter_templates' ] );

		// On import save subtype
		add_action( 'elementor/template-library/after_save_template', [ $this, 'save_subtype_on_import' ], 10, 2 );
	}

	public function save_subtype_on_import( $template_id, $data ) {

		if ( isset( $data['page_settings']['bpb_type'] ) &&
			 ! get_post_meta( $template_id, BuddyPress::REMOTE_CATEGORY_META_KEY, true ) &&
			 get_post_meta( $template_id, Document::TYPE_META_KEY, true ) === 'bpb-buddypress' ) {
			update_post_meta( $template_id, BuddyPress::REMOTE_CATEGORY_META_KEY, $data['page_settings']['bpb_type'] );
		}

	}

	/**
	 * Initial starter templates import and assign
	 */
	public function import_starter_templates() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! isset( $_REQUEST['action'], $_REQUEST['bpb_template'] ) || $_REQUEST['action'] !== self::IMPORT_KEY ) {
			return;
		}

		if ( ! $this->verify_request_nonce() ) {
			$this->send_error();

			return;
		}

		$bpb_template = sanitize_text_field( $_REQUEST['bpb_template'] );

		$templates = self::get_templates( sanitize_text_field( $bpb_template ) );

		if ( is_wp_error( $templates ) ) {
			$this->send_error();
		}

		$imported_ids = [];

		if ( ! empty( $templates ) ) {

			foreach ( $templates as $template ) {

				$is_group_tpl = isset( $template['subtype'] ) && strpos( $template['subtype'], 'group' ) !== false;

				if ( $is_group_tpl && ! bp_is_active( 'groups' ) ) {
					continue;
				}

				if ( empty( $template ) ) {
					$this->send_error();
				}

				$template_id = self::import_template_data( [ 'template_id' => self::TEMPLATE_ID_PREFIX . $template['id'] ] );

				if ( is_wp_error( $template_id ) ) {
					$this->send_error();
				} else {
					$imported_ids[] = $template_id;
				}
			}

			if ( ! empty( Source_Buddy_Builder::$imported_templates ) ) {
				bpb_bulk_save_settings( Source_Buddy_Builder::$imported_templates );
			}
		}

		$imported_templates                  = get_option( 'bpb_imported_templates', [] );
		$imported_templates[ $bpb_template ] = $imported_ids;

		update_option( 'bpb_imported_templates', $imported_templates );

		$this->send_success();

	}

	/**
	 * Import failed
	 */
	private function send_error() {
		wp_redirect( add_query_arg( 'bpb_import_status', 'error', admin_url( 'admin.php?page=' . Templates::get_instance()->get_slug() ) ) );
		exit;

	}

	/**
	 * Import success
	 */
	private function send_success() {
		wp_redirect( add_query_arg( 'bpb_import_status', 'success', admin_url( 'admin.php?page=' . Templates::get_instance()->get_slug() ) ) );
		exit;
	}

	/**
	 * Import template data.
	 *
	 * @param array $args Request arguments.
	 *
	 * @return array Template data.
	 * @since 1.0.0
	 */
	public static function import_template_data( $args ) {
		$source = Plugin::instance()->templates_manager->get_source( 'buddy_builder' );

		$args['template_id'] = (int) str_replace( self::TEMPLATE_ID_PREFIX, '', $args['template_id'] );

		$data = $source->get_data( $args );

		if ( is_wp_error( $data ) ) {
			return false;
		}

		return $source->import_item(
			[
				'content'       => $data['content'],
				'title'         => $data['title'],
				'type'          => 'bpb-buddypress',
				'subtype'       => $data['subtype'],
				'page_settings' => [],
			]
		);
	}

	/**
	 * Verify request nonce.
	 *
	 * Whether the request nonce verified or not.
	 *
	 * @return bool True if request nonce verified, False otherwise.
	 * @since 2.3.0
	 * @access public
	 */
	public function verify_request_nonce() {
		return ! empty( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], self::IMPORT_NONCE_KEY );
	}


	/**
	 * Fetch templates from server.
	 *
	 * @param string $category
	 *
	 * @return array|\WP_Error.
	 * @since 1.0.0
	 * @access public
	 */
	public static function get_templates( $category = '' ) {

		$url = sprintf( self::$api_url, $category );

		$response = wp_remote_get(
			$url,
			[
				'timeout' => 40,
			]
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return new \WP_Error( 'response_code_error', sprintf( 'The request returned with a status code of %s.', $response_code ) );
		}

		$template_content = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $template_content['error'] ) ) {
			return new \WP_Error( 'response_error', $template_content['error'] );
		}

		if ( empty( $template_content ) ) {
			return new \WP_Error( 'template_data_error', 'An invalid data was returned.' );
		}

		return $template_content;
	}

	/**
	 * Get template data.
	 *
	 * @param array $args Request arguments.
	 *
	 * @return array Template data.
	 * @since 1.0.0
	 */
	public static function get_template_data( $args ) {
		$source = Plugin::instance()->templates_manager->get_source( 'buddy_builder' );

		$args['template_id'] = (int) str_replace( self::TEMPLATE_ID_PREFIX, '', $args['template_id'] );

		return $source->get_data( $args );
	}

	/**
	 * Fetch template content from server.
	 *
	 * @param array $template_id Template ID.
	 *
	 * @return \WP_Error|array
	 * @since 1.0.0
	 * @access public
	 */
	public static function get_template_content( $template_id ) {
		$url = sprintf( self::$api_url, 'id/' . $template_id );

		$response = wp_remote_get(
			$url,
			[
				'timeout' => 15,
			]
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return new \WP_Error( 'response_code_error', sprintf( 'The request returned with a status code of %s.', $response_code ) );
		}

		$template_content = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $template_content['error'] ) ) {
			return new \WP_Error( 'response_error', $template_content['error'] );
		}

		if ( empty( $template_content['content'] ) ) {
			return new \WP_Error( 'template_data_error', 'An invalid data was returned.' );
		}

		return $template_content;
	}


	/**
	 * Override registered Elementor native actions.
	 *
	 * @param array $ajax AJAX manager.
	 *
	 * @since 1.0.0
	 */
	public function register_ajax_actions( $ajax ) {
		if ( ! isset( $_REQUEST['actions'] ) ) {
			return;
		}

		$actions = json_decode( stripslashes( $_REQUEST['actions'] ), true );

		$data = false;

		foreach ( $actions as $action_data ) {
			if ( ! isset( $action_data['get_template_data'] ) ) {
				$data = $action_data;
			}
		}

		if ( ! $data ) {
			return;
		}

		if ( ! isset( $data['data'] ) ) {
			return;
		}

		$data = $data['data'];

		if ( empty( $data['template_id'] ) ) {
			return;
		}

		if ( false === strpos( $data['template_id'], self::TEMPLATE_ID_PREFIX ) ) {
			return;
		}

		// Once found out that current request is for Buddy_Builder then replace the native action.
		$ajax->register_ajax_action( 'get_template_data', [ $this, 'get_template_data' ] );
	}


	/**
	 * Add new categories to list.
	 *
	 * @param array $data Template library data including categories and templates.
	 *
	 * @return array $data Modified library data.
	 * @since 1.0.0
	 */
	public function add_categories( $data ) {
		$bpb_templates            = bpb_get_template_types();
		$buddy_builder_categories = [];

		if ( isset( $_REQUEST['editor_post_id'] ) && (int) $_REQUEST['editor_post_id'] > 0 ) {
			$meta = get_post_meta( $_REQUEST['editor_post_id'], BuddyPress::REMOTE_CATEGORY_META_KEY, true );
			if ( $meta && isset( $bpb_templates[ $meta ] ) ) {
				$buddy_builder_categories[] = 'buddypress ' . str_replace( '-', ' ', $meta );
			}
		}

		if ( ! empty( $buddy_builder_categories ) ) {
			$data['types_data']['block']['categories'] = array_merge( $buddy_builder_categories, $data['types_data']['block']['categories'] );
			sort( $data['types_data']['block']['categories'] );
		}

		return $data;
	}

}
