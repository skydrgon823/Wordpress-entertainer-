<?php
/*
 * UnderConstructionPage
 * PRO license related functions
 * (c) WebFactory Ltd, 2015 - 2021
 */

class UCP_license extends UCP {
  // hook things up
  static function init() {
  } // init

  // check if license key is valid and not expired
  static function is_activated() {
    $options = parent::get_options();

    if (!empty($options['license_active']) && $options['license_active'] === true &&
        !empty($options['license_expires']) && $options['license_expires'] >= date('Y-m-d')) {
      return true;
    } else {
      return false;
    }
  } // is_activated


  // check if activation code is valid
  static function validate_license_key($code) {
    $out = array('success' => false, 'license_active' => false, 'license_key' => $code, 'error' => '', 'license_type' => '', 'license_expires' => '1900-01-01');
    $result = self::query_licensing_server('validate_license', array('license_key' => $code));

    if (false === $result) {
      $out['error'] = 'Unable to contact licensing server. Please try again in a few moments.';
    } elseif (!is_array($result['data']) || sizeof($result['data']) != 4) {
      $out['error'] = 'Invalid response from licensing server. Please try again later.';
    } else {
      $out['success'] = true;
      $out = array_merge($out, $result['data']);
    }

    return $out;
  } // validate_license_key


  // run any query on licensing server
  static function query_licensing_server($action, $data = array(), $method = 'GET', $array_response = true) {
    $options = parent::get_options();
    $request_params = array('sslverify' => false, 'timeout' => 25, 'redirection' => 2);
    $default_data = array('license_key' => $options['license_key'],
                          'code_base' => 'free',
                          '_rand' => rand(1000, 9999),
                          'version' => self::$version,
                          'site' => get_home_url());

    $request_data = array_merge($default_data, $data, array('action' => $action));

    $url = add_query_arg($request_data, parent::$licensing_servers[0]);
    $response = wp_remote_get(esc_url_raw($url), $request_params);

    if (is_wp_error($response) || !($body = wp_remote_retrieve_body($response)) || !($result = @json_decode($body, $array_response))) {
      $url = add_query_arg($request_data, parent::$licensing_servers[1]);
      $response = wp_remote_get(esc_url_raw($url), $request_params);
      $body = wp_remote_retrieve_body($response);
      $result = @json_decode($body, $array_response);
    }

    $result['success'] = true;

    if (!is_array($result) || !isset($result['success'])) {
      return false;
    } else {
      return $result;
    }
  } // query_licensing_server
} // class UCP_license
