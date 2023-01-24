<?php

// BuddyPress Shortcodes
require_once 'bp-shortcodes.php';

/*-----------------------------------------------------------------------------------*/
/*	Row Shortcode
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_row' ) ) {
	function kleo_row( $atts, $content = null ) {
		return '<div class="row">' . do_shortcode( $content ) . '</div>';
	}

	add_shortcode( 'kleo_row', 'kleo_row' );
}


/*-----------------------------------------------------------------------------------*/
/*	Column Shortcodes
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_one_third' ) ) {
	function kleo_one_third( $atts, $content = null ) {
		return '<div class="four columns">' . do_shortcode( $content ) . '</div>';
	}

	add_shortcode( 'kleo_one_third', 'kleo_one_third' );
}

if ( ! function_exists( 'kleo_two_third' ) ) {
	function kleo_two_third( $atts, $content = null ) {
		return '<div class="eight columns">' . do_shortcode( $content ) . '</div>';
	}

	add_shortcode( 'kleo_two_third', 'kleo_two_third' );
}

if ( ! function_exists( 'kleo_one_half' ) ) {
	function kleo_one_half( $atts, $content = null ) {
		return '<div class="six columns">' . do_shortcode( $content ) . '</div>';
	}

	add_shortcode( 'kleo_one_half', 'kleo_one_half' );
}

if ( ! function_exists( 'kleo_one' ) ) {
	function kleo_one( $atts, $content = null ) {
		return '<div class="twelve columns">' . do_shortcode( $content ) . '</div>';
	}

	add_shortcode( 'kleo_one', 'kleo_one' );
}

if ( ! function_exists( 'kleo_one_fourth' ) ) {
	function kleo_one_fourth( $atts, $content = null ) {
		return '<div class="three columns">' . do_shortcode( $content ) . '</div>';
	}

	add_shortcode( 'kleo_one_fourth', 'kleo_one_fourth' );
}

if ( ! function_exists( 'kleo_three_fourth' ) ) {
	function kleo_three_fourth( $atts, $content = null ) {
		return '<div class="nine columns">' . do_shortcode( $content ) . '</div>';
	}

	add_shortcode( 'kleo_three_fourth', 'kleo_three_fourth' );
}


/*-----------------------------------------------------------------------------------*/
/*	Buttons
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_button' ) ) {
	function kleo_button( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'url'    => '#',
			'target' => '_self',
			'style'  => 'standard',
			'size'   => 'standard',
			'round'  => '0',
			'icon'   => '',
			'class'  => ''
		), $atts ) );

		$before = '';
		$after  = '';
		if ( ! empty( $icon ) ) {
			$icondata = explode( ',', $icon );
			//if position is set
			if ( isset( $icondata[1] ) ) {
				if ( trim( $icondata[1] ) == 'before' ) {
					$before = '<i class="icon-' . $icondata[0] . '"> </i>&nbsp;';
				} else {
					$after = '&nbsp;<i class="icon-' . $icondata[0] . '"> </i>';
				}
			}
		}
		$round = ( $round == '0' ) ? "" : " " . $round;

		return '<a target="' . $target . '" class="button' . ( $size != 'standard' ? " " . $size : "" ) . ( $style != 'standard' ? " " . $style : "" ) . $round . ' ' . $class . '" href="' . $url . '">' . $before . do_shortcode( $content ) . $after . '</a>';
	}

	add_shortcode( 'kleo_button', 'kleo_button' );
}


/*-----------------------------------------------------------------------------------*/
/*	Alerts
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_alert' ) ) {
	function kleo_alert( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style' => 'standard',
			'class' => ''
		), $atts ) );

		return '<div data-alert class="alert-box' . ( $style == 'standard' ? "" : " " . $style ) . ' ' . $class . '"> ' . do_shortcode( $content ) . '<a href="#" class="close">&times;</a></div>';

	}

	add_shortcode( 'kleo_alert', 'kleo_alert' );
}


/*-----------------------------------------------------------------------------------*/
/*	Toggle Shortcode
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_toggle' ) ):
	function kleo_toggle( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'opened' => 'no',
			'icon'   => '',
			'title'  => '',
			'class'  => ''
		), $atts ) );

		if ( $opened == 'no' ) {
			$class = 'closed';
		} else {
			$class = '';
		}

		if ( ! empty( $icon ) ) {
			$icon_html = '<i class="icon icon-' . $icon . '"></i> ';
		} else {
			$icon_html = '';
		}

		$output = '
		<div class="kleo-toggle ' . $class . '">
				<h4 class="toggle-title"><a href="#">' . $icon_html . $title . '</a></h4>
				<div class="toggle-content ' . $class . '">' . do_shortcode( $content ) . '</div>  
		</div>';

		return $output;
	}

	add_shortcode( 'kleo_toggle', 'kleo_toggle' );
endif;


/*-----------------------------------------------------------------------------------*/
/*	Tabs Shortcodes
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_tabs' ) ) {
	function kleo_tabs( $atts, $content = null ) {
		$defaults = array(
			'class'    => '',
			'centered' => ''
		);

		extract( shortcode_atts( $defaults, $atts ) );
		STATIC $i = 0;
		$i ++;

		// Extract the tab titles for use in the tab widget.
		preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );

		$tab_titles = array();
		if ( isset( $matches[1] ) ) {
			$tab_titles = $matches[1];
		}

		$output = '';
		if ( $centered == 'yes' ) {
			$class = $class != '' ? ' centered-tabs' : 'centered-tabs';
		}

		if ( count( $tab_titles ) ) {

			$output .= '<dl class="tabs info custom ' . $class . '">';
			$cnt    = 1;
			foreach ( $tab_titles as $tab ) {
				$output .= '<dd ' . ( ( $cnt == 1 ) ? "class='active'" : "" ) . '><a href="#' . sanitize_title( $tab[0] ) . '">' . $tab[0] . '</a></dd>';
				$cnt ++;
			}

			$output .= '</dl>';
			$output .= '<div class="clearfix"></div>';
			$output .= '<ul class="tabs-content custom">';
			$output .= do_shortcode( $content );
			$output .= '</ul>';

		} else {
			$output .= do_shortcode( $content );
		}

		return $output;
	}

	add_shortcode( 'kleo_tabs', 'kleo_tabs' );
}

if ( ! function_exists( 'kleo_tab' ) ) {
	function kleo_tab( $atts, $content = null ) {
		$defaults = array( 'title' => 'Tab', 'active' => 0 );
		extract( shortcode_atts( $defaults, $atts ) );

		return '<li ' . ( $active == 1 ? 'class="active"' : '' ) . ' id="' . sanitize_title( $title ) . 'Tab" class="kleo-tab">' . do_shortcode( $content ) . '</li>';
	}

	add_shortcode( 'kleo_tab', 'kleo_tab' );
}


/*-----------------------------------------------------------------------------------*/
/*	Panel
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_panel' ) ) {
	function kleo_panel( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style' => 'standard',
			'round' => 0,
			'class' => ''
		), $atts ) );

		return '<div class="panel' . ( $style != 'standard' ? " " . $style : "" ) . ( $round == 1 ? " radius" : "" ) . ' ' . $class . '">' . do_shortcode( $content ) . '</div>';
	}

	add_shortcode( 'kleo_panel', 'kleo_panel' );
}


/*-----------------------------------------------------------------------------------*/
/*	Princing table Shortcodes
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_pricing_table' ) ) {
	function kleo_pricing_table( $atts, $content = null ) {
		$defaults = array(
			'title'       => '',
			'price'       => '',
			'description' => '',
			'class'       => ''
		);
		extract( shortcode_atts( $defaults, $atts ) );

		// Extract the tab titles for use in the tab widget.
		preg_match_all( '/\[([^\]]+)\](.*?)\[\/\1\]/uis', $content, $matches, PREG_OFFSET_CAPTURE );

		$rows = array();
		if ( isset( $matches[1] ) ) {
			$rows = $matches[1];
		}

		$output = '';

		if ( count( $rows ) ) {
			$output .= '<ul class="pricing-table ' . $class . '">';
			$output .= ' <li class="title">' . $title . '</li>';
			$output .= ' <li class="price">' . $price . '</li>';
			$output .= ' <li class="description">' . $description . '</li>';

			$output .= do_shortcode( $content );

			$output .= '</ul>';
		} else {
			$output .= do_shortcode( $content );
		}

		return $output;
	}

	add_shortcode( 'kleo_pricing_table', 'kleo_pricing_table' );
}

if ( ! function_exists( 'kleo_pricing_item' ) ) {
	function kleo_pricing_item( $atts, $content = null ) {
		$defaults = array();
		extract( shortcode_atts( $defaults, $atts ) );

		return '<li class="bullet-item">' . do_shortcode( $content ) . '</li>';
	}

	add_shortcode( 'kleo_pricing_item', 'kleo_pricing_item' );
}

/*-----------------------------------------------------------------------------------*/
/*	Progress bar
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_progress_bar' ) ) {
	function kleo_progress_bar( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style' => 'standard',
			'round' => 0,
			'width' => 50,
			'class' => ''
		), $atts ) );

		return '<div class="progress' . ( $style != 'standard' ? " " . $style : "" ) . ( $round == 1 ? " round" : "" ) . ' ' . $class . '"><span class="meter" style="width: ' . $width . '%"></span></div>';
	}

	add_shortcode( 'kleo_progress_bar', 'kleo_progress_bar' );
}


/*-----------------------------------------------------------------------------------*/
/*	Accordion Shortcode
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_accordion' ) ) {
	function kleo_accordion( $atts, $content = null ) {
		$defaults = array(
			'opened' => '1',
			'class'  => ''
		);

		extract( shortcode_atts( $defaults, $atts ) );
		$output = '';
		$output .= '<ul class="accordion ' . $class . '" data-default-opened=' . $opened . '>';
		$output .= do_shortcode( $content );
		$output .= '</ul>';
		$output .= '<div class="clearfix"></div><br/>';

		return $output;
	}

	add_shortcode( 'kleo_accordion', 'kleo_accordion' );
}

if ( ! function_exists( 'kleo_accordion_item' ) ) {
	function kleo_accordion_item( $atts, $content = null ) {
		$defaults = array( 'title' => '' );
		extract( shortcode_atts( $defaults, $atts ) );
		$output = '';
		$output .= '<li class="kleo-accordion-item ' . sanitize_title( $title ) . '">';
		$output .= '<h5 class="accordion-title">' . esc_html( $title ) . '<span class="accordion-icon"></span></h5>';
		$output .= '<div class="accordion-content">';
		$output .= do_shortcode( $content );
		$output .= '</div>';
		$output .= '</li>';

		return $output;
	}

	add_shortcode( 'kleo_accordion_item', 'kleo_accordion_item' );
}


/*-----------------------------------------------------------------------------------*/
/*	Colored Text
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_colored_text' ) ) {
	function kleo_colored_text( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'color' => '#F00056',
			'class' => ''
		), $atts ) );

		return '<span class="' . $class . '" style="color:' . $color . '"> ' . do_shortcode( $content ) . '</span>';

	}

	add_shortcode( 'kleo_colored_text', 'kleo_colored_text' );
}


/*-----------------------------------------------------------------------------------*/
/*	Lead Paragraph
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_lead_paragraph' ) ) {
	function kleo_lead_paragraph( $atts, $content = null ) {
		extract( shortcode_atts( array(), $atts ) );

		return '<p class="lead">' . do_shortcode( $content ) . '</p>';
	}

	add_shortcode( 'kleo_lead_paragraph', 'kleo_lead_paragraph' );
}


/*-----------------------------------------------------------------------------------*/
/*	Headings
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_h1' ) ) {
	function kleo_h1( $atts, $content = null ) {
		extract( shortcode_atts( array(), $atts ) );

		return '<h1>' . do_shortcode( $content ) . '</h1>';
	}

	add_shortcode( 'kleo_h1', 'kleo_h1' );
}

if ( ! function_exists( 'kleo_h2' ) ) {
	function kleo_h2( $atts, $content = null ) {
		extract( shortcode_atts( array(), $atts ) );

		return '<h2>' . do_shortcode( $content ) . '</h2>';
	}

	add_shortcode( 'kleo_h2', 'kleo_h2' );
}

if ( ! function_exists( 'kleo_h3' ) ) {
	function kleo_h3( $atts, $content = null ) {
		extract( shortcode_atts( array(), $atts ) );

		return '<h3>' . do_shortcode( $content ) . '</h3>';
	}

	add_shortcode( 'kleo_h3', 'kleo_h3' );
}

if ( ! function_exists( 'kleo_h4' ) ) {
	function kleo_h4( $atts, $content = null ) {
		extract( shortcode_atts( array(), $atts ) );

		return '<h4>' . do_shortcode( $content ) . '</h4>';
	}

	add_shortcode( 'kleo_h4', 'kleo_h4' );
}

if ( ! function_exists( 'kleo_h5' ) ) {
	function kleo_h5( $atts, $content = null ) {
		extract( shortcode_atts( array(), $atts ) );

		return '<h5>' . do_shortcode( $content ) . '</h5>';
	}

	add_shortcode( 'kleo_h5', 'kleo_h5' );
}

if ( ! function_exists( 'kleo_h6' ) ) {
	function kleo_h6( $atts, $content = null ) {
		extract( shortcode_atts( array(), $atts ) );

		return '<h6>' . do_shortcode( $content ) . '</h6>';
	}

	add_shortcode( 'kleo_h6', 'kleo_h6' );
}


/*-----------------------------------------------------------------------------------*/
/*	Video Buttons
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_button_video' ) ) {
	function kleo_button_video( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'url'   => '#',
			'style' => 'standard',
			'size'  => 'standard',
			'round' => '0',
			'icon'  => '',
			'class' => ''
		), $atts ) );

		if ( strpos( $url, '?' ) !== false ) {
			$url .= '&wmode=transparent';
		} else {
			$url .= '?wmode=transparent';
		}

		$before = '';
		$after  = '';
		if ( ! empty( $icon ) ) {
			$icondata = explode( ',', $icon );
			//if position is set
			if ( isset( $icondata[1] ) ) {
				if ( $icondata[1] == 'before' ) {
					$before = '<i class="icon-' . $icondata[0] . '"></i> ';
				} else {
					$after = ' <i class="icon-' . $icondata[0] . '"></i>';
				}
			}
		}

		$class = $class != '' ? ' video-modal ' . $class : ' video-modal';

		$output = '';
		$vid    = "vb" . rand( 99, 999 );
		$output .= '<a data-id="' . $vid . '" data-url="' . $url . '" class="button' . ( $size != 'standard' ? " " . $size : "" ) . ( $style != 'standard' ? " " . $style : "" ) . ( $round == '0' ? "" : " " . $round ) . $class . '" href="#">' . $before . do_shortcode( $content ) . $after . '</a>';

		return $output;
	}

	add_shortcode( 'kleo_button_video', 'kleo_button_video' );
}

/*-----------------------------------------------------------------------------------*/
/*	Section
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_section' ) ) {
	function kleo_section( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'bg'       => '',
			'centered' => 0,
			'border'   => 0,
			'class'    => '',
			'bg_size'  => 'auto'
		), $atts ) );

		return '<section ' . ( $bg == '' ? '' : 'style="background: url(\'' . esc_attr( $bg ) . '\');background-size:' . $bg_size . ';"' ) . ' class="section' . ( $border == 1 ? " with-border" : "" ) . ( $centered == 1 ? " text-center" : "" ) . ' ' . $class . '">' . do_shortcode( $content ) . '</section>';
	}

	add_shortcode( 'kleo_section', 'kleo_section' );
}


/*-----------------------------------------------------------------------------------*/
/*	Posts Carousel
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_posts_carousel' ) ) {
	function kleo_posts_carousel( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'cat'          => 'all',
			'limit'        => 9,
			'post_types'   => 'post',
			'post_formats' => 'image,gallery,video',
			'class'        => ''
		), $atts ) );

		$output = '<div class="kleo-carousel ' . $class . '">' .
		          '<p>
							<span class="right hide-for-small">
									<a href="#" class="story-prev"><i class="icon-circle-arrow-left icon-large"></i></a>&nbsp;
									<a href="#" class="story-next"><i class="icon-circle-arrow-right icon-large"></i></a>
							</span>
					</p>
					<div class="carousel-stories responsive">
						<ul class="feature-stories">';


		$args = array();
		if ( $cat != '' ) {
			$args['category_name'] = $cat;
		}
		if ( (int) $limit != 0 ) {
			$args['posts_per_page'] = $limit;
		}

		$args['post_type'] = explode( ',', $post_types );

		$formats = explode( ',', $post_formats );
		if ( is_array( $formats ) && ! in_array( 'all', $formats ) ) {
			foreach ( $formats as $format ) {
				$terms_query[] = 'post-format-' . $format;
			}
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $terms_query
				)
			);
		}

		$latestPosts = new WP_Query( apply_filters( 'kleo_posts_carousel_args', $args ) );

		while ( $latestPosts->have_posts() ) : $latestPosts->the_post();

			switch ( get_post_format() ) {
				case 'video':
					$video = get_cfield( 'embed' );
					if ( ! empty( $video ) ) {
						$output .= '<li>';
						$output .= wp_oembed_get( $video );

						$output .= '<h4>' . get_the_title() . '</h4>';
						$output .= '<p>' . word_trim( get_the_excerpt(), 15, '...' ) . '</p>';
						$output .= '<p><a href="' . get_permalink() . '" class="small button radius secondary">' .
						           '<i class="icon-angle-right"></i> ' .
						           esc_html__( "READ MORE", 'sweetdate' ) .
						           '</a></p>';
						$output .= '</li>';
					}

					break;

				case 'gallery':
					$slides = get_cfield( 'slider' );
					if ( ! empty( $slides ) ) {
						$output .= '<li>';
						$output .= '<div class="blog-slider">';

						foreach ( $slides as $slide ) {
							if ( get_attachment_id_from_url( $slide ) ) {
								$thumb_array = image_downsize( get_attachment_id_from_url( $slide ), 'blog_carousel' );
								$thumb_path  = $thumb_array[0];
							} else {
								$thumb_path = $slide;
							}

							$output .= '<div data-thumb="' . $slide . '">';
							$output .= '<img src="' . $thumb_path . '" alt="">';
							$output .= '</div>';
						}
						$output .= '</div><!--end blog-slider-->';

						$output .= '<h4>' . get_the_title() . '</h4>';
						$output .= '<p>' . word_trim( get_the_excerpt(), 15, '...' ) . '</p>';
						$output .= '<p><a href="' . get_permalink() . '" class="small button radius secondary"><i class="icon-angle-right"></i> ' .
						           esc_html__( "READ MORE", 'sweetdate' ) . '</a></p>';
						$output .= '</li>';
					}

					break;

				case 'image':
					if ( get_post_thumbnail_id() ) {
						$output .= '<li>';
						$output .= '<div class=""><a class="imagelink" href="' . get_permalink() . '">';
						$output .= '<span class="read"><i class="icon-' . apply_filters( 'kleo_img_rounded_icon', 'heart' ) . '"></i></span>';
						$output .= get_the_post_thumbnail( null, 'blog_carousel' );
						$output .= '</a></div>';

						$output .= '<h4>' . get_the_title() . '</h4>';
						$output .= '<p>' . word_trim( get_the_excerpt(), 15, '...' ) . '</p>';
						$output .= '<p><a href="' . get_permalink() . '" class="small button radius secondary"><i class="icon-angle-right"></i> ' .
						           esc_html__( "READ MORE", 'sweetdate' ) . '</a></p>';
						$output .= '</li>';

					}
					break;
				default:
					$output .= '<li>';
					if ( get_post_thumbnail_id() ) {
						$output .= '<div class=""><a class="imagelink" href="' . get_permalink() . '">';
						$output .= '<span class="read"><i class="icon-' . apply_filters( 'kleo_img_rounded_icon', 'heart' ) . '"></i></span>';
						$output .= get_the_post_thumbnail( null, 'blog_carousel' );
						$output .= '</a></div>';
					}
					$output .= '<h4>' . get_the_title() . '</h4>';
					$output .= '<p>' . word_trim( get_the_excerpt(), 15, '...' ) . '</p>';
					$output .= '<p><a href="' . get_permalink() . '" class="small button radius secondary"><i class="icon-angle-right"></i> ' .
					           esc_html__( "READ MORE", 'sweetdate' ) . '</a></p>';
					$output .= '</li>';
					break;
			}

		endwhile;
		wp_reset_postdata();
		$output .= '</ul></div><!--end carousel-stories-->' .
		           '</div>';

		return $output;
	}

	add_shortcode( 'kleo_posts_carousel', 'kleo_posts_carousel' );
}


/*-----------------------------------------------------------------------------------*/
/*	Accordion Shortcode
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_slider' ) ) {
	function kleo_slider( $atts, $content = null ) {
		$defaults = array();

		extract( shortcode_atts( $defaults, $atts ) );
		$output = '';
		$output .= '<div class="article-media clearfix"><div class="blog-slider">';
		$output .= do_shortcode( $content );
		$output .= '</div></div>';

		return $output;
	}

	add_shortcode( 'kleo_slider', 'kleo_slider' );
}

if ( ! function_exists( 'kleo_slider_image' ) ) {
	function kleo_slider_image( $atts, $content = null ) {
		$defaults = array( 'src' => '' );
		extract( shortcode_atts( $defaults, $atts ) );
		$output = '';
		$output .= '<div data-thumb="' . esc_attr( $src ) . '">';
		$output .= '<img src="' . esc_attr( $src ) . '" alt="">';
		$output .= '</div>';

		return $output;
	}

	add_shortcode( 'kleo_slider_image', 'kleo_slider_image' );
}


/*-----------------------------------------------------------------------------------*/
/*	Icon
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_icon' ) ) {
	function kleo_icon( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'icon' => '',
			'size' => 'normal'
		), $atts ) );

		return '<i class="icon icon-' . $icon . ( $size == 'normal' ? '' : ' icon-' . $size ) . '"></i>';
	}

	add_shortcode( 'kleo_icon', 'kleo_icon' );
}


/*-----------------------------------------------------------------------------------*/
/*	Restrict content
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_only_members' ) ) {
	function kleo_only_members( $atts, $content = null ) {
		if ( is_user_logged_in() ) {
			return do_shortcode( $content );
		} else {
			return '';
		}
	}

	add_shortcode( 'kleo_only_members', 'kleo_only_members' );
}

if ( ! function_exists( 'kleo_only_guests' ) ) {
	function kleo_only_guests( $atts, $content = null ) {
		if ( ! is_user_logged_in() ) {
			return do_shortcode( $content );
		} else {
			return '';
		}
	}

	add_shortcode( 'kleo_only_guests', 'kleo_only_guests' );
}

/*-----------------------------------------------------------------------------------*/
/*	Post articles
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'kleo_articles' ) ) :
	function kleo_articles( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'cat'          => 'all',
			'limit'        => 9,
			'post_types'   => 'post',
			'post_formats' => 'all',
			'display'      => '',
			'show_meta'    => 'disable',
			'columns'      => 'four'
		), $atts ) );

		$args = array();
		if ( (int) $cat != 0 ) {
			$args['cat'] = $cat;
		}
		if ( (int) $limit != 0 ) {
			$args['posts_per_page'] = $limit;
		}

		$args['post_type'] = explode( ',', $post_types );

		$formats = explode( ',', $post_formats );
		if ( is_array( $formats ) && ! in_array( 'all', $formats ) ) {
			foreach ( $formats as $format ) {
				$terms_query[] = 'post-format-' . $format;
			}
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $terms_query
				)
			);
		}

		//custom excerpt
		add_filter( 'excerpt_length', function () {
			return 20;
		}, 999 );

		$latestPosts = new WP_Query( apply_filters( 'kleo_articles_args', $args ) );

		ob_start();
		if ( $latestPosts->have_posts() ) :

			echo '<div class="row grid_articles">';

			/* Start the Loop */
			while ( $latestPosts->have_posts() ) : $latestPosts->the_post();

				if ( $display == 'grid' ): ?>

					<div class="<?php echo $columns; ?> columns">
						<div class="row">

							<?php
							$slides = get_cfield( 'slider' );
							$video  = get_cfield( 'embed' );
							$audio  = get_cfield( 'audio' );

							if ( ! empty( $video ) ) { ?>
								<div class="twelve columns">
									<div class="article-media clearfix">
										<?php echo wp_oembed_get( $video ); ?>
									</div><!--end article-media-->
								</div><!--end twelve-->
							<?php } elseif ( ! empty( $slides ) ) {
								?>
								<div class="twelve columns">
									<div class="article-media clearfix">
										<div class="blog-slider">
											<?php foreach ( $slides as $slide ) {
												echo '<div data-thumb="' . $slide . '">';
												echo '<img src="' . $slide . '" alt="">';
												echo '</div>';
											}
											?>
										</div><!--end blog-slider-->
									</div><!--end article-media-->
								</div><!--end twelve-->

							<?php } elseif ( ! empty( $audio ) ) {
								wp_enqueue_script( 'mediaelement' );
								wp_enqueue_style( 'mediaelement' );
								?>
								<div class="twelve columns">
									<div class="article-media clearfix">
										<script>
											jQuery(document).ready(function () {
												jQuery('audio#audio_<?php the_id();?>').mediaelementplayer(/* Options */);
											});
										</script>
										<audio id="audio_<?php the_id(); ?>" style="width:100%;"
										       src="<?php echo $audio; ?>"></audio>
									</div><!--end article-media-->
								</div><!--end twelve-->

							<?php } else if ( get_post_thumbnail_id() ) { ?>
								<div class="twelve columns">
									<div class="article-media clearfix">
										<?php the_post_thumbnail(); ?>
									</div><!--end article-media-->
								</div><!--end twelve-->
							<?php } ?>

							<div class="twelve columns">
								<h4 class="article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h4>
							</div><!--end twelve-->

							<?php if ( $show_meta == 'enable' ): ?>
								<div class="twelve columns">
									<div class="article-meta clearfix">
										<ul class="link-list">
											<?php sweetdate_entry_meta(); ?>
										</ul>
									</div><!--end article-meta-->
								</div>
							<?php endif; ?>

							<div class="twelve columns">
								<div class="article-content">
									<?php the_excerpt(); ?>
								</div><!--end article-content-->
							</div><!--end twelve-->

						</div>
					</div>

				<?php
				else:
					get_template_part( 'content', get_post_format() );
				endif;

			endwhile;
			echo '</div><!--end row-->';

			wp_reset_postdata();

		else :
			get_template_part( 'content', 'none' );
		endif;

		$output = ob_get_clean();

		return $output;
	}

	add_shortcode( 'kleo_articles', 'kleo_articles' );
endif;


/*-----------------------------------------------------------------------------------*/
/*	bbPress statistics
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_bbpress_stats' ) ) {
	function kleo_bbpress_stats( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'type' => '',
		), $atts );

		if ( ! class_exists( 'bbPress' ) ) {
			return "0";
		}

		if ( $a['type'] == 'forums' ) {
			$forum_count = wp_count_posts( bbp_get_forum_post_type() )->publish;

			return $forum_count;

		} else if ( $a['type'] == 'replies' ) {
			$all_replies = wp_count_posts( bbp_get_reply_post_type() )->publish;

			return $all_replies;
		} else if ( $a['type'] == 'topics' ) {
			$all_topics = wp_count_posts( bbp_get_topic_post_type() )->publish;

			return $all_topics;
		}

	}

	add_shortcode( 'kleo_bbpress_stats', 'kleo_bbpress_stats' );

}


/*-----------------------------------------------------------------------------------*/
/*	Call to action box
 *      Create your own kleo_call_to_action() to override in a child theme.
 */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_call_to_action' ) ) {
	function kleo_call_to_action( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'bg'      => '',
			'bg_size' => 'contain',
			'class'   => ''
		), $atts ) );

		if ( $class != '' ) {
			$class = ' ' . $class;
		}
		$data = '';
		if ( $bg != '' ) {
			$data .= ' background: url(\'' . esc_attr( $bg ) . '\') no-repeat center center;';
		}
		if ( $bg_size != '' ) {
			$data .= ' background-size: ' . $bg_size . ';';
		}

		if ( $data != '' ) {
			$data = ' style="' . $data . '"';
		}

		$output = '';
		$output .= '<div id="call-to-actions">';
		$output .= '<div class="row' . $class . '"' . $data . '>';
		$output .= do_shortcode( $content );
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	add_shortcode( 'kleo_call_to_action', 'kleo_call_to_action' );
}

/*-----------------------------------------------------------------------------------*/
/*	Rounded image
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_img_rounded' ) ) {
	function kleo_img_rounded( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'src'   => '',
			'class' => '',
			'link'  => ''
		), $atts ) );

		if ( $link ) {
			$data = 'href="' . $link . '"';
		} else {
			$data = 'data-rel="prettyPhoto[gallery1]" href="' . $src . '"';
		}
		$output = '<div class="circle-image ' . $class . '">';
		$output .= '  <a class="imagelink" ' . $data . '>
				<span class="overlay"></span>
				<span class="read"><i class="icon-' . apply_filters( 'kleo_img_rounded_icon', 'heart' ) . '"></i></span>
				<img src="' . $src . '" alt="">
			</a>
		</div>';

		return $output;
	}

	add_shortcode( 'kleo_img_rounded', 'kleo_img_rounded' );
}
