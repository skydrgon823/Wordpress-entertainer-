<?php
/*
Plugin Name: oAuth Twitter Feed for Developers
Description: Twitter API 1.1 compliant plugin that provides a function to get an array of tweets from the auth'd users Twitter feed for use in themes.
Version: 2.0.3
License: MIT
License URI: http://opensource.org/licenses/MIT
Author: Storm Consultancy (Liam Gladdy)
Author URI: http://www.stormconsultancy.co.uk
*/

if ( !class_exists('StormTwitter')) {
    require('StormTwitter.class.php');
}

/* implement getTweets */
function getTweets($username = false, $count = 20, $options = false) {

  $config['key'] = trim(sq_option('tdf_consumer_key'));
  $config['secret'] = trim(sq_option('tdf_consumer_secret'));
  $config['token'] = trim(sq_option('tdf_access_token'));
  $config['token_secret'] = trim(sq_option('tdf_access_token_secret'));
  $config['screenname'] = trim(sq_option('tdf_user_timeline'));
  $config['cache_expire'] = intval(sq_option('tdf_cache_expire'));
  if ($config['cache_expire'] < 1) $config['cache_expire'] = 3600;
  $config['directory'] = plugin_dir_path(__FILE__);
  
  $obj = new StormTwitter($config);
  $res = $obj->getTweets($count, $username, $options);
  update_option('tdf_last_error',$obj->st_last_error);
  return $res;
  
}
