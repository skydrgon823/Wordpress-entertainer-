<?php

namespace Leadin\admin;

use Leadin\AssetsManager;
use Leadin\utils\Versions;

/**
 * Contains all the methods used to initialize Gutenberg blocks.
 */
class Gutenberg {
	/**
	 * Class constructor, register Gutenberg blocks.
	 */
	public function __construct() {
		if ( ! function_exists( 'register_block_type' ) ) {
			// Gutenberg is not active.
			return;
		}

		add_action( 'init', array( $this, 'register_gutenberg_block' ) );

		// block_categories hook deprecated in 5.8 for block_categories_all https://developer.wordpress.org/reference/hooks/block_categories/.
		global $wp_version;
		$is_block_categories_supported = Versions::is_version_less_than( $wp_version, '5.8' );
		$block_categories_hook         = $is_block_categories_supported ? 'block_categories' : 'block_categories_all';
		add_filter( $block_categories_hook, array( $this, 'add_hubspot_category' ) );
	}

	/**
	 * Add HubSpot category to Gutenberg blocks.
	 *
	 * @param Array $categories Array of block categories.
	 */
	public function add_hubspot_category( $categories ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'leadin-blocks',
					'title' => __( 'HubSpot', 'leadin' ),
				),
			)
		);
	}
	/**
	 * Register HubSpot Form Gutenberg block.
	 */
	public function register_gutenberg_block() {
		AssetsManager::localize_gutenberg();
		AssetsManager::localize_meetings_gutenberg();
		register_block_type(
			'leadin/hubspot-blocks',
			array(
				'editor_script' => AssetsManager::GUTENBERG,
			)
		);
	}
}
