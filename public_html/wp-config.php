<?php
define( 'WP_CACHE', true ); // Added by WP Rocket

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
define( 'DB_NAME', 'bcgameco_wp807' );

/** Database username */
define( 'DB_USER', 'bcgameco_wp807' );

/** Database password */
define( 'DB_PASSWORD', '42S.pOep[1' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         'rsug6dixxoajrgehwyyt2shb0axmfg9hnwtk1vfwg9bbfjkcf8p7rv4i3v3sfv6g' );
define( 'SECURE_AUTH_KEY',  'ykd3bq7bmgwb3zmgb0pqvktazvavk3aaw1pmbc3suykpibtvsummhynqmzgfwqlk' );
define( 'LOGGED_IN_KEY',    'diphvlaigrqoqjek1rwzn2zrpyqrbcmbgxhydkeejaqoqphz6qhdfmcupxx1z8vr' );
define( 'NONCE_KEY',        'qon6deaq2hmv9dw8rna8sg6kgrdn0fogyzqjffjyo0qp08g0glv3oiz6rxkiv69c' );
define( 'AUTH_SALT',        'wheuz1nzz3mf1bbvw05jbhrdlw4nvamqbqwm1nrgsm0bwz41zr206bvhyemv6nqk' );
define( 'SECURE_AUTH_SALT', 'qokk6a4nhgcrypjunrdadczokxp1c3xqjtz6zkso51nwicbmoibzqbkx1t4zavli' );
define( 'LOGGED_IN_SALT',   'kqope6lyklbxcafjpjqa7bno4v2tur5kjxljoc7sgan2edna5awmtvl00wxjhc73' );
define( 'NONCE_SALT',       'lkca5owut1d5qzwknyzur2ghifa7f22mxnhgmt5mncvrcg91f87p9tj76gbtus4z' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpwb_';

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

define( 'WP_HOME', 'http://bcgameco.sg17.fcomet.com' );
define( 'WP_SITEURL', 'http://bcgameco.sg17.fcomet.com' );   


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
