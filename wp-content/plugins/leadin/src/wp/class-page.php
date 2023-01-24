<?php

namespace Leadin\wp;

/**
 * Static function that wraps WordPress utility functions for WordPress pages.
 */
class Page {
	/**
	 * Return true if the current page has Gutenberg active.
	 */
	public static function is_gutenberg_page() {
		if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
			// The Gutenberg plugin is on.
			return true;
		}

		$current_screen = get_current_screen();
		if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
			// Gutenberg page on 5+.
			return true;
		}
		return false;
	}

	/**
	 * Return true if the current page is the WP Admin dashboard.
	 */
	public static function is_dashboard() {
		$screen = get_current_screen();
		return 'dashboard' === $screen->id;
	}
}
