<?php
/**
 * Bootstrapper. Initializes the plugin.
 *
 * @package    BP_Profile_Completion
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2018, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh
 * @since      1.0.0
 */

namespace BP_Profile_Completion\Bootstrap;

use BP_Profile_Completion\Admin\Admin_Settings;
use BP_Profile_Completion\Core\BP_Profile_Completion_Helper;

// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 0 );
}

/**
 * Bootstrapper.
 */
class Bootstrapper {

	/**
	 * Setup the bootstrapper.
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
		return $self;
	}

	/**
	 * Bind hooks
	 */
	private function setup() {
		add_action( 'bp_loaded', array( $this, 'load' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'load_admin' ), 9996 ); // pt settings 1.0.4.
		add_action( 'bp_init', array( $this, 'load_translations' ) );
	}

	/**
	 * Load core functions/template tags.
	 * These are non auto loadable constructs.
	 */
	public function load() {
		require_once bp_profile_completion()->path . 'src/core/bp-profile-completion-functions.php';

		BP_Profile_Completion_Helper::boot();
	}

	/**
	 * Load pt-settings framework
	 */
	public function load_admin() {

		if ( function_exists( 'buddypress' ) && is_admin() && ! defined( 'DOING_AJAX' ) ) {
			require_once bp_profile_completion()->path . 'src/admin/pt-settings/pt-settings-loader.php';
			Admin_Settings::boot();
		}
	}

	/**
	 * Load translations.
	 */
	public function load_translations() {
		load_plugin_textdomain( 'buddypress-profile-completion', false, basename( bp_profile_completion()->path ) . '/languages' );
	}
}
