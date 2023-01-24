<?php
/**
 * Add Library buddypress Document.
 *
 * @package Buddy_Builder
 * @since 1.0.0
 */

namespace Buddy_Builder\Library\Documents;

use Buddy_Builder\ElementorHooks;

defined( 'ABSPATH' ) || die();

/**
 * Buddy_Builder buddypress library document.
 *
 * @since 1.0.0
 */
class BuddyPress extends Library_Document {

	/**
	 * Document sub type meta key.
	 */
	const REMOTE_CATEGORY_META_KEY   = '_bpb_page';
	const SET_AS_ACTIVE_CATEGORY_KEY = '_bpb_set_as_active';

	/**
	 * Get document properties.
	 *
	 * Retrieve the document properties.
	 *
	 * @return array Document properties.
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = true;
		$properties['library_view']    = 'list';
		$properties['show_in_library'] = true;
		$properties['support_kit']     = true;

		return $properties;
	}

	/**
	 * Get document name.
	 *
	 * Retrieve the document name.
	 *
	 * @return string Document name.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'bpb-buddypress';
	}

	/**
	 * Get document title.
	 *
	 * Shorten it on front-end are for admin bar
	 *
	 * @return string Document title.
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_title() {
		if ( is_admin() ) {
			return __( 'BuddyPress', 'stax-buddy-builder' );
		}

		return __( 'BP', 'stax-buddy-builder' );

	}

	/**
	 * @since  2.0.6
	 * @access public
	 */
	public function save_template_type() {
		parent::save_template_type();

		// Save template sub type
		if ( ! empty( $_REQUEST[ self::REMOTE_CATEGORY_META_KEY ] )
			 && array_key_exists( $_REQUEST[ self::REMOTE_CATEGORY_META_KEY ], bpb_get_template_types() ) ) {

			$sub_type = $_REQUEST[ self::REMOTE_CATEGORY_META_KEY ];

			$this->update_meta( self::REMOTE_CATEGORY_META_KEY, $sub_type );

			// Save as current sub template if we create it from our settings page
			if ( ! empty( $_REQUEST[ self::SET_AS_ACTIVE_CATEGORY_KEY ] ) ) {
				bpb_set_template_id( $sub_type, $this->post->ID );
				ElementorHooks::get_instance()->save_buddypress_options( $this->post->ID, null );
			}
		}

		// Save it for export/import
		if ( ! defined( 'DOING_AUTOSAVE' ) && $this->get_meta( self::REMOTE_CATEGORY_META_KEY ) ) {
			$settings = $this->get_settings();
			if ( empty( $settings ) ) {
				$settings = [];
			}

			if ( ! isset( $settings['bpb_type'] ) ) {
				$settings['bpb_type'] = $this->get_meta( self::REMOTE_CATEGORY_META_KEY );
				$this->save_settings( $settings );
			}
		}
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$category      = $this->get_meta( self::REMOTE_CATEGORY_META_KEY );
		$bpb_templates = bpb_get_template_types();

		if ( $category && isset( $bpb_templates[ $category ] ) ) {
			$config['category'] = 'buddypress ' . str_replace( '-', ' ', $category );
		}

		return $config;
	}

}
