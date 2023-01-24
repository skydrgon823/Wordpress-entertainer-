<?php if (!defined('ABSPATH')) die('No direct access.'); ?>
<div id="result"></div>
<h3><?php esc_html_e('Clear logs', 'stops-core-theme-and-plugin-updates'); ?></h3>
<?php
	printf('<p class="submit"><input type="submit" name="clear-log" id="clear-logs" class="button button-primary" value="%1$s" /></p>', esc_attr__('Clear now', 'stops-core-theme-and-plugin-updates'));
	do_action('eum_logs');
?>
	<h3><?php esc_html_e('Update logs', 'stops-core-theme-and-plugin-updates'); ?></h3>
	<p><?php esc_html_e('Please note that this feature does not necessarily work for premium themes and plugins.', 'stops-core-theme-and-plugin-updates');?></p>
<?php
$core_options = MPSUM_Updates_Manager::get_options('core');
$logs_table = new MPSUM_Logs_List_Table($args = array('paged' => $paged, 'view' => $view, 'status' => $status, 'action_type' => $action_type, 'type' => $type, 'm' => $m, 'is_search' => $is_search, 'search_term' => $search_term, 'order' => $order));
$logs_table->prepare_items();
$logs_table->views();
$logs_table->display();
