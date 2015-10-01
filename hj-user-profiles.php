<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.midwestdigitalmarketing.com
 * @since             1.0.0
 * @package           hj_User_Profiles
 *
 * @wordpress-plugin
 * Plugin Name: hj User Profiles
 * Plugin URI:  https://github.com/MDMDevOps/HJ-User-Profiles
 * Description: Simple user profile addition, including photos and user meta
 * Version:     1.0.1
 * Author:      Midwest Digital Marketing
 * Author URI:  http://www.midwestdigitalmarketing.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: hj-user-profiles
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hj-user-profiles-activator.php
 */
function activate_hj_user_profiles() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hj-user-profiles-activator.php';
	hj_User_Profiles_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_hj_user_profiles' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hj-user-profiles-deactivator.php
 */
function deactivate_hj_user_profiles() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hj-user-profiles-deactivator.php';
	hj_User_Profiles_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_hj_user_profiles' );

/**
 * The code that runs to update the plugin when an update is pushed to the repo
 * This action is documented in includes/class-mpress-analytics-updater.php
 */
function update_hj_user_profiles() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-hj-user-profiles-updater.php';
    if ( is_admin() ) {
        define( 'WP_GITHUB_FORCE_UPDATE', true );
        $config = array(
            'slug'                  => plugin_basename( __FILE__ ),
            'proper_folder_name'    => 'HJ-User-Profiles',
            'api_url'               => 'https://api.github.com/repos/MDMDevOps/HJ-User-Profiles',
            'raw_url'               => 'https://raw.github.com/MDMDevOps/HJ-User-Profiles/master',
            'github_url'            => 'https://github.com/MDMDevOps/HJ-User-Profiles',
            'zip_url'               => 'https://github.com/MDMDevOps/HJ-User-Profiles/zipball/master',
            'sslverify'             => false,
            'requires'              => '4.0',
            'tested'                => '4.3',
            'readme'                => 'README.md',
            'access_token'          => '6db9b5397831d3a6c26820594f7e79e162654a13'
        );
        $plugin_update = new WP_GitHub_Updater( $config );
    }
}
add_action( 'init', 'update_hj_user_profiles' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hj-user-profiles.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 */
function run_hj_user_profiles() {

	$plugin = new hj_User_Profiles();
	$plugin->run();

}
run_hj_user_profiles();
