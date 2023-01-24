<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( '\Elementor\Plugin' ) ) {
	get_template_part( 'page' );
	return;
}

\Elementor\Plugin::$instance->frontend->add_body_class( 'elementor-template-full-width stax-buddybuilder-template' );

get_header();

/**
 * Before Header-Footer page template content.
 *
 * Fires before the content of Elementor Header-Footer page template.
 *
 * @since 2.0.0
 */
do_action( 'elementor/page_templates/header-footer/before_content' );

\Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content();


/**
 * After Header-Footer page template content.
 *
 * Fires after the content of Elementor Header-Footer page template.
 *
 * @since 2.0.0
 */
do_action( 'elementor/page_templates/header-footer/after_content' );

get_footer();
