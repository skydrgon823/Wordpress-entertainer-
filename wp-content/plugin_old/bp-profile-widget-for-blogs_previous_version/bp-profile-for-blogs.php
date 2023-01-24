<?php
/**
 * Plugin Name: BuddyPress User Info Widget
 * Version: 1.2.9
 * Description: Let Blog Admins show all/some of their BuddyPress profile fields on their blogs as widget
 * Credits: Concept by Bowe(http://bp-tricks.com) and Mercime(http://buddypress.org/developers/mercime)
 * License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
 * Author: Brajesh Singh( BuddyDev.com )
 * Author URI: https://buddydev.com
 * Plugin URI:https://buddydev.com/buddypress/buddypress-profile-widget-for-blogs/
 */

/***
 * Copyright (C) 2010 Brajesh Singh(buddydev.com)
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or  any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses>.
 */
class BPDev_BPProfile_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( false, $name = __( 'BuddyPress User Info', 'bp-profile-widget-for-blogs' ) );
	}

	/**
	 * Display.
	 *
	 * @param array $args widget args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {
		// do not display widget if it should only show for the logged in user and the user is not logged in.
		$role = isset( $instance['user_role'] ) ? $instance['user_role'] : '';
		if ( 'loggedin' === $role && ! is_user_logged_in() ) {
			return;
		} elseif ( 'displayed' === $role && ! bp_is_user() ) {
			return;
		}

		$user_id = 0;

		if ( 'loggedin' === $role ) {
			$user_id = get_current_user_id();
		} elseif ( 'displayed' === $role ) {
			$user_id = bp_displayed_user_id();
		}

		$title = $instance['title'];
		if ( $user_id ) {
			$user  = get_user_by( 'id', $user_id );
			$title = str_replace( array( '%username%', '%display_name%', '%first_name%' ), array(
				$user->user_login,
				$user->display_name,
				$user->first_name
			), $title );
		}


		echo $args['before_widget'];
		echo $args['before_title']
		     . wp_kses_data( $title )
		     . $args['after_title'];

		self::show_blog_profile( $instance );
		echo $args['after_widget'];
	}

	/**
	 * Update widget.
	 *
	 * @param array $new_instance new settings.
	 * @param array $old_instance old settings.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		foreach ( $new_instance as $key => $val ) {
			$instance[ $key ] = $val;// update the instance.
		}

		return $instance;
	}

	/**
	 * Display form.
	 *
	 * @param array $instance widget instance.
	 *
	 * @return string|void
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array(
			'title'       => __( 'My Profile', 'bp-profile-widget-for-blogs' ),
			'show_avatar' => 'yes',
			'user_role'   => 'administrator',
		) );

		$title = wp_strip_all_tags( $instance['title'] );

		?>

		<p>
			<label for="bpdev-widget-title">
				<?php _e( 'Title:', 'bp-profile-widget-for-blogs' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( stripslashes( $title ) ); ?>"/>
			</label>
		</p>

		<p><label for="bpdev-widget-role">
				<?php _e( 'List profiles for:', 'bp-profile-widget-for-blogs' ); ?>
				<select id="<?php echo $this->get_field_id( 'user_role' ); ?>"
				        name="<?php echo $this->get_field_name( 'user_role' ); ?>">
					<?php wp_dropdown_roles( $instance['user_role'] ); ?>
					<option value="loggedin" <?php selected( $instance['user_role'], 'loggedin' ); ?>><?php _e( 'Logged In User', 'bp-profile-widget-for-blogs' ); ?></option>
					<option value="displayed" <?php selected( $instance['user_role'], 'displayed' ); ?>><?php _e( 'Displayed User', 'bp-profile-widget-for-blogs' ); ?></option>
				</select>
			</label>
		</p>


		<p>
			<label for="bpdev-widget-show-avatar"><?php _e( 'Show Avatar', 'bp-profile-widget-for-blogs' ); ?>
				<input type="radio" id="<?php echo $this->get_field_id( 'show_avatar' ); ?>" name="<?php echo $this->get_field_name( 'show_avatar' ); ?>" value="yes" <?php checked( $instance['show_avatar'], 'yes' ); ?> >Yes
				<input type="radio" id="<?php echo $this->get_field_id( 'show_avatar' ); ?>" name="<?php echo $this->get_field_name( 'show_avatar' ); ?>" value="no" <?php checked( $instance['show_avatar'], 'no' ); ?>>No
			</label>
		</p>
		<?php
		// get all xprofile fields and ask user whether to show them or not.
		?>
		<h3><?php _e( 'Profile Fields Visibility', 'bp-profile-widget-for-blogs' ); ?></h3>
		<table>

			<?php
            if ( function_exists( 'bp_has_profile' ) ) :
				if ( bp_has_profile( array(
					'member_type'            => false,
				) ) ) :
                    while ( bp_profile_groups() ) :
                        bp_the_profile_group(); ?>
					    <?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

	                    <?php
	                    $fld_name       = bp_get_the_profile_field_input_name();
	                    $fld_val        = isset( $instance[ $fld_name ] ) ? $instance[ $fld_name ] : 'yes';
	                    ?>
					<tr>
						<td>
							<label for="<?php echo $fld_name; ?>"><?php bp_the_profile_field_name() ?></label>
						</td>
						<td>
							<input type="radio" id="<?php echo $this->get_field_id( $fld_name ); ?>" name="<?php echo $this->get_field_name( $fld_name ); ?>" value="yes" <?php checked( $fld_val, 'yes' ); ?> >Show
							<input type="radio" id="<?php echo $this->get_field_id( $fld_name ); ?>" name="<?php echo $this->get_field_name( $fld_name ); ?>" value="no" <?php checked( $fld_val, 'no' ); ?>>Hide
						</td>
					</tr>

				<?php endwhile;
			endwhile;
			endif;
			endif;
			?>
		</table>

		<?php
	}

	/**
	 * Get user by role.
	 *
	 * @param string $user_role user role.
	 *
	 * @return array
	 */
	public static function get_users( $user_role = null ) {
		$users = get_users( array(
			'role'    => $user_role,
			'blog_id' => get_current_blog_id(),
			'fields'  => 'ID',
		) );

		return $users;
	}

	/**
	 * Show profile.
	 *
	 * @param array $instance instance.
	 */
	public static function show_blog_profile( $instance ) {

		// if buddypress is not active, return.
		if ( ! function_exists( 'buddypress' ) ) {
			return;
		}

		$show_avatar = $instance['show_avatar'];// we need to preserve for multi admin.
		$user_role   = $instance['user_role'];

		unset( $instance['show_avatar'] );

		// unset the title of the widget
        // because we will be iterating over the instance fields.
		unset( $instance['title'] );
		// unset the title of the widget,
        // because we will be iterating over the instance fields.
		unset( $instance['user_role'] );

        $users = array();

		if ( $user_role == 'loggedin' ) {
			$users[] = get_current_user_id();
		} elseif ( $user_role == 'displayed' ) {
			if ( bp_is_user() ) {
				$users[] = bp_displayed_user_id();
			}
		} else {
			$users = apply_filters( 'bp_blog_profile_for_users', self::get_users( $user_role ) );//may be we can improve it too
		}


		if ( empty( $users ) ) {
			return;
		}

		foreach ( $users as $user ) {

			$user_id = $user;//["user_id"];

			$op = "<table class='my-blog-profile bp-blog-user-profile bp-blog-user-profile-{$user}'>";

			if ( $show_avatar == 'yes' ) {
				$op .= "<tr class='user-avatar'>";
			
				$op .= "<td>" . bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'thumb' ) ) . "<br>".bp_core_get_userlink( $user_id )."</td>";
				$op .= "</tr>";
			}
$op .= "<tr><td >";
			//bad approach, because buddypress does not allow to fetch the field name from field key
			if ( function_exists( 'bp_has_profile' ) ) :
				if ( bp_has_profile( 'user_id=' . $user_id ) ) :

					while ( bp_profile_groups() ) : bp_the_profile_group();

						while ( bp_profile_fields() ) : bp_the_profile_field();

							$fld_name = bp_get_the_profile_field_input_name();
							if ( array_key_exists( $fld_name, $instance ) && $instance[ $fld_name ] == 'yes' ) {
							    $v = '';
							    if(bp_get_the_profile_field_name()=="Birthday"){
							        $dt = xprofile_get_field_data( bp_get_the_profile_field_id(),$user_id, 'comma' );
							        $from = new DateTime($dt);
                                    $to   = new DateTime('today');
                                    
                                    # procedural
                                    $v = $from->diff($to)->y;
							    } else if(bp_get_the_profile_field_name()=="I am a"){
							        $value = xprofile_get_field_data( bp_get_the_profile_field_id(),$user_id, 'comma' );
							        if($value == "Woman") $v = "F";
							        else $v = "M";
							    } else if(bp_get_the_profile_field_name()=="Looking for a"){
							        $value = xprofile_get_field_data( bp_get_the_profile_field_id(),$user_id, 'comma' );
							       $v = "Looking for a ".$value;
							    } else {
							        $value = xprofile_get_field_data( bp_get_the_profile_field_id(),$user_id, 'comma' );
							        $v = $value;
							    }
								$profilefields[] = $v;
							}
						endwhile;
					endwhile;
				endif;
			endif;
			$op .= implode("|",$profilefields);
			$op .= "</td></tr>";
			$op .= "</table>";
			echo $op;
		}
	}
}

/** Let us register the widget*/

function bpdev_register_bpprofile_for_blogs_widgets() {

	register_widget( 'BPDev_BPProfile_Widget' );
}

add_action( 'bp_widgets_init', 'bpdev_register_bpprofile_for_blogs_widgets' );
