<?php
/*
  Plugin Name: Under Construction
  Plugin URI: https://underconstructionpage.com/
  Description: Put your site behind a great looking under construction, coming soon, maintenance mode or landing page.
  Author: WebFactory Ltd
  Version: 3.88
  Requires at least: 4.0
  Requires PHP: 5.2
  Tested up to: 5.6
  Author URI: https://www.webfactoryltd.com/
  Text Domain: under-construction-page

  Copyright 2015 - 2021  WebFactory Ltd  (email: ucp@webfactoryltd.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// this is an include only WP file
if (!defined('ABSPATH')) {
  die;
}


define('UCP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('UCP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('UCP_OPTIONS_KEY', 'ucp_options');
define('UCP_META_KEY', 'ucp_meta');
define('UCP_POINTERS_KEY', 'ucp_pointers');
define('UCP_NOTICES_KEY', 'ucp_notices');
define('UCP_SURVEYS_KEY', 'ucp_surveys');

require_once 'wf-flyout/wf-flyout.php';
new wf_flyout(__FILE__);

require_once UCP_PLUGIN_DIR . 'ucp-license.php';


// main plugin class
class UCP {
  static $version = 0;
  static $licensing_servers = array('https://license1.underconstructionpage.com/', 'https://license2.underconstructionpage.com/');


  // get plugin version from header
  static function get_plugin_version() {
    $plugin_data = get_file_data(__FILE__, array('version' => 'Version'), 'plugin');
    self::$version = $plugin_data['version'];

    return $plugin_data['version'];
  } // get_plugin_version


  // hook things up
  static function init() {
    // check if minimal required WP version is present
    if (false === self::check_wp_version(4.0)) {
      return false;
    }

    if (is_admin()) {
      // if the plugin was updated from ver < 1.20 upgrade settings array
      self::maybe_upgrade();

      // add UCP menu to admin tools menu group
      add_action('admin_menu', array(__CLASS__, 'admin_menu'));

      // settings registration
      add_action('admin_init', array(__CLASS__, 'register_settings'));

      // aditional links in plugin description
      add_filter('plugin_action_links_' . plugin_basename(__FILE__),
                            array(__CLASS__, 'plugin_action_links'));
      add_filter('plugin_row_meta', array(__CLASS__, 'plugin_meta_links'), 10, 2);
      add_filter('admin_footer_text', array(__CLASS__, 'admin_footer_text'));
      add_filter('admin_footer', array(__CLASS__, 'admin_footer'));

      // manages admin header notifications
      add_action('admin_notices', array(__CLASS__, 'admin_notices'));
      add_action('admin_action_ucp_dismiss_notice', array(__CLASS__, 'dismiss_notice'));
      add_action('admin_action_ucp_change_status', array(__CLASS__, 'change_status'));
      add_action('admin_action_ucp_reset_settings', array(__CLASS__, 'reset_settings'));
      add_action('admin_action_install_weglot', array(__CLASS__, 'install_weglot'));

      // enqueue admin scripts
      add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts'), 100, 1);

      // AJAX endpoints
      add_action('wp_ajax_ucp_dismiss_pointer', array(__CLASS__, 'dismiss_pointer_ajax'));
      add_action('wp_ajax_ucp_dismiss_survey', array(__CLASS__, 'dismiss_survey_ajax'));
      add_action('wp_ajax_ucp_submit_survey', array(__CLASS__, 'submit_survey_ajax'));
      add_action('wp_ajax_ucp_submit_support_message', array(__CLASS__, 'submit_support_message_ajax'));
    } else {
      // main plugin logic
      add_action('wp', array(__CLASS__, 'display_construction_page'), 0, 1);

      // show under construction notice on login form
      add_filter('login_message', array(__CLASS__, 'login_message'));

      // disable feeds
      add_action('do_feed_rdf', array(__CLASS__, 'disable_feed'), 0, 1);
      add_action('do_feed_rss', array(__CLASS__, 'disable_feed'), 0, 1);
      add_action('do_feed_rss2', array(__CLASS__, 'disable_feed'), 0, 1);
      add_action('do_feed_atom', array(__CLASS__, 'disable_feed'), 0, 1);

      add_action('wp_footer', array(__CLASS__, 'whitelisted_notice'));
    } // if not admin

    // admin bar notice for frontend & backend
    add_action('wp_before_admin_bar_render', array(__CLASS__, 'admin_bar'));
    add_action('wp_head', array(__CLASS__, 'admin_bar_style'));
    add_action('admin_head', array(__CLASS__, 'admin_bar_style'));

    UCP_license::init();
  } // init


  // check if user has the minimal WP version required by UCP
  static function check_wp_version($min_version) {
    if (!version_compare(get_bloginfo('version'), $min_version,  '>=')) {
        add_action('admin_notices', array(__CLASS__, 'notice_min_wp_version'));
      return false;
    } else {
      return true;
    }
  } // check_wp_version


  // display error message if WP version is too low
  static function notice_min_wp_version() {
    echo '<div class="error"><p>' . sprintf(__('UnderConstruction plugin <b>requires WordPress version 4.0</b> or higher to function properly. You are using WordPress version %s. Please <a href="%s">update it</a>.', 'under-construction-page'), get_bloginfo('version'), admin_url('update-core.php')) . '</p></div>';
  } // notice_min_wp_version_error


  // some things have to be loaded earlier
  static function plugins_loaded() {
    self::get_plugin_version();

    load_plugin_textdomain('under-construction-page');
  } // plugins_loaded


  // activate doesn't get fired on upgrades so we have to compensate
  public static function maybe_upgrade() {
    $meta = self::get_meta();
    $options = self::get_options();

    // added in v1.70 to rename roles to whitelisted_roles
    if (isset($options['roles'])) {
      $options['whitelisted_roles'] = $options['roles'];
      unset($options['roles']);
      update_option(UCP_OPTIONS_KEY, $options);
    }

    // check if we need to convert options from the old format to new, or maybe it is already done
    if (isset($meta['options_ver']) && $meta['options_ver'] == self::$version) {
      return;
    }

    if (get_option('set_size') || get_option('set_tweet') || get_option('set_fb') || get_option('set_font') || get_option('set_msg') || get_option('set_opt') || get_option('set_admin')) {
      // convert old options to new
      $options['status'] = (get_option('set_opt') === 'Yes')? '1': '0';
      $options['content'] = trim(get_option('set_msg'));
      $options['whitelisted_roles'] = (get_option('set_admin') === 'No')? array('administrator'): array();
      $options['social_facebook'] = trim(get_option('set_fb'));
      $options['social_twitter'] = trim(get_option('set_tweet'));
      update_option(UCP_OPTIONS_KEY, $options);

      delete_option('set_size');
      delete_option('set_tweet');
      delete_option('set_fb');
      delete_option('set_font');
      delete_option('set_msg');
      delete_option('set_opt');
      delete_option('set_admin');

      self::reset_pointers();
    }

    // we update only once
    $meta['options_ver'] = self::$version;
    update_option(UCP_META_KEY, $meta);
  } // maybe_upgrade


  // get plugin's options
  static function get_options() {
    $options = get_option(UCP_OPTIONS_KEY, array());

    if (!is_array($options)) {
      $options = array();
    }
    $options = array_merge(self::default_options(), $options);

    return $options;
  } // get_options


  // get plugin's meta data
  static function get_meta() {
    $meta = get_option(UCP_META_KEY, array());

    if (!is_array($meta) || empty($meta)) {
      $meta['first_version'] = self::get_plugin_version();
      $meta['first_install'] = time();
      update_option(UCP_META_KEY, $meta);
    }

    return $meta;
  } // get_meta


  // fetch and display the construction page if it's enabled or preview requested
  static function display_construction_page() {
    $options = self::get_options();
    $request_uri = trailingslashit(strtolower(@parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));

    // just to be on the safe side
    if (defined('DOING_CRON') && DOING_CRON) {
      return false;
    }
    if (defined('DOING_AJAX') && DOING_AJAX) {
      return false;
    }
    if (defined('WP_CLI') && WP_CLI) {
      return false;
    }

    // some URLs have to be accessible at all times
    if ($request_uri == '/wp-admin/' ||
        $request_uri == '/feed/' ||
        $request_uri == '/feed/rss/' ||
        $request_uri == '/feed/rss2/' ||
        $request_uri == '/feed/rdf/' ||
        $request_uri == '/feed/atom/' ||
        $request_uri == '/admin/' ||
        $request_uri == '/wp-login.php') {
      return;
    }

    if (true == self::is_construction_mode_enabled(false) || (is_user_logged_in() && isset($_GET['ucp_preview']))) {
      header(self::wp_get_server_protocol() . ' 200 OK');
      if ($options['end_date'] && $options['end_date'] != '0000-00-00 00:00') {
        header('Retry-After: ' . date('D, d M Y H:i:s T', strtotime($options['end_date'])));
      } else {
        header('Retry-After: ' . DAY_IN_SECONDS);
      }

      $themes = self::get_themes();
      if (!empty($_GET['theme']) && substr($_GET['theme'], 5) != '_pro_' && !empty($themes[$_GET['theme']])) {
        $theme = $_GET['theme'];
      } else {
        $theme = $options['theme'];
      }

      echo self::get_template($theme);
      die();
    }
  } // display_construction_page


  // keeping compatibility with WP < v4.4
  static function wp_get_server_protocol() {
    $protocol = $_SERVER['SERVER_PROTOCOL'];
    if (!in_array($protocol, array('HTTP/1.1', 'HTTP/2', 'HTTP/2.0'))) {
        $protocol = 'HTTP/1.0';
    }

    return $protocol;
  } // wp_get_server_protocol


  // disables feed if necessary
  static function disable_feed() {
    if (true == self::is_construction_mode_enabled(false)) {
      echo '<?xml version="1.0" encoding="UTF-8" ?><status>Service unavailable.</status>';
      exit;
    }
  } // disable_feed


  // enqueue CSS and JS scripts in admin
  static function admin_enqueue_scripts($hook) {
    $surveys = get_option(UCP_SURVEYS_KEY);
    $meta = self::get_meta();
    $pointers = get_option(UCP_POINTERS_KEY);

    // auto remove welcome pointer when options are opened
    if (self::is_plugin_page()) {
      unset($pointers['welcome']);
      update_option(UCP_POINTERS_KEY, $pointers);
    }

    // survey is shown min 5min after install
    // DISABLED
    if (0 && empty($surveys['usage']) && time() - $meta['first_install'] > 300) {
      $open_survey = true;
    } else {
      $open_survey = false;
    }

    $promo = self::is_promo_active();
    if ($promo == 'welcome') {
      $countdown = $meta['first_install'] + HOUR_IN_SECONDS;
    } else {
      $countdown = 0;
    }

    $js_localize = array('undocumented_error' => __('An undocumented error has occured. Please refresh the page and try again.', 'under-construction-page'),
                         'plugin_name' => __('UnderConstructionPage', 'under-construction-page'),
                         'settings_url' => admin_url('options-general.php?page=ucp'),
                         'whitelisted_users_placeholder' => __('Select whitelisted user(s)', 'under-construction-page'),
                         'open_survey' => $open_survey,
                         'promo_countdown' => $countdown,
                         'is_activated' => UCP_license::is_activated(),
                         'dialog_upsell_title' => '<img alt="' . __('UnderConstructionPage PRO', 'under-construction-page') . '" title="' . __('UnderConstructionPage PRO', 'under-construction-page') . '" src="' . UCP_PLUGIN_URL . 'images/ucp_pro_logo_white.png' . '">',
                         'weglot_dialog_upsell_title' => '<img alt="' . __('Weglot', 'under-construction-page') . '" title="' . __('Weglot', 'under-construction-page') . '" src="' . UCP_PLUGIN_URL . 'images/weglot-logo-white.png' . '">',
                         'weglot_install_url' => add_query_arg(array('action' => 'install_weglot'), admin_url('admin.php')),
                         'nonce_dismiss_survey' => wp_create_nonce('ucp_dismiss_survey'),
                         'nonce_submit_survey' => wp_create_nonce('ucp_submit_survey'),
                         'nonce_submit_support_message' => wp_create_nonce('ucp_submit_support_message'),
                         'deactivate_confirmation' => __('Are you sure you want to deactivate UnderConstruction plugin?' . "\n" . 'If you are removing it because of a problem please contact our support. They will be more than happy to help.', 'under-construction-page'));

    if (self::is_plugin_page()) {
      remove_editor_styles();
      wp_enqueue_style('wp-jquery-ui-dialog');
      wp_enqueue_style('ucp-select2', UCP_PLUGIN_URL . 'css/select2.min.css', array(), self::$version);
      wp_enqueue_style('ucp-admin', UCP_PLUGIN_URL . 'css/ucp-admin.css', array(), self::$version);

      wp_enqueue_script('jquery-ui-tabs');
      wp_enqueue_script('jquery-ui-dialog');
      wp_enqueue_script('ucp-jquery-plugins', UCP_PLUGIN_URL . 'js/ucp-jquery-plugins.js', array('jquery'), self::$version, true);
      wp_enqueue_script('ucp-select2', UCP_PLUGIN_URL . 'js/select2.min.js', array(), self::$version, true);
      wp_enqueue_script('ucp-admin', UCP_PLUGIN_URL . 'js/ucp-admin.js', array('jquery'), self::$version, true);
      wp_localize_script('ucp-admin', 'ucp', $js_localize);

      // fix for agressive plugins
      wp_dequeue_style('uiStyleSheet');
      wp_dequeue_style('wpcufpnAdmin' );
      wp_dequeue_style('unifStyleSheet' );
      wp_dequeue_style('wpcufpn_codemirror');
      wp_dequeue_style('wpcufpn_codemirrorTheme');
      wp_dequeue_style('collapse-admin-css');
      wp_dequeue_style('jquery-ui-css');
      wp_dequeue_style('tribe-common-admin');
      wp_dequeue_style('file-manager__jquery-ui-css');
      wp_dequeue_style('file-manager__jquery-ui-css-theme');
      wp_dequeue_style('wpmegmaps-jqueryui');
      wp_dequeue_style('wp-botwatch-css');
    }

    if ($pointers) {
      $pointers['_nonce_dismiss_pointer'] = wp_create_nonce('ucp_dismiss_pointer');
      wp_enqueue_script('wp-pointer');
      wp_enqueue_script('ucp-pointers', plugins_url('js/ucp-admin-pointers.js', __FILE__), array('jquery'), self::$version, true);
      wp_enqueue_style('wp-pointer');
      wp_localize_script('wp-pointer', 'ucp_pointers', $pointers);
      wp_localize_script('wp-pointer', 'ucp', $js_localize);
    }
  } // admin_enqueue_scripts


  // permanently dismiss a pointer
  static function dismiss_pointer_ajax() {
    check_ajax_referer('ucp_dismiss_pointer');

    $pointers = get_option(UCP_POINTERS_KEY);
    $pointer = trim($_POST['pointer']);

    if (empty($pointers) || empty($pointers[$pointer])) {
      wp_send_json_error();
    }

    unset($pointers[$pointer]);
    update_option(UCP_POINTERS_KEY, $pointers);

    wp_send_json_success();
  } // dismiss_pointer_ajax


  // permanently dismiss a survey
  static function dismiss_survey_ajax() {
    check_ajax_referer('ucp_dismiss_survey');

    $surveys = get_option(UCP_SURVEYS_KEY, array());
    $survey = trim($_POST['survey']);

    $surveys[$survey] = -1;
    update_option(UCP_SURVEYS_KEY, $surveys);

    wp_send_json_success();
  } // dismiss_survey_ajax


  // send support message
  static function submit_support_message_ajax() {
    check_ajax_referer('ucp_submit_support_message');

    $options = self::get_options();

    $email = sanitize_text_field($_POST['support_email']);
    if (!is_email($email)) {
      wp_send_json_error(__('Please double-check your email address.', 'under-construction-page'));
    }

    $message = stripslashes(sanitize_text_field($_POST['support_message']));
    $subject = 'UCP Support';
    $body = $message;
    if (!empty($_POST['support_info'])) {
      $theme = wp_get_theme();
      $body .= "\r\n\r\nSite details:\r\n";
      $body .= '  WordPress version: ' . get_bloginfo('version') . "\r\n";
      $body .= '  UCP version: ' . self::$version . "\r\n";
      $body .= '  PHP version: ' . PHP_VERSION . "\r\n";
      $body .= '  Site URL: ' . get_bloginfo('url') . "\r\n";
      $body .= '  WordPress URL: ' . get_bloginfo('wpurl') . "\r\n";
      $body .= '  Theme: ' . $theme->get('Name') . ' v' . $theme->get('Version') . "\r\n";
      $body .= '  Options: ' . "\r\n" . serialize($options) . "\r\n";
    }
    $headers = 'From: ' . $email . "\r\n" . 'Reply-To: ' . $email;

    if (true === wp_mail('ucp@webfactoryltd.com', $subject, $body, $headers)) {
      wp_send_json_success();
    } else {
      wp_send_json_error(__('Something is not right with your wp_mail() function. Please email as at ucp@webfactoryltd.com.', 'under-construction-page'));
    }
  } // submit_support_message


  // submit survey
  static function submit_survey_ajax() {
    check_ajax_referer('ucp_submit_survey');

    $options = self::get_options();
    $meta = self::get_meta();
    $surveys = get_option(UCP_SURVEYS_KEY);

    $vars = wp_parse_args($_POST, array('survey' => '', 'answers' => '', 'custom_answer' => $options['theme'], 'emailme' => ''));
    $vars['answers'] = trim($vars['answers'], ',');
    $vars['custom_answer'] = trim(strip_tags($vars['custom_answer']));

    $vars['custom_answer'] .= '; ' . date('Y-m-d H:i:s', $meta['first_install']);
    $vars['custom_answer'] = trim($vars['custom_answer'], ' ;');

    if (empty($vars['survey']) || empty($vars['answers'])) {
      wp_send_json_error();
    }

    $request_params = array('sslverify' => false, 'timeout' => 15, 'redirection' => 2);
    $request_args = array('action' => 'submit_survey',
                          'survey' => $vars['survey'],
                          'email' => $vars['emailme'],
                          'answers' => $vars['answers'],
                          'custom_answer' => $vars['custom_answer'],
                          'first_version' => $meta['first_version'],
                          'version' => UCP::$version,
                          'codebase' => 'free',
                          'site' => get_home_url());

    $url = add_query_arg($request_args, self::$licensing_servers[0]);
    $response = wp_remote_get(esc_url_raw($url), $request_params);

    if (is_wp_error($response) || !wp_remote_retrieve_body($response)) {
      $url = add_query_arg($request_args, self::$licensing_servers[1]);
      $response = wp_remote_get(esc_url_raw($url), $request_params);
    }

    $surveys[$vars['survey']] = time();
    update_option(UCP_SURVEYS_KEY, $surveys);

    wp_send_json_success();
  } // submit_survey_ajax


  // encode email for frontend use
  static function encode_email($email) {
    $len = strlen($email);
    $out = '';

    for ($i = 0; $i < $len; $i++) {
      $out .= '&#'. ord($email[$i]) . ';';
    }

    return $out;
  } // encode_email


  // parse shortcode alike variables
  static function parse_vars($string) {
    $org_string = $string;

    $vars = array('site-title' => get_bloginfo('name'),
                  'site-tagline' => get_bloginfo('description'),
                  'site-description' => get_bloginfo('description'),
                  'site-url' => trailingslashit(get_home_url()),
                  'wp-url' => trailingslashit(get_site_url()),
                  'site-login-url' => get_site_url() . '/wp-login.php');

    foreach ($vars as $var_name => $var_value) {
      $var_name = '[' . $var_name . ']';
      $string = str_ireplace($var_name, $var_value, $string);
    }

    $string = apply_filters('ucp_parse_vars', $string, $org_string, $vars);

    return $string;
  } // parse_vars


  // generate HTML from social icons
  static function generate_social_icons($options, $template_id) {
    $out = '';

    if (!empty($options['social_facebook'])) {
      $out .= '<a title="Facebook" href="' . esc_attr($options['social_facebook']) . '" target="_blank"><i class="fa fa-facebook-square fa-3x"></i></a>';
    }
    if (!empty($options['social_twitter'])) {
      $out .= '<a title="Twitter" href="' . esc_attr($options['social_twitter']) . '" target="_blank"><i class="fa fa-twitter-square fa-3x"></i></a>';
    }
    if (!empty($options['social_linkedin'])) {
      $out .= '<a title="LinkedIn" href="' . esc_attr($options['social_linkedin']) . '" target="_blank"><i class="fa fa-linkedin-square fa-3x"></i></a>';
    }
    if (!empty($options['social_youtube'])) {
      $out .= '<a title="YouTube" href="' . esc_attr($options['social_youtube']) . '" target="_blank"><i class="fa fa-youtube-square fa-3x"></i></a>';
    }
    if (!empty($options['social_vimeo'])) {
      $out .= '<a title="Vimeo" href="' . esc_attr($options['social_vimeo']) . '" target="_blank"><i class="fa fa-vimeo-square fa-3x"></i></a>';
    }
    if (!empty($options['social_pinterest'])) {
      $out .= '<a title="Pinterest" href="' . esc_attr($options['social_pinterest']) . '" target="_blank"><i class="fa fa-pinterest-square fa-3x"></i></a>';
    }
    if (!empty($options['social_dribbble'])) {
      $out .= '<a title="Dribbble" href="' . esc_attr($options['social_dribbble']) . '" target="_blank"><i class="fa fa-dribbble fa-3x"></i></a>';
    }
    if (!empty($options['social_behance'])) {
      $out .= '<a title="Behance" href="' . esc_attr($options['social_behance']) . '" target="_blank"><i class="fa fa-behance-square fa-3x"></i></a>';
    }
    if (!empty($options['social_instagram'])) {
      $out .= '<a title="Instagram" href="' . esc_attr($options['social_instagram']) . '" target="_blank"><i class="fa fa-instagram fa-3x"></i></a>';
    }
    if (!empty($options['social_tumblr'])) {
      $out .= '<a title="Tumblr" href="' . esc_attr($options['social_tumblr']) . '" target="_blank"><i class="fa fa-tumblr-square fa-3x"></i></a>';
    }
    if (!empty($options['social_vk'])) {
      $out .= '<a title="VK" href="' . esc_attr($options['social_vk']) . '" target="_blank"><i class="fa fa-vk fa-3x"></i></a>';
    }
    if (!empty($options['social_skype'])) {
      $out .= '<a title="Skype" href="skype:' . esc_attr($options['social_skype']) . '?chat"><i class="fa fa-skype fa-3x"></i></a>';
    }
    if (!empty($options['social_whatsapp'])) {
      $out .= '<a title="WhatsApp" href="https://api.whatsapp.com/send?phone=' . str_replace('+', '', esc_attr($options['social_whatsapp'])) . '"><i class="fa fa-whatsapp fa-3x"></i></a>';
    }
    if (!empty($options['social_telegram'])) {
      $out .= '<a title="Telegram" href="' . esc_attr($options['social_telegram']) . '"><i class="fa fa-telegram fa-3x"></i></a>';
    }
    if (!empty($options['social_email'])) {
      $out .= '<a title="Email" href="mailto:' . esc_attr(self::encode_email($options['social_email'])) . '"><i class="fa fa-envelope fa-3x"></i></a>';
    }
    if (!empty($options['social_phone'])) {
      $out .= '<a title="Phone" href="tel:' . esc_attr($options['social_phone']) . '"><i class="fa fa-phone-square fa-3x"></i></a>';
    }

    return $out;
  } // generate_social_icons


  // shortcode for inserting things in header
  static function generate_head($options, $template_id) {
    $out = '';

    $out .= '<link rel="stylesheet" href="' . trailingslashit(UCP_PLUGIN_URL . 'themes/css') . 'bootstrap.min.css?v=' . self::$version . '" type="text/css">' . "\n";
    $out .= '<link rel="stylesheet" href="' . trailingslashit(UCP_PLUGIN_URL . 'themes/css') . 'common.css?v=' . self::$version . '" type="text/css">' . "\n";
    $out .= '<link rel="stylesheet" href="' . trailingslashit(UCP_PLUGIN_URL . 'themes/' . $template_id) . 'style.css?v=' . self::$version . '" type="text/css">' . "\n";
    $out .= '<link rel="stylesheet" href="' . trailingslashit(UCP_PLUGIN_URL . 'themes/css') . 'font-awesome.min.css?v=' . self::$version . '" type="text/css">' . "\n";

    $out .= '<link rel="icon" sizes="128x128" href="' . trailingslashit(UCP_PLUGIN_URL . 'themes/images') . 'favicon.png" />';

    if (self::is_weglot_setup()) {
      $out .= '<link rel="stylesheet" href="' . WEGLOT_URL_DIST . '/css/front-css.css?v=' . WEGLOT_VERSION . '" type="text/css">';
      $out .= '<script src="' . WEGLOT_URL_DIST . '/front-js.js?v=' . WEGLOT_VERSION . '"></script>';
    }

    if (!empty($options['ga_tracking_id'])) {
      $out .= "
      <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', '{$options['ga_tracking_id']}', 'auto');
        ga('send', 'pageview');
      </script>";
    }

    if (!empty($options['custom_css'])) {
      $out .= "\n" . '<style type="text/css">' . $options['custom_css'] . '</style>';
    }

    $out = apply_filters('ucp_head', $out, $options, $template_id);

    return trim($out);
  } // generate_head


  // shortcode for inserting things in footer
  static function generate_footer($options, $template_id) {
    $out = '';

    if ($options['linkback'] == '1') {
      $tmp = md5(get_site_url());
      if ($tmp[0] < '4') {
        $out .= '<p id="linkback">Create stunning <a href="' . self::generate_web_link('show-love-1')  . '" target="_blank">under construction pages for WordPress</a>. Completely free.</p>';
      } elseif ($tmp[0] < '8') {
        $out .= '<p id="linkback">Create a <a href="' . self::generate_web_link('show-love-2')  . '" target="_blank">free under construction page for WordPress</a> like this one in under a minute.</p>';
      } elseif ($tmp[0] < 'c') {
        $out .= '<p id="linkback">Join more than 400,000 happy people using the <a href="https://wordpress.org/plugins/under-construction-page/" target="_blank">free Under Construction Page plugin for WordPress</a>.</p>';
      } else {
        $out .= '<p id="linkback">Create free <a href="' . self::generate_web_link('show-love-3')  . '" target="_blank">under construction pages for WordPress</a>.</p>';
      }
    }

    if ($options['login_button'] == '1') {
      if (is_user_logged_in()) {
        $out .= '<div id="login-button" class="loggedin">';
        $out .= '<a title="' . __('Open WordPress admin', 'under-construction-page') . '" href="' . get_site_url() . '/wp-admin/"><i class="fa fa-wordpress fa-2x" aria-hidden="true"></i></a>';
      } else {
        $out .= '<div id="login-button" class="loggedout">';
        $out .= '<a title="' . __('Log in to WordPress admin', 'under-construction-page') . '" href="' . get_site_url() . '/wp-login.php"><i class="fa fa-wordpress fa-2x" aria-hidden="true"></i></a>';
      }
      $out .= '</div>';
    }

    $out = apply_filters('ucp_footer', $out, $options, $template_id);

    return $out;
  } // generate_footer


  // returnes parsed template
  static function get_template($template_id) {
    $vars = array();
    $options = self::get_options();

    $vars['version'] = self::$version;
    $vars['site-url'] = trailingslashit(get_home_url());
    $vars['wp-url'] = trailingslashit(get_site_url());
    $vars['theme-url'] = trailingslashit(UCP_PLUGIN_URL . 'themes/' . $template_id);
    $vars['theme-url-common'] = trailingslashit(UCP_PLUGIN_URL . 'themes');
    $vars['title'] = self::parse_vars($options['title']);
    $vars['generator'] = __('Free UnderConstructionPage plugin for WordPress', 'under-construction-page');
    $vars['heading1'] = self::parse_vars($options['heading1']);
    $vars['content'] = nl2br(self::parse_vars($options['content']));
    $vars['description'] = self::parse_vars($options['description']);
    $vars['social-icons'] = self::generate_social_icons($options, $template_id);
    $vars['head'] = self::generate_head($options, $template_id);
    $vars['footer'] = self::generate_footer($options, $template_id);

    $vars = apply_filters('ucp_get_template_vars', $vars, $template_id, $options);

    ob_start();
    require UCP_PLUGIN_DIR . 'themes/' . $template_id . '/index.php';
    $template = ob_get_clean();

    foreach ($vars as $var_name => $var_value) {
      $var_name = '[' . $var_name . ']';
      $template = str_ireplace($var_name, $var_value, $template);
    }

    $template = apply_filters('ucp_get_template', $template, $vars, $options);

    return $template;
  } // get_template


  // checks if construction mode is enabled for the current visitor
  static function is_construction_mode_enabled($settings_only = false) {
    $options = self::get_options();
    $current_user = wp_get_current_user();

    $override_status = apply_filters('ucp_is_construction_mode_enabled', null, $options);
    if (is_bool($override_status)) {
      return $override_status;
    }

    // just check if it's generally enabled
    if ($settings_only) {
      if ($options['status']) {
        return true;
      } else {
        return false;
      }
    } else {
      // check if enabled for current user
      if (!$options['status']) {
        return false;
      } elseif (self::user_has_role($options['whitelisted_roles'])) {
        return false;
      } elseif (in_array($current_user->ID, $options['whitelisted_users'])) {
        return false;
      } elseif (strlen($options['end_date']) === 16 && $options['end_date'] !== '0000-00-00 00:00' && $options['end_date'] < current_time('mysql')) {
        return false;
      } else {
        return true;
      }
    }
  } // is_construction_mode_enabled


  // check if user has the specified role
  static function user_has_role($roles) {
    $current_user = wp_get_current_user();

    if ($current_user->roles) {
      $user_role = $current_user->roles[0];
    } else {
      $user_role = 'guest';
    }

    return in_array($user_role, $roles);
  } // user_has_role


  // frontend notification when UCP is enabled but current user is whitelisted
  static function whitelisted_notice() {
    $notices = get_option(UCP_NOTICES_KEY);
    $dismiss_url = add_query_arg(array('action' => 'ucp_dismiss_notice', 'notice' => 'whitelisted', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));

    if (empty($notices['dismiss_whitelisted']) &&
        is_user_logged_in() &&
        self::is_construction_mode_enabled(true) &&
        !self::is_construction_mode_enabled(false))
        // keeping everything inline due to minimal CSS
        echo '<div style="background-color: #333; line-height: 140%; font-size: 14px; position: fixed; display: block; top: 50px; z-index: 99999; color: #fefefe; padding: 20px 35px 20px 20px; width: 500px; border: thin solid #fefefe; left: -1px;"><a style="color: #ea1919; font-weight: 900; text-decoration: none; position: absolute; top: 7px; right: 10px;" href="' . $dismiss_url . '" alt="Dismiss notice" onclick="window.location.href = \'' . $dismiss_url . '\'; return false;" title="Dismiss notice">X</a><b>' . __('<b>Under Construction Mode is enabled</b> but you are whitelisted so you see the normal site.', 'under-construction-page') . '<br><a href="' . get_home_url() . '/?ucp_preview" style="text-decoration: underline; color: #fefefe;">' . __('Preview UnderConstructionPage', 'under-construction-page') . '</a><br><a href="' . admin_url('options-general.php?page=ucp') . '" style="text-decoration: underline; color: #fefefe;">' . __('Configure UnderConstructionPage', 'under-construction-page') . '</a></div>';
  } // whitelisted_notification


  // displays various notices in admin header
  static function admin_notices() {
    $notices = get_option(UCP_NOTICES_KEY);
    $options = self::get_options();
    $meta = self::get_meta();
    $current_user = wp_get_current_user();
    $shown = false;
    $promo = self::is_promo_active();

    $name = '';
    if (!empty($current_user->user_firstname)) {
      $name = ' ' . $current_user->user_firstname;
    }

    // pro activated - update
    if (self::is_plugin_page() && UCP_license::is_activated()) {
      echo '<div id="ucp_update_pro" class="notice-error notice">';
      echo '<p class="center">Thank you for purchasing UnderConstructionPage PRO! <b>Your license has been verified and activated.</b><br>To start using the PRO version, please follow these steps:</p>';
      echo '<ol>';
      echo '<li><a href="https://underconstructionpage.com/pro-download/" target="_blank">Download</a> the latest version of the PRO plugin.</li>';
      echo '<li>Go to <a href="' . admin_url('plugin-install.php') . '">Plugins - Add New - Upload Plugin</a> and upload the ZIP you just downloaded.</li>';
      echo '<li>If asked to replace (overwrite) the free version - confirm it.</li>';
      echo '<li>Activate the plugin.</li>';
      echo '<li>That\'s it, no more steps.</li>';
      echo '</ol>';
      echo '</div>';
      $shown = true;

      return;
    }

    // ask for rating; disabled
    if (false && empty($notices['dismiss_rate']) &&
        (time() - $meta['first_install']) > (DAY_IN_SECONDS * 1.0)) {
      $rate_url = 'https://wordpress.org/support/plugin/under-construction-page/reviews/?filter=5&rate=5#new-post';
      $dismiss_url = add_query_arg(array('action' => 'ucp_dismiss_notice', 'notice' => 'rate', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));

      echo '<div id="ucp_rate_notice" class="notice-info notice"><p>Hi' . $name . '!<br>We saw you\'ve been using the <b class="ucp-logo" style="font-weight: bold;">UnderConstructionPage</b> plugin for a few days (that\'s awesome!) and wanted to ask for your help to <b>make the plugin better</b>.<br>We just need a minute of your time to rate the plugin. It helps us out a lot!';

      echo '<br><a target="_blank" href="' . esc_url($rate_url) . '" style="vertical-align: baseline; margin-top: 15px;" class="button-primary">' . __('Help make the plugin better by rating it', 'under-construction-page') . '</a>';
      echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . esc_url($dismiss_url) . '">' . __('I\'ve already rated the plugin', 'under-construction-page') . '</a>';
      echo '<br><br><b>' . __('Thank you very much! The UCP team', 'under-construction-page') . '</b>';
      echo '</p></div>';
      $shown = true;
    }

    // end date in past
    if (self::is_plugin_page() && self::is_construction_mode_enabled(true) && !empty($options['end_date']) && $options['end_date'] != '0000-00-00 00:00' && $options['end_date'] < current_time('mysql')) {
      echo '<div id="ucp_end_date_notice" class="notice-error notice"><p>Under construction mode is enabled but the <a href="#end_date" class="change_tab" data-tab="0">end date</a> is set to a past date so the <b>under construction page will not be shown</b>. Either move the <a href="#end_date" class="change_tab" data-tab="0">end date</a> to a future date or disable it.</p></div>';
      $shown = true;
    }

    // ask for translation
    // disabled till further notice
    if (false && self::is_plugin_page() &&
        empty($notices['dismiss_translate']) &&
        (time() - $meta['first_install']) > 1) {
      $translate_url = self::generate_web_link('translate-notification', 'translate-the-plugin/');
      $dismiss_url = add_query_arg(array('action' => 'ucp_dismiss_notice', 'notice' => 'translate', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));

      echo '<div id="ucp_rate_notice" class="notice-info notice"><p>Hi' . $name . ',<br>Help us translate UCP into your language and <b>get a PRO license for free</b>!<br>We want to make <b class="ucp-logo" style="font-weight: bold;">UnderConstructionPage</b> accessible to as many users as possible by translating it into their language. And we need your help!';

      echo '<br><a target="_blank" href="' . esc_url($translate_url) . '" style="vertical-align: baseline; margin-top: 15px;" class="button-primary">' . __('Translate UCP into your language &amp; get a PRO license for free', 'under-construction-page') . '</a>';
      echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . esc_url($dismiss_url) . '">' . __('I\'m not interested (remove this notice)', 'under-construction-page') . '</a>';
      echo '</p></div>';
      $shown = true;
    }

    // promo for new users
    if (self::is_plugin_page() &&
        empty($notices['dismiss_welcome']) &&
        !$shown && $promo == 'welcome') {
      $dismiss_url = add_query_arg(array('action' => 'ucp_dismiss_notice', 'notice' => 'welcome', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));

      echo '<div id="ucp_rate_notice" class="notice-info notice"><p>Hi' . $name . ',<br>';
      echo 'We have a <a class="open-ucp-upsell" data-pro-ad="notification-welcome-text" href="#">special time-sensitive offer</a> available just for another <b class="ucp-countdown">59min</b>! A <b>20% DISCOUNT</b> on our most popular lifetime licenses!<br>No nonsense! Pay once and use the plugin forever. <a class="open-ucp-upsell" data-pro-ad="notification-welcome-text2" href="#">Get</a> more than 50+ extra features, 250+ premium themes and over two million professional images.</p>';

      echo '<a href="#" class="button-primary open-ucp-upsell" data-pro-ad="notification-welcome-button">Upgrade to PRO now with a SPECIAL 20% WELCOME DISCOUNT</a>';
      echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . esc_url($dismiss_url) . '"><small>' . __('I\'m not interested (remove this notice)', 'under-construction-page') . '</small></a>';
      echo '</p></div>';
      $shown = true;
    }

    // promo for old users
    if (self::is_plugin_page() &&
        empty($notices['dismiss_olduser']) &&
        !$shown && $promo == 'olduser') {
      $dismiss_url = add_query_arg(array('action' => 'ucp_dismiss_notice', 'notice' => 'olduser', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));

      echo '<div id="ucp_rate_notice" class="notice-info notice"><p>Hi' . $name . ',<br>';
      echo 'We have a <a class="open-ucp-upsell" data-pro-ad="notification-olduser-text" href="#">special offer</a> only for <b>users like you</b> who\'ve been using the UnderConstructionPage for a longer period of time: a <b>special DISCOUNT</b> on our most popular lifetime licenses!<br>No nonsense! Pay once and use the plugin forever.<br><a class="open-ucp-upsell" data-pro-ad="notification-olduser-text" href="#">Upgrade now</a> to <b>PRO</b> &amp; get more than 50+ extra features, 220+ premium themes and over two million HD images.</p>';

      echo '<a href="#" class="button-primary open-ucp-upsell" data-pro-ad="notification-olduser-button">Upgrade to PRO now with a SPECIAL DISCOUNT</a>';
      echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . esc_url($dismiss_url) . '"><small>' . __('I\'m not interested (remove this notice)', 'under-construction-page') . '</small></a>';
      echo '</p></div>';
      $shown = true;
    }
  } // notices


  // handle dismiss button for notices
  static function dismiss_notice() {
    if (empty($_GET['notice'])) {
      wp_safe_redirect(admin_url());
      exit;
    }

    $notices = get_option(UCP_NOTICES_KEY, array());

    if ($_GET['notice'] == 'rate') {
      $notices['dismiss_rate'] = true;
    } elseif ($_GET['notice'] == 'translate') {
      $notices['dismiss_translate'] = true;
    } elseif ($_GET['notice'] == 'whitelisted') {
      $notices['dismiss_whitelisted'] = true;
    } elseif ($_GET['notice'] == 'olduser') {
      $notices['dismiss_olduser'] = true;
    } elseif ($_GET['notice'] == 'welcome') {
      $notices['dismiss_welcome'] = true;
    } else {
      wp_safe_redirect(admin_url());
      exit;
    }

    update_option(UCP_NOTICES_KEY, $notices);

    if (!empty($_GET['redirect'])) {
      wp_safe_redirect($_GET['redirect']);
    } else {
      wp_safe_redirect(admin_url());
    }

    exit;
  } // dismiss_notice


  // reset all settings to default values
  static function reset_settings() {
    check_admin_referer('ucp_reset_settings');

    if (false === current_user_can('administrator')) {
      wp_safe_redirect(admin_url());
      exit;
    }

    $options = self::default_options();
    update_option(UCP_OPTIONS_KEY, $options);

    if (!empty($_GET['redirect'])) {
      wp_safe_redirect($_GET['redirect']);
    } else {
      wp_safe_redirect(admin_url());
    }

    exit;
  } // reset_settings


  // change status via admin bar
  static function change_status() {
    check_admin_referer('ucp_change_status');

    if (false === current_user_can('administrator') || empty($_GET['new_status'])) {
      wp_safe_redirect(admin_url());
      exit;
    }

    $options = self::get_options();

    if ($_GET['new_status'] == 'enabled') {
      $options['status'] = '1';
    } else {
      $options['status'] = '0';
    }

    update_option(UCP_OPTIONS_KEY, $options);

    if (!empty($_GET['redirect'])) {
      wp_safe_redirect($_GET['redirect']);
    } else {
      wp_safe_redirect(admin_url());
    }

    exit;
  } // change_status


  static function admin_bar_style() {
    // admin bar has to be anabled, user an admin and custom filter true
    if (false === is_admin_bar_showing() || false === current_user_can('administrator') || false === apply_filters('ucp_show_admin_bar', true)) {
      return;
    }

    // no sense in loading a new CSS file for 2 lines of CSS
    $custom_css = '<style type="text/css">#wpadminbar ul li#wp-admin-bar-ucp-info { padding: 5px 0; } #wpadminbar ul li#wp-admin-bar-ucp-settings, #wpadminbar ul li#wp-admin-bar-ucp-status { padding-bottom: 2px; } #wpadminbar i.ucp-status-dot { font-size: 17px; margin-top: -7px; color: #02ca02; height: 17px; display: inline-block; } #wpadminbar i.ucp-status-dot-enabled { color: #87c826; } #wpadminbar i.ucp-status-dot-disabled { color: #ea1919; } #wpadminbar #ucp-status-wrapper { display: inline; border: 1px solid rgba(240,245,250,.7); padding: 0; margin: 0 0 0 5px; background: rgb(35, 40, 45); } #wpadminbar .ucp-status-btn { padding: 0 7px; color: #fff; } #wpadminbar #ucp-status-wrapper.off #ucp-status-off { background: #ea1919;} #wpadminbar #ucp-status-wrapper.on #ucp-status-on { background: #66b317; }#wp-admin-bar-under-construction-page img.logo { height: 17px; margin-bottom: 4px; padding-right: 3px; } body.wp-admin #wp-admin-bar-under-construction-page img.logo { margin-bottom: -4px; }</style>';

    echo $custom_css;
  } // admin_bar_style


  // add admin bar menu and status
  static function admin_bar() {
    global $wp_admin_bar;

    // only show to admins
    if (false === current_user_can('administrator') || false === apply_filters('ucp_show_admin_bar', true)) {
      return;
    }

    if (self::is_construction_mode_enabled(true)) {
      $main_label = '<img style="height: 17px; margin-bottom: -4px; padding-right: 3px;" src="' . UCP_PLUGIN_URL . 'images/ucp_icon.png" alt="' . __('Under construction mode is enabled', 'under-construction-page') . '" title="' . __('Under construction mode is enabled', 'under-construction-page') . '"> <span class="ab-label">' . __('UnderConstruction', 'under-construction-page') . ' <i class="ucp-status-dot ucp-status-dot-enabled">&#9679;</i></span>';
      $class = 'ucp-enabled';
      $action_url = add_query_arg(array('action' => 'ucp_change_status', 'new_status' => 'disabled', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));
      $action_url = wp_nonce_url($action_url, 'ucp_change_status');
      $action = __('Under Construction Mode', 'under-construction-page');
      $action .= '<a href="' . $action_url . '" id="ucp-status-wrapper" class="on"><span id="ucp-status-off" class="ucp-status-btn">OFF</span><span id="ucp-status-on" class="ucp-status-btn">ON</span></a>';
    } else {
      $main_label = '<img style="height: 17px; margin-bottom: -4px; padding-right: 3px;" src="' . UCP_PLUGIN_URL . 'images/ucp_icon.png" alt="' . __('Under construction mode is disabled', 'under-construction-page') . '" title="' . __('Under construction mode is disabled', 'under-construction-page') . '"> <span class="ab-label">' . __('UnderConstruction', 'under-construction-page') . ' <i class="ucp-status-dot ucp-status-dot-disabled">&#9679;</i></span>';
      $class = 'ucp-disabled';
      $action_url = add_query_arg(array('action' => 'ucp_change_status', 'new_status' => 'enabled', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));
      $action_url = wp_nonce_url($action_url, 'ucp_change_status');
      $action = __('Under Construction Mode', 'under-construction-page');
      $action .= '<a href="' . $action_url . '" id="ucp-status-wrapper" class="off"><span id="ucp-status-off" class="ucp-status-btn">OFF</span><span id="ucp-status-on" class="ucp-status-btn">ON</span></a>';
    }

    $wp_admin_bar->add_menu(array(
      'parent' => '',
      'id'     => 'under-construction-page',
      'title'  => $main_label,
      'href'   => admin_url('options-general.php?page=ucp'),
      'meta'   => array('class' => $class)
    ));
    $wp_admin_bar->add_node( array(
      'id'    => 'ucp-status',
      'title' => $action,
      'href'  => false,
      'parent'=> 'under-construction-page'
    ));
    $wp_admin_bar->add_node( array(
      'id'     => 'ucp-preview',
      'title'  => __('Preview', 'under-construction-page'),
      'meta'   => array('target' => 'blank'),
      'href'   => get_home_url() . '/?ucp_preview',
      'parent' => 'under-construction-page'
    ));
    $wp_admin_bar->add_node( array(
      'id'     => 'ucp-settings',
      'title'  => __('Settings', 'under-construction-page'),
      'href'   => admin_url('options-general.php?page=ucp'),
      'parent' => 'under-construction-page'
    ));
  } // admin_bar


  // show under construction notice on WP login form
  static function login_message($message) {
    if (self::is_construction_mode_enabled(true)) {
      $message .= '<div class="message">' . __('Under Construction Mode is <b>enabled</b>.', 'under-construction-page') . '</div>';
    }

    return $message;
  } // login_notice


  // add settings link to plugins page
  static function plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=ucp') . '" title="' . __('UnderConstruction Settings', 'under-construction-page') . '">' . __('Settings', 'under-construction-page') . '</a>';
    $pro_link = '<a target="_blank" href="' . self::generate_web_link('plugins-table-left') . '" title="' . __('Get PRO', 'under-construction-page') . '">' . __('Go <b>PRO</b>', 'under-construction-page') . '</a>';

    array_unshift($links, $pro_link);
    array_unshift($links, $settings_link);

    return $links;
  } // plugin_action_links


  // add links to plugin's description in plugins table
  static function plugin_meta_links($links, $file) {
    $support_link = '<a target="_blank" href="https://wordpress.org/support/plugin/under-construction-page" title="' . __('Get help', 'under-construction-page') . '">' . __('Support', 'under-construction-page') . '</a>';
    $pro_link = '<a target="_blank" href="' . self::generate_web_link('plugins-table-right') . '" title="' . __('Get PRO', 'under-construction-page') . '">' . __('Get the <b>PRO</b> version', 'under-construction-page') . '</a>';


    if ($file == plugin_basename(__FILE__)) {
      $links[] = $support_link;
      $links[] = $pro_link;
    }

    return $links;
  } // plugin_meta_links


  // additional powered by text in admin footer; only on UCP page
  static function admin_footer_text($text) {
    if (!self::is_plugin_page()) {
      return $text;
    }

    $text = '<i><a href="' . self::generate_web_link('admin-footer') . '" title="' . __('Visit UCP\'s site for more info', 'under-construction-page') . '" target="_blank">' . __('UnderConstructionPage', 'under-construction-page') . '</a> v' . self::$version . ' by <a href="https://www.webfactoryltd.com/" title="' . __('Visit our site to get more great plugins', 'under-construction-page') . '" target="_blank">' . __('WebFactory Ltd', 'under-construction-page') . '</a>. Please <a target="_blank" href="https://wordpress.org/support/plugin/under-construction-page/reviews/#new-post" title="Rate the plugin">rate the plugin <span>★★★★★</span></a> to help us spread the word. Thank you!</i>';

    return $text;
  } // admin_footer_text


  // fix for opening the plugin install modal
  static function admin_footer() {
    if (empty($_GET['fix-install-button']) || empty($_GET['tab']) || $_GET['tab'] != 'plugin-information') {
      return;
    }

    echo '<script>';
    echo "jQuery('#plugin_install_from_iframe').on('click', function() { window.location.href = jQuery(this).attr('href'); return false;});";
    echo '</script>';
  } // admin_footer


  // test if we're on plugin's page
  static function is_plugin_page() {
    $current_screen = get_current_screen();

    if ($current_screen->id == 'settings_page_ucp') {
      return true;
    } else {
      return false;
    }
  } // is_plugin_page


  // create the admin menu item
  static function admin_menu() {
    add_options_page(__('UnderConstruction', 'under-construction-page'), __('UnderConstruction', 'under-construction-page'), 'manage_options', 'ucp', array(__CLASS__, 'main_page'));
  } // admin_menu


  // all settings are saved in one option
  static function register_settings() {
    register_setting(UCP_OPTIONS_KEY, UCP_OPTIONS_KEY, array(__CLASS__, 'sanitize_settings'));
  } // register_settings


  // set default settings
  static function default_options() {
    $defaults = array('status' => '0',
                      'license_key' => '',
                      'license_active' => false,
                      'license_expires' => '1900-01-01',
                      'license_type' => '',
                      'end_date' => '',
                      'ga_tracking_id' => '',
                      'theme' => 'mad_designer',
                      'custom_css' => '',
                      'title' => '[site-title] is under construction',
                      'description' => '[site-tagline]',
                      'heading1' => __('Sorry, we\'re doing some work on the site', 'under-construction-page'),
                      'content' => __('Thank you for being patient. We are doing some work on the site and will be back shortly.', 'under-construction-page'),
                      'social_facebook' => '',
                      'social_twitter' => '',
                      'social_linkedin' => '',
                      'social_youtube' => '',
                      'social_vimeo' => '',
                      'social_pinterest' => '',
                      'social_dribbble' => '',
                      'social_behance' => '',
                      'social_instagram' => '',
                      'social_tumblr' => '',
                      'social_vk' => '',
                      'social_email' => '',
                      'social_phone' => '',
                      'social_skype' => '',
                      'social_telegram' => '',
                      'social_whatsapp' => '',
                      'login_button' => '1',
                      'linkback' => '0',
                      'whitelisted_roles' => array('administrator'),
                      'whitelisted_users' => array()
                      );

    return $defaults;
  } // default_options


  // sanitize settings on save
  static function sanitize_settings($options) {
    $old_options = self::get_options();

    foreach ($options as $key => $value) {
      switch ($key) {
        case 'title':
        case 'description':
          $options[$key] = trim(strip_tags($value));
        break;
        case 'heading1':
        case 'content':
          $options[$key] = trim(wp_kses($value, wp_kses_allowed_html('post')));
        break;
        case 'custom_css':
        case 'social_facebook':
        case 'social_twitter':
        case 'social_linkedin':
        case 'social_youtube':
        case 'social_vimeo':
        case 'social_pinterest':
        case 'social_dribbble':
        case 'social_behance':
        case 'social_instagram':
        case 'social_tumblr':
        case 'social_vk':
        case 'social_email':
        case 'social_phone':
        case 'social_telegram':
        case 'social_whatsapp':
        case 'license_key':
          $options[$key] = trim(strip_tags($value));
        break;
        case 'ga_tracking_id':
          $options[$key] = substr(strtoupper(trim($value)), 0, 15);
        break;
        case 'end_date':
          $options[$key] = substr(trim($value), 0, 16);
        break;
      } // switch
    } // foreach

    $options['title'] = strip_tags($options['title']);
    $options['description'] = strip_tags($options['description']);
    $options['heading1'] = strip_tags($options['heading1'], '<br><a><b><strong><i><em><p><del><img>');
    $options['content'] = strip_tags($options['content'], '<br><a><b><strong><i><em><p><del><img><ul><ol><li><blockquote><ins><code><hr><h2><h3><h4><span><div><iframe>');

    $options['whitelisted_roles'] = empty($options['whitelisted_roles'])? array(): $options['whitelisted_roles'];
    $options['whitelisted_users'] = empty($options['whitelisted_users'])? array(): $options['whitelisted_users'];
    $options = self::check_var_isset($options, array('status' => 0, 'linkback' => 0, 'login_button' => 0));

    if (empty($options['end_date_toggle'])) {
      $options['end_date'] = '';
    }
    if ($options['end_date'] == '0000-00-00 00:00') {
      $options['end_date'] = '';
    }
    unset($options['end_date_toggle']);

    if (empty($options['ga_tracking_toggle'])) {
      $options['ga_tracking_id'] = '';
    }
    if (!empty($options['ga_tracking_id']) && preg_match('/^UA-\d{3,}-\d{1,3}$/', $options['ga_tracking_id']) === 0) {
      add_settings_error('ucp', 'ga_tracking_id', __('Please enter a valid Google Analytics Tracking ID or disable tracking.', 'under-construction-page'));
    }
    unset($options['ga_tracking_toggle']);

    if (!empty($_POST['license-submit'])) {
      if (empty($options['license_key'])) {
        $options['license_type'] = '';
        $options['license_expires'] = '1900-01-01';
        $options['license_active'] = false;
        $options['license_key'] = '';
        add_settings_error(UCP_OPTIONS_KEY, 'license_key', __('License key saved.', 'under-construction-page'), 'updated');
      } else {
        $tmp = UCP_license::validate_license_key($options['license_key']);
        if ($tmp['success']) {
          $options['license_type'] = $tmp['license_type'];
          $options['license_expires'] = $tmp['license_expires'];
          $options['license_active'] = $tmp['license_active'];
          if ($tmp['license_active']) {
            add_settings_error(UCP_OPTIONS_KEY, 'license_key', __('License key saved and activated!', 'under-construction-page'), 'updated');
          } else {
            add_settings_error(UCP_OPTIONS_KEY, 'license_key', 'License not active. ' . $tmp['error'], 'error');
          }
        } else {
          add_settings_error(UCP_OPTIONS_KEY, 'license_key', 'Unable to contact licensing server. Please try again in a few moments.', 'error');
        }
      }
    } // update license

    // empty cache in 3rd party plugins
    if ($options != $old_options) {
      $notices = get_option(UCP_NOTICES_KEY);
      unset($notices['dismiss_whitelisted']);
      update_option(UCP_NOTICES_KEY, $notices);
      self::empty_cache();
    }

    return array_merge($old_options, $options);
  } // sanitize_settings


  static function empty_cache() {
    wp_cache_flush();
    if (function_exists('w3tc_flush_all')) {
      w3tc_flush_all();
    }
    if (function_exists('wp_cache_clear_cache')) {
      wp_cache_clear_cache();
    }
    if (method_exists('LiteSpeed_Cache_API', 'purge_all')) {
      LiteSpeed_Cache_API::purge_all();
    }
    if (class_exists('Endurance_Page_Cache')) {
      $epc = new Endurance_Page_Cache;
      $epc->purge_all();
    }
    if (class_exists('SG_CachePress_Supercacher') && method_exists('SG_CachePress_Supercacher', 'purge_cache')) {
      SG_CachePress_Supercacher::purge_cache(true);
    }
    if (class_exists('SiteGround_Optimizer\Supercacher\Supercacher')) {
      SiteGround_Optimizer\Supercacher\Supercacher::purge_cache();
    }
    if (isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')) {
      $GLOBALS['wp_fastest_cache']->deleteCache(true);
    }
    if (is_callable(array('Swift_Performance_Cache', 'clear_all_cache'))) {
      Swift_Performance_Cache::clear_all_cache();
    }
    if (is_callable(array('Hummingbird\WP_Hummingbird', 'flush_cache'))) {
      Hummingbird\WP_Hummingbird::flush_cache(true, false);
    }
    if (function_exists('rocket_clean_domain')) {
      rocket_clean_domain();
    }
    do_action('cache_enabler_clear_complete_cache');
  } // empty_cache


  // checkbox helper function
  static function checked($value, $current, $echo = false) {
    $out = '';

    if (!is_array($current)) {
      $current = (array) $current;
    }

    if (in_array($value, $current)) {
      $out = ' checked="checked" ';
    }

    if ($echo) {
      echo $out;
    } else {
      return $out;
    }
  } // checked


  // helper function for saving options, mostly checkboxes
  static function check_var_isset($values, $variables) {
    foreach ($variables as $key => $value) {
      if (!isset($values[$key])) {
        $values[$key] = $value;
      }
    }

    return $values;
  } // check_var_isset


  // helper function for creating dropdowns
  static function create_select_options($options, $selected = null, $output = true) {
    $out = "\n";

    if(!is_array($selected)) {
      $selected = array($selected);
    }

    foreach ($options as $tmp) {
      $data = '';
      if (isset($tmp['disabled'])) {
        $data .= ' disabled="disabled" ';
      }
      if (in_array($tmp['val'], $selected)) {
        $out .= "<option selected=\"selected\" value=\"{$tmp['val']}\"{$data}>{$tmp['label']}&nbsp;</option>\n";
      } else {
        $out .= "<option value=\"{$tmp['val']}\"{$data}>{$tmp['label']}&nbsp;</option>\n";
      }
    } // foreach

    if ($output) {
      echo $out;
    } else {
      return $out;
    }
  } // create_select_options


  // helper function to generate tagged buy links
  static function generate_web_link($placement = '', $page = '/', $params = array(), $anchor = '') {
    $base_url = 'https://underconstructionpage.com';

    if ('/' != $page) {
      $page = '/' . trim($page, '/') . '/';
    }
    if ($page == '//') {
      $page = '/';
    }

    if ($placement) {
      $placement = trim($placement, '-');
      $placement = '-' . $placement;
    }

    $parts = array_merge(array('ref' => 'ucp-free' . $placement), $params);

    if (!empty($anchor)) {
      $anchor = '#' . trim($anchor, '#');
    }

    $out = $base_url . $page . '?' . http_build_query($parts, '', '&amp;') . $anchor;

    return $out;
  } // generate_web_link


  // first, main tab content
  static function tab_main() {
    $options = self::get_options();
    $default_options = self::default_options();

    echo '<div class="ucp-tab-content">';
    echo '<table class="form-table">';

    echo '<tr valign="top">
    <th scope="row"><label for="status">' . __('Under Construction Mode', 'under-construction-page') . '</label></th>
    <td>';

    echo '<div class="toggle-wrapper" id="main-status">
      <input type="checkbox" id="status" ' . self::checked(1, $options['status']) . ' type="checkbox" value="1" name="' . UCP_OPTIONS_KEY . '[status]">
      <label for="status" class="toggle"><span class="toggle_handler"></span></label>
    </div>';

    echo '<p class="description">' . __('By enabling construction mode users will not be able to access the site\'s content. They will only see the under construction page. To configure exceptions set <a class="change_tab" data-tab="3" href="#whitelisted-roles">whitelisted user roles</a>.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="search_engines">' . __('Prevent Search Engines from Indexing the Temporary Site', 'under-construction-page') . '</label></th>
    <td>';
    echo '<div class="toggle-wrapper">
      <input type="checkbox" id="search_engines" type="checkbox" value="1" class="skip-save open-ucp-upsell">
      <label for="search_engines" class="toggle"><span class="toggle_handler"></span></label>
    </div>';

    echo '<p class="description">' . __('While performing maintenance or having any temporary content displayed, it is favorable for SEO to prevent search engines from indexing the temporaray site. This is a <a href="#" class="open-ucp-upsell" data-pro-ad="search_engines_text">PRO feature</a>.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="end_date_toggle">' . __('Automatic End Date &amp; Time', 'under-construction-page') . '</label></th>
    <td>';
    echo '<div class="toggle-wrapper">
      <input type="checkbox" id="end_date_toggle" ' . self::checked(1, (empty($options['end_date']) || $options['end_date'] == '0000-00-00 00:00')? 0: 1) . ' type="checkbox" value="1" name="' . UCP_OPTIONS_KEY . '[end_date_toggle]">
      <label for="end_date_toggle" class="toggle"><span class="toggle_handler"></span></label>
    </div>';
    echo '<div id="end_date_wrapper"><input id="end_date" type="text" class="datepicker" name="' . UCP_OPTIONS_KEY . '[end_date]" value="' . esc_attr($options['end_date']) . '" placeholder="yyyy-mm-dd hh:mm"><span title="' . __('Open date & time picker', 'under-construction-page') . '" alt="' . __('Open date & time picker', 'under-construction-page') . '" class="show-datepicker dashicons dashicons-calendar-alt"></span>';
    echo '<p class="description">' . __('If enabled, construction mode will automatically stop showing on the selected date.
    This option will not "auto-enable" construction mode. Status has to be set to "On".', 'under-construction-page') . '</p></div>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="ga_tracking_id_toggle">' . __('Google Analytics Tracking', 'under-construction-page') . '</label></th>
    <td>';
    echo '<div class="toggle-wrapper">
      <input type="checkbox" id="ga_tracking_id_toggle" ' . self::checked(1, empty($options['ga_tracking_id'])? 0: 1) . ' type="checkbox" value="1" name="' . UCP_OPTIONS_KEY . '[ga_tracking_toggle]">
      <label for="ga_tracking_id_toggle" class="toggle"><span class="toggle_handler"></span></label>
    </div>';
    echo '<div id="ga_tracking_id_wrapper"><input id="ga_tracking_id" type="text" class="code" name="' . UCP_OPTIONS_KEY . '[ga_tracking_id]" value="' . esc_attr($options['ga_tracking_id']) . '" placeholder="UA-xxxxxx-xx">';
    echo '<p class="description">' . __('Enter the unique tracking ID found in your GA tracking profile settings to track visits to pages.', 'under-construction-page') . '</p></div>';
    echo '</td></tr>';

    $reset_url = add_query_arg(array('action' => 'ucp_reset_settings', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));
    $reset_url = wp_nonce_url($reset_url, 'ucp_reset_settings');
    echo '<tr valign="top">
    <th scope="row"><label for="">' . __('Reset Settings', 'under-construction-page') . '</label></th>
    <td>';
    echo '<a href="' . $reset_url . '" class="button button-secondary reset-settings">' . __('Reset all settings to default values', 'under-construction-page') . '</a>';
    echo '<p class="description">' . __('By resetting all settings to their default values any customizations you have done will be lost. There is no undo.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '</table>';
    echo '</div>';

    self::footer_buttons();
  } // tab_main


  static function tab_content() {
    global $wpdb;
    $options = self::get_options();
    $default_options = self::default_options();

    echo '<div class="ucp-tab-content">';

    if (!self::is_weglot_active()) {
      echo '<div class="ucp-notice-small"><p><b>NEW</b> - Make your under construction page and your website <b>multilingual</b> with the Weglot Translate plugin.<br>To enable this feature, <a href="#" class="open-weglot-upsell">install the Weglot Translate freemium plugin</a>.';
      echo '</p></div>';
    }

    echo '<table class="form-table">';

  if (self::is_weglot_active()) {
    echo '<tr id="weglot-settings">';
    echo '<th><label for="weglot_lang">Multilingual Support</label></th>';
    echo '<td>';


    if (self::is_weglot_setup()) {
      $tmp = '';
      $active_languages = weglot_get_destination_language();
      $languages = weglot_get_languages_available();
      $original_language = weglot_get_original_language();

      echo '<p>Your under construction page is currently available in the following languages.<br>To add more languages and configure translations open <a href="' . admin_url('admin.php?page=weglot-settings') . '">Weglot settings</a>.</p>';
      echo '<ul class="ucp-list">';
      foreach ($languages as $language) {
        if ($language->getIso639() == $original_language) {
          $tmp = '<li>' . esc_html($language->getEnglishName()) . ' - original language</li>' . $tmp;
        }
        if (in_array($language->getIso639(), $active_languages, true )) {
          $tmp .= '<li>' . esc_html($language->getLocalName()) . '</li>';
        }
      } // foreach language
      echo $tmp;
      echo '</ul>';
    } else {
      echo '<p>Your under construction page is currently not translated.<br>Open <a href="' . admin_url('admin.php?page=weglot-settings') . '">Weglot settings</a> to select languages you want to translate to.</p>';
    }
    echo '</td>';
    echo '</tr>';
    } else {
      echo '<tr>';
      echo '<th><label for="weglot_support">Multilingual Support</label></th>';
      echo '<td>';
      echo '<div class="toggle-wrapper">
      <input type="checkbox" id="weglot_support" type="checkbox" value="1" class="skip-save open-weglot-upsell">
      <label for="weglot_support" class="toggle"><span class="toggle_handler"></span></label></div>';
      echo '<p class="description">55% of online visitors prefer to browse in their mother tongue. If you have an audience speaking multiple languages, making your website multilingual is a must-have. To instantly translate your website and your under construction page, <a href="#" class="open-weglot-upsell">install the Weglot plugin</a> (free plan and free trial available). It seamlessly integrates with UCP and is compatible with all themes &amp; plugins.</p>';
      echo '</td>';
      echo '</tr>';
    } // weglot not active

    echo '<tr>';
    echo '<th><label for="smush_support">Image Compression</label></th>';
    echo '<td>';
    if (defined('WP_SMUSH_VERSION')) {
      echo 'Configure <a target="_blank" href="' . admin_url('admin.php?page=smush') . '">image compression options</a>.';
    } else {
      echo '<div class="toggle-wrapper">
      <input type="checkbox" id="smush_support" type="checkbox" value="1" class="skip-save open-smush-install">
      <label for="smush_support" class="toggle"><span class="toggle_handler"></span></label></div>';
      echo '<p class="description">The easiest way to speed up any site is to <b>compress images</b>. On an average page you can easily save a few megabytes. Doing it manually in Photoshop is a pain! That\'s why there are free plugins like <a href="' . admin_url('plugin-install.php?fix-install-button=1&tab=plugin-information&plugin=wp-smushit&TB_iframe=true&width=600&height=550') . '" class="thickbox open-plugin-details-modal smush-thickbox" id="smush-install-link">Smush</a> that specialize in compressing images. <a href="' . admin_url('plugin-install.php?fix-install-button=1&tab=plugin-information&plugin=wp-smushit&TB_iframe=true&width=600&height=550') . '" class="thickbox open-plugin-details-modal smush-thickbox">Install the free Smush plugin</a>. It has no limit on the amount of images you can compress, seamlessly integrates with WordPress, and is compatible with all plugins &amp; themes. And best of all - <b>it\'s used by over a million users just like you</b>.</p>';
    }
    echo '</td>';
    echo '</tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="title">' . __('Title', 'under-construction-page') . '</label></th>
    <td><input type="text" id="title" class="regular-text" name="' . UCP_OPTIONS_KEY . '[title]" value="' . esc_attr($options['title']) . '" />';
    echo '<p class="description">Page title. Default: ' . $default_options['title'] . '</p>';
    echo '<p><b>Available shortcodes:</b> (only active in UC themes, not on the rest of the site)</p>
    <ul class="ucp-list">
    <li><code>[site-title]</code> - blog title, as set in <a href="options-general.php">Options - General</a></li>
    <li><code>[site-tagline]</code> - blog tagline, as set in <a href="options-general.php">Options - General</a></li>
    <li><code>[site-url]</code> - site address (URL), as set in <a href="options-general.php">Options - General</a></li>
    <li><code>[wp-url]</code> - WordPress address (URL), as set in <a href="options-general.php">Options - General</a></li>
    <li><code>[site-login-url]</code> - URL of the default site login page</li>
    </ul>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="description">' . __('Description', 'under-construction-page') . '</label></th>
    <td><input id="description" type="text" class="large-text" name="' . UCP_OPTIONS_KEY . '[description]" value="' . esc_attr($options['description']) . '" />';
    echo '<p class="description">Description meta tag (see above for available <a href="#title">shortcodes</a>). Default: ' . $default_options['description'] . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="heading1">' . __('Headline', 'under-construction-page') . '</label></th>
    <td><input id="heading1" type="text" class="large-text" name="' . UCP_OPTIONS_KEY . '[heading1]" value="' . esc_attr($options['heading1']) . '" />';
    echo '<p class="description">Main heading/title (see above for available <a href="#title">shortcodes</a>). Default: ' . $default_options['heading1'] . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" id="content_wrap">
    <th scope="row"><label for="content">' . __('Content', 'under-construction-page') . '</label></th>
    <td>';
    wp_editor($options['content'], 'content', array('tabfocus_elements' => 'insert-media-button,save-post', 'editor_height' => 250, 'resize' => 1, 'textarea_name' => UCP_OPTIONS_KEY . '[content]', 'drag_drop_upload' => 1));
    echo '<p class="description">All HTML elements are allowed. Shortcodes are not parsed except <a href="#title">UC theme ones</a>. Default: ' . $default_options['content'] . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="linkback">' . __('Show Some Love', 'under-construction-page') . '</label></th>
    <td>';
    echo '<div class="toggle-wrapper">
      <input type="checkbox" id="linkback" ' . self::checked(1, $options['linkback']) . ' type="checkbox" value="1" name="' . UCP_OPTIONS_KEY . '[linkback]">
      <label for="linkback" class="toggle"><span class="toggle_handler"></span></label>
    </div>';
    echo '<p class="description">Please help others learn about this free plugin by placing a small link in the footer. Thank you very much!</p>';
    echo '</td></tr>';

    echo '<tr>';
    echo '<th><label for="content_font">Content Font</label></th>';
    echo '<td><select class="skip-save open-ucp-upsell" id="content_font">';
    echo '<option value="" selected="selected">Theme Default</option>';
    echo '<option class="ucp-promo" value="-1">ABeeZee</option><option class="ucp-promo" value="-1">Abel</option><option class="ucp-promo" value="-1">Abril Fatface</option><option class="ucp-promo" value="-1">Aclonica</option><option class="ucp-promo" value="-1">Acme</option><option class="ucp-promo" value="-1">Actor</option><option class="ucp-promo" value="-1">Adamina</option><option class="ucp-promo" value="-1">Advent Pro</option><option class="ucp-promo" value="-1">Aguafina Script</option><option class="ucp-promo" value="-1">Akronim</option><option class="ucp-promo" value="-1">Aladin</option><option class="ucp-promo" value="-1">Aldrich</option><option class="ucp-promo" value="-1">Alef</option><option class="ucp-promo" value="-1">Alegreya</option><option class="ucp-promo" value="-1">Alegreya SC</option><option class="ucp-promo" value="-1">Alegreya Sans</option><option class="ucp-promo" value="-1">Alegreya Sans SC</option><option class="ucp-promo" value="-1">Alex Brush</option><option class="ucp-promo" value="-1">Alfa Slab One</option><option class="ucp-promo" value="-1">Alice</option><option class="ucp-promo" value="-1">Alike</option><option class="ucp-promo" value="-1">Alike Angular</option><option class="ucp-promo" value="-1">Allan</option><option class="ucp-promo" value="-1">Allerta</option><option class="ucp-promo" value="-1">Allerta Stencil</option><option class="ucp-promo" value="-1">Allura</option><option class="ucp-promo" value="-1">Almendra</option><option class="ucp-promo" value="-1">Almendra Display</option><option class="ucp-promo" value="-1">Almendra SC</option><option class="ucp-promo" value="-1">Amarante</option><option class="ucp-promo" value="-1">Amaranth</option><option class="ucp-promo" value="-1">Amatic SC</option><option class="ucp-promo" value="-1">Amethysta</option><option class="ucp-promo" value="-1">Anaheim</option><option class="ucp-promo" value="-1">Andada</option><option class="ucp-promo" value="-1">Andika</option><option class="ucp-promo" value="-1">Angkor</option><option class="ucp-promo" value="-1">Annie Use Your Telescope</option><option class="ucp-promo" value="-1">Anonymous Pro</option><option class="ucp-promo" value="-1">Antic</option><option class="ucp-promo" value="-1">Antic Didone</option><option class="ucp-promo" value="-1">Antic Slab</option><option class="ucp-promo" value="-1">Anton</option><option class="ucp-promo" value="-1">Arapey</option><option class="ucp-promo" value="-1">Arbutus</option><option class="ucp-promo" value="-1">Arbutus Slab</option><option class="ucp-promo" value="-1">Architects Daughter</option><option class="ucp-promo" value="-1">Archivo Black</option><option class="ucp-promo" value="-1">Archivo Narrow</option><option class="ucp-promo" value="-1">Arimo</option><option class="ucp-promo" value="-1">Arizonia</option><option class="ucp-promo" value="-1">Armata</option><option class="ucp-promo" value="-1">Artifika</option><option class="ucp-promo" value="-1">Arvo</option><option class="ucp-promo" value="-1">Asap</option><option class="ucp-promo" value="-1">Asset</option><option class="ucp-promo" value="-1">Astloch</option><option class="ucp-promo" value="-1">Asul</option><option class="ucp-promo" value="-1">Atomic Age</option><option class="ucp-promo" value="-1">Aubrey</option><option class="ucp-promo" value="-1">Audiowide</option><option class="ucp-promo" value="-1">Autour One</option><option class="ucp-promo" value="-1">Average</option><option class="ucp-promo" value="-1">Average Sans</option><option class="ucp-promo" value="-1">Averia Gruesa Libre</option><option class="ucp-promo" value="-1">Averia Libre</option><option class="ucp-promo" value="-1">Averia Sans Libre</option><option class="ucp-promo" value="-1">Averia Serif Libre</option><option class="ucp-promo" value="-1">Bad Script</option><option class="ucp-promo" value="-1">Balthazar</option><option class="ucp-promo" value="-1">Bangers</option><option class="ucp-promo" value="-1">Basic</option><option class="ucp-promo" value="-1">Battambang</option><option class="ucp-promo" value="-1">Baumans</option><option class="ucp-promo" value="-1">Bayon</option><option class="ucp-promo" value="-1">Belgrano</option><option class="ucp-promo" value="-1">Belleza</option><option class="ucp-promo" value="-1">BenchNine</option><option class="ucp-promo" value="-1">Bentham</option><option class="ucp-promo" value="-1">Berkshire Swash</option><option class="ucp-promo" value="-1">Bevan</option><option class="ucp-promo" value="-1">Bigelow Rules</option><option class="ucp-promo" value="-1">Bigshot One</option><option class="ucp-promo" value="-1">Bilbo</option><option class="ucp-promo" value="-1">Bilbo Swash Caps</option><option class="ucp-promo" value="-1">Bitter</option><option class="ucp-promo" value="-1">Black Ops One</option><option class="ucp-promo" value="-1">Bokor</option><option class="ucp-promo" value="-1">Bonbon</option><option class="ucp-promo" value="-1">Boogaloo</option><option class="ucp-promo" value="-1">Bowlby One</option><option class="ucp-promo" value="-1">Bowlby One SC</option><option class="ucp-promo" value="-1">Brawler</option><option class="ucp-promo" value="-1">Bree Serif</option><option class="ucp-promo" value="-1">Bubblegum Sans</option><option class="ucp-promo" value="-1">Bubbler One</option><option class="ucp-promo" value="-1">Buda</option><option class="ucp-promo" value="-1">Buenard</option><option class="ucp-promo" value="-1">Butcherman</option><option class="ucp-promo" value="-1">Butterfly Kids</option><option class="ucp-promo" value="-1">Cabin</option><option class="ucp-promo" value="-1">Cabin Condensed</option><option class="ucp-promo" value="-1">Cabin Sketch</option><option class="ucp-promo" value="-1">Caesar Dressing</option><option class="ucp-promo" value="-1">Cagliostro</option><option class="ucp-promo" value="-1">Calligraffitti</option><option class="ucp-promo" value="-1">Cambo</option><option class="ucp-promo" value="-1">Candal</option><option class="ucp-promo" value="-1">Cantarell</option><option class="ucp-promo" value="-1">Cantata One</option><option class="ucp-promo" value="-1">Cantora One</option><option class="ucp-promo" value="-1">Capriola</option><option class="ucp-promo" value="-1">Cardo</option><option class="ucp-promo" value="-1">Carme</option><option class="ucp-promo" value="-1">Carrois Gothic</option><option class="ucp-promo" value="-1">Carrois Gothic SC</option><option class="ucp-promo" value="-1">Carter One</option><option class="ucp-promo" value="-1">Caudex</option><option class="ucp-promo" value="-1">Cedarville Cursive</option><option class="ucp-promo" value="-1">Ceviche One</option><option class="ucp-promo" value="-1">Changa One</option><option class="ucp-promo" value="-1">Chango</option><option class="ucp-promo" value="-1">Chau Philomene One</option><option class="ucp-promo" value="-1">Chela One</option><option class="ucp-promo" value="-1">Chelsea Market</option><option class="ucp-promo" value="-1">Chenla</option><option class="ucp-promo" value="-1">Cherry Cream Soda</option><option class="ucp-promo" value="-1">Cherry Swash</option><option class="ucp-promo" value="-1">Chewy</option><option class="ucp-promo" value="-1">Chicle</option><option class="ucp-promo" value="-1">Chivo</option><option class="ucp-promo" value="-1">Cinzel</option><option class="ucp-promo" value="-1">Cinzel Decorative</option><option class="ucp-promo" value="-1">Clicker Script</option><option class="ucp-promo" value="-1">Coda</option><option class="ucp-promo" value="-1">Coda Caption</option><option class="ucp-promo" value="-1">Codystar</option><option class="ucp-promo" value="-1">Combo</option><option class="ucp-promo" value="-1">Comfortaa</option><option class="ucp-promo" value="-1">Coming Soon</option><option class="ucp-promo" value="-1">Concert One</option><option class="ucp-promo" value="-1">Condiment</option><option class="ucp-promo" value="-1">Content</option><option class="ucp-promo" value="-1">Contrail One</option><option class="ucp-promo" value="-1">Convergence</option><option class="ucp-promo" value="-1">Cookie</option><option class="ucp-promo" value="-1">Copse</option><option class="ucp-promo" value="-1">Corben</option><option class="ucp-promo" value="-1">Courgette</option><option class="ucp-promo" value="-1">Cousine</option><option class="ucp-promo" value="-1">Coustard</option><option class="ucp-promo" value="-1">Covered By Your Grace</option><option class="ucp-promo" value="-1">Crafty Girls</option><option class="ucp-promo" value="-1">Creepster</option><option class="ucp-promo" value="-1">Crete Round</option><option class="ucp-promo" value="-1">Crimson Text</option><option class="ucp-promo" value="-1">Croissant One</option><option class="ucp-promo" value="-1">Crushed</option><option class="ucp-promo" value="-1">Cuprum</option><option class="ucp-promo" value="-1">Cutive</option><option class="ucp-promo" value="-1">Cutive Mono</option><option class="ucp-promo" value="-1">Damion</option><option class="ucp-promo" value="-1">Dancing Script</option><option class="ucp-promo" value="-1">Dangrek</option><option class="ucp-promo" value="-1">Dawning of a New Day</option><option class="ucp-promo" value="-1">Days One</option><option class="ucp-promo" value="-1">Delius</option><option class="ucp-promo" value="-1">Delius Swash Caps</option><option class="ucp-promo" value="-1">Delius Unicase</option><option class="ucp-promo" value="-1">Della Respira</option><option class="ucp-promo" value="-1">Denk One</option><option class="ucp-promo" value="-1">Devonshire</option><option class="ucp-promo" value="-1">Didact Gothic</option><option class="ucp-promo" value="-1">Diplomata</option><option class="ucp-promo" value="-1">Diplomata SC</option><option class="ucp-promo" value="-1">Domine</option><option class="ucp-promo" value="-1">Donegal One</option><option class="ucp-promo" value="-1">Doppio One</option><option class="ucp-promo" value="-1">Dorsa</option><option class="ucp-promo" value="-1">Dosis</option><option class="ucp-promo" value="-1">Dr Sugiyama</option><option class="ucp-promo" value="-1">Droid Sans</option><option class="ucp-promo" value="-1">Droid Sans Mono</option><option class="ucp-promo" value="-1">Droid Serif</option><option class="ucp-promo" value="-1">Duru Sans</option><option class="ucp-promo" value="-1">Dynalight</option><option class="ucp-promo" value="-1">EB Garamond</option><option class="ucp-promo" value="-1">Eagle Lake</option><option class="ucp-promo" value="-1">Eater</option><option class="ucp-promo" value="-1">Economica</option><option class="ucp-promo" value="-1">Ek Mukta</option><option class="ucp-promo" value="-1">Electrolize</option><option class="ucp-promo" value="-1">Elsie</option><option class="ucp-promo" value="-1">Elsie Swash Caps</option><option class="ucp-promo" value="-1">Emblema One</option><option class="ucp-promo" value="-1">Emilys Candy</option><option class="ucp-promo" value="-1">Engagement</option><option class="ucp-promo" value="-1">Englebert</option><option class="ucp-promo" value="-1">Enriqueta</option><option class="ucp-promo" value="-1">Erica One</option><option class="ucp-promo" value="-1">Esteban</option><option class="ucp-promo" value="-1">Euphoria Script</option><option class="ucp-promo" value="-1">Ewert</option><option class="ucp-promo" value="-1">Exo</option><option class="ucp-promo" value="-1">Exo 2</option><option class="ucp-promo" value="-1">Expletus Sans</option><option class="ucp-promo" value="-1">Fanwood Text</option><option class="ucp-promo" value="-1">Fascinate</option><option class="ucp-promo" value="-1">Fascinate Inline</option><option class="ucp-promo" value="-1">Faster One</option><option class="ucp-promo" value="-1">Fasthand</option><option class="ucp-promo" value="-1">Fauna One</option><option class="ucp-promo" value="-1">Federant</option><option class="ucp-promo" value="-1">Federo</option><option class="ucp-promo" value="-1">Felipa</option><option class="ucp-promo" value="-1">Fenix</option><option class="ucp-promo" value="-1">Finger Paint</option><option class="ucp-promo" value="-1">Fira Mono</option><option class="ucp-promo" value="-1">Fira Sans</option><option class="ucp-promo" value="-1">Fjalla One</option><option class="ucp-promo" value="-1">Fjord One</option><option class="ucp-promo" value="-1">Flamenco</option><option class="ucp-promo" value="-1">Flavors</option><option class="ucp-promo" value="-1">Fondamento</option><option class="ucp-promo" value="-1">Fontdiner Swanky</option><option class="ucp-promo" value="-1">Forum</option><option class="ucp-promo" value="-1">Francois One</option><option class="ucp-promo" value="-1">Freckle Face</option><option class="ucp-promo" value="-1">Fredericka the Great</option><option class="ucp-promo" value="-1">Fredoka One</option><option class="ucp-promo" value="-1">Freehand</option><option class="ucp-promo" value="-1">Fresca</option><option class="ucp-promo" value="-1">Frijole</option><option class="ucp-promo" value="-1">Fruktur</option><option class="ucp-promo" value="-1">Fugaz One</option><option class="ucp-promo" value="-1">GFS Didot</option><option class="ucp-promo" value="-1">GFS Neohellenic</option><option class="ucp-promo" value="-1">Gabriela</option><option class="ucp-promo" value="-1">Gafata</option><option class="ucp-promo" value="-1">Galdeano</option><option class="ucp-promo" value="-1">Galindo</option><option class="ucp-promo" value="-1">Gentium Basic</option><option class="ucp-promo" value="-1">Gentium Book Basic</option><option class="ucp-promo" value="-1">Geo</option><option class="ucp-promo" value="-1">Geostar</option><option class="ucp-promo" value="-1">Geostar Fill</option><option class="ucp-promo" value="-1">Germania One</option><option class="ucp-promo" value="-1">Gilda Display</option><option class="ucp-promo" value="-1">Give You Glory</option><option class="ucp-promo" value="-1">Glass Antiqua</option><option class="ucp-promo" value="-1">Glegoo</option><option class="ucp-promo" value="-1">Gloria Hallelujah</option><option class="ucp-promo" value="-1">Goblin One</option><option class="ucp-promo" value="-1">Gochi Hand</option><option class="ucp-promo" value="-1">Gorditas</option><option class="ucp-promo" value="-1">Goudy Bookletter 1911</option><option class="ucp-promo" value="-1">Graduate</option><option class="ucp-promo" value="-1">Grand Hotel</option><option class="ucp-promo" value="-1">Gravitas One</option><option class="ucp-promo" value="-1">Great Vibes</option><option class="ucp-promo" value="-1">Griffy</option><option class="ucp-promo" value="-1">Gruppo</option><option class="ucp-promo" value="-1">Gudea</option><option class="ucp-promo" value="-1">Habibi</option><option class="ucp-promo" value="-1">Hammersmith One</option><option class="ucp-promo" value="-1">Hanalei</option><option class="ucp-promo" value="-1">Hanalei Fill</option><option class="ucp-promo" value="-1">Handlee</option><option class="ucp-promo" value="-1">Hanuman</option><option class="ucp-promo" value="-1">Happy Monkey</option><option class="ucp-promo" value="-1">Headland One</option><option class="ucp-promo" value="-1">Henny Penny</option><option class="ucp-promo" value="-1">Herr Von Muellerhoff</option><option class="ucp-promo" value="-1">Hind</option><option class="ucp-promo" value="-1">Holtwood One SC</option><option class="ucp-promo" value="-1">Homemade Apple</option><option class="ucp-promo" value="-1">Homenaje</option><option class="ucp-promo" value="-1">IM Fell DW Pica</option><option class="ucp-promo" value="-1">IM Fell DW Pica SC</option><option class="ucp-promo" value="-1">IM Fell Double Pica</option><option class="ucp-promo" value="-1">IM Fell Double Pica SC</option><option class="ucp-promo" value="-1">IM Fell English</option><option class="ucp-promo" value="-1">IM Fell English SC</option><option class="ucp-promo" value="-1">IM Fell French Canon</option><option class="ucp-promo" value="-1">IM Fell French Canon SC</option><option class="ucp-promo" value="-1">IM Fell Great Primer</option><option class="ucp-promo" value="-1">IM Fell Great Primer SC</option><option class="ucp-promo" value="-1">Iceberg</option><option class="ucp-promo" value="-1">Iceland</option><option class="ucp-promo" value="-1">Imprima</option><option class="ucp-promo" value="-1">Inconsolata</option><option class="ucp-promo" value="-1">Inder</option><option class="ucp-promo" value="-1">Indie Flower</option><option class="ucp-promo" value="-1">Inika</option><option class="ucp-promo" value="-1">Irish Grover</option><option class="ucp-promo" value="-1">Istok Web</option><option class="ucp-promo" value="-1">Italiana</option><option class="ucp-promo" value="-1">Italianno</option><option class="ucp-promo" value="-1">Jacques Francois</option><option class="ucp-promo" value="-1">Jacques Francois Shadow</option><option class="ucp-promo" value="-1">Jim Nightshade</option><option class="ucp-promo" value="-1">Jockey One</option><option class="ucp-promo" value="-1">Jolly Lodger</option><option class="ucp-promo" value="-1">Josefin Sans</option><option class="ucp-promo" value="-1">Josefin Slab</option><option class="ucp-promo" value="-1">Joti One</option><option class="ucp-promo" value="-1">Judson</option><option class="ucp-promo" value="-1">Julee</option><option class="ucp-promo" value="-1">Julius Sans One</option><option class="ucp-promo" value="-1">Junge</option><option class="ucp-promo" value="-1">Jura</option><option class="ucp-promo" value="-1">Just Another Hand</option><option class="ucp-promo" value="-1">Just Me Again Down Here</option><option class="ucp-promo" value="-1">Kalam</option><option class="ucp-promo" value="-1">Kameron</option><option class="ucp-promo" value="-1">Kantumruy</option><option class="ucp-promo" value="-1">Karla</option><option class="ucp-promo" value="-1">Karma</option><option class="ucp-promo" value="-1">Kaushan Script</option><option class="ucp-promo" value="-1">Kavoon</option><option class="ucp-promo" value="-1">Kdam Thmor</option><option class="ucp-promo" value="-1">Keania One</option><option class="ucp-promo" value="-1">Kelly Slab</option><option class="ucp-promo" value="-1">Kenia</option><option class="ucp-promo" value="-1">Khmer</option><option class="ucp-promo" value="-1">Kite One</option><option class="ucp-promo" value="-1">Knewave</option><option class="ucp-promo" value="-1">Kotta One</option><option class="ucp-promo" value="-1">Koulen</option><option class="ucp-promo" value="-1">Kranky</option><option class="ucp-promo" value="-1">Kreon</option><option class="ucp-promo" value="-1">Kristi</option><option class="ucp-promo" value="-1">Krona One</option><option class="ucp-promo" value="-1">La Belle Aurore</option><option class="ucp-promo" value="-1">Lancelot</option><option class="ucp-promo" value="-1">Lato</option><option class="ucp-promo" value="-1">League Script</option><option class="ucp-promo" value="-1">Leckerli One</option><option class="ucp-promo" value="-1">Ledger</option><option class="ucp-promo" value="-1">Lekton</option><option class="ucp-promo" value="-1">Lemon</option><option class="ucp-promo" value="-1">Libre Baskerville</option><option class="ucp-promo" value="-1">Life Savers</option><option class="ucp-promo" value="-1">Lilita One</option><option class="ucp-promo" value="-1">Lily Script One</option><option class="ucp-promo" value="-1">Limelight</option><option class="ucp-promo" value="-1">Linden Hill</option><option class="ucp-promo" value="-1">Lobster</option><option class="ucp-promo" value="-1">Lobster Two</option><option class="ucp-promo" value="-1">Londrina Outline</option><option class="ucp-promo" value="-1">Londrina Shadow</option><option class="ucp-promo" value="-1">Londrina Sketch</option><option class="ucp-promo" value="-1">Londrina Solid</option><option class="ucp-promo" value="-1">Lora</option><option class="ucp-promo" value="-1">Love Ya Like A Sister</option><option class="ucp-promo" value="-1">Loved by the King</option><option class="ucp-promo" value="-1">Lovers Quarrel</option><option class="ucp-promo" value="-1">Luckiest Guy</option><option class="ucp-promo" value="-1">Lusitana</option><option class="ucp-promo" value="-1">Lustria</option><option class="ucp-promo" value="-1">Macondo</option><option class="ucp-promo" value="-1">Macondo Swash Caps</option><option class="ucp-promo" value="-1">Magra</option><option class="ucp-promo" value="-1">Maiden Orange</option><option class="ucp-promo" value="-1">Mako</option><option class="ucp-promo" value="-1">Marcellus</option><option class="ucp-promo" value="-1">Marcellus SC</option><option class="ucp-promo" value="-1">Marck Script</option><option class="ucp-promo" value="-1">Margarine</option><option class="ucp-promo" value="-1">Marko One</option><option class="ucp-promo" value="-1">Marmelad</option><option class="ucp-promo" value="-1">Marvel</option><option class="ucp-promo" value="-1">Mate</option><option class="ucp-promo" value="-1">Mate SC</option><option class="ucp-promo" value="-1">Maven Pro</option><option class="ucp-promo" value="-1">McLaren</option><option class="ucp-promo" value="-1">Meddon</option><option class="ucp-promo" value="-1">MedievalSharp</option><option class="ucp-promo" value="-1">Medula One</option><option class="ucp-promo" value="-1">Megrim</option><option class="ucp-promo" value="-1">Meie Script</option><option class="ucp-promo" value="-1">Merienda</option><option class="ucp-promo" value="-1">Merienda One</option><option class="ucp-promo" value="-1">Merriweather</option><option class="ucp-promo" value="-1">Merriweather Sans</option><option class="ucp-promo" value="-1">Metal</option><option class="ucp-promo" value="-1">Metal Mania</option><option class="ucp-promo" value="-1">Metamorphous</option><option class="ucp-promo" value="-1">Metrophobic</option><option class="ucp-promo" value="-1">Michroma</option><option class="ucp-promo" value="-1">Milonga</option><option class="ucp-promo" value="-1">Miltonian</option><option class="ucp-promo" value="-1">Miltonian Tattoo</option><option class="ucp-promo" value="-1">Miniver</option><option class="ucp-promo" value="-1">Miss Fajardose</option><option class="ucp-promo" value="-1">Modern Antiqua</option><option class="ucp-promo" value="-1">Molengo</option><option class="ucp-promo" value="-1">Molle</option><option class="ucp-promo" value="-1">Monda</option><option class="ucp-promo" value="-1">Monofett</option><option class="ucp-promo" value="-1">Monoton</option><option class="ucp-promo" value="-1">Monsieur La Doulaise</option><option class="ucp-promo" value="-1">Montaga</option><option class="ucp-promo" value="-1">Montez</option><option class="ucp-promo" value="-1">Montserrat</option><option class="ucp-promo" value="-1">Montserrat Alternates</option><option class="ucp-promo" value="-1">Montserrat Subrayada</option><option class="ucp-promo" value="-1">Moul</option><option class="ucp-promo" value="-1">Moulpali</option><option class="ucp-promo" value="-1">Mountains of Christmas</option><option class="ucp-promo" value="-1">Mouse Memoirs</option><option class="ucp-promo" value="-1">Mr Bedfort</option><option class="ucp-promo" value="-1">Mr Dafoe</option><option class="ucp-promo" value="-1">Mr De Haviland</option><option class="ucp-promo" value="-1">Mrs Saint Delafield</option><option class="ucp-promo" value="-1">Mrs Sheppards</option><option class="ucp-promo" value="-1">Muli</option><option class="ucp-promo" value="-1">Mystery Quest</option><option class="ucp-promo" value="-1">Neucha</option><option class="ucp-promo" value="-1">Neuton</option><option class="ucp-promo" value="-1">New Rocker</option><option class="ucp-promo" value="-1">News Cycle</option><option class="ucp-promo" value="-1">Niconne</option><option class="ucp-promo" value="-1">Nixie One</option><option class="ucp-promo" value="-1">Nobile</option><option class="ucp-promo" value="-1">Nokora</option><option class="ucp-promo" value="-1">Norican</option><option class="ucp-promo" value="-1">Nosifer</option><option class="ucp-promo" value="-1">Nothing You Could Do</option><option class="ucp-promo" value="-1">Noticia Text</option><option class="ucp-promo" value="-1">Noto Sans</option><option class="ucp-promo" value="-1">Noto Serif</option><option class="ucp-promo" value="-1">Nova Cut</option><option class="ucp-promo" value="-1">Nova Flat</option><option class="ucp-promo" value="-1">Nova Mono</option><option class="ucp-promo" value="-1">Nova Oval</option><option class="ucp-promo" value="-1">Nova Round</option><option class="ucp-promo" value="-1">Nova Script</option><option class="ucp-promo" value="-1">Nova Slim</option><option class="ucp-promo" value="-1">Nova Square</option><option class="ucp-promo" value="-1">Numans</option><option class="ucp-promo" value="-1">Nunito</option><option class="ucp-promo" value="-1">Odor Mean Chey</option><option class="ucp-promo" value="-1">Offside</option><option class="ucp-promo" value="-1">Old Standard TT</option><option class="ucp-promo" value="-1">Oldenburg</option><option class="ucp-promo" value="-1">Oleo Script</option><option class="ucp-promo" value="-1">Oleo Script Swash Caps</option><option class="ucp-promo" value="-1">Open Sans</option><option class="ucp-promo" value="-1">Open Sans Condensed</option><option class="ucp-promo" value="-1">Oranienbaum</option><option class="ucp-promo" value="-1">Orbitron</option><option class="ucp-promo" value="-1">Oregano</option><option class="ucp-promo" value="-1">Orienta</option><option class="ucp-promo" value="-1">Original Surfer</option><option class="ucp-promo" value="-1">Oswald</option><option class="ucp-promo" value="-1">Over the Rainbow</option><option class="ucp-promo" value="-1">Overlock</option><option class="ucp-promo" value="-1">Overlock SC</option><option class="ucp-promo" value="-1">Ovo</option><option class="ucp-promo" value="-1">Oxygen</option><option class="ucp-promo" value="-1">Oxygen Mono</option><option class="ucp-promo" value="-1">PT Mono</option><option class="ucp-promo" value="-1">PT Sans</option><option class="ucp-promo" value="-1">PT Sans Caption</option><option class="ucp-promo" value="-1">PT Sans Narrow</option><option class="ucp-promo" value="-1">PT Serif</option><option class="ucp-promo" value="-1">PT Serif Caption</option><option class="ucp-promo" value="-1">Pacifico</option><option class="ucp-promo" value="-1">Paprika</option><option class="ucp-promo" value="-1">Parisienne</option><option class="ucp-promo" value="-1">Passero One</option><option class="ucp-promo" value="-1">Passion One</option><option class="ucp-promo" value="-1">Pathway Gothic One</option><option class="ucp-promo" value="-1">Patrick Hand</option><option class="ucp-promo" value="-1">Patrick Hand SC</option><option class="ucp-promo" value="-1">Patua One</option><option class="ucp-promo" value="-1">Paytone One</option><option class="ucp-promo" value="-1">Peralta</option><option class="ucp-promo" value="-1">Permanent Marker</option><option class="ucp-promo" value="-1">Petit Formal Script</option><option class="ucp-promo" value="-1">Petrona</option><option class="ucp-promo" value="-1">Philosopher</option><option class="ucp-promo" value="-1">Piedra</option><option class="ucp-promo" value="-1">Pinyon Script</option><option class="ucp-promo" value="-1">Pirata One</option><option class="ucp-promo" value="-1">Plaster</option><option class="ucp-promo" value="-1">Play</option><option class="ucp-promo" value="-1">Playball</option><option class="ucp-promo" value="-1">Playfair Display</option><option class="ucp-promo" value="-1">Playfair Display SC</option><option class="ucp-promo" value="-1">Podkova</option><option class="ucp-promo" value="-1">Poiret One</option><option class="ucp-promo" value="-1">Poller One</option><option class="ucp-promo" value="-1">Poly</option><option class="ucp-promo" value="-1">Pompiere</option><option class="ucp-promo" value="-1">Pontano Sans</option><option class="ucp-promo" value="-1">Port Lligat Sans</option><option class="ucp-promo" value="-1">Port Lligat Slab</option><option class="ucp-promo" value="-1">Prata</option><option class="ucp-promo" value="-1">Preahvihear</option><option class="ucp-promo" value="-1">Press Start 2P</option><option class="ucp-promo" value="-1">Princess Sofia</option><option class="ucp-promo" value="-1">Prociono</option><option class="ucp-promo" value="-1">Prosto One</option><option class="ucp-promo" value="-1">Puritan</option><option class="ucp-promo" value="-1">Purple Purse</option><option class="ucp-promo" value="-1">Quando</option><option class="ucp-promo" value="-1">Quantico</option><option class="ucp-promo" value="-1">Quattrocento</option><option class="ucp-promo" value="-1">Quattrocento Sans</option><option class="ucp-promo" value="-1">Questrial</option><option class="ucp-promo" value="-1">Quicksand</option><option class="ucp-promo" value="-1">Quintessential</option><option class="ucp-promo" value="-1">Qwigley</option><option class="ucp-promo" value="-1">Racing Sans One</option><option class="ucp-promo" value="-1">Radley</option><option class="ucp-promo" value="-1">Rajdhani</option><option class="ucp-promo" value="-1">Raleway</option><option class="ucp-promo" value="-1">Raleway Dots</option><option class="ucp-promo" value="-1">Rambla</option><option class="ucp-promo" value="-1">Rammetto One</option><option class="ucp-promo" value="-1">Ranchers</option><option class="ucp-promo" value="-1">Rancho</option><option class="ucp-promo" value="-1">Rationale</option><option class="ucp-promo" value="-1">Redressed</option><option class="ucp-promo" value="-1">Reenie Beanie</option><option class="ucp-promo" value="-1">Revalia</option><option class="ucp-promo" value="-1">Ribeye</option><option class="ucp-promo" value="-1">Ribeye Marrow</option><option class="ucp-promo" value="-1">Righteous</option><option class="ucp-promo" value="-1">Risque</option><option class="ucp-promo" value="-1">Roboto</option><option class="ucp-promo" value="-1">Roboto Condensed</option><option class="ucp-promo" value="-1">Roboto Slab</option><option class="ucp-promo" value="-1">Rochester</option><option class="ucp-promo" value="-1">Rock Salt</option><option class="ucp-promo" value="-1">Rokkitt</option><option class="ucp-promo" value="-1">Romanesco</option><option class="ucp-promo" value="-1">Ropa Sans</option><option class="ucp-promo" value="-1">Rosario</option><option class="ucp-promo" value="-1">Rosarivo</option><option class="ucp-promo" value="-1">Rouge Script</option><option class="ucp-promo" value="-1">Rubik Mono One</option><option class="ucp-promo" value="-1">Rubik One</option><option class="ucp-promo" value="-1">Ruda</option><option class="ucp-promo" value="-1">Rufina</option><option class="ucp-promo" value="-1">Ruge Boogie</option><option class="ucp-promo" value="-1">Ruluko</option><option class="ucp-promo" value="-1">Rum Raisin</option><option class="ucp-promo" value="-1">Ruslan Display</option><option class="ucp-promo" value="-1">Russo One</option><option class="ucp-promo" value="-1">Ruthie</option><option class="ucp-promo" value="-1">Rye</option><option class="ucp-promo" value="-1">Sacramento</option><option class="ucp-promo" value="-1">Sail</option><option class="ucp-promo" value="-1">Salsa</option><option class="ucp-promo" value="-1">Sanchez</option><option class="ucp-promo" value="-1">Sancreek</option><option class="ucp-promo" value="-1">Sansita One</option><option class="ucp-promo" value="-1">Sarina</option><option class="ucp-promo" value="-1">Satisfy</option><option class="ucp-promo" value="-1">Scada</option><option class="ucp-promo" value="-1">Schoolbell</option><option class="ucp-promo" value="-1">Seaweed Script</option><option class="ucp-promo" value="-1">Sevillana</option><option class="ucp-promo" value="-1">Seymour One</option><option class="ucp-promo" value="-1">Shadows Into Light</option><option class="ucp-promo" value="-1">Shadows Into Light Two</option><option class="ucp-promo" value="-1">Shanti</option><option class="ucp-promo" value="-1">Share</option><option class="ucp-promo" value="-1">Share Tech</option><option class="ucp-promo" value="-1">Share Tech Mono</option><option class="ucp-promo" value="-1">Shojumaru</option><option class="ucp-promo" value="-1">Short Stack</option><option class="ucp-promo" value="-1">Siemreap</option><option class="ucp-promo" value="-1">Sigmar One</option><option class="ucp-promo" value="-1">Signika</option><option class="ucp-promo" value="-1">Signika Negative</option><option class="ucp-promo" value="-1">Simonetta</option><option class="ucp-promo" value="-1">Sintony</option><option class="ucp-promo" value="-1">Sirin Stencil</option><option class="ucp-promo" value="-1">Six Caps</option><option class="ucp-promo" value="-1">Skranji</option><option class="ucp-promo" value="-1">Slabo 13px</option><option class="ucp-promo" value="-1">Slabo 27px</option><option class="ucp-promo" value="-1">Slackey</option><option class="ucp-promo" value="-1">Smokum</option><option class="ucp-promo" value="-1">Smythe</option><option class="ucp-promo" value="-1">Sniglet</option><option class="ucp-promo" value="-1">Snippet</option><option class="ucp-promo" value="-1">Snowburst One</option><option class="ucp-promo" value="-1">Sofadi One</option><option class="ucp-promo" value="-1">Sofia</option><option class="ucp-promo" value="-1">Sonsie One</option><option class="ucp-promo" value="-1">Sorts Mill Goudy</option><option class="ucp-promo" value="-1">Source Code Pro</option><option class="ucp-promo" value="-1">Source Sans Pro</option><option class="ucp-promo" value="-1">Source Serif Pro</option><option class="ucp-promo" value="-1">Special Elite</option><option class="ucp-promo" value="-1">Spicy Rice</option><option class="ucp-promo" value="-1">Spinnaker</option><option class="ucp-promo" value="-1">Spirax</option><option class="ucp-promo" value="-1">Squada One</option><option class="ucp-promo" value="-1">Stalemate</option><option class="ucp-promo" value="-1">Stalinist One</option><option class="ucp-promo" value="-1">Stardos Stencil</option><option class="ucp-promo" value="-1">Stint Ultra Condensed</option><option class="ucp-promo" value="-1">Stint Ultra Expanded</option><option class="ucp-promo" value="-1">Stoke</option><option class="ucp-promo" value="-1">Strait</option><option class="ucp-promo" value="-1">Sue Ellen Francisco</option><option class="ucp-promo" value="-1">Sunshiney</option><option class="ucp-promo" value="-1">Supermercado One</option><option class="ucp-promo" value="-1">Suwannaphum</option><option class="ucp-promo" value="-1">Swanky and Moo Moo</option><option class="ucp-promo" value="-1">Syncopate</option><option class="ucp-promo" value="-1">Tangerine</option><option class="ucp-promo" value="-1">Taprom</option><option class="ucp-promo" value="-1">Tauri</option><option class="ucp-promo" value="-1">Teko</option><option class="ucp-promo" value="-1">Telex</option><option class="ucp-promo" value="-1">Tenor Sans</option><option class="ucp-promo" value="-1">Text Me One</option><option class="ucp-promo" value="-1">The Girl Next Door</option><option class="ucp-promo" value="-1">Tienne</option><option class="ucp-promo" value="-1">Tinos</option><option class="ucp-promo" value="-1">Titan One</option><option class="ucp-promo" value="-1">Titillium Web</option><option class="ucp-promo" value="-1">Trade Winds</option><option class="ucp-promo" value="-1">Trocchi</option><option class="ucp-promo" value="-1">Trochut</option><option class="ucp-promo" value="-1">Trykker</option><option class="ucp-promo" value="-1">Tulpen One</option><option class="ucp-promo" value="-1">Ubuntu</option><option class="ucp-promo" value="-1">Ubuntu Condensed</option><option class="ucp-promo" value="-1">Ubuntu Mono</option><option class="ucp-promo" value="-1">Ultra</option><option class="ucp-promo" value="-1">Uncial Antiqua</option><option class="ucp-promo" value="-1">Underdog</option><option class="ucp-promo" value="-1">Unica One</option><option class="ucp-promo" value="-1">UnifrakturCook</option><option class="ucp-promo" value="-1">UnifrakturMaguntia</option><option class="ucp-promo" value="-1">Unkempt</option><option class="ucp-promo" value="-1">Unlock</option><option class="ucp-promo" value="-1">Unna</option><option class="ucp-promo" value="-1">VT323</option><option class="ucp-promo" value="-1">Vampiro One</option><option class="ucp-promo" value="-1">Varela</option><option class="ucp-promo" value="-1">Varela Round</option><option class="ucp-promo" value="-1">Vast Shadow</option><option class="ucp-promo" value="-1">Vibur</option><option class="ucp-promo" value="-1">Vidaloka</option><option class="ucp-promo" value="-1">Viga</option><option class="ucp-promo" value="-1">Voces</option><option class="ucp-promo" value="-1">Volkhov</option><option class="ucp-promo" value="-1">Vollkorn</option><option class="ucp-promo" value="-1">Voltaire</option><option class="ucp-promo" value="-1">Waiting for the Sunrise</option><option class="ucp-promo" value="-1">Wallpoet</option><option class="ucp-promo" value="-1">Walter Turncoat</option><option class="ucp-promo" value="-1">Warnes</option><option class="ucp-promo" value="-1">Wellfleet</option><option class="ucp-promo" value="-1">Wendy One</option><option class="ucp-promo" value="-1">Wire One</option><option class="ucp-promo" value="-1">Yanone Kaffeesatz</option><option class="ucp-promo" value="-1">Yellowtail</option><option class="ucp-promo" value="-1">Yeseva One</option><option class="ucp-promo" value="-1">Yesteryear</option><option class="ucp-promo" value="-1">Zeyada</option>';
    echo '</select>';
    echo '<p class="description">Choose one of 600+ beautiful Google fonts or use the default, theme set one. This is a <a href="#" class="open-ucp-upsell" data-pro-ad="content_font">PRO feature</a>.</p>';
    echo '</td>';
    echo '</tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="external_shortcodes">' . __('3rd Party Shortcode Support in Content', 'under-construction-page') . '</label></th>
    <td>';
    echo '<div class="toggle-wrapper">
      <input type="checkbox" id="external_shortcodes" type="checkbox" value="1" class="skip-save open-ucp-upsell">
      <label for="external_shortcodes" class="toggle"><span class="toggle_handler"></span></label>
    </div>';
    echo '<p class="description">Enable if you have a 3rd party shortcode you\'d like to use on the under construction page. This is a <a href="#" class="open-ucp-upsell" data-pro-ad="external_shortcodes">PRO feature</a>.</p>';
    echo '</td></tr>';

    echo '<tr valign="top" id="login_button_wrap">
    <th scope="row"><label for="login_button">' . __('Login Button', 'under-construction-page') . '</label></th>
    <td>';
    echo '<div class="toggle-wrapper">
      <input type="checkbox" id="login_button" ' . self::checked(1, $options['login_button']) . ' type="checkbox" value="1" name="' . UCP_OPTIONS_KEY . '[login_button]">
      <label for="login_button" class="toggle"><span class="toggle_handler"></span></label>
    </div>';
    echo '<p class="description">Show a discrete link to the login form, or WP admin if you\'re logged in, in the lower right corner of the page.</p>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="custom_footer_code">' . __('Custom Footer Code', 'under-construction-page') . '</label></th>
    <td>';
    echo '<textarea data-autoresize="1" rows="3" id="custom_footer_code" class="code large-text skip-save disabled open-ucp-upsell" name="" placeholder=""></textarea>';
    echo '<p class="description">Paste any 3rd party code here such as tracking scripts or tracking pixels. Be sure to include &lt;script&gt; tags as nothing is added automatically.<br>This is NOT a place to add Google Analytics code. Please use the <a href="#ga_tracking_id_toggle" class="change_tab" data-tab="0">GA Tracking setting</a> for that. This is a <a href="#" class="open-ucp-upsell" data-pro-ad="footer_code">PRO feature</a>.</p>';
    echo '</td></tr>';

    echo '</table>';

    self::footer_buttons();

    echo '<h2 class="title">' . __('Social &amp; Contact Icons', 'under-construction-page') . '</h2>';

    echo '<table class="form-table" id="ucp-social-icons">';
    echo '<tr valign="top">
    <th scope="row"><label for="social_facebook">' . __('Facebook Page', 'under-construction-page') . '</label></th>
    <td><input id="social_facebook" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_facebook]" value="' . esc_attr($options['social_facebook']) . '" placeholder="' . __('Facebook business or personal page URL', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Complete URL, with https prefix, to Facebook page.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="social_twitter">' . __('Twitter Profile', 'under-construction-page') . '</label></th>
    <td><input id="social_twitter" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_twitter]" value="' . esc_attr($options['social_twitter']) . '" placeholder="' . __('Twitter profile URL', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Complete URL, with https prefix, to Twitter profile page.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="social_linkedin">' . __('LinkedIn Profile', 'under-construction-page') . '</label></th>
    <td><input id="social_linkedin" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_linkedin]" value="' . esc_attr($options['social_linkedin']) . '" placeholder="' . __('LinkedIn profile page URL', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Complete URL, with https prefix, to LinkedIn profile page.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="social_youtube">' . __('YouTube Profile Page or Video', 'under-construction-page') . '</label></th>
    <td><input id="social_youtube" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_youtube]" value="' . esc_attr($options['social_youtube']) . '" placeholder="' . __('YouTube page or video URL', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Complete URL, with https prefix, to YouTube page or video.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" class="hidden">
    <th scope="row"><label for="social_vimeo">' . __('Vimeo Profile Page or Video', 'under-construction-page') . '</label></th>
    <td><input id="social_vimeo" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_vimeo]" value="' . esc_attr($options['social_vimeo']) . '" placeholder="' . __('Vimeo page or video URL', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Complete URL, with https prefix, to Vimeo page or video.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" class="hidden">
    <th scope="row"><label for="social_pinterest">' . __('Pinterest Profile', 'under-construction-page') . '</label></th>
    <td><input id="social_pinterest" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_pinterest]" value="' . esc_attr($options['social_pinterest']) . '" placeholder="' . __('Pinterest profile URL', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Complete URL, with https prefix, to Pinterest profile.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" class="hidden">
    <th scope="row"><label for="social_dribbble">' . __('Dribbble Profile', 'under-construction-page') . '</label></th>
    <td><input id="social_dribbble" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_dribbble]" value="' . esc_attr($options['social_dribbble']) . '" placeholder="' . __('Dribbble profile URL', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Complete URL, with https prefix, to Dribbble profile.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" class="hidden">
    <th scope="row"><label for="social_behance">' . __('Behance Profile', 'under-construction-page') . '</label></th>
    <td><input id="social_behance" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_behance]" value="' . esc_attr($options['social_behance']) . '" placeholder="' . __('Behance profile URL', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Complete URL, with https prefix, to Behance profile.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" class="hidden">
    <th scope="row"><label for="social_instagram">' . __('Instagram Profile', 'under-construction-page') . '</label></th>
    <td><input id="social_instagram" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_instagram]" value="' . esc_attr($options['social_instagram']) . '" placeholder="' . __('Instagram profile URL', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Complete URL, with https prefix, to Instagram profile.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" class="hidden">
    <th scope="row"><label for="social_vk">' . __('VK Profile', 'under-construction-page') . '</label></th>
    <td><input id="social_vk" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_vk]" value="' . esc_attr($options['social_vk']) . '" placeholder="' . __('VK profile URL', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Complete URL, with https prefix, to VK profile.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" class="hidden">
    <th scope="row"><label for="social_telegram">' . __('Telegram Group, Channel or Account', 'under-construction-page') . '</label></th>
    <td><input id="social_telegram" type="text" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_telegram]" value="' . esc_attr($options['social_telegram']) . '" placeholder="' . __('Telegram group, channel or account URL', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Complete URL, with https prefix to Telegram group, channel or account.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" class="hidden">
    <th scope="row"><label for="social_skype">' . __('Skype Username', 'under-construction-page') . '</label></th>
    <td><input id="social_skype" type="text" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_skype]" value="' . esc_attr($options['social_skype']) . '" placeholder="' . __('Skype username or account name', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Skype username or account name.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" class="hidden">
    <th scope="row"><label for="social_whatsapp">' . __('WhatsApp Phone Number', 'under-construction-page') . '</label></th>
    <td><input id="social_whatsapp" type="text" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_whatsapp]" value="' . esc_attr($options['social_whatsapp']) . '" placeholder="' . __('123-456-789', 'under-construction-page') . '">';
    echo '<p class="description">' . __('WhatsApp phone number in international format without + or 00 prefix.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" class="hidden">
    <th scope="row"><label for="social_email">' . __('Email Address', 'under-construction-page') . '</label></th>
    <td><input id="social_email" type="email" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_email]" value="' . esc_attr($options['social_email']) . '" placeholder="' . __('name@domain.com', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Email will be encoded on the page to protect it from email address harvesters.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top" class="hidden">
    <th scope="row"><label for="social_phone">' . __('Phone Number', 'under-construction-page') . '</label></th>
    <td><input id="social_phone" type="tel" class="regular-text" name="' . UCP_OPTIONS_KEY . '[social_phone]" value="' . esc_attr($options['social_phone']) . '" placeholder="' . __('+1-123-456-789', 'under-construction-page') . '">';
    echo '<p class="description">' . __('Phone number in full international format.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr><th colspan="2"><a id="show-social-icons" href="#" class="js-action">' . __('Show more Social &amp; Contact Icons', 'under-construction-page') . '</a></th></tr>';

    echo '</table>';

    echo '</div>';

    self::footer_buttons();
  } // tab_content


  static function get_themes() {
    $themes = array('_pro_custom' => __('Build Your Own Custom Theme', 'under-construction-page'),
                    'mad_designer' => __('Mad Designer', 'under-construction-page'),
                    '_pro_simple-beige' => __('Simple Beige Text', 'under-construction-page'),
                    'plain_text' => __('Plain Text', 'under-construction-page'),
                    '_pro_simple-office-theme' => __('Simple Office', 'under-construction-page'),
                    'under_construction' => __('Under Construction', 'under-construction-page'),
                    '_pro_model-portfolio' => __('Model Portfolio', 'under-construction-page'),
                    'dark' => __('Things Went Dark', 'under-construction-page'),
                    '_pro_watch-company' => __('The Watch Store', 'under-construction-page'),
                    'forklift' => __('Forklift at Work', 'under-construction-page'),
                    'under_construction_text' => __('Under Construction Text', 'under-construction-page'),
                    '_pro_cloud-business-inc' => __('Cloud Business', 'under-construction-page'),
                    'cyber_chick' => __('Cyber Chick', 'under-construction-page'),
                    'rocket' => __('Rocket Launch', 'under-construction-page'),
                    '_pro_smoothie' => __('Green Smoothie Webinar', 'under-construction-page'),
                    'loader' => __('Loader at Work', 'under-construction-page'),
                    'cyber_chick_dark' => __('Cyber Chick Dark', 'under-construction-page'),
                    '_pro_small-office' => __('Small Office', 'under-construction-page'),
                    'safe' => __('Safe', 'under-construction-page'),
                    'people' => __('People at Work', 'under-construction-page'),
                    '_pro_custom' => __('Build Your Own Custom Theme', 'under-construction-page'),
                    'windmill' => __('Windmill', 'under-construction-page'),
                    'sad_site' => __('Sad Site', 'under-construction-page'),
                    '_pro-soothing-nature' => __('Soothing Nature', 'under-construction-page'),
                    'lighthouse' => __('Lighthouse', 'under-construction-page'),
                    'hot_air_baloon' => __('Hot Air Balloon', 'under-construction-page'),
                    '_pro_business-statistics' => __('Business Statistics', 'under-construction-page'),
                    'people_2' => __('People at Work #2', 'under-construction-page'),
                    'rocket_2' => __('Rocket Launch #2', 'under-construction-page'),
                    '_pro_travel-blog' => __('Travel Blog', 'under-construction-page'),
                    'light_bulb' => __('Light Bulb', 'under-construction-page'),
                    'ambulance' => __('Ambulance', 'under-construction-page'),
                    '_pro_forest-in-the-fog' => __('Forest in the Fog', 'under-construction-page'),
                    'laptop' => __('Laptop', 'under-construction-page'),
                    'puzzles' => __('Puzzles', 'under-construction-page'),
                    '_pro_sunset' => __('Sunset', 'under-construction-page'),
                    'iot' => __('Internet of Things', 'under-construction-page'),
                    'setup' => __('Setup', 'under-construction-page'),
                    '_pro_fitness-studio' => __('Fitness Studio Landing Page', 'under-construction-page'),
                    'stop' => __('Stop', 'under-construction-page'),
                    'clock' => __('Clock', 'under-construction-page'),
                    '_pro_mountain' => __('Mountain Peak', 'under-construction-page'),
                    'bulldozer' => __('Bulldozer at Work', 'under-construction-page'),
                    'christmas' => __('Christmas Greetings', 'under-construction-page'),
                    '_pro_pink-lips' => __('Pink Lips', 'under-construction-page'),
                    'hard_worker' => __('Hard Worker', 'under-construction-page'),
                    'closed' => __('Temporarily Closed', 'under-construction-page'),
                    '_pro_animated-green' => __('Simple Green Animated', 'under-construction-page'),
                    'dumper_truck' => __('Dumper Truck', 'under-construction-page'),
                    '000webhost' => __('000webhost', 'under-construction-page'),
                    '_pro_grayscale-city' => __('Grayscale City', 'under-construction-page'),
                    'work_desk' => __('Work Desk', 'under-construction-page'),
                    'research' => __('Research', 'under-construction-page'),
                    '_pro_wedding' => __('Wedding', 'under-construction-page'));

    $themes = apply_filters('ucp_themes', $themes);

    return $themes;
  } // get_themes


  static function tab_design() {
    $options = self::get_options();

    $img_path = UCP_PLUGIN_URL . 'images/thumbnails/';
    $themes = self::get_themes();

    echo '<table class="form-table">';
    echo '<tr valign="top">
    <td colspan="2"><b style="margin-bottom: 10px; display: inline-block;">' . __('Theme', 'under-construction-page') . '</b> (<a target="_blank" href="' . self::generate_web_link('themes-browse-premium', 'templates') . '">browse 230+ premium themes</a>)<br>';
    echo '<input type="hidden" id="theme_id" name="' . UCP_OPTIONS_KEY . '[theme]" value="' . $options['theme'] . '">';

    foreach ($themes as $theme_id => $theme_name) {
      if ($theme_id === $options['theme']) {
        $class = ' active';
      } else {
        $class = '';
      }
      if (substr($theme_id, 0, 4) == '_pro') {
        $theme_id = substr($theme_id, 5);
        echo '<div class="ucp-thumb ucp-thumb-pro" data-theme-id="' . $theme_id . '"><img src="' . $img_path . 'pro/' . $theme_id . '.jpg" alt="' . $theme_name . '" title="' . $theme_name . '"><span>' . $theme_name . '</span>';
        echo '<div class="buttons"><a href="#" data-pro-ad="activate_' . $theme_id . '" class="open-ucp-upsell button button-primary">Activate</a> ';
        if ($theme_id != 'custom') {
          echo '<a href="https://templates.underconstructionpage.com/?ucp_template_preview&template=' . $theme_id . '&utm_source=ucp-free&utm_medium=plugin&utm_content=design-preview-' . $theme_id . '&utm_campaign=ucp-free-v' . self::$version . '" class="button-secondary" target="_blank">Preview</a>';
        }
        echo '</div>';
        echo '<div title="PRO template" class="ribbon"><i><span class="dashicons dashicons-star-filled"></span></i></div></div>';
      } else {
        echo '<div class="ucp-thumb' . $class . '" data-theme-id="' . $theme_id . '"><img src="' . $img_path . $theme_id . '.png" alt="' . $theme_name . '" title="' . $theme_name . '"><span>' . $theme_name . '</span>';
        echo '<div class="buttons">';
        if ($theme_id !== $options['theme']) {
          echo '<a href="#" class="button button-primary activate-theme">Activate</a> ';
        }
        echo '<a href="' . get_home_url() . '/?ucp_preview&theme=' . $theme_id . '" class="button-secondary" target="_blank">Preview</a></div>';
        echo '</div>';
      }
    } // foreach

    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="custom_css">' . __('Custom CSS', 'under-construction-page') . '</label></th>
    <td>';
    echo '<textarea data-autoresize="1" rows="3" id="custom_css" class="code large-text" name="' . UCP_OPTIONS_KEY . '[custom_css]" placeholder=".selector { property-name: property-value; }">' . esc_textarea($options['custom_css']) . '</textarea>';
    echo '<p class="description">&lt;style&gt; tags will be added automatically. Do not include them in your code.<br>
    For RTL languages support add: <code>body { direction: rtl; }</code><br>If you need help with writing custom CSS code, post your question on the <a href="https://wordpress.org/support/plugin/under-construction-page/" target="_blank">official forum</a>.</p>';
    echo '</td></tr>';

    echo '</table>';

    self::footer_buttons();
  } // tab_design


  // markup & logic for access tab
  static function tab_access() {
    $options = self::get_options();
    $default_options = self::default_options();
    $roles = $users = array();

    $tmp_roles = get_editable_roles();
    foreach ($tmp_roles as $tmp_role => $details) {
      $name = translate_user_role($details['name']);
      $roles[] = array('val' => $tmp_role,  'label' => $name);
    }

    $tmp_users = get_users(array('fields' => array('id', 'display_name')));
    foreach ($tmp_users as $user) {
      $users[] = array('val' => $user->id, 'label' => $user->display_name);
    }

    echo '<div class="ucp-tab-content">';
    echo '<table class="form-table">';

    echo '<tr valign="top">
    <th scope="row"><label for="whitelisted_ips">' . __('Whitelisted IP Addresses', 'under-construction-page') . '</label></th>
    <td>';
    echo '<div class="toggle-wrapper">
      <input type="checkbox" id="whitelisted_ips" type="checkbox" value="1" class="skip-save open-ucp-upsell">
      <label for="whitelisted_ips" class="toggle"><span class="toggle_handler"></span></label>
    </div>';
    echo '<p>Listed IP addresses (both IPv4 and IPv6 are supported) will not be affected by the under construction mode and their users will always see the "normal" site. This is a <a href="#" class="open-ucp-upsell" data-pro-ad="whitelisted_ips">PRO feature</a>.';
    echo '<td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="access_links">' . __('Secret Direct Access Links', 'under-construction-page') . '</label></th>
    <td>';
    echo '<div class="toggle-wrapper">
      <input type="checkbox" id="access_links" type="checkbox" value="1" class="skip-save open-ucp-upsell">
      <label for="access_links" class="toggle"><span class="toggle_handler"></span></label>
    </div>';
    echo '<p>The most flexible and user-friendly way (especially when working with clients) to give only selected visitors access to the "normal" site. Simply generate a new link, configure expiration options (time, number of visits or unique IPs) and share it with users to allow them access to the site. This is a <a href="#" class="open-ucp-upsell" data-pro-ad="access_links">PRO feature</a>.';
    echo '<td></tr>';

    echo '<tr valign="top" id="whitelisted-roles">
    <th scope="row">' . __('Whitelisted User Roles', 'under-construction-page') . '</th>
    <td>';
    foreach ($roles as $tmp_role) {
      echo  '<input name="' . UCP_OPTIONS_KEY . '[whitelisted_roles][]" id="roles-' . $tmp_role['val'] . '" ' . self::checked($tmp_role['val'], $options['whitelisted_roles'], false) . ' value="' . $tmp_role['val'] . '" type="checkbox" /> <label for="roles-' . $tmp_role['val'] . '">' . $tmp_role['label'] . '</label><br />';
    }
    echo '<p class="description">' . __('Selected user roles will <b>not</b> be affected by the under construction mode and will always see the "normal" site. Default: administrator.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="whitelisted_users">' . __('Whitelisted Users', 'under-construction-page') . '</label></th>
    <td><select id="whitelisted_users" class="select2" style="width: 50%; max-width: 300px;" name="' . UCP_OPTIONS_KEY . '[whitelisted_users][]" multiple>';
    self::create_select_options($users, $options['whitelisted_users'], true);

    echo '</select><p class="description">' . __('Selected users (when logged in) will <b>not</b> be affected by the under construction mode and will always see the "normal" site.', 'under-construction-page') . '</p>';
    echo '</td></tr>';

    echo '<tr>';
    echo '<th><label for="url_rules">' . __('URL Based Rules', 'under-construction-page') . '</label></th>';
    echo '<td><select class="skip-save open-ucp-upsell" id="url_rules">';
    echo '<option value="0">Disabled</option>';
    echo '<option class="ucp-promo" value="-1">Listed URLs will NEVER be affected by UCP</option>';
    echo '<option class="ucp-promo" value="-1">ONLY listed URLs CAN BE affected by UCP</option>';
    echo '</select>';
    echo '<p class="description">Use this option to set per URL rules and lock down the entire site except selected pages; or lock down just some pages and leave all others accessible to visitors. If second option is used all other access rules still apply. This is a <a href="#" class="open-ucp-upsell" data-pro-ad="url_rules">PRO feature</a>.</p>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>
    <th scope="row"><label for="direct_access_password">' . __('Direct Access Password', 'under-construction-page') . '</label></th>
    <td>';
    echo '<input id="direct_access_password" type="text" class="skip-save open-ucp-upsell" value="" placeholder="">';
    echo '<p class="description">Direct Access Password is the most user-friendly way (especially when working with clients) to give selected users access to the "normal" site. Choose a password, save changes, and send users this link: <a href="' . get_home_url() . '/#access-site-form">' . get_home_url() . '/#access-site-form</a> or enable the "Password Form Button" option to show the password form button. This is a <a href="#" class="open-ucp-upsell" data-pro-ad="whitelisted_ips">PRO feature</a>.</p>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="site_password">' . __('Password Protect Under Construction Page', 'under-construction-page') . '</label></th>
    <td>';
    echo '<div class="toggle-wrapper">
    <input type="checkbox" id="site_password" type="checkbox" value="1" class="skip-save open-ucp-upsell">
    <label for="site_password" class="toggle"><span class="toggle_handler"></span></label>
    </div>';
    echo '<p class="description">Protected the entire site with a password you choose. Only those with the password can view the under construction page. This is a <a href="#" class="open-ucp-upsell" data-pro-ad="whitelisted_ips">PRO feature</a>.</p>';
    echo '</td></tr>';

    echo '</table>';
    echo '</div>';

    self::footer_buttons();
  } // tab_access


  // support tab - FAQ and links
  static function tab_support() {
    $user = wp_get_current_user();
    $theme = wp_get_theme();
    $options = self::get_options();

    echo '<div id="tabs_support" class="ui-tabs ucp-tabs-2nd-level">';
    echo '<ul>';
    echo '<li><a href="#tab_support_contact">' . __('Contact Support', 'under-construction-page') . '</a></li>';
    echo '<li><a href="#tab_support_faq">' . __('FAQ', 'under-construction-page') . '</a></li>';
    echo '</ul>';

    echo '<div style="display: none;" id="tab_support_faq" class="ucp-tab-content">';

    echo '<p><b>Do you have any documentation?</b><br>Yes, <a href="' . self::generate_web_link('faq-documentation', 'documentation-free') . '" target="_blank">detailed documentation</a> with how-to guides is available on the plugins\'s site.</p>';

    echo '<p><b>Do you have a video to help me get started?</b><br>We sure do! <a href="https://www.youtube.com/watch?v=RN4XABhK7_w" target="_blank">Getting started with the UnderConstructionPage plugin</a>. If that doesn\'t help we also have an <a href="https://www.youtube.com/watch?v=K3DF-NP6Fog" target="_blank">in-depth video walktrough</a>. In case you\'re still uncertain about something don\'t hesitate to contact our friendly support.</p>';

    echo '<p><b>UCP is disabled but Twitter and Facebook still show it as my site\'s preview/thumbnail when I post the URL</b><br>Twitter and Facebook have their own cache which has to be refreshed. You can either wait and the problem will resolve itself in about a day or you can manually refresh the cache.<br>For Facebook open the <a href="https://developers.facebook.com/tools/debug/" target="_blank">Debugger</a>, input the URL, click "Debug". Once the results who up click "Scrape Again" to fetch the latest version of the page.<br>For Twitter, open the <a href="https://cards-dev.twitter.com/validator" target="_blank">Card validator</a>, enter the URL and click "Preview card". Latest version of the site should appear.</p>';

    echo '<p><b>How can I build a custom page or customize themes?</b><br>If you know how to code custom CSS a lot can be accomplished by using the <a class="change_tab" data-tab="1" href="#custom_css">custom CSS</a> option. A much faster and easier solution is to check out <a href="#" data-pro-ad="faq-custom-css" class="open-ucp-upsell">UCP PRO</a> with an easy-to-use drag&amp;drop builder and dozens of pre-built themes.</p>';

    echo '<p><b>How can I check if construction mode is really enabled on my site?</b><br>If the under construction status is green in the admin bar (the very top of the page, above this text), then it\'s enabled. But we made a tool specifically for these kinds of situations so you can double-check everything. <a href="' . self::generate_web_link('faq-tester', 'under-construction-tester/', array('url' => get_home_url())) . '" target="_blank">Run under construction mode tester</a>.</p>';

    echo '<p><b>How can I work on my site while construction mode is enabled?</b><br>Make sure your user role (probably admin) is selected under <a class="change_tab" data-tab="3" href="#whitelisted-roles">Access - Whitelisted User Roles</a> and open the site while logged in.</p>';

    echo '<p><b>How can I log in / access WordPress admin after construction mode has been enabled?</b><br>Enable the <a class="change_tab" data-tab="2" href="#login_button_wrap">Login Button</a> option under Content, and a login link will be shown in the lower right corner of the under construction page.</p>';

    echo '<p><b>How do I add my logo to the page?</b><br>Head over to <a class="change_tab" data-tab="2" href="#content_wrap">Content</a> and click "Add Media". Upload/select the logo, position it as you see fit and add other content.</p>';

    echo '<p><b>I\'ve made changes to UCP, but they are not visible. What do I do?</b><br>Click "Save Changes" one more time. Open your site and force refresh browser cache (Ctrl or Shift + F5). If that doesn\'t help it means you have a caching plugin installed. Purge/delete cache in that plugin or disable it.</p>';

    echo '<p><b>How can I get more designs? Where do I download them?</b><br>We update the plugin every 7-10 days and each update comes with at least one new theme/design. There is no other way of getting more designs nor a place to download them.</p>';

    echo '<p><b>How can I edit designs?</b><br>There is an option to add <a class="change_tab" data-tab="1" href="#custom_css">custom CSS</a>. If you want more than that you will have to edit the source files located in <code>/under-construction-page/themes/</code>.</p>';

    echo '<p><b>I have disabled UCP but the under construction page is still visible. How do I remove it?</b><br>Open your site and force refresh browser cache (Ctrl or Shift + F5). If that doesn\'t help it means you have a caching plugin installed. Purge/delete cache in that plugin or disable it. If that fails too contact your hosting provider and ask to empty the site cache for you.</p>';

    echo '<p><b>I have disabled UCP but the site\'s favicon is still the UCP logo. How do I change/remove it?</b><br>Make sure your theme has a favicon defined and empty all caches - browser and server ones. Open the site and force refresh browser cache (Ctrl or Shift + F5). If that doesn\'t help it means you have a caching plugin installed. Purge/delete cache in that plugin or disable it. If that fails too contact your hosting provider and ask to empty the site cache for you.</p>';

    echo '</div>'; // faq

    echo '<div style="display: none;" id="tab_support_contact" class="ucp-tab-content">';

    echo '<div id="support-hero"><p>Our average response time is 1.5h! <b>85% of tickets are resolved within 1 hour!</b> No ticket is left unanswered. If something is not working, don\'t think twice;</p><p><a class="button button-primary" href="https://wordpress.org/support/plugin/under-construction-page/#new-post" target="_blank">OPEN A SUPPORT TICKET NOW</a></p></div>';

    echo '<p class="description">Our support agents need this info to provide faster &amp; better support. Please include the following data in your message;</p>';
    echo '<p>WordPress version: <code>' . get_bloginfo('version') . '</code><br>';
    echo 'UCP Version: <code>' . self::$version . '</code><br>';
    echo 'PHP Version: <code>' . PHP_VERSION . '</code><br>';
    echo 'Site URL: <code>' . get_bloginfo('url') . '</code><br>';
    echo 'WordPress URL: <code>' . get_bloginfo('wpurl') . '</code><br>';
    echo 'Theme: <code>' . $theme->get('Name') . ' v' . $theme->get('Version') . '</code>';
    echo '</p>';

    echo '</div>'; // contact

    echo '</div>'; // tabs
  } // tab_support


  // tab PRO
  static function tab_pro() {
    $options = self::get_options();

    echo '<div class="ucp-tab-content">';
    echo '<h3 class="ucp-pro-logo"><a href="' . self::generate_web_link('pro-tab-logo') . '" target="_blank"><img src="' . UCP_PLUGIN_URL . 'images/ucp_pro_logo.png" alt="UnderConstructionPage PRO" title="UnderConstructionPage"></a></h3>';

    if (UCP_license::is_activated()) {
      $plugin = plugin_basename(__FILE__);
      $update_url = wp_nonce_url(admin_url('update.php?action=upgrade-plugin&amp;plugin=' . urlencode($plugin)), 'upgrade-plugin_' . $plugin);
      echo '<p style="text-align: center;"><a href="' . $update_url . '" class="button button-primary button-large">Update UnderConstructionPage files to PRO</a><br><br></p>';
    } else {
      echo '<div id="ucp-earlybird"><span>Build <b>landing pages, coming soon pages, maintenance &amp; under construction pages</b> faster &amp; easier!</span>';
      if (self::is_promo_active() == 'welcome') {
        echo '<p class="textcenter"><a data-pro-ad="get_pro" href="#" class="button button-primary button-large open-ucp-upsell">Get <b>PRO</b> now with a LIMITED <b>welcoming discount</b>! Offer is valid for only <b class="ucp-countdown">59min 33sec</b>.</a></p>';
      } elseif (self::is_promo_active() == 'olduser') {
        echo '<p class="textcenter"><a data-pro-ad="get_pro" href="#" class="button button-primary button-large open-ucp-upsell">Get <b>PRO</b> now with a special <b>DISCOUNT for long-term users</b>!</a></p>';
      } else {
        echo '<p class="textcenter"><a data-pro-ad="get_pro" href="#" class="button button-primary button-large open-ucp-upsell">Get <b>PRO</b> now!</a></p>';
      }
      echo '</div>';
    }
    echo '</div>';


    echo '<div class="ucp-tab-content">';
    echo '<table class="form-table">';

    echo '<tr valign="top">
    <th scope="row"><label for="license_key">' . __('License Key', 'under-construction-page') . '</label></th>';
    echo '<td><input type="text" id="license_key" class="regular-text" name="' . UCP_OPTIONS_KEY . '[license_key]" value="' . esc_attr($options['license_key']) . '" placeholder="12345-12345-12345-12345" />';
    echo '<p class="description">License key is located in the confirmation email you received after purchasing.<br>In case of any problems, please contact <a href="#" data-tab="4" class="change_tab">support</a>. If you don\'t have a PRO license key - <a data-pro-ad="get_key" href="#" class="open-ucp-upsell">get it now</a>.</p>';
    echo '</td></tr>';

    if (!empty($options['license_key'])) {
      if (UCP_license::is_activated()) {
        if ($options['license_expires'] == '2035-01-01') {
          $valid = 'indefinitely';
        } else {
          $valid = 'until ' . date('F jS, Y', strtotime($options['license_expires']));
          if (date('Y-m-d') == $options['license_expires']) {
            $valid .= '; expires today';
          } elseif (date('Y-m-d', time() + 30 * DAY_IN_SECONDS) > $options['license_expires']) {
            $tmp = (strtotime($options['license_expires'] . date(' G:i:s')) - time()) / DAY_IN_SECONDS;
            $valid .= '; expires in ' . round($tmp) . ' days';
          }
        }
        echo '<tr>
            <th scope="row"><label for="">' . __('License Status', 'under-construction-page') . '</label></th>
            <td><b style="color: #66b317;">Active</b><br>
            Type: ' . str_replace('pro', 'PRO', $options['license_type']);
        echo '<br>Valid ' . $valid . '</td>
            </tr>';
      } else {
        echo '<tr>
            <th scope="row"><label for="">' . __('License Status', 'under-construction-page') . '</label></th>
            <td><b style="color: #ea1919;">Inactive</b>';
        if (!empty($options['license_type'])) {
          echo '<br>Type: ' . $options['license_type'];
        }
        if (!empty($options['license_expires']) && $options['license_expires'] != '1900-01-01' && $options['license_expires'] != '1970-01-01') {
          echo '<br>Expired on ' . date('F jS, Y', strtotime($options['license_expires']));
        }
        echo '</td></tr>';
      }
    }

    echo '</table>';
    echo '</div>';

    echo '<p class="submit">';
    echo get_submit_button(__('Save &amp; Validate License Key', 'under-construction-page'), 'large secondary', 'license-submit', false);
    echo '<br><br><small><i>By attempting to activate a license you agree to share the following data with <a href="https://www.webfactoryltd.com/" target="_blank">WebFactory Ltd</a>: license key, site URL, site title, site WP version, and UnderConstructionPage (free) version.</i></small>';
    echo '</p>';
  } // tab_pro


  // output the whole options page
  static function main_page() {
    if (!current_user_can('manage_options'))  {
      wp_die('You do not have sufficient permissions to access this page.');
    }

    echo '<div class="wrap">
          <h1 class="ucp-logo"><a href="' . admin_url('options-general.php?page=ucp') . '"><img src="' . UCP_PLUGIN_URL . 'images/ucp_logo.png" class="rotate" alt="UnderConstructionPage" title="UnderConstructionPage"><img src="' . UCP_PLUGIN_URL . 'images/ucp_logo_2.png" class="ucp-logo-text" alt="UnderConstructionPage" title="UnderConstructionPage"></a></h1>';

    echo '<form action="options.php" method="post" id="ucp_form">';
    settings_fields(UCP_OPTIONS_KEY);

    $tabs = array();
    $tabs[] = array('id' => 'ucp_main', 'icon' => 'dashicons-admin-settings', 'class' => '', 'label' => __('Main', 'under-construction-page'), 'callback' => array(__CLASS__, 'tab_main'));
    $tabs[] = array('id' => 'ucp_design', 'icon' => 'dashicons-admin-customizer', 'class' => '', 'label' => __('Design', 'under-construction-page'), 'callback' => array(__CLASS__, 'tab_design'));
    $tabs[] = array('id' => 'ucp_content', 'icon' => 'dashicons-format-aside', 'class' => '', 'label' => __('Content', 'under-construction-page'), 'callback' => array(__CLASS__, 'tab_content'));
    $tabs[] = array('id' => 'ucp_access', 'icon' => 'dashicons-shield', 'class' => '', 'label' => __('Access', 'under-construction-page'), 'callback' => array(__CLASS__, 'tab_access'));
    $tabs[] = array('id' => 'ucp_support', 'icon' => 'dashicons-sos', 'class' => '', 'label' => __('Support', 'under-construction-page'), 'callback' => array(__CLASS__, 'tab_support'));
    $tabs[] = array('id' => 'ucp_pro', 'icon' => 'dashicons-star-filled', 'class' => '', 'label' => __('PRO', 'under-construction-page'), 'callback' => array(__CLASS__, 'tab_pro'));

    $tabs = apply_filters('ucp_tabs', $tabs);

    echo '<div id="ucp_tabs" class="ui-tabs" style="display: none;">';
    echo '<ul class="ucp-main-tab">';
    foreach ($tabs as $tab) {
      if (!empty($tab['label'])){
          echo '<li><a href="#' . $tab['id'] . '" class="' . $tab['class'] . '"><span class="icon"><span class="dashicons ' . $tab['icon'] . '"></span></span><span class="label">' . $tab['label'] . '</span></a></li>';
      }
    }
    echo '</ul>';

    foreach ($tabs as $tab) {
      if (is_callable($tab['callback'])) {
        echo '<div style="display: none;" id="' . $tab['id'] . '">';
        call_user_func($tab['callback']);
        echo '</div>';
      }
    } // foreach
    echo '</div>'; // ucp_tabs

    echo '</form>'; // ucp_tabs
    echo '</div>'; // wrap

    // weglot install dialog
    echo '<div id="weglot-upsell-dialog" style="display: none;" title="Weglot"><span class="ui-helper-hidden-accessible"><input type="text"/></span>';
    echo '<div style="padding: 20px; font-size: 15px;">';
    echo '<ul class="ucp-list">';
    echo '<li>Best-rated WordPress multilingual plugin</li>';
    echo '<li>Simple 5-minute set-up. No coding required</li>';
    echo '<li>Accelerated translation management: Machine & human translations with access to professional translators</li>';
    echo '<li>Compatible with any WordPress theme or plugin</li>';
    echo '<li>Optimized for multilingual SEO</li>';
    echo '<li>10-day Free trial and free plan available</li>';
    echo '</ul>';
    echo '<p class="upsell-footer"><a class="button button-primary" id="install-weglot">Install &amp; activate Weglot to make your website multilingual</a></p>';
    echo '</div>';
    echo '</div>';
    // weglot install dialog

    $promo = self::is_promo_active();
    if ($promo == 'welcome') {
      $header = 'A <b>welcoming discount</b> has been applied to selected packages! It\'s <b>time limited</b> and available for only another <b class="ucp-countdown">59min 30sec</b>.';
      $products['agency'] = array('link' => self::generate_web_link('pricing-table', 'buy/', array('p' => 'agency-lifetime-welcome', 'r' => 'UCP v' . self::$version)), 'price' => 'BUY NOW <u>$51 OFF</u><br><del>$250</del> $199<br><small>Discount ends in <b class="ucp-countdown">59min 30sec</b></small>');
      $products['pro-lifetime'] = array('link' => self::generate_web_link('pricing-table', 'buy/', array('p' => 'pro-lifetime-welcome', 'r' => 'UCP v' . self::$version)), 'price' => 'BUY NOW <u>20% OFF</u><br><del>$69</del> $55<br><small>Discount ends in <b class="ucp-countdown">59min 30sec</b></small>');
      $products['pro-yearly'] = array('link' => self::generate_web_link('pricing-table', 'buy/', array('p' => 'pro-monthly', 'r' => 'UCP v' . self::$version)), 'price' => 'BUY NOW<br>$8.99<small>/month</small>');
    } elseif ($promo == 'olduser') {
      $header = 'A special <b>discount for long-term users</b> has been applied to selected packages!';
      $products['agency'] = array('link' => self::generate_web_link('pricing-table', 'buy/', array('p' => 'agency-lifetime-olduser', 'r' => 'UCP v' . self::$version)), 'price' => 'BUY NOW <u>$51 OFF</u><br><del>$250</del> $199');
      $products['pro-lifetime'] = array('link' => self::generate_web_link('pricing-table', 'buy/', array('p' => 'pro-lifetime-olduser', 'r' => 'UCP v' . self::$version)), 'price' => 'BUY NOW <u>20% OFF</u><br><del>$69</del> $55');
      $products['pro-yearly'] = array('link' => self::generate_web_link('pricing-table', 'buy/', array('p' => 'pro-monthly', 'r' => 'UCP v' . self::$version)), 'price' => 'BUY NOW<br>$8.99<small>/month</small>');
    } else {
      $header = '';
      $products['agency'] = array('link' => self::generate_web_link('pricing-table', 'buy/', array('p' => 'agency-lifetime', 'r' => 'UCP v' . self::$version)), 'price' => 'BUY NOW<br>$250');
      $products['pro-lifetime'] = array('link' => self::generate_web_link('pricing-table', 'buy/', array('p' => 'pro-lifetime', 'r' => 'UCP v' . self::$version)), 'price' => 'BUY NOW<br>$69');
      $products['pro-yearly'] = array('link' => self::generate_web_link('pricing-table', 'buy/', array('p' => 'pro-monthly', 'r' => 'UCP v' . self::$version)), 'price' => 'BUY NOW<br>$8.99<small>/month</small>');
    }

    // upsell dialog
    echo '<div id="upsell-dialog" style="display: none;" title="UnderConstructionPage PRO"><span class="ui-helper-hidden-accessible"><input type="text"/></span>';
    echo '<div id="tabs_upsell" class="ui-tabs ucp-tabs-2nd-level">';
    echo '<ul>';
    echo '<li><a href="#tab-upsell-buy">' . __('Get PRO', 'under-construction-page') . '</a></li>';
    echo '<li><a href="#tab-upsell-features">' . __('Features', 'under-construction-page') . '</a></li>';
    echo '</ul>';

    echo '<div class="upsell-tab" id="tab-upsell-features" style="display: none;">';
    echo '<div class="ucp-pro-feature">';
    echo '<span>Frictionless Drag &amp; Drop Builder</span>';
    echo '<p>Forget about complicated, cumbersome builders that have too many options! UCP builder was purpose-built for the task at hand. Simple, user-friendly &amp; has only the options you need to build pages fast!</p>';
    echo '</div>';

    echo '<div class="ucp-pro-feature">';
    echo '<span>2.5+ Million HD Searchable Images</span>';
    echo '<p>There\'s nothing worse than googling for hours just to find that the perfect image you need is either copyrighted or too small. Enjoy a vast library of 4K+ sized images - categorized &amp; copyright free!</p>';
    echo '</div>';

    echo '<div class="ucp-pro-feature">';
    echo '<span>220+ Templates</span>';
    echo '<p>Building your own page from scratch is fun, but often you don\'t have time to do it! Use one of our purpose-built templates, change a few lines of text and you\'re ready to rock!</p>';
    echo '</div>';

    echo '<div class="ucp-pro-feature">';
    echo '<span>Affiliate & Traffic Tracking</span>';
    echo '<p>Having traffic is nice. Having targeted traffic is better! Generate tracked inbound links &amp; share them on social media or with your affiliates to pinpoint the best traffic sources.</p>';
    echo '</div>';

    echo '<div class="ucp-pro-feature">';
    echo '<span>Unlimited 3rd Party Integrations</span>';
    echo '<p>With our unique universal autoresponder support, you can integrate any email autoresponder or webinar system in a page within seconds. Or push data to Zapier to more than 1,000 applications.</p>';
    echo '</div>';

    echo '<div class="ucp-pro-feature">';
    echo '<span>Made for Agencies &amp; Webmasters</span>';
    echo '<p>Creating sites for others? We have your back! Our features, support &amp; licensing options are optimised for agencies while in-house, USA based support guarantee your peace of mind.</p>';
    echo '</div>';

    echo '<p class="upsell-footer">For a complete list of features, demos and screenshots visit <a href="' . self::generate_web_link('features-more-info') . '" target="_blank">underconstructionpage.com</a>. Already have a PRO license? <a href="#" class="go-to-license-key">Activate it</a>.</p>';

    echo '</div>'; // features tab
    echo '<div class="upsell-tab" id="tab-upsell-buy" style="display: none;">';
    if (!empty($header)) {
      echo '<div class="upsell-header">' . $header . '</div>';
    }
    echo '<table id="ucp-pricing-table">
  <tbody>
    <tr>
      <td>
        <h3>Lifetime<br>Agency License</h3>
      </td>
      <td>
        <h3>Lifetime<br>PRO License</h3>
      </td>
      <td>
        <h3>Personal<br>PRO License</h3>
      </td>
    </tr>
    <tr>
      <td>One Time Payment</td>
      <td>One Time Payment</td>
      <td>Monthly/Yearly Payment</td>
    </tr>
    <tr>
      <td>100 Client or Personal Sites<br>(licenses are transferable between sites)</td>
      <td>3 Personal or Client Sites</td>
      <td>3 Personal Sites</td>
    </tr>
    <tr>
      <td>White-Label License Mode</td>
      <td><span class="dashicons dashicons-no"></td>
      <td><span class="dashicons dashicons-no"></td>
    </tr>
    <tr>
      <td>Lifetime Priority Support &amp; Updates</td>
      <td>Lifetime Support &amp; Updates</td>
      <td>1 Month/Year of Support &amp; Updates</td>
    </tr>
    <tr style="display: none;">
      <td>1 Million+ Hi-Res Images</td>
      <td>1 Million+ Hi-Res Images</td>
      <td>1 Million+ Hi-Res Images</td>
    </tr>
    <tr>
      <td>Drag&amp;Drop Builder</td>
      <td>Drag&amp;Drop Builder</td>
      <td>Drag&amp;Drop Builder</td>
    </tr>
    <tr>
      <td>120+ PRO Templates</td>
      <td>120+ PRO Templates</td>
      <td>120+ PRO Templates</td>
    </tr>
    <tr>
      <td>110+ Agency Templates</td>
      <td><span class="dashicons dashicons-no"></td>
      <td><span class="dashicons dashicons-no"></td>
    </tr>
    <tr>
      <td>Zapier Integration + Extra Modules</td>
      <td><span class="dashicons dashicons-no"></td>
      <td><span class="dashicons dashicons-no"></td>
    </tr>
    <tr class="bb0">
      <td>
        <a data-href-org="' . $products['agency']['link'] . '" class="promo-button go-to-license-key" href="' . $products['agency']['link'] . '" target="_blank">' . $products['agency']['price'] . '</a>
      </td>
      <td>
        <a data-href-org="' . $products['pro-lifetime']['link'] . '" class="promo-button go-to-license-key" href="' . $products['pro-lifetime']['link'] . '" target="_blank">' . $products['pro-lifetime']['price'] . '</a>
      </td>
      <td>
        <a data-href-org="' . $products['pro-yearly']['link'] . '" class="promo-button go-to-license-key" href="' . $products['pro-yearly']['link'] . '" target="_blank">' . $products['pro-yearly']['price'] . '</a>
      </td>
    </tr>
    <tr class="bb0">
    <td colspan="3"><span class="instant-download"><span class="dashicons dashicons-yes"></span> Secure payment <span class="dashicons dashicons-yes"></span> Instant activation from WP admin <span class="dashicons dashicons-yes"></span> 100% No-Risk Money Back Guarantee</span></td>
    </tr>
  </tbody>
</table>';
    echo '<p class="upsell-footer">More pricing options &amp; details about packages are available on <a href="' . self::generate_web_link('pricing-table-more-info') . '" target="_blank">underconstructionpage.com</a>. Already have a PRO license? <a href="#" class="go-to-license-key">Activate it</a>.</p>';
    echo '</div>'; // pricing tab
    echo '</div>';

    echo '</div>'; // upsell-dialog
  } // main_page


  // tests if any of the promotions are active and if so returns the name
  static function is_promo_active() {
    $meta = self::get_meta();

    if ((time() - $meta['first_install']) < HOUR_IN_SECONDS) {
      return 'welcome';
    }

    if ((time() - $meta['first_install']) > DAY_IN_SECONDS * 35) {
      return 'olduser';
    }

    return false;
  } // is_promo_active


  // save and preview buttons
  static function footer_buttons() {
    echo '<p class="submit">';
    echo get_submit_button(__('Save Changes', 'under-construction-page'), 'primary large', 'submit', false);
    echo ' &nbsp; &nbsp; <a id="ucp_preview" href="' . get_home_url() . '/?ucp_preview" class="button button-large button-secondary" target="_blank">' . __('Preview', 'under-construction-page') . '</a>';
    echo '</p>';
  } // footer_buttons


  // reset all pointers to default state - visible
  static function reset_pointers() {
    $pointers = array();

    $pointers['welcome'] = array('target' => '#menu-settings', 'edge' => 'left', 'align' => 'right', 'content' => 'Thank you for installing the <b style="font-weight: 800; font-variant: small-caps;">UnderConstructionPage</b> plugin! Please open <a href="' . admin_url('options-general.php?page=ucp'). '">Settings - UnderConstruction</a> to create a beautiful under construction page.');
    $pointers['getting_started'] = array('target' => '.ucp-main-tab li:nth-child(2)', 'edge' => 'top', 'align' => 'left', 'content' => 'Watch the short <a href="https://www.youtube.com/watch?v=RN4XABhK7_w" target="_blank">getting started video</a> to get you up to speed with UCP in no time. If that doesn\'t answer your questions watch the longer <a href="https://www.youtube.com/watch?v=K3DF-NP6Fog" target="_blank">in-depth video walktrough</a>.<br>If you need the videos later, links are in the <a href="#" class="change_tab" data-tab="4">FAQ</a>.');

    update_option(UCP_POINTERS_KEY, $pointers);
  } // reset_pointers


  // auto download / install / activate Weglot plugin
  static function install_weglot() {
    if (false === current_user_can('administrator')) {
      wp_die('Sorry, you have to be an admin to run this action.');
    }

    $plugin_slug = 'weglot/weglot.php';
    $plugin_zip = 'https://downloads.wordpress.org/plugin/weglot.latest-stable.zip';

    @include_once ABSPATH . 'wp-admin/includes/plugin.php';
    @include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    @include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    @include_once ABSPATH . 'wp-admin/includes/file.php';
    @include_once ABSPATH . 'wp-admin/includes/misc.php';
		echo '<style>
		body{
			font-family: sans-serif;
			font-size: 14px;
			line-height: 1.5;
			color: #444;
		}
		</style>';

    echo '<div style="margin: 20px; color:#444;">';
    echo 'If things are not done in a minute <a target="_parent" href="' . admin_url('plugin-install.php?s=weglot&tab=search&type=term') .'">install the plugin manually via Plugins page</a><br><br>';
    echo 'Starting ...<br><br>';

		wp_cache_flush();
    $upgrader = new Plugin_Upgrader();
    echo 'Check if Weglot is already installed ... <br />';
    if (self::is_plugin_installed($plugin_slug)) {
      echo 'Weglot is already installed! <br /><br />Making sure it\'s the latest version.<br />';
      $upgrader->upgrade($plugin_slug);
      $installed = true;
    } else {
      echo 'Installing Weglot.<br />';
      $installed = $upgrader->install($plugin_zip);
    }
    wp_cache_flush();

    if (!is_wp_error($installed) && $installed) {
      echo 'Activating Weglot.<br />';
      $activate = activate_plugin($plugin_slug);

      if (is_null($activate)) {
        echo 'Weglot Activated.<br />';

        echo '<script>setTimeout(function() { top.location = "options-general.php?page=ucp"; }, 1000);</script>';
        echo '<br>If you are not redirected in a few seconds - <a href="options-general.php?page=ucp" target="_parent">click here</a>.';
      }
    } else {
      echo 'Could not install Weglot. You\'ll have to <a target="_parent" href="' . admin_url('plugin-install.php?s=weglot&tab=search&type=term') .'">download and install manually</a>.';
    }

    echo '</div>';
  } // install_weglot


  static function is_plugin_installed($slug) {
		if (!function_exists('get_plugins')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$all_plugins = get_plugins();

		if (!empty($all_plugins[$slug])) {
			return true;
		} else {
			return false;
		}
  } // is_plugin_installed


  // check if Weglot plugin is active and min version installed
  static function is_weglot_active() {
    if (!function_exists('is_plugin_active') || !function_exists('get_plugin_data')) {
     require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if (is_plugin_active('weglot/weglot.php')) {
      $weglot_info = get_plugin_data(ABSPATH . 'wp-content/plugins/weglot/weglot.php');
      if (version_compare($weglot_info['Version'], '2.5', '<')) {
        return false;
      } else {
        return true;
      }
    } else {
      return false;
    }
  } // is_weglot_active


  // check if Weglot is completely set up
  static function is_weglot_setup() {
    if (!self::is_weglot_active()) {
      return false;
    }

    $active_languages = weglot_get_destination_language();
    if (!empty($active_languages)) {
      return true;
    } else {
      return false;
    }
  } // is_weglot_setup


  // reset pointers on activation
  static function activate() {
    self::reset_pointers();
    self::empty_cache();
  } // activate


  // clean up on deactivation
  static function deactivate() {
    delete_option(UCP_POINTERS_KEY);
    delete_option(UCP_NOTICES_KEY);
    self::empty_cache();
  } // deactivate


  // clean up on uninstall
  static function uninstall() {
    delete_option(UCP_OPTIONS_KEY);
    delete_option(UCP_META_KEY);
    delete_option(UCP_POINTERS_KEY);
    delete_option(UCP_NOTICES_KEY);
    self::empty_cache();
  } // uninstall
} // class UCP


// hook everything up
register_activation_hook(__FILE__, array('UCP', 'activate'));
register_deactivation_hook(__FILE__, array('UCP', 'deactivate'));
register_uninstall_hook(__FILE__, array('UCP', 'uninstall'));
add_action('init', array('UCP', 'init'));
add_action('plugins_loaded', array('UCP', 'plugins_loaded'));
