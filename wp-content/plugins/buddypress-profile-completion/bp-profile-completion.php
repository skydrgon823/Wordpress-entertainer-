<?php
/**
 * Plugin Name: BuddyPress Profile Completion
 * Version: 1.0.8
 * Plugin URI: https://buddydev.com/introducing-buddypress-user-profile-completion/
 * Description: Force users to complete required fields, photos and cover.
 * Author: BuddyDev
 * Author URI: https://buddydev.com/
 * Requires PHP: 5.3
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  buddypress-profile-completion
 * Domain Path:  /languages
 *
 * @package BP_Profile_Completion
 **/

use BP_Profile_Completion\Bootstrap\Autoloader;
use BP_Profile_Completion\Bootstrap\Bootstrapper;

// Exit if accessed directly over web.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Do not load of a class with same name exists.
if ( ! class_exists( 'BP_Profile_Completion' ) ) {


	/**
	 * Class BP_Profile_Completion
	 *
	 * @property-read string $path absolute path to the plugin directory.
	 * @property-read string $url absolute url to the plugin directory.
	 * @property-read string $basename plugin base name.
	 * @property-read string $version plugin version.
	 */
	class BP_Profile_Completion {

		/**
		 * Plugin Version.
		 *
		 * @var string
		 */
		private $version = '1.0.8';

		/**
		 * Class instance
		 *
		 * @var BP_Profile_Completion
		 */
		private static $instance = null;

		/**
		 * Plugin absolute directory path
		 *
		 * @var string
		 */
		private $path;

		/**
		 * Plugin absolute directory url
		 *
		 * @var string
		 */
		private $url;

		/**
		 * Plugin Basename.
		 *
		 * @var string
		 */
		private $basename;

		/**
		 * Protected properties. These properties are inaccessible via magic method.
		 *
		 * @var array
		 */
		private static $protected = array( 'instance' );

		/**
		 * BP_Profile_Completion constructor.
		 */
		private function __construct() {
			$this->bootstrap();
		}

		/**
		 * Get Singleton Instance
		 *
		 * @return BP_Profile_Completion
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Bootstrap the core.
		 */
		private function bootstrap() {
			// Setup general properties.
			$this->path     = plugin_dir_path( __FILE__ );
			$this->url      = plugin_dir_url( __FILE__ );
			$this->basename = plugin_basename( __FILE__ );

			// Load autoloader.
			require_once $this->path . 'src/bootstrap/class-autoloader.php';

			$autoloader = new Autoloader( 'BP_Profile_Completion\\', __DIR__ . '/src/' );

			spl_autoload_register( $autoloader );

			Bootstrapper::boot();

			register_activation_hook( __FILE__, array( $this, 'activate' ) );
		}

		/**
		 * On activation.
		 */
		public function activate() {

			if ( ! get_option( 'bpprocn_settings' ) ) {
				require_once $this->path . 'src/core/bp-profile-completion-functions.php';
				update_option( 'bpprocn_settings', bpprocn_get_default_options() );
			}
		}


		/**
		 * Magic method for accessing property as readonly.
		 *
		 * @param string $name property name.
		 *
		 * @return mixed|null
		 */
		public function __get( $name ) {

			if ( ! in_array( $name, self::$protected, true ) && property_exists( $this, $name ) ) {
				return $this->{$name};
			}

			return null;
		}
	}

	/**
	 * Helper to access singleton instance
	 *
	 * @return BP_Profile_Completion
	 */
	function bp_profile_completion() {
		return BP_Profile_Completion::get_instance();
	}

	bp_profile_completion();

}