<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( !is_plugin_active('advanced-custom-fields/acf.php') ) {
	return;
}

add_action( 'bboss_global_search_settings_items_to_search', 'bboss_global_search_option_acf_search' );

/**
 * Print all custom post types on settings screen.
 * @param array $items_to_search
 * @since 1.0.0
 */
function bboss_global_search_option_acf_search( $items_to_search ) {
	global $wpdb;
	//all the acf field groups registered
	$acf_field_groups = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE post_status LIKE %s AND post_type IN( '%s', '%s' )", 'publish', 'acf', 'acf-field-group' ) );

	if ( ! empty( $acf_field_groups ) ) {
		echo "<p class='acf-group-label' style='margin: 5px 0'><strong>ACF</strong></p>";
		foreach ( $acf_field_groups as $acf_obj ) {
			$acf_group_id = $acf_obj->ID;
			$checked = ! empty( $items_to_search ) && in_array( 'acf-' . $acf_group_id, $items_to_search ) ? ' checked' : '';
			echo "<label><input type='checkbox' value='acf-{$acf_group_id}' name='buddyboss_global_search_plugin_options[items-to-search][]' {$checked}>{$acf_obj->post_title}</label><br>";
		}
	}
}

add_filter( 'bboss_global_search_additional_search_helpers', 'bboss_global_search_helpers_acf' );

/**
 * Load search helpers for each searchable acf group.
 * 
 * @param array $helpers
 * @return array
 * @since 1.0.0
 */
function bboss_global_search_helpers_acf( $helpers ) {
	/**
	 * Check which acfs we need to search.
	 * And create one helper class object for each.
	 */
	$searchable_types = buddyboss_global_search()->option( 'items-to-search' );

	foreach ( $searchable_types as $searchable_type ) {
		//if name starts with acf-

		$pos = strpos( $searchable_type, 'acf-' );
		if ( $pos === 0 ) {
			$acf_field_group = str_replace( 'acf-', '', $searchable_type );
			require_once BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DIR . 'includes/plugins/search-acf/class.BBoss_Global_Search_ACF.php';
			$helpers[ $searchable_type ] = new BBoss_Global_Search_ACF( $acf_field_group );
		}
	}

	return $helpers;
}
