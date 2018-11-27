<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.stephanieannland.com
 * @since      1.0.0
 *
 * @package    Wp_Job_Manager_Alerts_Registration
 * @subpackage Wp_Job_Manager_Alerts_Registration/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Job_Manager_Alerts_Registration
 * @subpackage Wp_Job_Manager_Alerts_Registration/includes
 * @author     Stephanie Land <kwikturnsteph@gmail.com>
 */
class Wp_Job_Manager_Alerts_Registration_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        remove_role( 'job_seeker' );
        update_option('default_role', 'subscriber');
	}

}
