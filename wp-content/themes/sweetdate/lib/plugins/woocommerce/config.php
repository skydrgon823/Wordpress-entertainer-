<?php
/**
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.3
 */


// Load WooCommerce custom stylsheet
if ( ! is_admin() ) {
	if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) < 0 ) {
		define( 'WOOCOMMERCE_USE_CSS', false );
	}
	add_action( 'wp_enqueue_scripts', 'kleo_load_woocommerce_css', 20 );
}

if ( ! function_exists( 'kleo_load_woocommerce_css' ) ) {
	function kleo_load_woocommerce_css() {
		wp_deregister_style( 'woocommerce-layout' );
		wp_dequeue_style( 'woocommerce-layout' );
		wp_deregister_style( 'woocommerce-smallscreen' );
		wp_dequeue_style( 'woocommerce-smallscreen' );

		wp_deregister_style( 'woocommerce-general' );
		wp_register_style( 'woocommerce-general', get_template_directory_uri() . '/woocommerce/assets/css/woocommerce.css' );
		wp_enqueue_style( 'woocommerce-general' );
	}
}

//de-register PrettyPhoto - we will use our own
//DISABLE WOOCOMMERCE PRETTY PHOTO STYLE
add_action( 'wp_print_styles', 'my_deregister_styles', 100 );

function my_deregister_styles() {
	//wp_deregister_style( 'woocommerce_prettyPhoto_css' );
}

/*-----------------------------------------------------------------------------------*/
/* Hook Woocommerce on activation */
/*-----------------------------------------------------------------------------------*/

global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
	add_action( 'init', 'kleo_install_theme', 1 );
}

/*-----------------------------------------------------------------------------------*/
/* Install */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_install_theme' ) ) {
	function kleo_install_theme() {
		update_option( 'woocommerce_thumbnail_image_width', '300' );
		update_option( 'woocommerce_thumbnail_image_height', '300' );
		update_option( 'woocommerce_single_image_width', '600' ); // Single
		update_option( 'woocommerce_single_image_height', '600' ); // Single
		update_option( 'woocommerce_catalog_image_width', '400' ); // Catalog
		update_option( 'woocommerce_catalog_image_height', '400' ); // Catalog
	}
}


if ( ! function_exists( 'checked_environment' ) ) {
	// Check WooCommerce is installed first
	add_action( 'plugins_loaded', 'checked_environment' );

	function checked_environment() {
		if ( ! class_exists( 'woocommerce' ) ) {
			wp_die( 'WooCommerce must be installed' );
		}
	}
}

add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );

// Remove WC sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// WooCommerce layout overrides
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'kleo_woocommerce_before_content' ) ) {
	// WooCommerce layout overrides
	add_action( 'woocommerce_before_main_content', 'kleo_woocommerce_before_content', 10 );
	function kleo_woocommerce_before_content() {
		get_template_part( 'page-parts/general-before-wrap' );
	}
}

if ( ! function_exists( 'kleo_woocommerce_after_content' ) ) {
	// WooCommerce layout overrides
	add_action( 'woocommerce_after_main_content', 'kleo_woocommerce_after_content', 20 );
	function kleo_woocommerce_after_content() {
		get_template_part( 'page-parts/general-after-wrap' );
	}
}

//Remove breadcrumb
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

// Change columns in related products output to 3
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'kleo_woocommerce_output_related_products', 20 );

if ( ! function_exists( 'kleo_woocommerce_output_related_products' ) ) {
	function kleo_woocommerce_output_related_products() {
		woocommerce_related_products( 3, 3 ); // 3 products, 3 columns
	}
}

// Change columns in upsell products output to 3
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

add_action( 'woocommerce_after_single_product_summary', 'kleo_woocommerce_upsell_display', 20 );

if ( ! function_exists( 'kleo_woocommerce_upsell_display' ) ) {
	function kleo_woocommerce_upsell_display() {
		woocommerce_upsell_display( 3, 3 ); // 3 products, 3 columns
	}
}

if ( ! function_exists( 'loop_columns' ) ) {
	// Change columns in product loop to 3
	function loop_columns() {
		return 3;
	}

	add_filter( 'loop_shop_columns', 'loop_columns' );
}


//remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 11 );

// Display 12 products per page
add_filter( 'loop_shop_per_page', function ( $cols ) {
	return 12;
} );


if ( ! function_exists( 'kleo_star_sidebar' ) ) {
	// Adjust the star rating in the sidebar
	add_filter( 'woocommerce_star_rating_size_sidebar', 'kleo_star_sidebar' );

	function kleo_star_sidebar() {
		return 12;
	}
}

if ( ! function_exists( 'kleo_star_reviews' ) ) {
	// Adjust the star rating in the recent reviews
	add_filter( 'woocommerce_star_rating_size_recent_reviews', 'kleo_star_reviews' );

	function kleo_star_reviews() {
		return 12;
	}
}


add_action( 'woocommerce_after_shop_loop_item', 'kleo_product_list_button', 16 );
function kleo_product_list_button() {
	global $product;
	$output = '';
	if ( $product->get_type() == 'bundle' ) {
		$product = new WC_Product_Bundle( $product->get_id() );
	}

	if ( $product->get_type() == 'simple' ) {
		$output = "<a class='view_details_button' href='" . esc_url( get_permalink( $product->get_id() ) ) . "'>" . esc_html__( 'Details', 'sweetdate' ) . "</a>";
	}
	echo $output; // PHPCS: XSS ok.
}


/**
 * Cart widget
 */
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );

add_action( 'woocommerce_widget_shopping_cart_buttons', 'sq_woo_widget_shopping_cart_button_view_cart', 10 );
add_action( 'woocommerce_widget_shopping_cart_buttons', 'sq_woo_widget_shopping_cart_proceed_to_checkout', 20 );

function sq_woo_widget_shopping_cart_button_view_cart() {
	echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button small wc-forward">' . esc_html__( 'View cart', 'woocommerce' ) . '</a>&nbsp;';

}

function sq_woo_widget_shopping_cart_proceed_to_checkout() {
	echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="button small checkout wc-forward">' . esc_html__( 'Checkout', 'woocommerce' ) . '</a>';
}

//Woocommerce structure
add_action( 'kleo_before_page', 'kleo_woo_pages' );

function kleo_woo_pages() {
	if ( is_woocommerce() ) {
		$woo_sidebar = sq_option( 'woo_sidebar', 'default' );
		if ( $woo_sidebar == 'default' ) {
			$woo_sidebar = sq_option( 'global_sidebar', 'right' );
		}
		switch ( $woo_sidebar ) {
			case 'right':
				remove_action( 'kleo_before_content', 'kleo_sidebar' );
				remove_action( 'kleo_after_content', 'kleo_sidebar' );
				add_action( 'kleo_after_content', 'kleo_woo_sidebar' );
				add_filter( 'kleo_content_class', function () {
					return "eight";
				} );
				break;
			case 'left':
				remove_action( 'kleo_before_content', 'kleo_sidebar' );
				remove_action( 'kleo_after_content', 'kleo_sidebar' );
				add_action( 'kleo_before_content', 'kleo_woo_sidebar' );
				add_filter( 'kleo_content_class', function () {
					return "eight";
				} );
				break;
			case '3ll':
				remove_action( 'kleo_before_content', 'kleo_sidebar' );
				remove_action( 'kleo_after_content', 'kleo_sidebar' );
				add_action( 'kleo_before_content', 'kleo_woo_sidebar' );
				add_action( 'kleo_before_content', 'kleo_extra_sidebar' );
				add_filter( 'kleo_content_class', function () {
					return "six";
				} );
				break;
			case '3lr':
				remove_action( 'kleo_before_content', 'kleo_sidebar' );
				remove_action( 'kleo_after_content', 'kleo_sidebar' );
				add_action( 'kleo_before_content', 'kleo_woo_sidebar' );
				add_action( 'kleo_after_content', 'kleo_extra_sidebar' );
				add_filter( 'kleo_content_class', function () {
					return "six";
				} );
				break;
			case '3rr':
				remove_action( 'kleo_before_content', 'kleo_sidebar' );
				remove_action( 'kleo_after_content', 'kleo_sidebar' );
				add_action( 'kleo_after_content', 'kleo_woo_sidebar' );
				add_action( 'kleo_after_content', 'kleo_extra_sidebar' );
				add_filter( 'kleo_content_class', function () {
					return "six";
				} );
				break;
			case 'no':
				remove_action( 'kleo_before_content', 'kleo_sidebar' );
				remove_action( 'kleo_after_content', 'kleo_sidebar' );
				add_filter( 'kleo_content_class', function () {
					return "twelve";
				} );
				break;
			default:
				break;
		}
	}
}


//get the global sidebar
if ( ! function_exists( 'kleo_woo_sidebar' ) ):
	function kleo_woo_sidebar() {
		get_sidebar( 'woocommerce' );
	}
endif;
