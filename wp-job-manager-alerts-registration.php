<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.stephanieannland.com
 * @since             1.0.0
 * @package           Wp_Job_Manager_Alerts_Registration
 *
 * @wordpress-plugin
 * Plugin Name:       WP Job Manager - Alerts - Registration
 * Plugin URI:        http://www.stephanieannland.com
 * Description:       This plugin changes the default registration form; creates a user role, Job Seeker; and redirects that user to the job alerts page upon login.
 * Version:           1.0.0
 * Author:            Stephanie Land
 * Author URI:        http://www.stephanieannland.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-job-manager-alerts-registration
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

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-job-manager-alerts-registration-activator.php
 */
function activate_wp_job_manager_alerts_registration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-job-manager-alerts-registration-activator.php';
	Wp_Job_Manager_Alerts_Registration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-job-manager-alerts-registration-deactivator.php
 */
function deactivate_wp_job_manager_alerts_registration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-job-manager-alerts-registration-deactivator.php';
	Wp_Job_Manager_Alerts_Registration_Deactivator::deactivate();
}

define( 'WP_JOB_ALERTS_REGISTRATION_VERSION', '1.0.0' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( ! is_plugin_active( 'wp-job-manager/wp-job-manager.php' ) || ! !is_plugin_active( 'wp-job-manager/wp-job-manager-alerts.php' )) {
    deactivate_plugins( plugin_basename( __FILE__ ) );
    wp_die('Sorry, but the WP Job Alerts Registration plugin requires the WP Jobs plugin and the WP Job Alerts plugin be installed and activated <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
}
register_activation_hook( __FILE__, 'activate_wp_job_manager_alerts_registration' );
register_deactivation_hook( __FILE__, 'deactivate_wp_job_manager_alerts_registration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-job-manager-alerts-registration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_job_manager_alerts_registration() {

	$plugin = new Wp_Job_Manager_Alerts_Registration();
	$plugin->run();

}
run_wp_job_manager_alerts_registration();
