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
 * @package           Views points
 *
 * @wordpress-plugin
 * Plugin Name:       Views points
 * Plugin URI:        https://www.fiverr.com
 * Description:       This plugin allowing to prevent the page view with their local IP, the whole plugin functionality work only for WordPress popular posts plugin
 * Version:           1.0.0
 * Author:            Developer Junayed
 * Author URI:        https://www.fiverr.com/junaidzx90
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       views-points
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
define( 'VIEWS_POINTS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 */
function activate_views_points() {
	
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_views_points() {
	
}

register_activation_hook( __FILE__, 'activate_views_points' );
register_deactivation_hook( __FILE__, 'deactivate_views_points' );

add_filter( "wpp_update_views_count_value", function($views, $post_ID){
	$localIP = $_SERVER['REMOTE_ADDR'];
	$localIpKey = bin2hex($localIP);
    if(get_post_meta( $post_ID, "vp_$localIpKey", true )){
        exit;
    }
}, 10, 2 );

add_action( "wpp_post_update_views", function($post_ID){
	$localIP = $_SERVER['REMOTE_ADDR'];
	$localIpKey = bin2hex($localIP);
	$post = get_post($post_ID);
	$post_author = $post->post_author;
	$amount = ((get_option( 'point_amounts' ))?get_option( 'point_amounts' ):1);

	if(!get_post_meta( $post_ID, "vp_$localIpKey", true )){
		update_post_meta($post_ID, "vp_$localIpKey", $localIP);
		do_shortcode( '[mycred_give amount="'.$amount.'" user_id="'.$post_author.'" log="Points for one view of your posts" ref="view_content_author"]' );
    }
}, 10, 1 );

add_action("admin_menu", function(){
	add_submenu_page( "mycred", "Views Points", "Views Points", "manage_options", "views-points", "views_points_cb", null );
	add_settings_section( 'views_points_setting_section', '', '', 'views_points_setting_page' );
	// Point amounts
	add_settings_field( 'point_amounts', 'Point amounts','point_amounts_cb', 'views_points_setting_page','views_points_setting_section' );
	register_setting( 'views_points_setting_section', 'point_amounts' );
});

function point_amounts_cb(){
	echo '<input type="number" placeholder="1" name="point_amounts" value="'.get_option('point_amounts').'">';
}

function views_points_cb(){
	?>
	<h3>Settings</h3>
	<hr>
	<div class="ph-settings" style="width: 50%">
		<form method="post" action="options.php">
			<?php
			settings_fields( 'views_points_setting_section' );
			do_settings_sections('views_points_setting_page');
			echo get_submit_button( 'Save Changes', 'secondary', 'save-vp-setting' );
			?>
		</form>
	</div>
	<?php
}