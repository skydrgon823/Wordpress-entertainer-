<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ES_Gallery' ) ) {
	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Admin Settings
	 *
	 * @package    Email_Subscribers
	 * @subpackage Email_Subscribers/admin
	 */
	class ES_Gallery {
	
		// class instance
		public static $instance;

		// class constructor
		public function __construct() {
			$this->init();
		}

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
	
			return self::$instance;
		}

		public function init() {
			$this->register_hooks();
		}
	
		public function register_hooks() {
			add_action( 'admin_init', array( $this, 'import_gallery_item' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_ajax_ig_es_get_gallery_items', array( $this, 'get_gallery_items' ) );
		}

		/**
		 * Register the JavaScript for campaign rules.
		 */
		public function enqueue_scripts() {

			$current_page = ig_es_get_request_data( 'page' );

			if ( in_array( $current_page, array( 'es_gallery' ), true ) ) {
				wp_register_script( 'mithril', plugins_url( '/js/mithril.min.js', __FILE__ ), array(), '2.0.4', true );
				wp_enqueue_script( 'mithril' );

				$main_js_data = array(
					'dnd_editor_slug'                 => esc_attr( IG_ES_DRAG_AND_DROP_EDITOR ),
					'classic_editor_slug'             => esc_attr( IG_ES_CLASSIC_EDITOR ),
					'post_notification_campaign_type' => esc_attr( IG_CAMPAIGN_TYPE_POST_NOTIFICATION ),
					'newsletter_campaign_type'        => esc_attr( IG_CAMPAIGN_TYPE_NEWSLETTER ),
					'post_digest_campaign_type'       => esc_attr( IG_CAMPAIGN_TYPE_POST_DIGEST ),
				);

				if ( ! wp_script_is( 'wp-i18n' ) ) {
					wp_enqueue_script( 'wp-i18n' );
				}

				wp_register_script( 'ig-es-main-js', plugins_url( '/dist/main.js', __FILE__ ), array( 'mithril' ), '2.0.4', true );
				wp_enqueue_script( 'ig-es-main-js' );

				wp_localize_script( 'ig-es-main-js', 'ig_es_main_js_data', $main_js_data );
			}
		}
	
		public function render() {
			include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/gallery.php';
		}

		public function import_gallery_item() {

			$action = ig_es_get_request_data( 'action' );
			
			if ( 'ig_es_import_gallery_item' === $action ) {
				check_admin_referer( 'ig-es-admin-ajax-nonce' );
				$template_id          = ig_es_get_request_data( 'template-id' );
				$campaign_id          = ig_es_get_request_data( 'campaign-id' );
				$campaign_type        = ig_es_get_request_data( 'campaign-type' );
				$imported_campaign_id = $this->import_gallery_item_handler( $template_id, $campaign_type, $campaign_id );
				if ( ! empty( $imported_campaign_id ) ) {
					if ( IG_CAMPAIGN_TYPE_POST_DIGEST === $campaign_type || IG_CAMPAIGN_TYPE_POST_NOTIFICATION === $campaign_type ) {
						$redirect_url = admin_url( 'admin.php?page=es_notifications&action=edit&list=' . $imported_campaign_id );
					} else {
						$redirect_url = admin_url( 'admin.php?page=es_newsletters&action=edit&list=' . $imported_campaign_id );
					}
					wp_safe_redirect( $redirect_url );
					exit();
				}
			}
		}

		public function import_gallery_item_handler( $template_id, $campaign_type, $campaign_id = 0 ) {
			
			if ( ! empty( $template_id ) ) {
				$template = get_post( $template_id );
				if ( ! empty( $template ) ) {
					$subject     = $template->post_title;
					$content     = $template->post_content;
					$from_email  = ES_Common::get_ig_option( 'from_email' );
					$from_name   = ES_Common::get_ig_option( 'from_name' );
					$editor_type = get_post_meta( $template_id, 'es_editor_type', true );
					
					if ( empty( $editor_type ) ) {
						$editor_type = IG_ES_CLASSIC_EDITOR;
					}

					$campaign_meta = array(
						'editor_type' => $editor_type,
					);

					if ( IG_ES_DRAG_AND_DROP_EDITOR === $editor_type ) {
						$dnd_editor_data = get_post_meta( $template_id, 'es_dnd_editor_data', true );
						if ( ! empty( $dnd_editor_data ) ) {
							$campaign_meta['dnd_editor_data'] = wp_json_encode( $dnd_editor_data );
						}
					} else {
						if ( false === strpos( $content, '<html' ) ) {
							// In classic edior, we need to add p tag to content when not already added.
							$content = wpautop( $content );
						}
					}

					$campaign_meta = maybe_serialize( $campaign_meta );

					$campaign_data = array(
						'name'       => $subject,
						'subject'    => $subject,
						'slug'       => sanitize_title( sanitize_text_field( $subject ) ),
						'body'       => $content,
						'from_name'  => $from_name,
						'from_email' => $from_email,
						'type'       => $campaign_type,
						'meta'		 => $campaign_meta,
					);

					if ( ! empty( $campaign_id ) ) {
						ES()->campaigns_db->update( $campaign_id, $campaign_data );
					} else {
						$campaign_id = ES()->campaigns_db->save_campaign( $campaign_data );
					}

				}
			}
			
			return $campaign_id;
		}

		/**
		 * Get campaign templates
		 */
		public function get_gallery_items() {

			check_ajax_referer( 'ig-es-admin-ajax-nonce', 'security' );
	
			$response = array();
			$gallery_items = array();

			$campaign_templates = ES_Common::get_templates();
			
			if ( !empty( $campaign_templates ) ) {
				foreach ( $campaign_templates as $campaign_template) {
					$editor_type = get_post_meta( $campaign_template->ID, 'es_editor_type', true );
					$categories = array();
					$gallery_item['ID'] = $campaign_template->ID;
					$gallery_item['title'] = $campaign_template->post_title;
					$gallery_item['type'] = get_post_meta( $campaign_template->ID, 'es_template_type', true );
					$gallery_item['editor_type'] = !empty($editor_type) ? $editor_type : IG_ES_CLASSIC_EDITOR;
					$categories[] = !empty($gallery_item['type']) ?  $gallery_item['type'] : IG_CAMPAIGN_TYPE_NEWSLETTER;
					$categories[] = !empty($editor_type) ? $editor_type : IG_ES_CLASSIC_EDITOR;
					$gallery_item['categories'] = $categories;
					$thumbnail_url = ( ! empty( $campaign_template->ID ) ) ? get_the_post_thumbnail_url(
						$campaign_template->ID,
						array(
							'200',
							'200',
						) ): '';
					$gallery_item['thumbnail'] = ( !empty ($thumbnail_url) ) ? $thumbnail_url : '';
					$gallery_items[] = $gallery_item;
				}
			}
			
			
			$response['items'] = $gallery_items;

			wp_send_json_success( $response );
		}
	}

}

ES_Gallery::get_instance();
