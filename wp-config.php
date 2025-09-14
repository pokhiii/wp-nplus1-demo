<?php
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
define( 'DB_NAME', 'wpnplus1' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',          '=_;dBM.D%7O4&DY=?h}=5C9,| <v%YMLtcOr4q-3R(@s_CO],o:A,NE=KEJ&$fPO' );
define( 'SECURE_AUTH_KEY',   'ZO^cXV-)gLbP:`Q5K%Gh6(q)LR/N(^34DNVkc?zdI!(]!?=m=3oV;t0frI9/=<*!' );
define( 'LOGGED_IN_KEY',     'oUdWDZb-;#Z]d3sWJ]]Er+tawis[IK5gB~yH*4m>(=~*+$y4+3ToV5djc>+#XV[G' );
define( 'NONCE_KEY',         '+jMXG]<ae<<<G1D*A2V-!oWv]j>9z5XjB6eiUnX>Do|h,;4Sw}0Lq0IV9s35]Ff<' );
define( 'AUTH_SALT',         'pY#wsfc|vcXV^dK7TF3zFpX.PEtB(7KhD{y<SuPoEW}@OW:o@EP+-*@SsUH$<w#Y' );
define( 'SECURE_AUTH_SALT',  '~KQ6!1.:d>zY,4-NRC S*4H5E#@-I)I.&p6RP.fNw$~;3^)Xj[r{doo{c28.-)lw' );
define( 'LOGGED_IN_SALT',    'k`ETQ4^=$X`o#yQq{AFj>rvQ|7M#g[=Z!S}W%#2/yiS_XN )f0)o7*((jbk(ALIR' );
define( 'NONCE_SALT',        '(u@~{8N0iFi;/gq%Q0al72r<PdPFFFcio`:Z|}D|@2c9y:>[?X%Rz8_xUC#VIRgf' );
define( 'WP_CACHE_KEY_SALT', '@)~~HJMb)c@5JLt_Lh4>)6.1{p(-dvlM<8w57>0}M]dc,1$6Ne;`uGtG2-gz{=f(' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
