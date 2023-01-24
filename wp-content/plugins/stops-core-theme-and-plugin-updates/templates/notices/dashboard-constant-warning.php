<?php if (!defined('EASY_UPDATES_MANAGER_MAIN_PATH')) die('No direct access allowed'); ?>

<div id="easy-updates-manager-constants-enabled" class="error">
	<div style="float:right;"><a href="#" onclick="jQuery('#easy-updates-manager-constants-enabled').slideUp(); jQuery.post(ajaxurl, {action: 'easy_updates_manager_ajax', subaction: 'dismiss_constant_notices', nonce: '<?php echo wp_create_nonce('easy-updates-manager-ajax-nonce'); ?>' });"><?php printf(__('Dismiss', 'stops-core-theme-and-plugin-updates')); ?></a></div>

	<h3><?php
		// Allow white label
		$eum_white_label = apply_filters('eum_whitelabel_name', __('Easy Updates Manager', 'stops-core-theme-and-plugin-updates'));
		sprintf(_e("The following constants are set and will prevent automatic updates in %s.", 'stops-core-theme-and-plugin-updates'), $eum_white_label);
	?></h3>
	<div id="easy-updates-manager-constants-enabled-wrapper">
		<p><?php sprintf(esc_html_e('Please check your wp-config.php file or other files for these constants and remove them to allow Easy Updates Manager to have control.', 'stops-core-theme-and-plugin-updates'), $eum_white_label); ?></p>
		<?php
		$html = '<ul>';
		if (defined('AUTOMATIC_UPDATER_DISABLED') && true === AUTOMATIC_UPDATER_DISABLED) {
			$html .= sprintf('<li><strong>%s</strong>: %s</li>', 'AUTOMATIC_UPDATER_DISABLED', esc_html__('This constant disables any automatic updates.', 'stops-core-theme-and-plugin-updates'));
		}
		if (defined('WP_AUTO_UPDATE_CORE') && 'minor' === WP_AUTO_UPDATE_CORE) {
			$html .= sprintf('<li><strong>%s</strong>: %s</li>', 'WP_AUTO_UPDATE_CORE', esc_html__('This constant prevents automatic updates to new major releases of WordPress.', 'stops-core-theme-and-plugin-updates'));
		}
		if (defined('WP_AUTO_UPDATE_CORE') && false === WP_AUTO_UPDATE_CORE) {
			$html .= sprintf('<li><strong>%s</strong>: %s</li>', 'WP_AUTO_UPDATE_CORE', esc_html__('This constant disables WordPress core from being automatically updated.', 'stops-core-theme-and-plugin-updates'));
		}
		$html .= '</ul>';
		echo $html;
		?>
	</div>
</div>
