<?php

namespace Leadin;

use \Leadin\AssetsManager;
use \Leadin\PageHooks;
use \Leadin\admin\LeadinAdmin;
use Leadin\rest\LeadinRestApi;
use Leadin\admin\widgets\ElementorForm;
use Leadin\admin\widgets\ElementorMeeting;

/**
 * Main class of the plugin.
 */
class Leadin {
	/**
	 * Plugin's constructor. Everything starts here.
	 */
	public function __construct() {
		new PageHooks();
		new LeadinRestApi();
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
		if ( is_admin() ) {
			new LeadinAdmin();
		}
	}

	/**
	 * Register widgets for Elementor.
	 *
	 * @param object $elements_manager elementor widget manager.
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'hubspot',
			array(
				'title' => esc_html__( 'Hubspot', 'leadin' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Register widgets for Elementor.
	 *
	 * @param object $widgets_manager elementor widget manager.
	 */
	public function register_elementor_widgets( $widgets_manager ) {
		$widgets_manager->register( new ElementorForm() );
		$widgets_manager->register( new ElementorMeeting() );
	}

}

