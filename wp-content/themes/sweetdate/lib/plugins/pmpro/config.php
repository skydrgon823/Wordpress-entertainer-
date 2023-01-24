<?php
/**
 * Membership functions
 *
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 2.0
 */

/* Checkout page manipulation */

if ( function_exists( 'bp_is_active' ) && sq_option( 'pmpro_bp_register', 0 ) == 0 ) {
	
	if ( ! is_user_logged_in() ) {
		add_filter( 'pmpro_skip_account_fields', '__return_true' );
		add_filter( 'pmpro_include_billing_address_fields', '__return_false' );
		add_filter( 'pmpro_include_payment_information_fields', '__return_false' );
		add_filter( 'pmpro_checkout_default_submit_button', '__return_false' );

		add_filter( 'pmpro_checkout_after_user_fields', 'sq_pmpro_login_register' );
	}

}

function sq_pmpro_login_register() {
	?>
	
	<p>
		<?php _e( "Please Login or Register a new account in order to continue", 'paid-memberships-pro' ); ?>
	</p>
	
	<a href="#" data-reveal-id="login_panel" class="small secondary button radius">
		<i class="icon-user"></i> <?php _e( "LOG IN", 'sweetdate' ); ?>
	</a>
	
	<?php if ( get_option( 'users_can_register' ) ) { ?>
		<a href="<?php bp_signup_page(); ?>" class="small button radius">
			<i class="icon-group"></i> <?php _e( "SIGN UP", 'sweetdate' ); ?>
		</a>
	<?php } ?>
	
	<?php
}



//options array for restrictions: kleo_restrict_sweetdate
global $kleo_pay_settings;
$kleo_pay_settings = array(
	array(
		'title' => esc_html__( 'Restrict members directory', 'sweetdate' ),
		'front' => esc_html__( 'View members directory', 'sweetdate' ),
		'name'  => 'members_dir'
	),
	array(
		'title' => esc_html__( 'Restrict viewing other profiles', 'sweetdate' ),
		'front' => esc_html__( 'View members profile', 'sweetdate' ),
		'name'  => 'view_profiles'
	),
	array(
		'title' => esc_html__( 'Restrict access to groups directory', 'sweetdate' ),
		'front' => esc_html__( 'Access group directory', 'sweetdate' ),
		'name'  => 'groups_dir'
	),
	array(
		'title' => esc_html__( 'Restrict access to single group page', 'sweetdate' ),
		'front' => esc_html__( 'Access to groups', 'sweetdate' ),
		'name'  => 'view_groups'
	),
	array(
		'title' => esc_html__( 'Restrict users from viewing site activity', 'sweetdate' ),
		'front' => esc_html__( 'View site activity', 'sweetdate' ),
		'name'  => 'show_activity'
	),
	array(
		'title' => esc_html__( 'Restrict users from sending private messages', 'sweetdate' ),
		'front' => esc_html__( 'Send Private messages', 'sweetdate' ),
		'name'  => 'pm'
	),
	array(
		'title' => esc_html__( 'Restrict users from adding media to their profile using rtMedia or bpAlbum', 'sweetdate' ),
		'front' => esc_html__( 'Add media to your profile', 'sweetdate' ),
		'name'  => 'add_media'
	)
);

$kleo_pay_settings = apply_filters( 'kleo_pmpro_level_restrictions', $kleo_pay_settings );

/**
 * Get saved membership settings
 *
 * @param $theme string
 *
 * @return array
 * @since 2.0
 */
function kleo_memberships( $theme = 'sweetdate' ) {
	$newoptions = sq_option( 'membership' );
	if ( is_array( $newoptions ) && ! empty( $newoptions ) ) {
		$restrict_options = $newoptions;
	} else {
		$restrict_options = get_option( 'kleo_restrict_' . $theme );
	}
	
	return $restrict_options;
}

if ( ! function_exists( 'kleo_pmpro_restrict_rules' ) ):
	/**
	 * Applies restrictions based on the PMPRO -> Advanced settings
	 * @return void
	 * @since 2.0
	 */
	function kleo_pmpro_restrict_rules() {
		//if PMPRO is not activated
		if ( ! function_exists( 'pmpro_url' ) ) {
			return;
		}
		
		//if buddypress is not activated
		if ( ! function_exists( 'bp_is_active' ) ) {
			return;
		}
		
		//full current url
		$actual_link = kleo_full_url();
		
		//our request uri
		$home_url = home_url();
		
		//WPML support
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			global $sitepress;
			$home_url = $sitepress->language_url( ICL_LANGUAGE_CODE );
		}
		
		$home_url = str_replace( "www.", "", $home_url );
		
		$uri = str_replace( untrailingslashit( $home_url ), "", $actual_link );
		
		//restriction match array
		$final = array();
		
		$allowed_chars    = apply_filters( 'kleo_pmpro_allowed_chars', "a-z 0-9~%.:_\-" );
		$restrict_options = kleo_memberships();
		$members_slug     = str_replace( '/', '\/', bp_get_members_root_slug() );
		
		
		/*-----------------------------------------------------------------------------------*/
		/* Preg match rules
		/*-----------------------------------------------------------------------------------*/
		
		//members directory restriction rule
		$final[ "/^\/" . $members_slug . "\/?$/" ] = array( 'name'   => 'members_dir',
		                                                    'type'   => $restrict_options['members_dir']['type'],
		                                                    'levels' => isset( $restrict_options['members_dir']['levels'] ) ? $restrict_options['members_dir']['levels'] : array()
		);
		
		if ( function_exists( 'bp_get_groups_root_slug' ) ) {
			$groups_slug = str_replace( '/', '\/', bp_get_groups_root_slug() );
			
			//groups directory restriction rule
			$final[ "/^\/" . $groups_slug . "\/?$/" ] = array( 'name'   => 'groups_dir',
			                                                   'type'   => $restrict_options['groups_dir']['type'],
			                                                   'levels' => isset( $restrict_options['groups_dir']['levels'] ) ? $restrict_options['groups_dir']['levels'] : array()
			);
			
			//groups single page restriction rule
			$final[ "/^\/" . $groups_slug . "\/[" . $allowed_chars . "\/]+\/?$/" ] = array( 'name'   => 'view_groups',
			                                                                                'type'   => $restrict_options['view_groups']['type'],
			                                                                                'levels' => isset( $restrict_options['view_groups']['levels'] ) ? $restrict_options['view_groups']['levels'] : array()
			);
		}
		
		if ( function_exists( 'bp_get_activity_root_slug' ) ) {
			$activity_slug = str_replace( '/', '\/', bp_get_activity_root_slug() );
			//activity page restriction rule
			$final[ "/^\/" . $activity_slug . "\/?$/" ] = array( 'name'   => 'show_activity',
			                                                     'type'   => $restrict_options['show_activity']['type'],
			                                                     'levels' => isset( $restrict_options['show_activity']['levels'] ) ? $restrict_options['show_activity']['levels'] : array()
			);
		}
		
		/* You can add extra restrictions using this filter */
		$final = apply_filters( 'kleo_pmpro_match_rules', $final );
		
		//no redirection for super-admin
		if ( is_super_admin() ) {
			return false;
		} elseif ( is_user_logged_in() ) //only if logged in
		{
			//restrict media
			if ( preg_match( "/^\/" . $members_slug . "\/" . bp_get_loggedin_user_username() . "\/media\/?/", $uri )
			     || preg_match( "/^\/" . $members_slug . "\/" . bp_get_loggedin_user_username() . "\/album\/?/", $uri )
			) {
				kleo_check_access( 'add_media', $restrict_options );
			} //restrict private messages
            elseif ( bp_is_messages_compose_screen() || bp_is_messages_conversation() ) {
				kleo_check_access( 'pm', $restrict_options );
			}
			
			/* Add other restrictions for own profile */
			do_action( 'kleo_pmro_extra_restriction_before_my_profile', $restrict_options );
			
			//allow me to view other parts of my profile
			if ( bp_is_my_profile() ) {
				return false;
			}
		}
		
		
		//members single profile restriction rule
		if ( bp_is_user() ) {
			kleo_check_access( 'view_profiles', $restrict_options );
		}
		
		//loop trought remaining restrictions
		foreach ( $final as $rk => $rv ) {
			if ( preg_match( $rk, $uri ) ) {
				kleo_check_access( $rv['name'], $restrict_options );
			}
		}
		
		do_action( 'kleo_pmro_extra_restriction_rules', $restrict_options );
	}
endif;

if ( ! is_admin() ) {
	add_action( "init", "kleo_pmpro_restrict_rules" );
}

if ( ! function_exists( 'kleo_check_access' ) ) :
	/**
	 * Checks $area for applied restrictions based on user status(logged in, membership level)
	 * and does the proper redirect
	 * @global object $current_user
	 *
	 * @param string $area
	 * @param array $restrict_options
	 * @param boolean $return Whether to just return true if the restriction should be applied
	 * @since 2.0
	 *
	 * @return mixed|bool
	 */
	function kleo_check_access( $area, $restrict_options = null, $return = false ) {
		global $current_user;
		
		if ( ! $restrict_options ) {
			$restrict_options = kleo_memberships();
		}
		
		if ( pmpro_url( "levels" ) ) {
			$default_redirect = pmpro_url( "levels" );
		} else {
			$default_redirect = bp_get_signup_page();
		}
		$default_redirect = apply_filters( 'kleo_pmpro_url_redirect', $default_redirect );
		
		//no restriction
		if ( $restrict_options[ $area ]['type'] == 0 ) {
			return false;
		}
		
		//restrict all members -> go to home url
		if ( $restrict_options[ $area ]['type'] == 1 ) {
			wp_redirect( apply_filters( 'kleo_pmpro_home_redirect', home_url() ) );
			exit;
		}

		//is a member
		if ( isset( $current_user->membership_level ) && $current_user->membership_level && $current_user->membership_level->ID ) {

			//if restrict my level
			if ( $restrict_options[ $area ]['type'] == 2 && isset( $restrict_options[ $area ]['levels'] ) && is_array( $restrict_options[ $area ]['levels'] ) && ! empty( $restrict_options[ $area ]['levels'] ) && pmpro_hasMembershipLevel( $restrict_options[ $area ]['levels'] ) ) {
				return kleo_pmpro_return_restriction( $return, $default_redirect );
			}
			
			//logged in but not a member
		} else if ( is_user_logged_in() ) {
			if ( $restrict_options[ $area ]['type'] == 2 && isset( $restrict_options[ $area ]['not_member'] ) && $restrict_options[ $area ]['not_member'] == 1 ) {
				return kleo_pmpro_return_restriction( $return, $default_redirect );
			}
		} //not logged in
		else {
			if ( $restrict_options[ $area ]['type'] == 2 && isset( $restrict_options[ $area ]['guest'] ) && $restrict_options[ $area ]['guest'] == 1 ) {
				return kleo_pmpro_return_restriction( $return, $default_redirect );
			}
		}
	}
endif;

/**
 * Calculate if we want to apply the redirect or just return true when restriction is applied
 *
 * @param boolean $return
 * @param string $default_redirect
 *
 * @return boolean
 */
function kleo_pmpro_return_restriction( $return = false, $default_redirect = null ) {
	if ( $return === false ) {
		wp_redirect( $default_redirect );
		exit;
	} else {
		return true;
	}
}


if ( ! function_exists( 'kleo_membership_info' ) ) :
	/**
	 * Add membership info next to profile page username
	 * @since 2.0
	 */
	function kleo_membership_info() {
		global $membership_levels, $current_user;
		if ( ! $membership_levels ) {
			return;
		}
		
		if ( bp_is_my_profile() ) {
			if ( isset( $current_user->membership_level ) && $current_user->membership_level && $current_user->membership_level->ID ) {
				echo '<a href="' . pmpro_url( "account" ) . '"><span class="label radius pmpro_label">' . $current_user->membership_level->name . '</span></a>';
			} else {
				echo '<a href="' . pmpro_url( "levels" ) . '"><span class="label radius pmpro_label">' . esc_html__( "Upgrade account", 'sweetdate' ) . '</span></a>';
			}
		}
	}
endif;
add_action( 'kleo_bp_after_profile_name', 'kleo_membership_info' );


/* BP Profile Message UX compatibility  */
function kleo_bp_profile_message_ux_send_private_message() {
	if ( isset( $_POST['private_message_content'] ) && ! empty( $_POST['private_message_content'] ) ) {
		$content_restricted = esc_html__( "You aren't allowed to perform this action", 'sweetdate' );
		
		if ( kleo_check_access( 'pm', null, true ) ) {
			bp_core_add_message( $content_restricted, 'error' );
			bp_core_redirect( bp_displayed_user_domain() );
		}
	}
}

add_action( 'wp', 'kleo_bp_profile_message_ux_send_private_message', 2 );

/* Restrict email messages content to non paying members */
if ( ! function_exists( 'kleo_pmpro_restrict_pm_email_content' ) ) {
	function kleo_pmpro_restrict_pm_email_content( $email_content, $sender_name, $subject, $content, $message_link, $settings_link, $ud ) {
		
		$restrict_message = false;
		$restrict_options = kleo_memberships();
		$area             = 'pm';
		
		if ( pmpro_getMembershipLevelForUser( $ud->ID ) ) {
			$current_level_obj = pmpro_getMembershipLevelForUser( $ud->ID );
			$current_level     = $current_level_obj->ID;
			
			//if restrict my level
			if ( $restrict_options[ $area ]['type'] == 2 && isset( $restrict_options[ $area ]['levels'] ) && is_array( $restrict_options[ $area ]['levels'] ) && ! empty( $restrict_options[ $area ]['levels'] ) && in_array( $current_level, $restrict_options[ $area ]['levels'] ) ) {
				$restrict_message = true;
			}
			
			//not a member
		} else {
			if ( $restrict_options[ $area ]['type'] == 2 && isset( $restrict_options[ $area ]['not_member'] ) && $restrict_options[ $area ]['not_member'] == 1 ) {
				$restrict_message = true;
			}
		}
		
		if ( $restrict_message ) {
			
			$content       = esc_html__( 'Your current membership does not allow private messages access.', 'sweetdate' );
			$email_content = sprintf( __(
				'%1$s sent you a new message:

Subject: %2$s

"%3$s"

To view and read your messages please log in and visit: %4$s

---------------------
', 'buddypress' ), $sender_name, $subject, $content, $message_link );
			
			// Only show the disable notifications line if the settings component is enabled
			if ( bp_is_active( 'settings' ) ) {
				$email_content .= sprintf( esc_html__( 'To disable these notifications, please log in and go to: %s', 'buddypress' ), $settings_link );
			}
			
			return $email_content;
		}
		
		return $email_content;
		
	}
}
add_filter( 'messages_notification_new_message_message', 'kleo_pmpro_restrict_pm_email_content', 11, 7 );



global $kleo_pay_settings, $sweetdate_sections;
$restrict_options = kleo_memberships();

$old_settings = array();
foreach ( $kleo_pay_settings as $pays ) :
	$old_settings[ $pays['name'] ] = array(
		'type'       => isset( $restrict_options[ $pays['name'] ]['type'] ) ? $restrict_options[ $pays['name'] ]['type'] : null,
		'levels'     => isset( $restrict_options[ $pays['name'] ]['levels'] ) ? $restrict_options[ $pays['name'] ]['levels'] : null,
		'showfield'  => isset( $restrict_options[ $pays['name'] ]['showfield'] ) ? $restrict_options[ $pays['name'] ]['showfield'] : null,
		'guest'      => isset( $restrict_options[ $pays['name'] ]['guest'] ) ? $restrict_options[ $pays['name'] ]['guest'] : null,
		'not_member' => isset( $restrict_options[ $pays['name'] ]['not_member'] ) ? $restrict_options[ $pays['name'] ]['not_member'] : null
	);
endforeach;

$old_settings['kleo_membership_popular'] = get_option( 'kleo_membership_popular' );
$old_settings['kleo_pmpro_levels_order'] = get_option( 'kleo_pmpro_levels_order' );

$sweetdate_sections[] = array(
	'icon'       => 'group',
	'icon_class' => 'icon-large',
	'title'      => esc_html__( 'Memberships', 'sweetdate' ),
	'desc'       => esc_html__( 'Settings related to membership. You need to have Paid Memberships Pro plugin activated', 'sweetdate' ),
	'fields'     => array(
		array(
			'id'     => 'pmpro_bp_register',
			'type'   => 'checkbox',
			'title'  => esc_html__( 'Allow registrations also from PMPRO checkout page.', 'sweetdate' ),
			'desc'   => '<p class="description">' . esc_html__( 'Users will be able to create an account directly at checkout. Will skip BuddyPress registration page. Setting depends on Buddypress to be active.', 'sweetdate' ) . '</p>',
			'switch' => true,
			'std'    => '0' // 1 = checked | 0 = unchecked
		),
		array(
			'id'       => 'membership',
			'type'     => 'text',
			'title'    => esc_html__( 'Membership settings', 'sweetdate' ),
			'sub_desc' => '',
			'callback' => 'pmpro_data_set',
			'std'      => $old_settings
		)
	)
);
