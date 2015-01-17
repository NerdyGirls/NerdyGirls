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
define('DB_NAME', 'webdb');

/** MySQL database username */
define('DB_USER', 'webuser');

/** MySQL database password */
define('DB_PASSWORD', 'ACyucko91');

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
define('AUTH_KEY',         '%1V;73+x -HAJksW+%0=j)B@ /]O0&]XbXB-g2M@6hWYEre#E`v>R|/|b3k5lq 8');
define('SECURE_AUTH_KEY',  'fg+lmn|&9CA~x-<}NF$>`k SJ-Yi_:C[WeQ~bYh#|.e}l {<%d}Epsh|qTuAqq_@');
define('LOGGED_IN_KEY',    'n,}R9.v7Mtx= wQ@c.?1%@>0XEUt~EorM?,<Yg_GNWzASc-3Ye,X!@$cD*g3R9[k');
define('NONCE_KEY',        '@q`B7H&*8?gU7VPg|A]FuK|z?m%$qE@%5Jjzd8?r+?0y,+V!j~U7Eo|wI=5. vU`');
define('AUTH_SALT',        '=|yf6S-#E(ZS<Uh~<$aP67 FBW*Or;TX.=z)H<#+g+fhHibk19WRDRuC]oVY[^a4');
define('SECURE_AUTH_SALT', 'SY/Y-(<_=:@wy6nq&>MC u6b6MM4Q:YPwb_6>;vogm}SJ.863C+cJ~z(%|L5yJ}S');
define('LOGGED_IN_SALT',   'p~Br0*EA1H{^|m@jz+LISZ&yT<S|Q0w(|U$v/+GT2}c2as4j(qHthP&F5e5Gw}l(');
define('NONCE_SALT',       '[sf4k|@UOK@R1+:+DkAH_vt#MQE7Ea-INDvXGEFX:-<3YZS}(]-A%YF5>mI=GQ5J');

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
