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
define('DB_NAME', 'corwave');

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
define('AUTH_KEY',         'WTnJi)w6Oz-8uxa)R;NB/,;,`/}1GdYL8XV N(6QWA<RFL#)DFkf9kAP&)Hc)gO3');
define('SECURE_AUTH_KEY',  '{K3oO[U G/fHE~sSgujF?rU7qoT=P9a@SU{dO-.<wC|K8pY%`)]yl|zOS LqhRy0');
define('LOGGED_IN_KEY',    '>iT)-a__#:kR+cuS>|ubwMZ-:86E+*!+DY#;Gsa-B`Zu7;_$I9e<L-RO(?m,|+/>');
define('NONCE_KEY',        'd-8?Db/-g7#`ZKI4@t&+@o-GYCXI$o{Q-UtR(-#^Zi||N5B@~v.+N@gnZMn@@L_}');
define('AUTH_SALT',        'QONd::l5o9ohLv.Pj+vOTRXs {=XDOxp=ed^@LF5b D-<CyVW`#Aub0)+7bUn24Z');
define('SECURE_AUTH_SALT', ':8-;2NmqDV3N<SFOUn|K`iB69zGlAU&_ZnY|Jqvs^QODAN L2-|uN8~8v^5B{M|1');
define('LOGGED_IN_SALT',   'WWq3/4+5/&vb]u>=Q0fbq2klo1r0)>n SeEe^+/,OD[}!x:dH&6Q&PNb`cGT1-yB');
define('NONCE_SALT',       'c>2cKFj13g^Kh!A 32jyOiF4|2/ sqBB?:rE7R&m2XdIi<L*ci$=l*(.&(<okkPe');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'corwave_';

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
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);

define('WP_POST_REVISIONS', 5);
define('EMPTY_TRASH_DAYS', 10);
define('WP_AUTO_UPDATE_CORE', true);
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_UNFILTERED_HTML', true);

/**
 * Enable WordPress Multisite.
 * NOTE: Make sure those lines are just above
 * "That's all, stop editing! Happy blogging."
 */
define('MULTISITE', true);
define( 'WP_ALLOW_MULTISITE', TRUE );
// NOTE: Set this to true for sub-domain installations.
define( 'SUBDOMAIN_INSTALL',    false      );
define( 'DOMAIN_CURRENT_SITE',  'localhost');
define( 'PATH_CURRENT_SITE',    '/'        );
define( 'SITE_ID_CURRENT_SITE', 1          );
define( 'BLOG_ID_CURRENT_SITE', 1          );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** 
 * Sets up WordPress vars and included files. 
 * NOTE: Make sure this is the last line in this file,
 * otherwise the multisite will break.
 */
require_once(ABSPATH . 'wp-settings.php');
