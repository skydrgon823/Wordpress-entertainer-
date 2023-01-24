<?php

add_filter( 'bps_templates', 'kleo_bp_search_tpl' );

function kleo_bp_search_tpl( $templates ) {
    $templates = array ( 'members/bps-form-legacy', 'members/bps-form-horizontal' , 'members/bps-form-custom');

    return $templates;
}

/* Members directory */
add_action( 'init', 'sq_bps_members_form');
function sq_bps_members_form() {
	if ( function_exists( 'bp_is_active' ) && bp_is_members_directory() ) {
		remove_action ('bp_before_directory_members_tabs', 'bps_add_form');
		add_action ('kleo_bp_before_page', 'bps_add_form');
	}
}
