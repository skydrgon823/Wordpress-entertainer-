<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WprAddons\Admin\Includes\WPR_Templates_Loop;
use WprAddonsPro\Admin\Wpr_White_Label;
use WprAddons\Classes\Utilities;

// Register Menus
function wpr_addons_add_admin_menu() {
    $menu_icon = !empty(get_option('wpr_wl_plugin_logo')) ? 'dashicons-admin-generic' : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTciIGhlaWdodD0iNzUiIHZpZXdCb3g9IjAgMCA5NyA3NSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTAuMDM2NDA4NiAyMy4yODlDLTAuNTc1NDkgMTguNTIxIDYuNjg4NzMgMTYuMzY2NiA5LjU0OSAyMC40Njc4TDQyLjgzNjUgNjguMTk3MkM0NC45MTgxIDcxLjE4MiA0Mi40NDk0IDc1IDM4LjQzNzggNzVIMTEuMjc1NkM4LjY1NDc1IDc1IDYuNDUyNjQgNzMuMjg1NSA2LjE2MTcgNzEuMDE4NEwwLjAzNjQwODYgMjMuMjg5WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTk2Ljk2MzYgMjMuMjg5Qzk3LjU3NTUgMTguNTIxIDkwLjMxMTMgMTYuMzY2NiA4Ny40NTEgMjAuNDY3OEw1NC4xNjM1IDY4LjE5NzJDNTIuMDgxOCA3MS4xODIgNTQuNTUwNiA3NSA1OC41NjIyIDc1SDg1LjcyNDRDODguMzQ1MiA3NSA5MC41NDc0IDczLjI4NTUgOTAuODM4MyA3MS4wMTg0TDk2Ljk2MzYgMjMuMjg5WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTUzLjI0MTIgNC40ODUyN0M1My4yNDEyIC0wLjI3MDc2MSA0NS44NDg1IC0xLjc0ODAzIDQzLjQ2NTEgMi41MzE3NEw2LjY4OTkxIDY4LjU2NzdDNS4wMzM0OSA3MS41NDIxIDcuNTIyNzIgNzUgMTEuMzIwMyA3NUg0OC4wOTU1QzUwLjkzNzQgNzUgNTMuMjQxMiA3Mi45OTQ4IDUzLjI0MTIgNzAuNTIxMlY0LjQ4NTI3WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTQzLjc1ODggNC40ODUyN0M0My43NTg4IC0wLjI3MDc2MSA1MS4xNTE1IC0xLjc0ODAzIDUzLjUzNDkgMi41MzE3NEw5MC4zMTAxIDY4LjU2NzdDOTEuOTY2NSA3MS41NDIxIDg5LjQ3NzMgNzUgODUuNjc5NyA3NUg0OC45MDQ1QzQ2LjA2MjYgNzUgNDMuNzU4OCA3Mi45OTQ4IDQzLjc1ODggNzAuNTIxMlY0LjQ4NTI3WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==';
    add_menu_page( Utilities::get_plugin_name(), Utilities::get_plugin_name(), 'manage_options', 'wpr-addons', 'wpr_addons_settings_page', $menu_icon, '58.6' );
    
    add_action( 'admin_init', 'wpr_register_addons_settings' );
    add_filter( 'plugin_action_links_royal-elementor-addons/wpr-addons.php', 'wpr_settings_link' );
}
add_action( 'admin_menu', 'wpr_addons_add_admin_menu' );

// Add Settings page link to plugins screen
function wpr_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=wpr-addons">Settings</a>';
    array_push( $links, $settings_link );

    return $links;
}

// Register Settings
function wpr_register_addons_settings() {
    // Integrations
    register_setting( 'wpr-settings', 'wpr_google_map_api_key' );
    register_setting( 'wpr-settings', 'wpr_mailchimp_api_key' );

    // Lightbox
    register_setting( 'wpr-settings', 'wpr_lb_bg_color' );
    register_setting( 'wpr-settings', 'wpr_lb_toolbar_color' );
    register_setting( 'wpr-settings', 'wpr_lb_caption_color' );
    register_setting( 'wpr-settings', 'wpr_lb_gallery_color' );
    register_setting( 'wpr-settings', 'wpr_lb_pb_color' );
    register_setting( 'wpr-settings', 'wpr_lb_ui_color' );
    register_setting( 'wpr-settings', 'wpr_lb_ui_hr_color' );
    register_setting( 'wpr-settings', 'wpr_lb_text_color' );
    register_setting( 'wpr-settings', 'wpr_lb_icon_size' );
    register_setting( 'wpr-settings', 'wpr_lb_arrow_size' );
    register_setting( 'wpr-settings', 'wpr_lb_text_size' );

    // White Label
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_logo' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_name' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_desc' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_author' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_website' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_plugin_links' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_hide_elements_tab' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_hide_extensions_tab' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_hide_settings_tab' );
    register_setting( 'wpr-wh-settings', 'wpr_wl_hide_white_label_tab' );

    // Extensions
    register_setting('wpr-extension-settings', 'wpr-particles');
    register_setting('wpr-extension-settings', 'wpr-parallax-background');
    register_setting('wpr-extension-settings', 'wpr-parallax-multi-layer');
    register_setting('wpr-extension-settings', 'wpr-sticky-section');
    // register_setting('wpr-extension-settings', 'wpr-reading-progress-bar');

    // Element Toggle
    foreach ( Utilities::get_registered_modules() as $title => $data ) {
        $slug = $data[0];
        register_setting( 'wpr-elements-settings', 'wpr-element-'. $slug, [ 'default' => 'on' ] );
    }
    register_setting( 'wpr-elements-settings', 'wpr-element-toggle-all', [ 'default' => 'on' ]  );
}

function wpr_addons_settings_page() {
    
?>

<div class="wrap wpr-settings-page-wrap">

<div class="wpr-settings-page-header">
    <h1><?php echo Utilities::get_plugin_name(true); ?></h1>
    <p><?php esc_html_e( 'The most powerful Elementor Addons in the universe.', 'wpr-addons' ); ?></p>

    <?php if ( empty(get_option('wpr_wl_plugin_links')) ) : ?>
    <a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-plugin-prev-btn#widgets" target="_blank" class="button wpr-options-button">
        <span><?php echo esc_html( 'View Plugin Demo', 'wpr-addons' ); ?></span>
    </a>
    <?php endif; ?>
</div>

<div class="wpr-settings-page">
<form method="post" action="options.php">
    <?php

    // Active Tab
    if ( empty(get_option('wpr_wl_hide_elements_tab')) ) {
        $active_tab = isset( $_GET['tab'] ) ? esc_attr($_GET['tab']) : 'wpr_tab_elements';
    } elseif ( empty(get_option('wpr_wl_hide_extensions_tab')) ) {
        $active_tab = isset( $_GET['tab'] ) ? esc_attr($_GET['tab']) : 'wpr_tab_extensions';
    } elseif ( empty(get_option('wpr_wl_hide_settings_tab')) ) {
        $active_tab = isset( $_GET['tab'] ) ? esc_attr($_GET['tab']) : 'wpr_tab_settings';
    } elseif ( empty(get_option('wpr_wl_hide_white_label_tab')) ) {
        $active_tab = isset( $_GET['tab'] ) ? esc_attr($_GET['tab']) : 'wpr_tab_white_label';
    } else {
        $active_tab = isset( $_GET['tab'] ) ? esc_attr($_GET['tab']) : 'wpr_tab_my_templates';
    }
    

    // Render Create Templte Popup
    WPR_Templates_Loop::render_create_template_popup();
    
    ?>

    <!-- Tabs -->
    <div class="nav-tab-wrapper wpr-nav-tab-wrapper">
        <?php if ( empty(get_option('wpr_wl_hide_elements_tab')) ) : ?>
        <a href="?page=wpr-addons&tab=wpr_tab_elements" data-title="Elements" class="nav-tab <?php echo $active_tab == 'wpr_tab_elements' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Widgets', 'wpr-addons' ); ?>
        </a>
        <?php endif; ?>

        <?php if ( empty(get_option('wpr_wl_hide_extensions_tab')) ) : ?>
        <a href="?page=wpr-addons&tab=wpr_tab_extensions" data-title="Extensions" class="nav-tab <?php echo $active_tab == 'wpr_tab_extensions' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Extensions', 'wpr-addons' ); ?>
        </a>
        <?php endif; ?>

        <a href="?page=wpr-addons&tab=wpr_tab_my_templates" data-title="My Templates" class="nav-tab <?php echo $active_tab == 'wpr_tab_my_templates' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'My Templates', 'wpr-addons' ); ?>
        </a>

        <?php if ( empty(get_option('wpr_wl_hide_settings_tab')) ) : ?>
        <a href="?page=wpr-addons&tab=wpr_tab_settings" data-title="Settings" class="nav-tab <?php echo $active_tab == 'wpr_tab_settings' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Settings', 'wpr-addons' ); ?>
        </a>
        <?php endif; ?>

        <?php // White Label
            echo !empty(get_option('wpr_wl_hide_white_label_tab')) ? '<div style="display: none;">' : '<div>';
                do_action('wpr_white_label_tab');
            echo '</div>';
        ?>
    </div>

    <?php if ( $active_tab == 'wpr_tab_elements' ) : ?>

    <?php

    // Settings
    settings_fields( 'wpr-elements-settings' );
    do_settings_sections( 'wpr-elements-settings' );

    ?>

    <div class="wpr-elements-toggle">
        <div>
            <h3><?php esc_html_e( 'Toggle all Widgets', 'wpr-addons' ); ?></h3>
            <input type="checkbox" name="wpr-element-toggle-all" id="wpr-element-toggle-all" <?php checked( get_option('wpr-element-toggle-all', 'on'), 'on', true ); ?>>
            <label for="wpr-element-toggle-all"></label>
        </div>
        <p><?php esc_html_e( 'You can disable some widgets for faster page speed.', 'wpr-addons' ); ?></p>
    </div>

    <div class="wpr-elements">

    <?php

    foreach ( Utilities::get_registered_modules() as $title => $data ) {
        $slug = $data[0];
        $url  = $data[1];
        $reff = '?ref=rea-plugin-backend-elements-widget-prev'. $data[2];

        echo '<div class="wpr-element">';
            echo '<div class="wpr-element-info">';
                echo '<h3>'. $title .'</h3>';
                echo '<input type="checkbox" name="wpr-element-'. $slug .'" id="wpr-element-'. $slug .'" '. checked( get_option('wpr-element-'. $slug, 'on'), 'on', false ) .'>';
                echo '<label for="wpr-element-'. $slug .'"></label>';
                echo ( '' !== $url && empty(get_option('wpr_wl_plugin_links')) ) ? '<a href="'. $url . $reff .'" target="_blank">'. esc_html('View Widget Demo', 'wpr-addons') .'</a>' : '';
            echo '</div>';
        echo '</div>';
    }
    
    ?>

    </div>

    <?php submit_button( '', 'wpr-options-button' ); ?>

    <?php elseif ( $active_tab == 'wpr_tab_my_templates' ) : ?>

        <!-- Custom Template -->
        <div class="wpr-user-template">
            <span><?php esc_html_e( 'Create Template', 'wpr-addons' ); ?></span>
            <span class="plus-icon">+</span>
        </div>

        <?php Wpr_Templates_Loop::render_elementor_saved_templates(); ?>

    <?php elseif ( $active_tab == 'wpr_tab_settings' ) : ?>

        <?php

        // Settings
        settings_fields( 'wpr-settings' );
        do_settings_sections( 'wpr-settings' );

        ?>

        <div class="wpr-settings">

        <?php submit_button( '', 'wpr-options-button' ); ?>

        <div class="wpr-settings-group">
            <h3 class="wpr-settings-group-title"><?php esc_html_e( 'Integrations', 'wpr-addons' ); ?></h3>

            <div class="wpr-setting">
                <h4>
                    <span><?php esc_html_e( 'Google Map API Key', 'wpr-addons' ); ?></span>
                    <br>
                    <a href="https://www.youtube.com/watch?v=O5cUoVpVUjU" target="_blank"><?php esc_html_e( 'How to get Google Map API Key?', 'wpr-addons' ); ?></a>
                </h4>

                <input type="text" name="wpr_google_map_api_key" id="wpr_google_map_api_key" value="<?php echo esc_attr(get_option('wpr_google_map_api_key')); ?>">
            </div>

            <div class="wpr-setting">
                <h4>
                    <span><?php esc_html_e( 'MailChimp API Key', 'wpr-addons' ); ?></span>
                    <br>
                    <a href="https://mailchimp.com/help/about-api-keys/" target="_blank"><?php esc_html_e( 'How to get MailChimp API Key?', 'wpr-addons' ); ?></a>
                </h4>

                <input type="text" name="wpr_mailchimp_api_key" id="wpr_mailchimp_api_key" value="<?php echo esc_attr(get_option('wpr_mailchimp_api_key')); ?>">
            </div>
        </div>

        <div class="wpr-settings-group">
            <h3 class="wpr-settings-group-title"><?php esc_html_e( 'Lightbox', 'wpr-addons' ); ?></h3>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Background Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_bg_color" id="wpr_lb_bg_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_bg_color','rgba(0,0,0,0.6)')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Toolbar BG Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_toolbar_color" id="wpr_lb_toolbar_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_toolbar_color','rgba(0,0,0,0.8)')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Caption BG Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_caption_color" id="wpr_lb_caption_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_caption_color','rgba(0,0,0,0.8)')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Gallery BG Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_gallery_color" id="wpr_lb_gallery_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_gallery_color','#444444')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Progress Bar Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_pb_color" id="wpr_lb_pb_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_pb_color','#a90707')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'UI Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_ui_color" id="wpr_lb_ui_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_ui_color','#efefef')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'UI Hover Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_ui_hr_color" id="wpr_lb_ui_hr_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_ui_hr_color','#ffffff')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Text Color', 'wpr-addons' ); ?></h4>
                <input type="text" name="wpr_lb_text_color" id="wpr_lb_text_color" data-alpha="true" value="<?php echo esc_attr(get_option('wpr_lb_text_color','#efefef')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'UI Icon Size', 'wpr-addons' ); ?></h4>
                <input type="number" name="wpr_lb_icon_size" id="wpr_lb_icon_size" value="<?php echo esc_attr(get_option('wpr_lb_icon_size','20')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Navigation Arrow Size', 'wpr-addons' ); ?></h4>
                <input type="number" name="wpr_lb_arrow_size" id="wpr_lb_arrow_size" value="<?php echo esc_attr(get_option('wpr_lb_arrow_size','35')); ?>">
            </div>

            <div class="wpr-setting">
                <h4><?php esc_html_e( 'Text Size', 'wpr-addons' ); ?></h4>
                <input type="number" name="wpr_lb_text_size" id="wpr_lb_text_size" value="<?php echo esc_attr(get_option('wpr_lb_text_size','14')); ?>">
            </div>
        </div>

        <?php submit_button( '', 'wpr-options-button' ); ?>

        </div>

    <?php elseif ( $active_tab == 'wpr_tab_extensions' ) :

        // Extensions
        settings_fields( 'wpr-extension-settings' );
        do_settings_sections( 'wpr-extension-settings' );

        global $new_allowed_options;

        // array of option names
        $option_names = $new_allowed_options[ 'wpr-extension-settings' ];

        echo '<div class="wpr-elements">';

        foreach ($option_names as $option_name) {  
            $option_title = ucwords( preg_replace( '/-/i', ' ', preg_replace('/wpr-||-toggle/i', '', $option_name ) ));

            echo '<div class="wpr-element">';
                echo '<div class="wpr-element-info">';
                    echo '<h3>' . $option_title . '</h3>';
                    echo '<input type="checkbox" name="'. $option_name .'" id="'. $option_name .'" '. checked( get_option(''. $option_name .'', 'on'), 'on', false ) .'>';
                    echo '<label for="'. $option_name .'"></label>';

                    if ( 'wpr-parallax-background' === $option_name ) {
                        echo '<br><span>Tip: Edit any Section > Navigate to Style tab</span>';
                        echo '<a href="https://www.youtube.com/watch?v=DcDeQ__lJbw" target="_blank">Watch Video Tutorial</a>';
                    } else if ( 'wpr-parallax-multi-layer' === $option_name ) {
                        echo '<br><span>Tip: Edit any Section > Navigate to Style tab</span>';
                        echo '<a href="https://youtu.be/DcDeQ__lJbw?t=121" target="_blank">Watch Video Tutorial</a>';
                    } else if ( 'wpr-particles' === $option_name ) {
                        echo '<br><span>Tip: Edit any Section > Navigate to Style tab</span>';
                        echo '<a href="https://www.youtube.com/watch?v=8OdnaoFSj94" target="_blank">Watch Video Tutorial</a>';
                    } else if ( 'wpr-sticky-section' === $option_name ) {
                        echo '<br><span>Tip: Edit any Section > Navigate to Advanced tab</span>';
                        echo '<a href="https://www.youtube.com/watch?v=at0CPKtklF0&t=375s" target="_blank">Watch Video Tutorial</a>';
                    }

                    // echo '<a href="https://royal-elementor-addons.com/elementor-particle-effects/?ref=rea-plugin-backend-extentions-prev">'. esc_html('View Extension Demo', 'wpr-addons') .'</a>';
                echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        
        submit_button( '', 'wpr-options-button' );

    elseif ( $active_tab == 'wpr_tab_white_label' ) :

        do_action('wpr_white_label_tab_content');

    endif; ?>

</form>
</div>

</div>


<?php

} // End wpr_addons_settings_page()



// Add Support Sub Menu item that will redirect to wp.org
function wpr_addons_add_support_menu() {
    add_submenu_page( 'wpr-addons', 'Support', 'Support', 'manage_options', 'wpr-support', 'wpr_addons_support_page', 99 );
}
add_action( 'admin_menu', 'wpr_addons_add_support_menu', 99 );

function wpr_addons_support_page() {}

function wpr_redirect_support_page() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready( function($) {
            $( 'ul#adminmenu a[href*="page=wpr-support"]' ).attr('href', 'https://wordpress.org/support/plugin/royal-elementor-addons/').attr( 'target', '_blank' );
        });
    </script>
    <?php
}
add_action( 'admin_head', 'wpr_redirect_support_page' );


// Add Upgrade Sub Menu item that will redirect to royal-elementor-addons.com
function wpr_addons_add_upgrade_menu() {
    if ( defined('WPR_ADDONS_PRO_VERSION') ) return;
    add_submenu_page( 'wpr-addons', 'Upgrade', 'Upgrade', 'manage_options', 'wpr-upgrade', 'wpr_addons_upgrade_page', 99 );
}
add_action( 'admin_menu', 'wpr_addons_add_upgrade_menu', 99 );

function wpr_addons_upgrade_page() {}

function wpr_redirect_upgrade_page() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready( function($) {
            $( 'ul#adminmenu a[href*="page=wpr-upgrade"]' ).attr('href', 'https://royal-elementor-addons.com/?ref=rea-plugin-backend-menu-upgrade-pro#purchasepro').attr( 'target', '_blank' );
            $( 'ul#adminmenu a[href*="#purchasepro"]' ).css('color', 'greenyellow');
        });
    </script>
    <?php
}
add_action( 'admin_head', 'wpr_redirect_upgrade_page' );