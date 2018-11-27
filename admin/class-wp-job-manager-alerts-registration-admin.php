<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.stephanieannland.com
 * @since      1.0.0
 *
 * @package    Wp_Job_Manager_Alerts_Registration
 * @subpackage Wp_Job_Manager_Alerts_Registration/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Job_Manager_Alerts_Registration
 * @subpackage Wp_Job_Manager_Alerts_Registration/admin
 * @author     Stephanie Land <kwikturnsteph@gmail.com>
 */
class Wp_Job_Manager_Alerts_Registration_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        // Add extra fields to registration page
        add_action( 'register_form', array($this, 'wp_jobs_manager_alerts_registration_register_form') );
        //2. Add validation. In this case, we make sure first_name is required.
        add_filter( 'registration_errors', array($this, 'wp_jobs_manager_alerts_registration_registration_errors'), 10, 3 );
        //3. Finally, save our extra registration user meta.
        add_action( 'user_register', array($this, 'wpjmar_user_register') );
        // redirect after register
        //add_filter( 'registration_redirect', array($this, 'wpjmar_redirect_home') );
        add_filter( 'login_redirect', array($this, 'wpjmar_login_redirect'), 10, 3 );
// add job alerts dashboard widget
        add_action( 'wp_dashboard_setup', array($this, 'jobs_add_dashboard_widgets') );
        // change dashboard title for job seekers
        add_filter('admin_title', array($this, 'job_seeker_admin_title'), 10, 2);
        add_action( 'admin_menu' , array($this, 'change_dashboard_menu') );
        //remove dashboard boxes from jobseeker accounts
        add_action('wp_dashboard_setup', array($this, 'remove_dashboard_widgets'));
        // hide update notices from jobseeker
        add_action( 'admin_head', array($this, 'hide_update_notice_from_job_seeker'), 1 );
// hide color scheme choice from jobseeker
        add_action('admin_head', array($this, 'admin_color_scheme'));
// hide show tool bar when viewing site option since they won't have a way to get to the dashboard without it.
        add_action( 'admin_print_styles-user-edit.php', array($this, 'hide_admin_bar_settings_from_job_seekers') );
        add_action( 'admin_print_styles-profile.php', array($this, 'hide_admin_bar_settings_from_job_seekers') );

    }

	/**
	 * Register the stylesheets for the admin area.
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

	//	wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-job-manager-alerts-registration-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

	//	wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-job-manager-alerts-registration-admin.js', array( 'jquery' ), $this->version, false );

	}



    function wp_jobs_manager_alerts_registration_register_form() {

        $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
        $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';

        ?>
        <p>
            <label for="first_name"><?php _e( 'First Name', $this->plugin_name ) ?><br />
                <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
        </p>

        <p>
            <label for="last_name"><?php _e( 'Last Name', $this->plugin_name ) ?><br />
                <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
        </p>

        <?php
    }


    function wp_jobs_manager_alerts_registration_registration_errors( $errors, $sanitized_user_login, $user_email ) {

        if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
            $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: You must include a first name.', $this->plugin_name ) );
        }
        if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
            $errors->add( 'last_name_error', __( '<strong>ERROR</strong>: You must include a last name.', $this->plugin_name ) );
        }
        return $errors;
    }


    function wpjmar_user_register( $user_id ) {
        if ( ! empty( $_POST['first_name'] ) ) {
            update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
            update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
        }
    }


    function wpjmar_redirect_home( $registration_redirect ) {
        $alertsURL = get_permalink( get_option( 'job_manager_alerts_page_id' ) );
        return  $alertsURL;
    }
    /**
     * Redirect user after successful login.
     *
     * @param string $redirect_to URL to redirect to.
     * @param string $request URL the user is coming from.
     * @param object $user Logged user's data.
     * @return string
     */

    function wpjmar_login_redirect( $redirect_to, $request, $user ) {
        //is there a user to check?
        if (isset($user->roles) && is_array($user->roles)) {
            //check for subscribers
            if (in_array('job_seeker', $user->roles)) {
                // redirect them to another URL, in this case, the homepage
                $alertsURL = get_permalink( get_option( 'job_manager_alerts_page_id' ) );
                $redirect_to =  $alertsURL;
            }
        }

        return $redirect_to;
    }


    /**
     * Add a widget to the dashboard.
     *
     * This function is hooked into the 'wp_dashboard_setup' action below.
     */
    function jobs_add_dashboard_widgets() {

        wp_add_dashboard_widget(
            'job_alerts_dashboard_widget',         // Widget slug.
            'My Job Alerts',         // Title.
            array($this, 'job_alerts_dashboard_widget_function') // Display function.
        );
    }


    /**
     * Create the function to output the contents of our Dashboard Widget.
     */
    function job_alerts_dashboard_widget_function() {
        // Display whatever it is you want to show.
        $args = array(
            'post_type'           => 'job_alert',
            'post_status'         => array( 'publish', 'draft' ),
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => -1,
            'orderby'             => 'title',
            'order'               => 'asc',
            'author'              => get_current_user_id()
        );

        $alerts = get_posts( $args );
        $user   = wp_get_current_user(); ?>

        <div id="job-manager-alerts">
	<p><?php printf( __( 'Your job alerts are shown in the table below and will be emailed to %s.', 'wp-job-manager-alerts' ), $user->user_email ); ?></p>
<table class="job-manager-alerts" cellpadding="0" cellspacing="0">
    <style>table.job-manager-alerts{border:1px solid #555;}table.job-manager-alerts td, table.job-manager-alerts th{padding:5px} table.job-manager-alerts tbody td {border-bottom:1px solid #555;}</style>
    <thead>
    <tr>
        <th><?php _e( 'Alert Name', 'wp-job-manager-alerts' ); ?></th>
        <th><?php _e( 'Keywords', 'wp-job-manager-alerts' ); ?></th>
        <?php if ( get_option( 'job_manager_enable_categories' ) && wp_count_terms( 'job_listing_category' ) > 0 ) : ?>
            <th><?php _e( 'Categories', 'wp-job-manager-alerts' ); ?></th>
        <?php endif; ?>
        <?php if ( taxonomy_exists( 'job_listing_tag' ) ) : ?>
            <th><?php _e( 'Tags', 'wp-job-manager-alerts' ); ?></th>
        <?php endif; ?>
        <th><?php _e( 'Location', 'wp-job-manager-alerts' ); ?></th>
        <th><?php _e( 'Frequency', 'wp-job-manager-alerts' ); ?></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="6">
        <?php $alertsURL = get_permalink( get_option( 'job_manager_alerts_page_id' ) ); ?>
            <a class="button" href="<?php echo $alertsURL; ?>" style="margin:10px;">Manage Alerts</a>
        </td>
    </tr>
    </tfoot>
    <tbody>
    <?php foreach ( $alerts as $alert ) : ?>
        <?php
        $search_terms = WP_Job_Manager_Alerts_Post_Types::get_alert_search_terms( $alert->ID );
        ?>
        <tr class="alert-<?php echo $alert->post_status === 'draft' ? 'disabled' : 'enabled'; ?>">
            <td>
                <?php echo esc_html( $alert->post_title ); ?>

            </td>
            <td class="alert_keyword"><?php
                if ( $value = get_post_meta( $alert->ID, 'alert_keyword', true ) )
                    echo esc_html( $value );
                else
                    echo '&ndash;';
                ?></td>
            <?php if ( get_option( 'job_manager_enable_categories' ) && wp_count_terms( 'job_listing_category' ) > 0 ) : ?>
                <td class="alert_category"><?php
                    $term_ids = ! empty( $search_terms['categories'] ) ? $search_terms['categories'] : array();
                    $terms = array();
                    if ( ! empty( $term_ids ) ) {
                        $terms = get_terms( array(
                            'taxonomy'         => 'job_listing_category',
                            'fields'           => 'names',
                            'include'          => $term_ids,
                            'hide_empty'       => false,
                        ) );
                    }
                    echo $terms ? esc_html( implode( ', ', $terms ) ) : '&ndash;';
                    ?></td>
            <?php endif; ?>
            <?php if ( taxonomy_exists( 'job_listing_tag' ) ) : ?>
                <td class="alert_tag"><?php
                    $term_ids = ! empty( $search_terms['tags'] ) ? $search_terms['tags'] : array();
                    $terms = array();
                    if ( ! empty( $term_ids ) ) {
                        $terms = get_terms( array(
                            'taxonomy'         => 'job_listing_tag',
                            'fields'           => 'names',
                            'include'          => $term_ids,
                            'hide_empty'       => false,
                        ) );
                    }
                    echo $terms ? esc_html( implode( ', ', $terms ) ) : '&ndash;';
                    ?></td>
            <?php endif; ?>
            <td class="alert_location"><?php
                if ( taxonomy_exists( 'job_listing_region' ) && wp_count_terms( 'job_listing_region' ) > 0 ) {
                    $term_ids = ! empty( $search_terms['regions'] ) ? $search_terms['regions'] : array();
                    $terms = array();
                    if ( ! empty( $term_ids ) ) {
                        $terms = get_terms( array(
                            'taxonomy'         => 'job_listing_region',
                            'fields'           => 'names',
                            'include'          => $term_ids,
                            'hide_empty'       => false,
                        ) );
                    }
                    echo $terms ? esc_html( implode( ', ', $terms ) ) : '&ndash;';
                } else {
                    $value = get_post_meta( $alert->ID, 'alert_location', true );
                    echo $value ? esc_html( $value ) : '&ndash;';
                }
                ?></td>
            <td class="alert_frequency"><?php
                $schedules = WP_Job_Manager_Alerts_Notifier::get_alert_schedules();
                $freq      = get_post_meta( $alert->ID, 'alert_frequency', true );

                if ( ! empty( $schedules[ $freq ] ) ) {
                    echo esc_html( $schedules[ $freq ]['display'] );
                }


                ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div> <?php

    }

    function job_seeker_admin_title($admin_title)
    {

        $user = wp_get_current_user();
        if ( in_array( 'job_seeker', (array) $user->roles ) ) {

            global $current_screen;
            global $title;

            if ($current_screen->id != 'dashboard') {

                return $admin_title;

            }

            $change_title = 'My Jobs Dashboard';

            $admin_title = str_replace(__('Dashboard'), $change_title, $admin_title);
            $title = $change_title;

            return $admin_title;
        }
    }



    function change_dashboard_menu() {
        $user = wp_get_current_user();
        if ( in_array( 'job_seeker', (array) $user->roles ) ) {

            global $menu;

            foreach ($menu as $key => $menu_setting) {

                $menu_slug = $menu_setting[2];

                if (empty($menu_slug)) {

                    continue;

                }

                if ($menu_slug != 'index.php') {

                    continue;

                }

                $menu[$key][0] = 'Job Dashboard';

                break;

            }
        }
    }
    function remove_dashboard_widgets () {
        $user = wp_get_current_user();
        if ( in_array( 'job_seeker', (array) $user->roles ) ) {
            remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); //Quick Press widget
            remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); //Recent Drafts
            remove_meta_box('dashboard_primary', 'dashboard', 'side'); //WordPress.com Blog
            remove_meta_box('dashboard_secondary', 'dashboard', 'side'); //Other WordPress News
            remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); //Incoming Links
            remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); //Plugins
            remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //Right Now
            remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal'); //Gravity Forms
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); //Recent Comments
            remove_meta_box('icl_dashboard_widget', 'dashboard', 'normal'); //Multi Language Plugin
            remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Activity
            remove_action('welcome_panel', 'wp_welcome_panel');
        }
    }


    function hide_update_notice_from_job_seeker()
    {
        $user = wp_get_current_user();
        if ( in_array( 'job_seeker', (array) $user->roles ) ) {
            echo '<style>#setting-error-tgmpa>.updated settings-error notice is-dismissible, div.update-nag, div.updated, span#footer-thankyou { display: none; }</style>';
        }
    }

    function admin_color_scheme() {
        $user = wp_get_current_user();
        if ( in_array( 'job_seeker', (array) $user->roles ) ) {
            global $_wp_admin_css_colors;
            $_wp_admin_css_colors = 0;
            // also remove the profile photo option
            update_option( 'show_avatars', 0 );
        }
    }

    function hide_admin_bar_settings_from_job_seekers()
    {
        $user = wp_get_current_user();
        if ( in_array( 'job_seeker', (array) $user->roles ) ) {
            ?>
            <style>.show-admin-bar {
                    display: none;
                }</style><?php
        }
    }
}
