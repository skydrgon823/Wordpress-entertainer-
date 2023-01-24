<?php
/*-----------------------------------------------------------------------------------*/
/*	Shortcode - Status icon
/*-----------------------------------------------------------------------------------*/


//MAIN SEARCH SHORTCODE VERTICAL
if ( function_exists( 'kleo_bp_search_form' ) ) {
	add_shortcode( 'kleo_search_members', 'sweet_search_members' );
	function sweet_search_members( $atts, $content ) {
		$profiles = $before = '';

		extract( shortcode_atts( array(
			'before'   => '',
			'profiles' => 1
		), $atts ) );

		ob_start();
		kleo_bp_search_form( $profiles, $before );

		return ob_get_clean();
	}
}


// HORIZONTAL
if ( function_exists( 'kleo_bp_search_form_horizontal' ) ) {
	add_shortcode( 'kleo_search_members_horizontal', 'sweet_search_members_horizontal' );
	function sweet_search_members_horizontal( $atts, $content ) {
		$button = $before = '';
		extract( shortcode_atts( array(
			'button' => 1,
			'before' => ''
		), $atts ) );

		ob_start();
		kleo_bp_search_form_horizontal( $button, $before );

		return ob_get_clean();
	}
}

/* Deprecated */
if ( ! function_exists( 'kleo_status_icon' ) ) {
	function kleo_status_icon( $atts, $content = null ) {
		$field = $value = $online = $type = $image = $subtitle = $class = $href = '';
		extract( shortcode_atts( array(
			'field'    => '',
			'value'    => '',
			'online'   => 'yes',
			'type'     => 'total',
			'image'    => '',
			'subtitle' => '',
			'class'    => '',
			'href'     => ''
		), $atts ) );

		switch ( $type ) {
			case 'total':
				$image  = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_01.png' : $image;
				$number = bp_get_total_member_count();
				break;
			case 'members_online':
				$image  = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_02.png' : $image;
				$number = bp_get_online_users();
				break;
			case 'women_online':
				$image  = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_03.png' : $image;
				$number = bp_get_online_users( "Woman" );
				break;
			case 'men_online':
				$image  = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_04.png' : $image;
				$number = bp_get_online_users( "Man" );
				break;
			case 'custom':
				$image = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_01.png' : $image;

				$field  = $field != '' ? $field : false;
				$value  = $value != '' ? $value : false;
				$online = $online == 'yes' ? true : false;
				$number = bp_member_statistics( $field, $value, $online );
				break;

			default:
				if ( $type == 'Man' ) {
					$image = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_04.png' : $image;
				} elseif ( $type == 'Woman' ) {
					$image = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_03.png' : $image;
				} else {
					$image = ( $image == '' ) ? get_template_directory_uri() . '/assets/images/icons/steps/status_01.png' : $image;
				}
				$number = bp_get_online_users( $type );
				break;
		}

		$output = '<div class="status three columns mobile-one ' . $class . '">';
		$output .= '<div data-animation="pulse" class="icon">';
		if ( $href != '' ) {
			$output .= '<a href="' . $href . '">';
		}
		$output .= '<img width="213" height="149" alt="" src="' . $image . '">';
		if ( $href != '' ) {
			$output .= '</a>';
		}
		$output .= '</div>';
		$output .= '<ul class="block-grid">';
		$output .= '<li class="title">' . $number . '</li>';
		$output .= '<li class="subtitle">' . $subtitle . '</li>';
		$output .= '</ul>';
		$output .= '</div>';

		return $output;
	}

	add_shortcode( 'kleo_status_icon', 'kleo_status_icon' );
}

/*-----------------------------------------------------------------------------------*/
/*	Shortcodes
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'kleo_member_stats' ) ) {
	$field = $value = $online = '';
	function kleo_member_stats( $atts, $content = null ) {
		$field = $value = $online = '';
		extract( shortcode_atts( array(
			'field'  => '',
			'value'  => '',
			'online' => ''
		), $atts ) );

		$field  = $field != '' ? $field : false;
		$value  = $value != '' ? $value : false;
		$online = $online == 'yes' ? true : false;

		return bp_member_statistics( $field, $value, $online );
	}

	add_shortcode( 'kleo_member_stats', 'kleo_member_stats' );
}


if ( ! function_exists( 'kleo_total_members' ) ) {
	function kleo_total_members( $atts, $content = null ) {
		return bp_get_total_member_count();
	}

	add_shortcode( 'kleo_total_members', 'kleo_total_members' );
}

if ( ! function_exists( 'kleo_members_online' ) ) {
	function kleo_members_online( $atts, $content = null ) {
		$field_name = $field = '';
		extract( shortcode_atts( array(
			'field_name' => false,
			'field'      => false,
		), $atts ) );

		return bp_get_online_users( $field, $field_name );
	}

	add_shortcode( 'kleo_members_online', 'kleo_members_online' );
}

if ( ! function_exists( 'kleo_women_online' ) ) {
	function kleo_women_online( $atts, $content = null ) {
		$field = '';
		extract( shortcode_atts( array(
			'field' => 'Woman',
		), $atts ) );

		return bp_get_online_users( $field );
	}

	add_shortcode( 'kleo_women_online', 'kleo_women_online' );
}

if ( ! function_exists( 'kleo_men_online' ) ) {
	function kleo_men_online( $atts, $content = null ) {
		$field = '';
		extract( shortcode_atts( array(
			'field' => 'Man',
		), $atts ) );

		return bp_get_online_users( $field );
	}

	add_shortcode( 'kleo_men_online', 'kleo_men_online' );
}


//Top members
if ( ! function_exists( 'kleo_top_members' ) ) {
	function kleo_top_members( $atts, $content = null ) {
		$number = $class = '';
		extract( shortcode_atts( array(
			'number' => '6',
			'class'  => ''
		), $atts ) );

		$output = '

    <div class="section-members ' . $class . '">
        <div class="item-options" id="members-list-options">
          <a href="' . bp_get_members_directory_permalink() . '" data-id="newest" class="members-switch">' . esc_html__( "Newest", 'sweetdate' ) . '</a>
          <a href="' . bp_get_members_directory_permalink() . '" data-id="active" class="selected members-switch">' . esc_html__( "Active", 'sweetdate' ) . '</a>
          <a href="' . bp_get_members_directory_permalink() . '" data-id="popular" class="members-switch">' . esc_html__( "Popular", 'sweetdate' ) . '</a>
        </div>';

		$output .= '<ul class="item-list kleo-bp-active-members">';
		if ( bp_has_members( bp_ajax_querystring( 'members' ) . '&type=active&max=' . $number . '&per_page=' . $number ) ) :
			while ( bp_members() ) : bp_the_member();
				$output .= section_members_li();

			endwhile;
		endif;
		$output .= '</ul>';

		$output .= '<ul class="item-list kleo-bp-newest-members" style="display:none;">';
		if ( bp_has_members( bp_ajax_querystring( 'members' ) . '&type=newest&max=' . $number . '&per_page' . $number ) ) :
			while ( bp_members() ) : bp_the_member();
				$output .= section_members_li( 'newest' );

			endwhile;
		endif;
		$output .= '</ul>';

		$output .= '<ul class="item-list kleo-bp-popular-members" style="display:none;">';
		if ( bp_has_members( bp_ajax_querystring( 'members' ) . '&type=popular&max=' . $number . '&per_page' . $number ) ) :
			while ( bp_members() ) : bp_the_member();
				$output .= section_members_li( 'popular' );

			endwhile;
		endif;
		$output .= '</ul>';

		$output .= '</div><!--end section-members-->';

		$output .= <<<JS
<script type="text/javascript">
jQuery(document).ready(function() {

    jQuery(".members-switch").click(function() {
        var bpMembersContext = jQuery(this).parent().parent();
        var container = "ul.kleo-bp-"+jQuery(this).attr('data-id')+"-members";

        jQuery("ul.item-list", bpMembersContext).hide();
        jQuery(".members-switch").removeClass("selected");
        jQuery(this).addClass("selected");
        jQuery(container, bpMembersContext).show(0, function() {
            jQuery(container+" li").hide().each(function (i) {
                var delayInterval = 150; // milliseconds
                jQuery(this).delay(i * delayInterval).fadeIn();
            });
        });
        return false;
    });
});

jQuery(function () {
	if (!isMobile()) {
		jQuery('.kleo-bp-active-members').hide();
		jQuery('.section-members').one('inview', function (event, visible) {
		  if (visible) {
			  var container = ".kleo-bp-active-members";
			  jQuery(container).show(0, function() {
				  jQuery(container+" li").hide().each(function (i) {
					  var delayInterval = 150; // milliseconds
					  jQuery(this).delay(i * delayInterval).fadeIn();
				  });
			  });
		  }
		});
	}

});

</script>
JS;

		return $output;

	}

	add_shortcode( 'kleo_top_members', 'kleo_top_members' );

}

//render member list item
function section_members_li( $type = 'active' ) {
	$output =
		'<li class="two columns mobile-two top-' . $type . '-members">
        <div class="item-avatar">
          <a href="' . bp_get_member_permalink() . '" title="' . bp_get_member_name() . '">' . bp_get_member_avatar( 'type=thumb&width=125&height=125' ) . '</a>
        </div><!--end item-avatar-->
        <div class="item">
          <div class="item-title fn"><a href="' . bp_get_member_permalink() . '" title="' . bp_get_member_name() . '">' . bp_get_member_name() . '</a></div>
          <div class="item-meta">
            <span class="activity">';

	switch ( $type ) {
		case 'newest':
			$output .= bp_get_member_registered();
			break;

		case 'popular':
			if ( function_exists( 'bp_get_member_total_friend_count' ) ) {
				$output .= bp_get_member_total_friend_count();
			} else {
				$output .= bp_get_member_last_active();
			}

			break;

		case 'active':
		default:
			$output .= bp_get_member_last_active();
			break;
	}

	$output .= '</span>
          </div>
        </div><!--end item-->
    </li>';

	return $output;
}

//Recent Groups
if ( ! function_exists( 'kleo_recent_groups' ) ) {
	function kleo_recent_groups( $atts, $content = null ) {
		$class = $max = '';
		extract( shortcode_atts( array(
			'class' => '',
			'max'   => 4
		), $atts ) );

		$output = '';
		if ( function_exists( 'bp_has_groups' ) && bp_has_groups( bp_ajax_querystring( 'groups' ) . "&type=active&max=" . apply_filters( 'kleo_recent_groups_max', $max ) ) ) :

			$output .= '<div id="groups" class="' . $class . '">';
			while ( bp_groups() ) : bp_the_group();
				//$members_no = preg_replace('/\D/', '', bp_get_group_member_count());
				global $groups_template;
				$members_no = $groups_template->group->total_member_count;
				$output     .= '
					<div class="six columns group-item">
						<div class="five columns">
							<div class="item-header-avatar">
								<div class="circular-item" title="">
									<small class="icon">' . esc_html__( "members", 'sweetdate' ) . '</small>
									<input type="text" value="' . $members_no . '" class="pinkCircle">
								</div>
								' . bp_get_group_avatar( 'type=full&width=300&height=300' ) . '
							</div>
						</div>
						<h4><a href="' . bp_get_group_permalink() . '">' . bp_get_group_name() . '</a></h4>
						<p>' . char_trim( strip_tags( bp_get_group_description_excerpt() ), 60, '...' ) . '</p>
						<p><a href="' . bp_get_group_permalink() . '">' . __( "View group", 'sweetdate' ) . ' <i class="icon-caret-right"></i></a></p>
					</div><!--end six-->';

			endwhile;
			$output .= '</div><div class="clear clearfix"></div>';

			$output .= <<<JS
<script type="text/javascript">
jQuery(function () {
    if (!isMobile()) {
		jQuery(".item-header-avatar img").each(function (i) {
			jQuery(this).attr('data-src' ,jQuery(this).attr('src'));
			jQuery(this).attr('src', kleoFramework.blank_img);
		});

		jQuery('#groups').one('inview', function (event, visible) {
			if (visible) {
				var container = "#groups";

				jQuery(container+" .item-header-avatar img").each(function (i) {
					var element = jQuery(this);
					var delayInterval = 250; // milliseconds
					jQuery(this).delay(i * delayInterval).fadeOut(function() { element.attr('src' ,jQuery(this).attr('data-src')); element.fadeIn() });
				});

			}
		});
	}

});
</script>
JS;

		endif;

		return $output;
	}

	add_shortcode( 'kleo_recent_groups', 'kleo_recent_groups' );
}

//Members Shortcode
if ( ! function_exists( 'kleo_members' ) ) {
	/**
	 * Display members list
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function kleo_members( $atts, $content = null ) {
		global $bp_results;
		$show_filter = '';
		extract( shortcode_atts( array(
			'show_filter' => 'no'
		), $atts ) );

		$output = '<div class="search-result">';

		if ( $show_filter == 'yes' ) {
			$lead = '';
			if ( isset( $_GET['bs'] ) && $bp_results['users'][0] != 0 ) {
				$lead = esc_html__( "Your search returned", 'sweetdate' ) . " " . count( $bp_results['users'] ) . " " . _n( 'member', 'members', count( $bp_results['users'] ), 'sweetdate' );
			}

			$output .= '<p class="lead">' . $lead . '</p>

			<div class="item-list-tabs" role="navigation">
				<ul>
					<li class="selected" id="members-all"><a href="' . trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() ) . '">';
			$output .= sprintf( __( 'All Members <span>%s</span>', 'buddypress' ), bp_get_total_member_count() ) . '</a></li>';

			if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) :

				$output .= '<li id="members-personal"><a href="' . bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/">' . sprintf( __( 'My Friends <span>%s</span>', 'buddypress' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ) . '</a></li>';

			endif;
			$output .= '</ul>
			</div><!-- .item-list-tabs -->';
		}

		$output .= '<div id="members-dir-list" class="members dir-list">
			<!--Search List-->
			<div class="search-list twelve">';
		ob_start();
		locate_template( array( 'members/members-loop.php' ), true );
		$output .= ob_get_contents();
		ob_end_clean();
		$output .= '</div><!--end Search List-->
		</div><!-- #members-dir-list --></div>';

		return $output;
	}

	add_shortcode( 'kleo_members', 'kleo_members' );
}


//Members Carousel
if ( ! function_exists( 'kleo_members_carousel' ) ):
	function kleo_members_carousel( $atts, $content = null ) {
		$type = $image_size = $total = $width = $height = $class = '';
		extract( shortcode_atts( array(
			'type'       => apply_filters( 'kleo_bps_carousel_members_type', 'newest' ),
			'image_size' => 'thumb',
			'total'      => sq_option( 'buddypress_perpage' ),
			'width'      => '94',
			'height'     => '94',
			'class'      => ''
		), $atts ) );

		$output = '<div class="kleo_members_carousel ' . $class . '">' .
		          '<p>' .
		          '<span class="right hide-for-small">' .
		          '<a href="#" class="profile-thumbs-prev"><i class="icon-circle-arrow-left icon-large"></i></a>&nbsp;' .
		          '<a href="#" class="profile-thumbs-next"><i class="icon-circle-arrow-right icon-large"></i></a>' .
		          '</span>' .
		          '</p>' .
		          '<div class="clearfix"></div>' .
		          '<div class="carousel-profiles responsive">' .
		          '<ul class="profile-thumbs">';

		if ( bp_has_members( bp_ajax_querystring( 'members' ) . '&type=' . $type . '&per_page=' . $total ) ) :
			while ( bp_members() ) : bp_the_member();
				$output .= '<li><a href="' . bp_get_member_permalink() . '">' . bp_get_member_avatar( 'type=' . $image_size . '&width=' . $width . '&height=' . $height . '&class=' ) . '</a></li>';
			endwhile;
		endif;
		$output .= '</ul>' .
		           '</div><!--end carousel-profiles-->' .
		           '</div>';

		return $output;
	}

	add_shortcode( 'kleo_members_carousel', 'kleo_members_carousel' );
endif;

/* Register form shortcode */
if ( ! function_exists( 'kleo_register_form' ) ) {
	function kleo_register_form( $atts, $content = null ) {
		$profiles = $title = $details = '';
		extract( shortcode_atts( array(
			'profiles' => 1,
			'title'    => '',
			'details'  => ''
		), $atts ) );

		global $bp_reg_form_show_cols, $bp_reg_form_show_carousel, $bp_reg_form_title,
		       $bp_reg_form_details;

		$bp_reg_form_show_cols     = true;
		$bp_reg_form_show_carousel = $profiles;
		$bp_reg_form_title         = $title;
		$bp_reg_form_details       = $details;

		ob_start();
		get_template_part( 'page-parts/home-register-form' );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	add_shortcode( 'kleo_register_form', 'kleo_register_form' );
}
