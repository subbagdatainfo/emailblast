<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'test');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ',51dWXIS;>2BQKS3cwByZ/F .GsOHX69<;P4+4*3J8]-(Zss=C$lHGl9o2,vAzzk');
define('SECURE_AUTH_KEY',  'IpE{dp71ohES5u5d :^t;,#T.iLa|,]V#!Qob~o%G!zy+4+5t<!GmF(aOY~~YiS}');
define('LOGGED_IN_KEY',    'w<7v**j)R&`@H)si8ZxjYv.KQ:g0^+o%K.W8]R@.8Y8b5;OHb}Tq+=7V*qxs%eD?');
define('NONCE_KEY',        'Z@NM2 xLP2j$@BcN46D8K(<muod3Kq]kkiTYe1E,_YWQCilu/Ahh~S%<mA]<${%~');
define('AUTH_SALT',        'Hbme~_.%dL=^&GT167sCU7Z[4),oQaUPb-E=U7Max(`;jjMc<p:1dM K|@#Etd+Y');
define('SECURE_AUTH_SALT', ';|)4/1U[6Lj47b|]FOy n&1!]p*WYTeBq!&7:pdQ=HHez S?Z2@>4D~$Vj!*B8`H');
define('LOGGED_IN_SALT',   'KQAud0h=FM%G2S!Iu%?rZF@{k)(L6t^}~U[A?/mqEbNGwZ?8FnkB-|jbc9pKcvs_');
define('NONCE_SALT',       '>YU1@dP5-?>C&4V8ZkGkQ+v.SWQggDhOvEIY-I}r<v{b#C`??Zjtzb6+8x/(S$AZ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);
define('FS_METHOD', direct);
/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'localhost');
define('PATH_CURRENT_SITE', '/test/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

