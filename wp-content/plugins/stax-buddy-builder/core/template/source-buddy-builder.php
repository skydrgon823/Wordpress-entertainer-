<?php

namespace Buddy_Builder\Template;

use Buddy_Builder\Library\Documents\BuddyPress;
use Elementor\TemplateLibrary\Source_Base;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

/**
 * Elementor template library remote source.
 *
 * Elementor template library remote source handler class is responsible for
 * handling remote templates from Elementor.com servers.
 *
 * @since 1.0.0
 */
class Source_Buddy_Builder extends Source_Base {

	/**
	 * Elementor template-library post-type slug.
	 */
	const CPT = 'elementor_library';

	public static $imported_templates = [];

	public function get_id() {
		return 'buddy_builder';
	}

	public function get_title() {
		return __( 'BuddyBuilder', 'stax-buddy-builder' );
	}

	public function register_data() {
	}

	public function get_items( $args = [] ) {
		$templates_data = Module::get_templates();

		$templates = [];

		foreach ( $templates_data as $template_data ) {
			$templates[] = $this->get_item( $template_data );
		}

		return $templates;
	}

	public function get_item( $template_data ) {
		$favorite_templates = $this->get_user_meta( 'favorites' );

		return [
			'template_id'     => 'buddy_builder_' . $template_data['id'],
			'source'          => 'remote',
			'type'            => 'block',
			'subtype'         => 'buddypress ' . $template_data['subtype'],
			'title'           => 'BuddyBuilder - ' . $template_data['title'], // Prepend name for searchable string
			'thumbnail'       => $template_data['thumbnail'],
			'date'            => $template_data['tmpl_created'],
			'author'          => $template_data['author'],
			'tags'            => $template_data['tags'],
			'isPro'           => 0,
			'popularityIndex' => (int) $template_data['popularity_index'],
			'trendIndex'      => (int) $template_data['trend_index'],
			'hasPageSettings' => ( '1' === $template_data['has_page_settings'] ),
			'url'             => $template_data['url'],
			'favorite'        => ! empty( $favorite_templates[ $template_data['id'] ] ),
		];
	}

	public function save_item( $template_data ) {
		return false;
	}

	public function update_item( $new_data ) {
		return false;
	}

	public function delete_template( $template_id ) {
		return false;
	}

	public function export_template( $template_id ) {
		return false;
	}

	public function get_data( array $args, $context = 'display' ) {
		$data = Module::get_template_content( $args['template_id'] );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		if ( isset( $_POST['editor_post_id'] ) ) {
			$post_id  = $_POST['editor_post_id']; // phpcs:ignore
			$document = Plugin::$instance->documents->get( $post_id );
			if ( $document ) {
				$data['content'] = $document->get_elements_raw_data( $data['content'], true );
			}
		}

		return $data;
	}


	/**
	 * Used in custom import logic
	 *
	 * @param $template_data
	 *
	 * @return bool|false|int|\WP_Error
	 */
	public function import_item( $template_data ) {

		$defaults = [
			'title'         => __( '(no title)', 'elementor' ),
			'page_settings' => [],
			'status'        => current_user_can( 'publish_posts' ) ? 'publish' : 'pending',
		];

		$template_data = wp_parse_args( $template_data, $defaults );

		$document = Plugin::$instance->documents->create(
			$template_data['type'],
			[
				'post_title'  => $template_data['title'],
				'post_status' => $template_data['status'],
				'post_type'   => self::CPT,
			]
		);

		if ( is_wp_error( $document ) ) {
			/**
			 * @var \WP_Error $document
			 */
			return $document;
		}

		if ( ! empty( $template_data['content'] ) ) {
			$template_data['content'] = $this->replace_elements_ids( $template_data['content'] );
		}

		$document->save(
			[
				'elements' => $template_data['content'],
				'settings' => $template_data['page_settings'],
			]
		);

		// Stax Set subtype
		if ( $template_data['subtype'] ) {
			$document->update_meta( $document::REMOTE_CATEGORY_META_KEY, $template_data['subtype'] );
			self::$imported_templates[ $template_data['subtype'] ] = $document->get_main_id();
		}

		return $document->get_main_id();

	}

}
