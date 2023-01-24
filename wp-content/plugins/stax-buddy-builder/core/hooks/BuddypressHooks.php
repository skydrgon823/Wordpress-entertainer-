<?php
/**
 * Add Buddypress Hooks
 *
 * @package Buddy_Builder
 * @since 1.0.0
 */

namespace Buddy_Builder;

defined( 'ABSPATH' ) || die();

use Buddy_Builder\Library\Documents\BuddyPress;
use Buddy_Builder\Template\Module;

/**
 * Class BuddypressHooks
 *
 * @package Buddy_Builder
 */
class BuddypressHooks extends Singleton {

	/**
	 * BuddypressHooks constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ], 1 );
		add_action( 'bp_init', [ $this, 'bp_init' ] );

		add_filter( 'bp_get_template_stack', [ $this, 'rewrite_template' ], ( PHP_INT_MAX - 1 ) );
		add_filter( 'bp_get_theme_package_id', [ $this, 'rewrite_theme_id' ], ( PHP_INT_MAX - 1 ) );
		add_filter( 'option__bp_theme_package_id', [ $this, 'rewrite_theme_id' ], ( PHP_INT_MAX - 1 ) );
		add_filter( 'template_include', [ $this, 'change_buddypress_tpl_for_edit' ], ( PHP_INT_MAX - 1 ) ); // maybe remove?
		add_filter( 'rtmedia_located_template', [ $this, 'override_rtmedia_template' ], 10, 4 );

		add_filter( 'body_class', [ $this, 'set_body_class' ] );
		add_filter( 'bp_nouveau_customizer_controls', [ $this, 'customizer_controls' ], 20 );
		add_filter( 'bp_after_nouveau_appearance_settings_parse_args', [ $this, 'default_customizer_options' ] );
		add_filter( 'bp_nouveau_get_loop_classes', [ $this, 'loop_classes' ], 10, 2 );
		add_action( 'the_content', [ $this, 'set_content_preview' ], ( PHP_INT_MAX - 1 ) );

		if ( version_compare( bp_get_version(), '6.0.0', '>=' ) ) {
			add_filter( 'bp_after_members_cover_image_settings_parse_args', [ $this, 'change_cover_args' ] );
		} else {
			add_filter( 'bp_after_xprofile_cover_image_settings_parse_args', [ $this, 'change_cover_args' ] );
		}
		add_filter( 'bp_after_groups_cover_image_settings_parse_args', [ $this, 'change_cover_args' ] );

		add_filter( 'bp_ajax_querystring', [ $this, 'filter_bp_ajax_querystring' ], 10, 2 );
	}

	/**
	 * Override RTmedia template
	 *
	 * @param $located
	 * @param $url
	 * @param $ogpath
	 * @param $template_name
	 * @return void
	 */
	public function override_rtmedia_template( $located, $url, $ogpath, $template_name ) {
		if ( $template_name === 'main.php' && $this->current_component_has_template() ) {
			if ( $url ) {
				return $this->get_template_url() . 'rtmedia/main.php';
			}

			return $this->get_template_path() . 'rtmedia/main.php';

		}

		return $located;
	}

	/**
	 * Plugins loaded
	 */
	public function plugins_loaded() {
		if ( ! $this->current_component_has_template() ) {
			return;
		}

		// theme compat
		add_action(
			'after_setup_theme',
			function () {
				remove_action( 'widgets_init', 'crum_mycred_user_balance_wp_widget' );
				remove_action( 'wp_enqueue_scripts', 'sportix_print_bp_styles', 99 );
			}
		);

		// Stop Youzer functionality for the component.
		remove_action( 'plugins_loaded', 'youzer', YOUZER_LATE_LOAD );

		add_action( 'after_setup_theme', [ $this, 'add_nouveau_compat' ], 50 );
	}

	/**
	 * Buddypress Init
	 */
	public function bp_init() {
		if ( ! $this->current_component_has_template() ) {
			return;
		}

		// Get Templates Location from our plugin
		if ( function_exists( 'bp_register_template_stack' ) ) {
			bp_register_template_stack( [ $this, 'get_template_path' ], - 1 );
			bp_register_template_stack( [ $this, 'get_child_theme_template_path' ], - 2 );
		}
	}

	/**
	 * BuddyPress template path
	 *
	 * @return string
	 */
	public function get_template_path() {
		return BPB_BASE_PATH . 'templates/buddypress/';
	}

	/**
	 * BuddyPress template url
	 *
	 * @return string
	 */
	public function get_template_url() {
		return BPB_BASE_URL . 'templates/buddypress/';
	}

	/**
	 * BuddyPress templats path
	 *
	 * @return string
	 */
	public function get_child_theme_template_path() {
		return get_stylesheet_directory() . '/buddybuilder/';
	}

	/**
	 *  Make sure we use Nouveau template
	 */
	public function add_nouveau_compat() {
		add_theme_support( 'buddypress-use-nouveau' );
		remove_theme_support( 'buddypress-use-legacy' );
	}

	/**
	 * Cover image args
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	public function change_cover_args( $args ) {
		$args['theme_handle'] = 'stax-buddy-builder-bp';
		$args['width']        = 1300;
		$args['height']       = 900;

		return $args;
	}

	/**
	 * Force our template path
	 *
	 * @param $stack
	 *
	 * @return mixed
	 */
	public function rewrite_template( $stack ) {
		if ( $this->current_component_has_template() ) {
			$theme_path = get_template_directory();
			$key        = array_search( $theme_path . '/buddypress', $stack, true );

			if ( $key !== false ) {
				unset( $stack[ $key ] );
			}

			array_unshift( $stack, BPB_BASE_PATH . 'templates/buddypress' );
			array_unshift( $stack, get_stylesheet_directory() . '/buddybuilder' );
		}

		return $stack;
	}

	/**
	 * Force nouveau template
	 *
	 * @param $pack
	 *
	 * @return string
	 */
	public function rewrite_theme_id( $pack ) {
		$importing = isset( $_REQUEST['action'] ) && $_REQUEST['action'] === Module::IMPORT_KEY;

		// on member profile in AJAX needs to be nouveau for some reason.
		if ( $importing || $this->current_component_has_template() || wp_doing_ajax() ) {
			return 'nouveau';
		}

		return $pack;
	}

	/**
	 * Change the template when editing and previewing to buddybuilder one
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function change_buddypress_tpl_for_edit( $template ) {
		if ( $this->current_component_has_template( false, true ) && bpb_is_preview_mode() ) {
			$template = BPB_BASE_PATH . 'templates/buddypress/buddypress.php';
		}

		return $template;
	}

	/**
	 * Override loop classes
	 *
	 * @param $classes
	 * @param $component
	 *
	 * @return array
	 */
	public function loop_classes( $classes, $component ) {
		$listing_columns = bpb_get_listing_columns();
		$components      = [ 'members', 'groups' ];

		if ( in_array( $component, $components, true ) ) {
			foreach ( $classes as $key => $class ) {
				if ( in_array( $class, [ 'grid', 'two', 'three', 'four' ] ) ) {
					unset( $classes[ $key ] );
				}
			}
		}

		if ( $component === 'members' ) {
			$classes[] = 'grid';
			foreach ( $listing_columns['members_directory'] as $type => $list_class ) {
				$classes[] = bpb_get_column_class( $list_class, $type );
			}
		}

		if ( $component === 'groups' ) {
			$classes[] = 'grid';
			foreach ( $listing_columns['groups_directory'] as $type => $list_class ) {
				$classes[] = bpb_get_column_class( $list_class, $type );
			}
		}

		return $classes;
	}

	/**
	 * @param $content
	 *
	 * @return false|string
	 */
	public function set_content_preview( $content ) {
		global $post;

		$template_type = get_post_meta( $post->ID, BuddyPress::REMOTE_CATEGORY_META_KEY, true );

		if ( ! $template_type ) {
			return $content;
		}

		if ( ! bpb_is_elementor_editor() && ! array_key_exists( $template_type, bpb_get_template_types() ) ) {
			return $content;
		}

		ob_start();

		bpb_load_template( 'preview/wrapper', [ 'content' => $content ] );

		return apply_filters( 'buddy_builder/preview/content', ob_get_clean(), $template_type, $content );
	}

	/**
	 * @param $classes
	 *
	 * @return array
	 */
	public function set_body_class( $classes ) {
		global $post;

		if ( ! $post ) {
			return $classes;
		}

		$template_type = get_post_meta( $post->ID, BuddyPress::REMOTE_CATEGORY_META_KEY, true );

		if ( ! $template_type ) {
			return $classes;
		}

		if ( $template_type && ! bpb_is_elementor_editor() && ! array_key_exists( $template_type, bpb_get_template_types() ) ) {
			return $classes;
		}

		$classes[] = 'buddypress';
		$classes[] = 'members';
		$classes[] = 'directory';
		$classes[] = 'stax-buddybuilder-template';

		return $classes;
	}

	/**
	 * Remove Buddypress Customizer options
	 *
	 * @param $controls
	 *
	 * @return mixed
	 */
	public function customizer_controls( $controls ) {
		unset(
			$controls['members_layout'],
			$controls['groups_layout'],
			$controls['bp_site_avatars'],
			$controls['group_front_boxes'],
			$controls['group_front_description'],
			$controls['user_front_bio'],
			$controls['group_nav_display'],
			$controls['group_nav_tabs'],
			$controls['group_subnav_tabs'],
			$controls['user_nav_display'],
			$controls['user_nav_tabs'],
			$controls['user_subnav_tabs'],
			$controls['members_dir_layout'],
			$controls['members_dir_tabs'],
			$controls['act_dir_layout'],
			$controls['act_dir_tabs'],
			$controls['group_dir_layout'],
			$controls['group_dir_tabs']
		);

		return $controls;
	}

	/**
	 * Force default values for Buddypress Customizer options
	 *
	 * @param $options
	 *
	 * @return array
	 */
	public function default_customizer_options( $options ) {
		return array_merge(
			$options,
			[
				'avatar_style'            => 0,
				'user_front_bio'          => 0,
				'user_nav_display'        => 0,
				'user_nav_tabs'           => 0,
				'user_subnav_tabs'        => 0,
				'members_dir_tabs'        => 0,
				'members_dir_layout'      => 0,
				'activity_dir_layout'     => 0,
				'activity_dir_tabs'       => 0,
				'group_front_boxes'       => 0,
				'group_front_description' => 0,
				'group_nav_display'       => 0,
				'group_nav_tabs'          => 0,
				'group_subnav_tabs'       => 0,
				'groups_dir_layout'       => 0,
				'groups_dir_tabs'         => 0,
				'sites_dir_layout'        => 0,
				'sites_dir_tabs'          => 0,
				'global_alignment'        => '',
			]
		);
	}

	/**
	 * Check if current Buddypress component has Elementor template
	 *
	 * @param bool $with_filter
	 * @param bool $strict
	 *
	 * @return bool
	 */
	private function current_component_has_template( $with_filter = true, $strict = false ) {

		// short circuit and force
		if ( $with_filter && apply_filters( 'buddy_builder/has_template/pre', false ) ) {
			return true;
		}

		// Return true when editing or previewing.
		if ( bpb_is_edit_frame() || bpb_is_preview_mode() || bpb_is_front_library() ) {
			return true;
		}

		if ( is_admin() && ! wp_doing_ajax() ) {
			return false;
		}

		if ( isset( $_GET['elementor-preview'], $_GET['elementor_library'] ) ) {
			return false;
		}

		$settings = bpb_get_settings();
		$settings = $settings['templates'];

		if ( ! bp_current_component() ) {

			// Try tp get the component from ajax request.
			if ( wp_doing_ajax() ) {
				if ( isset( $_POST['object'] ) ) {

					// Members loop && groups loop
					if ( $_POST['object'] === 'members' && $settings['members-directory'] ) {
						return bpb_is_template_id_populated( $settings['members-directory'] );
					}

					if ( $_POST['object'] === 'groups' && $settings['groups-directory'] ) {
						return bpb_is_template_id_populated( $settings['groups-directory'] );
					}

					if ( $_POST['object'] === 'activity' && $settings['sitewide-activity'] ) {
						return bpb_is_template_id_populated( $settings['sitewide-activity'] );
					}
				} else {
					return true;
				}
			} else {
				$slug = $this->get_page_slug();

				if ( empty( $slug ) ) {
					return false;
				}

				$bp_pages_ids = get_option( 'bp-pages' );
				$bp_pages_ids = array_map( [ $this, 'get_wpml_page_id' ], $bp_pages_ids );

				$bp_slugs = [
					'members'  => '',
					'groups'   => '',
					'activity' => '',
				];

				// Set page slugs.
				foreach ( $bp_pages_ids as $k => $bp_page_id ) {
					$bp_page        = get_post( $bp_pages_ids[ $k ] );
					$bp_slugs[ $k ] = $bp_page->post_name;
				}

				// Members directory.
				if ( $slug === $bp_slugs['members'] ) {
					if ( $settings['members-directory'] ) {
						return bpb_is_template_id_populated( $settings['members-directory'] );
					}

					return false;
				}

				if ( strpos( $slug, $bp_slugs['members'] ) !== false ) {
					if ( $settings['member-profile'] ) {
						return bpb_is_template_id_populated( $settings['member-profile'] );
					}

					return false;
				}

				// Groups directory.
				if ( empty( $bp_slugs['groups'] ) ) {
					return false;
				}

				if ( $slug === $bp_slugs['groups'] ) {
					if ( $settings['groups-directory'] ) {
						return bpb_is_template_id_populated( $settings['groups-directory'] );
					}

					return false;
				}

				if ( strpos( $slug, $bp_slugs['groups'] ) !== false ) {
					if ( $settings['group-profile'] ) {
						return bpb_is_template_id_populated( $settings['group-profile'] );
					}

					return false;
				}

				// Site-wide activity.
				if ( $slug === $bp_slugs['activity'] ) {
					if ( $settings['sitewide-activity'] ) {
						return bpb_is_template_id_populated( $settings['sitewide-activity'] );
					}

					return false;
				}
			}
		} else {
			// Members directory.
			if ( $settings['members-directory'] && bp_is_members_directory() ) {
				return bpb_is_template_id_populated( $settings['members-directory'] );
			}

			// Groups directory.
			if ( $settings['groups-directory'] && ! empty( bp_is_active( 'groups' ) ) && bp_is_groups_directory() ) {
				return bpb_is_template_id_populated( $settings['groups-directory'] );
			}

			// Member profile.
			if ( $settings['member-profile'] && bp_is_user() ) {
				if ( ( ! isset( $settings['sitewide-activity-item'] ) || ! $settings['sitewide-activity-item'] ) &&
					 bp_current_component() === 'activity' &&
					 is_numeric( bp_current_action() ) ) {
					return false;
				}

				return bpb_is_template_id_populated( $settings['member-profile'] );
			}

			// Group profile.
			if ( $settings['group-profile'] && ! empty( bp_is_active( 'groups' ) ) && bp_is_single_item() && bp_is_groups_component() ) {
				return bpb_is_template_id_populated( $settings['group-profile'] );
			}

			// Site-wide activity.
			if ( $settings['sitewide-activity'] && ! empty( bp_is_active( 'activity' ) ) && bp_is_activity_directory() ) {
				return bpb_is_template_id_populated( $settings['sitewide-activity'] );
			}

			// Register page.
			if ( $settings['sitewide-activity'] ) {
				return bpb_is_template_id_populated( $settings['register-page'] );
			}
		}

		// If we are anywhere else in WP, then we can load our logic for general widgets.
		return ! $strict && bp_is_blog_page();
	}

	/**
	 * Filter ajax query strings
	 *
	 * @param object $query
	 * @param object $object
	 * @return object
	 */
	public function filter_bp_ajax_querystring( $query, $object ) {
		if ( isset( $_REQUEST['bpb-list-mode'] ) && $_REQUEST['bpb-list-mode'] === 'list' && ! isset( $_REQUEST['bpb-list-hidden'] ) ) {
			add_filter(
				'bp_nouveau_get_loop_classes',
				function ( $classes = [] ) {
					$classes[] = 'grid-one-force';
					return $classes;
				}
			);
		}

		return $query;
	}

	/**
	 * Get WPML page ID
	 *
	 * @param int $id
	 * @return int
	 */
	public function get_wpml_page_id( $id ) {
		if ( function_exists( 'wpml_object_id_filter' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
			return wpml_object_id_filter( $id, 'page', true, ICL_LANGUAGE_CODE );
		}

		return $id;
	}

	/**
	 * Get the current page url
	 *
	 * @return string
	 */
	public function get_page_slug() {
		if ( ! isset( $_SERVER['SERVER_NAME'] ) ) {
			global $wp;

			$url = home_url( $wp->request );
		} else {
			if ( empty( $_SERVER['HTTPS'] ) ) {
				$s = '';
			} elseif ( $_SERVER['HTTPS'] === 'on' ) {
				$s = 's';
			} else {
				$s = '';
			}

			$protocol = strtolower( substr( $_SERVER['SERVER_PROTOCOL'], 0, strpos( $_SERVER['SERVER_PROTOCOL'], '/' ) ) ) . $s;
			$uri      = $protocol . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			$segments = explode( '?', $uri, 2 );
			$url      = $segments[0];
		}

		$home_url = home_url( '/' );
		$home_url = str_replace( [ 'www.', 'https://', 'http://' ], '', $home_url );
		$url      = str_replace( [ 'www.', 'https://', 'http://', $home_url ], '', $url );
		$url      = rtrim( $url, '/' );

		return $url;
	}

}
