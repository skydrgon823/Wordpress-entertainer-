<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly
}

class MobileMenuAdminTab
{
    /**
     * Default settings specific for this container
     *
     * @var array
     */
    private  $defaultSettings = array(
        'name' => '',
        'id'   => '',
        'desc' => '',
    ) ;
    public  $options = array() ;
    public  $settings ;
    public  $owner ;
    function __construct( $settings, $owner )
    {
        $this->owner = $owner;
        $this->settings = array_merge( $this->defaultSettings, $settings );
        if ( empty($this->settings['id']) ) {
            $this->settings['id'] = str_replace( ' ', '-', trim( strtolower( $this->settings['name'] ) ) );
        }
    }
    
    public function isActiveTab()
    {
        return $this->settings['id'] == $this->owner->getActiveTab()->settings['id'];
    }
    
    public function createOption( $settings )
    {
        if ( !apply_filters( 'mm_create_option_continue_mobmenu', true, $settings ) ) {
            return null;
        }
        $obj = MobileMenuOption::factory( $settings, $this );
        $this->options[] = $obj;
        do_action( 'mm_create_option_mobmenu', $obj );
        return $obj;
    }
    
    public function displayTab()
    {
        $url = add_query_arg( array(
            'page' => $this->owner->settings['id'],
            'tab'  => $this->settings['id'],
        ), remove_query_arg( array( 'message', 'mobmenu-action' ) ) );
        $tab_submenus = $this->displayTabSubmenus();
        
        if ( $this->settings['id'] == 'general-options' ) {
            $options_id = '';
        } else {
            $options_id = '-options';
        }
        
        switch ( $this->settings['name'] ) {
            case 'General Options':
                $mm_nav_icon = 'dashicons-admin-generic';
                break;
            case 'WooCommerce':
                $mm_nav_icon = 'dashicons-cart';
                break;
            case 'Left Menu':
                $mm_nav_icon = 'dashicons-align-pull-left';
                break;
            case 'Right Menu':
                $mm_nav_icon = 'dashicons-align-pull-right';
                break;
            case 'Header':
                $mm_nav_icon = 'dashicons-align-pull-left rotate-90-cw';
                break;
            case 'Footer':
                $mm_nav_icon = 'dashicons-align-pull-left rotate-90-ccw';
                break;
            case 'Colors':
                $mm_nav_icon = 'dashicons-admin-customizer';
                break;
            default:
                $mm_nav_icon = '';
        }
        ?>
		<a href="#" data-tab-id="<?php 
        echo  $this->settings['id'] . $options_id ;
        ?>" class="nav-tab"><span class="dashicons <?php 
        echo  $mm_nav_icon ;
        ?>"></span><?php 
        echo  $this->settings['name'] ;
        echo  $tab_submenus ;
        ?></a>
		<?php 
    }
    
    public function displayTabSubmenus()
    {
        global  $mm_fs ;
        $output = '';
        $li_elements = '';
        $general_options_arr = array(
            array(
            'name' => __( 'Getting started', 'mobile-menu' ),
            'url'  => 'general-options',
        ),
            array(
            'name' => __( 'Visibility options', 'mobile-menu' ),
            'url'  => 'general-visibility-options',
        ),
            array(
            'name' => __( 'Font Options', 'mobile-menu' ),
            'url'  => 'font-options',
        ),
            array(
            'name' => __( 'Alerts', 'mobile-menu' ),
            'url'  => 'general-alerts',
        ),
            array(
            'name' => __( 'Advanced options', 'mobile-menu' ),
            'url'  => 'advanced-options',
        ),
            array(
            'name' => __( 'Import and Export', 'mobile-menu' ),
            'url'  => 'advanced-import-export',
        )
        );
        $header_arr = array( array(
            'name' => __( 'Main Options', 'mobile-menu' ),
            'url'  => 'header-options',
        ), array(
            'name' => __( 'Logo', 'mobile-menu' ),
            'url'  => 'logo-options',
        ) );
        $woocommerce_arr = [];
        $right_menu_arr = array( array(
            'name' => __( 'Main Options', 'mobile-menu' ),
            'url'  => 'right-menu-options',
        ), array(
            'name' => __( 'Menu Icon', 'mobile-menu' ),
            'url'  => 'right-menu-icon',
        ), array(
            'name' => __( 'Right Panel', 'mobile-menu' ),
            'url'  => 'right-panel-options',
        ) );
        $colors_arr = array(
            array(
            'name' => __( 'General', 'mobile-menu' ),
            'url'  => 'colors-options',
        ),
            array(
            'name' => __( 'Header', 'mobile-menu' ),
            'url'  => 'header-colors',
        ),
            array(
            'name' => __( 'Footer', 'mobile-menu' ),
            'url'  => 'footer-colors',
        ),
            array(
            'name' => __( 'Left Menu', 'mobile-menu' ),
            'url'  => 'left-menu-colors',
        ),
            array(
            'name' => __( 'Right Menu', 'mobile-menu' ),
            'url'  => 'right-menu-colors',
        )
        );
        $left_menu_arr = array( array(
            'name' => __( 'Main Options', 'mobile-menu' ),
            'url'  => 'left-menu-options',
        ), array(
            'name' => __( 'Menu Icon', 'mobile-menu' ),
            'url'  => 'left-menu-icon',
        ), array(
            'name' => __( 'Left Panel', 'mobile-menu' ),
            'url'  => 'left-panel-options',
        ) );
        // Define the settings submenu.
        $submenu_options = array(
            'general-options' => $general_options_arr,
            'header'          => $header_arr,
            'left-menu'       => $left_menu_arr,
            'right-menu'      => $right_menu_arr,
            'woocommerce'     => $woocommerce_arr,
            'colors'          => $colors_arr,
        );
        // Create the submenu.
        
        if ( isset( $submenu_options[$this->settings['id']] ) ) {
            foreach ( $submenu_options[$this->settings['id']] as $items ) {
                $li_elements .= '<li data-link-id="' . $items['url'] . '">' . $items['name'] . '</li>';
            }
            $output = '<ul>' . $li_elements . '</ul>';
        }
        
        return $output;
    }
    
    public function displayOptions()
    {
        foreach ( $this->options as $option ) {
            $option->display();
        }
    }

}