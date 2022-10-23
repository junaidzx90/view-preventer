<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fiverr.com/junaidzx90
 * @since             1.0.0
 * @package           View_Preventer
 *
 * @wordpress-plugin
 * Plugin Name:       View Preventer
 * Plugin URI:        https://www.fiverr.com
 * Description:       This plugin allowing to prevent the page view with their local IP, the whole plugin functionality work only for WordPress popular posts plugin
 * Version:           1.0.0
 * Author:            Developer Junayed
 * Author URI:        https://www.fiverr.com/junaidzx90
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       view-preventer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'VIEW_PREVENTER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 */
function activate_view_preventer() {
	
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_view_preventer() {
	
}

register_activation_hook( __FILE__, 'activate_view_preventer' );
register_deactivation_hook( __FILE__, 'deactivate_view_preventer' );

add_filter( "wpp_update_views_count_value", function($views, $post_ID){
	$localIP = getHostByName(getHostName());
	$localIpKey = bin2hex($localIP);
    if(get_post_meta( $post_ID, "vp_$localIpKey", true )){
        exit;
    }
}, 10, 2 );

add_action( "wpp_post_update_views", function($post_ID){
	$localIP = getHostByName(getHostName());
	$localIpKey = bin2hex($localIP);
    update_post_meta($post_ID, "vp_$localIpKey", $localIP);
}, 10, 1 );