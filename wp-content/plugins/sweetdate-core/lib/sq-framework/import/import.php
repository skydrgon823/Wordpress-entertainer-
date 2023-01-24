<?php
/**
 * SeventhQueen import helper
 */
$is_admin_area = is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'sq_import' && isset($_POST['import']);
$is_ajax_request = defined('DOING_AJAX') && DOING_AJAX && isset($_POST['action']) && $_POST['action'] == 'sq_single_import';

if ( $is_admin_area || $is_ajax_request ) {
	if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
		define( 'WP_LOAD_IMPORTERS', true );
	}

	require_once plugin_dir_path( __FILE__ ) . 'wordpress-importer.php';
}
