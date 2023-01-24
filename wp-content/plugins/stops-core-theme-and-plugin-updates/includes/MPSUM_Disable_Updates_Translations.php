<?php
/**
 * Disables all WordPress translation updates.
 *
 * @package WordPress
 *  @since 5.0.0
 */

/**
 * Disable tranlastion updates
 * Credit - From https://wordpress.org/plugins/disable-wordpress-updates/
 */
class MPSUM_Disable_Updates_Translations {
	/**
	 * Constructor
	 */
	public function __construct() {
		/*
		 * Disable All Automatic Updates
		 * 3.7+
		 *
		 * @author	sLa NGjI's @ slangji.wordpress.com
		 */
		add_filter('auto_update_translation', '__return_false');
		
		/*
		 * Disable Theme Translations
		 * 2.8 to 3.0
		 */
		add_filter('pre_transient_update_themes', array($this, 'remove_translations'), 10, 2);

		/*
		 * 3.0
		 */
		add_filter('pre_site_transient_update_themes', array($this, 'remove_translations'), 10, 2);
		
		
		/*
		 * Disable Plugin Translations
		 * 2.8 to 3.0
		 */
		add_action('pre_transient_update_plugins', array($this, 'remove_translations'), 10, 2);

		/*
		 * 3.0
		 */
		add_filter('pre_site_transient_update_plugins', array($this, 'remove_translations'), 10, 2);
		
		
		/*
		 * Disable Core Translations
		 * 2.8 to 3.0
		 */
		add_filter('pre_transient_update_core', array($this, 'remove_translations'), 10, 2);

		/*
		 * 3.0
		 */
		add_filter('pre_site_transient_update_core', array($this, 'remove_translations'), 10, 2);
		
	} //end constructor
	
	/**
	 * Remove translations
	 *
	 * @param array $transient Transient options
	 * @param value $key       Transient value name
	 * @return array
	 */
	public function remove_translations($transient, $key) {
		remove_filter('pre_transient_update_themes', array($this, 'remove_translations'), 10, 2);
		remove_filter('pre_site_transient_update_themes', array($this, 'remove_translations'), 10, 2);
		remove_filter('pre_transient_update_plugins', array($this, 'remove_translations'), 10, 2);
		remove_filter('pre_site_transient_update_plugins', array($this, 'remove_translations'), 10, 2);
		remove_filter('pre_transient_update_core', array($this, 'remove_translations'), 10, 2);
		remove_filter('pre_site_transient_update_core', array($this, 'remove_translations'), 10, 2);
		$option = get_site_transient($key);
		if (isset($option->translations)) {
			$option->translations = array();
		}
		add_filter('pre_transient_update_themes', array($this, 'remove_translations'), 10, 2);
		add_filter('pre_site_transient_update_themes', array($this, 'remove_translations'), 10, 2);
		add_filter('pre_transient_update_plugins', array($this, 'remove_translations'), 10, 2);
		add_filter('pre_site_transient_update_plugins', array($this, 'remove_translations'), 10, 2);
		add_filter('pre_transient_update_core', array($this, 'remove_translations'), 10, 2);
		add_filter('pre_site_transient_update_core', array($this, 'remove_translations'), 10, 2);
;
		return $option;
	}
}
