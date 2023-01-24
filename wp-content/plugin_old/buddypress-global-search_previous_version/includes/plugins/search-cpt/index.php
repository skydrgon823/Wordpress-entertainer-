<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'bboss_global_search_settings_items_to_search', 'bboss_global_search_option_cpt_search' );
/**
 * Print all custom post types on settings screen.
 * @param array $items_to_search
 * @since 1.0.0
 */
function bboss_global_search_option_cpt_search( $items_to_search ){
	//all the cpts registered
	$cpts = get_post_types( array( 'public'=>true, 'publicly_queryable'=>true, 'exclude_from_search'=>false ), 'objects' );
	
	//remove posts
	$cpts['post'] = null;
	unset( $cpts['post'] );
	
	//remove attachment
	$cpts['attachment'] = null;
	unset( $cpts['attachment'] );
	
	$cpts = apply_filters( 'bboss_global_search_cpts_to_search', $cpts );
	
	if( !empty( $cpts ) ){
		foreach( $cpts as $cpt=>$cpt_obj ){
			$checked = !empty( $items_to_search ) && in_array( 'cpt-' . $cpt, $items_to_search ) ? ' checked' : '';
			echo "<label><input type='checkbox' value='cpt-{$cpt}' name='buddyboss_global_search_plugin_options[items-to-search][]' {$checked}>{$cpt_obj->label}</label><br>";
			do_action( 'bboss_global_search_settings_item_'.$cpt, $items_to_search );
		}
	}
}

add_filter( 'bboss_global_search_additional_search_helpers', 'bboss_global_search_helpers_cpts' );
/**
 * Load search helpers for each searchable custom post type.
 * 
 * @param array $helpers
 * @return array
 * @since 1.0.0
 */
function bboss_global_search_helpers_cpts( $helpers ){
	/**
	 * Check which cpts we need to search.
	 * And create one helper class object for each.
	 */
	$searchable_types = buddyboss_global_search()->option('items-to-search');
		
	foreach( $searchable_types as $searchable_type ){
		//if name starts with cpt-
		
		$pos = strpos( $searchable_type, 'cpt-' );
		if( $pos === 0 ){
			$cpt_name = str_replace( 'cpt-', '', $searchable_type );
			$cpt_obj = get_post_type_object( $cpt_name );
			//is cpt still valid?
			if( $cpt_obj && !is_wp_error( $cpt_obj ) ){
				require_once  BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DIR . 'includes/plugins/search-cpt/class.BBoss_Global_Search_CPT.php';
				$helpers[$searchable_type] = new BBoss_Global_Search_CPT( $cpt_name, $searchable_type );
			}
		}
	}
	
	return $helpers;
}

add_filter( 'bboss_global_search_label_search_type', 'bboss_global_search_label_search_type_cpts' );
/**
 * Change the display text of custom post type search tabs.
 * Change it from 'cpt-movie' to 'Movies' for example.
 * 
 * @param string $search_type_label
 * @return string
 * @since 1.0.0
 */
function bboss_global_search_label_search_type_cpts( $search_type_label ){
	/**
	 * search type is 'cpt-movie', 'cpt-book' etc.
	 * so removing 'cpt-' gives us the custom post type name
	 */

	//Return label from admin search items options
	$items = buddyboos_global_search_items();
	if ( isset( $items[$search_type_label] ) ) {
		return $items[$search_type_label];
	}

	$pos = strpos( $search_type_label, 'cpt-' );
	if( $pos === 0 ){
		$cpt_name = str_replace( 'cpt-', '', $search_type_label );
		
		$cpt_obj = get_post_type_object( $cpt_name );
		if( $cpt_obj && !is_wp_error( $cpt_obj ) ){
			$search_type_label = $cpt_obj->label;
		}
	}
	
	$pos = strpos( $search_type_label, 'Cpt-' );
	if( $pos === 0 ){
		$cpt_name = str_replace( 'Cpt-', '', $search_type_label );
		
		$cpt_obj = get_post_type_object( $cpt_name );
		if( $cpt_obj && !is_wp_error( $cpt_obj ) ){
			$search_type_label = $cpt_obj->label;
		}
	}
	return $search_type_label;
}