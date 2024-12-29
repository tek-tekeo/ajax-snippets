<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * This has been slightly modified (to read environment variables) for use in Docker.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// IMPORTANT: this file needs to stay in-sync with https://github.com/WordPress/WordPress/blob/master/wp-config-sample.php
// (it gets parsed by the upstream wizard in https://github.com/WordPress/WordPress/blob/f27cb65e1ef25d11b535695a660e7282b98eb742/wp-admin/setup-config.php#L356-L392)

// a helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
  // https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
  function getenv_docker($env, $default)
  {
    if ($fileEnv = getenv($env . '_FILE')) {
      return rtrim(file_get_contents($fileEnv), "\r\n");
    } else if (($val = getenv($env)) !== false) {
      return $val;
    } else {
      return $default;
    }
  }
}

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'wordpress'));

/** Database username */
define('DB_USER', getenv_docker('WORDPRESS_DB_USER', 'example username'));

/** Database password */
define('DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', 'example password'));

/**
 * Docker image fallback values above are sourced from the official WordPress installation wizard:
 * https://github.com/WordPress/WordPress/blob/1356f6537220ffdc32b9dad2a6cdbe2d010b7a88/wp-admin/setup-config.php#L224-L238
 * (However, using "example username" and "example password" in your database is strongly discouraged.  Please use strong, random credentials!)
 */

/** Database hostname */
define('DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'mysql'));

/** Database charset to use in creating database tables. */
define('DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8'));

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', ''));

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
define('AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         '6fb16bce9a2dec70c600ad02ac1ee0020054e59c'));
define('SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  'b8bf5ec6796c622e6baf79815e073abdbda39fd4'));
define('LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    'ddbcd1c4c70d3a15ddcf5fd800548cdea240e68f'));
define('NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        'c6b3ea874e18dc3be37608f819fa3da43f68ff8b'));
define('AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        '67e25c52d861e9d9ff88531cf369dbee246e3e61'));
define('SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', 'c19f966508745de86a810032ccd725fc92ae6e55'));
define('LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   'a979a075d7d093b23326c92cae6b8e7fe00f1594'));
define('NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       '639cf61bd5c3a41d4bdf7ff6a7ce5cb8b2ad419c'));
// (See also https://wordpress.stackexchange.com/a/152905/199287)

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', ''));
define('WP_DEBUG_LOG', true);            // エラーログを wp-content/debug.log に記録する
define('WP_DEBUG_DISPLAY', false);       // エラーを画面に表示しない
@ini_set('display_errors', 0);           // PHPのエラー表示を無効化
/* Add any custom values between this line and the "stop editing" line. */

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
  $_SERVER['HTTPS'] = 'on';
}
// (we include this by default because reverse proxying is extremely common in container environments)

if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
  eval($configExtra);
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
  define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
