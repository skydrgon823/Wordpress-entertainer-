<?php 
/**
 * AWPCP Extension.
 * Extension for searching contents of 'Another WordPress Classifieds Plugin'
 * https://wordpress.org/plugins/another-wordpress-classifieds-plugin/
 * 
 * Since version 1.0.9
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('BBoss_Global_Search_AWPCP_Loader')):

	class BBoss_Global_Search_AWPCP_Loader {
		private $search_type				= 'awpcp_ad_listing',
				$search_type_label			= 'awpcp_ad_listing',
				$is_awpcp_activated			= false,
				$is_awpcp_network_activated = false;
		
		/**
		 * Empty constructor function to ensure a single instance
		 */
		public function __construct() {
			// ... leave empty, see Singleton below
		}

		/**
		 * singleton
		 *
		 * @return object BBoss_Global_Search_AWPCP_Loader
		 */
		public static function instance() {
			static $instance = null;

			if (null === $instance) {
				$instance = new BBoss_Global_Search_AWPCP_Loader();
				$instance->setup();
			}

			return $instance;
		}
		
		private function setup(){
			if ( !function_exists('is_plugin_active') )
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			
			$this->is_awpcp_activated = is_plugin_active( 'another-wordpress-classifieds-plugin/awpcp.php' );
			
			if ( is_multisite() ) {
				if ( is_plugin_active_for_network( 'another-wordpress-classifieds-plugin/awpcp.php' ) ) {
					$this->is_awpcp_network_activated = true;
				}
			}
			
			if( !$this->is_awpcp_activated && !$this->is_awpcp_network_activated ){
				//looks like AWPCP plugin is not active, in that case, it doesn't make sense to load this extension
				$load_extension = apply_filters( 'bboss_global_search_load_extension_awpcp', false );
				if( !$load_extension )
					return;
			}
			
			/**
			 * The filter below can be used, if you need some other text insted of 'Classifieds'.
			 */
			$this->search_type_label = apply_filters( 'bboss_global_search_label_awpcp_ad_listing', __( 'Classifieds', 'buddypress-global-search' ) );
			
			//1. display setting
			add_action( 'bboss_global_search_settings_items_to_search',		array( $this, 'print_awpcp_search_option' ) );
			
			//2. load search helper
			add_filter( 'bboss_global_search_additional_search_helpers',	array( $this, 'load_search_helper' ) );
			
			//3. filter search type display text
			add_filter( 'bboss_global_search_label_search_type',			array( $this, 'search_type_label' ) );
		}
		
		/**
		 * Print 'Classified listings' on settings screen.
		 * @param array $items_to_search
		 */
		public function print_awpcp_search_option( $items_to_search ){
			$checked = !empty( $items_to_search ) && in_array( $this->search_type, $items_to_search ) ? ' checked' : '';
			echo "<label><input type='checkbox' value='{$this->search_type}' name='buddyboss_global_search_plugin_options[items-to-search][]' {$checked}>{$this->search_type_label}</label><br>";
		}
		
		public function load_search_helper( $helpers ){
			require_once  BUDDYBOSS_GLOBAL_SEARCH_PLUGIN_DIR . 'includes/plugins/awpcp/class.BBoss_Global_Search_AWPCP.php';
			$helpers[$this->search_type] = BBoss_Global_Search_AWPCP::instance();

			return $helpers;
		}
		
		public function search_type_label( $label ){
			if( $label == $this->search_type ){
				$label = $this->search_type_label;
			}
			return $label;
		}
		
	}
endif;

BBoss_Global_Search_AWPCP_Loader::instance();//instantiate