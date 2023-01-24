<?php if (!defined('ABSPATH')) die('No direct access.'); ?>
<h3><?php esc_html_e('Plugin update options', 'stops-core-theme-and-plugin-updates'); ?></h3>
<?php
	$core_options = MPSUM_Updates_Manager::get_options('core');
	if (false === MPSUM_Admin_Plugins::can_update_plugins()) {
		printf('<div class="error"><p><strong>%s</strong></p></div>', esc_html__('All plugin updates have been disabled.', 'stops-core-theme-and-plugin-updates'));
	}
	do_action('eum_plugins_tab_header');
	$plugin_table = new MPSUM_Plugins_List_Table($args = array('screen' => $slug, 'paged' => $paged, 'view' => $view));
	$plugin_table->prepare_items();
	$plugin_table->views();
	$plugin_table->display();
	?>
	<p class="submit" style="display:none"><input type="submit" name="submit" id="eum-save-settings" class="button button-primary" value="<?php esc_attr_e('Save', 'stops-core-theme-and-plugin-updates'); ?>"></p>
