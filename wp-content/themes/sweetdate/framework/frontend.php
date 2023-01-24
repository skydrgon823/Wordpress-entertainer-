<?php

//render custom css resulted from theme options
add_action( 'wp_head', 'kleo_custom_css' );
function kleo_custom_css() {
	global $kleo_sweetdate;
	echo "<style>\n";
	echo strip_tags( $kleo_sweetdate->render_css() );
	echo "\n</style>\n";
}

/***************************************************
 * Custom frontend style
 ***************************************************/

//header background and styles
$kleo_sweetdate->add_bg_css( 'header_background', '.header-bg' );
$kleo_sweetdate->add_css( '#header, #header .form-header .lead, #header label {color:' . sq_option( 'header_font_color' ) . ';} #header a:not(.button), div#main .widgets-container.sidebar_location .form-search a:not(.button), .form-search.custom input[type="text"],.form-search.custom input[type="password"], .form-search.custom select {color:' . sq_option( 'header_primary_color' ) . ';} #header a:not(.button):hover,#header a:not(.button):focus{color:' . sq_option( 'header_secondary_color' ) . ';}' );

//top menu bar
$kleo_sweetdate->add_css( '.top-bar ul > li:not(.name):hover, .top-bar ul > li:not(.name).active, .top-bar ul > li:not(.name):focus { background: ' . sq_option( 'menu_primary_color' ) . ';}#header .top-bar ul > li:hover:not(.name) a {color:' . sq_option( 'menu_color_enabled', '#fff' ) . '}; .top-bar ul > li:not(.name):hover a, .top-bar ul > li:not(.name).active a, .top-bar ul > li:not(.name):focus a { color: ' . sq_option( 'header_font_color' ) . '; }.top-bar ul > li.has-dropdown .dropdown:before { border-color: transparent transparent ' . sq_option( 'menu_primary_color' ) . ' transparent; }.top-bar ul > li.has-dropdown .dropdown li a {color: ' . sq_option( 'header_font_color' ) . ';background: ' . sq_option( 'menu_primary_color' ) . ';}.top-bar ul > li.has-dropdown .dropdown li a:hover,.top-bar ul > li.has-dropdown .dropdown li a:focus { background: ' . sq_option( 'menu_secondary_color' ) . ';}.top-bar ul > li.has-dropdown .dropdown li.has-dropdown .dropdown:before {border-color: transparent ' . sq_option( 'menu_primary_color' ) . ' transparent transparent;}' );

$kleo_sweetdate->add_css( '.lt-ie9 .top-bar section > ul > li a:hover, .lt-ie9 .top-bar section > ul > li a:focus { color: ' . sq_option( 'header_font_color' ) . '; }' .
                          '.lt-ie9 .top-bar section > ul > li:hover, .lt-ie9 .top-bar section > ul > li:focus { background: ' . sq_option( 'menu_primary_color' ) . '; }' .
                          '.lt-ie9 .top-bar section > ul > li.active { background: ' . sq_option( 'menu_primary_color' ) . '; color: ' . sq_option( 'header_font_color' ) . '; }' );

//breadcrumb
$kleo_sweetdate->add_bg_css( 'breadcrumb_background', '#breadcrumbs-wrapp' );
$kleo_sweetdate->add_css( '#breadcrumbs-wrapp, ul.breadcrumbs li:before {color:' . sq_option( 'breadcrumb_font_color' ) . ';} #breadcrumbs-wrapp a{color:' . sq_option( 'breadcrumb_primary_color' ) . ';} #breadcrumbs-wrapp a:hover,#breadcrumbs-wrapp a:focus{color:' . sq_option( 'breadcrumb_secondary_color' ) . ';}' );

//body background
$kleo_sweetdate->add_bg_css( 'body_background', '.kleo-page' );
$kleo_sweetdate->add_css( 'div#main {color:' . sq_option( 'body_font_color' ) . ';}a:not(.button),div#main a:not(.button):not(.elementor-button), #header .form-footer a:not(.button){color:' . sq_option( 'body_primary_color' ) . ';} ' .
                          'div#main a:not(.button):not(.elementor-button):hover, a:not(.button):not(.elementor-button):hover,a:not(.button):focus,div#main a:not(.button):focus{color:' . sq_option( 'body_secondary_color' ) . ';}' );
//sidebar
$kleo_sweetdate->add_css( 'div#main .widgets-container.sidebar_location {color:' . sq_option( 'sidebar_font_color' ) . ';} div#main .widgets-container.sidebar_location a:not(.button){color:' . sq_option( 'sidebar_primary_color' ) . ';} div#main .widgets-container.sidebar_location a:not(.button):hover,div#main a:not(.button):focus{color:' . sq_option( 'sidebar_secondary_color' ) . ';}' );


//footer background and colors
$kleo_sweetdate->add_bg_css( 'footer_background', '#footer' );
$kleo_sweetdate->add_css( '#footer, #footer .footer-social-icons a:not(.button), #footer h5{color:' . sq_option( 'footer_font_color' ) . ';} #footer a:not(.button){color:' . sq_option( 'footer_primary_color' ) . ';} #footer a:not(.button):hover,#footer a:not(.button):focus{color:' . sq_option( 'footer_secondary_color' ) . ';}' );


//boxed background
if ( sq_option( 'site_style', 'wide-style' ) == 'boxed-style' ) :
	$kleo_sweetdate->add_bg_css( 'boxed_background', 'body' );
endif;

//Headings
if ( sq_option( 'heading' ) ) {
	$kleo_sweetdate->add_typography_css( 'heading' );
}

//other primary/secondary colored elements
$kleo_sweetdate->add_css( '.form-search, .form-header, div.alert-box, div.pagination span.current {background:' . sq_option( 'button_bg_color' ) . '}' );
$kleo_sweetdate->add_css( '.top-links, .top-links a, .circular-progress-item input, .ajax_search_image .icon{color: ' . sq_option( 'button_bg_color' ) . ';}' );
$kleo_sweetdate->add_css( '.form-search .notch {border-top: 10px solid ' . sq_option( 'button_bg_color' ) . ';}' );
$kleo_sweetdate->add_css( '.form-search.custom div.custom.dropdown a.current, .form-search.custom input[type="text"],.form-search.custom input[type="password"], .form-search.custom select {background-color: ' . sq_option( 'button_bg_color_hover' ) . '; }.form-search.custom div.custom.dropdown a.selector, .form-search.custom div.custom.dropdown a.current, .form-search.custom select { border: solid 1px ' . sq_option( 'button_bg_color_hover' ) . '; }' );
$kleo_sweetdate->add_css( '.form-search.custom input[type="text"]::placeholder, .form-search.custom input[type="password"]::placeholder {color: ' . svq_color_contrast( sq_option( 'button_bg_color_hover' ) ) . ';}' );
$kleo_sweetdate->add_css( '.form-search.custom input[type="text"],.form-search.custom input[type="password"] {border: 1px solid ' . sq_option( 'button_bg_color' ) . ' }' );
$kleo_sweetdate->add_css( '.form-header, div.alert-box {color:' . sq_option( 'button_text_color' ) . '}' );
//mediaelement
$kleo_sweetdate->add_css( '.mejs-controls .mejs-time-rail .mejs-time-loaded{background-color: ' . sq_option( 'button_bg_color_hover' ) . '; }' );

//form transparency
$main_color_rgb = hexToRGB( sq_option( 'button_bg_color_hover', '#1FA8D1' ) );
$kleo_sweetdate->add_css( '.form-search {border-left: 10px solid rgba(' . $main_color_rgb['r'] . ', ' . $main_color_rgb['g'] . ', ' . $main_color_rgb['b'] . ', 0.3);  border-right: 10px solid rgba(' . $main_color_rgb['r'] . ', ' . $main_color_rgb['g'] . ', ' . $main_color_rgb['b'] . ', 0.3);}' );
$kleo_sweetdate->add_css( '.form-header {border-left: 10px solid rgba(' . $main_color_rgb['r'] . ', ' . $main_color_rgb['g'] . ', ' . $main_color_rgb['b'] . ', 0.3); border-top: 10px solid rgba(' . $main_color_rgb['r'] . ', ' . $main_color_rgb['g'] . ', ' . $main_color_rgb['b'] . ', 0.3);  border-right: 10px solid rgba(' . $main_color_rgb['r'] . ', ' . $main_color_rgb['g'] . ', ' . $main_color_rgb['b'] . ', 0.3);}' );

//tabs pill and callout
$kleo_sweetdate->add_css( '.tabs.pill.custom dd.active a, .tabs.pill.custom li.active a, div.item-list-tabs ul li a span, #profile .pmpro_label {background: ' . sq_option( 'button_bg_color' ) . '; color: ' . sq_option( 'button_text_color' ) . ';}' );
$kleo_sweetdate->add_css( '.tabs.pill.custom dd.active a:after {border-top: 10px solid ' . sq_option( 'button_bg_color' ) . '}' );
$kleo_sweetdate->add_css( '.tabs.info dd.active a, .tabs.info li.active a, #object-nav ul li.current a, #object-nav ul li.selected a, .tabs.info dd.active, .tabs.info li.active, #object-nav ul li.selected, #object-nav ul li.current {border-bottom: 2px solid ' . sq_option( 'button_bg_color' ) . ';} .tabs.info dd.active a:after, #object-nav ul li.current a:after, #object-nav ul li.selected a:after {border-top:5px solid ' . sq_option( 'button_bg_color' ) . ';}' );
$kleo_sweetdate->add_css( 'div.item-list-tabs li#members-all.selected, div.item-list-tabs li#members-personal.selected, .section-members .item-options .selected {border-bottom: 3px solid ' . sq_option( 'button_bg_color' ) . ';} div.item-list-tabs li#members-all.selected:after, div.item-list-tabs li#members-personal.selected:after, .section-members .item-options .selected:after {border-top: 5px solid ' . sq_option( 'button_bg_color' ) . '}' );

//Primary Buttons
$kleo_sweetdate->add_css( '.button, ul.sub-nav li.current a, .item-list-tabs ul.sub-nav li.selected a, #subnav ul li.current a, .wpcf7-submit, #rtmedia-add-media-button-post-update, #rt_media_comment_submit, .rtmedia-container input[type="submit"] { border: 1px solid ' . sq_option( 'button_bg_color' ) . '; background: ' . sq_option( 'button_bg_color' ) . '; color: ' . sq_option( 'button_text_color' ) . '; }' );
$kleo_sweetdate->add_css( '.button:hover, .button:focus, .form-search .button, .form-search .button:hover, .form-search .button:focus, .wpcf7-submit:focus, .wpcf7-submit:hover, #rtmedia-add-media-button-post-update:hover, #rt_media_comment_submit:hover, .rtmedia-container input[type="submit"]:hover { color: ' . sq_option( 'button_text_color_hover' ) . '; background-color: ' . sq_option( 'button_bg_color_hover' ) . '; border: 1px solid ' . sq_option( 'button_bg_color_hover' ) . '; }' );

//Secondary Buttons
$kleo_sweetdate->add_css( '.button.secondary,.button.dropdown.split.secondary > a, #messages_search_submit, #rtmedia-whts-new-upload-button, #rtMedia-upload-button, #rtmedia_create_new_album,#rtmedia-nav-item-albums-li a,#rtmedia-nav-item-photo-profile-1-li a,#rtmedia-nav-item-video-profile-1-li a,#rtmedia-nav-item-music-profile-1-li a,.bp-member-dir-buttons div.generic-button a.add,.bp-member-dir-buttons div.generic-button a.remove { background-color: ' . sq_option( 'button_secondary_bg_color' ) . '; color: ' . sq_option( 'button_secondary_text_color' ) . '; border: 1px solid ' . sq_option( 'button_secondary_bg_color' ) . '; }' );
$kleo_sweetdate->add_css( '.button.secondary:hover, .button.secondary:focus, .button.dropdown.split.secondary > a:hover, .button.dropdown.split.secondary > a:focus, #messages_search_submit:hover, #messages_search_submit:focus,  #rtmedia-whts-new-upload-button:hover, #rtMedia-upload-button:hover, #rtmedia_create_new_album:hover,#rtmedia-nav-item-albums-li a:hover,#rtmedia-nav-item-photo-profile-1-li a:hover,#rtmedia-nav-item-video-profile-1-li a:hover,#rtmedia-nav-item-music-profile-1-li a:hover,.bp-member-dir-buttons div.generic-button a.add:hover,.bp-member-dir-buttons div.generic-button a.remove:hover { background-color: ' . sq_option( 'button_secondary_bg_color_hover' ) . ';  border: 1px solid ' . sq_option( 'button_secondary_bg_color_hover' ) . '; color: ' . sq_option( 'button_secondary_text_color_hover' ) . '; }' );

$kleo_sweetdate->add_css( '.btn-profile .button.dropdown > ul, .button.dropdown.split.secondary > span {background: ' . sq_option( 'button_secondary_bg_color' ) . ';}' .
                          '.button.dropdown.split.secondary > span:hover, .button.dropdown.split.secondary > span:focus { background-color: ' . sq_option( 'button_secondary_bg_color_hover' ) . '; color: ' . sq_option( 'button_secondary_text_color_hover' ) . ';}' .
                          '#header .btn-profile a:not(.button) {color: ' . sq_option( 'button_secondary_text_color' ) . ';}' .
                          '#header .btn-profile .button.dropdown > ul li:hover a:not(.button),' .
                          '#header .btn-profile .button.dropdown > ul li:focus a:not(.button) {background-color: ' . sq_option( 'button_secondary_bg_color_hover' ) . '; color:' . sq_option( 'button_secondary_text_color_hover' ) . ';}' );

//Bordered Button
$kleo_sweetdate->add_css( '.button.bordered { background-color: #fff; border: 1px solid ' . sq_option( 'button_secondary_bg_color' ) . '; color: ' . sq_option( 'button_secondary_text_color' ) . '; }' );
$kleo_sweetdate->add_css( '.button.bordered:hover,.button.bordered:focus { background-color: ' . sq_option( 'button_secondary_bg_color_hover' ) . '; border: 1px solid ' . sq_option( 'button_secondary_bg_color_hover' ) . '; color: ' . sq_option( 'button_secondary_text_color_hover' ) . '; }' );

//Buddypress header background
$bp_bg = sq_option( 'bp_header_background' );
$kleo_sweetdate->add_bg_css( 'bp_header_background', 'div#profile' );
$kleo_sweetdate->add_css( '#profile, #profile h2, #profile span {color:' . sq_option( 'bp_header_font_color', '#ffffff' ) . ';} #profile .cite a, #profile .regulartab a, #profile .btn-carousel a {color:' . sq_option( 'bp_header_primary_color', '#ffffff' ) . ';} #profile .cite a:hover,#profile .cite a:focus, #profile .regulartab a:hover, #profile .regulartab a:focus, .callout .bp-profile-details:before{color:' . sq_option( 'bp_header_secondary_color' ) . ';}' );
$kleo_sweetdate->add_css( '#profile .tabs.pill.custom dd.active a, #profile .pmpro_label {background: ' . sq_option( 'bp_header_secondary_color' ) . ' }' );
$kleo_sweetdate->add_css( '#profile:after {border-color:' . $bp_bg['color'] . ' transparent transparent transparent;}' );
//transparent items
if ( sq_option( 'bp_items_transparency' ) != '1' ) {
	$kleo_sweetdate->add_css( '#item-header-avatar img, .mySlider img {border-color: rgba(255,255,255,' . sq_option( 'bp_items_transparency' ) . ') !important;}' );
	$kleo_sweetdate->add_css( '#profile .generic-button a, .tabs.pill.custom dd:not(.active) a, #profile .callout, .regulartab dt, .regulartab dd {background: rgba(255,255,255,' . sq_option( 'bp_items_transparency' ) . '); color: ' . sq_option( 'bp_header_font_color' ) . ';}' );
	$kleo_sweetdate->add_css( '#profile hr {border-color: rgba(255,255,255,' . sq_option( 'bp_items_transparency' ) . ');}' );
}

$kleo_sweetdate->add_css( '.rtmedia-container.rtmedia-single-container .row .rtmedia-single-meta button, .rtmedia-single-container.rtmedia-activity-container .row .rtmedia-single-meta button, .rtmedia-item-actions input[type=submit] {border: 1px solid ' . sq_option( 'button_bg_color' ) . '; background: ' . sq_option( 'button_bg_color' ) . '; color: ' . sq_option( 'button_text_color' ) . '; }' );
$kleo_sweetdate->add_css( '.rtmedia-container.rtmedia-single-container .row .rtmedia-single-meta button:hover, .rtmedia-single-container.rtmedia-activity-container .row .rtmedia-single-meta button:hover, .rtmedia-item-actions input[type=submit]:hover { color: ' . sq_option( 'button_text_color_hover' ) . '; background-color: ' . sq_option( 'button_bg_color_hover' ) . '; border: 1px solid ' . sq_option( 'button_bg_color_hover' ) . '; }' );

//Woocommerce
if ( class_exists( 'woocommerce' ) ):
	$kleo_sweetdate->add_css( '.woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-range, .woocommerce span.onsale, .woocommerce-page span.onsale{background:' . sq_option( 'button_bg_color' ) . ';} .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle {border: 1px solid ' . sq_option( 'button_bg_color' ) . ';background:' . sq_option( 'button_bg_color_hover' ) . '}' );
	$kleo_sweetdate->add_css( '.woocommerce .widget_layered_nav_filters ul li a, .woocommerce-page .widget_layered_nav_filters ul li a { border: 1px solid ' . sq_option( 'button_bg_color' ) . '; background-color: ' . sq_option( 'button_bg_color' ) . '; color: ' . sq_option( 'button_text_color' ) . '; }' );
	$kleo_sweetdate->add_css( '.woocommerce div.product .woocommerce-tabs ul.tabs li.active:after, .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active:after, .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active:after, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active:after {border-top:5px solid ' . sq_option( 'button_bg_color' ) . '}.woocommerce #main ul.products li a.view_details_button:not(.button),.woocommerce ul.products li .add_to_cart_button:before,.woocommerce ul.products li .product_type_grouped:before,.woocommerce ul.products li .add_to_cart_button.added:before,.woocommerce ul.products li .add_to_cart_button.loading:before,.woocommerce ul.products li .product_type_external:before,.woocommerce ul.products li .product_type_variable:before, .woocommerce ul.products li .add_to_cart_button.loading,.woocommerce ul.products li .add_to_cart_button,.woocommerce ul.products li .product_type_grouped,.woocommerce ul.products li .view_details_button,.woocommerce ul.products li .product_type_external,.woocommerce ul.products li .product_type_variable{color:' . sq_option( 'button_bg_color' ) . '}' );
	$kleo_sweetdate->add_css( '.woocommerce ul.products li .add_to_cart_button:hover:before, .woocommerce ul.products li .product_type_grouped:hover:before, .woocommerce ul.products li .view_details_button:hover:before, .woocommerce ul.products li .product_type_external:hover:before, .woocommerce ul.products li .product_type_variable:hover:before {color: ' . sq_option( 'button_text_color_hover' ) . ';}' );
	$kleo_sweetdate->add_css( '.woocommerce ul.products li .add_to_cart_button:hover, .woocommerce ul.products li .product_type_grouped:hover, .woocommerce ul.products li .view_details_button:hover, .woocommerce ul.products li .product_type_external:hover, .woocommerce ul.products li .product_type_variable:hover{color: ' . sq_option( 'button_text_color_hover' ) . ';background-color: ' . sq_option( 'button_bg_color_hover' ) . '}' );
endif;

//Squared avatars
if ( sq_option( 'squared_images', 0 ) == 1 ) {
	$kleo_sweetdate->add_css( '#whats-new-avatar,.avatar,#activity-stream div.activity-avatar,.attachment-shop_thumbnail,.carousel-profiles li,.carousel-profiles img,.buddypress.widgets ul.item-list .item-avatar,.buddypress.widgets .avatar-block .item-avatar,#bbpress-forums div.bbp-forum-author .bbp-author-avatar,#bbpress-forums div.bbp-topic-author .bbp-author-avatar,#bbpress-forums div.bbp-reply-author .bbp-author-avatar, .search-item .avatar img { border-radius: 3px !important; }' );
}

//mobile
if ( sq_option( 'responsive_design' ) == 1 ) {
	$kleo_sweetdate->add_css( '@media only screen and (max-width: 940px) {.top-bar ul > li:not(.name):hover, .top-bar ul > li:not(.name).active, .top-bar ul > li:not(.name):focus { background: ' . sq_option( 'menu_secondary_color' ) . '; }.top-bar { background: ' . sq_option( 'menu_primary_color' ) . '; }.top-bar > ul .name h1 a { background: ' . sq_option( 'menu_secondary_color' ) . '; }.top-bar ul > li.has-dropdown.moved > .dropdown li a:hover { background: ' . sq_option( 'menu_secondary_color' ) . '; display: block; }.top-bar ul > li.has-dropdown .dropdown li.has-dropdown > a li a:hover, .top-bar ul > li.toggle-topbar { background: ' . sq_option( 'menu_secondary_color' ) . '; }}' );
}
/* -----------------------------------------------------------------------------
 * END Front end style
 */


/**
 * Retina js logo
 * Load high resolution logo image is we are on a retina display
 */

if ( sq_option( 'logo_retina' ) != '' ) {
	add_action( 'wp_footer', 'kleo_retina_logo' );
}

function kleo_retina_logo() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			if (window.devicePixelRatio > 1) {
				var image = jQuery("#logo_img");
				imageName = '<?php echo sq_option( 'logo_retina' );?>';
				//rename image
				image.attr('src', imageName);
			}
		});
	</script>
	<?php
}


/***************************************************
 * Customize wp-login.php
 ***************************************************/
function custom_login_css() {
	global $kleo_sweetdate;
	echo '<style>';

	echo strip_tags( $kleo_sweetdate->get_bg_css( 'header_background', 'body.login' ) );

	echo "\n";
	echo '.login h1 a { background-image: url("' . esc_url( sq_option( 'logo', get_template_directory_uri() . '/assets/images/logo.png' ) ) . '"); background-size: contain;width: 326px; min-height: 80px;}';
	echo '#login {padding: 20px 0 0;}';
	echo '.login #nav a, .login #backtoblog a {color:' . sq_option( 'header_primary_color' ) . '!important;text-shadow:none;}';

	echo '</style>';
}

add_action( 'login_head', 'custom_login_css' );

function kleo_new_wp_login_url() {
	return home_url();
}

add_filter( 'login_headerurl', 'kleo_new_wp_login_url' );

function kleo_new_wp_login_title() {
	return get_option( 'blogname' );
}

add_filter( 'login_headertext', 'kleo_new_wp_login_title' );
