<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WprAddons\Admin\Includes\WPR_Templates_Loop;
use WprAddons\Classes\Utilities;

// Register Menus
function wpr_addons_add_popups_menu() {
	add_submenu_page( 'wpr-addons', 'Popups', 'Popups', 'manage_options', 'wpr-popups', 'wpr_addons_popups_page' );
}
add_action( 'admin_menu', 'wpr_addons_add_popups_menu' );

function wpr_addons_popups_page() {

?>

<div class="wrap wpr-settings-page-wrap">

<div class="wpr-settings-page-header">
    <h1><?php echo Utilities::get_plugin_name(true); ?></h1>
    <p><?php esc_html_e( 'The most powerful Elementor Addons in the universe.', 'wpr-addons' ); ?></p>

    <!-- Custom Template -->
    <div class="wpr-user-template">
        <span><?php esc_html_e( 'Create Template', 'wpr-addons' ); ?></span>
        <span class="plus-icon">+</span>
    </div>
</div>

<div class="wpr-settings-page">
<form method="post" action="options.php">
    <?php

    // Active Tab
    $active_tab = isset( $_GET['tab'] ) ? esc_attr($_GET['tab']) : 'wpr_tab_popups';

    ?>

    <!-- Template ID Holder -->
    <input type="hidden" name="wpr_template" id="wpr_template" value="">

    <!-- Conditions Popup -->
    <?php WPR_Templates_Loop::render_conditions_popup(); ?>

    <!-- Create Templte Popup -->
    <?php WPR_Templates_Loop::render_create_template_popup(); ?>

    <!-- Tabs -->
    <div class="nav-tab-wrapper wpr-nav-tab-wrapper">
        <a href="?page=wpr-theme-builder&tab=wpr_tab_popups" data-title="popup" class="nav-tab <?php echo $active_tab == 'wpr_tab_popups' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Popups', 'wpr-addons' ); ?>
        </a>
    </div>

    <?php if ( $active_tab == 'wpr_tab_popups' ) : ?>

        <!-- Save Conditions -->
        <input type="hidden" name="wpr_popup_conditions" id="wpr_popup_conditions" value="<?php echo esc_attr(get_option('wpr_popup_conditions', '[]')); ?>">

        <?php WPR_Templates_Loop::render_theme_builder_templates( 'popup' ); ?>

    <?php endif; ?>

</form>
</div>

</div>


<?php

} // End wpr_addons_popups_page()