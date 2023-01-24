<?php 
global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;

$pmpro_levels = pmpro_getAllLevels(false, true);
$pmpro_level_order = pmpro_getOption('level_order');

if(!empty($pmpro_level_order))
{
	$order = explode(',',$pmpro_level_order);

	//reorder array
	$reordered_levels = array();
	foreach($order as $level_id) {
		foreach($pmpro_levels as $key=>$level) {
			if($level_id == $level->id)
				$reordered_levels[] = $pmpro_levels[$key];
		}
	}

	$pmpro_levels = $reordered_levels;
}

$pmpro_levels = apply_filters("pmpro_levels_array", $pmpro_levels);

if($pmpro_msg)
{
?>
<div class="<?php echo pmpro_get_element_class( 'pmpro_message ' . $pmpro_msgt, $pmpro_msgt ); ?>"><?php echo $pmpro_msg?></div>
<?php
}
?>
<table id="pmpro_levels_table" class="<?php echo pmpro_get_element_class( 'pmpro_table pmpro_checkout', 'pmpro_levels_table' ); ?>">
<thead>
  <tr>
	<th><?php _e('Level', 'paid-memberships-pro' );?></th>
	<th><?php _e('Price', 'paid-memberships-pro' );?></th>	
	<th>&nbsp;</th>
  </tr>
</thead>
<tbody>
	<?php	
	$count = 0;
	$has_any_level = false;
	foreach($pmpro_levels as $level)
	{
		$user_level = pmpro_getSpecificMembershipLevelForUser( $current_user->ID, $level->id );
		$has_level = ! empty( $user_level );
		$has_any_level = $has_level ?: $has_any_level;
	?>
	<tr class="<?php if($count++ % 2 == 0) { ?>odd<?php } ?><?php if( $has_level ) { ?> active<?php } ?>">
		<td><?php echo $has_level ? "<strong>{$level->name}</strong>" : $level->name?></td>
		<td>
			<?php
				$cost_text = pmpro_getLevelCost($level, true, true); 
				$expiration_text = pmpro_getLevelExpiration($level);
				if(!empty($cost_text) && !empty($expiration_text))
					echo $cost_text . "<br />" . $expiration_text;
				elseif(!empty($cost_text))
					echo $cost_text;
				elseif(!empty($expiration_text))
					echo $expiration_text;
			?>
		</td>
		<td>
		<?php if ( ! $has_level ) { ?>                	
			<a class="<?php echo pmpro_get_element_class( 'pmpro_btn pmpro_btn-select', 'pmpro_btn-select' ); ?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Select', 'paid-memberships-pro' );?></a>
		<?php } else { ?>      
			<?php
				//if it's a one-time-payment level, offer a link to renew	
				if( pmpro_isLevelExpiringSoon( $user_level ) && $level->allow_signups ) {
					?>
						<a class="<?php echo pmpro_get_element_class( 'pmpro_btn pmpro_btn-select', 'pmpro_btn-select' ); ?>" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Renew', 'paid-memberships-pro' );?></a>
					<?php
				} else {
					?>
						<a class="<?php echo pmpro_get_element_class( 'pmpro_btn disabled', 'pmpro_btn' ); ?>" href="<?php echo pmpro_url("account")?>"><?php _e('Your&nbsp;Level', 'paid-memberships-pro' );?></a>
					<?php
				}
			?>
		<?php } ?>
		</td>
	</tr>
	<?php
	}
	?>
</tbody>
</table>
<p class="<?php echo pmpro_get_element_class( 'pmpro_actions_nav' ); ?>">
	<?php if( $has_any_level ) { ?>
		<a href="<?php echo pmpro_url("account")?>" id="pmpro_levels-return-account"><?php _e('&larr; Return to Your Account', 'paid-memberships-pro' );?></a>
	<?php } else { ?>
		<a href="<?php echo home_url()?>" id="pmpro_levels-return-home"><?php _e('&larr; Return to Home', 'paid-memberships-pro' );?></a>
	<?php } ?>
</p> <!-- end pmpro_actions_nav -->
