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
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'mintoku_mobile' );

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
define( 'AUTH_KEY',         '>#*YDRO?wvxBA~aL~%XWYHUO{41x[B<z;D;?k{2qs^B7upuQmPE42;cyjy6)T)iw' );
define( 'SECURE_AUTH_KEY',  'w9/BIyHR6}+e0a2hZI9I3Yh=^Dt90KQX[{/Gy&30{DuM?^I}J-)&3#{i4K-;BW~S' );
define( 'LOGGED_IN_KEY',    'RYZ5(HG(f/U]!I1x8s]wBqor(gV-!kXYe~sc_F&+a5*Kr& yb2}|5)@=TLn-aNM(' );
define( 'NONCE_KEY',        'EP<xCa3^7eqU)(kxg)53HjRR)h>1VoQ0%I*FD^mJKr5}5g{w-*#=$nK#A1av6G:g' );
define( 'AUTH_SALT',        '7@k;xogP4Zgj[:{Uw&$*t[R@NZo8vrXaE%=pTy#dXry_@Iizz~ZgZBFYsG:&YF#A' );
define( 'SECURE_AUTH_SALT', '&4-*Pb`,Z*zfpM*fcAJM[v9L0tXUc,ZtQU1;CcqY#d}ol@4$%fsTKl(`N91!Aw0-' );
define( 'LOGGED_IN_SALT',   '/r~T*7=O[C`IAd.>),VFS-hU}^;w=t+d%(W@PNsuJN-+sbjrjx932Bts@!/F UgW' );
define( 'NONCE_SALT',       '~Wm3GC_k|Ujo698v/>3{^8EgqBsb[KC(]uhs*vTjrNPw*LG$0%yo[k5<!G^-W8Yo' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
