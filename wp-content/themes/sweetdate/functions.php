<?php
/**
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */
global $kleo_config;
$kleo_config['image_sizes'] = array( 'blog_carousel' => array( 'width' => 310, 'height' => 177 ) );

//From which profile field to show member details on members directory page
$kleo_config['bp_members_details_field'] = 'About me';

/* Include theme constants */
require_once( get_template_directory() . '/framework/constants.php' );

/* Include our Framework logic */
require_once( FRAMEWORK_PATH . '/load.php' );


if ( ! isset( $content_width ) ) {
	$content_width = 980;
}

/**
 * Sets up theme defaults and registers the various WordPress features
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 *    custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Sweetdate 1.0
 */
function sweetdate_setup() {
	global $kleo_config;
	/*
	 * Makes Sweetdate available for translation.
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'sweetdate', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'structured-post-formats', array( 'link', 'video' ) );
	add_theme_support( 'post-formats', array(
		'aside',
		'audio',
		'gallery',
		'image',
		'quote',
		'status',
		'link',
		'video'
	) );

	add_theme_support( 'bbpress' );
	add_theme_support( 'woocommerce' );

	add_theme_support( 'title-tag' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', esc_html__( 'Primary Menu', 'sweetdate' ) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 938, 9999 ); // Unlimited height, soft crop 
	add_image_size( 'blog_carousel', $kleo_config['image_sizes']['blog_carousel']['width'], $kleo_config['image_sizes']['blog_carousel']['height'], true ); // hard crop for articles carousel
}

add_action( 'after_setup_theme', 'sweetdate_setup', 8 );


if ( ! function_exists( '_wp_render_title_tag' ) ) {
	function kleo_slug_render_title() {
		?>
        <title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}

	add_action( 'wp_head', 'kleo_slug_render_title' );
}


/***************************************************
 * JS load
 ***************************************************/

add_action( 'wp_enqueue_scripts', 'sq_load_assets_files' );

// Register some javascript files
function sq_load_assets_files() {

	$min = sq_option( 'dev_mode', 0 ) == 1 ? '' : '.min';

	/* Javascript files */

	//header - modernizr.foundation
	wp_register_script( 'modernizr', get_template_directory_uri() . '/assets/scripts/modernizr.foundation.js' );

	//footer
	wp_register_script( 'foundation', get_template_directory_uri() . '/assets/scripts/foundation.min.js', array( 'jquery' ), SQUEEN_THEME_VERSION, true );
	wp_register_script( 'jquery-tweet', '//platform.twitter.com/widgets.js', array(), '', true );
	wp_register_script( 'scripts', get_template_directory_uri() . '/assets/scripts/scripts' . $min . '.js', array( 'jquery' ), SQUEEN_THEME_VERSION, true );
	//mediaelement - audio video
	wp_register_script( 'mediaelement', get_template_directory_uri() . '/assets/scripts/plugins/mediaelement/build/mediaelement-and-player.min.js', array( 'jquery' ), SQUEEN_THEME_VERSION, true );
	//autocomplete - jquery ui
	wp_register_script( 'jquery-ui-autocomplete', get_template_directory_uri() . '/assets/scripts/plugins/jquery-ui-1.10.3.custom.min.js', array( 'jquery' ), '1.10.3', true );
	// Custom JS effects, tweaks and inits
	wp_register_script( 'app', get_template_directory_uri() . '/assets/scripts/app' . $min . '.js', array(
		'jquery',
		'scripts'
	), SQUEEN_THEME_VERSION, true );

	//enque them
	wp_enqueue_script( 'modernizr' );
	wp_enqueue_script( 'foundation' );
	wp_enqueue_script( 'scripts' );
	wp_enqueue_script( 'app' );

	$obj_array = array(
		'blank_img'      => get_template_directory_uri() . "/assets/images/blank.png",
		'ajaxurl'        => admin_url( 'admin-ajax.php' ),
		'mainColor'      => sq_option( 'bp_header_secondary_color' ),
		'bpMatchBg'      => sq_option( 'bp_match_bg_color', '' ),
		'bpMatchFg'      => sq_option( 'bp_match_fg_color', '' ),
		'tosAlert'       => apply_filters( 'kleo_fb_tos_alert', esc_html__( "You must agree with the terms and conditions.", 'sweetdate' ) ),
		'loadingmessage' => '<i class="icon icon-refresh icon-spin"></i> ' . esc_html__( 'Sending info, please wait...', 'sweetdate' ),
	);
	if ( function_exists( 'bp_is_active' ) ) {
		$obj_array['totalMembers'] = bp_get_total_member_count();
	}

	/* Deprecated - typo */
	//$obj_array = apply_filters( 'kleo_sript_localize_array', $obj_array );

	/* New filter */
	$obj_array = apply_filters( 'kleo_script_localize_array', $obj_array );

	wp_localize_script( 'app', 'kleoFramework', $obj_array );

	$foundation_array = array(
		'back' => esc_html__( "Back", 'sweetdate' )
	);
	wp_localize_script( 'foundation', 'foundTranslated', $foundation_array );


	/* Load CSS */

	// Register the style like this for a theme:
	wp_register_style( 'foundation', get_template_directory_uri() . '/assets/styles/foundation-nonresponsive' . $min . '.css', array(), SQUEEN_THEME_VERSION, 'all' );
	wp_register_style( 'foundation-responsive', get_template_directory_uri() . '/assets/styles/responsive' . $min . '.css', array(), SQUEEN_THEME_VERSION, 'all' );

	//Plugins
	wp_register_style( 'sq-font-awesome', get_template_directory_uri() . '/assets/styles/font-awesome.min.css', array(), SQUEEN_THEME_VERSION, 'all' );
	wp_register_style( 'prettyPhoto', get_template_directory_uri() . '/assets/styles/prettyPhoto' . $min . '.css', array(), SQUEEN_THEME_VERSION, 'all' );
	//Mediaelement
	wp_register_style( 'mediaelement', get_template_directory_uri() . '/assets/scripts/plugins/mediaelement/build/mediaelementplayer' . $min . '.css', array(), SQUEEN_THEME_VERSION, 'all' );
	//Main Stylesheet
	wp_register_style( 'app', get_template_directory_uri() . '/assets/styles/app' . $min . '.css', array(), SQUEEN_THEME_VERSION, 'all' );
	wp_register_style( 'kleo-rtl', get_template_directory_uri() . '/rtl.css', array(), SQUEEN_THEME_VERSION, 'all' );
	wp_register_style( 'sweet-style', get_stylesheet_directory_uri() . '/style.css', array(), SQUEEN_THEME_VERSION, 'all' );

	//enque them
	wp_enqueue_style( 'foundation' );
	wp_enqueue_style( 'sq-font-awesome' );
	wp_enqueue_style( 'prettyPhoto' );

	//main stylesheet
	wp_enqueue_style( 'app' );

	//enable/disable responsive
	if ( sq_option( 'responsive_design' ) == 1 ) {
		wp_enqueue_style( 'foundation-responsive' );
	}

	//enqueue child theme style only if activated
	if ( is_child_theme() ) {
		if ( is_rtl() ) {
			wp_enqueue_style( 'kleo-rtl' );
		}
	}

}


add_action( 'wp_enqueue_scripts', 'sweet_load_css_files_plugin_compat', 1000 );

function sweet_load_css_files_plugin_compat() {

	//enqueue child theme style only if activated
	if ( is_child_theme() ) {
		wp_enqueue_style( 'sweet-style' );
	}


} // sweet_load_css_files_plugin_compat()


/***************************************************
 * Adds JavaScript to pages with the comment form
 * to support sites with threaded comments (when in use).
 ***************************************************/
function sweetdate_comment_script() {
	global $wp_styles;

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}

add_action( 'wp_enqueue_scripts', 'sweetdate_comment_script' );


/***************************************************
 * :: Render the header section with the menus
 ***************************************************/

function sweetdate_show_header() {
	get_template_part( 'page-parts/general-header' );
}

add_action( 'sweetdate_header', 'sweetdate_show_header' );

/* Load the framework functions. */
add_action( 'after_setup_theme', 'kleo_framework_functions', 9 );

/**
 * Adds theme functionality
 */
function kleo_framework_functions() {

	/* Include Buddypress functions */
	if ( function_exists( 'bp_is_active' ) ) {
		locate_template( 'custom_buddypress/bp-functions.php', true );
	}

	/* Include Woocommerce functions */
	if ( function_exists( 'is_woocommerce' ) && in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		locate_template( 'lib/plugins/woocommerce/config.php', true );
	}

	// Compatibility Bp Profile Search
	if ( defined( 'BPS_VERSION' ) ) {
		locate_template( 'lib/plugins/bp-profile-search/config.php', true );
	}

	/* Paid memberships pro integration */
	if ( function_exists( 'pmpro_url' ) ) {
		locate_template( 'lib/plugins/pmpro/config.php', true );
	}

	if ( defined( 'ELEMENTOR_PATH' ) ) {
		locate_template( 'lib/plugins/elementor/config.php', true );
	}
}


if ( ! function_exists( '_wp_render_title_tag' ) ) {
	function kleo_slug_render_title() {
		?>
        <title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}

	add_action( 'wp_head', 'kleo_slug_render_title' );
}

if ( ! function_exists( 'sweetdate_wp_title' ) ) {
	/**
	 * Creates a nicely formatted and more specific title element text
	 * for output in head of document, based on current view.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 *
	 * @return string Filtered title.
	 * @since Kleo Framework 1.0
	 *
	 */
	function sweetdate_wp_title( $title, $sep ) {
		global $paged, $page;

		if ( is_feed() ) {
			return $title;
		}
		// Add the site name.
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title = "$title $sep $site_description";
		}
		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 ) {
			$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'sweetdate' ), max( $paged, $page ) );
		}

		return $title;
	}

	if ( ! function_exists( '_wp_render_title_tag' ) ) {
		add_filter( 'wp_title', 'sweetdate_wp_title', 10, 2 );
	}
}


if ( ! function_exists( 'sweetdate_main_nav' ) ) :
	/**
	 * wp_nav_menu() callback from the main navigation in header.php
	 *
	 * Used when the custom menus haven't been configured.
	 *
	 * @param array $args arguments from wp_nav_menu()
	 *
	 * @see wp_nav_menu()
	 * @since BuddyPress (1.5)
	 */
	function sweetdate_main_nav( $args ) {
		$pages_args = array(
			'depth'    => 0,
			'echo'     => false,
			'exclude'  => '',
			'title_li' => ''
		);
		$menu       = wp_page_menu( $pages_args );
		$menu       = str_replace( array(
			'<div class="menu"><ul>',
			'</ul></div>'
		), array(
			'<ul class="right"><li><a href="' . esc_url( home_url( '/' ) ) . '"><i class="icon-home"></i> ' . esc_html__( "HOME", 'sweetdate' ) . '</a></li>',
			'</ul>'
		), $menu );
		echo $menu; // PHPCS: XSS ok.

		do_action( 'bp_nav_items' );
	}
endif;

//------------------------------------------------------------------------------


/**
 * Modify some elements for the menu
 */
if ( ! class_exists( 'sweetdate_walker_nav_menu' ) ) :
	class sweetdate_walker_nav_menu extends Walker_Nav_Menu {

		// add classes to ul sub-menus
		function start_lvl( &$output, $depth = 0, $args = array() ) {
			// depth dependent classes
			$indent        = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
			$display_depth = ( $depth + 1 ); // because it counts the first submenu as 0
			$classes       = array(
				'dropdown'
			);
			$class_names   = implode( ' ', $classes );

			// build html
			$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
		}

		function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
			$id_field = $this->db_fields['id'];
			if ( is_object( $args[0] ) ) {
				$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
			}

			return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}


		// add main/sub classes to li's and links
		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			global $wp_query;
			$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

			// depth dependent classes
			$depth_classes     = array(
				( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
				( $depth >= 2 ? 'sub-sub-menu-item' : '' ),
				( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
				'menu-item-depth-' . $depth
			);
			$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

			// passed classes
			$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
			$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

			// build html
			//$output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';
			$output .= $indent . '<li id="nav-menu-item-' . $item->ID . '" ' . ( $args->has_children > 0 ? 'class="has-dropdown"' : '' ) . '>';

			// link attributes
			$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
			$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
			$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
			$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
			$attributes .= ' class="' . $class_names . ( $depth > 0 ? ' sub-menu-link' : ' main-menu-link' ) . '"';

			if ( $args->has_children ) {
				$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
					$args->before,
					$attributes,
					$args->link_before,
					apply_filters( 'the_title', $item->title, $item->ID ),
					$args->link_after,
					$args->after
				);
			} else {
				$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
					$args->before,
					$attributes,
					$args->link_before,
					apply_filters( 'the_title', $item->title, $item->ID ),
					$args->link_after,
					$args->after
				);
			}
			// build html
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
endif;
//------------------------------------------------------------------------------


if ( ! function_exists( 'sweetdate_widgets_init' ) ):
	/**
	 * Registers our main widget area and the front page widget areas.
	 *
	 * @since Twenty Twelve 1.0
	 */
	function sweetdate_widgets_init() {
		register_sidebar( array(
			'name'          => esc_html__( 'Main Sidebar', 'sweetdate' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Default sidebar', 'sweetdate' ),
			'before_widget' => '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		) );
		register_sidebar( array(
			'name'          => 'Footer Widget 1',
			'id'            => 'footer-1',
			'before_widget' => '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		) );

		register_sidebar( array(
			'name'          => 'Footer Widget 2',
			'id'            => 'footer-2',
			'before_widget' => '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		) );

		register_sidebar( array(
			'name'          => 'Footer Widget 3',
			'id'            => 'footer-3',
			'before_widget' => '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		) );

		register_sidebar( array(
			'name'          => 'Footer Widget 4',
			'id'            => 'footer-4',
			'before_widget' => '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		) );

		register_sidebar( array(
			'name'          => 'Footer Level 1 - Widget 1',
			'id'            => 'footer-level1-1',
			'before_widget' => '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		) );

		register_sidebar( array(
			'name'          => 'Footer Level 1 - Widget 2',
			'id'            => 'footer-level1-2',
			'before_widget' => '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		) );

		register_sidebar( array(
			'name'          => 'Footer Level 2',
			'id'            => 'footer-level-2',
			'before_widget' => '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		) );
		register_sidebar( array(
			'name'          => 'Shop Sidebar',
			'id'            => 'shop-1',
			'before_widget' => '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		) );
		register_sidebar( array(
			'name'          => 'Extra - for 3 columns pages',
			'id'            => 'extra',
			'before_widget' => '<div id="%1$s" class="widgets clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		) );

	}
endif;

add_action( 'widgets_init', 'sweetdate_widgets_init' );

//------------------------------------------------------------------------------


/***************************************************
 * :: ADMIN CSS
 ***************************************************/
function kleo_admin_styles() {
	wp_register_style( "kleo-admin", FRAMEWORK_HTTP . "/inc/kleo-admin.css", array(), "1.0", "all" );
	wp_enqueue_style( 'kleo-admin' );
}

add_action( 'admin_enqueue_scripts', 'kleo_admin_styles' );


if ( ! function_exists( 'sweetdate_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own sweetdate_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Sweetdate 1.0
	 */
	function sweetdate_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				// Display trackbacks differently than normal comments.
				?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                <p><?php esc_html_e( 'Pingback:', 'sweetdate' ); ?><?php comment_author_link(); ?>
					<?php edit_comment_link( esc_html__( '(Edit)', 'sweetdate' ), '<span class="edit-link">', '</span>' ); ?>
                </p>
				<?php
				break;
			default :
				// Proceed with normal comments.
				global $post;
				?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

				<?php
				echo '<div class="avatar">' . get_avatar( $comment, 94 ) . '</div>';
				echo '<div class="comment-meta"><h5 class="author">' . get_comment_author_link() . '</h5>';
				echo '<p class="date">' . sprintf( esc_html__( '%1$s at %2$s', 'sweetdate' ), get_comment_date(), get_comment_time() ) . '</p></div>';
				?>
                <div class="comment-body">
					<?php comment_text(); ?>
					<?php edit_comment_link( esc_html__( 'Edit', 'sweetdate' ), '<p class="edit-link">', '</p>' ); ?>
                </div>

                <div class="reply">
					<?php comment_reply_link( array_merge( $args, array(
						'reply_text' => esc_html__( 'Reply', 'sweetdate' ),
						'after'      => ' <span>&darr;</span>',
						'depth'      => $depth,
						'max_depth'  => $args['max_depth']
					) ) ); ?>
                </div><!-- .reply -->
				<?php if ( '0' == $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'sweetdate' ); ?></p>
			<?php endif; ?>

				<?php
				break;
		endswitch; // end comment_type check
	}
endif;


//------------------------------------------------------------------------------


/**
 * Customize comment reply form
 *
 */
add_filter( 'comment_form_default_fields', 'kleo_comment_field_changes' );
if ( ! function_exists( 'kleo_comment_field_changes' ) ):
	function kleo_comment_field_changes( $arg ) {

		$commenter     = wp_get_current_commenter();
		$user          = wp_get_current_user();
		$user_identity = $user->exists() ? $user->display_name : '';

		$req      = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$arg['author'] = '<div class="row"><div class="six columns">' . '<label for="author">' . esc_html__( 'Name', 'sweetdate' ) . ( $req ? ' <span class="required"> (' . esc_html__( "required", 'sweetdate' ) . ')</span>' : '' ) . '</label> ' .
		                 '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . ( $req ? ' required' : '' ) . '></div>';

		$arg['email'] = '<div class="six columns"><label for="email">' . esc_html__( 'Email', 'sweetdate' ) . ( $req ? ' <span class="required"> (' . esc_html__( "required", 'sweetdate' ) . ')</span>' : '' ) . '</label> ' .
		                '<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . ( $req ? ' required' : '' ) . '></div></div>';
		$arg['url']   = '';

		return $arg;
	}
endif;

add_filter( 'comment_form_defaults', 'kleo_comment_changes' );
if ( ! function_exists( 'kleo_comment_changes' ) ):
	function kleo_comment_changes( $arg ) {
		$arg['label_submit']         = esc_html__( 'Send Message', 'sweetdate' );
		$arg['comment_notes_before'] = '';
		$arg['comment_notes_after']  = '';
		$arg['comment_field']        = '<div class="row"><div class="twelve columns"><label for="comment">' . esc_html__( 'Comment', 'sweetdate' ) . ' (' . esc_html__( "required", 'sweetdate' ) . ')</label>
		<textarea id="comment" name="comment" cols="45" rows="8" required aria-required="true"></textarea></div></div>';

		return $arg;
	}
endif;


//------------------------------------------------------------------------------

if ( ! function_exists( 'kleo_comment_form' ) ):
	/**
	 * Outputs a complete commenting form for use within a template.
	 * Most strings and form fields may be controlled through the $args array passed
	 * into the function, while you may also choose to use the comment_form_default_fields
	 * filter to modify the array of default fields if you'd just like to add a new
	 * one or remove a single field. All fields are also individually passed through
	 * a filter of the form comment_form_field_$name where $name is the key used
	 * in the array of fields.
	 *
	 * @param array $args Options for strings, fields etc in the form
	 * @param mixed $post_id Post ID to generate the form for, uses the current post if null
	 *
	 * @return void
	 */
	function kleo_comment_form( $args = array(), $post_id = null ) {
		global $id;

		if ( null === $post_id ) {
			$post_id = $id;
		} else {
			$id = $post_id;
		}

		$commenter     = wp_get_current_commenter();
		$user          = wp_get_current_user();
		$user_identity = $user->exists() ? $user->display_name : '';

		$req      = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$fields   = array(
			'author' => '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'sweetdate' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
			            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'sweetdate' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
			            '<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
			'url'    => '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'sweetdate' ) . '</label>' .
			            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
		);

		$required_text = sprintf( ' ' . esc_html__( 'Required fields are marked %s', 'sweetdate' ), '<span class="required">*</span>' );
		$defaults      = array(
			'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
			'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'sweetdate' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
			'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'sweetdate' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
			'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'sweetdate' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
			'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Your email address will not be published.', 'sweetdate' ) . ( $req ? $required_text : '' ) . '</p>',
			'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'sweetdate' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
			'id_form'              => 'commentform',
			'id_submit'            => 'submit',
			'title_reply'          => esc_html__( 'Leave a Reply', 'sweetdate' ),
			'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'sweetdate' ),
			'cancel_reply_link'    => esc_html__( 'Cancel reply', 'sweetdate' ),
			'label_submit'         => esc_html__( 'Post Comment', 'sweetdate' ),
		);

		$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

		?>
		<?php if ( comments_open( $post_id ) ) : ?>
			<?php do_action( 'comment_form_before' ); ?>
            <div id="respond">
                <h4 id="reply-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?>
                    <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small>
                </h4>
                <br>
				<?php if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) : ?>
					<?php echo wp_kses_post( $args['must_log_in'] ); ?>
					<?php do_action( 'comment_form_must_log_in_after' ); ?>
				<?php else : ?>
                    <form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post"
                          id="<?php echo esc_attr( $args['id_form'] ); ?>" class="leave-comment clearfix">
						<?php do_action( 'comment_form_top' ); ?>
						<?php if ( is_user_logged_in() ) : ?>
							<?php echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
							<?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
						<?php else : ?>
							<?php echo wp_kses_post( $args['comment_notes_before'] ); ?>
							<?php
							do_action( 'comment_form_before_fields' );
							foreach ( (array) $args['fields'] as $name => $field ) {
								echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
							}
							do_action( 'comment_form_after_fields' );
							?>
						<?php endif; ?>
						<?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
						<?php echo wp_kses_post( $args['comment_notes_after'] ); ?>

                        <button type="submit" class="radius button right" name="submit"
                                id="<?php echo esc_attr( $args['id_submit'] ); ?>"><?php echo esc_attr( $args['label_submit'] ); ?></button>
						<?php comment_id_fields( $post_id ); ?>

						<?php do_action( 'comment_form', $post_id ); ?>
                    </form>
				<?php endif; ?>
            </div><!-- #respond -->
			<?php do_action( 'comment_form_after' ); ?>
		<?php else : ?>
			<?php do_action( 'comment_form_comments_closed' ); ?>
		<?php endif; ?>
		<?php
	}
endif;

if ( ! function_exists( 'sweetdate_entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
	 *
	 * Create your own sweetdate_entry_meta() to override in a child theme.
	 *
	 * @since Sweetdate 1.0
	 */
	function sweetdate_entry_meta() {
		// Translators: used between list items, there is a space after the comma.
		$categories_list = get_the_category_list( esc_html__( ', ', 'sweetdate' ) );

		// Translators: used between list items, there is a space after the comma.
		$tag_list = get_the_tag_list( '', esc_html__( ', ', 'sweetdate' ) );

		$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);

		$author = sprintf( '<a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( esc_html__( 'View all posts by %s', 'sweetdate' ), get_the_author() ) ),
			get_the_author()
		);

		// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
		if ( $categories_list ) {
			echo '<li><i class="icon-calendar"></i> ' . $date . '</li>';
			echo '<li><i class="icon-user"></i> ' . $author . '</li>';
			echo '<li><i class="icon-heart"></i> ' . $categories_list . '</li>';
			if ( $tag_list ) {
				echo '<li><i class="icon-tags"></i> ' . $tag_list . '</li>';
			}
			echo '<li><i class="icon-comments"></i> <a href="' . get_permalink() . '#comments">' . sprintf( _n( 'One comment', '%1$s comments', get_comments_number(), 'sweetdate' ), number_format_i18n( get_comments_number() ) ) . '</a></li>';
		} else {
			echo '<li><i class="icon-calendar"></i> ' . $date . '</li>';
			echo '<li><i class="icon-user"></i> ' . $author . '</li>';
			if ( $tag_list ) {
				echo '<li><i class="icon-tags"></i> ' . $tag_list . '</li>';
			}
			echo '<li><i class="icon-comments"></i> <a href="' . get_permalink() . '#comments">' . sprintf( _n( 'One comment', '%1$s comments', get_comments_number(), 'sweetdate' ), number_format_i18n( get_comments_number() ) ) . '</a></li>';
		}

	}
endif;


// -----------------------------------------------------------------------------


if ( ! function_exists( 'add_video_wmode_transparent' ) ) :
	/**
	 * Automatically add wmode=transparent to embeded media
	 * Automatically add showinfo=0 for youtube
	 *
	 * @param type $html
	 * @param type $url
	 * @param type $attr
	 *
	 * @return type
	 */
	function add_video_wmode_transparent( $html, $url, $attr ) {

		if ( strpos( $html, "youtube.com" ) !== false || strpos( $html, "youtu.be" ) !== false ) {
			$info = "&amp;showinfo=0";
		} else {
			$info = "";
		}
		//add specific classes so the video will fit the container
		if ( strpos( $html, "youtube.com" ) !== false || strpos( $html, "youtu.be" ) !== false || strpos( $html, "vimeo.com" ) !== false ) {
			$html = '<div class="flex-video widescreen vimeo">' . $html . '</div>';
		}

		if ( strpos( $html, "<embed src=" ) !== false ) {
			return str_replace( '</param><embed', '</param><param name="wmode" value="opaque"></param><embed wmode="opaque" ', $html );
		} elseif ( strpos( $html, 'feature=oembed' ) !== false ) {
			return str_replace( 'feature=oembed', 'feature=oembed&amp;wmode=opaque' . $info, $html );
		} else {
			return $html;
		}
	}
endif;

add_filter( 'oembed_result', 'add_video_wmode_transparent', 10, 3 );

if ( ! function_exists( 'kleo_oembed_filter' ) ):
	function kleo_oembed_filter( $return, $data, $url ) {
		$return = str_replace( 'frameborder="0"', 'style="border: none"', $return );

		return $return;
	}
endif;
add_filter( 'oembed_dataparse', 'kleo_oembed_filter', 90, 3 );


/**
 *  ACTIONS section
 */

//GLOBAL SIDEBAR

if ( sq_option( 'global_sidebar' ) == 'left' ) {
	add_action( 'kleo_before_content', 'kleo_sidebar' );
} elseif ( sq_option( 'global_sidebar' ) == 'right' ) {
	add_action( 'kleo_after_content', 'kleo_sidebar' );
} elseif ( sq_option( 'global_sidebar' ) == '3ll' ) {
	add_action( 'kleo_before_content', 'kleo_sidebar' );
	add_action( 'kleo_before_content', 'kleo_extra_sidebar' );

} elseif ( sq_option( 'global_sidebar' ) == '3lr' ) {
	add_action( 'kleo_before_content', 'kleo_sidebar' );
	add_action( 'kleo_after_content', 'kleo_extra_sidebar' );
} elseif ( sq_option( 'global_sidebar' ) == '3rr' ) {
	add_action( 'kleo_after_content', 'kleo_sidebar' );
	add_action( 'kleo_after_content', 'kleo_extra_sidebar' );
}


//get the global sidebar
if ( ! function_exists( 'kleo_sidebar' ) ):
	function kleo_sidebar() {
		get_sidebar();
	}
endif;

//get the extra sidebar
if ( ! function_exists( 'kleo_extra_sidebar' ) ):
	function kleo_extra_sidebar() {
		?>
        <aside class="three columns">
            <div class="widgets-container sidebar_location">
				<?php
				if ( function_exists( 'generated_dynamic_sidebar' ) ) {
					generated_dynamic_sidebar( 'extra' );
				} else {
					dynamic_sidebar( 'extra' );
				}
				?>
            </div>
        </aside> <!--end four columns-->
		<?php
	}
endif;


// -----------------------------------------------------------------------------


/* If we are on the home page here it will render the search form */
if ( sq_option( 'home_search', 1 ) == 1 ) {
	add_action( 'after_header_content', 'render_user_search' );
} /* If we are on the home page here it will render the register form */
elseif ( sq_option( 'home_search', 1 ) == 2 ) {
	add_action( 'after_header_content', 'render_user_register' );
} /* If we are on the home page here it will render the mixed form */
elseif ( sq_option( 'home_search', 1 ) == 3 ) {
	add_action( 'after_setup_theme', 'kleo_home_form' );
}

if ( ! function_exists( 'kleo_home_form' ) ) :
	function kleo_home_form() {
		if ( is_user_logged_in() ) {
			add_action( 'after_header_content', 'render_user_search' );
		} else {
			add_action( 'after_header_content', 'render_user_register' );
		}
	}
endif;

if ( ! function_exists( 'render_user_search' ) ) :
	/**
	 * Prints HTML on homepage search form
	 *
	 * Create your own render_user_search() to override in a child theme.
	 *
	 * @since Sweetdate 1.0
	 */

	function render_user_search() {
		if ( is_page_template( 'page-templates/front-page.php' ) ) {
			get_template_part( 'page-parts/home-search-form' );
		}
	}

endif;


if ( ! function_exists( 'render_user_register' ) ) :
	/**
	 * Prints Register form
	 *
	 * Create your own render_user_register() to override in a child theme.
	 *
	 * @since Sweetdate 1.5
	 */

	function render_user_register() {
		if ( is_page_template( 'page-templates/front-page.php' ) ) {
			get_template_part( 'page-parts/home-register-form' );
		}
	}

endif;

// -----------------------------------------------------------------------------


/* Add Home page Image */
add_action( 'wp_head', 'kleo_home_page_image', 9 );

if ( ! function_exists( 'kleo_home_page_image' ) ):
	function kleo_home_page_image() {
		global $kleo_sweetdate;
		//HOME PAGE IMAGE
		if ( is_page_template( 'page-templates/front-page.php' ) ) {
			//backward compatibile theme check
			if ( count( sq_option( 'home_pic_background' ) ) > 0 ) {
				$kleo_sweetdate->add_bg_css( 'home_pic_background', '#header' );
			} else {
				$kleo_sweetdate->add_css( '#header { background-image: url("' . sq_option( 'home_pic_background_image' ) . '"); background-position: ' . sq_option( 'home_pic_background_image_horizontal' ) . ' ' . sq_option( 'home_pic_background_image_vertical' ) . '; background-repeat: ' . sq_option( 'home_pic_background_image_repeat' ) . '; }' );
			}

			if ( ( sq_option( 'responsive_design' ) == 1 ) ) {
				$kleo_sweetdate->add_css( '@media only screen and (max-width: 767px) {#header { background-image: none;}}' );
			}
		}
	}
endif;
// -----------------------------------------------------------------------------


/* Revolution slider in homepage */
if ( sq_option( 'home_rev', 0 ) == 1 && function_exists( 'putRevSlider' ) ) {
	add_action( 'kleo_after_header', 'kleo_home_revslider' );
}

if ( ! function_exists( 'kleo_home_revslider' ) ) {
	/**
	 * Generate revolution slider on front page template
	 * @since 2.1
	 */
	function kleo_home_revslider() {
		if ( sq_option( 'home_rev_slide' ) && is_page_template( 'page-templates/front-page.php' ) ) {
			putRevSlider( sq_option( 'home_rev_slide' ) );
		}
	}
}

/* Revolution slider in single page */
add_action( 'kleo_after_header', 'kleo_page_revslider' );
/**
 * Render revslider
 * @since 2.1
 */
function kleo_page_revslider() {
	if ( ( is_single() || is_page() ) && get_cfield( 'revolution_slider' ) && function_exists( 'putRevSlider' ) ) {
		putRevSlider( get_cfield( 'revolution_slider' ) );
	}
}


/**
 * Retrieve defined Revolution sliders
 * @return array
 * @global object $wpdb
 * @since 2.1
 */
function kleo_revslide_sliders() {
	if ( class_exists( 'RevSlider' ) ) {
		$theslider  = new RevSlider();
		$arrSliders = $theslider->getArrSliders();
		$arrA       = array( 0 );
		$arrT       = array( esc_html__( 'Select slider', 'sweetdate' ) );
		foreach ( $arrSliders as $slider ) {
			$arrA[] = $slider->getAlias();
			$arrT[] = $slider->getTitle();
		}

		$revsliders = array_combine( $arrA, $arrT );

		return $revsliders;
	} else {
		return array( esc_html__( 'You need to install Revolution Slider plugin first', 'sweetdate' ) );
	}
}


/*
 * Adds some required body claasses
 */
add_filter( 'body_class', 'kleo_body_classes' );

/**
 * Adds specific classes to body element
 *
 * @param array $classes
 *
 * @return array
 * @since 2.1
 */
function kleo_body_classes( $classes = '' ) {
	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		//if revslider is enabled in header
		if ( sq_option( 'home_rev', 0 ) == 1 && function_exists( 'putRevSlider' ) ) {
			$classes[] = 'revslider-head';
		}
		//if we want an absolute header
		if ( sq_option( 'home_rev', 0 ) == 1 && sq_option( 'home_rev_transparent', 1 ) == 1 ) {
			$classes[] = 'absolute-head';
		}
	}

	if ( is_single() || is_page() ) {
		//if revslider is enabled in header
		if ( get_cfield( 'revolution_slider' ) && function_exists( 'putRevSlider' ) ) {
			$classes[] = 'revslider-head';
		}
		//if we want an absolute header
		if ( get_cfield( 'rev_transparent' ) == 1 && ! isset( $_GET['elementor-preview'] ) ) {
			$classes[] = 'absolute-head';
		}

	}

	if ( is_admin_bar_showing() && sq_option( 'admin_bar', 1 ) == 1 ) {
		$classes[] = 'adminbar-enable';
	}

	if ( sq_option( 'responsive_design', 1 ) == 0 ) {
		$classes[] = 'not-responsive';
	}

	return $classes;
}

// -----------------------------------------------------------------------------


/* If WPML is active add the language switcher */
add_action( 'kleo_before_top_links', 'kleo_language_selector' );

function kleo_language_selector() {
	if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		do_action( 'icl_language_selector' );
	}
}

// -----------------------------------------------------------------------------


/* Ajax search in header */

//if set from admin to show search
if ( sq_option( 'ajax_search', 1 ) ) {
	add_filter( 'wp_nav_menu_items', 'kleo_search_menu_item', 10, 2 );
}


if ( ! function_exists( 'kleo_search_menu_item' ) ) {
	/**
	 * Add search to menu
	 *
	 * @param string $items
	 * @param oject $args
	 *
	 * @return string
	 */
	function kleo_search_menu_item( $items, $args ) {
		if ( $args->theme_location == 'primary' ) {
			ob_start();
			get_template_part( 'page-parts/header-ajaxsearch' );
			$form = ob_get_clean();

			$items .= '<li id="nav-menu-item-search" class="menu-item kleo-menu-item-search"><a class="search-trigger" href="#"><i class="icon icon-search"></i></a>' . $form . '</li>';
		}

		return $items;
	}
}

//Catch ajax requests
add_action( 'wp_ajax_kleo_ajax_search', 'kleo_ajax_search' );
add_action( 'wp_ajax_nopriv_kleo_ajax_search', 'kleo_ajax_search' );
if ( ! function_exists( 'kleo_ajax_search' ) ) {
	function kleo_ajax_search() {
		//if "s" input is missing exit
		if ( empty( $_REQUEST['s'] ) ) {
			die();
		}

		$output   = "";
		$defaults = array(
			'numberposts'      => 4,
			'post_type'        => 'any',
			'post_status'      => 'publish',
			'post_password'    => '',
			'suppress_filters' => false
		);
		$defaults = apply_filters( 'kleo_ajax_query_args', $defaults );

		$query = array_merge( $defaults, $_REQUEST );
		$query = http_build_query( $query );
		$posts = get_posts( $query );

		//if there are no posts
		if ( empty( $posts ) ) {
			$output = "<div class='kleo_ajax_entry ajax_not_found'>";
			$output .= "<div class='ajax_search_content'>";
			$output .= "<i class='icon icon-exclamation-sign'></i> ";
			$output .= esc_html__( "Sorry, no pages matched your criteria.", 'sweetdate' );
			$output .= "<br>";
			$output .= esc_html__( "Please try searching by different terms.", 'sweetdate' );
			$output .= "</div>";
			$output .= "</div>";
			echo $output; // PHPCS: XSS ok.
			die();
		}

		//if there are posts
		$post_types    = array();
		$post_type_obj = array();
		foreach ( $posts as $post ) {

			$post_types[ $post->post_type ][] = $post;
			if ( empty( $post_type_obj[ $post->post_type ] ) ) {
				$post_type_obj[ $post->post_type ] = get_post_type_object( $post->post_type );
			}
		}

		foreach ( $post_types as $ptype => $post_type ) {
			if ( isset( $post_type_obj[ $ptype ]->labels->name ) ) {
				$output .= "<h4>" . esc_html( $post_type_obj[ $ptype ]->labels->name ) . "</h4>";
			} else {
				$output .= "<hr>";
			}
			foreach ( $post_type as $post ) {
				$format = get_post_format( $post->ID );
				if ( get_the_post_thumbnail( $post->ID, 'thumbnail' ) ) {
					$image = get_the_post_thumbnail( $post->ID, 'thumbnail' );
				} else {
					if ( $format == 'video' ) {
						$image = "<i class='icon icon-film'></i>";
					} elseif ( $format == 'image' || $format == 'gallery' ) {
						$image = "<i class='icon icon-picture'></i>";
					} else {
						$image = "<i class='icon-info-sign'></i>";
					}
				}

				$excerpt = "";

				if ( ! empty( $post->post_content ) ) {
					$excerpt = "<br>" . char_trim( trim( strip_tags( strip_shortcodes( $post->post_content ) ) ), 40, "..." );
				}
				$link    = apply_filters( 'kleo_custom_url', get_permalink( $post->ID ) );
				$classes = "format-" . $format;
				$output  .= "<div class ='kleo_ajax_entry $classes'>";
				$output  .= "<div class='ajax_search_image'>$image</div>";
				$output  .= "<div class='ajax_search_content'>";
				$output  .= "<a href='" . esc_url( $link ) . "' class='search_title'>";
				$output  .= get_the_title( $post->ID );
				$output  .= "</a>";
				$output  .= "<span class='search_excerpt'>";
				$output  .= $excerpt;
				$output  .= "</span>";
				$output  .= "</div>";
				$output  .= "</div>";
			}
		}

		$output .= "<a class='ajax_view_all' href='" . home_url( '?s=' . $_REQUEST['s'] ) . "'>" . esc_html__( 'View all results', 'sweetdate' ) . "</a>";

		echo $output; // PHPCS: XSS ok.
		die();
	}
}


//add mp4, webm and ogv mimes for uploads
add_filter( 'upload_mimes', 'kleo_add_upload_mimes' );
if ( ! function_exists( 'kleo_add_upload_mimes' ) ) {
	function kleo_add_upload_mimes( $mimes ) {
		return array_merge( $mimes, array( 'mp4' => 'video/mp4', 'ogv' => 'video/ogg', 'webm' => 'video/webm' ) );
	}
}


/*
 * Display breadcrumb
 */
add_action( 'kleo_before_page', 'kleo_show_breadcrumb', 9 );

if ( ! function_exists( 'kleo_show_breadcrumb' ) ):

	/**
	 * Renders the breadcrumb
	 */
	function kleo_show_breadcrumb() {
		if ( sq_option( 'breadcrumb_status' ) == 1 ) {
			if ( ! is_page_template( 'page-templates/front-page.php' ) ) { ?>
                <!-- BREADCRUMBS SECTION
				================================================ -->
                <section>
                    <div id="breadcrumbs-wrapp">
                        <div class="row">
                            <div class="nine columns">
								<?php kleo_breadcrumb( array( 'container_class' => 'breadcrumbs hide-for-small' ) ); ?>
                            </div>

							<?php do_action( 'kleo_after_breadcrumb' ); ?>

                        </div><!--end row-->
                    </div><!--end breadcrumbs-wrapp-->
                </section>
                <!--END BREADCRUMBS SECTION-->
				<?php
			}
		}
	}
endif;

/* Specific page hide breadcrumb */
add_action( 'kleo_after_header', 'kleo_set_breadcrumb' );

/**
 * Disable breadcrumb on page if set
 * @since 2.1
 */
function kleo_set_breadcrumb() {
	if ( get_cfield( 'hide_breadcrumb' ) == 1 ) {
		remove_action( 'kleo_before_page', 'kleo_show_breadcrumb', 9 );
	}
}


/**
 * Text content of widgets is parsed for shortcodes and those shortcodes are ran
 * @since 1.5
 */
add_filter( 'widget_text', 'do_shortcode' );

/*
 * Remove prev/next link in header
 * @since 2.1
 */
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );


if ( ! function_exists( 'kleo_copyright_text' ) ):
	/**
	 * Add footer text
	 */

	function kleo_copyright_text() {
		if ( ! empty( sq_option( 'footer_copyright' ) ) ) {
			echo sq_option( 'footer_copyright' );
		} else {
			echo '<p>' . esc_html__( "Copyright", 'sweetdate' ) . ' &copy; ' . date( "Y" ) . ' ' . get_bloginfo( 'name' ) . '. <br class="hide-for-large show-for-small"/>' . get_bloginfo( 'description' ) . '</p>';
		}
	}
endif;
add_action( 'kleo_footer_text', 'kleo_copyright_text' );


/* Modal Ajax login */

//Render some required HTML
add_action( 'fb_popup_button', 'kleo_add_login_result_tag', 99 );
function kleo_add_login_result_tag() {
	wp_nonce_field( 'kleo-ajax-login-nonce', 'security' );
	echo '<div id="kleo-login-result"></div>';
}

add_action( 'wp_ajax_nopriv_kleoajaxlogin', 'kleo_ajax_login' );

if ( ! function_exists( 'kleo_ajax_login' ) ) {
	function kleo_ajax_login() {
		// Check the nonce, if it fails the function will break
		check_ajax_referer( 'kleo-ajax-login-nonce', 'security' );

		// Nonce is checked, continue
		$secure_cookie = '';

		// If the user wants ssl but the session is not ssl, force a secure cookie.
		if ( ! empty( $_POST['log'] ) && ! force_ssl_admin() ) {
			$user_name = sanitize_user( $_POST['log'] );
			if ( $user = get_user_by( 'login', $user_name ) ) {
				if ( get_user_option( 'use_ssl', $user->ID ) ) {
					$secure_cookie = true;
					force_ssl_admin( true );
				}
			}
		}

		if ( isset( $_REQUEST['redirect_to'] ) ) {
			$redirect_to = $_REQUEST['redirect_to'];
			// Redirect to https if user wants ssl
			if ( $secure_cookie && false !== strpos( $redirect_to, 'wp-admin' ) ) {
				$redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );
			}
		} else {
			$redirect_to = '';
		}


		$user_signon = wp_signon( '', $secure_cookie );
		if ( is_wp_error( $user_signon ) ) {
			$error_msg = $user_signon->get_error_message();
			echo json_encode( array(
				'loggedin' => false,
				//'message'  => '<i class="icon-warning-sign"></i> ' . $error_msg
				'message'  => '<i class="icon-warning-sign"></i> ' . $error_msg,

			) );
		} else {
			if ( sq_option( 'login_redirect', 'default' ) == 'reload' ) {
				$redirecturl = null;
			} else {
				$requested_redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
				/**
				 * Filter the login redirect URL.
				 *
				 * @param string $redirect_to The redirect destination URL.
				 * @param string $requested_redirect_to The requested redirect destination URL passed as a parameter.
				 * @param WP_User|WP_Error $user WP_User object if login was successful, WP_Error object otherwise.
				 *
				 * @since 3.0.0
				 *
				 */
				$redirecturl = apply_filters( 'login_redirect', $redirect_to, $requested_redirect_to, $user_signon );
				if ( $redirecturl == false ) {
					$redirecturl = '';
				}

			}

			echo json_encode( array(
				'loggedin'    => true,
				'redirecturl' => $redirecturl,
				'message'     => '<i class="icon-ok-sign"></i> ' . esc_html__( 'Login successful, redirecting...', 'sweetdate' )
			) );
		}

		die();
	}
}

add_action( 'wp_ajax_kleoajaxlogin', 'kleo_ajax_login_priv' );

if ( ! function_exists( 'kleo_ajax_login_priv' ) ):
	function kleo_ajax_login_priv() {
		$link = "javascript:window.location.reload();return false;";
		echo json_encode( array(
			'loggedin' => false,
			'message'  => '<i class="icon-warning-sign"></i> ' . sprintf( __( 'You are already logged in. Please <a href="#" onclick="%s">refresh</a> page', 'sweetdate' ), $link )
		) );
		die();
	}
endif;
// -----------------------------------------------------------------------------


/* rtMedia compatibility */


add_filter( 'rtmedia_main_template_include', 'kleo_rtmedia_template_include', 11, 2 );
function kleo_rtmedia_template_include( $template, $new_rt_template ) {
	return $new_rt_template;
}

add_action( 'wp_enqueue_scripts', 'kleo_rtmedia_script', 999 );
function kleo_rtmedia_script() {
	wp_deregister_style( 'rtmedia-font-awesome' );
	wp_dequeue_script( 'rtmedia-touchswipe' );
	wp_deregister_script( 'rtmedia-touchswipe' );
}

// -----------------------------------------------------------------------------


/* Admin bar fix for versions great than 3.8 */

if ( ! function_exists( 'kleo_admin_bar_fix' ) ):
	/**
	 * Admin bar fix for WP > 3.8
	 * @since 2.3.1
	 */
	function kleo_admin_bar_fix() {
		global $wp_version, $kleo_sweetdate;
		if ( $wp_version >= 3.8 ) {
			$kleo_sweetdate->add_css( '@media screen and (max-width: 600px) {#wpadminbar { position: fixed; }}'
			                          . '@media screen and ( max-width: 782px ) {.adminbar-enable .sticky.fixed { margin-top: 46px; }}'
			);
		}
	}
endif;
add_action( 'wp_head', 'kleo_admin_bar_fix', 9 );


/* BuddyPress Profile cover compatibility */
add_filter( 'bpcp_profile_tag', 'sweeet_bpcp_tag' );
function sweeet_bpcp_tag( $tag ) {
	$tag = 'div#profile';

	return $tag;
}

/* Updates logic */
if ( is_admin() ) {
	require_once get_template_directory() . '/lib/updates.php';
}

/* Sidebar generator */
add_filter( 'sq_ms_before_title', function ( $tag ) {
	$tag = '<h5>';

	return $tag;
} );


add_filter( 'gettext', 'sweetdate_gettext_fallback', 10, 3 );
/**
 * Fallback translation for old text domain
 *
 * @param $translation
 * @param $text
 * @param $domain
 *
 * @return string
 */
function sweetdate_gettext_fallback( $translation, $text, $domain ) {
	if ( 'kleo_framework' == $domain ) {
		return translate( $text, 'sweetdate' );
	}

	return $translation;
}

/**
 * Try to register dynamic strings for translation
 *
 * @param $string
 * @param $domain
 * @param bool $escape
 *
 * @return string
 */
function sweet_translate_dynamic( $string, $domain, $escape = false ) {
	if ( function_exists( 'sweet_core_translate_dynamic' ) ) {
		return sweet_core_translate_dynamic( $string, $domain, $escape );
	}

	return $string;
}

/**
 * WP 5.2
 */
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}
