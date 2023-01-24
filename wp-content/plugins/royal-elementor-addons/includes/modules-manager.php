<?php
namespace WprAddons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Manager {

	public function __construct() {
    	$modules = Utilities::get_available_modules();

    	if ( empty(Utilities::get_available_modules()) && false === get_option('wpr-element-toggle-all') ) {
    		$modules = Utilities::get_registered_modules();
    	}

		foreach ( $modules as $data ) {
			$module = $data[0];

			$class_name = str_replace( '-', ' ', $module );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';
			
			$class_name::instance();
		}
	}
	
}