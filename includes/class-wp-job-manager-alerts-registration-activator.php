<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.stephanieannland.com
 * @since      1.0.0
 *
 * @package    Wp_Job_Manager_Alerts_Registration
 * @subpackage Wp_Job_Manager_Alerts_Registration/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Job_Manager_Alerts_Registration
 * @subpackage Wp_Job_Manager_Alerts_Registration/includes
 * @author     Stephanie Land <kwikturnsteph@gmail.com>
 */
class Wp_Job_Manager_Alerts_Registration_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        if ( (! is_plugin_active( 'wp-job-manager/wp-job-manager.php' ) && current_user_can( 'activate_plugins' ) ) || ( !is_plugin_active( 'wp-job-manager-alerts/wp-job-manager-alerts.php' ) && current_user_can( 'activate_plugins' )) ) {
            // Stop activation redirect and show error
            wp_die('Sorry, but this plugin requires the WP Jobs and WP Job Alerts plugin be installed and activated <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
        }
        $capabilities = array( 'read' => true );
        add_role('job_seeker', 'Job Seeker', $capabilities);
        update_option('default_role', 'job_seeker');
	}

}
