<?php
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Utilities {

	/**
	** Get Plugin Name
	*/
	public static function get_plugin_name($full = false) {
		return class_exists('\WprAddonsPro\Admin\Wpr_White_Label') ? \WprAddonsPro\Admin\Wpr_White_Label::get_plugin_name($full) : 'Royal Addons';
	}

	/**
	** Get Available Modules
	*/
	public static function get_registered_modules() {
		return [
			'Post Grid' => ['grid', 'https://royal-elementor-addons.com/elementor-grid-widget-examples/', '#filter:category-portfolio-grid'],
			'WooCommerce Grid' => ['woo-grid', 'https://royal-elementor-addons.com/elementor-grid-widget-examples/', '#filter:category-woo-grid'],
			'Image Grid' => ['media-grid', 'https://royal-elementor-addons.com/elementor-grid-widget-examples/', '#filter:category-gallery-grid'],
			'Magazine Grid' => ['magazine-grid', 'https://royal-elementor-addons.com/elementor-grid-widget-examples/', '#filter:category-magazine-grid'],
			'Posts/Story Timeline' => ['posts-timeline', 'https://royal-elementor-addons.com/elementor-timeline-widget/', ''],
			'Advanced Slider' => ['advanced-slider', 'https://royal-elementor-addons.com/elementor-advanced-slider-widget/', ''],
			'Testimonial' => ['testimonial', 'https://royal-elementor-addons.com/elementor-testimonials-slider-widget/', ''],
			'Nav Menu' => ['nav-menu', 'http://royal-elementor-addons.com/elementor-menu-widget/', ''],
			'Onepage Navigation' => ['onepage-nav', 'https://royal-elementor-addons.com/elementor-one-page-navigation-widget/', ''],
			'Pricing Table' => ['pricing-table', 'https://royal-elementor-addons.com/elementor-pricing-table-widget/', ''],
			'Content Toggle' => ['content-toggle', 'https://royal-elementor-addons.com/elementor-content-toggle-widget/', ''],
			'Countdown' => ['countdown', 'https://royal-elementor-addons.com/elementor-countdown-widget/', ''],
			'Progress Bar' => ['progress-bar', 'https://royal-elementor-addons.com/elementor-progress-bar-widget/', ''],
			'Tabs' => ['tabs', 'https://royal-elementor-addons.com/elementor-tabs-widget/', ''],
			'Advanced Text' => ['advanced-text', 'https://royal-elementor-addons.com/elementor-advanced-text-widget/', ''],
			'Flip Box' => ['flip-box', 'https://royal-elementor-addons.com/elementor-flip-box-widget/', ''],
			'Promo Box' => ['promo-box', 'https://royal-elementor-addons.com/elementor-promo-box-widget/', ''],
			'Before After' => ['before-after', 'https://royal-elementor-addons.com/elementor-before-after-widget/', ''],
			'Image Hotspots' => ['image-hotspots', 'https://royal-elementor-addons.com/elementor-image-hotspot-widget/', ''],
			'Forms' => ['forms', 'https://royal-elementor-addons.com/elementor-forms-widget/', ''],
			'MailChimp' => ['mailchimp', 'https://royal-elementor-addons.com/elementor-mailchimp-subscription-widget/', ''],
			'Content Ticker' => ['content-ticker', 'https://royal-elementor-addons.com/elementor-content-ticker-widget/', ''],
			'Button' => ['button', 'https://royal-elementor-addons.com/elementor-button-widget/', ''],
			'Dual Button' => ['dual-button', 'https://royal-elementor-addons.com/elementor-button-widget/', '#dualbuttonsection'],
			'Team Member' => ['team-member', 'https://royal-elementor-addons.com/elementor-team-member-widget/', ''],
			'Google Maps' => ['google-maps', 'http://royal-elementor-addons.com/elementor-google-maps-widget/', ''],
			'Price List' => ['price-list', 'https://royal-elementor-addons.com/elementor-price-list-widget/', ''],
			'Business Hours' => ['business-hours', 'https://royal-elementor-addons.com/elementor-business-hours-widget/', ''],
			'Sharing Buttons' => ['sharing-buttons', 'https://royal-elementor-addons.com/elementor-social-sharing-buttons-widget/', ''],
			'Search Form' => ['search', 'https://royal-elementor-addons.com/elementor-search-widget/', ''],
			'Back to Top' => ['back-to-top', 'https://royal-elementor-addons.com/elementor-back-to-top-widget/', ''],
			'Phone Call' => ['phone-call', 'https://royal-elementor-addons.com/elementor-phone-call-widget/', ''],
			'Lottie Animations' => ['lottie-animations', 'https://royal-elementor-addons.com/elementor-lottie-animation-widget/?ref=rea-plugin-backend-elements-widget-prev', ''],
			'Site Logo' => ['logo', '', ''],
			'Popup Trigger' => ['popup-trigger', '', ''],
			// 'Random Image' => ['random-image', '', ''],
			// 'Author Box' => 'author-box',
		];
	}

	/**
	** Get Enabled Modules
	*/
	public static function get_available_modules() {
		$modules = Utilities::get_registered_modules();

		foreach ( $modules as $title => $data ) {
			$slug = $data[0];
			if ( 'on' !== get_option('wpr-element-'. $slug, 'on') ) {
				unset($modules[$title]);
			}
		}
		return $modules;
	}

	/**
	** Get Available Custom Post Types or Taxonomies
	*/
	public static function get_custom_types_of( $query, $exclude_defaults = true ) {
		// Taxonomies
		if ( 'tax' === $query ) {
			$custom_types = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );
		
		// Post Types
		} else {
			$custom_types = get_post_types( [ 'show_in_nav_menus' => true ], 'objects' );
		}

		$custom_type_list = [];

		foreach ( $custom_types as $key => $value ) {
			if ( $exclude_defaults ) {
				if ( $key != 'post' && $key != 'page' && $key != 'category' && $key != 'post_tag' ) {
					$custom_type_list[ $key ] = $value->label;
				}
			} else {
				$custom_type_list[ $key ] = $value->label;
			}
		}

		return $custom_type_list;
	}


	/**
	** Get All Users
	*/
	public static function get_users() {
		$users = [];
		
		foreach ( get_users() as $key => $user ) {
			$users[$user->data->ID] = $user->data->user_nicename;
		}
		
		wp_reset_postdata();

		return $users;
	}


	/**
	** Get User Roles
	*/
	public static function get_user_roles() {
		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once ABSPATH . 'wp-admin/includes/user.php';
		}

		$r = [];

		$editable_roles = array_reverse( get_editable_roles() );
		
		$r['guest'] = esc_html__( 'Guest', 'wpr-addons' );

		foreach ( $editable_roles as $role => $details ) {
			$r[ $role ] = translate_user_role( $details['name'] );
		}

		return $r;
	}


	/**
	** Get Terms of Taxonomy
	*/
	public static function get_terms_by_taxonomy( $slug ) {
		if ( ( 'product_cat' === $slug || 'product_tag' === $slug ) && ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$query = get_terms( $slug, [ 'hide_empty' => false, 'posts_per_page' => -1 ] );
		$taxonomies = [];

		foreach ( $query as $tax ) {
			$taxonomies[$tax->term_id] = $tax->name;
		}

		wp_reset_postdata();

		return $taxonomies;
	}


	/**
	** Get Posts of Post Type
	*/
	public static function get_posts_by_post_type( $slug ) {
		$query = get_posts( [ 'post_type' => $slug, 'posts_per_page' => -1 ] );
		$posts = [];

		foreach ( $query as $post ) {
			$posts[$post->ID] = $post->post_title;
		}
		
		wp_reset_postdata();

		return $posts;
	}


	/**
	** Get Library Template ID
	*/
	public static function get_template_id( $slug ) {
		$template = get_page_by_path( $slug, OBJECT, 'wpr_templates' );

        return isset( $template->ID ) ? $template->ID : false;
	}

    /**
    ** Get Library Template Slug
    */
	public static function get_template_slug( $data, $page, $post_id = '' ) {
		if ( is_null($data) ) {
			return;
		}
		
		$template = NULL;

		// Find a Custom Condition
		foreach( $data as $id => $conditions ) {
			if ( in_array( $page .'/'. $post_id, $conditions) ) {
				$template = $id;
			} elseif ( in_array( $page .'/all', $conditions) ) {
				$template = $id;
			} elseif ( in_array( $page, $conditions) ) {
				$template = $id;
			}
		}

		// If a Custom NOT Found, use Global
		if ( is_null($template) ) {
			foreach( $data as $id => $conditions ) {
				if ( in_array( 'global', $conditions) ) {
					$template = $id;
				}
			}
		}

		return $template;
	}


	/**
	** Get Elementor Template Type
	*/
	public static function get_elementor_template_type( $id ) {
		$post_meta = get_post_meta($id);
		$template_type = isset($post_meta['_elementor_template_type'][0]) ? $post_meta['_elementor_template_type'][0] : false;

        return $template_type;
	}


	/**
	** Render Elementor Template
	*/
	public static function render_elementor_template( $slug ) {
		$template_id = Utilities::get_template_id( $slug );
		$get_elementor_content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id, false );

		if ( '' === $get_elementor_content ) {
			return;
		}

    	// Render Template Content
		echo $get_elementor_content;
	}


	/**
	** Blog Archive Page Check
	*/
	public static function is_blog_archive() {
		$result = false;
		$front_page = get_option( 'page_on_front' );
		$posts_page = get_option( 'page_for_posts' );

		if ( is_home() && '0' === $front_page && '0' === $posts_page || intval($posts_page) === get_queried_object_id() ) {
			$result = true;
		}

		return $result;
	}

	// Get Post Sharing Icon
	public static function get_post_sharing_icon( $args = [] ) {
		
		if ( 'facebook-f' === $args['network'] ) {
			$sharing_url = 'https://www.facebook.com/sharer.php?u='. $args['url'];
			$network_title = esc_html__( 'Facebook', 'wpr-addons' );
		} elseif ( 'twitter' === $args['network'] ) {
			$sharing_url = 'https://twitter.com/intent/tweet?url='. $args['url'];
			$network_title = esc_html__( 'Twitter', 'wpr-addons' );
		} elseif ( 'linkedin-in' === $args['network'] ) {
			$sharing_url = 'https://www.linkedin.com/shareArticle?mini=true&url='. $args['url'] .'&title='. $args['title'] .'&summary='. $args['text'] .'&source='. $args['url'];
			$network_title = esc_html__( 'LinkedIn', 'wpr-addons' );
		} elseif ( 'pinterest-p' === $args['network'] ) {
			$sharing_url = 'https://www.pinterest.com/pin/find/?url='. $args['url'];
			$network_title = esc_html__( 'Pinterest', 'wpr-addons' );
		} elseif ( 'reddit' === $args['network'] ) {
			$sharing_url = 'https://reddit.com/submit?url='. $args['url'] .'&title='. $args['title'];
			$network_title = esc_html__( 'Reddit', 'wpr-addons' );
		} elseif ( 'tumblr' === $args['network'] ) {
			$sharing_url = 'https://tumblr.com/share/link?url='. $args['url'];
			$network_title = esc_html__( 'Tumblr', 'wpr-addons' );
		} elseif ( 'digg' === $args['network'] ) {
			$sharing_url = 'https://digg.com/submit?url='. $args['url'];
			$network_title = esc_html__( 'Digg', 'wpr-addons' );
		} elseif ( 'xing' === $args['network'] ) {
			$sharing_url = 'https://www.xing.com/app/user?op=share&url='. $args['url'];
			$network_title = esc_html__( 'Xing', 'wpr-addons' );
		} elseif ( 'stumbleupon' === $args['network'] ) {
			$sharing_url = 'https://www.stumbleupon.com/submit?url='. $args['url'];
			$network_title = esc_html__( 'StumpleUpon', 'wpr-addons' );
		} elseif ( 'vk' === $args['network'] ) {
			$sharing_url = 'https://vkontakte.ru/share.php?url='. $args['url'] .'&title='. $args['title'] .'&description='. wp_trim_words( $args['text'], 250 ) .'&image='. $args['image'] .'/';
			$network_title = esc_html__( 'vKontakte', 'wpr-addons' );
		} elseif ( 'odnoklassniki' === $args['network'] ) {
			$sharing_url = 'http://odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl='. $args['url'];
			$network_title = esc_html__( 'OdnoKlassniki', 'wpr-addons' );
		} elseif ( 'get-pocket' === $args['network'] ) {
			$sharing_url = 'https://getpocket.com/edit?url='. $args['url'];
			$network_title = esc_html__( 'Pocket', 'wpr-addons' );
		} elseif ( 'skype' === $args['network'] ) {
			$sharing_url = 'https://web.skype.com/share?url='. $args['url'];
			$network_title = esc_html__( 'Skype', 'wpr-addons' );
		} elseif ( 'whatsapp' === $args['network'] ) {
			$sharing_url = 'https://api.whatsapp.com/send?text=*'. $args['title'] .'*\n'. $args['text'] .'\n'. $args['url'];
			$network_title = esc_html__( 'WhatsApp', 'wpr-addons' );
		} elseif ( 'telegram' === $args['network'] ) {
			$sharing_url = 'https://telegram.me/share/url?url='. $args['url'] .'&text='. $args['text'];
			$network_title = esc_html__( 'Telegram', 'wpr-addons' );
		} elseif ( 'delicious' === $args['network'] ) {
			$sharing_url = 'https://del.icio.us/save?url='. $args['url'] .'&title={title}';
			$network_title = esc_html__( 'Delicious', 'wpr-addons' );
		} elseif ( 'envelope' === $args['network'] ) {
			$sharing_url = 'mailto:?subject='. $args['title'] .'&body='. $args['url'];
			$network_title = esc_html__( 'Email', 'wpr-addons' );
		} elseif ( 'print' === $args['network'] ) {
			$sharing_url = 'javascript:window.print()';
			$network_title = esc_html__( 'Print', 'wpr-addons' );
		} else {
			$sharing_url = '';
			$network_title = '';
		}

		$sharing_url = 'print' === $args['network'] ? $sharing_url : esc_url( $sharing_url );

		$output = '';

		if ( '' !== $network_title ) {
			$output .= '<a href="'. $sharing_url .'" class="wpr-sharing-icon wpr-sharing-'. esc_attr( $args['network'] ) .'" title="" target="_blank">';
				// Tooltip
				$output .= 'yes' === $args['tooltip'] ? '<span class="wpr-sharing-tooltip wpr-tooltip">'. esc_html( $network_title ) .'</span>' : '';
				
				// Category
				if ( 'envelope' === $args['network'] || 'print' === $args['network'] ) {
					$category = 'fas';
				} else {
					$category = 'fab';
				}

				// Icon
				if ( 'yes' === $args['icons'] ) {
					$output .= '<i class="'. esc_attr($category) .' fa-'. esc_attr( $args['network'] ) .'"></i>';
				}

				// Label
				if ( isset( $args['labels'] ) && 'yes' === $args['labels'] ) {
					$label = isset( $args['custom_label'] ) && '' !== $args['custom_label'] ? $args['custom_label'] :  $network_title;
					$output .= '<span class="wpr-sharing-label">'. esc_html( $label ) .'</span>';
				}
			$output .= '</a>';
		}

		return $output;
	}


	/**
	** Filter oEmbed Results
	*/
	public static function filter_oembed_results( $html ) {
		// Filter
		preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $matches );

		// Return URL
		return  $matches[1] .'&auto_play=true';
	}


	/**
	** Get Custom Meta Keys
	*/
	public static function get_custom_meta_keys() {
		$data = [];
		$options = [];
		$merged_meta_keys = [];
		$post_types = Utilities::get_custom_types_of( 'post', false );

		foreach ( $post_types as $post_type_slug => $post_type_name ) {
			$data[ $post_type_slug ] = [];
			$posts = get_posts( [ 'post_type' => $post_type_slug ] );

			foreach (  $posts as $key => $post ) {
				$meta_keys = get_post_custom_keys( $post->ID );

				if ( ! empty($meta_keys) ) {
					for ( $i = 0; $i < count( $meta_keys ); $i++ ) {
						if ( '_' !== substr( $meta_keys[$i], 0, 1 ) ) {
							array_push( $data[$post_type_slug], $meta_keys[$i] );
						}
					}
				}
			}

			$data[ $post_type_slug ] = array_unique( $data[ $post_type_slug ] );
		}

		foreach ( $data as $array ) {
			$merged_meta_keys = array_unique( array_merge( $merged_meta_keys, $array ) );
		}
		
		// Rekey
		$merged_meta_keys = array_values($merged_meta_keys);

		for ( $i = 0; $i < count( $merged_meta_keys ); $i++ ) {
			$options[ $merged_meta_keys[$i] ] = $merged_meta_keys[$i];
		}

		return [ $data, $options ];
	}


	/**
	** Get SVG Icons Array
	*/
	public static function get_svg_icons_array( $stack, $fa_icons ) {
		$svg_icons = [];

		if ( 'arrows' === $stack ) {
			$svg_icons['svg-angle-1-left'] = esc_html__( 'Angle', 'wpr-addons' );
			$svg_icons['svg-angle-2-left'] = esc_html__( 'Angle Bold', 'wpr-addons' );
			$svg_icons['svg-angle-3-left'] = esc_html__( 'Angle Bold Round', 'wpr-addons' );
			$svg_icons['svg-angle-4-left'] = esc_html__( 'Angle Plane', 'wpr-addons' );
			$svg_icons['svg-arrow-1-left'] = esc_html__( 'Arrow', 'wpr-addons' );
			$svg_icons['svg-arrow-2-left'] = esc_html__( 'Arrow Bold', 'wpr-addons' );
			$svg_icons['svg-arrow-3-left'] = esc_html__( 'Arrow Bold Round', 'wpr-addons' );
			$svg_icons['svg-arrow-4-left'] = esc_html__( 'Arrow Caret', 'wpr-addons' );

		} elseif ( 'blockquote' === $stack ) {
			$svg_icons['svg-blockquote-1'] = esc_html__( 'Blockquote Round', 'wpr-addons' );
			$svg_icons['svg-blockquote-2'] = esc_html__( 'Blockquote ST', 'wpr-addons' );
			$svg_icons['svg-blockquote-3'] = esc_html__( 'Blockquote BS', 'wpr-addons' );
			$svg_icons['svg-blockquote-4'] = esc_html__( 'Blockquote Edges', 'wpr-addons' );
			$svg_icons['svg-blockquote-5'] = esc_html__( 'Blockquote Quad', 'wpr-addons' );

		} elseif ( 'sharing' === $stack ) {
			// $svg_icons['svg-sharing-1'] = esc_html__( 'sharing 1', 'wpr-addons' );
			// $svg_icons['svg-sharing-2'] = esc_html__( 'sharing 2', 'wpr-addons' );
		}

		// Merge FontAwesome and SVG icons
		return array_merge( $fa_icons, $svg_icons );
	}


	/**
	** Get SVG Icon
	*/
	public static function get_svg_icon( $icon, $dir ) {
		$style_attr = '';

		// Rotate Right
		if ( 'right' === $dir ) {
			$style_attr = 'style="transform: rotate(180deg); -webkit-transform: rotate(180deg);" ';
		}

		$icons = [
			// Arrows
			'svg-angle-1-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 283.4 512" style="enable-background:new 0 0 283.4 512;" xml:space="preserve"><g><polygon class="st0" points="54.5,256.3 283.4,485.1 256.1,512.5 0,256.3 0,256.3 27.2,229 256.1,0 283.4,27.4 "/></g></svg>',
			'svg-angle-2-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 303.3 512" style="enable-background:new 0 0 303.3 512;" xml:space="preserve"><g><polygon class="st0" points="94.7,256 303.3,464.6 256,512 47.3,303.4 0,256 47.3,208.6 256,0 303.3,47.4 "/></g></svg>',
			'svg-angle-3-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 291.4 512" style="enable-background:new 0 0 291.4 512;" xml:space="preserve"><g><path class="st0" d="M281.1,451.5c13.8,13.8,13.8,36.3,0,50.1c-13.8,13.8-36.3,13.8-50.1,0L10.4,281C3.5,274.1,0,265.1,0,256c0-9.1,3.5-18.1,10.4-25L231,10.4c13.8-13.8,36.3-13.8,50.1,0c6.9,6.9,10.4,16,10.4,25s-3.5,18.1-10.4,25L85.5,256L281.1,451.5z"/></g></svg>',
			'svg-angle-4-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 259.6 512" style="enable-background:new 0 0 259.6 512;" xml:space="preserve"><g><path class="st0" d="M256.6,18.1L126.2,256.1l130.6,237.6c3.6,5.6,3.9,10.8,0.2,14.9c-0.2,0.2-0.2,0.3-0.3,0.3s-0.3,0.3-0.3,0.3c-3.9,3.9-10.3,3.6-14.2-0.3L2.9,263.6c-2-2.1-3.1-4.7-2.9-7.5c0-2.8,1-5.6,3.1-7.7L242,3.1c4.1-4.1,10.6-4.1,14.6,0l0,0C260.7,7.3,260.5,10.9,256.6,18.1z"/></g></svg>',
			'svg-arrow-1-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 338.4" style="enable-background:new 0 0 512 338.4;" xml:space="preserve"><g><polygon class="st0" points="511.4,183.1 53.4,183.1 188.9,318.7 169.2,338.4 0,169.2 169.2,0 188.9,19.7 53.4,155.3 511.4,155.3 "/></g></svg>',
			'svg-arrow-2-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 320.6" style="enable-background:new 0 0 512 320.6;" xml:space="preserve"><g><polygon class="st0" points="512,184.4 92.7,184.4 194.7,286.4 160.5,320.6 34.3,194.4 34.3,194.4 0,160.2 160.4,0 194.5,34.2 92.7,136 512,136 "/></g></svg>',
			'svg-arrow-3-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 499.6 320.6" style="enable-background:new 0 0 499.6 320.6;" xml:space="preserve"><g><path class="st0" d="M499.6,159.3c0.3,7-2.4,13.2-7,17.9c-4.3,4.3-10.4,7-16.9,7H81.6l95.6,95.6c9.3,9.3,9.3,24.4,0,33.8c-4.6,4.6-10.8,7-16.9,7c-6.1,0-12.3-2.4-16.9-7L6.9,177.2c-9.3-9.3-9.3-24.4,0-33.8l16.9-16.9l0,0L143.3,6.9c9.3-9.3,24.4-9.3,33.8,0c4.6,4.6,7,10.8,7,16.9s-2.4,12.3-7,16.9l-95.6,95.6h393.7C488.3,136.3,499.1,146.4,499.6,159.3z"/></g></svg>',
			'svg-arrow-4-left' => '<svg '. $style_attr .'version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 499.6 201.3" style="enable-background:new 0 0 499.6 201.3;" xml:space="preserve"><g><polygon class="st0" points="0,101.1 126,0 126,81.6 499.6,81.6 499.6,120.8 126,120.8 126,201.3 "/></g></svg>',
		
			// Blockquote
			'svg-blockquote-1' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 406.1" style="enable-background:new 0 0 512 406.1;" xml:space="preserve"><g><g id="Layer_2_1_" class="st0"><path class="st1" d="M510.6,301.8c0,57.6-46.7,104.3-104.3,104.3c-12.6,0-24.7-2.3-36-6.4c-28.3-9.1-64.7-29.1-82.8-76.3C218.9,145.3,477.7,0.1,477.7,0.1l6.4,12.3c0,0-152.4,85.7-132.8,200.8C421.8,170.3,510.1,220.2,510.6,301.8z"/><path class="st1" d="M234.6,301.8c0,57.6-46.7,104.3-104.3,104.3c-12.6,0-24.7-2.3-36-6.4c-28.3-9.1-64.7-29.1-82.8-76.3C-57.1,145.3,201.8,0.1,201.8,0.1l6.4,12.3c0,0-152.4,85.7-132.8,200.8C145.9,170.3,234.1,220.2,234.6,301.8z"/></g></g></svg>',
			'svg-blockquote-2' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 415.9" style="enable-background:new 0 0 512 415.9;" xml:space="preserve"><g><g class="st0"><polygon class="st1" points="512,0 303.1,208 303.1,415.9 512,415.9 "/><polygon class="st1" points="208.9,0 0,208 0,415.9 208.9,415.9 "/></g></g></svg>',
			'svg-blockquote-3' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 369.3" style="enable-background:new 0 0 512 369.3;" xml:space="preserve"><g><g class="st0"><polygon class="st1" points="240.7,0 240.7,240.5 88.1,369.3 88.1,328.3 131.4,240.5 0.3,240.5 0.3,0 "/><polygon class="st1" points="512,43.3 512,238.6 388.1,343.2 388.1,310 423.2,238.6 316.7,238.6 316.7,43.3 "/></g></g></svg>',
			'svg-blockquote-4' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 369.3" style="enable-background:new 0 0 512 369.3;" xml:space="preserve"><g><g class="st0"><g><path class="st1" d="M469.1,299.1c-62,79.7-148.7,69.8-148.7,69.8v-86.5c0,0,42.6-0.6,77.5-35.4c20.3-20.3,22.7-65.6,22.8-81.4h-101V-10.9H512v176.6C512.2,184.7,509.4,247.2,469.1,299.1z"/></g><g><path class="st1" d="M149.3,299.1c-62,79.7-148.7,69.8-148.7,69.8v-86.5c0,0,42.6-0.6,77.5-35.4c20.3-20.3,22.7-65.6,22.8-81.4H0V-10.9h192.2v176.6C192.4,184.7,189.7,247.2,149.3,299.1z"/></g></g></g></svg>',
			'svg-blockquote-5' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 422.1" style="enable-background:new 0 0 512 422.1;" xml:space="preserve"><g><g class="st0"><polygon class="st1" points="237,0 237,223.7 169.3,422.1 25.7,422.1 53.4,223.7 0,223.7 0,0 "/><polygon class="st1" points="512,0 512,223.7 444.3,422.1 300.7,422.1 328.4,223.7 275,223.7 275,0 "/></g></g></svg>',
			
			// Sharing
			'svg-sharing-1' => '<?xml version="1.0" ?><svg style="enable-background:new 0 0 48 48;" version="1.1" viewBox="0 0 48 48" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Icons"><g id="Icons_15_"><g><path d="M25.03766,20.73608v-3.7207c0-0.3799,0.4135-0.6034,0.7263-0.4023l9.3855,5.9218     c0.3017,0.19,0.3017,0.6146,0,0.8045l-5.1844,3.2738l-1.8659,1.1843l-2.3352,1.4749c-0.3129,0.2011-0.7263-0.0335-0.7263-0.4022     v-3.2403v-0.4916" style="fill:#5F83CF;"/><path d="M29.96506,26.61318l-1.8659,1.1843l-2.3352,1.4749c-0.3128,0.2011-0.7263-0.0335-0.7263-0.4022     v-3.2403v-0.4916c-2.5759,0.1057-5.718-0.3578-7.8439,0.6112c-1.9663,0.8963-3.5457,2.5639-4.2666,4.6015     c-0.1282,0.3623-0.2296,0.7341-0.3029,1.1114v-2.9721c0-1.128,0.2449-2.2513,0.7168-3.2759     c0.4588-0.9961,1.1271-1.8927,1.948-2.6196c0.8249-0.7306,1.8013-1.2869,2.8523-1.6189     c1.5111-0.4774,3.1532-0.4118,4.7155-0.3096c0.7252,0.0475,1.4538,0.0698,2.1808,0.0698" style="fill:#5F83CF;"/></g></g></g></svg>',
			'svg-sharing-2' => '<?xml version="1.0" ?><svg style="enable-background:new 0 0 48 48;" version="1.1" viewBox="0 0 48 48" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Icons"><g id="Icons_16_"><g><path d="M27.775,21.64385L27.775,21.64385l1-0.01h1v1.65l2.17-1.38l0.1-0.06l2.95-1.87l-5.22-3.29v0.87     v0.77h-1l-1-0.02l0,0" style="fill:#5F83CF;"/><path d="M28.775,18.32385c-0.33,0-0.67-0.01-1-0.02c-0.22-0.01-0.43-0.02-0.65-0.04     c-1.3358-0.0496-2.5105-0.0408-3.55,0.24c-0.5,0.16-0.97,0.38-1.41,0.67c-0.26,0.16-0.51,0.34-0.74,0.55     c-0.62,0.54-1.12,1.22-1.47,1.97c-0.35,0.77-0.54,1.62-0.54,2.47v2.24c0.06-0.29,0.13-0.57,0.23-0.84     c0.54-1.53,1.73-2.79,3.22-3.47c1.34-0.61,3.21-0.47,4.91-0.45c0.35,0,0.68,0,1-0.01" style="fill:#5F83CF;"/><path d="M31.945,23.63175l-1.8884,1.1873v3.8702c0,0.5422-0.5142,0.991-1.1499,0.991H16.0432     c-0.6357,0-1.1498-0.4488-1.1498-0.991v-8.7689c0-0.5515,0.5142-1.0002,1.1498-1.0002h3.5525h0.0037     c0.0561-0.0748,0.1739-0.2057,0.2393-0.2618c0.6731-0.5983,1.4864-1.0657,2.3465-1.3368     c0.0467-0.0187,0.0935-0.0281,0.1402-0.0374h-6.2821c-1.6734,0-3.0383,1.1872-3.0383,2.6362v8.7689     c0,1.449,1.3649,2.6269,3.0383,2.6269h12.8634c1.6734,0,3.0383-1.1779,3.0383-2.6269V23.63175z" style="fill:#F2F2F2;"/></g></g></g></svg>',
			
		];
		
		return $icons[$icon];
	}


	/**
	** Get WPR Icon
	*/
	public static function get_wpr_icon( $icon, $dir ) {
		if ( false !== strpos( $icon, 'svg-' ) ) {
			return Utilities::get_svg_icon( $icon, $dir );
		} elseif ( false !== strpos( $icon, 'fa-' ) ) {
			$dir = '' !== $dir ? '-'. $dir : '';
			return '<i class="'. esc_attr($icon . $dir) .'"></i>';
		} else {
			return '';
		}
	}


	/**
	** Mailchimp AJAX Subscribe
	*/
	public static function ajax_mailchimp_subscribe() {
		// API Key
        $api_key = sanitize_text_field($_POST['apiKey']);
        $api_key_sufix = explode( '-', $api_key )[1];

        // List ID
        $list_id = sanitize_text_field($_POST['listId']);

        // Get Available Fileds
        wp_parse_str( $_POST['fields'], $fields );

        // Merge Additional Fields
        $merge_fields = array(
            'FNAME' => ! empty( esc_html($fields['wpr_mailchimp_firstname']) ) ? esc_html($fields['wpr_mailchimp_firstname']) : '',
            'LNAME' => ! empty( esc_html($fields['wpr_mailchimp_lastname']) ) ? esc_html($fields['wpr_mailchimp_lastname']) : '',
        );

        // API URL
        $api_url = 'https://'. $api_key_sufix .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members/'. md5(strtolower(esc_html($fields['wpr_mailchimp_email'])));

        // API Args
        $api_args = [
			'method' => 'PUT',
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'apikey ' . $api_key,
			],
			'body' => json_encode([
				'email_address' => esc_html($fields[ 'wpr_mailchimp_email' ]),
				'status' => 'subscribed',
				'merge_fields' => $merge_fields,
			]),
        ];

        // Send Request
        $request = wp_remote_post( $api_url, $api_args );

		if ( ! is_wp_error($request) ) {
			$request = json_decode( wp_remote_retrieve_body($request) );

			// Set Status
			if ( ! empty($request) ) {
				if ($request->status == 'subscribed') {
					wp_send_json([ 'status' => 'subscribed' ]);
				} else {
					wp_send_json([ 'status' => $request->title ]);
				}
			}
		}
	}

	/**
	** Mailchimp - Get Lists
	*/
	public static function get_mailchimp_lists() {
		$api_key = get_option('wpr_mailchimp_api_key', '');

		$mailchimp_list = [
			'def' => esc_html__( 'Select List', 'wpr-addons' )
		];

		if ( '' === $api_key ) {
			return $mailchimp_list;
		} else {
	    	$url = 'https://'. substr( $api_key, strpos( $api_key, '-' ) + 1 ) .'.api.mailchimp.com/3.0/lists/';
		    $args = [ 'headers' => [ 'Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key ) ] ];

		    $response = wp_remote_get( $url, $args );
		    $body = json_decode($response['body']);
			 
			if ( ! empty( $body->lists ) ) {
				foreach ( $body->lists as $list ) {
					$mailchimp_list[$list->id] = $list->name .' (' . $list->stats->member_count . ')';
				}
			}

			return $mailchimp_list;
		}
	}

	/**
	** WPR Animation Timings
	*/
	public static function wpr_animation_timings() {
		$timing_functions = [
			'ease-default' => 'Default',
			'linear' => 'Linear',
			'ease-in' => 'Ease In',
			'ease-out' => 'Ease Out',
			'pro-eio' => 'EI Out (Pro)',
			'pro-eiqd' => 'EI Quad (Pro)',
			'pro-eicb' => 'EI Cubic (Pro)',
			'pro-eiqrt' => 'EI Quart (Pro)',
			'pro-eiqnt' => 'EI Quint (Pro)',
			'pro-eisn' => 'EI Sine (Pro)',
			'pro-eiex' => 'EI Expo (Pro)',
			'pro-eicr' => 'EI Circ (Pro)',
			'pro-eibk' => 'EI Back (Pro)',
			'pro-eoqd' => 'EO Quad (Pro)',
			'pro-eocb' => 'EO Cubic (Pro)',
			'pro-eoqrt' => 'EO Quart (Pro)',
			'pro-eoqnt' => 'EO Quint (Pro)',
			'pro-eosn' => 'EO Sine (Pro)',
			'pro-eoex' => 'EO Expo (Pro)',
			'pro-eocr' => 'EO Circ (Pro)',
			'pro-eobk' => 'EO Back (Pro)',
			'pro-eioqd' => 'EIO Quad (Pro)',
			'pro-eiocb' => 'EIO Cubic (Pro)',
			'pro-eioqrt' => 'EIO Quart (Pro)',
			'pro-eioqnt' => 'EIO Quint (Pro)',
			'pro-eiosn' => 'EIO Sine (Pro)',
			'pro-eioex' => 'EIO Expo (Pro)',
			'pro-eiocr' => 'EIO Circ (Pro)',
			'pro-eiobk' => 'EIO Back (Pro)',
		];

		if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
			$timing_functions = \WprAddonsPro\Includes\Controls\WPR_Control_Animations_Pro::wpr_animation_timings();
		}

		return $timing_functions;
	}
	
	public static function wpr_animation_timing_pro_conditions() {
		return ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk'];
	}

	/**
	** WPR Library Button
	*/
	public static function wpr_library_buttons( $module, $controls_manager, $tutorial_url = '' ) {
		if ( empty(get_option('wpr_wl_plugin_links')) ) {
			if ( '' !== $tutorial_url ) {
				$tutorial_link = '<a href="'. $tutorial_url .'" target="_blank">'. esc_html__( 'Watch Video Tutorial ', 'wpr-addons' ) .'<span class="dashicons dashicons-video-alt3"></span></a>';
			} else {
				$tutorial_link = '';
			}

			$module->add_control(
	            'wpr_library_buttons',
	            [
					'raw' => '<div><a href="#" target="_blank" data-theme="'. get_template() .'">'. esc_html__( 'Widget Preview', 'wpr-addons' ) .'</a> <a href="#">'. esc_html__( 'Predefined Styles', 'wpr-addons' ) .'</a></div>'. $tutorial_link,
					'type' => $controls_manager,
				]
	        );
        }
	}

	/**
	** Upgrade to Pro Notice
	*/
	public static function upgrade_pro_notice( $module, $controls_manager, $widget, $option, $condition = [] ) {
		if ( wpr_fs()->can_use_premium_code() ) {
			return;
		}

		$module->add_control(
            $option .'_pro_notice',
            [
				'raw' => 'This option is available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-'. $widget .'-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
				// 'raw' => 'This option is available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
				'type' => $controls_manager,
				'content_classes' => 'wpr-pro-notice',
				'condition' => [
					$option => $condition,
				]
			]
        );
	}

	/**
	** Pro Features List Section
	*/
	public static function pro_features_list_section( $module, $controls_manager, $widget, $features ) {
		if ( wpr_fs()->can_use_premium_code() ) {
			return;
		}

		$module->start_controls_section(
			'pro_features_section',
			[
				'label' => 'Pro Features <span class="dashicons dashicons-star-filled"></span>',
			]
		);

		$list_html = '';

		for ($i=0; $i < count($features); $i++) { 
			$list_html .= '<li>'. $features[$i] .'</li>';
		}

		$module->add_control(
			'pro_features_list',
			[
				'type' => $controls_manager,
				'raw' => '<ul>'. $list_html .'</ul>
						  <a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-pro-sec-'. $widget .'-upgrade-pro#purchasepro" target="_blank">Get Pro version</a>',
				'content_classes' => 'wpr-pro-features-list',
			]
		);

		$module->end_controls_section();
	}

	/**
	** Check for New Free Users
	*/
	public static function is_new_free_user() {
		return !wpr_fs()->can_use_premium_code() && intval(get_option('royal_elementor_addons_activation_time')) > 1643803820;
	}

}