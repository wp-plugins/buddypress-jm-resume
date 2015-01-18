<?php
/**
 * Don't load this file directly!
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Implementation of JM_Resume_Bp_Resume_Loader
 *
 * @package BuddyPress_JM_Resume_Component
 * @author kishore
 */
if ( !class_exists( 'JM_Resume_Bp_Resume_Loader' ) ) {
	class JM_Resume_Bp_Resume_Loader extends BP_Component {
            
                /**
                 * Start the messages component creation process
                 *
                 * @since BuddyPress (1.5)
                 */
                private $sub_nav_items;
                static $resumes_slug = 'resumes';
                private $menu_order = 85;
                public function __construct() {
                    
                        parent::start(
                                'resume',
                                __( 'Resume', 'buddypress_jm_resume' ),
                               BUDDYPRESS_JM_RESUME_PATH,
                                array(
                                    'adminbar_myaccount_order' => 999999
                                )
                        );
                        $this->includes();
                }
        
	
                /**
                 * Include files
                 *
                 * @global BuddyPress $bp The one true BuddyPress instance
                 */
                public function includes( $includes = array() ) {
                    
                       $includes = array(
							'screens',
							'functions',
						);

                       
                        parent::includes( $includes );
                }
                
                /**
                 * Setup globals
                 *
                 * The BP_MESSAGES_SLUG constant is deprecated, and only used here for
                 * backwards compatibility.
                 *
                 * @since BuddyPress (1.5)
                 */
                public function setup_globals( $args = array() ) {
                        $bp = buddypress();

                        // Define a slug, if necessary
                        if ( !defined( 'BP_RESUME_SLUG' ) )
                                define( 'BP_RESUME_SLUG', $this->id );

                       

                        // All globals for messaging component.
                        // Note that global_tables is included in this array.
                        $globals = array(
                                'slug'                  => BP_RESUME_SLUG,
                                'has_directory'         => false,
                                'notification_callback' => 'messages_format_notifications',
                                'search_string'         => __( 'Search Messages...', 'buddypress_jm_resume' ),
                                
                        );

                        $this->autocomplete_all = defined( 'BP_MESSAGES_AUTOCOMPLETE_ALL' );

                        parent::setup_globals( $globals );
                }
        
		/**
		 * Set up your component's navigation.
		 *
		 * The navigation elements created here are responsible for the main site navigation (eg
		 * Profile > Activity > Mentions), as well as the navigation in the BuddyBar. WP Admin Bar
		 * navigation is broken out into a separate method; see
		 * BP_Example_Component::setup_admin_bar().
		 *
		 * @global obj $bp
		 */
		function setup_nav( $nav = array(), $sub_nav = array() ) {
			if ( ! class_exists( 'WP_Job_Manager' ) )
				return;
			
            $nav_name = __( 'Resume', 'buddypress_jm_resume' );

			// Add 'Resume' to the main navigation
			$main_nav = array(
				'name' 		      => __( 'Resume', 'buddypress_jm_resume' ),
				'slug' 		      => $this->id,
				'position' 	      => $this->menu_order,
				'screen_function'     => 'bp_jm_screen_resumes',
				'default_subnav_slug' => 'resume',
			);

            // Determine user to use
            if ( bp_displayed_user_domain() ) {
                    $user_domain = bp_displayed_user_domain();
            } elseif ( bp_loggedin_user_domain() ) {
                    $user_domain = bp_loggedin_user_domain();
            } else {
                    return;
            }
			
			$job_manager_resume_link = trailingslashit( $user_domain . $this->slug );
			
			// Add My Resume nav item
			if ( get_current_user_id() == bp_displayed_user_id() ) {
				$sub_nav[] = array(
					'name'            => __( 'My Resume', 'buddypress_jm_resume' ),
					'slug'            => 'my-resume',
					'parent_url'      => $job_manager_resume_link,
					'parent_slug'     => $this->slug,
					'screen_function' => 'bp_jm_screen_my_resumes',
					'position'        => 20,
					'user_has_access' => bp_core_can_edit_settings()
				);
			}          
			
			parent::setup_nav( $main_nav, $sub_nav );

		}
		
	/**
	 * Set up the Toolbar
	 */
	public function setup_admin_bar( $wp_admin_nav = array() ) {
		
		if ( ! class_exists( 'WP_Job_Manager' ) )
			return;

		// The instance
		$bp = buddypress();

		// Menus for logged in user
		if ( is_user_logged_in() ) {

			// Setup the logged in user variables
			$user_domain   = bp_loggedin_user_domain();
			$job_manager_resume_link = trailingslashit( $user_domain . $this->slug );

			// Add My Resume menu
			$wp_admin_nav[] = array(
				'parent' => $bp->my_account_menu_id,
				'id'     => 'my-account-' . $this->id,
				'title'  => __( 'Resume', 'buddypress_jm_resume' ),
				'href'   => trailingslashit( $job_manager_resume_link )
			);
			
			// Job Dashboard
			$wp_admin_nav[] = array(
				'parent' => 'my-account-' . $this->id,
				'id'     => 'my-account-' . $this->id . '-job-dashboard',
				'title'  => __( 'My Resume', 'buddypress_jm_resume' ),
				'href'   => trailingslashit( $job_manager_resume_link . 'my-resume' )
			);
		}

		parent::setup_admin_bar( $wp_admin_nav );
	}
		

	
	}
}
$GLOBALS['jm_resume_bp_resume_loader'] = new JM_Resume_Bp_Resume_Loader();