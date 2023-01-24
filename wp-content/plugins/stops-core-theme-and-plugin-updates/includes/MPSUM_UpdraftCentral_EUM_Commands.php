<?php
if (!defined('ABSPATH')) die('No direct access allowed');

/**
 * This is a small glue class, which makes available all the commands in WP_Optimize_Commands, and translates the response from WP_Optimize_Commands (which is either data to return, or a WP_Error) into the format used by UpdraftCentral.
 */
class UpdraftCentral_EUM_Commands extends UpdraftCentral_Commands {
	
	private $commands;

	/**
	 * UpdraftCentral_EUM_Commands class constructor.
	 */
	public function __construct() {
		$this->commands = new MPSUM_Commands();
	}

	/**
	 * Mapping function for remote calls
	 *
	 * @param string $name      Command name
	 * @param array  $arguments Arguments for the method call
	 *
	 * @return array Response to the remote call
	 */
	public function __call($name, $arguments) {
		if (!current_user_can('manage_options')) {
			$result = __('User has insufficient capability to manage options', 'stops-core-theme-and-plugin-updates');
		} elseif ('_post_action' == $name || '_pre_action' == $name) {
			return array('result' => 'empty_action');
		} else {
			$result = call_user_func_array(array($this->commands, $name), $arguments);
		}
		
		if (is_wp_error($result)) {
			return $this->_generic_error_response($result->get_error_code(), $result->get_error_data());
		} else {
			return $this->_response($result);
		}
	}
}
