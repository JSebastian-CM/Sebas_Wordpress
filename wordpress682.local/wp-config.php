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
define( 'DB_NAME', 'wordpress682' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'dnstuff' );

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
define( 'AUTH_KEY',         '$bTaNwz[,WGtc]))w%IFjnI|9=6DJXw*h*W-:K9B::^UQ=Y+fw*R7YFHFqxay8]%' );
define( 'SECURE_AUTH_KEY',  ',A9NjX e&!jeYJgbo/Mp9{2nokgCnc{T5U:D|~{&R.2ifgC#K$HQ)[5JWU%cnq2I' );
define( 'LOGGED_IN_KEY',    'OxVaZ0M;1o|NwX8Rff7,*{S>i-Cyc.`jLpN/g8pO(5a-IcI+gbkDHsE-4tgIs)#F' );
define( 'NONCE_KEY',        'vJf|,Vz3C!muKl4O;38hX)n]YvUY&QShR!fJ}<zf5.x;Z`9RB-LlM[(Y/XVyl)<a' );
define( 'AUTH_SALT',        '0?w87m=4I>Km-#P^<Snc9AZuw*mn6D`-3iDN.Ugg&2q.=9_*]@Oq[9%`36ddh:PN' );
define( 'SECURE_AUTH_SALT', '$WZ1?jJ=!FArHg9:lZ#TNM4E A*1.ck PqQn=D)o +A,([cibn-k96zwb+CI|ETn' );
define( 'LOGGED_IN_SALT',   '~Y_(NSwXrNG`*no_{$HDQN)#A4~pp;nOuIIYTW|XO[)sm#70ASHE-{;.zvCO 3/:' );
define( 'NONCE_SALT',       '$}:o@5plqiqw|IV+8Dh.4zRs0nb{gee}GWFI|Z8}Sjv~h=xDLu+EIfr:a1BZC,Vt' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
