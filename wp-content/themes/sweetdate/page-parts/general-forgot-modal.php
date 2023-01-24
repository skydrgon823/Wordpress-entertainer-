<div id="forgot_panel" class="reveal-modal">
  <div class="row">
    <div class="twelve columns">
      <h5><i class="icon-lightbulb icon-large"></i> <?php _e("FORGOT YOUR DETAILS?", 'sweetdate');?></h5>
    </div>
    <form id="forgot_form" name="forgot_form" method="post" class="clearfix">
    <div class="twelve columns">
      <input type="text" id="forgot-email" name="email" class="inputbox" placeholder="<?php _e("Email Address",'sweetdate');?>">
      <button type="submit" id="recover" name="submit" class="radius secondary button"><?php _e("SEND MY DETAILS!", 'sweetdate');?> &nbsp;<i class="icon-envelope"></i></button>
      <div id="lost_result"></div>
    </div>
    </form>
    <div class="twelve columns"><hr>
      <small><a href="#" data-reveal-id="login_panel" class="radius secondary label"><?php _e("AAH, WAIT, I REMEMBER NOW!", 'sweetdate');?></a></small>
    </div>
  <div class="login-form-hook">
      <?php do_action('lostpassword_form'); ?>
  </div>
  </div><!--end row-->
  <a href="#" class="close-reveal-modal">×</a>
</div>