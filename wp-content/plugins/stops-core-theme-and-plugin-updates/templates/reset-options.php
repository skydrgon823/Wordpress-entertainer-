<?php
if (!defined('ABSPATH')) die('No direct access.');
echo '<div class="eum-advanced-settings-container reset-options">';
printf('<h3>%s</h3>', esc_html__('Reset options', 'stops-core-theme-and-plugin-updates'));
printf('<p>%s</p>', esc_html__('This will reset all options to as if you have just installed the plugin. WARNING!: This also clears the logs.', 'stops-core-theme-and-plugin-updates'));
printf('<p class="submit"><input type="submit" name="submit" id="reset-options" class="button button-primary" value="%s"></p>', esc_attr__('Reset all options', 'stops-core-theme-and-plugin-updates'));
echo '</div>';
