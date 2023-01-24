<!-- PROFILE SECTION
================================================ -->
<section>
  <div id="profile">

	  <a id="header-cover-image"></a>

    <?php do_action( 'bp_before_member_home_content' ); ?>

    <div class="row">

      <?php
      /**
       * If the cover image feature is enabled, use a specific header
       */
      if ( version_compare( BP_VERSION, '2.4', '>=' ) && bp_displayed_user_use_cover_image_header() ) :
        bp_get_template_part( 'members/single/cover-image-header' );
      else :
        bp_get_template_part( 'members/single/member-header' );
      endif;
      ?>

    </div><!--end row-->

  </div><!--end profile-->

</section><!--END PROFILE SECTION-->