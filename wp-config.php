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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'hotelsie_wp841' );

/** MySQL database username */
define( 'DB_USER', 'hotelsie_wp841' );

/** MySQL database password */
define( 'DB_PASSWORD', '0458[[9SpB' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'tjymqdo7hzwtdmcfgt7r4bx9vk7z8v42nmvdhrswbqn1umk5dagjybhzi3ddfbyw' );
define( 'SECURE_AUTH_KEY',  '2x6prla8qzjvmxs92oppjl4n7c6opgci125vtlqi00cjcss75imuw6tj0wavjunt' );
define( 'LOGGED_IN_KEY',    'diyiikc1k0bq4qy6xr3aeyem7epyhrwtfccennwnz3cazyix08v5fxlus4fc3xc5' );
define( 'NONCE_KEY',        'dwzdlv3ys2um5ydlyo0youzshqulnck2gcojpqmcr1wf0qaz9no8dwjwmy2r5flm' );
define( 'AUTH_SALT',        'xhutnq5pxa7wsyigi2p3t5b8odeavwr1apcgmogxqzztjyklyqknljv4xusll3h0' );
define( 'SECURE_AUTH_SALT', 'xcmlqdnnaamwjy4fuydqz204nvxwjdshkvoxorqtsc0aew0tppf5w6r7rkqzhwni' );
define( 'LOGGED_IN_SALT',   'jmg9hjvojfytkt4ivlv1v465fas5blf5fffvhq9bocxlesiqnjgkiuh0xtawkqsg' );
define( 'NONCE_SALT',       'clfggfzuwxpjzp5fbkqgeheliaatflfrtzkrsvgtlwjipj6sg9cmrq1paeexam8i' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
