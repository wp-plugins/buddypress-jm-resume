<?php

/********************************************************************************
 * Screen Functions
 *
 * Screen functions are the controllers of BuddyPress. They will execute when their
 * specific URL is caught. They will first save or manipulate data using business
 * functions, then pass on the user to a template file.
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * If your component uses a top-level directory, this function will catch the requests and load
 * the index page.
 *
 * @package BuddyPress_Template_Pack
 * @since 1.6
 */
function bp_jm_screen() {
	if ( bp_is_current_component( 'resume' ) ){
    	bp_core_load_template( 'members/single/home'  );
	}	
}
add_action( 'bp_screens', 'bp_jm_screen' );

function bp_jm_screen_my_resumes() { 
	add_action('bp_template_content','load_bp_jm_screen_template');
}

function load_bp_jm_screen_template() {
    include  BUDDYPRESS_JM_RESUME.'/templates/jm-resume.php';
}


/**
 * Show the resumes Job Manager template
 *
 * @since Job Manager (1.0.0)
 */
function bp_jm_screen_resumes() {

	if ( bp_action_variables() ) {
		bp_do_404();
		return;
	}

	// Load the template
	bp_core_load_template( apply_filters( 'bp_jm_screen_resumes', 'members/single/home' ) );
	add_action('bp_template_content','load_jobs_resumes_template');
}

function load_bp_jm_screen_resumes_template() {
    // include  file or echo do_shortcode
    echo do_shortcode( '[resumes]' );
}

?>