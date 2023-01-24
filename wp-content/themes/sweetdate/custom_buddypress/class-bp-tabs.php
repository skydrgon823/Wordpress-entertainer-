<?php

/**
 * Tabs class to display tabs next to user photo
 */

do_action( 'kleo_before_bp_tabs' );

if ( ! class_exists( 'BpMembersTabs' ) ):
	class BpMembersTabs {

		private static $instance;
		public $tabs;
		public $active_tab = false;
		public $album_exists = false;
		public $has_data = array();
		public $fields_data = array();

		public function __construct( $tabs ) {

			self::$instance =& $this;

			$this->tabs       = $tabs;
			$this->active_tab = apply_filters( 'kleo_bp_profile_default_top_tab', false );

			$this->render();
		}

		public static function &get_instance() {
			return self::$instance;
		}

		public function render() {
			if ( ! is_array( $this->tabs ) ) {
				return;
			}

			echo '<dl class="tabs pill custom">';

			foreach ( $this->tabs as $key => $tab ) {
				if ( empty( $tab ) ) {
					continue;
				}
				if ( ! isset( $tab['group'] ) ) {
					$tab['group']                = $tab['name'];
					$this->tabs[ $key ]['group'] = $tab['name'];
				}

				$name = 'BpMembersTab_' . $tab['type'];
				if ( class_exists( $name ) ) {
					$tabcls = new $name( $tab );
				} else {
					$tabcls = new BpMembersTab( $tab );
				}

				if ( $tabcls->has_profile_data( $tab['group'] ) ) {
					echo sweet_translate_dynamic( $tabcls->title(), 'buddypress' );
				}

			}
			echo '</dl>';

			echo '<ul class="tabs-content custom">';
			foreach ( $this->tabs as $tab ) {

				if ( isset( $this->fields_data[ $tab['group'] ] ) ) {
					$active = '';
					if ( $this->active_tab == esc_attr( str_replace( "%", "", sanitize_title_with_dashes( $tab['name'] ) ) ) ) {
						$active = 'active';
					}

					echo '<li ' . $active . ' id="' . esc_attr( str_replace( "%", "", sanitize_title_with_dashes( $tab['name'] ) ) ) . 'Tab" class="' . $active . " " . $tab['class'] . '">';
					echo $this->fields_data[ $tab['group'] ]; // PHPCS: XSS ok.
					echo '</li>';
				}
			}
			echo '</ul>';
		}

	}
endif;

/**
 * Base Tab class that is used to render tab data
 * This can be extended by other types you need to define
 */
if ( ! class_exists( 'BpMembersTab' ) ):
	class BpMembersTab {
		/* tabs instance */
		public $tabs_instance;
		public $args;

		public function __construct( $args ) {
			$this->tabs_instance = BpMembersTabs::get_instance();

			$this->args = $args;
		}

		public function title() {
			$active = '';
			if ( $this->tabs_instance->active_tab === false || $this->tabs_instance->active_tab == $this->args["name"] ) {
				$active = 'active';
				$this->tabs_instance->active_tab = esc_attr( str_replace( "%", "", sanitize_title_with_dashes( $this->args["name"] ) ) );
			}

			return '<dd class="' . $active . '"><a href="#' . esc_attr( str_replace( "%", "", sanitize_title_with_dashes( $this->args["name"] ) ) ) . '">' . $this->args["name"] . '</a></dd>';
		}

		public function has_profile_data( $name ) {
			$this->tabs_instance->has_data[ $name ]    = false;
			$this->tabs_instance->fields_data[ $name ] = '';

			if ( bp_is_active( 'xprofile' ) && get_group_id_by_name( $name ) ) :
				if ( bp_has_profile( 'profile_group_id=' . get_group_id_by_name( $name ) ) ) :

					while ( bp_profile_groups() ) : bp_the_profile_group();
						if ( bp_profile_group_has_fields() ) :
							$this->tabs_instance->fields_data[ $name ] .= '<dl class="dl-horizontal">';
							while ( bp_profile_fields() ) : bp_the_profile_field();
								if ( bp_field_has_data() ) :
									$this->tabs_instance->has_data[ $name ]    = true;
									$this->tabs_instance->fields_data[ $name ] .= '<dt class="bp-field-name bp-field-id-' . esc_attr( bp_get_the_profile_field_id() ) . '">' . sweet_translate_dynamic( bp_get_the_profile_field_name(), 'sweetdate' ) . '</dt>';
									$this->tabs_instance->fields_data[ $name ] .= '<dd class="bp-field-value bp-field-id-' . esc_attr( bp_get_the_profile_field_id() ) . '">' . sweet_translate_dynamic( strip_tags(bp_get_the_profile_field_value()), 'sweetdate' ) . '</dd>';
								endif;
							endwhile;
							$this->tabs_instance->fields_data[ $name ] .= '</dl>';
						endif;
					endwhile;

				endif;
			endif;

			if ( $this->tabs_instance->has_data[ $name ] == true ) {
				return true;
			}
		}
	}
endif;

//add_action('kleo_after_bp_memberstabs', 'my_func');


/**
 * Cite Tab type - A different type to list profile fields
 */
if ( ! class_exists( 'BpMembersTab_cite' ) ):
	class BpMembersTab_cite extends BpMembersTab {
		public function __construct( $args ) {
			parent::__construct( $args );
		}

		public function has_profile_data( $name ) {
			$this->tabs_instance->has_data[ $name ]    = false;
			$this->tabs_instance->fields_data[ $name ] = '';

			if ( bp_is_active( 'xprofile' ) && get_group_id_by_name( $name ) ) :
				if ( bp_has_profile( 'profile_group_id=' . get_group_id_by_name( $name ) ) ) :

					while ( bp_profile_groups() ) : bp_the_profile_group();
						if ( bp_profile_group_has_fields() ) :

							while ( bp_profile_fields() ) : bp_the_profile_field();
								if ( bp_field_has_data() ) :
									$this->tabs_instance->has_data[ $name ]    = true;
									$this->tabs_instance->fields_data[ $name ] .= '<div class="callout"><div class="bp-profile-details bp-field-id-' . bp_get_the_profile_field_id() . '">';
									$this->tabs_instance->fields_data[ $name ] .= bp_get_the_profile_field_name();
									$this->tabs_instance->fields_data[ $name ] .= '</div><div class="cite">' . bp_get_the_profile_field_value() . '</div></div>';
								endif;
							endwhile;

						endif;
					endwhile;

				endif;
			endif;

			if ( $this->tabs_instance->has_data[ $name ] == true ) {
				return true;
			}
		}
	}
endif;


/**
 * Bp-Album tab type - Display member photos
 * extends BpMembersTab
 */
if ( ! class_exists( 'BpMembersTab_bp_album' ) ):

	class BpMembersTab_bp_album extends BpMembersTab {
		public function __construct( $args ) {
			parent::__construct( $args );
		}

		public function title() {
			$active = '';
			if ( $this->tabs_instance->active_tab === false || $this->tabs_instance->active_tab == $this->args["name"] ) {
				$active                          = 'active';
				$this->tabs_instance->active_tab = esc_attr( str_replace( "%", "", sanitize_title_with_dashes( $this->args["name"] ) ) );
			}

			return '<dd class="sliderEvent ' . $active . '"><a href="#' . esc_attr( str_replace( "%", "", sanitize_title_with_dashes( $this->args["name"] ) ) ) . '">' . $this->args["name"] . ' <span class="radius label alert">' . bp_album_get_total_picture_count() . '</span></a></dd>';
		}

		public function has_profile_data( $name ) {
			global $bp;
			$save_current_action = $bp->current_action;
			$bp->current_action  = 'hack-to-show-all-albums';

			if ( ! function_exists( 'bp_album_query_pictures' ) ) {
				return false;
			}

			bp_album_query_pictures( 'per_page=100' );
			if ( bp_album_has_pictures() ) :

				$this->tabs_instance->has_data[ $name ]    = true;
				$this->tabs_instance->fields_data[ $name ] = '<div id="gallery-carousel">';
				while ( bp_album_has_pictures() ) : bp_album_the_picture();
					$this->tabs_instance->fields_data[ $name ] .= '<span class="circle">';
					$this->tabs_instance->fields_data[ $name ] .= '<a href="' . bp_album_get_picture_original_url() . '" class="imagelink" data-rel="prettyPhoto[gallery2]">';
					$this->tabs_instance->fields_data[ $name ] .= '<span class="overlay"></span>';
					$this->tabs_instance->fields_data[ $name ] .= '<span class="read"><i class="icon-' . apply_filters( 'kleo_img_rounded_icon', 'heart' ) . '"></i></span>';
					$this->tabs_instance->fields_data[ $name ] .= '<img src="' . bp_album_get_picture_original_url() . '" alt="' . esc_html__( 'Album Image', 'sweetdate' ) . '">';
					$this->tabs_instance->fields_data[ $name ] .= '</a>';
					$this->tabs_instance->fields_data[ $name ] .= '</span>';
				endwhile;
				$this->tabs_instance->fields_data[ $name ] .= '</div><!--end #gallery-carousel-->';
				$this->tabs_instance->fields_data[ $name ] .= '<div class="clearfix"></div>
                <div class="four columns centered btn-carousel hide-for-small">
                  <small><a href="#" id="stanga-prev">' . esc_html__( "PREVIOUS", 'sweetdate' ) . '</a>&nbsp; &nbsp; &nbsp;
                  <a href="#" id="dreapta-next">' . esc_html__( "NEXT", 'sweetdate' ) . '</a></small>
                </div>';

			endif;
			$bp->current_action = $save_current_action;
			bp_album_query_pictures();

			if ( isset( $this->tabs_instance->has_data[ $name ] ) ) {
				return true;
			}
		}

	}
endif;


/**
 * rtMedia tab type - Display member photos
 * extends BpMembersTab
 */
if ( ! class_exists( 'BpMembersTab_rt_media' ) ):

	class BpMembersTab_rt_media extends BpMembersTab {
		private $friendship;
		private $number = 0;

		public function __construct( $args ) {
			parent::__construct( $args );
			$this->friendship = new RTMediaFriends();
		}

		public function title() {
			$active = '';
			if ( $this->tabs_instance->active_tab === false || $this->tabs_instance->active_tab == $this->args["name"] ) {
				$active                          = 'active';
				$this->tabs_instance->active_tab = esc_attr( str_replace( "%", "", sanitize_title_with_dashes( $this->args["name"] ) ) );
			}

			return '<dd class="sliderEvent ' . $active . '">' .
			       '<a href="#' . esc_attr( str_replace( "%", "", sanitize_title_with_dashes( $this->args["name"] ) ) ) . '">' . $this->args["name"] .
			       ' <span class="radius label alert">' . $this->number . '</span>' .
			       '</a></dd>';
		}

		public function has_profile_data( $name ) {
			if ( ! class_exists( 'RTMedia' ) ) {
				return false;
			}

			global $wpdb;
			$user           = $this->get_user();
			$displayed_user = bp_displayed_user_id();
			$table_name     = $wpdb->base_prefix . "rt_rtm_media";

			$where = "SELECT * FROM {$table_name}";
			$where .= " WHERE {$table_name}.media_type = 'photo' AND media_author = $displayed_user";
			$where .= " AND ({$table_name}.privacy is NULL OR {$table_name}.privacy<=0";
			$where .= " AND {$table_name}.context = 'profile'";
			if ( $user ) {
				$where .= " OR {$table_name}.privacy=20";
				//if my profile
				if ( $user == $displayed_user || is_super_admin() ) {
					$where .= " OR {$table_name}.privacy >= 40";
				} else {
					if ( class_exists( 'BuddyPress' ) && bp_is_active( 'friends' ) ) {
						//my friends
						$friends = $this->friendship->get_friends_cache( $user );
						//if displayed user is my friend -> view its pictures
						if ( in_array( $displayed_user, $friends ) ) {
							$where .= " OR {$table_name}.privacy=40";
						}
					}
				}
			}
			$where  .= ") ORDER BY media_id DESC LIMIT " . apply_filters( 'kleo_rtmedia_photo_limit', 100 );
			$myrows = $wpdb->get_results( $where );

			if ( $myrows && count( $myrows ) > 0 ) {
				$this->tabs_instance->has_data[ $name ]    = true;
				$this->tabs_instance->fields_data[ $name ] = '<div id="gallery-carousel" class="rtmedia-list-media rtm-gallery-list">';

				foreach ( $myrows as $row ) {
					$this->number ++;
					$src = wp_get_attachment_image_src( $row->media_id, 'rt_media_thumbnail' );

					$this->tabs_instance->fields_data[ $name ] .= '<span class="circle">';
					$this->tabs_instance->fields_data[ $name ] .= '<a href="' . trailingslashit( trailingslashit( get_rtmedia_user_link( $displayed_user ) ) . RTMEDIA_MEDIA_SLUG . '/' . rtmedia_id( $row->media_id ) ) . '" class="imagelink">';
					$this->tabs_instance->fields_data[ $name ] .= '<span class="overlay"></span>';
					$this->tabs_instance->fields_data[ $name ] .= '<span class="read"><i class="icon-' . apply_filters( 'kleo_img_rounded_icon', 'heart' ) . '"></i></span>';
					$this->tabs_instance->fields_data[ $name ] .= '<img src="' . $src[0] . '" alt="' . esc_attr( get_the_title( $row->media_id ) ) . '">';
					$this->tabs_instance->fields_data[ $name ] .= '</a>';
					$this->tabs_instance->fields_data[ $name ] .= '</span>';

				}
				$this->tabs_instance->fields_data[ $name ] .= '</div>';
				$this->tabs_instance->fields_data[ $name ] .= '<div class="clearfix"></div>
						<div class="four columns centered btn-carousel hide-for-small">
							<small><a href="#" id="stanga-prev">' . esc_html__( "PREVIOUS", 'sweetdate' ) . '</a>&nbsp; &nbsp; &nbsp;
							<a href="#" id="dreapta-next">' . esc_html__( "NEXT", 'sweetdate' ) . '</a></small>
						</div>';
			}

			if ( isset( $this->tabs_instance->has_data[ $name ] ) && $this->tabs_instance->has_data[ $name ] == true ) {
				return true;
			}
		}

		function get_user() {
			if ( is_user_logged_in() ) {
				$user = get_current_user_id();
			} else {
				$user = 0;
			}

			return $user;
		}

	}
endif;


/**
 * Render the contents of a page next to user profile
 *
 */
if ( ! class_exists( 'BpMembersTab_page' ) ):
	class BpMembersTab_page extends BpMembersTab {
		public function has_profile_data( $name ) {
			$page = get_page_by_title( $name );
			if ( $page ) {
				$this->tabs_instance->has_data[ $name ]    = true;
				$content                                   = apply_filters( 'the_content', $page->post_content );
				$this->tabs_instance->fields_data[ $name ] = $content;

				return true;
			} else {
				$this->tabs_instance->has_data[ $name ] = false;

				return false;
			}
		}
	}
endif;
