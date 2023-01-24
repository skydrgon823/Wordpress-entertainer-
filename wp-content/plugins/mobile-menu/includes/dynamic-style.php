<?php

/**
 * ---------------
 * Plugin Styling
 * ---------------
 * WP Mobile Menu
 * Copyright WP Mobile Menu 2019 - http://www.wpmobilemenu.com/
 * CUSTOM CSS OUTPUT
 */
global  $mm_fs ;
$plugin_settings = MobileMenuOptions::getInstance( 'mobmenu' );
$default_elements = '';
$logo_height = '';
$header_logo_float = '';
$def_el_arr = $plugin_settings->getOption( 'default_hided_elements' );
$trigger_res = $plugin_settings->getOption( 'width_trigger' );
$right_menu_width = $plugin_settings->getOption( 'right_menu_width' ) . 'px';
$right_menu_width_translate = '100%';
$left_menu_width_translate = '100%';
$left_menu_height_translate = '100%';
$woo_menu_width_translate = '100%';
$woo_menu_width = 0;
$woo_menu_font = '';
$search_results_font = $plugin_settings->getOption( 'search_results_font' );
if ( in_array( '1', $def_el_arr, true ) ) {
    $default_elements .= '.nav, ';
}
if ( in_array( '2', $def_el_arr, true ) ) {
    $default_elements .= '.main-navigation, ';
}
if ( in_array( '3', $def_el_arr, true ) ) {
    $default_elements .= '.genesis-nav-menu, ';
}
if ( in_array( '4', $def_el_arr, true ) ) {
    $default_elements .= '#main-header, ';
}
if ( in_array( '5', $def_el_arr, true ) ) {
    $default_elements .= '#et-top-navigation, ';
}
if ( in_array( '6', $def_el_arr, true ) ) {
    $default_elements .= '.site-header, ';
}
if ( in_array( '7', $def_el_arr, true ) ) {
    $default_elements .= '.site-branding, ';
}
if ( in_array( '8', $def_el_arr, true ) ) {
    $default_elements .= '.ast-mobile-menu-buttons, ';
}
if ( in_array( '9', $def_el_arr, true ) ) {
    $default_elements .= '.storefront-handheld-footer-bar, ';
}
$default_elements .= '.hide';
// Check if the Naked Header is enabled.

if ( $plugin_settings->getOption( 'enabled_naked_header' ) ) {
    $header_bg_color = 'transparent';
    $wrap_padding_top = '0';
    $header_width = '10%';
    // If we are only using one of the menus without any logo.
    if ( $plugin_settings->getOption( 'disabled_logo_text' ) && (!$plugin_settings->getOption( 'enable_left_menu' ) || !$plugin_settings->getOption( 'enable_right_menu' )) ) {
        $header_width = '10%';
    }
} else {
    $header_bg_color = $plugin_settings->getOption( 'header_bg_color' );
    $wrap_padding_top = $plugin_settings->getOption( 'header_height' );
}

// Determine the Width of the Left menu panel.

if ( $plugin_settings->getOption( 'left_menu_width_units' ) ) {
    $left_menu_width = $plugin_settings->getOption( 'left_menu_width' ) . 'px';
    $left_menu_width_translate = $plugin_settings->getOption( 'left_menu_width' ) - 1 . 'px';
} else {
    $left_menu_width = $plugin_settings->getOption( 'left_menu_width_percentage' ) . '%';
}

// Determine the Width of the Right menu panel.

if ( $plugin_settings->getOption( 'right_menu_width_units' ) ) {
    $right_menu_width = $plugin_settings->getOption( 'right_menu_width' ) . 'px';
} else {
    $right_menu_width = $plugin_settings->getOption( 'right_menu_width_percentage' ) . '%';
}


if ( $plugin_settings->getOption( 'logo_height' ) > 0 ) {
    $logo_height = $plugin_settings->getOption( 'logo_height' );
} else {
    $logo_height = $plugin_settings->getOption( 'header_height' );
}

$logo_height = 'height:' . $logo_height . 'px!important;';
$header_height = $plugin_settings->getOption( 'header_height' );
$total_header_height = $header_height;
$banner_height = 0;
$header_margin_top = $plugin_settings->getOption( 'logo_top_margin' );
$header_banner_padding_top = 0;
$header_width = '100%';
// Left Menu Background Image.
$left_menu_bg_image = $plugin_settings->getOption( 'left_menu_bg_image' );
$left_menu_bg_image_size = 'initial';
$cart_menu_icon_font_size = 0;
$cart_icon_top_margin = 0;
$woo_menu_panel_bg_color = 'initial';
$search_icon_font_size = 0;
if ( $plugin_settings->getOption( 'search_icon_font_size' ) ) {
    $search_icon_font_size = $plugin_settings->getOption( 'search_icon_font_size' );
}
if ( $plugin_settings->getOption( 'woo_menu_panel_bg_color' ) ) {
    $woo_menu_panel_bg_color = $plugin_settings->getOption( 'woo_menu_panel_bg_color' );
}
if ( $plugin_settings->getOption( 'left_menu_bg_image_size' ) ) {
    $left_menu_bg_image_size = $plugin_settings->getOption( 'left_menu_bg_image_size' );
}
if ( $plugin_settings->getOption( 'mm_woo_menu_icon_font_size' ) ) {
    $cart_menu_icon_font_size = $plugin_settings->getOption( 'mm_woo_menu_icon_font_size' );
}
if ( $plugin_settings->getOption( 'cart_icon_top_margin' ) ) {
    $cart_icon_top_margin = $plugin_settings->getOption( 'cart_icon_top_margin' );
}
$header_margin_top = $header_margin_top . 'px';
$header_margin_left = '0';
$header_margin_right = '0';
$header_text_position = 'absolute';
$border_menu_size = $plugin_settings->getOption( 'menu_items_border_size' );
$submenu_open_icon_font = $plugin_settings->getOption( 'submenu_open_icon_font' );
// Sticky Header.

if ( $plugin_settings->getOption( 'enabled_sticky_header' ) ) {
    $header_position = 'fixed';
} else {
    $header_position = 'absolute';
}


if ( 'center' === $plugin_settings->getOption( 'header_text_align' ) ) {
    $logo_header_position = 'absolute';
} else {
    $logo_header_position = 'relative';
}

// Header Text alignment.
if ( 'center' === $plugin_settings->getOption( 'header_text_align' ) ) {
    $header_text_position = 'initial';
}
if ( 'left' === $plugin_settings->getOption( 'header_text_align' ) ) {
    $header_margin_left = $plugin_settings->getOption( 'header_text_left_margin' ) . 'px;';
}
if ( 'right' === $plugin_settings->getOption( 'header_text_align' ) ) {
    $header_margin_right = $plugin_settings->getOption( 'header_text_right_margin' ) . 'px;';
}
if ( $plugin_settings->getOption( 'logo_img_retina' ) ) {
    ?>
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {

	.mob-menu-logo-holder .mob-retina-logo {
		display: inline-block;
	}
	.mob-standard-logo {
		display: none!important;
	}
}
<?php 
}
?>

@media screen and ( min-width: 782px ){
		body.admin-bar .mobmenu, body.admin-bar .mobmenu-panel, .show-nav-left.admin-bar .mobmenu-overlay, .show-nav-right.admin-bar .mobmenu-overlay {
			top: 32px!important;
		}
		<?php 
$admin_bar_height = '32';
?>
		body.admin-bar .mobmenu-search-holder {
				top: <?php 
echo  $total_header_height + $admin_bar_height ;
?>px!important;
		}
}

@media screen and ( max-width: 782px ){
	body.admin-bar .mobmenu, body.admin-bar .mobmenu-panel, .show-nav-left.admin-bar .mobmenu-overlay, .show-nav-right.admin-bar .mobmenu-overlay {
		top: 46px!important;
	}

	body.admin-bar .mob-menu-header-banner {
		top: <?php 
echo  $header_banner_padding_top ;
?>px!important;
	}
	<?php 
$admin_bar_height = '46';
?>
	body.admin-bar .mobmenu-search-holder {
		top: <?php 
echo  $total_header_height + $admin_bar_height ;
?>px!important;
	}
	body.admin-bar  .mob-menu-slideout .mobmenu-search-holder {
		top: <?php 
echo  $header_height ;
?>px!important;
	}

}

@media screen and ( max-width: 479px ) {
	.mob-menu-overlay .mobmenu-content {
		padding-top: 5%;
	}
}

@media screen and ( max-width: 782px ) {
	.mob-menu-overlay .mobmenu-content {
		padding-top: 10%;
	}
}

@media screen and ( min-width: 782px ) {
	#mobmenu-footer li:hover {
		background-color: <?php 
echo  $plugin_settings->getOption( 'footer_bg_color_hover' ) ;
?>;
	}
	#mobmenu-footer li:hover i {
		color: <?php 
echo  $plugin_settings->getOption( 'footer_icon_color_hover' ) ;
?>;
	}
}

@media only screen and (min-width:<?php 
echo  $trigger_res + 1 ;
?>px){
	.mob_menu, .mobmenu-panel, .mobmenu, .mobmenu-cart-panel, .mobmenu-footer-menu-holder, .mobmenu-right-panel, .mobmenu-left-panel  {
		display: none!important;
	}
}
<?php 

if ( 0 < $border_menu_size ) {
    $border_menu_color = $plugin_settings->getOption( 'menu_items_border_color' );
    $border_style = $border_menu_size . 'px solid ' . $border_menu_color;
    ?>

		.mobmenu-content li {
			border-bottom: <?php 
    echo  $border_style ;
    ?>;
		}

<?php 
}


if ( $plugin_settings->getOption( 'left_menu_tabbed_menus', false ) ) {
    $border_menu_color = $plugin_settings->getOption( 'menu_items_border_color' );
    $border_style = '2px solid ' . $border_menu_color;
    ?>

		.mobmenu-left-panel .mobmenu-tabs-header .active-tab {
			border-bottom: <?php 
    echo  $border_style ;
    ?>;
		}

<?php 
}


if ( $plugin_settings->getOption( 'right_menu_tabbed_menus', false ) ) {
    $border_menu_color = $plugin_settings->getOption( 'menu_items_border_color' );
    $border_style = '2px solid ' . $border_menu_color;
    ?>

		.mobmenu-right-panel .mobmenu-tabs-header .active-tab {
			border-bottom: <?php 
    echo  $border_style ;
    ?>;
		}

<?php 
}


if ( '' !== $plugin_settings->getOption( 'hide_elements' ) ) {
    ?>
/* Our css Custom Options values */
@media only screen and (max-width:<?php 
    echo  $trigger_res ;
    ?>px){
	<?php 
    echo  $plugin_settings->getOption( 'hide_elements' ) ;
    ?> {
		display:none !important;
	}
}

<?php 
}

?>

@media only screen and (max-width:<?php 
echo  $trigger_res ;
?>px) {

		<?php 
?>
	
	.mobmenur-container i {
		color: <?php 
echo  $plugin_settings->getOption( 'right_menu_icon_color' ) ;
?>;
	}
	.mobmenul-container i {
		color: <?php 
echo  $plugin_settings->getOption( 'left_menu_icon_color' ) ;
?>;
	}
	.mobmenul-container img {
		max-height:  <?php 
echo  $plugin_settings->getOption( 'header_height' ) - $plugin_settings->getOption( 'logo_top_margin' ) * 2 - $plugin_settings->getOption( 'left_icon_top_margin' ) ;
?>px;
		float: left;
	}
	.mobmenur-container img {
		max-height:  <?php 
echo  $plugin_settings->getOption( 'header_height' ) - $plugin_settings->getOption( 'logo_top_margin' ) * 2 - $plugin_settings->getOption( 'right_icon_top_margin' ) ;
?>px;
		float: right;
	}
	.mob-expand-submenu i {
		font-size: <?php 
echo  $plugin_settings->getOption( 'submenu_icon_font_size' ) ;
?>px;
	}
	#mobmenuleft li a , #mobmenuleft li a:visited, .mobmenu-content h2, .mobmenu-content h3, .show-nav-left .mob-menu-copyright, .show-nav-left .mob-expand-submenu i {
		color: <?php 
echo  $plugin_settings->getOption( 'left_panel_text_color' ) ;
?>;

	}
	.mob-cancel-button {
		font-size: <?php 
echo  $plugin_settings->getOption( 'close_icon_font_size' ) ;
?>px!important;
	}

	/* 3rd Level Left Menu Items Background color on Hover*/
	.mobmenu-content #mobmenuleft .sub-menu  .sub-menu li a:hover {
		color: <?php 
echo  $plugin_settings->getOption( 'left_panel_3rd_menu_text_color_hover' ) ;
?>;
	}
	/* 3rd Level Left Menu Items Background color on Hover*/
	.mobmenu-content #mobmenuleft .sub-menu .sub-menu li:hover {
		background-color: <?php 
echo  $plugin_settings->getOption( 'left_panel_3rd_menu_bg_color_hover' ) ;
?>;
	}
	.mobmenu-content #mobmenuleft li:hover, .mobmenu-content #mobmenuright li:hover  {
		background-color: <?php 
echo  $plugin_settings->getOption( 'left_panel_hover_bgcolor' ) ;
?>;
	}
	.mobmenu-content #mobmenuright li:hover  {
		background-color: <?php 
echo  $plugin_settings->getOption( 'right_panel_hover_bgcolor' ) ;
?> ;
	}
	/* 3rd Level Right Menu Items Background color on Hover*/
	.mobmenu-content #mobmenuright .sub-menu .sub-menu li:hover {
		background-color: <?php 
echo  $plugin_settings->getOption( 'right_panel_3rd_menu_bg_color_hover' ) ;
?>;
	}
	/* 3rd Level Right Menu Items Background color on Hover*/
	.mobmenu-content #mobmenuright .sub-menu  .sub-menu li a:hover {
		color: <?php 
echo  $plugin_settings->getOption( 'right_panel_3rd_menu_text_color_hover' ) ;
?>;
	}

	<?php 
if ( $plugin_settings->getOption( 'header_shadow' ) && !$plugin_settings->getOption( 'enabled_naked_header' ) ) {
    ?>
		.mob-menu-header-holder {
			box-shadow:0px 0px 8px 0px rgba(0,0,0,0.15);
		}
	<?php 
}
?>
	.mobmenu-content #mobmenuleft .sub-menu {
		background-color: <?php 
echo  $plugin_settings->getOption( 'left_panel_submenu_bgcolor' ) ;
?> ;
		margin: 0;
		color: <?php 
echo  $plugin_settings->getOption( 'left_panel_submenu_text_color' ) ;
?> ;
		width: 100%;
		position: initial;
		height: 100%;
	}
	.mob-menu-left-bg-holder {
		<?php 

if ( $left_menu_bg_image ) {
    ?>
			background: url(<?php 
    echo  wp_get_attachment_url( $left_menu_bg_image ) ;
    ?>);
		<?php 
}

?>
		opacity: <?php 
echo  $plugin_settings->getOption( 'left_menu_bg_opacity' ) / 100 ;
?>;
		background-attachment: fixed ;
		background-position: center top ;
		-webkit-background-size:  <?php 
echo  $left_menu_bg_image_size ;
?>;
		-moz-background-size: <?php 
echo  $left_menu_bg_image_size ;
?>;
		background-size: <?php 
echo  $left_menu_bg_image_size ;
?>;
	}
	.mob-menu-right-bg-holder { 
		<?php 

if ( $plugin_settings->getOption( 'right_menu_bg_image' ) ) {
    ?>
			background: url(<?php 
    echo  wp_get_attachment_url( $plugin_settings->getOption( 'right_menu_bg_image' ) ) ;
    ?>);
		<?php 
}

?>
		opacity: <?php 
echo  $plugin_settings->getOption( 'right_menu_bg_opacity' ) / 100 ;
?>;
		background-attachment: fixed ;
		background-position: center top ;
		-webkit-background-size: <?php 
echo  $plugin_settings->getOption( 'right_menu_bg_image_size' ) ;
?>;
		-moz-background-size: <?php 
echo  $plugin_settings->getOption( 'right_menu_bg_image_size' ) ;
?>;
		background-size:  <?php 
echo  $plugin_settings->getOption( 'right_menu_bg_image_size' ) ;
?>;
	}
	<?php 
?>
	.mobmenu-content #mobmenuleft .sub-menu a {
		color: <?php 
echo  $plugin_settings->getOption( 'left_panel_submenu_text_color' ) ;
?> ;
	}
	.mobmenu-content #mobmenuright .sub-menu  a {
		color: <?php 
echo  $plugin_settings->getOption( 'right_panel_submenu_text_color' ) ;
?> ;
	}
	.mobmenu-content #mobmenuright .sub-menu .sub-menu {
		background-color: inherit;
	}
	.mobmenu-content #mobmenuright .sub-menu {
		background-color: <?php 
echo  $plugin_settings->getOption( 'right_panel_submenu_bgcolor' ) ;
?> ;
		margin: 0;
		color: <?php 
echo  $plugin_settings->getOption( 'right_panel_submenu_text_color' ) ;
?> ;
		position: initial;
		width: 100%;
	}
	#mobmenuleft li:hover a, #mobmenuleft li:hover i {
		color: <?php 
echo  $plugin_settings->getOption( 'left_panel_hover_text_color' ) ;
?>;
	}
	#mobmenuright li a , #mobmenuright li a:visited, .show-nav-right .mob-menu-copyright, .show-nav-right .mob-expand-submenu i {
		color: <?php 
echo  $plugin_settings->getOption( 'right_panel_text_color' ) ;
?> ;
	}
	#mobmenuright li a:hover {
		color: <?php 
echo  $plugin_settings->getOption( 'right_panel_hover_text_color' ) ;
?> ;
	}
	.mobmenul-container {
		top: <?php 
echo  $plugin_settings->getOption( 'left_icon_top_margin' ) ;
?>px;
		margin-left: <?php 
echo  $plugin_settings->getOption( 'left_icon_left_margin' ) ;
?>px;
		margin-top: <?php 
echo  $header_margin_top ;
?>;
		height: <?php 
echo  $header_height ;
?>px;
		float: left;
	}
	.mobmenur-container {
		top: <?php 
echo  $plugin_settings->getOption( 'right_icon_top_margin' ) ;
?>px;
		margin-right: <?php 
echo  $plugin_settings->getOption( 'right_icon_right_margin' ) ;
?>px;
		margin-top: <?php 
echo  $header_margin_top ;
?>;
	}
	<?php 
switch ( $plugin_settings->getOption( 'header_text_align' ) ) {
    case 'left':
        $header_logo_float = 'float:left;';
        break;
    case 'center':
        $header_logo_float = '';
        break;
    case 'right':
        $header_logo_float = 'float:right;';
        break;
}
?>
	.mob-menu-logo-holder {
		margin-top:   <?php 
echo  $header_margin_top ;
?>;
		text-align:   <?php 
echo  $plugin_settings->getOption( 'header_text_align' ) ;
?>;
		margin-left:  <?php 
echo  $header_margin_left ;
?>;
		margin-right: <?php 
echo  $header_margin_right ;
?>;
		height:       <?php 
echo  $header_height ;
?>px;
		<?php 
echo  $header_logo_float ;
?>
	}
	.mob-menu-header-holder {
		width:  <?php 
echo  $header_width ;
?> ;
		background-color: <?php 
echo  $header_bg_color ;
?> ;
		height: <?php 
echo  $total_header_height ;
?>px ;
		position:<?php 
echo  $header_position ;
?>;
	}
	body.mob-menu-overlay, body.mob-menu-slideout, body.mob-menu-slideout-over, body.mob-menu-slideout-top {
		padding-top: <?php 
echo  $wrap_padding_top ;
?>px;
	}
	<?php 

if ( '' !== $plugin_settings->getOption( 'left_menu_bg_gradient' ) ) {
    $left_panel_bg_color = $plugin_settings->getOption( 'left_menu_bg_gradient' ) . ';';
} else {
    $left_panel_bg_color = 'background-color:' . $plugin_settings->getOption( 'left_panel_bg_color' ) . ';';
}


if ( '' !== $plugin_settings->getOption( 'right_menu_bg_gradient' ) ) {
    $right_panel_bg_color = $plugin_settings->getOption( 'right_menu_bg_gradient' ) . ';';
} else {
    $right_panel_bg_color = 'background-color:' . $plugin_settings->getOption( 'right_panel_bg_color' ) . ';';
}

$mm_woo_menu_panel_bg_color = '#CCC';
if ( $plugin_settings->getOption( 'mm_woo_menu_panel_bg_color' ) ) {
    $mm_woo_menu_panel_bg_color = $plugin_settings->getOption( 'mm_woo_menu_panel_bg_color' );
}

if ( $plugin_settings->getOption( 'mm_woo_menu_bg_gradient' ) ) {
    $cart_panel_bg_color = $plugin_settings->getOption( 'mm_woo_menu_bg_gradient' ) . ';';
} else {
    $cart_panel_bg_color = 'background-color:' . $mm_woo_menu_panel_bg_color . ';';
}

?>
	.mobmenul-container, .mobmenur-container{
		position: <?php 
echo  $logo_header_position ;
?>; 
	}
	.mobmenu-left-panel {
		<?php 
echo  $left_panel_bg_color ;
?>;
		width:  <?php 
echo  $left_menu_width ;
?>;  
	}
	.mobmenu-right-panel {
		<?php 
echo  $right_panel_bg_color ;
?>
		width:  <?php 
echo  $right_menu_width ;
?>; 
	}
	.show-nav-left .mobmenu-overlay, .show-nav-right .mobmenu-overlay, .show-mob-menu-search .mobmenu-overlay  {
		background: <?php 
echo  $plugin_settings->getOption( 'overlay_bg_color' ) ;
?>;
	}
	.mob-menu-slideout-top .mobmenu-overlay {
		display:none!important;
	}
	.mob-menu-slideout.show-nav-left .mobmenu-push-wrap, .mob-menu-slideout.show-nav-left .mob-menu-header-holder {
		-webkit-transform: translateX(<?php 
echo  $left_menu_width ;
?>);
		-moz-transform: translateX(<?php 
echo  $left_menu_width ;
?>);
		-ms-transform: translateX(<?php 
echo  $left_menu_width ;
?>);
		-o-transform: translateX(<?php 
echo  $left_menu_width ;
?>);
		transform: translateX(<?php 
echo  $left_menu_width ;
?>);
	}
	.mob-menu-slideout.show-nav-right .mobmenu-push-wrap, .mob-menu-slideout.show-nav-right .mob-menu-header-holder {
		-webkit-transform: translateX(-<?php 
echo  $right_menu_width ;
?>);
		-moz-transform: translateX(-<?php 
echo  $right_menu_width ;
?>);
		-ms-transform: translateX(-<?php 
echo  $right_menu_width ;
?>);
		-o-transform: translateX(-<?php 
echo  $right_menu_width ;
?>);
		transform: translateX(-<?php 
echo  $right_menu_width ;
?>);
	}
	.mob-menu-slideout-top .mobmenu-panel {
		width:  100%;
		height: <?php 
echo  $left_menu_height_translate ;
?>;
		z-index: 1;
		position: fixed;
		left: 0px;
		top: 0px;
		max-height: <?php 
echo  $left_menu_height_translate ;
?>;
		-webkit-transform: translateY(-<?php 
echo  $left_menu_height_translate ;
?>);
		-moz-transform: translateY(-<?php 
echo  $left_menu_height_translate ;
?>);
		-ms-transform: translateY(-<?php 
echo  $left_menu_height_translate ;
?>);
		-o-transform: translateY(-<?php 
echo  $left_menu_height_translate ;
?>);
		transform: translateY(-<?php 
echo  $left_menu_height_translate ;
?>);
	}
	.mob-menu-slideout-top.show-nav-left .show-panel, .mob-menu-slideout-top.show-nav-right .show-panel  {
		-webkit-transform: translateY(0px);
		-moz-transform: translateY(0px);
		-ms-transform: translateY(0px);
		-o-transform: translateY(0px);
		transform: translateY(0px);
		z-index: 300000;
	}
	.mob-menu-slideout-over.show-nav-left .mobmenu-left-panel {
		overflow: hidden;
	}
	.show-nav-left .mobmenu-panel.show-panel , .show-nav-right .mobmenu-panel.show-panel {
		z-index: 300000;
	}
	/* Hides everything pushed outside of it */
	.mob-menu-slideout .mobmenu-panel, .mob-menu-slideout-over .mobmenu-panel, .mob-menu-slideout .mobmenu-cart-panel, .mob-menu-slideout-over .mobmenu-cart-panel {
		position: fixed;
		top: 0;
		height: 100%;
		overflow-y: auto;
		overflow-x: auto;
		z-index: 10000;
		opacity: 1;
	}
	/*End of Mobmenu Slide Over */
	.mobmenu .headertext { 
		color: <?php 
echo  $plugin_settings->getOption( 'header_text_color' ) ;
?> ;
		text-decoration: none;
	}
	.headertext span {
		position: <?php 
echo  $header_text_position ;
?>;
		line-height: <?php 
echo  $header_height ;
?>px;
	}
	.mobmenu-search-holder {
		top: <?php 
echo  $total_header_height ;
?>px;
	}
	/*Premium options  */
	<?php 
?>

	/* Mobile Menu Frontend CSS Style*/
	body.mob-menu-overlay, body.mob-menu-slideout, body.mob-menu-slideout-over, body.mob-menu-slideout-top  {
		overflow-x: hidden;
	}
	
	.mobmenu-left-panel li a, .leftmbottom, .leftmtop{
		padding-left: <?php 
echo  $plugin_settings->getOption( 'left_menu_content_padding' ) ;
?>%;
		padding-right: <?php 
echo  $plugin_settings->getOption( 'left_menu_content_padding' ) ;
?>%;
	}
	.mobmenu-content li > .sub-menu li {
		padding-left: calc(<?php 
echo  $plugin_settings->getOption( 'left_menu_content_padding' ) ;
?>*1%);
	}

	.mobmenu-right-panel li, .rightmbottom, .rightmtop{
		padding-left: <?php 
echo  $plugin_settings->getOption( 'right_menu_content_padding' ) ;
?>%;
		padding-right: <?php 
echo  $plugin_settings->getOption( 'right_menu_content_padding' ) ;
?>%;
	}
	.mobmenul-container i {
		line-height: <?php 
echo  $plugin_settings->getOption( 'left_icon_font_size' ) ;
?>px;
		font-size: <?php 
echo  $plugin_settings->getOption( 'left_icon_font_size' ) ;
?>px;
		float: left;
	}
	.left-menu-icon-text {
		float: left;
		line-height: <?php 
echo  $plugin_settings->getOption( 'left_icon_font_size' ) ;
?>px;
		color: <?php 
echo  $plugin_settings->getOption( 'header_text_after_icon' ) ;
?>;
	}
	.mobmenu-left-panel .mobmenu-display-name {
		color: <?php 
echo  $plugin_settings->getOption( 'left_panel_text_color' ) ;
?>;
	}
	.right-menu-icon-text {
		float: right;
		line-height: <?php 
echo  $plugin_settings->getOption( 'right_icon_font_size' ) ;
?>px;
		color: <?php 
echo  $plugin_settings->getOption( 'header_text_before_icon' ) ;
?>;
	}
	.mobmenur-container i {
		line-height: <?php 
echo  $plugin_settings->getOption( 'right_icon_font_size' ) ;
?>px;
		font-size: <?php 
echo  $plugin_settings->getOption( 'right_icon_font_size' ) ;
?>px;
		float: right;
	}
	<?php 
echo  $default_elements ;
?> {
		display: none!important;
	}
	
	.mob-standard-logo {
		display: inline-block;
		<?php 
echo  $logo_height ;
?>
	}
	.mob-retina-logo {
		<?php 
echo  $logo_height ;
?>
	}
	.mobmenu-content #mobmenuleft > li > a:hover {
		background-color: <?php 
echo  $plugin_settings->getOption( 'left_panel_hover_bgcolor' ) ;
?>;
	}

	.mobmenu-content #mobmenuright > li > a:hover {
		background-color: <?php 
echo  $plugin_settings->getOption( 'right_panel_hover_bgcolor' ) ;
?>;
	}
	.mobmenu-left-panel .mob-cancel-button {
		color: <?php 
echo  $plugin_settings->getOption( 'left_panel_cancel_button_color' ) ;
?>;
	}
	.mobmenu-right-panel .mob-cancel-button {
		color: <?php 
echo  $plugin_settings->getOption( 'right_panel_cancel_button_color' ) ;
?>;
	}	
	
}

<?php 
$text_after_left_icon_font = $plugin_settings->getOption( 'text_after_left_icon_font' );
$footer_text_font = $plugin_settings->getOption( 'footer_text_font' );
$header_banner_font = $plugin_settings->getOption( 'header_banner_font' );
$header_menu_font = $plugin_settings->getOption( 'header_menu_font' );
$left_menu_font = $plugin_settings->getOption( 'left_menu_font' );
$text_before_right_icon_font = $plugin_settings->getOption( 'text_before_right_icon_font' );
$right_menu_font = $plugin_settings->getOption( 'right_menu_font' );
?>

.mob-menu-logo-holder > .headertext span,.mobmenu input.mob-menu-search-field {
	font-family:<?php 
echo  $header_menu_font['font-family'] ;
?>;
	font-size:<?php 
echo  $header_menu_font['font-size'] ;
?>;
	font-weight:<?php 
echo  $header_menu_font['font-weight'] ;
?>;
	font-style:<?php 
echo  $header_menu_font['font-style'] ;
?>;
	letter-spacing:<?php 
echo  $header_menu_font['letter-spacing'] ;
?>;
	text-transform:<?php 
echo  $header_menu_font['text-transform'] ;
?>;
}

.left-menu-icon-text {
	font-family:<?php 
echo  $text_after_left_icon_font['font-family'] ;
?>;
	font-size:<?php 
echo  $text_after_left_icon_font['font-size'] ;
?>;
	font-weight:<?php 
echo  $text_after_left_icon_font['font-weight'] ;
?>;
	font-style:<?php 
echo  $text_after_left_icon_font['font-style'] ;
?>;
	line-height:<?php 
echo  $text_after_left_icon_font['line-height'] ;
?>;
	letter-spacing:<?php 
echo  $text_after_left_icon_font['letter-spacing'] ;
?>;
	text-transform:<?php 
echo  $text_after_left_icon_font['text-transform'] ;
?>;
}

#mobmenuleft .mob-expand-submenu,#mobmenuleft > .widgettitle,#mobmenuleft li a,#mobmenuleft li a:visited,#mobmenuleft .mobmenu-content h2,#mobmenuleft .mobmenu-content h3,.mobmenu-left-panel .mobmenu-display-name, .mobmenu-content .mobmenu-tabs-header li {
	font-family:<?php 
echo  $left_menu_font['font-family'] ;
?>;
	font-size:<?php 
echo  $left_menu_font['font-size'] ;
?>;
	font-weight:<?php 
echo  $left_menu_font['font-weight'] ;
?>;
	font-style:<?php 
echo  $left_menu_font['font-style'] ;
?>;
	line-height:<?php 
echo  $left_menu_font['line-height'] ;
?>;
	letter-spacing:<?php 
echo  $left_menu_font['letter-spacing'] ;
?>;
	text-transform:<?php 
echo  $left_menu_font['text-transform'] ;
?>;
}

.right-menu-icon-text {
	font-family:<?php 
echo  $text_before_right_icon_font['font-family'] ;
?>;
	font-size:<?php 
echo  $text_before_right_icon_font['font-size'] ;
?>;
	font-weight:<?php 
echo  $text_before_right_icon_font['font-weight'] ;
?>;
	font-style:<?php 
echo  $text_before_right_icon_font['font-style'] ;
?>;
	line-height:<?php 
echo  $text_before_right_icon_font['line-height'] ;
?>;
	letter-spacing:<?php 
echo  $text_before_right_icon_font['letter-spacing'] ;
?>;
	text-transform:<?php 
echo  $text_before_right_icon_font['text-transform'] ;
?>;
}

#mobmenuright li a,#mobmenuright li a:visited,#mobmenuright .mobmenu-content h2,#mobmenuright .mobmenu-content h3,.mobmenu-left-panel .mobmenu-display-name {
	font-family:<?php 
echo  $right_menu_font['font-family'] ;
?>;
	font-size:<?php 
echo  $right_menu_font['font-size'] ;
?>;
	font-weight:<?php 
echo  $right_menu_font['font-weight'] ;
?>;
	font-style:<?php 
echo  $right_menu_font['font-style'] ;
?>;
	line-height:<?php 
echo  $right_menu_font['line-height'] ;
?>;
	letter-spacing:<?php 
echo  $right_menu_font['letter-spacing'] ;
?>;
	text-transform:<?php 
echo  $right_menu_font['text-transform'] ;
?>;
}

