<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://jwsuperthemes.com
 * @since             4.6
 * @package           Jws_Streamvid
 *
 * @wordpress-plugin
 * Plugin Name:       Jws Streamvid
 * Plugin URI:        https://streamvid.jwsuperthemes.com
 * Description:       The plugin contains important functions for streamvid.
 * Version:           4.6
 * Author:            Jws Theme
 * Author URI:        https://jwsuperthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jws_streamvid
 * Domain Path:       /languages
 * Requires at least: 4.9
 * Requires PHP: 5.2.4
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 4.6 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'JWS_STREAMVID_VERSION', '4.6' );
define( 'JWS_STREAMVID_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) . 'public' );
define( 'JWS_STREAMVID_PATH_PUBLIC', trailingslashit( plugin_dir_path( __FILE__ ) ) . 'public' );
define( 'JWS_STREAMVID_URL_PUBLIC', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'public' );
define( 'JWS_STREAMVID_URL_PUBLIC_ASSETS', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'public/assets' );



/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jws-streamvid-activator.php
 */
function activate_jws_streamvid() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jws-streamvid-activator.php';
	Jws_Streamvid_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jws-streamvid-deactivator.php
 */
function deactivate_jws_streamvid() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jws-streamvid-deactivator.php';
	Jws_Streamvid_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_jws_streamvid' );
register_deactivation_hook( __FILE__, 'deactivate_jws_streamvid' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-jws-streamvid.php';


include_once( 'check_update.php' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function jws_streamvid() {
    $GLOBALS['jws_streamvid'] = new Jws_Streamvid();
	return $GLOBALS['jws_streamvid'];
}
jws_streamvid()->run();
