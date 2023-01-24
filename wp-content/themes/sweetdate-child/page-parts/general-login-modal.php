<div id="login_panel" class="reveal-modal">
  <div class="row">
    <div class="twelve columns">
      <h5><i class="icon-user icon-large"></i> <?php _e("SIGN INTO YOUR ACCOUNT", 'sweetdate');?><?php if(get_option('users_can_register')) { ?> </h5>
    </div>
      <form action="<?php echo wp_login_url(apply_filters('kleo_modal_login_redirect', '')  ); ?>" id="login_form" name="login_form" method="post" class="clearfix">
      <div class="six columns">
        <input type="text" id="username" required name="log" class="inputbox" value="" placeholder="<?php _e("Username", 'sweetdate');?>">
      </div>
      <div class="six columns">
        <input type="password" id="password" value="" required name="pwd" class="inputbox" placeholder="<?php _e("Password", 'sweetdate');?>">
      </div>
      <p class="twelve columns">
          <label>
              <input type="checkbox" value="forever" name="rememberme" id="rememberme"> <?php _e( "Remember me", 'sweetdate' ); ?>
          </label>
        <small>
            <?php
            if( sq_option('privacy_page', '#') != "#" && sq_option('privacy_page', '#') != '' ) {
                $privacy_page_id = sq_option('privacy_page');
                /* WPML compatibility */
                if ( function_exists( 'icl_object_id' ) ) {
                    $privacy_page_id = icl_object_id( $privacy_page_id, 'page', true );
                }
                $privacy_link = get_permalink( $privacy_page_id );
                printf( __( 'Your <a href="%s" target="_blank">privacy</a> is important to us and we will never rent or sell your information.', 'sweetdate' ), $privacy_link );
            }
            ?>
        </small>
          <div class="login-form-hook">
            <?php do_action('login_form');?>
          </div>
      </p>
      <div class="twelve columns">
        <button type="submit" id="login" name="wp-submit" class="radius secondary button"><i class="icon-unlock"></i> &nbsp;<?php _e("LOG IN", 'sweetdate');?></button> &nbsp;
        <span class="subheader right small-link"><a href="/register/" class="radius secondary small button"><?php _e("CREATE NEW ACCOUNT", 'sweetdate');?></a></span><?php } ?>
		<?php do_action('fb_popup_button'); ?> 
      </div>
    </form>
    <div class="twelve columns"><hr>
      <ul class="inline-list">
        <li><small><a href="#" data-reveal-id="forgot_panel"><?php _e("FORGOT YOUR USERNAME OR PASSWORD?", 'sweetdate');?></a></small></li>
      </ul>
    </div>
  </div><!--end row-->
  <a href="#" class="close-reveal-modal">×</a>
</div>
