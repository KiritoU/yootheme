<?php
define( 'WP_CACHE', true );


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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'animesugexyz_YjEzYmVjOTQ0OWVkMW_dbname' );

/** Database username */
define( 'DB_USER', 'animesugexyz_YjEzYmVjOTQ0OWVkMW_username' );

/** Database password */
define( 'DB_PASSWORD', 'YjEzYmVjOTQ0OWVkMWVjZDNhNDQ2MDRkNmQ4' );

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
define( 'AUTH_KEY',          'ZF0:*#FJK^twFH)f(dYnm{hFDlHEI)NE}!#1yW%K HvevS#5gz2VA!J..@]B]G>~' );
define( 'SECURE_AUTH_KEY',   '8S92.GeGe=@*K%~aZMsB-6liu?<9qqDfN1Z*8FSKSw/.Zs=CzS|,-IiA%-QIQ7M8' );
define( 'LOGGED_IN_KEY',     '1s9<x@,{]:LZ[_k*qxR`B<kCoE(R2}NbTCqypts@=luZv+u~G,rik/~In[>2}Y7:' );
define( 'NONCE_KEY',         '7)Fa`Wy&,o5^0-mctNb9mE:Y#q5RGhv0%;=CQ)V`4aFyQb!tS5H>#9>QKi83Jj3|' );
define( 'AUTH_SALT',         'F}nxL[;waK46i3&t25Xo 8v#=lv4$fNbwCddK_&OtnP6Xj>oZVw-e4)K` jfD85N' );
define( 'SECURE_AUTH_SALT',  'p]+:IJNx%&g9S.#oy>yr#|%whfc^2J,/1Zz[AKaDLOq|k%&FDrr^C,yy5|f&iU0z' );
define( 'LOGGED_IN_SALT',    'D/0nlHU5ALj|CF7,TnH[n$D$>w5e!L>Ti}WCm*sY.bZ2sFt)VdLrgSkt>{I*v0#H' );
define( 'NONCE_SALT',        'Z@4fMW]`DQAMq*GduE/d$Yf].T=PhvoRB.OjXw}?R}@GV<|^9U|-+zq~Yfe>GlGT' );


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

define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
