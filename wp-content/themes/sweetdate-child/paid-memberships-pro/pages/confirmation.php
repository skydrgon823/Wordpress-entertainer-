<?php
global $wpdb, $current_user, $pmpro_invoice, $pmpro_msg, $pmpro_msgt, $pmpro_currency_symbol;

if ( $pmpro_msg ) {
	?>
	<div class="pmpro_message <?php echo strip_tags( $pmpro_msgt ); ?>"><?php echo wp_kses_post( $pmpro_msg ); ?></div>
	<?php
}

if ( empty( $current_user->membership_level ) ) {
	$confirmation_message = "<p class=\"strong\">" . esc_html__( 'Your payment has been submitted to PayPal. Your membership will be activated shortly.', 'paid-memberships-pro' ) . "</p>";
} else {
	$confirmation_message = "<p class=\"strong\">" . sprintf( esc_html__( 'Thank you for your membership to %s. Your %s membership is now active.', 'paid-memberships-pro' ), get_bloginfo( "name" ), $current_user->membership_level->name ) . "</p>";
}

//confirmation message for this level
$level_message = $wpdb->get_var( "SELECT l.confirmation FROM $wpdb->pmpro_membership_levels l LEFT JOIN $wpdb->pmpro_memberships_users mu ON l.id = mu.membership_id WHERE mu.status = 'active' AND mu.user_id = '" . $current_user->ID . "' LIMIT 1" );
if ( ! empty( $level_message ) ) {
	$confirmation_message .= "\n" . stripslashes( $level_message ) . "\n";
}
?>

<?php if ( $pmpro_invoice ) { ?>

	<?php
	$pmpro_invoice->getUser();
	$pmpro_invoice->getMembershipLevel();

	$confirmation_message .= "<p>" . sprintf( esc_html__( 'Below are details about your membership account and a receipt for your initial membership invoice. A welcome email with a copy of your initial membership invoice has been sent to %s.', 'paid-memberships-pro' ), $pmpro_invoice->user->user_email ) . "</p>";

	//check instructions
	if ( $pmpro_invoice->gateway == "check" && ! pmpro_isLevelFree( $pmpro_invoice->membership_level ) ) {
		$confirmation_message .= wpautop( pmpro_getOption( "instructions" ) );
	}

	$confirmation_message = apply_filters( "pmpro_confirmation_message", $confirmation_message, $pmpro_invoice );

	echo apply_filters( "the_content", $confirmation_message );
	?>


	<h3>
		<?php printf( _x( 'Invoice #%s on %s', 'Invoice # header. E.g. Invoice #ABCDEF on 2013-01-01.', 'paid-memberships-pro' ), $pmpro_invoice->code, date( get_option( 'date_format' ), $pmpro_invoice->timestamp ) ); ?>
	</h3>
	<a class="pmpro_a-print" href="javascript:window.print()"><?php _e( 'Print', 'paid-memberships-pro' ); ?></a>
	<ul class="no-bullet">
		<?php do_action( "pmpro_invoice_bullets_top", $pmpro_invoice ); ?>
		<li>
			<strong><?php esc_html_e( 'Account', 'paid-memberships-pro' ); ?>:</strong>
			<?php echo esc_html( $pmpro_invoice->user->display_name ); ?>
			(<?php echo esc_html( $pmpro_invoice->user->user_email ); ?>)
		</li>
		<li><strong><?php _e( 'Membership Level', 'paid-memberships-pro' ); ?>:</strong> <span
				class="label radius"><?php echo esc_html( $current_user->membership_level->name ); ?></span></li>
		<?php if ( $current_user->membership_level->enddate ) { ?>
			<li><strong><?php _e( 'Membership Expires', 'paid-memberships-pro' ); ?>
					:</strong> <?php echo date( get_option( 'date_format' ), $current_user->membership_level->enddate ) ?>
			</li>
		<?php } ?>
		<?php if ( $pmpro_invoice->getDiscountCode() ) { ?>
			<li><strong><?php esc_html_e( 'Discount Code', 'paid-memberships-pro' ); ?>
					:</strong> <?php echo esc_html( $pmpro_invoice->discount_code->code ); ?></li>
		<?php } ?>
		<?php do_action( "pmpro_invoice_bullets_bottom", $pmpro_invoice ); ?>
	</ul>

	<table id="pmpro_confirmation_table" class="pmpro_invoice" width="100%" cellpadding="0" cellspacing="0" border="0">
		<thead>
		<tr>
			<?php if ( ! empty( $pmpro_invoice->billing->name ) ) { ?>
				<th><?php esc_html_e( 'Billing Address', 'paid-memberships-pro' ); ?></th>
			<?php } ?>
			<th><?php esc_html_e( 'Payment Method', 'paid-memberships-pro' ); ?></th>
			<th><?php esc_html_e( 'Membership Level', 'paid-memberships-pro' ); ?></th>
			<th><?php esc_html_e( 'Total Billed', 'paid-memberships-pro' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<?php if ( ! empty( $pmpro_invoice->billing->name ) ) { ?>
				<td>
					<?php echo esc_html( $pmpro_invoice->billing->name ); ?><br/>
					<?php echo esc_html( $pmpro_invoice->billing->street ); ?><br/>
					<?php if ( $pmpro_invoice->billing->city && $pmpro_invoice->billing->state ) { ?>
						<?php echo esc_html( $pmpro_invoice->billing->city ); ?>,
						<?php echo esc_html( $pmpro_invoice->billing->state ); ?>
						<?php echo esc_html( $pmpro_invoice->billing->zip ); ?>
						<?php echo esc_html( $pmpro_invoice->billing->country ); ?>
						<br/>
					<?php } ?>
					<?php echo formatPhone( $pmpro_invoice->billing->phone ) ?>
				</td>
			<?php } ?>
			<td>
				<?php if ( $pmpro_invoice->accountnumber ) { ?>
					<?php echo esc_html( $pmpro_invoice->cardtype ); ?><?php echo _x( 'ending in', 'credit card type {ending in} xxxx', 'sweetdate' ); ?><?php echo last4( $pmpro_invoice->accountnumber ) ?>
					<br/>
					<small><?php esc_html_e( 'Expiration', 'paid-memberships-pro' ); ?>:
						<?php echo esc_html( $pmpro_invoice->expirationmonth ); ?>
						/<?php echo esc_html( $pmpro_invoice->expirationyear ); ?></small>
				<?php } elseif ( $pmpro_invoice->payment_type ) { ?>
					<?php echo esc_html( $pmpro_invoice->payment_type ); ?>
				<?php } ?>
			</td>
			<td><span class="label radius">
					<?php echo esc_html( $pmpro_invoice->membership_level->name ); ?>
				</span></td>
			<td><?php if ( $pmpro_invoice->total ) {
					echo esc_html( $pmpro_currency_symbol ) . number_format( $pmpro_invoice->total, 2 );
				} else {
					echo "---";
				} ?></td>
		</tr>
		</tbody>
	</table>
	<?php
} else {
	$confirmation_message .= "<p>" . sprintf( esc_html__( 'Below are details about your membership account. A welcome email with has been sent to %s.', 'paid-memberships-pro' ), $current_user->user_email ) . "</p>";

	$confirmation_message = apply_filters( "pmpro_confirmation_message", $confirmation_message, false );

	echo wp_kses_post( $confirmation_message );
	?>
	<ul class="no-bullet">
		<li><strong><?php esc_html_e( 'Account', 'paid-memberships-pro' ); ?>:</strong>
			<?php echo esc_html( $current_user->display_name ); ?>
			(<?php echo esc_html( $current_user->user_email ); ?>)
		</li>
		<li>
			<strong><?php esc_html_e( 'Membership Level', 'paid-memberships-pro' ); ?>:</strong>
			<span class="label radius">
				<?php if ( ! empty( $current_user->membership_level ) ) {
					echo esc_html( $current_user->membership_level->name );
				} else {
					_ex( "Pending", "User without membership is in {pending} status.", 'paid-memberships-pro' );
				} ?>
			</span>
		</li>
	</ul>
	<?php
}
?>
<nav id="nav-below" class="navigation" role="navigation">
	<div class="nav-next">
		<?php if ( ! empty( $current_user->membership_level ) ) { ?>
			<a href="<?php echo esc_url( pmpro_url( "account" ) ); ?>"
			   class="small radius button secondary"><?php esc_html_e( 'View Your Membership Account &rarr;', 'paid-memberships-pro' ); ?></a>
		<?php } else { ?>
			<?php esc_html_e( 'If your account is not activated within a few minutes, please contact the site owner.', 'paid-memberships-pro' ); ?>
		<?php } ?>
	</div>
</nav>
