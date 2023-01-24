<?php
/**
 * Buddypress functions
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */


/* Remove BuddyPress loaded style */
add_action( 'wp_enqueue_scripts', 'kleo_bp_dequeue', 9 );

function kleo_bp_dequeue() {
	if ( is_rtl() ) {

		if ( is_child_theme() && file_exists( get_stylesheet_directory() . '/buddypress/css/buddypress.css' ) ) {
			$location = get_stylesheet_directory() . '/buddypress/css/buddypress.css';
		} else {
			$location = get_template_directory_uri() . '/buddypress/css/buddypress.css';
		}

		wp_enqueue_style( 'bp-legacy-css-rtl', $location, [], BP_VERSION );
	}
}

//Members Search fields values
global $bp_search_fields;
$bp_search_fields = sq_option( 'bp_search_form' );

/*
 * Copy bp-custom.php to plugins directory
 *
 */
if ( ! get_option( 'sweetdate' . "_bp_custom_" . SQUEEN_THEME_VERSION ) && ! file_exists( WP_PLUGIN_DIR . "/bp-custom.php" ) ) {
	if ( copy( FRAMEWORK_URL . "/inc/bp-custom.php", WP_PLUGIN_DIR . "/bp-custom.php" ) ) {
		add_option( 'sweetdate' . "_bp_custom_" . SQUEEN_THEME_VERSION, 1 );
	}
}
// -----------------------------------------------------------------------------


/*
 * Custom group search form
 *
 */
if ( ! function_exists( 'bp_my_directory_groups_search_form' ) ) :
	function bp_my_directory_groups_search_form() {

		$default_search_value = bp_get_search_default_text( 'groups' );
		$search_value         = ! empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value; ?>

        <form action="" method="get" id="search-groups-form" class="custom">
            <div class="row collapse">
                <div class="nine columns">
                    <label><input type="text" name="s" id="groups_search"
                                  placeholder="<?php echo esc_attr( $search_value ) ?>"/></label>
                </div>
                <div class="three columns">
                    <input class="button small radius secondary expand postfix" type="submit" id="groups_search_submit"
                           name="groups_search_submit" value="<?php _e( 'Search', 'sweetdate' ) ?>"/>
                </div>
            </div>
        </form>

		<?php
	}
endif;


/*
 * Custom blogs search form
 *
 */
if ( ! function_exists( 'bp_my_directory_blogs_search_form' ) ) :
	function bp_my_directory_blogs_search_form() {

		$default_search_value = bp_get_search_default_text();
		$search_value         = ! empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value; ?>

        <form action="" method="get" id="search-blogs-form">
            <div class="row collapse">
                <div class="nine columns">
                    <label><input type="text" name="s" id="blogs_search"
                                  placeholder="<?php echo esc_attr( $search_value ) ?>"/></label>
                </div>
                <div class="three columns">
                    <input type="submit" id="blogs_search_submit" class="button small radius secondary expand postfix"
                           name="blogs_search_submit" value="<?php _e( 'Search', 'sweetdate' ) ?>"/>
                </div>
            </div>
        </form>

		<?php
	}
endif;


if ( ! function_exists( 'bp_signup_password_value' ) ):
	function bp_signup_password_value() {
		echo bp_get_signup_password_value();
	}

	function bp_get_signup_password_value() {
		$value = '';
		if ( isset( $_POST['signup_password'] ) ) {
			$value = $_POST['signup_password'];
		}

		return apply_filters( 'bp_get_signup_password_value', $value );
	}
endif;

if ( ! function_exists( 'bp_signup_password_confirm_value' ) ):
	function bp_signup_password_confirm_value() {
		echo bp_get_signup_password_confirm_value();
	}

	function bp_get_signup_password_confirm_value() {
		$value = '';
		if ( isset( $_POST['signup_password_confirm'] ) ) {
			$value = $_POST['signup_password_confirm'];
		}

		return apply_filters( 'bp_get_signup_password_confirm_value', $value );
	}
endif;


/*
 * Add Prev,Next links after breadcrumb if it is a profile page
 */
function bp_add_profile_navigation() {
	if ( bp_is_user() ): ?>

        <div class="three columns">
            <ul class="inline-list right">
                <li><?php esc_html_e( "Quick profile navigation", 'sweetdate' ); ?> </li>
				<?php $prev = bp_prev_profile( bp_displayed_user_id() );
				if ( $prev !== "#" ) : ?>
                    <li>
                    <a href="<?php echo esc_url( $prev ); ?>"
                       title="<?php esc_html_e( "Previous profile", 'sweetdate' ); ?>">
                        <i class="icon-chevron-left"></i>
                    </a>
                    </li><?php endif; ?>
				<?php $next = bp_next_profile( bp_displayed_user_id() );
				if ( $next !== "#" ) : ?>
                    <li>
                    <a href="<?php echo esc_url( $next ); ?>"
                       title="<?php esc_html_e( "Next profile", 'sweetdate' ); ?>">
                        <i class="icon-chevron-right"></i>
                    </a>
                    </li><?php endif; ?>
            </ul>
        </div>

	<?php endif;

}

add_action( 'kleo_after_breadcrumb', 'bp_add_profile_navigation' );

/**
 * Get next profile link
 *
 * @param int $current_id Displayer user ID
 *
 * @return string User link
 */
if ( ! function_exists( 'bp_next_profile' ) ):
	function bp_next_profile( $current_id ) {
		global $wpdb;

		$extra = '';
		$obj   = new stdClass();
		do_action_ref_array( 'bp_pre_user_query_construct', array( &$obj ) );
		if ( isset( $obj->query_vars ) && $obj->query_vars && $obj->query_vars['exclude'] && is_array( $obj->query_vars['exclude'] ) && ! empty( $obj->query_vars['exclude'] ) ) {
			$extra = " AND us.ID NOT IN (" . implode( ",", $obj->query_vars['exclude'] ) . ")";
		}

		$sql = "SELECT MIN(us.ID) FROM " . $wpdb->base_prefix . "users us"
		       . " JOIN " . $wpdb->base_prefix . "bp_xprofile_data bp ON us.ID = bp.user_id"
		       . " JOIN " . $wpdb->base_prefix . "usermeta um ON um.user_id = us.ID"
		       . " WHERE um.meta_key = 'last_activity' AND us.ID > $current_id"
		       . $extra;

		if ( $wpdb->get_var( $sql ) && $wpdb->get_var( $sql ) !== $current_id ) {
			return bp_core_get_user_domain( $wpdb->get_var( $sql ) );
		} else {
			return '#';
		}
	}
endif;

/**
 * Get previous profile link
 *
 * @param int $current_id Displayer user ID
 *
 * @return string User link
 */
if ( ! function_exists( 'bp_prev_profile' ) ):
	function bp_prev_profile( $current_id ) {
		global $wpdb;

		$extra = '';
		$obj   = new stdClass();
		do_action_ref_array( 'bp_pre_user_query_construct', array( &$obj ) );
		if ( isset( $obj->query_vars ) && $obj->query_vars && $obj->query_vars['exclude'] && is_array( $obj->query_vars['exclude'] ) && ! empty( $obj->query_vars['exclude'] ) ) {
			$extra = " AND us.ID NOT IN (" . implode( ",", $obj->query_vars['exclude'] ) . ")";
		}

		$sql = "SELECT MAX(us.ID) FROM " . $wpdb->base_prefix . "users us"
		       . " JOIN " . $wpdb->base_prefix . "bp_xprofile_data bp ON us.ID = bp.user_id"
		       . " JOIN " . $wpdb->base_prefix . "usermeta um ON um.user_id = us.ID"
		       . " WHERE um.meta_key = 'last_activity' AND us.ID < $current_id"
		       . $extra;

		if ( $wpdb->get_var( $sql ) && $wpdb->get_var( $sql ) !== $current_id ) {
			return bp_core_get_user_domain( $wpdb->get_var( $sql ) );
		} else {
			return '#';
		}
	}
endif;

if ( ! function_exists( 'bp_get_online_users' ) ):
	/**
	 * Return Buddypress online users
	 *
	 * @param string $value
	 *
	 * @return integer
	 * @global object $wpdb
	 *
	 */
	function bp_get_online_users( $value = false, $field = false ) {
		global $wpdb;
		$match_ids = array();

		/* If we want to get specific members */
		if ( $value ) {

			if ( ! $field ) {
				$default_sex = get_profile_id_by_name( 'I am a' );
				$sex         = sq_option( 'bp_sex_field', $default_sex );
				if ( $sex == 0 ) {
					$sex = $default_sex;
				}
			} else {
				if ( is_numeric( $field ) ) {
					$sex = $field;
				} else {
					$sex = get_profile_id_by_name( $field );
				}
			}

			$where = " WHERE field_id = '" . $sex . "' AND value = '" . esc_sql( $value ) . "'";
			$sql   = "SELECT " . $wpdb->base_prefix . "bp_xprofile_data.user_id FROM " . $wpdb->base_prefix . "bp_xprofile_data
							$where";

			$match_ids = $wpdb->get_col( $sql );
			if ( ! $match_ids ) {
				$match_ids = array( 0 );
			}
		}
		$i = 0;

		if ( ! empty( $match_ids ) ) {
			$include_members = '&include=' . join( ",", $match_ids );
		} else {
			$include_members = '';
		}

		if ( bp_has_members( 'user_id=0&type=online&per_page=99999999&populate_extras=0' . $include_members ) ) :
			while ( bp_members() ) : bp_the_member();
				$i ++;
			endwhile;
		endif;

		return apply_filters( 'kleo_online_users_count', $i, $value );
	}
endif;

if ( ! function_exists( 'bp_member_statistics' ) ):
	/**
	 * @deprecated since 3.0
	 * @use sq_bp_member_stats
	 */
	function bp_member_statistics( $field = false, $value = false, $online = false ) {
		global $wpdb;

		// If field not set, get Gender from theme options
		if ( ! $field ) {
			$field    = 'I am a';
			$db_field = sq_option( 'bp_sex_field' );
			if ( $db_field ) {
				$field_data = new BP_XProfile_Field( $db_field );
				$field      = $field_data->name;
			}
		}

		$where = " WHERE name = '" . $field . "' AND value = '" . esc_sql( $value ) . "'";
		$sql   = "SELECT " . $wpdb->base_prefix . "bp_xprofile_data.user_id FROM " . $wpdb->base_prefix . "bp_xprofile_data
				JOIN " . $wpdb->base_prefix . "bp_xprofile_fields ON " . $wpdb->base_prefix . "bp_xprofile_data.field_id = " . $wpdb->base_prefix . "bp_xprofile_fields.id
				$where";

		$match_ids = $wpdb->get_col( $sql );
		if ( ! $online ) {
			return count( $match_ids );
		}

		if ( ! $match_ids ) {
			$match_ids = array( 0 );
		}

		if ( ! empty( $match_ids ) ) {
			$include_members = '&include=' . join( ",", $match_ids );
		} else {
			$include_members = '';
		}

		$i = 0;
		if ( bp_has_members( 'user_id=0&type=online&per_page=999999999&populate_extras=0' . $include_members ) ) :
			while ( bp_members() ) : bp_the_member();
				$i ++;
			endwhile;
		endif;

		return apply_filters( 'bp_member_statistics', $i, $value );
	}

endif;


if ( ! function_exists( 'sq_bp_member_stats' ) ) {
	function sq_bp_member_stats( $field = false, $value = false, $online = false ) {
		global $wpdb;

		if ( $field && $value ) {
			$where = " WHERE field_id = '" . $field . "' AND value = '" . esc_sql( $value ) . "'";
		} else {
			$where = '';
		}
		$sql = "SELECT " . $wpdb->base_prefix . "bp_xprofile_data.user_id FROM " . $wpdb->base_prefix . "bp_xprofile_data
				JOIN " . $wpdb->base_prefix . "bp_xprofile_fields ON " . $wpdb->base_prefix . "bp_xprofile_data.field_id = " . $wpdb->base_prefix . "bp_xprofile_fields.id
				$where";

		$match_ids = $wpdb->get_col( $sql );
		//var_dump( $match_ids );
		if ( ! $online ) {
			return count( array_unique( $match_ids ) );
		}

		if ( ! $match_ids ) {
			$match_ids = array( 0 );
		}

		if ( ! empty( $match_ids ) ) {
			$include_members = '&include=' . join( ",", $match_ids );
		} else {
			$include_members = '';
		}

		$i = 0;
		if ( bp_has_members( 'user_id=0&type=online&per_page=999999999&populate_extras=0' . $include_members ) ) {
			while ( bp_members() ) {
				bp_the_member();
				$i ++;
			}
		}

		return apply_filters( 'sq_bp_member_stats', $i, $value );
	}

}

function special_bp_dtheme_ajax_querystring( ) {
	
   global $wpdb, $bp,$bp_results;
  
	 
	$cookie = apply_filters ('bps_cookie_name', 'bps_request');
	$request = isset ($_REQUEST[BPS_FORM])? $_REQUEST: array ();
	if (empty ($request) && isset ($_COOKIE[$cookie]))
		parse_str (stripslashes ($_COOKIE[$cookie]), $request);
	
	
	$params = array();
    parse_str($_POST['data'],$params);
	
	$fields = bps_parse_request ($params);
	//var_dump($fields);die();
	foreach ($fields as $f)
	{
	//	var_dump($f);echo '<br><br>';
	
		if (!isset ($f->filter))  continue;
		if (!is_callable ($f->search))  continue;

		do_action ('bps_field_before_query', $f);
		
		$found = call_user_func ($f->search, $f);
		//var_dump($found);
		//$found = apply_filters ('bps_field_search_results', $found, $f);
//echo 'fsar';echo '<br><br>';continue;
		$match_all = apply_filters ('bps_match_all', true);
		
		if ($match_all)
		{
			$users = isset ($users)? array_intersect ($users, $found): $found;
			//if (count ($users) == 0)  return $results;
			//var_dump($users);echo '<br><br>';
		}
		else
		{
			$users = isset ($users)? array_merge ($users, $found): $found;
		}
	}
	/*
	
    if ( ! empty( $query_string ) ) {
        $query_string .= '&';
    }*/
    
    //var_dump($params); die();
	$user_id     = get_current_user_id();
//	echo $user_id;
//var_dump($params);die();
  //  $bp_results = bp_members_search( $params );
//	setcookie( 'bp-members-search', serialize( $_POST ), 0, COOKIEPATH );

	//print_r($bp_results);die();
   //$query_string .= 'exclude=2';
   // return $query_string;
   // 
   $userids = implode(",",$users);
	$excludeusers = "1";
	if($user_id > 0){
		$excludeusers .= ",".$user_id;
	}
   if(bp_has_members("include=".$userids."&exclude=".$excludeusers)){
	   
	   while ( bp_members() ) {
    			 bp_the_member();
		   
		       	 ?>
 <div class="four columns">
                <div <?php bp_member_class( array( 'search-item' ) ); ?>>
                    <div class="avatar">
                        <a href="<?php bp_member_permalink(); ?>">
							<?php bp_member_avatar( 'type=full&width=94&height=94&class=' ); ?>
                        </a>
						<?php do_action( 'bp_members_inside_avatar' ); ?>
                    </div>

                    <h5 class="author"><a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a></h5>

					<?php // do_action( 'bp_members_meta' ); ?>

                    <div class="search-body">
						<?php

						/**
						 * Fires inside the display of a directory member item.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_directory_members_item' ); ?>

						<?php
						/***
						 * If you want to show specific profile fields here you can,
						 * but it'll add an extra query for each member in the loop
						 * (only one regardless of the number of fields you show):
						 *
						 * bp_member_profile_data( 'field=the field name' );
						 */
						?>
                    </div>
                    <div class="bp-member-dir-buttons">
						<?php do_action( 'bp_directory_members_item_last' ); ?>

                        <div class="action"><?php

							/**
							 * Fires inside the members action HTML markup to display actions.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_directory_members_actions' ); ?></div>
                    </div>
                </div>
            </div>
                 <?php 
    			
       } 
	   
   }
}
add_action( 'wp_ajax_special_bp_dtheme_ajax_querystring', 'special_bp_dtheme_ajax_querystring' );
add_action( 'wp_ajax_nopriv_special_bp_dtheme_ajax_querystring', 'special_bp_dtheme_ajax_querystring' );
add_action( 'wp_ajax_members_filter_custom', 'members_filter_custom' );
add_action( 'wp_ajax_nopriv_members_filter_custom', 'members_filter_custom' );

function members_filter_custom(){
	
    $response = array();
    $response['status'] = 'success';
    //echo 'd';
    add_action( 'bp_legacy_theme_ajax_querystring', 'my_bp_loop_querystring', 20, 2 );
    $posted = special_bp_dtheme_ajax_querystring();
    $result  = bp_members_search($posted);
	
    if ( bp_has_members( bp_ajax_querystring( 'members' )) ) {
       	$i = 0;
       /*while ( bp_members() ) {
    			 bp_the_member();
    			$i++;
    			
       } */
       //echo $i;die();
    }
   // echo json_encode($response);die();
}

function custom_members_filter() {
global $wpdb, $bp,$bp_results;
	 
	$cookie = apply_filters ('bps_cookie_name', 'bps_request');
	$request = isset ($_REQUEST[BPS_FORM])? $_REQUEST: array ();
	if (empty ($request) && isset ($_COOKIE[$cookie]))
		parse_str (stripslashes ($_COOKIE[$cookie]), $request);
	
	$results = bps_search ($request);
	$fields = bps_parse_request ($request);

	 echo '<pre>';
			print_r($request);
		echo '</pre>';

	echo json_encode($titles); //encode into JSON format and output
	die(); //stop "0" from being output
}
add_action( 'wp_ajax_custom_members_filter', 'custom_members_filter' );
add_action( 'wp_ajax_nopriv_custom_members_filter', 'custom_members_filter' );

add_action( 'wp_ajax_sp_members_filter_custom', 'sp_members_filter_custom' );
add_action( 'wp_ajax_nopriv_sp_members_filter_custom', 'sp_members_filter_custom' );

function sp_members_filter_custom(){ 	
	global $wpdb, $bp,$bp_results;
	 
	$cookie = apply_filters ('bps_cookie_name', 'bps_request');
	$request = isset ($_REQUEST[BPS_FORM])? $_REQUEST: array ();
	if (empty ($request) && isset ($_COOKIE[$cookie]))
		parse_str (stripslashes ($_COOKIE[$cookie]), $request);
	
	$results = bps_search ($request);
	$fields = bps_parse_request ($request);

		/* echo '<pre>';
			print_r($fields);
		echo '</pre>'; */
	

   $user_id     = get_current_user_id();   
   $userids = implode(",",$results['users']);
   
	$excludeusers = "1";
	if($user_id > 0){
		$excludeusers .= ",".$user_id;
	}

   if(bp_has_members("include=".$userids."&exclude=".$excludeusers)) {
	   
	   while ( bp_members() ) {
    			 bp_the_member();
		   
		       	 ?>
 <div class="three columns">
                <div <?php bp_member_class( array( 'search-item' ) ); ?>>
                    <div class="avatar">
                        <a href="<?php bp_member_permalink(); ?>">
							<?php bp_member_avatar( 'type=full&width=94&height=94&class=' ); ?>
                        </a>
						<?php do_action( 'bp_members_inside_avatar' ); ?>
                    </div>

                    <h5 class="author"><a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a></h5>

					<?php // do_action( 'bp_members_meta' ); ?>

                    <div class="search-body">
						<?php

						/**
						 * Fires inside the display of a directory member item.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_directory_members_item' ); ?>

						<?php
						/***
						 * If you want to show specific profile fields here you can,
						 * but it'll add an extra query for each member in the loop
						 * (only one regardless of the number of fields you show):
						 *
						 * bp_member_profile_data( 'field=the field name' );
						 */
						?>
                    </div>
                    <div class="bp-member-dir-buttons">
						<?php do_action( 'bp_directory_members_item_last' ); ?>

                        <div class="action"><?php

							/**
							 * Fires inside the members action HTML markup to display actions.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_directory_members_actions' ); ?></div>
                    </div>
                </div>
            </div>
                 <?php 
    			
       } 
	   
   } else {
	   
	   
	   echo 'Sorry, no members were found.';
   }
	
	die();
	//echo json_encode($users);die();
    
}


/**
 * Buddypress AJAX Widget
 *
 */
function widget_ajax_groups() {
	?>
    <script type="text/javascript">
        /* Buddpress Groups widget */
        jQuery(document).ready(function () {
            jQuery(".widgets div#groups-list-options a").on("click", function () {
                var a = this;
                jQuery(a).addClass("loading");
                jQuery(".widgets div#groups-list-options a").removeClass("selected");
                jQuery(this).addClass("selected");
                jQuery.post(
                    ajaxurl,
                    {
                        action: "widget_groups_list",
                        cookie: encodeURIComponent(document.cookie),
                        _wpnonce: jQuery("input#_wpnonce-groups").val(),
                        max_groups: jQuery("input#groups_widget_max").val(),
                        filter: jQuery(this).attr("id")
                    },
                    function (b) {
                        jQuery(a).removeClass("loading");
                        groups_wiget_response(b)
                    }
                );
                return false;
            })
        });

        function groups_wiget_response(a) {
            a = a.substr(0, a.length - 1);
            a = a.split("[[SPLIT]]");
            if (a[0] != "-1") {
                jQuery(".widgets ul#groups-list").fadeOut(200, function () {
                    jQuery(".widgets ul#groups-list").html(a[1]);
                    jQuery(".widgets ul#groups-list").fadeIn(200)
                })
            } else {
                jQuery(".widgets ul#groups-list").fadeOut(200, function () {
                    var b = "<p>" + a[1] + "</p>";
                    jQuery(".widgets ul#groups-list").html(b);
                    jQuery(".widgets ul#groups-list").fadeIn(200)
                })
            }
        }

        /* Buddpress Members widget */
        jQuery(document).ready(function () {
            jQuery(".widgets div#members-list-options a").on("click", function () {
                var a = this;
                jQuery(a).addClass("loading");
                jQuery(".widgets div#members-list-options a").removeClass("selected");
                jQuery(this).addClass("selected");
                jQuery.post(ajaxurl, {
                    action: "widget_members",
                    cookie: encodeURIComponent(document.cookie),
                    _wpnonce: jQuery("input#_wpnonce-members").val(),
                    "max-members": jQuery("input#members_widget_max").val(),
                    filter: jQuery(this).attr("id")
                }, function (b) {
                    jQuery(a).removeClass("loading");
                    member_wiget_response(b)
                });
                return false
            })
        });

        function member_wiget_response(a) {
            a = a.substr(0, a.length - 1);
            a = a.split("[[SPLIT]]");
            if (a[0] != "-1") {
                jQuery(".widgets ul#members-list").fadeOut(200, function () {
                    jQuery(".widgets ul#members-list").html(a[1]);
                    jQuery(".widgets ul#members-list").fadeIn(200)
                })
            } else {
                jQuery(".widgets ul#members-list").fadeOut(200, function () {
                    var b = "<p>" + a[1] + "</p>";
                    jQuery(".widgets ul#members-list").html(b);
                    jQuery(".widgets ul#members-list").fadeIn(200)
                })
            }
        }


    </script>
	<?php
}

add_action( 'wp_footer', 'widget_ajax_groups' );

/***************************************************
 * Frontend Profile pictures slider init
 ***************************************************/
add_action( 'wp_footer', 'kleo_slider_js', 90 );

function kleo_slider_js() {
	if ( ! bp_is_user() ) {
		return;
	}
	?>
    <script type="text/javascript">
        function loadSlider(sliderId, left, right) {
            // Gallery carousel
            jQuery("#" + sliderId).carouFredSel({
                width: "100%",
                auto: false,
                scroll: 1,
                swipe: {
                    onMouse: true,
                    onTouch: true
                },
                prev: {
                    button: "#" + left,
                    key: "left"
                },
                next: {
                    button: "#" + right,
                    key: "right"
                }
            });
        }

        jQuery(window).ready(function () {
            // put here your slider ID
            var sliderID = "gallery-carousel";

            //load the slider on page load
            loadSlider(sliderID, "stanga-prev", "dreapta-next");

            //when someone calls the tab with the slider update the slider sizes
            jQuery(".sliderEvent").on("click", function () {
                jQuery(".mySlider").show();
                loadSlider(sliderID, "stanga-prev", "dreapta-next");
            });
        });
    </script>
	<?php
}

/* -----------------------------------------------------------------------------
 * END kleo_slider_js()
 */


/**
 * Return group ID by group name
 *
 * @param string $name
 *
 * @return integer
 * @global object $wpdb
 *
 */
function get_group_id_by_name( $name ) {
	global $wpdb, $bp;
	if ( ! isset( $name ) ) {
		return false;
	}
	$table_name = $bp->profile->table_name_groups;

	return $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table_name} WHERE name = %s", $name ) );
}

// -----------------------------------------------------------------------------


/**
 * Return profile field ID by profile name
 *
 * @param string $name
 *
 * @return integer
 * @global object $wpdb
 *
 */
function get_profile_id_by_name( $name ) {
	global $wpdb;
	if ( ! isset( $name ) ) {
		return false;
	}

	$sql = "SELECT id FROM " . $wpdb->base_prefix . "bp_xprofile_fields WHERE name = '" . $name . "'";

	return $wpdb->get_var( $sql );
}

// -----------------------------------------------------------------------------

/* Load Buddypress custom search */
locate_template( 'custom_buddypress/kleo-bp-search.php', true );

if ( ! function_exists( 'get_member_age' ) ):
	/**
	 * Calculate member age based on date of birth
	 *
	 * @param int $id User id to get the age for
	 *
	 * @return string
	 */
	function get_member_age( $id ) {
		$default_age_field = get_profile_id_by_name( 'Birthday' );
		$age_field         = sq_option( 'bp_age_field', $default_age_field );
		if ( $age_field == 0 ) {
			$age_field = $default_age_field;
		}

		$age = '';

		if ( bp_is_active( 'xprofile' ) && xprofile_get_field_data( $age_field, $id ) ) {
			$birth = BP_XProfile_ProfileData::get_value_byid( $age_field, $id );

			$field = new BP_XProfile_Field ( $age_field );

			if ( $field->type == 'birthdate' ) {
				if ( strtotime( $birth ) == false ) {
					return strip_tags( $birth );
				}
			}
			if ( ! empty( $birth ) ) {
				$diff = time() - strtotime( $birth );
				$age  = floor( $diff / ( 365 * 60 * 60 * 24 ) );
			}
		}

		return $age;
	}
endif;


/* Show Age instead of Birthdate */
if ( sq_option( 'bp_birthdate_to_age', 0 ) == 1 ) {

	add_filter( 'bp_get_the_profile_field_name', 'kleo_change_birth_to_age' );
	add_action( 'bp_before_profile_edit_content', 'kleo_revert_birth_to_age' );
	add_filter( 'bp_get_the_profile_field_value', 'kleo_get_field_value', 9, 3 );
	remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_format_field_value', 1, 2 );
	remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_format_field_value_by_type', 8, 2 );

}


function kleo_revert_birth_to_age() {

	remove_filter( 'bp_get_the_profile_field_name', 'kleo_change_birth_to_age' );

}

function kleo_change_birth_to_age( $name ) {

	if ( $name == esc_html__( 'Birthday', 'sweetdate' ) ) {

		$name = esc_html__( 'Age', 'sweetdate' );

	}

	return $name;
}

if ( ! function_exists( 'kleo_get_field_value' ) ) {
	function kleo_get_field_value( $value, $type, $field_id ) {

		$value_to_return = $value;
		$field           = new BP_XProfile_Field( $field_id );

		//our Age field
		$default_age_field = get_profile_id_by_name( 'Birthday' );
		$age_field         = sq_option( 'bp_age_field', $default_age_field );
		if ( $age_field == 0 ) {
			$age_field = $default_age_field;
		}

		if ( $type == 'datebox' && $age_field == $field_id ) {
			$value_to_return = floor( ( time() - strtotime( $value ) ) / 31556926 );
		}

		return $value_to_return;
	}
}

// -----------------------------------------------------------------------------


/* Members meta on members listing */
add_action( 'bp_members_meta', 'render_bp_meta' );

if ( ! function_exists( 'render_bp_meta' ) ):
	function render_bp_meta() {
		global $kleo_config;
		$output = array();

		if ( get_member_age( bp_get_member_user_id() ) ) {
			$output['age'] = apply_filters( 'kleo_bp_meta_after_age', get_member_age( bp_get_member_user_id() ) );
		}
		//fields to show
		$fields_arr = $kleo_config['bp_members_loop_meta'];


		//user private fields
		$private_fields = array();
		if ( function_exists( 'bp_xprofile_get_hidden_fields_for_user' ) ) {
			$private_fields = bp_xprofile_get_hidden_fields_for_user( bp_get_member_user_id() );
		}
		if ( ! empty( $private_fields ) ) {
			//get the fields ids that will be displayed on members list
			if ( false === ( $fields_id_arr = get_transient( 'kleo_bp_meta_fields' ) ) ) {

				$fields_id_arr = array();

				foreach ( $fields_arr as $val ) {
					if ( get_profile_id_by_name( $val ) ) {
						$fields_id_arr[ $val ] = get_profile_id_by_name( $val );
					}
				}

				set_transient( 'kleo_bp_meta_fields', $fields_id_arr, 60 * 60 * 12 );
			}
			if ( ! empty( $fields_id_arr ) ) {
				//fields that will actually display
				$show_fields = array_diff( $fields_id_arr, $private_fields );
				if ( ! empty( $show_fields ) ) {
					$fields_arr_inv = array_flip( $fields_id_arr );

					foreach ( $show_fields as $key => $val ):
						if ( @bp_get_member_profile_data( 'field=' . $fields_arr_inv[ $val ] ) ):
							$output[] = bp_get_member_profile_data( 'field=' . $fields_arr_inv[ $val ] );
						endif;
					endforeach;
				}
			}
		} else {
			foreach ( $fields_arr as $key => $val ) {
				if ( @bp_get_member_profile_data( 'field=' . $val ) ) {
					$output[] = bp_get_member_profile_data( 'field=' . $val );
				}
			}
		}

		$output = apply_filters( 'kleo_bp_meta_fields', $output );
		if ( is_array( $output ) ) {
			$output_str = '<p class="date">' . implode( " / ", $output ) . '</p>';
		} else {
			$output_str = '';
		}
		echo '<div class="search-meta">';
		echo apply_filters( 'kleo_bp_members_dir_name', '<h5 class="author">' .
		                                                '<a href="' . esc_url( bp_get_member_permalink() ) . '">' .
		                                                esc_html( bp_get_member_name() ) .
		                                                '</a></h5>' );
		echo $output_str; // PHPCS: XSS ok.
		echo '</div>';
	}
endif;

/**
 * Removed the member name from 'render_bp_meta' action
 * @since 3.0
 */
add_filter( 'kleo_bp_members_dir_name', '__return_empty_string', 99 );


/* Members details on members listing */
add_action( 'bp_directory_members_item', 'render_bp_details' );

if ( ! function_exists( 'render_bp_details' ) ):
	function render_bp_details() {
		global $kleo_config;

		if ( @bp_get_member_profile_data( 'field=' . apply_filters( 'kleo_bp_details_field', $kleo_config['bp_members_details_field'] ) ) ) {
			echo '<p>' . word_trim( bp_get_member_profile_data( 'field=' . apply_filters( 'kleo_bp_details_field', $kleo_config['bp_members_details_field'] ) ), 50, '...' ) . '</p>';
		}
	}
endif;


if ( ! function_exists( 'compatibility_score' ) ) {
	/**
	 * Calculate compatibility between members based on their profiles
	 *
	 * @param int|bool $userid1
	 * @param int|bool $userid2
	 *
	 * @return int
	 */
	function compatibility_score( $userid1 = false, $userid2 = false ) {
		global $kleo_config;

		$score = $kleo_config['matching_fields']['starting_score'];

		if ( $userid1 && $userid2 ) {

			//Sex match
			if ( ( isset( $kleo_config['matching_fields']['sex_match'] ) && $kleo_config['matching_fields']['sex_match'] == '1' )
			     || ! isset( $kleo_config['matching_fields']['sex_match'] )
			) {
				$field1_u1  = xprofile_get_field_data( $kleo_config['matching_fields']['sex'], $userid1 );
				$field12_u1 = xprofile_get_field_data( $kleo_config['matching_fields']['looking_for'], $userid1 );
				$field1_u2  = xprofile_get_field_data( $kleo_config['matching_fields']['sex'], $userid2 );
				$field12_u2 = xprofile_get_field_data( $kleo_config['matching_fields']['looking_for'], $userid2 );

				if ( $field1_u1 == $field12_u2 && $field12_u1 == $field1_u2 ) {
					$score += $kleo_config['matching_fields']['sex_percentage'];
				} //if no sex match, return the score
				else {
					if ( ( isset( $kleo_config['matching_fields']['sex_mandatory'] ) && $kleo_config['matching_fields']['sex_mandatory'] == 1 ) || ! isset( $kleo_config['matching_fields']['sex_mandatory'] ) ) {
						return $score;
					}
				}
			}

			//single fields match
			if ( is_array( $kleo_config['matching_fields']['single_value'] ) ) {
				foreach ( $kleo_config['matching_fields']['single_value'] as $key => $value ) {
					if ( xprofile_get_field_data( $key, $userid1 )
					     && xprofile_get_field_data( $key, $userid2 )
					     && xprofile_get_field_data( $key, $userid1 ) == xprofile_get_field_data( $key, $userid2 )
					) {
					    $score += (int) $value;
					}
				}
			}

			//multiple fields match
			if ( is_array( $kleo_config['matching_fields']['multiple_values'] ) ) {
				foreach ( $kleo_config['matching_fields']['multiple_values'] as $key => $value ) {
					$field1 = xprofile_get_field_data( $key, $userid1 );
					$field2 = xprofile_get_field_data( $key, $userid2 );
					if ( $field1 && $field2 ) {
						$intersect = array_intersect( (array) $field1, (array) $field2 );
						if ( count( $intersect ) >= 1 ) {
							$score += $value * count( $intersect );
						}
					}
				}
			}
			if ( $score > 100 ) {
				$score = 100;
			}
		}

		return $score;
	}
}

function kleo_bp_match_data() {
	if ( sq_option( 'bp_match', 0 ) == 1 ) {

		global $kleo_config;
		$match_data = sq_option( 'bp_match_data' );

		// var_dump($match_data);exit;

		$kleo_config['matching_fields']['starting_score'] = (int) sq_option( 'bp_match_start_percent', 0 );

		if ( sq_option( 'bp_comp_fields', 0 ) == 1 ) {
			if ( sq_option( 'bp_comp_field1', '' ) != '' && sq_option( 'bp_comp_field2', '' ) != '' ) {
				$kleo_config['matching_fields']['sex_match']      = 1;
				$kleo_config['matching_fields']['sex']            = sq_option( 'bp_comp_field1', '' );
				$kleo_config['matching_fields']['looking_for']    = sq_option( 'bp_comp_field2', '' );
				$kleo_config['matching_fields']['sex_percentage'] = (int) sq_option( 'bp_comp_percent', 0 );
				$kleo_config['matching_fields']['sex_mandatory']  = (int) sq_option( 'bp_comp_mandatory', 1 );


			}

		} else {
			$kleo_config['matching_fields']['sex_match'] = 0;
		}

		$kleo_config['matching_fields']['single_value']    = array();
		$kleo_config['matching_fields']['multiple_values'] = array();

		if ( isset( $match_data['single'] ) && is_array( $match_data['single'] ) ) {

			$single_match = array();

			foreach ( $match_data['single'] as $single ) {
				if ( isset( $match_data['percentages'][ $single ] ) ) {
					$single_match[ $single ] = $match_data['percentages'][ $single ];
				}
			}
			$kleo_config['matching_fields']['single_value'] = $single_match;
		}

		if ( isset( $match_data['multi'] ) && is_array( $match_data['multi'] ) ) {

			$multi_match = array();

			foreach ( $match_data['multi'] as $multi ) {
				if ( isset( $match_data['percentages'][ $multi ] ) ) {
					$multi_match[ $multi ] = $match_data['percentages'][ $multi ];
				}
			}
			$kleo_config['matching_fields']['multiple_values'] = $multi_match;
		}
		//print_r($kleo_config['matching_fields']);
	}
}

if ( ! is_admin() ) {
	add_action( 'init', 'kleo_bp_match_data' );
}


/* Match compatibility hook */
// add_action( 'kleo_bp_before_profile_name', 'kleo_bp_compatibility_match' );

if ( ! function_exists( 'kleo_bp_compatibility_match' ) ):
	function kleo_bp_compatibility_match() {
		global $bp;
		if ( is_user_logged_in() && ! bp_is_my_profile() ):
			echo '<div class="circular-item" title="' . esc_html__( "Compatibility match", 'sweetdate' ) . '">';
			echo '<small class="icon strong">' . esc_html__( "match", 'sweetdate' ) . '</small>';
			echo '<input type="text" value="' . compatibility_score( $bp->loggedin_user->id, bp_displayed_user_id() ) . '" class="greenCircle">';
			echo '<span class="hearts"></span>';
			echo '</div>';
		endif;
	}
endif;

/* Fix for embeded videos widescreen */
function kleo_fix_video() {
	?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.activity-content .activity-inner iframe').each(function () {
                if (!jQuery(this).parent().hasClass('flex-video')) {
                    jQuery(this).wrap('<div class="flex-video widescreen"></div>');
                }
            });
        });
    </script>
	<?php
}

add_action( 'wp_footer', 'kleo_fix_video' );

/**
 * Before carousel search form - ACTION
 */
add_action( 'kleo_bps_before_carousel', 'kleo_before_carousel_text' );

if ( ! function_exists( 'kleo_before_carousel_text' ) ):
	function kleo_before_carousel_text() {
		echo '<strong>' . esc_html__( "Latest registered members", 'sweetdate' ) . '</strong>';
	}
endif;

/**
 * Buttons on member page - ACTION
 */
add_action( 'kleo_bp_header_actions', 'kleo_bp_member_buttons' );
if ( ! function_exists( 'kleo_bp_member_buttons' ) ):
	function kleo_bp_member_buttons() {
		?>

        <div class="six columns">
            <div id="item-buttons">
				<?php if ( ! is_user_logged_in() ) : ?>
					<?php if ( bp_is_active( 'friends' ) ): ?>
                        <div id="friendship-button-<?php bp_displayed_user_id(); ?>"
                             class="generic-button friendship-button not_friends">
                            <a data-reveal-id="login_panel" class="has-tip tip-right friendship-button not_friends add"
                               data-width="350" rel="add" id="friend-<?php bp_displayed_user_id(); ?>"
                               title="<?php _e( "Please Login to Add Friend", 'sweetdate' ); ?>"
                               href="#"><?php _e( "Add Friend", 'sweetdate' ); ?></a>
                        </div>
					<?php endif; ?>
					<?php if ( bp_is_active( 'activity' ) ): ?>
                        <div id="post-mention" class="generic-button">
                            <a data-reveal-id="login_panel" class="has-tip tip-right activity-button mention"
                               data-width="350"
                               title="<?php _e( "Please Login to Send a public message", 'sweetdate' ); ?>"
                               href="#"><?php _e( "Public Message", 'sweetdate' ); ?></a>
                        </div>
					<?php endif; ?>
					<?php if ( bp_is_active( 'messages' ) ): ?>
                        <div id="send-private-message" class="generic-button">
                            <a data-reveal-id="login_panel" class="has-tip tip-right send-message" data-width="350"
                               title="<?php _e( "Please Login to Send a private message", 'sweetdate' ); ?>"
                               href="#"><?php _e( "Private Message", 'sweetdate' ); ?></a>
                        </div>
					<?php endif; ?>
				<?php else : ?>
					<?php
					/**
					 * Fires in the member header actions section.
					 *
					 * @since 1.2.6
					 */
					do_action( 'bp_member_header_actions' );
					?>
				<?php endif; ?>

            </div><!-- #item-buttons -->
        </div>
		<?php
	}
endif;

/* Load Buddypress profile tabs */
locate_template( 'custom_buddypress/class-bp-tabs.php', true );

/**
 * Define what tabs to display next to user profile
 */
global $bp_tabs;

/**
 * Customise tabs according to Theme options - BP Tabs
 *
 * @since 2.8
 */
function kleo_my_custom_tabs_data() {
	if ( sq_option( 'bp_tabs', 0 ) == 1 && bp_is_active( 'xprofile' ) ) {
		global $bp_tabs;
		$bp_tabs = array();

		$tabs_data = sq_option( 'bp_tabs_data' );

		if ( ! empty( $tabs_data ) && ! empty( $tabs_data['fields'] ) ) {
			foreach ( $tabs_data['fields'] as $tab ) {

				if ( strpos( $tab, 'group' ) !== false ) {
					$group_id = substr( $tab, 6 );

					$group = xprofile_get_field_group( $group_id );

					if ( $group ) {
						$name = $group->name;

						$label = isset( $tabs_data['labels'] ) && isset( $tabs_data['labels'][ $tab ] ) && $tabs_data['labels'][ $tab ] != '' ? $tabs_data['labels'][ $tab ] : $name;
						$type  = isset( $tabs_data['types'] ) && isset( $tabs_data['types'][ $tab ] ) && $tabs_data['types'][ $tab ] != '' ? $tabs_data['types'][ $tab ] : 'regular';

						$label = sweet_translate_dynamic( $label, 'sweetdate' );

						$bp_tabs[] = array(
							'type'  => $type,
							'name'  => $label,
							'group' => $name,
							'class' => $type . 'tab'
						);
					}
				} else {
					$label = isset( $tabs_data['labels'] ) && isset( $tabs_data['labels'][ $tab ] ) && $tabs_data['labels'][ $tab ] != '' ? $tabs_data['labels'][ $tab ] : esc_html__( 'My photos', 'sweetdate' );
					$class = in_array( $tab, array( 'rt_media', 'bp_album' ) ) ? 'mySlider' : '';

					$label = sweet_translate_dynamic( $label, 'sweetdate' );

					$bp_tabs[] = array(
						'type'  => $tab,
						'name'  => $label,
						'class' => $class
					);
				}

			}
		}
	 }


}

add_action( 'init', 'kleo_my_custom_tabs_data' );


/**
 * Displays tabs next to user image
 * @global array $bp_tabs
 */
if ( ! function_exists( 'kleo_bp_profile_tabs' ) ):
	function kleo_bp_profile_tabs() {
		global $bp_tabs;
		echo '<div class="seven columns">';
		new BpMembersTabs( $bp_tabs );
		echo '</div>';
	}
endif;
add_action( 'bp_after_member_header', 'kleo_bp_profile_tabs', 2 );

/* Add a new activity stream when registering with Facebook */
if ( ! function_exists( 'gaf_fb_register_activity' ) ):
	function gaf_fb_register_activity( $user_id ) {
		global $bp;
		if ( ! function_exists( 'bp_activity_add' ) ) {
			return false;
		}

		$userlink = bp_core_get_userlink( $user_id );
		bp_activity_add( array(
			'user_id'   => $user_id,
			'action'    => apply_filters( 'xprofile_fb_register_action', sprintf( esc_html__( '%s became a registered member', 'buddypress' ), $userlink ), $user_id ),
			'component' => 'xprofile',
			'type'      => 'new_member'
		) );
	}
endif;
add_action( 'fb_register_action', 'gaf_fb_register_activity' );


/* Show "Change photo" link over profile image */
if ( ! function_exists( 'kleo_bp_profile_photo_change' ) ):
	function kleo_bp_profile_photo_change() {
		if ( bp_is_my_profile() ) {
			echo '<div class="profile-hover-link">';
			echo '<a href="' . bp_loggedin_user_domain() . 'profile/change-avatar/#item-nav">';
			_e( "Change photo", 'sweetdate' );
			echo '</a></div>';

			echo '<script type="text/javascript">
		jQuery(document).ready(function() {
				jQuery("#profile").hover(
						function() {
								jQuery(".image-hover img").fadeTo(200, 0.9);
								jQuery(".image-hover .profile-hover-link").show();
						},
						function() {
								jQuery(".image-hover img").fadeTo(200, 1);
								jQuery(".image-hover .profile-hover-link").hide();
				});
		});
		</script>';
		}
	}
endif;
add_action( 'kleo_bp_after_profile_image', 'kleo_bp_profile_photo_change' );

/* Language parameter in search form */
add_action( 'kleo_bp_search_add_data', 'kleo_translated_field' );
function kleo_translated_field() {
	if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		echo "<input type='hidden' name='lang' value='" . ICL_LANGUAGE_CODE . "'/>";
	}
}


if ( ! function_exists( 'kleo_bp_member_dir_view_button' ) ):
	/**
	 * Render view profile button on members directory
	 */
	function kleo_bp_member_dir_view_button() {
		?>
        <a href="<?php bp_member_permalink(); ?>" class="small button radius secondary"><i
                    class="icon-angle-right"></i> <?php _e( "View profile", 'sweetdate' ); ?></a>
		<?php
	}
endif;
add_action( 'bp_directory_members_item_last', 'kleo_bp_member_dir_view_button', 10 );


if ( ! function_exists( 'kleo_bp_member_dir_friend_button' ) ) {
	/**
	 * Render add friend button on members directory
	 */
	function kleo_bp_member_dir_friend_button() {
		add_filter( 'bp_get_add_friend_button', 'kleo_change_profile_button' );
		bp_add_friend_button( bp_get_member_user_id() );
	}
}
if ( bp_is_active( 'friends' ) ) {
	add_action( 'bp_directory_members_item_last', 'kleo_bp_member_dir_friend_button', 11 );
}
remove_action( 'bp_directory_members_actions', 'bp_member_add_friend_button' );

if ( ! function_exists( 'kleo_change_profile_button' ) ):
	/**
	 * Change default BP button class and text next to profile in members directory
	 *
	 * @param array $button
	 *
	 * @return array
	 */
	function kleo_change_profile_button( $button ) {
		$is_friend = bp_is_friend( bp_get_member_user_id() );

		if ( empty( $is_friend ) ) {
			return false;
		}

		switch ( $is_friend ) {
			case "pending":
			case "is_friend":
				$button['link_text'] = '<i class="icon-minus"></i>';
				break;
			default:
				$button['link_text'] = '<i class="icon-plus"></i>';
				break;
		}

		$button['link_class'] = 'button small secondary radius';

		return $button;
	}
endif;


/* User online */
if ( ! function_exists( 'kleo_is_user_online' ) ):
	/**
	 * Check if a Buddypress member is online or not
	 *
	 * @param integer $user_id
	 * @param integer $time
	 *
	 * @return boolean
	 * @global object $wpdb
	 *
	 */
	function kleo_is_user_online( $user_id, $time = 5 ) {
		global $wpdb;
		$sql        = $wpdb->prepare( "
			SELECT u.user_login FROM $wpdb->users u JOIN $wpdb->usermeta um ON um.user_id = u.ID
			WHERE u.ID = %d
			AND um.meta_key = 'last_activity'
			AND DATE_ADD( um.meta_value, INTERVAL %d MINUTE ) >= UTC_TIMESTAMP()", $user_id, $time );
		$user_login = $wpdb->get_var( $sql );
		if ( isset( $user_login ) && $user_login != "" ) {
			return true;
		} else {
			return false;
		}
	}
endif;

if ( ! function_exists( 'kleo_online_status' ) ) {
	/**
	 * Render the html to show if a user is online or not
	 */
	function kleo_online_status() {
		if ( kleo_is_user_online( bp_get_member_user_id() ) ) {
			echo '<span class="online"></span>';
		} else {
			echo '<span class="offline"></span>';
		}
	}

	if ( sq_option( 'bp_online_status', 0 ) == 1 ) {
		add_action( 'bp_members_inside_avatar', 'kleo_online_status', 9 );
	}
}


//Buddypress SIDEBAR ACTION
add_action( 'wp_head', 'kleo_bp_layout' );
function kleo_bp_layout() {
	if ( ! bp_is_blog_page() ) {
		if ( bp_is_members_directory() ) {
			$option = sq_option( 'bp_members_sidebar', 'no' );
		} elseif ( bp_is_user() && 'default' != sq_option( 'bp_member_sidebar', 'default' ) ) {
			$option = sq_option( 'bp_member_sidebar', 'default' );
		} else {
			$option = sq_option( 'buddypress_sidebar', 'right' );
		}

		switch ( $option ) {
			case 'left':
				remove_action( 'kleo_buddypress_before_content', 'kleo_buddypress_sidebar' );
				remove_action( 'kleo_buddypress_after_content', 'kleo_buddypress_sidebar' );
				add_action( 'kleo_buddypress_before_content', 'kleo_buddypress_sidebar' );
				add_filter( 'kleo_buddypress_content_class', function () {
					return "eight";
				} );
				add_filter( 'kleo_sidebar_class', function () {
					return "four";
				} );
				break;
			case 'right':
				remove_action( 'kleo_buddypress_before_content', 'kleo_buddypress_sidebar' );
				remove_action( 'kleo_buddypress_after_content', 'kleo_buddypress_sidebar' );
				add_action( 'kleo_buddypress_after_content', 'kleo_buddypress_sidebar' );
				add_filter( 'kleo_buddypress_content_class', function () {
					return "eight";
				} );
				add_filter( 'kleo_sidebar_class', function () {
					return "four";
				} );
				break;
			case '3ll':
				add_filter( 'kleo_buddypress_content_class', function () {
					return "six";
				} );
				add_filter( 'kleo_sidebar_class', function () {
					return "three";
				} );
				add_action( 'kleo_buddypress_before_content', 'kleo_buddypress_sidebar' );
				add_action( 'kleo_buddypress_before_content', 'kleo_extra_sidebar' );
				break;
			case '3lr':
				add_filter( 'kleo_buddypress_content_class', function () {
					return "six";
				} );
				add_filter( 'kleo_sidebar_class', function () {
					return "three";
				} );
				add_action( 'kleo_buddypress_before_content', 'kleo_buddypress_sidebar' );
				add_action( 'kleo_buddypress_after_content', 'kleo_extra_sidebar' );
				break;
			case '3rr':
				add_filter( 'kleo_buddypress_content_class', function () {
					return "six";
				} );
				add_filter( 'kleo_sidebar_class', function () {
					return "three";
				} );
				add_action( 'kleo_buddypress_after_content', 'kleo_buddypress_sidebar' );
				add_action( 'kleo_buddypress_after_content', 'kleo_extra_sidebar' );
				break;
			case 'no':
				remove_action( 'kleo_buddypress_before_content', 'kleo_sidebar' );
				remove_action( 'kleo_buddypress_after_content', 'kleo_sidebar' );
				add_filter( 'kleo_buddypress_content_class', function () {
					return "twelve";
				} );
				break;
			default:
				break;
		}
	}
}

//get buddypress sidebar
if ( ! function_exists( 'kleo_buddypress_sidebar' ) ) {
	function kleo_buddypress_sidebar() {
		get_sidebar( 'buddypress' );
	}
}


/* Buddypress fix for Posting ordered list */
function kleo_fix_activity_ordered_list( $activity_allowedtags ) {
	$activity_allowedtags['ol'] = array();

	return $activity_allowedtags;
}

add_filter( 'bp_activity_allowed_tags', 'kleo_fix_activity_ordered_list' );


/* Terms and conditions - Register page */
add_action( 'bp_after_signup_profile_fields', 'kleo_bp_tos_register' );
//TOS in register page
function kleo_bp_tos_register() {
	if ( sq_option( 'terms_page', '#' ) != "#" ) {
		$checked       = isset( $_POST['tos_register'] ) ? 'checked="checked"' : '';
		$terms_page_id = sq_option( 'terms_page' );
		/* WPML compatibility */
		if ( function_exists( 'icl_object_id' ) ) {
			$terms_page_id = icl_object_id( $terms_page_id, 'page', true );
		}
		$terms_link = get_permalink( $terms_page_id );
		?>
        <p class="right">
            <label>
                <input name="tos_register" id="tos_register"<?php echo $checked; /* PHPCS: XSS ok */ ?> type="checkbox"
                       class="tos_register" data-customforms="disabled">
                <small>
					<?php printf( __( 'I agree with the <a href="%s" target="_blank"><strong>terms and conditions</strong></a>.', 'sweetdate' ), $terms_link ); ?>
                </small>
            </label>
        </p>
		<?php
	}
}

/* Profile placeholder in menu */
add_filter( 'walker_nav_menu_start_el', 'kleo_bp_replace_placeholders' );

function kleo_bp_replace_placeholders( $output ) {

	if ( strpos( $output, '##profile_link##' ) !== false ) {

		if ( ! is_user_logged_in() ) {
			return '';
		}

		$logged_in_link = bp_loggedin_user_domain();
		$output         = str_replace( '##profile_link##', $logged_in_link, $output );
	}

	return $output;
}


/* Buddypress cover compatibility */

function sweet_bp_cover_image_css( $settings = array() ) {
	/**
	 * If you are using a child theme, use bp-child-css
	 * as the theme handel
	 */
	$theme_handle = 'bp-parent-css';

	/**
	 * You can choose to register it for Members and / or Groups by including (or not)
	 * the corresponding components in your feature's settings. In this example, we
	 * chose to register it for both components.
	 */
	$components             = array( 'groups', 'xprofile' );
	$settings['components'] = $components;

	$settings['theme_handle'] = $theme_handle;
	$settings['width']        = 940;
	$settings['height']       = 225;

	/**
	 * Then you'll probably also need to use your own callback function
	 * @see the previous snippet
	 */
	$settings['callback'] = 'sweet_default_cover_image';


	return $settings;
}

add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'sweet_bp_cover_image_css', 10, 1 );
add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'sweet_bp_cover_image_css', 10, 1 );


/**
 * Function to customize the display of the cover image
 *
 * @param array $params
 *
 * @return string|void
 */
function sweet_default_cover_image( $params = array() ) {

	if ( empty( $params ) || ( isset( $params['cover_image'] ) && ! $params['cover_image'] ) ) {
		return;
	}

	/* Add body class for users with cover */
	add_filter( 'body_class', 'sweet_bp_cover_add_body_class', 30 );

	$cover_image = 'background-image: url(' . $params['cover_image'] . '); ' .
	               'background-repeat: no-repeat; background-size: cover; background-position: center center !important;';

	if ( bp_is_user() ) {

		return '
		/* Cover image */
		body.buddypress.is-user-profile div#profile {
			' . $cover_image . '
		}';
	} else {
		return '
		/* Cover image */
		body.buddypress #header-cover-image  {
			' . $cover_image . '
		}';
	}
}

//inject custom class for profile pages
function sweet_bp_cover_add_body_class( $classes ) {
	$classes[] = 'is-user-profile';

	return $classes;
}


/* END COVER */


if ( ! function_exists( 'bp_groups_front_template_part' ) ) {
	/**
	 * Output the contents of the current group's home page.
	 *
	 * You should only use this when on a single group page.
	 *
	 * @since 2.4.0
	 */
	function bp_groups_front_template_part() {
		$located = bp_groups_get_front_template();

		if ( false !== $located ) {
			$slug = str_replace( '.php', '', $located );

			/**
			 * Let plugins adding an action to bp_get_template_part get it from here
			 *
			 * @param string $slug Template part slug requested.
			 * @param string $name Template part name requested.
			 */
			do_action( 'get_template_part_' . $slug, $slug, false );

			load_template( $located, true );

		} else if ( bp_is_active( 'activity' ) ) {
			bp_get_template_part( 'groups/single/activity' );

		} else if ( bp_is_active( 'members' ) ) {
			bp_groups_members_template_part();
		}

		return $located;
	}
}


/* Display BuddyPress Member Types Filters in Members Directory */

add_action( 'bp_members_directory_member_types', 'kleo_bp_member_types_tabs' );
function kleo_bp_member_types_tabs() {
	if ( ! bp_get_current_member_type() ) {
		$member_types = bp_get_member_types( array(), 'objects' );
		if ( $member_types ) {
			foreach ( $member_types as $member_type ) {
				if ( $member_type->has_directory == 1 ) {
					echo '<li id="members-' . esc_attr( $member_type->name ) . '" class="bp-member-type-filter">';
					echo '<a href="' . bp_get_members_directory_permalink() . 'type/' . $member_type->directory_slug . '/">' . sprintf( '%s <span>%d</span>', $member_type->labels['name'], kleo_bp_count_member_types( $member_type->name ) ) . '</a>';
					echo '</li>';
				}
			}
		}
	}
}


add_filter( 'bp_modify_page_title', 'kleo_bp_members_type_directory_page_title', 9, 4 );
function kleo_bp_members_type_directory_page_title( $title, $original_title, $sep, $seplocation ) {
	$member_type = bp_get_current_member_type();
	if ( bp_is_directory() && $member_type ) {
		$member_type = bp_get_member_type_object( $member_type );
		if ( $member_type ) {
			global $post;
			$post->post_title = $member_type->labels['name'];
			$title            = $member_type->labels['name'] . " " . $sep . " " . $original_title;
		}
	}

	return $title;
}


add_filter( 'bp_get_total_member_count', 'kleo_bp_get_total_member_count_member_type' );
function kleo_bp_get_total_member_count_member_type() {
	$count       = bp_core_get_active_member_count();
	$member_type = bp_get_current_member_type();
	if ( bp_is_directory() && $member_type ) {
		$count = kleo_bp_count_member_types( $member_type );
	}

	return $count;
}


add_filter( 'bp_get_total_friend_count', 'kleo_bp_get_total_friend_count_member_type' );
function kleo_bp_get_total_friend_count_member_type() {
	$user_id     = get_current_user_id();
	$count       = friends_get_total_friend_count( $user_id );
	$member_type = bp_get_current_member_type();
	if ( bp_is_directory() && $member_type ) {
		global $bp, $wpdb;
		$friends = $wpdb->get_results( "SELECT count(1) as count FROM {$bp->friends->table_name} bpf
        LEFT JOIN {$wpdb->term_relationships} tr ON (bpf.initiator_user_id = tr.object_id || bpf.friend_user_id = tr.object_id )
        LEFT JOIN {$wpdb->terms} t ON t.term_id = tr.term_taxonomy_id
        WHERE t.slug = '" . $member_type . "' AND (bpf.initiator_user_id = $user_id || bpf.friend_user_id = $user_id ) AND tr.object_id != $user_id AND bpf.is_confirmed = 1", ARRAY_A );
		$count   = 0;
		if ( isset( $friends['0']['count'] ) && is_numeric( $friends['0']['count'] ) ) {
			$count = $friends['0']['count'];
		}
	}

	return $count;
}


function kleo_bp_count_member_types( $member_type = '' ) {
	if ( ! bp_is_root_blog() ) {
		switch_to_blog( bp_get_root_blog_id() );
	}
	global $wpdb;
	$sql           = array(
		'select' => "SELECT t.slug, tt.count FROM {$wpdb->term_taxonomy} tt LEFT JOIN {$wpdb->terms} t",
		'on'     => 'ON tt.term_id = t.term_id',
		'where'  => $wpdb->prepare( 'WHERE tt.taxonomy = %s', 'bp_member_type' ),
	);
	$members_count = $wpdb->get_results( join( ' ', $sql ) );
	$members_count = wp_filter_object_list( $members_count, array( 'slug' => $member_type ), 'and', 'count' );
	$members_count = array_values( $members_count );
	if ( isset( $members_count[0] ) && is_numeric( $members_count[0] ) ) {
		$members_count = $members_count[0];
	} else {
		$members_count = 0;
	}
	restore_current_blog();

	return $members_count;
}


add_filter( 'bp_before_has_members_parse_args', 'kleo_bp_set_has_members_type_arg', 10, 1 );
function kleo_bp_set_has_members_type_arg( $args ) {
	$member_type  = bp_get_current_member_type();
	$member_types = bp_get_member_types( array(), 'names' );
	if ( isset( $args['scope'] ) && ! isset( $args['member_type'] ) && in_array( $args['scope'], $member_types ) ) {
		if ( $member_type ) {
			unset( $args['scope'] );
		} else {
			$args['member_type'] = $args['scope'];
		}
	}

	return $args;
}

add_action( 'bp_before_member_header_meta', 'kleo_bp_profile_member_type_label' );
function kleo_bp_profile_member_type_label() {
	$member_type = bp_get_member_type( bp_displayed_user_id() );
	if ( empty( $member_type ) ) {
		return;
	}
	$member_type_object = bp_get_member_type_object( $member_type );
	if ( $member_type_object ) {
		$member_type_label = '<p class="kleo_bp_profile_member_type_label">' . esc_html( $member_type_object->labels['singular_name'] ) . '</p>';
		echo apply_filters( 'kleo_bp_profile_member_type_label', $member_type_label );
	}
}

/* Render search form horizontal on members page */
add_action( 'kleo_bp_before_page', 'kleo_members_filter' );
if ( ! function_exists( 'kleo_members_filter' ) ) {
	function kleo_members_filter() {
		if ( ! bp_is_members_directory() ) {
			return;
		}
		global $bp_search_fields;

		if ( function_exists( 'kleo_bp_search_form_horizontal' ) && bp_is_active( 'xprofile' ) ) {
			$mode = ( isset( $bp_search_fields['button_show'] ) && $bp_search_fields['button_show'] == 1 ) ? true : false;
			kleo_bp_search_form_horizontal( $mode );
		}
	}
}

/* Members directory page title */
add_action( 'bp_before_directory_members_content', 'sweet_bp_members_title' );
function sweet_bp_members_title() {
	global $bp_results;

	$page_title = get_the_title();
	$lead       = '';
	if ( isset( $_GET['bs'] ) && ! empty( $bp_results['users'] )
	     && ( ( isset( $bp_results['users'][0] ) && $bp_results['users'][0] != 0 ) || ! isset( $bp_results['users'][0] ) )
	) {
		$page_title = __( 'Profile Search <span class="pink-text">results</span>', 'sweetdate' );
		$count      = count( $bp_results['users'] );
		$lead       = sprintf( esc_html__( 'Your search returned %s %s', 'sweetdate' ),
			$count,
			_n( 'member', 'members', $count, 'sweetdate' )
		);
	}

	echo '<h2>' . $page_title . '</h2>';
	echo '<p class="lead">' . $lead . '</p>';
}

/* BuddyPress 3.0 compat */
if ( 'legacy' !== get_option( '_bp_theme_package_id' ) && false == apply_filters( 'bp_30_disable_compat', false ) ) {
	update_option( '_bp_theme_package_id', 'legacy' );
}
