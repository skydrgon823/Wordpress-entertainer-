<?php

/**
 * WP Mobile Menu options class
 *
 * This will manage the plugin options.
 *
 * @package WP Mobile Menu
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 *
 * @since 1.0
 */
if ( !class_exists( 'WP_Mobile_Menu' ) ) {
    die;
}
/**
 *
 * Class WP_Mobile_Menu_Options.
 *
 * @since 2.0
 */
class WP_Mobile_Menu_Options
{
    /**
     *
     * Class Constructor.
     *
     * @since 2.0
     */
    public function __construct()
    {
        $this->init_options();
    }
    
    /**
     *
     * Initiliaze the Options.
     *
     * @since 2.0
     */
    private function init_options()
    {
        add_action( 'init', array( $this, 'create_plugin_options' ) );
    }
    
    /**
     *
     * Create Plugin options.
     *
     * @since 2.0
     */
    public function create_plugin_options()
    {
        global  $mm_fs ;
        global  $general_tab ;
        global  $message_code ;
        global  $message ;
        $prefix = '';
        $menus = get_terms( 'nav_menu', array(
            'hide_empty' => true,
        ) );
        $menus_options = array();
        $menus_options[''] = __( 'Choose one menu', 'mobile-menu' );
        $icons_positions = array();
        $icon_types = array();
        $plugin_settings = MobileMenuOptions::getInstance( 'mobmenu' );
        foreach ( $menus as $menu ) {
            $menus_options[$menu->name] = $menu->name;
        }
        $icon_types = array(
            'image' => __( 'Image', 'mobile-menu' ),
            'icon'  => __( 'Icon', 'mobile-menu' ),
        );
        $display_type = array(
            'slideout-push' => __( 'Slideout Push Content', 'mobile-menu' ),
            'slideout-over' => __( 'Slideout Over Content', 'mobile-menu' ),
        );
        $default_header_elements = array(
            'left-menu'  => 'Left Menu',
            'logo'       => 'Logo',
            'right-menu' => 'Right Menu',
        );
        $right_menu_elements = array(
            'logo'       => 'Logo',
            'search'     => 'Search',
            'right-menu' => 'Right Menu',
        );
        $left_menu_elements = array(
            'logo'      => 'Logo',
            'search'    => 'Search',
            'left-menu' => 'Left Menu',
        );
        // Create my admin options panel.
        $panel = $plugin_settings->createAdminPanel( array(
            'name'  => 'Mobile Menu Options',
            'title' => __( 'Mobile Menu Options', 'mobile-menu' ),
            'icon'  => 'dashicons-smartphone',
        ) );
        // Only proceed if we are in the plugin page.
        
        if ( !is_admin() || isset( $_GET['page'] ) && 'mobile-menu-options' === sanitize_text_field( $_GET['page'] ) ) {
            // Create General Options panel.
            $general_tab2 = $panel->createTab( array(
                'name' => __( 'General Options', 'mobile-menu' ),
                'id'   => 'general-options',
            ) );
            $general_tab = $panel->createTab( array(
                'name' => __( 'Header', 'mobile-menu' ),
                'id'   => 'header',
            ) );
            $general_tab = $panel->createTab( array(
                'name' => __( 'Footer', 'mobile-menu' ),
                'id'   => 'footer',
            ) );
            $general_tab = $panel->createTab( array(
                'name' => __( 'Left Menu', 'mobile-menu' ),
                'id'   => 'left-menu',
            ) );
            $general_tab = $panel->createTab( array(
                'name' => __( 'Right Menu', 'mobile-menu' ),
                'id'   => 'right-menu',
            ) );
            $general_tab = $panel->createTab( array(
                'name' => __( 'WooCommerce', 'mobile-menu' ),
                'id'   => 'woocommerce',
            ) );
            $general_tab = $panel->createTab( array(
                'name' => __( 'Colors', 'mobile-menu' ),
                'id'   => 'colors',
            ) );
            $general_tab = $general_tab2;
            $version_class = 'mm-free-version';
            ob_start();
            ?>
			<div class="mobile-menu-demos-wrapper">
			<!-- Add text when implement the Tour steps - "or follow our initial tour to learn the basic steps." -->
			<p><span style="float:left;max-width:65%"><?php 
            esc_html_e( 'WP Mobile Menu is ready to help you with your mobile visitor. You can quickly start by importing one of the demos or follow our initial tour to learn the basic steps..', 'mobile-menu' );
            ?></span>
			<a href="#" style="font-size: 22px;text-decoration: auto;border: 2px solid #2271b1;float: right; padding: 8px 15px 8px 15px;border-radius: 4px;"><i class="dashicons-before dashicons-video-alt3"></i> Start Tour</a></p>
			
			<ul class="demos-importer">
				<li>
					<a href="https://demo.wpmobilemenu.com/?utm_source=wprepo-dash&utm_medium=user%20website&utm_campaign=import-demo" target="_blank">
						<img src="<?php 
            echo  plugins_url( 'demo-content/assets/freedemo-mobile-menu.png', __FILE__ ) ;
            ?>">
						<span><?php 
            esc_html_e( 'See Demo Site', 'mobile-menu' );
            ?></span>
					</a>	
					<div>
						<h4><?php 
            esc_html_e( 'Free Demo', 'mobile-menu' );
            ?></h4>
						<button type="submit" class="button button-secondary button-next mobile-menu-import-demo" data-demo-id="free-demo" value="<?php 
            esc_attr_e( 'Import Demo', 'mobile-menu' );
            ?>"><?php 
            esc_html_e( 'Import Demo', 'mobile-menu' );
            ?></button>
						<?php 
            
            if ( isset( $_REQUEST['demo'] ) && 'free-demo' === $_REQUEST['demo'] ) {
                ?>
								<h4 class="<?php 
                echo  $message_code ;
                ?>"><?php 
                _e( $message, 'mobile-menu' );
                ?></h4>
						<?php 
            }
            
            ?>
					</div>
					
				</li>
				<li>
					<a href="https://shopdemo.wpmobilemenu.com/?utm_source=wprepo-dash&utm_medium=user%20website&utm_campaign=demo_importer_option" target="_blank">
						<img src="<?php 
            echo  plugins_url( 'demo-content/assets/shopdemo-mobile-menu.png', __FILE__ ) ;
            ?>">
						<span><?php 
            esc_html_e( 'See Demo Site', 'mobile-menu' );
            ?></span>
					</a>
					<div>
						<h4><?php 
            esc_html_e( 'WooCommerce Shop Demo (Business)', 'mobile-menu' );
            ?></h4>
						<button type="submit" class="button button-secondary button-next mobile-menu-import-demo <?php 
            echo  $version_class ;
            ?>" data-demo-id="shop-demo" value="<?php 
            esc_attr_e( 'Import Demo', 'mobile-menu' );
            ?>"><?php 
            esc_html_e( 'Import Demo', 'mobile-menu' );
            ?></button>
						<?php 
            
            if ( isset( $_REQUEST['demo'] ) && 'shop-demo' === $_REQUEST['demo'] ) {
                ?>
								<h4 class="<?php 
                echo  $message_code ;
                ?>"><?php 
                _e( $message, 'mobile-menu' );
                ?></h4>
						<?php 
            }
            
            ?>
					</div>
				</li>
				<li>
					<a href="https://prodemo.wpmobilemenu.com/?utm_source=wprepo-dash&utm_medium=user%20website&utm_campaign=demo_importer_option" target="_blank">
						<img src="<?php 
            echo  plugins_url( 'demo-content/assets/prodemo-mobile-menu.png', __FILE__ ) ;
            ?>">
						<span><?php 
            esc_html_e( 'See Demo Site', 'mobile-menu' );
            ?></span>
					</a>
					<div>
						<h4><?php 
            esc_html_e( 'Professional Demo', 'mobile-menu' );
            ?></h4>
						<button type="submit" class="button button-secondary button-next mobile-menu-import-demo <?php 
            echo  $version_class ;
            ?>" data-demo-id="professional-demo" value="<?php 
            esc_attr_e( 'Import Demo', 'mobile-menu' );
            ?>"><?php 
            esc_html_e( 'Import Demo', 'mobile-menu' );
            ?></button>
						<?php 
            
            if ( isset( $_REQUEST['demo'] ) && 'professional-demo' === $_REQUEST['demo'] ) {
                ?>
								<h4 class="<?php 
                echo  $message_code ;
                ?>"><?php 
                _e( $message, 'mobile-menu' );
                ?></h4>
						<?php 
            }
            
            ?>
					</div>
				</li>
			</ul>
		</div>
		<?php 
            $demos_content = ob_get_clean();
            $custom_html = '<div class="mm-welcome-box"><h3>Welcome to WP Mobile Menu</h3>' . $demos_content . '</div>';
            $custom_html .= '<h3>' . __( 'Choose the style of your Mobile Menu', 'mobile-menu' ) . '</h3><div class="mm-mobile-header-type">';
            $custom_html .= '<div><h4>' . __( 'Hamburger Menu', 'mobile-menu' ) . '</h4><img src="' . plugins_url( '/assets/mobile_hamburger_menu_option.png', __FILE__ ) . '" class="hamburger-menu"></div>';
            $custom_html .= '<div><h4>' . __( 'Header Menu', 'mobile-menu' ) . '</h4><img src="' . plugins_url( '/assets/mobile_header.png', __FILE__ ) . '"></div></div>';
            // Mobile Header type.
            $general_tab->createOption( array(
                'name'   => 'mobile-header-type',
                'type'   => 'custom',
                'custom' => $custom_html,
                'class'  => 'general-options',
            ) );
            // Enable/Disable Naked Header.
            $general_tab->createOption( array(
                'name'     => __( 'Header Style', 'mobile-menu' ),
                'id'       => 'enabled_naked_header',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => '',
                'enabled'  => __( 'Hamburger Menu', 'mobile-menu' ),
                'disabled' => __( 'Header Menu', 'mobile-menu' ),
                'class'    => 'general-options header-options',
            ) );
            // Enable/Disable Left Header Menu.
            $general_tab->createOption( array(
                'name'     => __( 'Enable Left Menu', 'mobile-menu' ),
                'id'       => 'enable_left_menu',
                'type'     => 'enable',
                'default'  => true,
                'enabled'  => __( 'Yes', 'mobile-menu' ),
                'disabled' => __( 'No', 'mobile-menu' ),
                'class'    => 'general-options left-menu-options',
            ) );
            // Left Menu.
            $general_tab->createOption( array(
                'name'    => __( 'Left Menu', 'mobile-menu' ),
                'id'      => 'left_menu',
                'type'    => 'select',
                'desc'    => __( 'Select the menu that will open in the left side.', 'mobile-menu' ),
                'options' => $menus_options,
                'default' => $plugin_settings->getOption( 'left_menu' ),
                'class'   => 'general-options left-menu-options',
            ) );
            // Enable/Disable Right Header Menu.
            $general_tab->createOption( array(
                'name'     => __( 'Enable Right Menu', 'mobile-menu' ),
                'id'       => 'enable_right_menu',
                'type'     => 'enable',
                'default'  => false,
                'enabled'  => __( 'Yes', 'mobile-menu' ),
                'disabled' => __( 'No', 'mobile-menu' ),
                'class'    => 'general-options right-menu-options',
            ) );
            // Right Menu.
            $general_tab->createOption( array(
                'name'    => __( 'Right Menu', 'mobile-menu' ),
                'id'      => 'right_menu',
                'type'    => 'select',
                'desc'    => __( 'Select the menu that will open in the right side.', 'mobile-menu' ),
                'options' => $menus_options,
                'default' => $plugin_settings->getOption( 'right_menu' ),
                'class'   => 'general-options right-menu-options',
            ) );
            // Menu Display Type.
            $general_tab->createOption( array(
                'name'    => __( 'Menu Display Type', 'mobile-menu' ),
                'id'      => 'menu_display_type',
                'type'    => 'select',
                'desc'    => __( 'Choose the display type for the mobile menu.', 'mobile-menu' ),
                'options' => $display_type,
                'default' => 'slideout-over',
                'class'   => 'general-options',
            ) );
            $this->create_footer_options_upsell( $panel, $plugin_settings );
            // Create Woocommerce options upsell.
            $this->create_woocommerce_options_upsell( $panel, $plugin_settings );
            // Enable/Disable only in Mobile Devices.
            $general_tab->createOption( array(
                'name'     => __( 'Enable only in Mobile devices', 'mobile-menu' ),
                'id'       => 'only_mobile_devices',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => __( 'Enable only in Mobiles devices. This will disable the Mobile Menu Visibilty option above (using resolution width trigger).', 'mobile-menu' ),
                'enabled'  => __( 'On', 'mobile-menu' ),
                'disabled' => __( 'Off', 'mobile-menu' ),
                'class'    => 'general-visibility-options',
            ) );
            // Width trigger.
            $general_tab->createOption( array(
                'name'    => __( 'Mobile Menu Visibility(Width Trigger)', 'mobile-menu' ),
                'id'      => 'width_trigger',
                'type'    => 'number',
                'desc'    => __( 'The Mobile menu will appear at this window size. Place it at 5000 to be always visible. ', 'mobile-menu' ),
                'default' => '1024',
                'max'     => '5000',
                'min'     => '479',
                'unit'    => 'px',
                'class'   => 'general-visibility-options',
            ) );
            // Hide Html Elements.
            $general_tab->createOption( array(
                'name'    => __( 'Hide Elements', 'mobile-menu' ),
                'id'      => 'hide_elements',
                'type'    => 'text',
                'default' => '',
                'desc'    => __( 'Use the Find element button and click in the elements you want to hide. When you are done hit the Save Changes button.<br>Example: .menu , #nav</p>', 'mobile-menu' ),
                'class'   => 'general-visibility-options',
            ) );
            /*
            			$general_tab->createOption( array(
            				'type' => 'note',
            				'desc' => __( 'The Width trigger field is very important because it determines the width that will show the Mobile Menu. If you want it always visible set it to 5000px', 'mobile-menu' ),
            				'class'   => 'general-visibility-options',
            			) );*/
            // Enable/Disable Testing Mode.
            $general_tab->createOption( array(
                'name'     => __( 'Testing Mode (only visible for admins).', 'mobile-menu' ),
                'id'       => 'only_testing_mode',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => __( 'Enable only for admin users. This will disable the Mobile Menu for all the visitors of your site except the administrator users.', 'mobile-menu' ),
                'enabled'  => __( 'On', 'mobile-menu' ),
                'disabled' => __( 'Off', 'mobile-menu' ),
                'class'    => 'general-visibility-options',
            ) );
            $general_tab->createOption( array(
                'type'  => 'note',
                'desc'  => __( 'If you somehow couldn\'t find the necessary elements using the visual tool to pick elements just create a new ticket in our <a href="https://www.wpmobilemenu.com/support-contact/?utm_source=wprepo-dash&utm_medium=user%20website&utm_campaign=hide_original_menu_help" target="_blank">support page</a> with your site url and a screenshot of the element you want to hide. We reply fast.', 'mobile-menu' ),
                'class' => 'general-visibility-options',
            ) );
            $general_tab->createOption( array(
                'name'    => __( 'Hide elements by default', 'mobile-menu' ),
                'id'      => 'default_hided_elements',
                'type'    => 'multicheck',
                'desc'    => __( 'Check the desired elements', 'mobile-menu' ),
                'options' => array(
                '1'  => '.nav',
                '2'  => '.main-navigation',
                '3'  => '.genesis-nav-menu',
                '4'  => '#main-header',
                '5'  => '#et-top-navigation',
                '6'  => '.site-header',
                '7'  => '.site-branding',
                '8'  => '.ast-mobile-menu-buttons',
                '9'  => '.storefront-handheld-footer-bar',
                '10' => '.elementor-menu-toggle',
                '11' => '#site-header-inner',
                '12' => '#sq-masthead',
                '13' => '.menu-toggle',
                '14' => '.fusion-header',
                '15' => '#site-header',
                '16' => '.elementor-widget-nav-menu',
                '17' => '.fl-nav',
            ),
                'default' => array(
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
                '10',
                '11',
                '12',
                '13',
                '14',
                '15',
                '16',
                '17'
            ),
                'class'   => 'general-visibility-options hide',
            ) );
            // Automatically Close Sub Menus.
            $general_tab->createOption( array(
                'name'     => __( 'Automatically Close Submenus', 'mobile-menu' ),
                'id'       => 'autoclose_submenus',
                'type'     => 'enable',
                'desc'     => __( 'When you open a submenu it automatically closes the other submenus that are open.', 'mobile-menu' ),
                'default'  => false,
                'enabled'  => __( 'On', 'mobile-menu' ),
                'disabled' => __( 'Off', 'mobile-menu' ),
                'class'    => 'left-menu-options right-menu-options',
            ) );
            // Menu Border Style.
            $general_tab->createOption( array(
                'name'    => __( 'Menu Items Border Size', 'mobile-menu' ),
                'id'      => 'menu_items_border_size',
                'type'    => 'number',
                'default' => '0',
                'desc'    => __( 'Choose the size of the menu items border.<a href="/wp-admin/admin.php?page=mobile-menu-options&tab=colors" target="_blank">Click here</a> to adjust the color.', 'mobile-menu' ),
                'max'     => '5',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'left-menu-options right-menu-options',
            ) );
            // Custom css.
            $general_tab->createOption( array(
                'name'  => __( 'Custom CSS', 'mobile-menu' ),
                'id'    => 'custom_css',
                'type'  => 'code',
                'desc'  => __( 'Put your custom CSS rules here', 'mobile-menu' ),
                'lang'  => 'css',
                'class' => 'advanced-options',
            ) );
            // Custom js.
            $general_tab->createOption( array(
                'name'  => __( 'Custom JS', 'mobile-menu' ),
                'id'    => 'custom_js',
                'type'  => 'code',
                'desc'  => __( 'Put your custom JS rules here', 'mobile-menu' ),
                'lang'  => 'javascript',
                'class' => 'advanced-options',
            ) );
            // Close Menu Icon Font.
            $general_tab->createOption( array(
                'name'    => __( 'Close Icon', 'mobile-menu' ),
                'id'      => 'close_icon_font',
                'type'    => 'text',
                'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mobile-menu' ),
                'default' => 'cancel-1',
                'class'   => 'advanced-options',
            ) );
            // Close Menu Icon Font Size.
            $general_tab->createOption( array(
                'name'    => __( 'Close Icon Font Size', 'mobile-menu' ),
                'id'      => 'close_icon_font_size',
                'type'    => 'number',
                'desc'    => __( 'Enter the Close Icon Font Size', 'mobile-menu' ),
                'default' => '30',
                'max'     => '100',
                'min'     => '5',
                'unit'    => 'px',
                'class'   => 'advanced-options',
            ) );
            // Submenu Open Icon Font.
            $general_tab->createOption( array(
                'name'    => __( 'Submenu Open Icon', 'mobile-menu' ),
                'id'      => 'submenu_open_icon_font',
                'type'    => 'text',
                'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mobile-menu' ),
                'default' => 'down-open',
                'class'   => 'advanced-options',
            ) );
            // Submenu Close Icon Font.
            $general_tab->createOption( array(
                'name'    => __( 'Submenu Close Icon', 'mobile-menu' ),
                'id'      => 'submenu_close_icon_font',
                'type'    => 'text',
                'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mobile-menu' ),
                'default' => 'up-open',
                'class'   => 'advanced-options',
            ) );
            // Submenu Icon Font Size.
            $general_tab->createOption( array(
                'name'    => __( 'Submenu Icon Font Size', 'mobile-menu' ),
                'id'      => 'submenu_icon_font_size',
                'type'    => 'number',
                'desc'    => __( 'Enter the Submenu Icon Font Size', 'mobile-menu' ),
                'default' => '25',
                'max'     => '100',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'advanced-options',
            ) );
            // Automatically Close Sub Menus.
            $general_tab->createOption( array(
                'name'     => __( 'Cache Dynamic CSS', 'mobile-menu' ),
                'id'       => 'cache_dynamic_css',
                'type'     => 'enable',
                'desc'     => __( 'This will able to cache the dynamic CSS. If enable it you might not see the changes you unless you purge the cache of your cache plugin.', 'mobile-menu' ),
                'default'  => false,
                'enabled'  => __( 'On', 'mobile-menu' ),
                'disabled' => __( 'Off', 'mobile-menu' ),
                'class'    => 'advanced-options',
            ) );
            // Sticky Html Elements.
            $general_tab->createOption( array(
                'name'    => __( 'Sticky Html Elements', 'mobile-menu' ),
                'id'      => 'sticky_elements',
                'type'    => 'text',
                'default' => '',
                'desc'    => __( '<p>If you are having issues with sticky elements that dont assume a sticky behaviour, enter the ids or class name that identify that element.</p>', 'mobile-menu' ),
                'class'   => 'advanced-options',
            ) );
            // Export settings.
            $general_tab->createOption( array(
                'name'   => __( 'Export Settings', 'mobile-menu' ),
                'type'   => 'custom',
                'custom' => '<button class="button button-secondary export-mobile-menu-settings">' . __( 'Export', 'mobile-menu' ) . '</button>',
                'class'  => 'advanced-import-export',
            ) );
            // Import settings.
            $general_tab->createOption( array(
                'name'   => __( 'Import Settings', 'mobile-menu' ),
                'type'   => 'custom',
                'custom' => '<button class="button button-secondary import-mobile-menu-settings">' . __( 'Import', 'mobile-menu' ) . '</button>',
                'class'  => 'advanced-import-export',
            ) );
            // Enable/Disable Sticky Header.
            $general_tab->createOption( array(
                'name'     => __( 'Sticky Header', 'mobile-menu' ),
                'id'       => 'enabled_sticky_header',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Choose if you want to have the Header Fixed or scrolling with the content.', 'mobile-menu' ),
                'enabled'  => __( 'Yes', 'mobile-menu' ),
                'disabled' => __( 'No', 'mobile-menu' ),
                'class'    => 'header-options',
            ) );
            // Enable/Disable Logo Url.
            $general_tab->createOption( array(
                'name'     => __( 'Disable Logo/Text', 'mobile-menu' ),
                'id'       => 'disabled_logo_text',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => __( 'Choose if you want to disable the logo/text so it will only display the menu icons in the header.', 'mobile-menu' ),
                'enabled'  => __( 'Yes', 'mobile-menu' ),
                'disabled' => __( 'No', 'mobile-menu' ),
                'class'    => 'header-options',
            ) );
            // Header Shadow.
            $general_tab->createOption( array(
                'name'     => __( 'Header Shadow.', 'mobile-menu' ),
                'id'       => 'header_shadow',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => __( 'Choose if you want to enable the header shadow at the bottom of the header.', 'mobile-menu' ),
                'enabled'  => __( 'Yes', 'mobile-menu' ),
                'disabled' => __( 'No', 'mobile-menu' ),
                'class'    => 'header-options',
            ) );
            // Header Height.
            $general_tab->createOption( array(
                'name'    => __( 'Header Height', 'mobile-menu' ),
                'id'      => 'header_height',
                'type'    => 'number',
                'desc'    => __( 'Enter the height of the header', 'mobile-menu' ),
                'default' => '50',
                'max'     => '500',
                'min'     => '20',
                'unit'    => 'px',
                'class'   => 'header-options',
            ) );
            // Header Text.
            $general_tab->createOption( array(
                'name'    => __( 'Header Text', 'mobile-menu' ),
                'id'      => 'header_text',
                'type'    => 'text',
                'desc'    => __( 'Enter the desired text for the Mobile Header. If not specified it will use the site title.', 'mobile-menu' ),
                'default' => '',
                'class'   => 'header-options',
            ) );
            // Header Text Font Size.
            $general_tab->createOption( array(
                'name'    => __( 'Header Text Font Size', 'mobile-menu' ),
                'id'      => 'header_font_size',
                'type'    => 'number',
                'desc'    => __( 'Enter the header text font size', 'mobile-menu' ),
                'default' => '20',
                'max'     => '100',
                'min'     => '5',
                'unit'    => 'px',
                'class'   => 'header-options',
            ) );
            // Header Logo/Text Alignment.
            $general_tab->createOption( array(
                'name'    => 'Header Logo/Text Alignment',
                'id'      => 'header_text_align',
                'type'    => 'select',
                'desc'    => 'Chose the header Logo/Text alignment.',
                'options' => array(
                'left'   => __( 'Left', 'mobile-menu' ),
                'center' => __( 'Center', 'mobile-menu' ),
                'right'  => __( 'Right', 'mobile-menu' ),
            ),
                'default' => 'center',
                'class'   => 'header-options',
            ) );
            // Header Logo/Text Left Margin.
            $general_tab->createOption( array(
                'name'    => __( 'Header Logo/Text Left Margin', 'mobile-menu' ),
                'id'      => 'header_text_left_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the header Logo/Text left margin (only used whit Header Left Alignment)', 'mobile-menu' ),
                'default' => '20',
                'max'     => '200',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'header-options',
            ) );
            // Header Logo/Text Right Margin.
            $general_tab->createOption( array(
                'name'    => __( 'Header Logo/Text Right Margin', 'mobile-menu' ),
                'id'      => 'header_text_right_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the header Logo/Text right margin (only used whit Header Right Alignment)', 'mobile-menu' ),
                'default' => '20',
                'max'     => '200',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'header-options',
            ) );
            $header_branding = array(
                'logo' => __( 'Image', 'mobile-menu' ),
                'text' => __( 'Text', 'mobile-menu' ),
            );
            
            if ( $plugin_settings->getOption( 'enabled_logo' ) ) {
                $default_header_branding = 'logo';
            } else {
                $default_header_branding = 'text';
            }
            
            // Use the page title in the Header or Header Banner(global Option).
            $general_tab->createOption( array(
                'name'    => __( 'Site Logo', 'mobile-menu' ),
                'id'      => 'header_branding',
                'type'    => 'select',
                'desc'    => __( 'Chose the Header Branding ( Logo/Text ).', 'mobile-menu' ),
                'options' => $header_branding,
                'default' => $default_header_branding,
                'class'   => 'logo-options',
            ) );
            // Site Logo Image.
            $general_tab->createOption( array(
                'name'    => __( 'Logo Image', 'mobile-menu' ),
                'id'      => 'logo_img',
                'type'    => 'upload',
                'desc'    => __( 'Upload your logo image', 'mobile-menu' ),
                'default' => '',
                'class'   => 'logo-options',
            ) );
            // Site Logo Retina Image.
            $general_tab->createOption( array(
                'name'    => __( 'Logo Image for Retina devices', 'mobile-menu' ),
                'id'      => 'logo_img_retina',
                'type'    => 'upload',
                'desc'    => __( 'Upload your logo image for retina devices', 'mobile-menu' ),
                'default' => '',
                'class'   => 'logo-options',
            ) );
            // Header Height.
            $general_tab->createOption( array(
                'name'    => __( 'Logo Image Height', 'mobile-menu' ),
                'id'      => 'logo_height',
                'type'    => 'number',
                'desc'    => __( 'Enter the height of the logo', 'mobile-menu' ),
                'default' => '',
                'max'     => '500',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'logo-options',
            ) );
            // Enable/Disable Logo Url.
            $general_tab->createOption( array(
                'name'     => __( 'Disable Logo URL ', 'mobile-menu' ),
                'id'       => 'disabled_logo_url',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => __( 'Choose if you want to disable the logo url to avoid being redirect to the homepage or alternative home url when touching the header logo.', 'mobile-menu' ),
                'enabled'  => __( 'Yes', 'mobile-menu' ),
                'disabled' => __( 'No', 'mobile-menu' ),
                'class'    => 'logo-options',
            ) );
            // Alternative Site URL.
            $general_tab->createOption( array(
                'name'    => __( 'Alternative Logo URL', 'mobile-menu' ),
                'id'      => 'logo_url',
                'type'    => 'text',
                'desc'    => __( 'Enter you alternative logo URL. If you leave it blank it will use the Site URL.', 'mobile-menu' ),
                'default' => '',
                'class'   => 'logo-options',
            ) );
            // Logo/text Top Margin.
            $general_tab->createOption( array(
                'name'    => __( 'Logo/Text Top Margin', 'mobile-menu' ),
                'id'      => 'logo_top_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the logo/text top margin', 'mobile-menu' ),
                'default' => '0',
                'max'     => '450',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'logo-options',
            ) );
            $def_value = $plugin_settings->getOption( 'header_font_size' );
            
            if ( $def_value > 0 ) {
                $def_value .= 'px';
            } else {
                $def_value = '';
            }
            
            $general_tab->createOption( array(
                'name'                => __( 'Header Menu Font', 'mobile-menu' ),
                'id'                  => 'header_menu_font',
                'type'                => 'font',
                'desc'                => __( 'Select a style', 'mobile-menu' ),
                'show_font_weight'    => true,
                'show_font_style'     => true,
                'show_letter_spacing' => true,
                'show_text_transform' => true,
                'show_font_variant'   => false,
                'show_text_shadow'    => false,
                'show_color'          => false,
                'show_line_height'    => false,
                'default'             => array(
                'font-family' => 'Dosis',
                'font-size'   => $def_value,
            ),
                'class'               => 'font-options',
            ) );
            // Click Menu Parent link to open Sub menu.
            $general_tab->createOption( array(
                'name'     => __( 'Parent Link open submenu', 'mobile-menu' ),
                'id'       => 'left_menu_parent_link_submenu',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => __( 'Choose if you want to open the submenu by click in the Parent Menu item.', 'mobile-menu' ),
                'enabled'  => __( 'Yes', 'mobile-menu' ),
                'disabled' => __( 'No', 'mobile-menu' ),
                'class'    => 'left-menu-options',
            ) );
            
            if ( true === $plugin_settings->getOption( 'left_menu_icon_opt' ) ) {
                $icon_type = 'image';
            } else {
                $icon_type = 'icon';
            }
            
            // Icon Image/text Option.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Type', 'mobile-menu' ),
                'id'      => 'left_menu_icon_new',
                'type'    => 'select',
                'default' => $icon_type,
                'desc'    => __( 'Choose if you want to display an image, icon or an animated icon.', 'mobile-menu' ),
                'options' => $icon_types,
                'class'   => 'left-menu-icon',
            ) );
            // Left Menu Icon Font.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Font', 'mobile-menu' ),
                'id'      => 'left_menu_icon_font',
                'type'    => 'text',
                'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mobile-menu' ),
                'default' => 'menu',
                'class'   => 'left-menu-icon',
            ) );
            // Left Menu Icon Font Size.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Font Size', 'mobile-menu' ),
                'id'      => 'left_icon_font_size',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Icon Font Size', 'mobile-menu' ),
                'default' => '30',
                'max'     => '100',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'left-menu-icon',
            ) );
            // Left Menu Icon.
            $general_tab->createOption( array(
                'name'        => __( 'Icon Image', 'mobile-menu' ),
                'id'          => 'left_menu_icon',
                'type'        => 'upload',
                'placeholder' => 'Click here to select the icon',
                'desc'        => __( 'Upload your left menu icon image', 'mobile-menu' ),
                'default'     => '',
                'class'       => 'left-menu-icon',
            ) );
            // Icon Action Option.
            $general_tab->createOption( array(
                'name'     => __( 'Icon Action', 'mobile-menu' ),
                'id'       => 'left_menu_icon_action',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Open the Left Menu Panel or open a Link url.', 'mobile-menu' ),
                'enabled'  => __( 'Open Menu', 'mobile-menu' ),
                'disabled' => __( 'Open Link Url', 'mobile-menu' ),
                'class'    => 'left-menu-icon',
            ) );
            // Icon URL.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Link URL', 'mobile-menu' ),
                'id'      => 'left_icon_url',
                'type'    => 'text',
                'desc'    => __( 'Enter the Icon Link Url.', 'mobile-menu' ),
                'default' => '',
                'class'   => 'left-menu-icon',
            ) );
            // Icon URL Target.
            $general_tab->createOption( array(
                'name'     => __( 'Icon Link Url Target', 'mobile-menu' ),
                'id'       => 'left_icon_url_target',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Choose it the link will open in the same window or in the new window.', 'mobile-menu' ),
                'enabled'  => 'Self',
                'disabled' => 'Blank',
                'class'    => 'left-menu-icon',
            ) );
            // Text After Left Icon.
            $general_tab->createOption( array(
                'name'    => __( 'Text After Icon', 'mobile-menu' ),
                'id'      => 'left_menu_text',
                'type'    => 'text',
                'desc'    => __( 'Enter the text that will appear after the Icon.', 'mobile-menu' ),
                'default' => '',
                'class'   => 'left-menu-icon',
            ) );
            // Text After Left Icon Font Options.
            $general_tab->createOption( array(
                'name'                => __( 'Text After Icon Font', 'mobile-menu' ),
                'id'                  => 'text_after_left_icon_font',
                'type'                => 'font',
                'desc'                => __( 'Select a style', 'mobile-menu' ),
                'show_font_weight'    => true,
                'show_font_style'     => true,
                'show_line_height'    => true,
                'show_letter_spacing' => true,
                'show_text_transform' => true,
                'show_font_variant'   => false,
                'show_text_shadow'    => false,
                'show_color'          => true,
                'default'             => array(
                'line-height' => '1.5em',
                'font-family' => 'Dosis',
            ),
                'class'               => 'font-options',
            ) );
            // Left Menu Icon Top Margin.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Top Margin', 'mobile-menu' ),
                'id'      => 'left_icon_top_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Icon Top Margin', 'mobile-menu' ),
                'default' => '10',
                'max'     => '450',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'left-menu-icon',
            ) );
            // Left Menu Icon Left Margin.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Left Margin', 'mobile-menu' ),
                'id'      => 'left_icon_left_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Icon Left Margin', 'mobile-menu' ),
                'default' => '5',
                'max'     => '450',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'left-menu-icon',
            ) );
            // Left Menu Background Image.
            $general_tab->createOption( array(
                'name'  => __( 'Panel Background Image', 'mobile-menu' ),
                'id'    => 'left_menu_bg_image',
                'type'  => 'upload',
                'desc'  => __( 'Upload your left menu background image(this will override the Background color option)', 'mobile-menu' ),
                'class' => 'left-panel-options',
            ) );
            // Left Menu Background Image Opacity.
            $general_tab->createOption( array(
                'name'    => __( 'Panel Background Image Opacity', 'mobile-menu' ),
                'id'      => 'left_menu_bg_opacity',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Background image opacity', 'mobile-menu' ),
                'default' => '100',
                'max'     => '100',
                'min'     => '10',
                'step'    => '10',
                'unit'    => '%',
                'class'   => 'left-panel-options',
            ) );
            // Left Menu Background Image Size.
            $general_tab->createOption( array(
                'name'    => __( 'Panel Background Image Size', 'mobile-menu' ),
                'id'      => 'left_menu_bg_image_size',
                'type'    => 'upload',
                'type'    => 'select',
                'desc'    => __( 'Select the Background image size type. <a href="https://www.w3schools.com/cssref/css3_pr_background-size.asp" target="_blank">See the CSS Documentation</a>', 'mobile-menu' ),
                'options' => array(
                'auto'    => __( 'Auto', 'mobile-menu' ),
                'contain' => __( 'Contain', 'mobile-menu' ),
                'cover'   => __( 'Cover', 'mobile-menu' ),
                'inherit' => __( 'Inherit', 'mobile-menu' ),
                'initial' => __( 'Initial', 'mobile-menu' ),
                'unset'   => __( 'Unset', 'mobile-menu' ),
            ),
                'default' => 'cover',
                'class'   => 'left-panel-options',
            ) );
            // Left Menu Gradient css.
            $general_tab->createOption( array(
                'name'    => __( 'Panel Background Gradient Css', 'mobile-menu' ),
                'id'      => 'left_menu_bg_gradient',
                'type'    => 'text',
                'desc'    => __( '<a href="https://webgradients.com/" target="_blank">Click here</a> to get your desired Gradient, just press the copy button and paste in this field.', 'mobile-menu' ),
                'default' => '',
                'class'   => 'left-panel-options',
            ) );
            // Left Menu Panel Width Units.
            $general_tab->createOption( array(
                'name'     => __( 'Menu Panel Width Units', 'mobile-menu' ),
                'id'       => 'left_menu_width_units',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Choose the width units.', 'mobile-menu' ),
                'enabled'  => 'Pixels',
                'disabled' => __( 'Percentage', 'mobile-menu' ),
                'class'    => 'left-panel-options',
            ) );
            // Left Menu Panel Width.
            $general_tab->createOption( array(
                'name'    => __( 'Menu Panel Width(Pixels)', 'mobile-menu' ),
                'id'      => 'left_menu_width',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Menu Panel Width', 'mobile-menu' ),
                'default' => '270',
                'max'     => '1000',
                'min'     => '50',
                'unit'    => 'px',
                'class'   => 'left-panel-options',
            ) );
            // Left Menu Panel Width.
            $general_tab->createOption( array(
                'name'    => __( 'Menu Panel Width(Percentage)', 'mobile-menu' ),
                'id'      => 'left_menu_width_percentage',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Menu Panel Width', 'mobile-menu' ),
                'default' => '70',
                'max'     => '90',
                'min'     => '0',
                'unit'    => '%',
                'class'   => 'left-panel-options',
            ) );
            // Left Menu Content Padding.
            $general_tab->createOption( array(
                'name'    => __( 'Left Menu Content Padding', 'mobile-menu' ),
                'id'      => 'left_menu_content_padding',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Menu Content Padding', 'mobile-menu' ),
                'default' => '10',
                'max'     => '30',
                'min'     => '0',
                'step'    => '1',
                'unit'    => '%',
                'class'   => 'left-panel-options',
            ) );
            // Left Menu Font.
            $general_tab->createOption( array(
                'name'                => __( 'Left Menu Font', 'mobile-menu' ),
                'id'                  => 'left_menu_font',
                'type'                => 'font',
                'desc'                => __( 'Select a style', 'mobile-menu' ),
                'show_font_weight'    => true,
                'show_font_style'     => true,
                'show_line_height'    => true,
                'show_letter_spacing' => true,
                'show_text_transform' => true,
                'show_font_variant'   => false,
                'show_text_shadow'    => false,
                'show_color'          => false,
                'default'             => array(
                'line-height' => '1.5em',
                'font-family' => 'Dosis',
            ),
                'class'               => 'font-options',
            ) );
            // Click Menu Parent link to open Sub menu.
            $general_tab->createOption( array(
                'name'     => __( 'Parent Link open submenu', 'mobile-menu' ),
                'id'       => 'right_menu_parent_link_submenu',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => __( 'Choose if you want to open the submenu by click in the Parent Menu item.', 'mobile-menu' ),
                'enabled'  => __( 'Yes', 'mobile-menu' ),
                'disabled' => __( 'No', 'mobile-menu' ),
                'class'    => 'right-menu-options',
            ) );
            
            if ( true === $plugin_settings->getOption( 'right_menu_icon_opt' ) ) {
                $icon_type = 'image';
            } else {
                $icon_type = 'icon';
            }
            
            // Icon Image/text Option.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Type', 'mobile-menu' ),
                'id'      => 'right_menu_icon_new',
                'type'    => 'select',
                'default' => $icon_type,
                'desc'    => __( 'Choose if you want to display an image, icon or an animated icon.', 'mobile-menu' ),
                'options' => $icon_types,
                'class'   => 'right-menu-icon',
            ) );
            // Right Menu Icon Font.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Font', 'mobile-menu' ),
                'id'      => 'right_menu_icon_font',
                'type'    => 'text',
                'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mobile-menu' ),
                'default' => 'menu',
                'class'   => 'right-menu-icon',
            ) );
            // Right Menu Icon Font Size.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Font Size', 'mobile-menu' ),
                'id'      => 'right_icon_font_size',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Icon Font Size', 'mobile-menu' ),
                'default' => '30',
                'max'     => '100',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'right-menu-icon',
            ) );
            // Right Menu Icon.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Image', 'mobile-menu' ),
                'id'      => 'right_menu_icon',
                'type'    => 'upload',
                'desc'    => __( 'Upload your right menu icon image', 'mobile-menu' ),
                'default' => '',
                'class'   => 'right-menu-icon',
            ) );
            // Icon Action Option.
            $general_tab->createOption( array(
                'name'     => __( 'Icon Action', 'mobile-menu' ),
                'id'       => 'right_menu_icon_action',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Open the Right Menu Panel or open a Link url.', 'mobile-menu' ),
                'enabled'  => __( 'Open Menu', 'mobile-menu' ),
                'disabled' => __( 'Open Link Url', 'mobile-menu' ),
                'class'    => 'right-menu-icon',
            ) );
            // Icon URL.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Link URL', 'mobile-menu' ),
                'id'      => 'right_icon_url',
                'type'    => 'text',
                'desc'    => __( 'Enter the Icon Link Url.', 'mobile-menu' ),
                'default' => '',
                'class'   => 'right-menu-icon',
            ) );
            // Icon URL Target.
            $general_tab->createOption( array(
                'name'     => __( 'Icon Link Url Target', 'mobile-menu' ),
                'id'       => 'right_icon_url_target',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Choose it the link will open in the same window or in the new window.', 'mobile-menu' ),
                'enabled'  => 'Self',
                'disabled' => 'Blank',
                'class'    => 'right-menu-icon',
            ) );
            // Text Before Right Icon.
            $general_tab->createOption( array(
                'name'    => __( 'Text Before Icon', 'mobile-menu' ),
                'id'      => 'right_menu_text',
                'type'    => 'text',
                'desc'    => __( 'Enter the text that will appear before the Icon.', 'mobile-menu' ),
                'default' => '',
                'class'   => 'right-menu-icon',
            ) );
            // Text Before Right Icon Font Options.
            $general_tab->createOption( array(
                'name'                => __( 'Text Before Icon Font', 'mobile-menu' ),
                'id'                  => 'text_before_right_icon_font',
                'type'                => 'font',
                'desc'                => __( 'Select a style', 'mobile-menu' ),
                'show_font_weight'    => true,
                'show_font_size'      => true,
                'show_font_style'     => true,
                'show_line_height'    => true,
                'show_letter_spacing' => true,
                'show_text_transform' => true,
                'show_font_variant'   => false,
                'show_text_shadow'    => false,
                'show_color'          => false,
                'default'             => array(
                'line-height' => '1.5em',
                'font-family' => 'Dosis',
            ),
                'class'               => 'font-options',
            ) );
            // Right Menu Icon Top Margin.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Top Margin', 'mobile-menu' ),
                'id'      => 'right_icon_top_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Icon Top Margin', 'mobile-menu' ),
                'default' => '10',
                'max'     => '450',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'right-menu-icon',
            ) );
            // Right Menu Icon Right Margin.
            $general_tab->createOption( array(
                'name'    => __( 'Icon Right Margin', 'mobile-menu' ),
                'id'      => 'right_icon_right_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Icon Right Margin', 'mobile-menu' ),
                'default' => '5',
                'max'     => '450',
                'min'     => '0',
                'unit'    => 'px',
                'class'   => 'right-menu-icon',
            ) );
            // Right Menu Background Image.
            $general_tab->createOption( array(
                'name'  => __( 'Panel Background Image', 'mobile-menu' ),
                'id'    => 'right_menu_bg_image',
                'type'  => 'upload',
                'desc'  => __( 'upload your right menu background image(this will override the Background color option)', 'mobile-menu' ),
                'class' => 'right-panel-options',
            ) );
            // Right Menu Background Image Opacity.
            $general_tab->createOption( array(
                'name'    => __( 'Panel Background Image Opacity', 'mobile-menu' ),
                'id'      => 'right_menu_bg_opacity',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Background image opacity', 'mobile-menu' ),
                'default' => '100',
                'max'     => '100',
                'min'     => '10',
                'step'    => '10',
                'unit'    => '%',
                'class'   => 'right-panel-options',
            ) );
            // Left Menu Background Image Size.
            $general_tab->createOption( array(
                'name'    => __( 'Panel Background Image Size', 'mobile-menu' ),
                'id'      => 'right_menu_bg_image_size',
                'type'    => 'select',
                'desc'    => __( 'Select the Background image size type. <a href="https://www.w3schools.com/cssref/css3_pr_background-size.asp" target="_blank">See the CSS Documentation</a>', 'mobile-menu' ),
                'options' => array(
                'auto'    => __( 'Auto', 'mobile-menu' ),
                'contain' => __( 'Contain', 'mobile-menu' ),
                'cover'   => __( 'Cover', 'mobile-menu' ),
                'inherit' => __( 'Inherit', 'mobile-menu' ),
                'initial' => __( 'Initial', 'mobile-menu' ),
                'unset'   => __( 'Unset', 'mobile-menu' ),
            ),
                'default' => 'cover',
                'class'   => 'right-panel-options',
            ) );
            // Right Menu Gradient css.
            $general_tab->createOption( array(
                'name'    => __( 'Panel Background Gradient Css', 'mobile-menu' ),
                'id'      => 'right_menu_bg_gradient',
                'type'    => 'text',
                'desc'    => __( '<a href="https://webgradients.com/" target="_blank">Click here</a> to get your desired Gradient, just press the copy button and paste in this field.', 'mobile-menu' ),
                'default' => '',
                'class'   => 'right-panel-options',
            ) );
            // Right Menu Panel Width Units.
            $general_tab->createOption( array(
                'name'     => __( 'Menu Panel Width Units', 'mobile-menu' ),
                'id'       => 'right_menu_width_units',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Choose the width units.', 'mobile-menu' ),
                'enabled'  => __( 'Pixels', 'mobile-menu' ),
                'disabled' => __( 'Percentage', 'mobile-menu' ),
                'class'    => 'right-panel-options',
            ) );
            // Right Menu Panel Width.
            $general_tab->createOption( array(
                'name'    => __( 'Menu Panel Width(Pixels)', 'mobile-menu' ),
                'id'      => 'right_menu_width',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Menu Panel Width', 'mobile-menu' ),
                'default' => '270',
                'max'     => '450',
                'min'     => '50',
                'unit'    => 'px',
                'class'   => 'right-panel-options',
            ) );
            // Right Menu Panel Width.
            $general_tab->createOption( array(
                'name'    => __( 'Menu Panel Width(Percentage)', 'mobile-menu' ),
                'id'      => 'right_menu_width_percentage',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Menu Panel Width', 'mobile-menu' ),
                'default' => '70',
                'max'     => '90',
                'min'     => '0',
                'unit'    => '%',
                'class'   => 'right-panel-options',
            ) );
            // Right Menu Content Padding.
            $general_tab->createOption( array(
                'name'    => __( 'Right Menu Content Padding', 'mobile-menu' ),
                'id'      => 'right_menu_content_padding',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Menu Content Padding', 'mobile-menu' ),
                'default' => '10',
                'max'     => '30',
                'min'     => '0',
                'step'    => '1',
                'unit'    => '%',
                'class'   => 'right-panel-options',
            ) );
            // Right Menu Font.
            $general_tab->createOption( array(
                'name'                => __( 'Right Menu Font', 'mobile-menu' ),
                'id'                  => 'right_menu_font',
                'type'                => 'font',
                'desc'                => __( 'Select a style', 'mobile-menu' ),
                'show_font_weight'    => true,
                'show_font_style'     => true,
                'show_line_height'    => true,
                'show_letter_spacing' => true,
                'show_text_transform' => true,
                'show_font_size'      => true,
                'show_font_variant'   => false,
                'show_text_shadow'    => false,
                'show_color'          => false,
                'default'             => array(
                'line-height' => '1.5em',
                'font-family' => 'Dosis',
            ),
                'class'               => 'font-options',
            ) );
            // Overlay Background color.
            $general_tab->createOption( array(
                'name'    => __( 'Overlay Background Color', 'mobile-menu' ),
                'id'      => 'overlay_bg_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => 'rgba(0, 0, 0, 0.83)',
                'class'   => 'colors-options',
            ) );
            // Menu Items Border color.
            $general_tab->createOption( array(
                'name'    => __( 'Menu Items Border Color', 'mobile-menu' ),
                'id'      => 'menu_items_border_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => 'rgba(0, 0, 0, 0.83)',
                'class'   => 'colors-options',
            ) );
            // Header Background color.
            $general_tab->createOption( array(
                'name'    => __( 'Header Background Color', 'mobile-menu' ),
                'id'      => 'header_bg_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#111111',
                'class'   => 'header-colors',
            ) );
            // Header Text color.
            $general_tab->createOption( array(
                'name'    => __( 'Header Text Color', 'mobile-menu' ),
                'id'      => 'header_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#FFF',
                'class'   => 'header-colors',
            ) );
            $general_tab->createOption( array(
                'name'  => __( 'Alerts', 'mobile-menu' ),
                'type'  => 'note',
                'class' => 'general-alerts heading',
            ) );
            // Left Menu Icon color.
            $general_tab->createOption( array(
                'name'    => __( 'Menu Icon Color', 'mobile-menu' ),
                'id'      => 'left_menu_icon_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#FFF',
                'class'   => 'left-menu-colors',
            ) );
            // Header Text After Left Icon.
            $general_tab->createOption( array(
                'name'    => __( 'Text After Left Icon', 'mobile-menu' ),
                'id'      => 'header_text_after_icon',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#222',
                'class'   => 'left-menu-colors',
            ) );
            // Left Panel Background color.
            $general_tab->createOption( array(
                'name'    => __( 'Background Color', 'mobile-menu' ),
                'id'      => 'left_panel_bg_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#F7F7F7',
                'class'   => 'left-menu-colors',
            ) );
            // Left Panel Text color.
            $general_tab->createOption( array(
                'name'    => __( 'Text Color', 'mobile-menu' ),
                'id'      => 'left_panel_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#666',
                'class'   => 'left-menu-colors',
            ) );
            // Left Panel Background Hover Color.
            $general_tab->createOption( array(
                'name'    => __( 'Background Hover Color', 'mobile-menu' ),
                'id'      => 'left_panel_hover_bgcolor',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#666',
                'class'   => 'left-menu-colors',
            ) );
            // Left Panel Text color Hover.
            $general_tab->createOption( array(
                'name'    => __( 'Hover Text Color', 'mobile-menu' ),
                'id'      => 'left_panel_hover_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#FFF',
                'class'   => 'left-menu-colors',
            ) );
            // Left Panel Sub-menu Background Color.
            $general_tab->createOption( array(
                'name'    => __( 'Submenu Background Color', 'mobile-menu' ),
                'id'      => 'left_panel_submenu_bgcolor',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#3a3a3a',
                'class'   => 'left-menu-colors',
            ) );
            // Left Panel Sub-menu Text Color.
            $general_tab->createOption( array(
                'name'    => __( 'Submenu Text Color', 'mobile-menu' ),
                'id'      => 'left_panel_submenu_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#fff',
                'class'   => 'left-menu-colors',
            ) );
            // Left Panel Cancel Button Color.
            $general_tab->createOption( array(
                'name'    => __( 'Cancel Button Color', 'mobile-menu' ),
                'id'      => 'left_panel_cancel_button_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#666',
                'class'   => 'left-menu-colors',
            ) );
            // Right Menu Icon color.
            $general_tab->createOption( array(
                'name'    => __( 'Menu Icon Color', 'mobile-menu' ),
                'id'      => 'right_menu_icon_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#FFF',
                'class'   => 'right-menu-colors',
            ) );
            // Header Text Before Right Icon.
            $general_tab->createOption( array(
                'name'    => __( 'Text Before Right Icon', 'mobile-menu' ),
                'id'      => 'header_text_before_icon',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#222',
                'class'   => 'right-menu-colors',
            ) );
            // Right Panel Background color.
            $general_tab->createOption( array(
                'name'    => __( 'Background Color', 'mobile-menu' ),
                'id'      => 'right_panel_bg_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#F7F7F7',
                'class'   => 'right-menu-colors',
            ) );
            // Right Panel Text color.
            $general_tab->createOption( array(
                'name'    => __( 'Text Color', 'mobile-menu' ),
                'id'      => 'right_panel_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#666',
                'class'   => 'right-menu-colors',
            ) );
            // Right Panel Background Hover Color.
            $general_tab->createOption( array(
                'name'    => __( 'Background Hover Color', 'mobile-menu' ),
                'id'      => 'right_panel_hover_bgcolor',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#666',
                'class'   => 'right-menu-colors',
            ) );
            // Right Panel Text color Hover.
            $general_tab->createOption( array(
                'name'    => __( 'Hover Text Color', 'mobile-menu' ),
                'id'      => 'right_panel_hover_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#FFF',
                'class'   => 'right-menu-colors',
            ) );
            // Right Panel Sub-menu Background Color.
            $general_tab->createOption( array(
                'name'    => __( 'Submenu Background Color', 'mobile-menu' ),
                'id'      => 'right_panel_submenu_bgcolor',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#3a3a3a',
                'class'   => 'right-menu-colors',
            ) );
            // Right Panel Sub-menu Text Color.
            $general_tab->createOption( array(
                'name'    => __( 'Submenu Text Color', 'mobile-menu' ),
                'id'      => 'right_panel_submenu_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#fff',
                'class'   => 'right-menu-colors',
            ) );
            // Right Panel Cancel Button Color.
            $general_tab->createOption( array(
                'name'    => __( 'Cancel Button Color', 'mobile-menu' ),
                'id'      => 'right_panel_cancel_button_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#666',
                'class'   => 'right-menu-colors',
            ) );
            // Left Panel 3rd Level Left Menu Items Text color.
            $general_tab->createOption( array(
                'name'    => __( '3rd Level Text Color', 'mobile-menu' ),
                'id'      => 'left_panel_3rd_menu_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#fff',
                'class'   => 'left-menu-colors',
            ) );
            // Left Panel 3rd Level Left Menu Items Text color Hover.
            $general_tab->createOption( array(
                'name'    => __( '3rd Level Text Color Hover', 'mobile-menu' ),
                'id'      => 'left_panel_3rd_menu_text_color_hover',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#ccc',
                'class'   => 'left-menu-colors',
            ) );
            // Left Panel 3rd Level Left Menu Items Background color.
            $general_tab->createOption( array(
                'name'    => __( '3rd Level Background Color', 'mobile-menu' ),
                'id'      => 'left_panel_3rd_menu_bg_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#222',
                'class'   => 'left-menu-colors',
            ) );
            // Left Panel 3rd Level Left Menu Items Background color Hover.
            $general_tab->createOption( array(
                'name'    => __( '3rd Level Background Color Hover', 'mobile-menu' ),
                'id'      => 'left_panel_3rd_menu_bg_color_hover',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#666',
                'class'   => 'left-menu-colors',
            ) );
            // Right Panel 3rd Level Right Menu Items Text color.
            $general_tab->createOption( array(
                'name'    => __( '3rd Level Menu Text', 'mobile-menu' ),
                'id'      => 'right_panel_3rd_menu_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#fff',
                'class'   => 'right-menu-colors',
            ) );
            // Right Panel 3rd Level Right Menu Items Text color Hover.
            $general_tab->createOption( array(
                'name'    => __( '3rd Level Text Color Hover', 'mobile-menu' ),
                'id'      => 'right_panel_3rd_menu_text_color_hover',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#ccc',
                'class'   => 'right-menu-colors',
            ) );
            // Right Panel 3rd Level Right Menu Items Background color.
            $general_tab->createOption( array(
                'name'    => __( '3rd Level Background Color', 'mobile-menu' ),
                'id'      => 'right_panel_3rd_menu_bg_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#222',
                'class'   => 'right-menu-colors',
            ) );
            // Right Panel 3rd Level Right Menu Items Background color Hover.
            $general_tab->createOption( array(
                'name'    => __( '3rd Level Background Color Hover', 'mobile-menu' ),
                'id'      => 'right_panel_3rd_menu_bg_color_hover',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#666',
                'class'   => 'right-menu-colors',
            ) );
            $panel->createOption( array(
                'type' => 'save',
            ) );
        }
    
    }
    
    /**
     *
     * Create Woocommerce options upsell.
     *
     * @since 2.6
     *
     * @param type   $panel Panel Options.
     * @param Object $plugin_settings plugin settings object that is being edited.
     */
    public function create_woocommerce_options_upsell( $panel, $plugin_settings )
    {
        global  $mm_fs ;
        global  $general_tab ;
        $custom_html = '<div class="mm-business-features-holder"><div class="mm-bussiness-features"><h3>' . __( 'Increase your shop revenue (Business Version)', 'mobile-menu' ) . '</h3>';
        $custom_html .= '<p><span class="dashicons dashicons-yes"></span><strong>Menu Cart Icon</strong> - Product counter notification buble, upload the desired icon.</p>';
        $custom_html .= '<p><span class="dashicons dashicons-yes"></span><strong>Mobile Product Filter</strong> - Advanced product filter for mobile users.</p>';
        $custom_html .= '<p><span class="dashicons dashicons-yes"></span><strong>Show only products</strong> - In the Header Live Search.</p>';
        $custom_html .= '<p><span class="dashicons dashicons-yes"></span><strong>Cart total in footer</strong> - Cart total in all pages or only in WooCommerce pages.</p>';
        $custom_html .= '<p><span class="dashicons dashicons-yes"></span><strong>Sliding Cart</strong> - Easily see what is in the Cart.</p>';
        $custom_html .= '<p><span class="dashicons dashicons-yes"></span><strong>Account links in Sliding Cart</strong> - Easy access to the account area.</p>';
        $custom_html .= '<p><span class="dashicons dashicons-yes"></span><strong>Checkout and View Cart</strong> - Inside the sliding cart it will increase the conversion rate and avoid less abandoned carts.</p>';
        $custom_html .= '<p><a href="' . $mm_fs->get_upgrade_url() . '&cta=woo-settings#" class="button mm-button-business-upgrade">' . __( 'Upgrade to Business!', 'mobile-menu' ) . '</a></p>';
        $custom_html .= '<p>Not sure if it has the right features?  <a href="' . $mm_fs->get_trial_url() . '">' . esc_html( 'Start a Free trial', 'mobile-menu' ) . '</a></p>';
        $custom_html .= '</div>';
        $custom_html .= '<div class="mm-business-image"><a href="https://shopdemo.wpmobilemenu.com/?utm_source=wprepo-dash&utm_medium=user%20website&utm_campaign=upsell_link" target="_blank"><img src="' . plugins_url( 'demo-content/assets/shopdemo-mobile-menu.png', __FILE__ ) . '">';
        $custom_html .= '</a><p><a href="https://shopdemo.wpmobilemenu.com/?utm_source=wprepo-dash&utm_medium=user%20website&utm_campaign=upsell_link"> ' . esc_html( 'See Demo Site', 'mobile-menu' ) . '</a></div></div>';
        $general_tab->createOption( array(
            'name'   => 'woo-premium-features',
            'type'   => 'custom',
            'custom' => $custom_html,
            'class'  => 'woocommerce-options',
        ) );
    }
    
    /**
     *
     * Create Footer options upsell.
     *
     * @since 2.6
     *
     * @param type   $panel Panel Options.
     * @param Object $plugin_settings plugin settings object that is being edited.
     */
    public function create_footer_options_upsell( $panel, $plugin_settings )
    {
        global  $mm_fs ;
        global  $general_tab ;
        $custom_html = '<div class="mm-business-features-holder"><div class="mm-bussiness-features"><h3>' . __( 'Give your Website an App look and feel', 'mobile-menu' ) . '</h3>';
        $custom_html .= '<p><span class="dashicons dashicons-yes"></span><strong>Fixed Footer Bar</strong></p>';
        $custom_html .= '<p><span class="dashicons dashicons-yes"></span><strong>Auto-hide on Scroll</strong></p>';
        $custom_html .= '<p><span class="dashicons dashicons-yes"></span><strong>Highlight current page</strong></p>';
        $custom_html .= '<p><span class="dashicons dashicons-yes"></span><strong>4 Different Styles</strong></p>';
        $custom_html .= '<p><a href="' . $mm_fs->get_upgrade_url() . '&cta=footer-settings#" class="button mm-button-business-upgrade">' . __( 'Upgrade now!', 'mobile-menu' ) . '</a></p>';
        $custom_html .= '<p>Not sure if it has the right features?  <a href="' . $mm_fs->get_trial_url() . '">' . esc_html( 'Start a Free trial', 'mobile-menu' ) . '</a></p>';
        $custom_html .= '</div>';
        $custom_html .= '<div class="mm-business-image"><a href="https://prodemo.wpmobilemenu.com/?utm_source=wprepo-dash&utm_medium=user%20website&utm_campaign=upsell_link" target="_blank"><img src="' . plugins_url( 'demo-content/assets/prodemo-mobile-menu.png', __FILE__ ) . '">';
        $custom_html .= '</a><p><a href="https://prodemo.wpmobilemenu.com/?utm_source=wprepo-dash&utm_medium=user%20website&utm_campaign=upsell_link"> ' . esc_html( 'See Demo Site', 'mobile-menu' ) . '</a></div></div>';
        // Footer Tab Upgrade Content.
        $general_tab->createOption( array(
            'name'   => 'footer-upsell',
            'type'   => 'custom',
            'custom' => $custom_html,
            'class'  => 'footer-options',
        ) );
    }

}