<?php

use Buddy_Builder\Template\Module;
use Elementor\Core\Base\Document;

/**
 * Get shortcode string
 *
 * @param $template
 * @param bool     $preview
 *
 * @return bool|string
 */
function bpb_get_shortcode_str( $template, $preview = false ) {
	if ( ! is_int( $template ) ) {
		$settings    = bpb_get_settings();
		$template_id = isset( $settings['templates'][ $template ] ) ? $settings['templates'][ $template ] : 0;
	} else {
		$template_id = $template;
	}

	if ( ! get_post( $template_id ) ) {
		return '';
	}

	$shortcode_name = 'elementor-template';

	if ( $preview ) {
		$shortcode_name .= '-preview';
	}

	return (bool) $template_id ? '[' . $shortcode_name . ' id="' . $template_id . '"]' : false;
}

/**
 * Check if template is populated
 *
 * @param $template
 *
 * @return bool
 */
function bpb_is_template_populated( $template = null ) {
	if ( ! $template ) {
		return false;
	}

	if ( ! is_int( $template ) ) {
		$settings    = bpb_get_settings();
		$template_id = isset( $settings['templates'][ $template ] ) ? $settings['templates'][ $template ] : 0;
	} else {
		$template_id = $template;
	}

	if ( ! get_post( $template_id ) ) {
		return false;
	}

	return bpb_is_template_id_populated( $template_id );
}

/**
 * Check if template ID is populated
 *
 * @param $id
 *
 * @return bool
 */
function bpb_is_template_id_populated( $id ) {
	$content = get_post_meta( $id, '_elementor_data', true );
	$content = json_decode( $content, true );

	return ! empty( $content );
}

/**
 * Get available plugin template types
 *
 * @return array
 */
function bpb_get_template_types() {
	$templates_types = apply_filters(
		'buddy_builder/get_template_types',
		[
			'member-profile'    => [
				'name'  => __( 'Member Profile', 'stax-buddy-builder' ),
				'order' => 1,
			],
			'members-directory' => [
				'name'  => __( 'Members Directory', 'stax-buddy-builder' ),
				'order' => 2,
			],
			'group-profile'     => [
				'name'  => __( 'Group Profile', 'stax-buddy-builder' ),
				'order' => 4,
			],
			'groups-directory'  => [
				'name'  => __( 'Groups Directory', 'stax-buddy-builder' ),
				'order' => 5,
			],
			'sitewide-activity' => [
				'name'  => __( 'Site-Wide Activity', 'stax-buddy-builder' ),
				'order' => 7,
			],
		]
	);

	uasort(
		$templates_types,
		static function ( $a, $b ) {
			return $a['order'] - $b['order'];
		}
	);

	return $templates_types;
}

/**
 * Get saved templates settings
 *
 * @return array
 */
function bpb_get_settings() {
	$options = get_option( 'bpb_settings' );

	$defaults = [
		'templates' => [
			'member-profile'    => 0,
			'members-directory' => 0,
			'group-profile'     => 0,
			'groups-directory'  => 0,
			'sitewide-activity' => 0,
		],
	];

	$defaults = apply_filters( 'buddy_builder/default_settings', $defaults );

	if ( $options ) {
		foreach ( $defaults as $section => $default ) {
			if ( isset( $options[ $section ] ) ) {
				if ( is_array( $default ) ) {
					foreach ( $default as $key => $item ) {
						if ( is_array( $item ) ) {
							foreach ( $item as $itemKey => $itemValue ) {
								if ( isset( $options[ $section ][ $key ][ $itemKey ] ) ) {
									$defaults[ $section ][ $key ][ $itemKey ] = $options[ $section ][ $key ][ $itemKey ];
								}
							}
						} elseif ( isset( $options[ $section ][ $key ] ) ) {
							$defaults[ $section ][ $key ] = $options[ $section ][ $key ];
						}
					}
				} else {
					$defaults[ $section ] = $options[ $section ];
				}
			}
		}
	}

	return $defaults;
}

/**
 * Bulk update assigned templates
 *
 * @param array $data
 */
function bpb_bulk_save_settings( $data ) {
	$existing = bpb_get_settings();

	foreach ( $existing['templates'] as $slug => $template_id ) {
		if ( isset( $data[ $slug ] ) ) {
			$existing['templates'][ $slug ] = $data[ $slug ];
		}
	}

	update_option( 'bpb_settings', $existing );
}

/**
 * Set active template ID for a specific template type
 *
 * @param $template_slug
 * @param $id
 *
 * @return bool
 */
function bpb_set_template_id( $template_slug, $id ) {
	$settings = bpb_get_settings();

	if ( ! isset( $settings['templates'][ $template_slug ] ) || get_post_type( $id ) !== 'elementor_library' ) {
		return false;
	}

	$settings['templates'][ $template_slug ] = $id;

	return update_option( 'bpb_settings', $settings );
}

/**
 * Check if it's elementor editor or preview mode
 *
 * @return bool
 */
function bpb_is_elementor_editor() {
	$importing    = isset( $_REQUEST['action'] ) && $_REQUEST['action'] === Module::IMPORT_KEY;
	$is_ajax      = ( wp_doing_ajax() && ! isset( $_POST['object'] ) && ! isset( $_POST['target'] ) );
	$is_db_update = isset( $_GET['buddybuilder_db_update'] );

	return defined( 'ELEMENTOR_VERSION' ) &&
		   ( $is_ajax || $importing || $is_db_update ||
			 \Elementor\Plugin::$instance->editor->is_edit_mode() ||
			 \Elementor\Plugin::$instance->preview->is_preview_mode() ||
			 bpb_is_preview_mode() || bpb_is_front_library()
		   );
}

/**
 * Check if we are previewing a buddybuilder template in front-end
 *
 * @return bool
 */
function bpb_is_preview_mode() {
	return isset( $_GET['elementor_library'], $_GET['preview'] ) && bpb_is_doc_type( (int) $_GET['preview_id'] );
}

/**
 * Get column class
 *
 * @param $type
 * @param string $viewport
 *
 * @return mixed|string
 */
function bpb_get_column_class( $type, $viewport = '' ) {
	$classes = [
		'1' => 'grid-one',
		'2' => 'grid-two',
		'3' => 'grid-three',
		'4' => 'grid-four',
	];

	if ( $viewport === 'tablet' ) {
		return 'md-' . $classes[ $type ];
	}

	if ( $viewport === 'mobile' ) {
		return 'sm-' . $classes[ $type ];
	}

	return $classes[ $type ];
}

/**
 * Listing columns
 *
 * @return mixed|void
 */
function bpb_get_listing_columns() {
	$default = [
		'desktop' => '3',
		'tablet'  => '2',
		'mobile'  => '1',
	];

	$default_data = apply_filters(
		'buddy_builder/loop_columns',
		[
			'members_directory'      => $default,
			'groups_directory'       => $default,
			'group_profile_members'  => $default,
			'member_profile_friends' => $default,
		]
	);

	return get_option( 'bpb_listings', $default_data );
}

/**
 * Update columns
 *
 * @param $data
 */
function bpb_update_listing_columns( $data ) {
	update_option( 'bpb_listings', $data );
}

/**
 * If we are viewing a buddybuilder template in front-end
 *
 * @return bool
 */
function bpb_is_front_library() {
	if ( ! isset( $_GET['elementor_library'] ) ) {
		return false;
	}

	$tpl_id = (int) get_the_ID();

	if ( $tpl_id === 0 ) {
		$slug = sanitize_text_field( $_GET['elementor_library'] );
		$tpl  = get_page_by_path( $slug, OBJECT, 'elementor_library' );
		if ( $tpl ) {
			$tpl_id = $tpl->ID;
		}
	}

	return bpb_is_doc_type( $tpl_id );
}

/**
 * Check if we are on preview iframe in edit mode and a buddybuilder template
 *
 * @return bool
 */
function bpb_is_edit_frame() {
	if ( ! isset( $_GET['elementor-preview'] ) ) {
		return false;
	}

	$post_id = (int) $_GET['elementor-preview'];

	if ( $post_id === 0 ) {
		return false;
	}

	if ( ! bpb_is_doc_type( $post_id ) ) {
		return false;
	}

	return true;
}

/**
 * Check if the post is a buddybuilder document type
 *
 * @param int $id
 *
 * @return bool
 */
function bpb_is_doc_type( $id = 0 ) {
	if ( ! $id ) {
		return false;
	}

	return get_post_meta( $id, Document::TYPE_META_KEY, true ) === 'bpb-buddypress';
}

/**
 * @param $name
 * @param array $args
 * @param bool  $require_once
 */
function bpb_load_template( $name, $args = [], $require_once = false ) {
	if ( ! $name ) {
		return;
	}

	extract( $args );
	$name = trim( $name );

	ob_start();
	include __DIR__ . '/templates/' . $name . '.php';
	echo ob_get_clean();
}

/**
 * @return mixed|void
 */
function bpb_get_appearance() {
	return get_option( 'bp_nouveau_appearance', [] );
}

/**
 * @param $data
 *
 * @return bool
 */
function bpb_update_appearance( $data ) {
	return update_option( 'bp_nouveau_appearance', $data );
}

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_stax_buddy_builder() {
	if ( ! class_exists( 'Appsero\Client' ) ) {
		return;
	}

	$client = new Appsero\Client( '3c249edb-b9b3-4811-b254-f77c9ac85f66', 'BuddyBuilder', BPB_FILE );

	// Active insights
	$client->insights()->init();

	// Active automatic updater
	$client->updater();

}

appsero_init_tracker_stax_buddy_builder();
