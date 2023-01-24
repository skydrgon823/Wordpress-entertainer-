<?php
/*
* Shortcode generator
*/

class KleoShortcodes {

	function __construct() {
		require_once( SWEETCORE_PATH . 'core/shortcodes/' . 'shortcodes.php' );

		define( 'KLEO_TINYMCE_URI', SWEETCORE_URL . '/core/shortcodes/' . 'tinymce' );
		define( 'KLEO_TINYMCE_DIR', SWEETCORE_PATH . 'core/shortcodes/' . 'tinymce' );

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		//paragraph fix
		add_filter( 'the_content', array( $this, 'shortcode_empty_paragraph_fix' ) );
	}

	/**
	 * Registers TinyMCE rich editor button
	 *
	 * @return    void
	 */
	function init() {

		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', array( $this, 'add_rich_plugins' ) );
			add_filter( 'mce_buttons', array( $this, 'register_rich_buttons' ) );
		}
	}

	// --------------------------------------------------------------------------

	/**
	 * Fix issues when shortcodes are embedded in a block of content that is filtered by wpautop
	 * Thanks to http://www.johannheyne.de
	 *
	 * @param string $content
	 *
	 * @return string
	 */

	function shortcode_empty_paragraph_fix( $content ) {
		$array = array(
			'<p>['    => '[',
			']</p>'   => ']',
			']<br />' => ']'
		);

		$content = strtr( $content, $array );

		return $content;
	}

	// --------------------------------------------------------------------------

	/**
	 * Define TinyMCE rich editor js plugin
	 *
	 * @param $plugin_array
	 *
	 * @return mixed
	 */
	function add_rich_plugins( $plugin_array ) {
		$plugin_array['kleoShortcodes'] = KLEO_TINYMCE_URI . '/plugin.js';

		return $plugin_array;
	}

	// --------------------------------------------------------------------------

	/**
	 * Adds TinyMCE rich editor buttons
	 *
	 * @param $buttons
	 *
	 * @return mixed
	 */
	function register_rich_buttons( $buttons ) {
		array_push( $buttons, "|", 'kleo_button' );

		return $buttons;
	}

	/**
	 * Enqueue Scripts and Styles
	 *
	 * @return    void
	 */
	function admin_init() {
		// css
		wp_enqueue_style( 'kleo-popup', KLEO_TINYMCE_URI . '/css/popup.css', false, '1.0', 'all' );

		// js
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-livequery', KLEO_TINYMCE_URI . '/js/jquery.livequery.js', false, '1.1.1', false );
		wp_enqueue_script( 'jquery-appendo', KLEO_TINYMCE_URI . '/js/jquery.appendo.js', false, '1.0', false );
		wp_enqueue_script( 'base64', KLEO_TINYMCE_URI . '/js/base64.js', false, '1.0', false );
		wp_enqueue_script( 'kleo-popup', KLEO_TINYMCE_URI . '/js/popup.js', false, '1.0', false );

		$wp_version = floatval( get_bloginfo( 'version' ) );
		if ( $wp_version >= "3.6" ) {
			wp_localize_script( 'jquery-core', 'KleoShortcodes', array( 'plugin_folder' => get_template_directory_uri() . '/framework/shortcodes' ) );
		} else {
			wp_localize_script( 'jquery', 'KleoShortcodes', array( 'plugin_folder' => get_template_directory_uri() . '/framework/shortcodes' ) );
		}

	}

}

$kleo_shortcodes = new KleoShortcodes();
