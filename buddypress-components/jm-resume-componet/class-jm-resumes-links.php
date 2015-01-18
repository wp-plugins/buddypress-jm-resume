<?php
/**
 * Don't load this file directly!
 */
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WP_Job_Manager_Resumes_Links class.
 */
class WP_Job_Manager_Resumes_Links {
	private static $instance;

	public static function instance() {
		if ( ! isset ( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	public function __construct() {
		// Add your own function to filter the fields
		add_filter( 'submit_resume_form_fields', array( $this, 'custom_submit_resume_form_fields'  ));
		add_action( 'wp_head', array( $this, 'output_inline_css' ) );
	}
	
	
	
	// This is your function which takes the fields, modifies them, and returns them
	function custom_submit_resume_form_fields( $fields ) {
		$user_ID = get_current_user_id();
	    // Here we target one of the job fields (candidate name) and change it's label
	    $fields['resume_fields']['candidate_user_id'] = array(
			'label'       => __( 'User ID', 'wp-job-manager-resumes' ),
			'type'        => 'text',
			'value'		  => $user_ID
		);
	
	    // And return the modified fields
	    return $fields;
	}
	
	public function output_inline_css() {
		$terms   = get_terms( 'restaurant_listing_type', array( 'hide_empty' => false ) );

		echo "<style id='review_restaurant_colors'>\n";
		echo ".fieldset-candidate_id{display: none;}";
		echo ".fieldset-candidate_user_id{display: none;}";
		echo ".buddypress_jm_resume ul li{list-style:none;left:0;}";

		echo "</style>\n";
	}
	
	function display_user_resume_ref() {
		global $post,$bp, $wpdb, $wp_query, $paged;
		$user_id = $bp->displayed_user->id;
		$post_metas = $wpdb->get_col( "SELECT post_id from {$wpdb->postmeta} WHERE meta_key = '_candidate_user_id' and meta_value = {$bp->displayed_user->id} ");
		?>
		<ul>
		<?php
		foreach ($post_metas as $post_meta){
			$post = get_post( $post_meta );
			if ( $post->post_status != 'draft' ) {
			?>
			<li>
			<?php
				get_job_manager_template( 'resume-links.php', array( 'post' => $post ), 'buddypress-jm-resumes-links',  BUDDYPRESS_JM_RESUME . '/templates/' );
			?>
			</li>
			<?php
			}
		}
		?>
		</ul>
		<?php
		
	}
	
	

}

$GLOBALS['jm_resume_bp_resume_links'] = new WP_Job_Manager_Resumes_Links();