<?php
namespace WprAddons\Admin\Templates;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPR_Templates_Library_Pages setup
 *
 * @since 1.0
 */
class WPR_Templates_Library_Pages {

	/**
	** Constructor
	*/
	public function __construct() {

		// Template Library Popup
		add_action( 'wp_ajax_render_library_templates_pages', [ $this, 'render_library_templates_pages' ] );

	}

	/**
	** Template Library Popup
	*/
	public function render_library_templates_pages() {
		?>

		<div class="wpr-tplib-sidebar">
			<ul>
				<li>Home</li>
				<li>Blog</li>
				<li>Landing</li>
			</ul>
		</div>

		<div class="wpr-tplib-template-gird">
			<div class="wpr-tplib-template" data-slug="page-1">Page 1</div>
			<div class="wpr-tplib-template" data-slug="page-2">Page 2</div>
			<div class="wpr-tplib-template">Page 3</div>
			<div class="wpr-tplib-template">Page 4</div>
			<div class="wpr-tplib-template">Page 5</div>
			<div class="wpr-tplib-template">Page 6</div>
		</div>


		<?php exit();
	}

}