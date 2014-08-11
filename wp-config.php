<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'teknoboost');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'merdeka123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

define('WP_CACHE', true);

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
 /* Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'GUW0d^%1P(5rqhd_Q_-^y$][E=Gr`9++RPIDK./GMB+>}l-a!mp-:.)JE~?+73O,');
define('SECURE_AUTH_KEY',  '<cxHl3DgWm{T&cnNQFpN8lQ+0X64@!O=S4ut`8=adVW<|`&Pk9Epr?OfLvpT&%zd');
define('LOGGED_IN_KEY',    '$z|#sd$1lJ6qMz,n}^k^>c3$+-g`.`[|*v]1u!<zgI.Obr+*P7<~2(ijmBt%;zYB');
define('NONCE_KEY',        'l!0jiwsby},K*9z9DNJgMOf1?8_mN-fY`HG8TV4+#:+Mh?wL[P&_ K##]&0gG|14');
define('AUTH_SALT',        'A8orO_Jl9bG5)Ipp7/Cd[GWeX;;juwSjuPkUB+>Y8;;~t-`$@1=+l9;cW16oolrs');
define('SECURE_AUTH_SALT', ':4wCnA@fe)(to]Z~|<|DFaW/dHd8s`diA(.|N5%4%qoEQ8MT76kw!H2)[+`Xn4<-');
define('LOGGED_IN_SALT',   '!h&MkxL:;$(vVc($G0n=#D2jI8A,8K)BSQ}jo?VGszku3|vStekFtirM&ZU7+-F~');
define('NONCE_SALT',       '4j:HwdEp/fNs3nRGB/f?-4pG8Ix8+V5I*q=k1lCqU$b|p]+k;4#h||%~%ZJ8*(88');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'tekno_bost_test';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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

