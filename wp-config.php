<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache


/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'vendor' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'a7pbo2limvep0gshn4kab98ablvu5jawwaltbqp8fkj9ktzyslim8bo5xmnfllob' );
define( 'SECURE_AUTH_KEY',  '4mxgjgktvhs9htdquzgcgbk1l7x5h2fezvrxvik4ah3envgb6dqbqttnf5dddodx' );
define( 'LOGGED_IN_KEY',    'oziweivcjc4hgc2kutewbsh73tavdzrwfukpgkosnkmz4o0m9f2cc6lhnmlrcq3e' );
define( 'NONCE_KEY',        'wvolam5qmgn9ou7m1klvmbev1vdrdd3ojkyjt2z7agsi6e3gs6jxo6tgrmktwww4' );
define( 'AUTH_SALT',        'pnzrlbsayzq4t2kxrkf359vqqxwzsdoaa6uwvt9ry0lqd0cizs1p0uozeuonm3rm' );
define( 'SECURE_AUTH_SALT', 'oehp8i7ea98yibue0i3yqga5jdq4uvpa7bm04orizx7oc1bprudbpnao14uvsycp' );
define( 'LOGGED_IN_SALT',   'yzlbqtcxjsbcnjfsbklgfgolojwlaknmhvay8mtb4jxjwdcze7eplumhw2wkvtfp' );
define( 'NONCE_SALT',       'idbunyfqruujmn2hcervtdb8x2vovkz6x774jcabmchvyx5r4zh48lolfvu8kqtw' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
