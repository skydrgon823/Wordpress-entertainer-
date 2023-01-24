<?php
namespace WprAddons\Admin\Includes;

use WprAddons\Plugin;
use Elementor\TemplateLibrary\Source_Base;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * WPR_Templates_Actions setup
 *
 * @since 1.0
 */
class WPR_Templates_Actions {

	/**
	** Constructor
	*/
	public function __construct() {

		// Save Conditions
		add_action( 'wp_ajax_wpr_save_template_conditions', [ $this, 'wpr_save_template_conditions' ] );

		// Create Template
		add_action( 'wp_ajax_wpr_create_template', [ $this, 'wpr_create_template' ] );

		// Import Library Template
		add_action( 'wp_ajax_wpr_import_library_template', [ $this, 'wpr_import_library_template' ] );

		// Reset Template
		add_action( 'wp_ajax_wpr_delete_template', [ $this, 'wpr_delete_template' ] );

		// Register Elementor AJAX Actions
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_elementor_ajax_actions' ] );

		// Enqueue Scripts
		add_action( 'admin_enqueue_scripts', [ $this, 'templates_library_scripts' ] );

	}

	/**
	** Save Template Conditions
	*/
	public function wpr_save_template_conditions() {
		// Header
		if ( isset($_POST['wpr_header_conditions']) ) {
			update_option( 'wpr_header_conditions', $this->sanitize_conditions($_POST['wpr_header_conditions']) );

			if ( isset($_POST['wpr_header_show_on_canvas']) ) {
				update_post_meta( Utilities::get_template_id($_POST['template']), 'wpr_header_show_on_canvas', $_POST['wpr_header_show_on_canvas'] );
			}
		}

		// Footer
		if ( isset($_POST['wpr_footer_conditions']) ) {
			update_option( 'wpr_footer_conditions', $this->sanitize_conditions($_POST['wpr_footer_conditions']) );

			if ( isset($_POST['wpr_footer_show_on_canvas']) ) {
				update_post_meta( Utilities::get_template_id($_POST['template']), 'wpr_footer_show_on_canvas', $_POST['wpr_footer_show_on_canvas'] );
			}
		}

		// Archive
		if ( isset($_POST['wpr_archive_conditions']) ) {
			update_option( 'wpr_archive_conditions', $this->sanitize_conditions($_POST['wpr_archive_conditions']) );
		}

		// Single
		if ( isset($_POST['wpr_single_conditions']) ) {
			update_option( 'wpr_single_conditions', $this->sanitize_conditions($_POST['wpr_single_conditions']) );
		}

		// Popup
		if ( isset($_POST['wpr_popup_conditions']) ) {
			update_option( 'wpr_popup_conditions', $this->sanitize_conditions($_POST['wpr_popup_conditions']) );
		}
	}

	public function sanitize_conditions( $data ) {
		return stripslashes( json_encode( array_filter( json_decode(stripcslashes($data), true) ) ) );
	}

	/**
	** Create Template
	*/
	public function wpr_create_template() {
		if ( isset($_POST['user_template_title']) ) {
			// Create
			$template_id = wp_insert_post(array (
				'post_type' 	=> sanitize_text_field($_POST['user_template_library']),
				'post_title' 	=> sanitize_text_field($_POST['user_template_title']),
				'post_name' 	=> sanitize_text_field($_POST['user_template_slug']),
				'post_content' 	=> '',
				'post_status' 	=> 'publish'
			));

			// Set Types
			if ( 'wpr_templates' === $_POST['user_template_library'] ) {
				wp_set_object_terms( $template_id, [sanitize_text_field($_POST['user_template_type']), 'user'], 'wpr_template_type' );

				if ( 'popup' === $_POST['user_template_type'] ) {
					update_post_meta( $template_id, '_elementor_template_type', 'wpr-popups' );
				} else {
					update_post_meta( $template_id, '_elementor_template_type', 'wpr-theme-builder' );
					update_post_meta( $template_id, '_wpr_template_type', sanitize_text_field($_POST['user_template_type']) );
				}
			} else {
				update_post_meta( $template_id, '_elementor_template_type', 'page' );
			}

			// Set Canvas Template
			update_post_meta( $template_id, '_wp_page_template', 'elementor_canvas' ); //tmp - maybe set for wpr_templates only

			// Send ID to JS
			echo $template_id;
		}
	}

	/**
	** Import Library Template
	*/
	public function wpr_import_library_template() {
        $source = new WPR_Library_Source();

        $data = $source->get_data([
        	'template_id' => $_POST['slug']
        ]);
        
        echo json_encode($data);
	}

	/**
	** Reset Template
	*/
	public function wpr_delete_template() {
		$post = get_page_by_path( sanitize_text_field($_POST['template_slug']), OBJECT, sanitize_text_field($_POST['template_library']) );
		wp_delete_post( $post->ID, true );
	}

	/**
	** Enqueue Scripts and Styles
	*/
	public function templates_library_scripts( $hook ) {

		// Get Plugin Version
		$version = Plugin::instance()->get_version();

		// Deny if NOT Plugin Page
		if ( 'toplevel_page_wpr-addons' == $hook || strpos($hook, 'wpr-theme-builder') || strpos($hook, 'wpr-popups') ) {

			// Color Picker
			wp_enqueue_style( 'wp-color-picker' );
		    wp_enqueue_script( 'wp-color-picker-alpha', WPR_ADDONS_URL .'assets/js/admin/wp-color-picker-alpha.min.js', ['jquery', 'wp-color-picker'], $version, true );

		    // Media Upload
			if ( ! did_action( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}

			// enqueue CSS
			wp_enqueue_style( 'wpr-plugin-options-css', WPR_ADDONS_URL .'assets/css/admin/plugin-options.css', [], $version );

		    // enqueue JS
		    wp_enqueue_script( 'wpr-plugin-options-js', WPR_ADDONS_URL .'assets/js/admin/plugin-options.js', ['jquery'], $version );

		}

		if ( strpos($hook, 'wpr-templates-kit') ) {
			wp_enqueue_style( 'wpr-templates-kit-css', WPR_ADDONS_URL .'assets/css/admin/templates-kit.css', [], $version );
		    wp_enqueue_script( 'wpr-templates-kit-js', WPR_ADDONS_URL .'assets/js/admin/templates-kit.js', ['jquery', 'updates'], $version );
		}

		if ( strpos($hook, 'wpr-premade-blocks') ) {
			wp_enqueue_style( 'wpr-premade-blocks-css', WPR_ADDONS_URL .'assets/css/admin/premade-blocks.css', [], $version );

		    wp_enqueue_script( 'wpr-macy-js', WPR_ADDONS_URL .'assets/js/lib/macy/macy.js', ['jquery'], $version );
		    wp_enqueue_script( 'wpr-premade-blocks-js', WPR_ADDONS_URL .'assets/js/admin/premade-blocks.js', ['jquery'], $version );
		}
	}

	/**
	** Register Elementor AJAX Actions
	*/
	public function register_elementor_ajax_actions( Ajax $ajax ) {

		// Elementor Search Data
		$ajax->register_ajax_action( 'wpr_elementor_search_data', function( $data ) {
			// Freemius OptIn
			if ( ! (wpr_fs()->is_registered() && wpr_fs()->is_tracking_allowed() || wpr_fs()->is_pending_activation() )) {
				return;
			}

			if ( strlen($data['search_query']) > 25 ) {
				return;
			}

			// Send Search Query
		    wp_remote_post( 'https://reastats.kinsta.cloud/wp-json/elementor-search/data', [
		        'body' => [
		            'search_query' => $data['search_query']
		        ]
		    ] );
		} );
	}
}

/**
 * WPR_Templates_Actions setup
 *
 * @since 1.0
 */
class WPR_Library_Source extends \Elementor\TemplateLibrary\Source_Base {

	public function get_id() {
		return 'wpr-layout-manager';
	}

	public function get_title() {
		return 'WPR Layout Manager';
	}

	public function register_data() {}

	public function save_item( $template_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot save template to a WPR layout manager' );
	}

	public function update_item( $new_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot update template to a WPR layout manager' );
	}

	public function delete_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot delete template from a WPR layout manager' );
	}

	public function export_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot export template from a WPR layout manager' );
	}

	public function get_items( $args = [] ) {
		return [];
	}

	public function get_item( $template_id ) {
		$templates = $this->get_items();

		return $templates[ $template_id ];
	}

	public function request_template_data( $template_id ) {
		if ( empty( $template_id ) ) {
			return;
		}

		$response = wp_remote_get( 'https://royal-elementor-addons.com/library/premade-styles/'. $template_id .'.json', [
			'timeout'   => 60,
			'sslverify' => false
		] );
		
		return wp_remote_retrieve_body( $response );
	}

	public function get_data( array $args ) {//TODO: FIX - This function imports placeholder images in library
		$data = $this->request_template_data( $args['template_id'] );

		$data = json_decode( $data, true );

		if ( empty( $data ) || empty( $data['content'] ) ) {
			throw new \Exception( 'Template does not have any content' );
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		return $data;
	}

}