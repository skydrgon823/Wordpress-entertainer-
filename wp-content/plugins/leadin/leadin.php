<?php

namespace Leadin;

/**
 * Plugin Name: HubSpot All-In-One Marketing - Forms, Popups, Live Chat
 * Plugin URI: http://www.hubspot.com/integrations/wordpress
 * Description: HubSpot’s official WordPress plugin allows you to add forms, popups, and live chat to your website and integrate with the best WordPress CRM.
 * Version: 9.0.123
 * Author: HubSpot
 * Author URI: http://hubspot.com/products/wordpress
 * License: GPL v3
 * Text Domain: leadin
 * Domain Path: /languages/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// =============================================
// Define Constants
// =============================================
if ( ! defined( 'LEADIN_BASE_PATH' ) ) {
	define( 'LEADIN_BASE_PATH', __FILE__ );
}

if ( ! defined( 'LEADIN_PATH' ) ) {
	define( 'LEADIN_PATH', untrailingslashit( plugins_url( '', LEADIN_BASE_PATH ) ) );
}

if ( ! defined( 'LEADIN_PLUGIN_DIR' ) ) {
	define( 'LEADIN_PLUGIN_DIR', untrailingslashit( dirname( LEADIN_BASE_PATH ) ) );
}

if ( ! defined( 'LEADIN_REQUIRED_WP_VERSION' ) ) {
	define( 'LEADIN_REQUIRED_WP_VERSION', '4.0' );
}

if ( ! defined( 'LEADIN_REQUIRED_PHP_VERSION' ) ) {
	define( 'LEADIN_REQUIRED_PHP_VERSION', '5.6' );
}

if ( ! defined( 'LEADIN_PLUGIN_VERSION' ) ) {
	define( 'LEADIN_PLUGIN_VERSION', '9.0.123' );
}

if ( ! defined( 'LEADIN_JS_BASE_PATH' ) ) {
	define( 'LEADIN_JS_BASE_PATH', LEADIN_PATH . '/js/dist' );
}

// =============================================
// Set autoload
// =============================================
require_once LEADIN_PLUGIN_DIR . '/vendor/autoload.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

use \Leadin\Leadin;

$leadin = new Leadin();
