<?php
namespace WprAddons\Admin\Includes;

use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPR_Conditions_Manager setup
 *
 * @since 1.0
 */
class WPR_Conditions_Manager {

    /**
    ** Header & Footer Conditions
    */
    public static function header_footer_display_conditions( $conditions ) {
        $template = NULL;

        // Custom
        if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
	        if ( ! empty($conditions) ) {

				// Archive Pages (includes search)
				if ( ! is_null( \WprAddonsPro\Classes\Pro_Modules::archive_templates_conditions( $conditions ) ) ) {
					$template = \WprAddonsPro\Classes\Pro_Modules::archive_templates_conditions( $conditions );
				}

	        	// Single Pages
				if ( ! is_null( \WprAddonsPro\Classes\Pro_Modules::single_templates_conditions( $conditions, true ) ) ) {
					$template = \WprAddonsPro\Classes\Pro_Modules::single_templates_conditions( $conditions, true );
				}

	        }
        } else {
        	$template = Utilities::get_template_slug( $conditions, 'global' );
        }

	    return $template;
    }
}