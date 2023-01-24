<?php
if ( ! function_exists( 'hexToRGB' ) && ! function_exists( 'hextorgb' ) ) {
	function hexToRGB( $hex ) {
		$hex   = str_replace( "#", "", $hex );
		$color = array();

		if ( strlen( $hex ) == 3 ) {
			$color['r'] = hexdec( substr( $hex, 0, 1 ) . $r );
			$color['g'] = hexdec( substr( $hex, 1, 1 ) . $g );
			$color['b'] = hexdec( substr( $hex, 2, 1 ) . $b );
		} else if ( strlen( $hex ) == 6 ) {
			$color['r'] = hexdec( substr( $hex, 0, 2 ) );
			$color['g'] = hexdec( substr( $hex, 2, 2 ) );
			$color['b'] = hexdec( substr( $hex, 4, 2 ) );
		}

		return $color;
	}
}

if ( ! function_exists( 'RGBToHex' ) && ! function_exists( 'rgbtohex' ) ) {
	function RGBToHex( $r, $g, $b ) {
		$hex = "#";
		$hex .= str_pad( dechex( $r ), 2, "0", STR_PAD_LEFT );
		$hex .= str_pad( dechex( $g ), 2, "0", STR_PAD_LEFT );
		$hex .= str_pad( dechex( $b ), 2, "0", STR_PAD_LEFT );

		return $hex;
	}
}

if ( ! function_exists( 'svq_color_contrast' ) ) {
	function svq_color_contrast( $hexcolor, $hsl = false ) {
		// Strip # sign is present
		$hexcolor = str_replace( "#", "", $hexcolor );

		// Make sure it's 6 digits
		if ( strlen( $hexcolor ) === 3 ) {
			$hexcolor = str_split( $hexcolor );
			$hexcolor = $hexcolor[0] . $hexcolor[0] . $hexcolor[1] . $hexcolor[1] . $hexcolor[2] . $hexcolor[2];
		}

		$r   = hexdec( substr( $hexcolor, 0, 2 ) );
		$g   = hexdec( substr( $hexcolor, 2, 2 ) );
		$b   = hexdec( substr( $hexcolor, 4, 2 ) );
		$yiq = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;

		if ( $hsl ) {
			return ( $yiq >= 154 ) ? '0, 0%, 0%' : '0, 0%, 100%';
		} else {
			return ( $yiq >= 154 ) ? '#000000' : '#ffffff';
		}
	}
}


/*
 * Retrive custom field
 */
function get_cfield( $meta = null, $id = null ) {
	if ( $meta === null ) {
		return false;
	}

	if ( $id === null ) {
		$id = get_the_ID();
	}

	return get_post_meta( $id, '_kleo_' . $meta, true );
}

/*
 * Echo the custom field
 */
function the_cfield( $meta = null, $id = null ) {
	if ( $meta === null ) {
		echo '';
	}

	if ( $id === null ) {
		$id = get_the_ID();
	}

	echo get_post_meta( $id, '_kleo_' . $meta, true );
}


/*
 * Get POST value
 */
function get_postval( $val ) {
	global $_POST;
	if ( isset( $_POST[ $val ] ) && ! empty( $_POST[ $val ] ) ) {
		return $_POST[ $val ];
	} else {
		return false;
	}
}


/**
 * Set selected attribute in select form
 *
 * @param string $request
 * @param string $val
 */
function set_selected( $request, $val ) {
	global $_REQUEST;
	if ( isset( $_REQUEST[ $request ] ) && $_REQUEST[ $request ] == $val ) {
		echo 'selected="selected"';
	} else {
		echo '';
	}
}

/**
 * Returns selected attribute in select form
 *
 * @param string $request $_REQUEST value
 * @param string $val value to check uppon
 * @param string $default default value if no $_REQUEST is set
 */
function get_selected( $request, $val, $default = false ) {
	global $_REQUEST;
	if ( isset( $_REQUEST[ $request ] ) && $_REQUEST[ $request ] == $val ) {
		return 'selected="selected"';
	} elseif ( isset( $default ) && $default == $val ) {
		return 'selected="selected"';
	} else {
		return '';
	}
}


//TRIM WORD
function word_trim( $string, $count, $ellipsis = false ) {
	$words = explode( ' ', $string );
	if ( count( $words ) > $count ) {
		array_splice( $words, $count );
		$string = implode( ' ', $words );
		if ( is_string( $ellipsis ) ) {
			$string .= $ellipsis;
		} elseif ( $ellipsis ) {
			$string .= '&hellip;';
		}
	}

	return $string;
}

//TRIM by characters
function char_trim( $string, $count = 50, $ellipsis = false ) {
	$trimstring = substr( $string, 0, $count );
	if ( strlen( $string ) > $count ) {
		if ( is_string( $ellipsis ) ) {
			$trimstring .= $ellipsis;
		} elseif ( $ellipsis ) {
			$trimstring .= '&hellip;';
		}
	}

	return $trimstring;
}

//SANITIZE
function kleo_cleanInput( $input ) {

	$search = array(
		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);

	$output = preg_replace( $search, '', $input );

	return $output;
}

function kleo_sanitize( $input ) {
	if ( is_array( $input ) ) {
		foreach ( $input as $var => $val ) {
			$output[ $var ] = kleo_sanitize( $val );
		}
	} else {
		if ( get_magic_quotes_gpc() ) {
			$input = stripslashes( $input );
		}
		$input  = kleo_cleanInput( $input );
		$output = addslashes( $input );
	}

	return $output;
}


//GET THE LINK FOR AN ARCHIVE
if ( ! function_exists( 'get_archive_link' ) ) {
	function get_archive_link( $post_type ) {
		global $wp_post_types;
		$archive_link = false;
		if ( isset( $wp_post_types[ $post_type ] ) ) {
			$wp_post_type = $wp_post_types[ $post_type ];
			if ( $wp_post_type->publicly_queryable ) {
				if ( $wp_post_type->has_archive && $wp_post_type->has_archive !== true ) {
					$slug = $wp_post_type->has_archive;
				} else if ( isset( $wp_post_type->rewrite['slug'] ) ) {
					$slug = $wp_post_type->rewrite['slug'];
				} else {
					$slug = $post_type;
				}
			}
			$archive_link = get_option( 'siteurl' ) . "/{$slug}/";
		}

		return apply_filters( 'archive_link', $archive_link, $post_type );
	}
}


if ( ! function_exists( 'kleo_pagination' ) ) :
	/**
	 * Displays pagination where if is required
	 *
	 * @param string $pages - Number of pages for the current section(this is set automatically if it is omitted)
	 * @param integer $range - How many pagination links to show
	 *
	 * @return void
	 */
	function kleo_pagination( $pages = '', $range = 3 ) {
		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}

		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links( array(
			'base'      => $pagenum_link,
			'format'    => $format,
			'total'     => $GLOBALS['wp_query']->max_num_pages,
			'current'   => $paged,
			'mid_size'  => $range,
			'add_args'  => array_map( 'urlencode', $query_args ),
			'prev_text' => '<i class="icon-chevron-left"></i>',
			'next_text' => '<i class="icon-chevron-right"></i>',
			'type'      => 'array'
		) );

		$output = '';

		if ( $links ) {

			$output .= '<div class="row">';
			$output .= '<div class="twelve columns">';
			$output .= '<ul class="pagination seventhqueen">';

			foreach ( $links as $link ) {
				$output .= '<li>' . $link . '</li>';
			}

			$output .= '</ul>';
			$output .= '</div>';
			$output .= '</div>';

		}

		echo apply_filters( 'kleo_pagination_output', $output );
	}
endif;


if ( ! function_exists( 'awesome_array' ) ):
	function awesome_array() {
		$fonts = array(
			'0'                  => esc_html__( 'Select icon', 'sweetdate' ),
			'glass'              => 'glass',
			'music'              => 'music',
			'search'             => 'search',
			'envelope'           => 'envelope',
			'heart'              => 'heart',
			'star'               => 'star',
			'star-empty'         => 'star-empty',
			'user'               => 'user',
			'film'               => 'film',
			'th-large'           => 'th-large',
			'th'                 => 'th',
			'th-list'            => 'th-list',
			'ok'                 => 'ok',
			'remove'             => 'remove',
			'zoom-in'            => 'zoom-in',
			'zoom-out'           => 'zoom-out',
			'off'                => 'off',
			'signal'             => 'signal',
			'cog'                => 'cog',
			'trash'              => 'trash',
			'home'               => 'home',
			'file'               => 'file',
			'time'               => 'time',
			'road'               => 'road',
			'download-alt'       => 'download-alt',
			'download'           => 'download',
			'upload'             => 'upload',
			'inbox'              => 'inbox',
			'play-circle'        => 'play-circle',
			'repeat'             => 'repeat',
			'refresh'            => 'refresh',
			'list-alt'           => 'list-alt',
			'lock'               => 'lock',
			'flag'               => 'flag',
			'headphones'         => 'headphones',
			'volume-off'         => 'volume-off',
			'volume-down'        => 'volume-down',
			'volume-up'          => 'volume-up',
			'qrcode'             => 'qrcode',
			'barcode'            => 'barcode',
			'tag'                => 'tag',
			'tags'               => 'tags',
			'book'               => 'book',
			'bookmark'           => 'bookmark',
			'print'              => 'print',
			'camera'             => 'camera',
			'font'               => 'font',
			'bold'               => 'bold',
			'italic'             => 'italic',
			'text-height'        => 'text-height',
			'text-width'         => 'text-width',
			'align-left'         => 'align-left',
			'align-center'       => 'align-center',
			'align-right'        => 'align-right',
			'align-justify'      => 'align-justify',
			'list'               => 'list',
			'indent-left'        => 'indent-left',
			'indent-right'       => 'indent-right',
			'facetime-video'     => 'facetime-video',
			'picture'            => 'picture',
			'pencil'             => 'pencil',
			'map-marker'         => 'map-marker',
			'adjust'             => 'adjust',
			'tint'               => 'tint',
			'edit'               => 'edit',
			'share'              => 'share',
			'check'              => 'check',
			'move'               => 'move',
			'step-backward'      => 'step-backward',
			'fast-backward'      => 'fast-backward',
			'backward'           => 'backward',
			'play'               => 'play',
			'pause'              => 'pause',
			'stop'               => 'stop',
			'forward'            => 'forward',
			'fast-forward'       => 'fast-forward',
			'step-forward'       => 'step-forward',
			'eject'              => 'eject',
			'chevron-left'       => 'chevron-left',
			'chevron-right'      => 'chevron-right',
			'plus-sign'          => 'plus-sign',
			'minus-sign'         => 'minus-sign',
			'remove-sign'        => 'remove-sign',
			'ok-sign'            => 'ok-sign',
			'question-sign'      => 'question-sign',
			'info-sign'          => 'info-sign',
			'screenshot'         => 'screenshot',
			'remove-circle'      => 'remove-circle',
			'ok-circle'          => 'ok-circle',
			'ban-circle'         => 'ban-circle',
			'arrow-left'         => 'arrow-left',
			'arrow-right'        => 'arrow-right',
			'arrow-up'           => 'arrow-up',
			'arrow-down'         => 'arrow-down',
			'share-alt'          => 'share-alt',
			'resize-full'        => 'resize-full',
			'resize-small'       => 'resize-small',
			'plus'               => 'plus',
			'minus'              => 'minus',
			'asterisk'           => 'asterisk',
			'exclamation-sign'   => 'exclamation-sign',
			'gift'               => 'gift',
			'leaf'               => 'leaf',
			'fire'               => 'fire',
			'eye-open'           => 'eye-open',
			'eye-close'          => 'eye-close',
			'warning-sign'       => 'warning-sign',
			'plane'              => 'plane',
			'calendar'           => 'calendar',
			'random'             => 'random',
			'comment'            => 'comment',
			'magnet'             => 'magnet',
			'chevron-up'         => 'chevron-up',
			'chevron-down'       => 'chevron-down',
			'retweet'            => 'retweet',
			'shopping-cart'      => 'shopping-cart',
			'folder-close'       => 'folder-close',
			'folder-open'        => 'folder-open',
			'resize-vertical'    => 'resize-vertical',
			'resize-horizontal'  => 'resize-horizontal',
			'bar-chart'          => 'bar-chart',
			'twitter-sign'       => 'twitter-sign',
			'facebook-sign'      => 'facebook-sign',
			'camera-retro'       => 'camera-retro',
			'key'                => 'key',
			'cogs'               => 'cogs',
			'comments'           => 'comments',
			'thumbs-up'          => 'thumbs-up',
			'thumbs-down'        => 'thumbs-down',
			'star-half'          => 'star-half',
			'heart-empty'        => 'heart-empty',
			'signout'            => 'signout',
			'linkedin-sign'      => 'linkedin-sign',
			'pushpin'            => 'pushpin',
			'external-link'      => 'external-link',
			'signin'             => 'signin',
			'trophy'             => 'trophy',
			'github-sign'        => 'github-sign',
			'upload-alt'         => 'upload-alt',
			'lemon'              => 'lemon',
			'phone'              => 'phone',
			'check-empty'        => 'check-empty',
			'bookmark-empty'     => 'bookmark-empty',
			'phone-sign'         => 'phone-sign',
			'twitter'            => 'twitter',
			'facebook'           => 'facebook',
			'github'             => 'github',
			'unlock'             => 'unlock',
			'credit-card'        => 'credit-card',
			'rss'                => 'rss',
			'hdd'                => 'hdd',
			'bullhorn'           => 'bullhorn',
			'bell'               => 'bell',
			'certificate'        => 'certificate',
			'hand-right'         => 'hand-right',
			'hand-left'          => 'hand-left',
			'hand-up'            => 'hand-up',
			'hand-down'          => 'hand-down',
			'circle-arrow-left'  => 'circle-arrow-left',
			'circle-arrow-right' => 'circle-arrow-right',
			'circle-arrow-up'    => 'circle-arrow-up',
			'circle-arrow-down'  => 'circle-arrow-down',
			'globe'              => 'globe',
			'wrench'             => 'wrench',
			'tasks'              => 'tasks',
			'filter'             => 'filter',
			'briefcase'          => 'briefcase',
			'fullscreen'         => 'fullscreen',
			'group'              => 'group',
			'link'               => 'link',
			'cloud'              => 'cloud',
			'beaker'             => 'beaker',
			'cut'                => 'cut',
			'copy'               => 'copy',
			'paper-clip'         => 'paper-clip',
			'save'               => 'save',
			'sign-blank'         => 'sign-blank',
			'reorder'            => 'reorder',
			'list-ul'            => 'list-ul',
			'list-ol'            => 'list-ol',
			'strikethrough'      => 'strikethrough',
			'underline'          => 'underline',
			'table'              => 'table',
			'magic'              => 'magic',
			'truck'              => 'truck',
			'pinterest'          => 'pinterest',
			'pinterest-sign'     => 'pinterest-sign',
			'google-plus-sign'   => 'google-plus-sign',
			'google-plus'        => 'google-plus',
			'money'              => 'money',
			'caret-down'         => 'caret-down',
			'caret-up'           => 'caret-up',
			'caret-left'         => 'caret-left',
			'caret-right'        => 'caret-right',
			'columns'            => 'columns',
			'sort'               => 'sort',
			'sort-down'          => 'sort-down',
			'sort-up'            => 'sort-up',
			'envelope-alt'       => 'envelope-alt',
			'linkedin'           => 'linkedin',
			'undo'               => 'undo',
			'legal'              => 'legal',
			'dashboard'          => 'dashboard',
			'comment-alt'        => 'comment-alt',
			'comments-alt'       => 'comments-alt',
			'bolt'               => 'bolt',
			'sitemap'            => 'sitemap',
			'umbrella'           => 'umbrella',
			'paste'              => 'paste',
			'lightbulb'          => 'lightbulb',
			'exchange'           => 'exchange',
			'cloud-download'     => 'cloud-download',
			'cloud-upload'       => 'cloud-upload',
			'user-md'            => 'user-md',
			'stethoscope'        => 'stethoscope',
			'suitcase'           => 'suitcase',
			'bell-alt'           => 'bell-alt',
			'coffee'             => 'coffee',
			'food'               => 'food',
			'file-alt'           => 'file-alt',
			'building'           => 'building',
			'hospital'           => 'hospital',
			'ambulance'          => 'ambulance',
			'medkit'             => 'medkit',
			'fighter-jet'        => 'fighter-jet',
			'beer'               => 'beer',
			'h-sign'             => 'h-sign',
			'plus-sign-alt'      => 'plus-sign-alt',
			'double-angle-left'  => 'double-angle-left',
			'double-angle-right' => 'double-angle-right',
			'double-angle-up'    => 'double-angle-up',
			'double-angle-down'  => 'double-angle-down',
			'angle-left'         => 'angle-left',
			'angle-right'        => 'angle-right',
			'angle-up'           => 'angle-up',
			'angle-down'         => 'angle-down',
			'desktop'            => 'desktop',
			'laptop'             => 'laptop',
			'tablet'             => 'tablet',
			'mobile-phone'       => 'mobile-phone',
			'circle-blank'       => 'circle-blank',
			'quote-left'         => 'quote-left',
			'quote-right'        => 'quote-right',
			'spinner'            => 'spinner',
			'circle'             => 'circle',
			'reply'              => 'reply',
			'github-alt'         => 'github-alt',
			'folder-close-alt'   => 'folder-close-alt',
			'folder-open-alt'    => 'folder-open-alt',
		);

		return $fonts;
	}
endif;

if ( ! function_exists( 'get_attachment_id_from_url' ) ) {
	function get_attachment_id_from_url( $url ) {
		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$url'";

		return $wpdb->get_var( $query );
	}
}


/**
 * Get the current page url
 * @return string
 */
function kleo_full_url() {
	$s        = empty( $_SERVER["HTTPS"] ) ? '' : ( $_SERVER["HTTPS"] == "on" ) ? "s" : "";
	$protocol = substr( strtolower( $_SERVER["SERVER_PROTOCOL"] ), 0, strpos( strtolower( $_SERVER["SERVER_PROTOCOL"] ), "/" ) ) . $s;
	$port     = ( $_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443" ) ? "" : ( ":" . $_SERVER["SERVER_PORT"] );
	$uri      = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
	$segments = explode( '?', $uri, 2 );
	$url      = $segments[0];
	$url      = str_replace( "www.", "", $url );

	return $url;
}


/* Get current Buddypress page */
function kleo_bp_get_page_id() {
	$current_page_id = null;
	$page_array      = get_option( 'bp-pages' );

	if ( bp_is_register_page() ) { /* register page */
		$current_page_id = $page_array['register'];
	} elseif ( bp_is_members_directory() ) { /* members directory */
		$current_page_id = $page_array['members'];
	} elseif ( bp_is_activity_directory() ) { /* activity directory */
		$current_page_id = $page_array['activity'];
	} elseif ( bp_is_groups_directory() ) { /* groups directory */
		$current_page_id = $page_array['groups'];
	} elseif ( bp_is_activation_page() ) { /* activation page */
		$current_page_id = $page_array['activate'];
	}

	return $current_page_id;
}

if ( ! function_exists( 'sq_bp_get_page_id' ) ) {
	function sq_bp_get_page_id() {
		return kleo_bp_get_page_id();
	}
}


function sq_bp_fields_array( $multi = true ) {

	$kleo_bp_textboxes = array();
	$kleo_bp_multi     = array();
	$kleo_bp_dateboxes = array();

	if ( function_exists( 'bp_is_active' ) && bp_is_active( 'xprofile' ) ) :
		if ( function_exists( 'bp_has_profile' ) ) :
			if ( bp_has_profile( 'hide_empty_fields=0' ) ) :
				while ( bp_profile_groups() ) :
					bp_the_profile_group();
					while ( bp_profile_fields() ) :
						bp_the_profile_field();


						switch ( bp_get_the_profile_field_type() ) {
							case 'datebox':
							case 'birthdate':
								$kleo_bp_dateboxes[ bp_get_the_profile_field_id() ] = bp_get_the_profile_field_name();
								break;
							case 'textbox':
							case 'selectbox':
							case 'radio':
								$kleo_bp_textboxes[ bp_get_the_profile_field_id() ] = bp_get_the_profile_field_name();
								break;

							case 'multiselectbox':
							case 'checkbox':
								$kleo_bp_multi[ bp_get_the_profile_field_id() ] = bp_get_the_profile_field_name();
								break;
						}


					endwhile;
				endwhile;
			endif;
		endif;
	endif;

	return $kleo_bp_textboxes + $kleo_bp_multi + $kleo_bp_dateboxes;
}

//JSON Validator function
if ( ! function_exists( 'json_validates' ) ) {
	function json_validates( $data = null ) {

		if ( ! empty( $data ) ) {

			@json_decode( $data );

			return ( json_last_error() === JSON_ERROR_NONE );

		}

		return false;
	}
}
