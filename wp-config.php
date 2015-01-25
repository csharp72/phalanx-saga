<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'ZQKMf<fj`)<I92[ ~>yy1Aa4@-n-x?MN_@|NZff6uX5Crq6BK0awQ/oc+-!^ 9n_');
define('SECURE_AUTH_KEY',  'N,ORM72%46d-s5HC<-`&neo)rdL#|VBkT>&k{mqA[G_9|bu>L(-|vj~mw-^uKSO~');
define('LOGGED_IN_KEY',    'z9+aFn9Uu)UJ]jnRLld^B8-,m<I4UvE^P--k6m1Tad@taW3rM:Giz=WR9ab.n_OS');
define('NONCE_KEY',        '#41Qz!*Eo4_XNC DAJsk9KWt&;8-F*`>aiO-+iUrH,Vx?RPoQvsH1VP1a__z@:-u');
define('AUTH_SALT',        'bm1%>Ex~|RIPJ$L3Wnf8f]@x8d{5t]~0b)(*2-#@#4kKhH}Z5k#py4WlDFy3~/bX');
define('SECURE_AUTH_SALT', '(R:@{ggs%Y7/+F}u@l-[&G[9GWSDo-K_Q7sQucmL _3K^z*D!cd!!qfswv2>!GhD');
define('LOGGED_IN_SALT',   '}+_E9C%c:2phK>r!m}|JT&]pl+<=0wVU3RV<<]Flo -Uy?-fF=&[<iF=oV%q`]I~');
define('NONCE_SALT',       '(:5j`Ur,%$Gb(tk a51pJL$M%-Y3WJR:z/diP%3=6;OAhXv&ko7}jq,wXyp3$j@D');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
