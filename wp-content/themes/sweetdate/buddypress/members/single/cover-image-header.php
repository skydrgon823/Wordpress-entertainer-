<?php

/**
 * BuddyPress - Users Header
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php

/**
 * Fires before the display of a member's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_header' ); ?>


<?php

/**
 * Fires after the group header actions section.
 *
 * If you'd like to show specific profile fields here use:
 * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
 *
 * @since 1.2.0
 */
do_action( 'bp_profile_header_meta' );

?>

<div class="five columns">

    <?php
    /**
     * kleo_bp_before_profile_name hook
     *
     * @hooked kleo_bp_compatibility_match - 20
     */
     do_action( 'kleo_bp_before_profile_name' );
     ?>

    <h2><?php bp_displayed_user_fullname(); ?></h2>
    <span class="activity"><i class="icon-time"></i> <?php bp_last_activity( bp_displayed_user_id() ); ?></span>

	<?php

	/**
	 * Fires before the display of the member's header meta.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_header_meta' ); ?>
		
    <?php 
    /**
     * kleo_bp_after_profile_username
     *
     * @hooked kleo_membership_info - 10
     */
    do_action('kleo_bp_after_profile_name');
    ?>
    <p>&nbsp;</p>

    <div class="row">
        <div id="item-header-avatar" class="six columns image-hover">
            <?php bp_displayed_user_avatar( array('type' =>'full','width' => 320, 'height' => 320) ); ?>
			 <?php
            /**
             * kleo_bp_after_profile_image
             *
             * @hooked kleo_bp_profile_photo_change - 10
             */
             do_action( 'kleo_bp_after_profile_image' );
             ?>
			
        </div>

        <?php do_action('kleo_bp_header_actions');?>

		
    </div><!--end row-->
</div><!--end five-->

<?php

/**
 * Fires after the display of a member's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_header' ); ?>

<div id="template-notices" role="alert" aria-atomic="true">
	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
	do_action( 'template_notices' ); ?>

</div>

<script>
	(function($) {
		$(document).ready(function () {
			$(document).ajaxComplete(function (event, xhr, settings) {
				if(settings.data){
					if(settings.data.indexOf("action=bp_cover_image_delete") != -1){
						$('body').removeClass('is-user-profile');
						$("#profile").css('backgroundImage', 'none');
					}
				}
			});
			if(typeof(bp) !== 'undefined' && typeof(bp.Uploader) !== 'undefined' && typeof(bp.Uploader.filesQueue) !== 'undefined'){
				bp.Uploader.filesQueue.on( 'add', function(){
					$('body').addClass('is-user-profile');
				});
			}
		});
	})(jQuery);
</script>
