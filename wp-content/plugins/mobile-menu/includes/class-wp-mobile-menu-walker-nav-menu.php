<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class WP_Mobile_Menu_Walker_Nav_Menu extends Walker_Nav_Menu
{
    public  $menu_position ;
    public  $footer_li_class ;
    public function __construct( $myarg, $aditional_footer_li_class )
    {
        global  $mm_fs ;
        $this->menu_position = $myarg;
    }
    
    public function start_el(
        &$output,
        $item,
        $depth = 0,
        $args = array(),
        $id = 0
    )
    {
        global  $mm_fs ;
        $icon_class = '';
        $mobile_icon = '';
        $menu_position = '';
        $indent = ( $depth ? str_repeat( "\t", $depth ) : '' );
        $class_names = '';
        $value = '';
        $classes = ( empty($item->classes) ? array() : (array) $item->classes );
        $classes[] = 'menu-item-' . $item->ID;
        $class_names = join( ' ', apply_filters(
            'nav_menu_css_class',
            array_filter( $classes ),
            $item,
            $args
        ) );
        $class_names = ( $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '' );
        $id = apply_filters(
            'nav_menu_item_id',
            'menu-item-' . $item->ID,
            $item,
            $args
        );
        $id = ( $id ? ' id="' . esc_attr( $id ) . '"' : '' );
        $output .= $indent . '';
        $attributes = ( !empty($item->attr_title) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '' );
        $attributes .= ( !empty($item->target) ? ' target="' . esc_attr( $item->target ) . '"' : '' );
        $attributes .= ( !empty($item->xfn) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '' );
        $attributes .= ( !empty($item->url) ? ' href="' . esc_attr( $item->url ) . '"' : '' );
        $attributes .= ' role="menuitem"';
        $item_output = $args->before;
        
        if ( 'below' === $menu_position ) {
            $item_output .= '<li role="none" ' . $class_names . '><a' . $attributes . ' class="' . $icon_class . '">';
            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $mobile_icon . $args->link_after;
        } else {
            $item_output .= '<li role="none" ' . $class_names . '><a' . $attributes . ' class="' . $icon_class . '">' . $mobile_icon;
            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        }
        
        $item_output .= '</a>';
        $item_output .= $args->after;
        // Execute only in the footer menu.
        if ( $this->footer_li_class !== '' ) {
        }
        $output .= apply_filters(
            'walker_nav_menu_start_el',
            $item_output,
            $item,
            $depth,
            $args
        );
    }
    
    public function end_el(
        &$output,
        $item,
        $depth = 0,
        $args = array()
    )
    {
        // Copy all the end_el code from source, and modify.
        $output .= '</li>';
    }
    
    public function start_lvl( &$output, $depth = 0, $args = array() )
    {
        global  $mm_fs ;
        $indent = str_repeat( "\t", $depth );
        $output .= "\n{$indent}<ul  role='menu' class=\"sub-menu \">\n";
    }

}