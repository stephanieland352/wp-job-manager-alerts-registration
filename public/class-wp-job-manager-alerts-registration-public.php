<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.stephanieannland.com
 * @since      1.0.0
 *
 * @package    Wp_Job_Manager_Alerts_Registration
 * @subpackage Wp_Job_Manager_Alerts_Registration/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Job_Manager_Alerts_Registration
 * @subpackage Wp_Job_Manager_Alerts_Registration/public
 * @author     Stephanie Land <kwikturnsteph@gmail.com>
 */
class Wp_Job_Manager_Alerts_Registration_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        add_shortcode('loggedin', array($this, 'check_user_loggedin') );
        add_shortcode('loggedout', array($this, 'check_user_loggedout') );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Job_Manager_Alerts_Registration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Job_Manager_Alerts_Registration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-job-manager-alerts-registration-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Job_Manager_Alerts_Registration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Job_Manager_Alerts_Registration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-job-manager-alerts-registration-public.js', array( 'jquery' ), $this->version, false );

	}
    function check_user_loggedin ($params, $content = null){
        if ( is_user_logged_in() ){
            return $content;
        }
        else{
            return;
        }
    }
    function check_user_loggedout ($params, $content = null){
        if (! is_user_logged_in() ){
            return $content;
        }
        else{
            return;
        }
    }


}
