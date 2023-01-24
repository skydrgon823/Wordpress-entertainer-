<?php if (!defined('ABSPATH')) die('No direct access.'); ?>
<h3><?php esc_html_e('Theme update options', 'stops-core-theme-and-plugin-updates'); ?></h3>
<?php
	if (false === MPSUM_Admin_Themes::can_update_themes()) {
		printf('<div class="error"><p><strong>%s</strong></p></div>', esc_html__('All theme updates have been disabled.', 'stops-core-theme-and-plugin-updates'));
	}
	do_action('eum_themes_tab_header');
	$theme_table = new MPSUM_Themes_List_Table($args = array('screen' => $slug, 'paged' => $paged, 'view' => $view));
	$theme_table->prepare_items();
	$theme_table->views();
	$theme_table->display();
	?>
	<p class="submit" style="display:none"><input type="submit" name="submit" id="eum-save-settings" class="button button-primary" value="<?php esc_attr_e('Save', 'stops-core-theme-and-plugin-updates'); ?>"></p>