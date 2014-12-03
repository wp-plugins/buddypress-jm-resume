<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div role="main" class="profile">		
	<div class="bp-widget base buddypress_jm_resume">
		<div class="hr-title hr-full hr-double"><abbr><?php _e( 'Resume', 'buddypress_jm_resume' ); ?></abbr></div>
		<div class="gap-10"></div>
      <?php global $jm_resume_bp_resume_links;
			$jm_resume_bp_resume_links->display_user_resume_ref();
	  ?>
	</div><!-- end bp-widget -->
</div>