<?php
global $wpdb, $pmpro_invoice, $pmpro_msg, $pmpro_msgt, $current_user, $pmpro_currency_symbol;

if ( $pmpro_msg ) {
	?>
	<div class="pmpro_message <?php echo strip_tags( $pmpro_msgt ); ?>"><?php echo wp_kses_post( $pmpro_msg ); ?></div>
	<?php
}
?>

<?php
if ( $pmpro_invoice ) {
	?>
	<?php
	$pmpro_invoice->getUser();
	$pmpro_invoice->getMembershipLevel();
	?>

	<h4>
		<?php printf( esc_html__( 'Invoice #%s on %s', 'paid-memberships-pro' ), $pmpro_invoice->code, date_i18n( get_option( 'date_format' ), $pmpro_invoice->timestamp ) ); ?>
	</h4>
	<a class="pmpro_a-print tiny radius button bordered" href="javascript:window.print()"><i
			class="icon icon-print"></i> <?php _e( "Print", 'sweetdate' ); ?></a>
	<ul class="no-bullet">
		<?php do_action( "pmpro_invoice_bullets_top", $pmpro_invoice ); ?>
		<li>
			<strong>
				<?php esc_html_e( 'Account', 'paid-memberships-pro' ); ?>:
			</strong>
			<?php echo esc_html( $pmpro_invoice->user->display_name ); ?>
			(<?php echo esc_html( $pmpro_invoice->user->user_email ); ?>)
		</li>
		<li>
			<strong>
				<?php esc_html_e( 'Membership Level', 'paid-memberships-pro' ); ?>:
			</strong>
			<span class="label radius">
				<?php echo esc_html( $current_user->membership_level->name ); ?>
			</span>
			<br><br>
		</li>
		<?php if ( $current_user->membership_level->enddate ) { ?>
			<li><strong><?php _e( 'Membership Expires', 'paid-memberships-pro' ); ?>:</strong>
				<?php echo date( get_option( 'date_format' ), $current_user->membership_level->enddate ) ?>
			</li>
		<?php } ?>
		<?php if ( $pmpro_invoice->getDiscountCode() ) { ?>
			<li>
				<strong><?php _e( 'Discount Code', 'paid-memberships-pro' ); ?>:</strong>
				<?php echo esc_html( $pmpro_invoice->discount_code->code ); ?></li>
		<?php } ?>
		<?php do_action( "pmpro_invoice_bullets_bottom", $pmpro_invoice ); ?>
	</ul>

	<?php
	//check instructions
	if ( $pmpro_invoice->gateway == "check" && ! pmpro_isLevelFree( $pmpro_invoice->membership_level ) ) {
		echo wpautop( pmpro_getOption( "instructions" ) );
	}
	?>

	<table id="pmpro_invoice_table" class="pmpro_invoice" width="100%" cellpadding="0" cellspacing="0" border="0">
		<thead>
		<tr>
			<?php if ( ! empty( $pmpro_invoice->billing->name ) ) { ?>
				<th><?php esc_html_e( 'Billing Address', 'paid-memberships-pro' ); ?></th>
			<?php } ?>
			<th><?php esc_html_e( 'Payment Method', 'paid-memberships-pro' ); ?></th>
			<th><?php esc_html_e( 'Membership Level', 'paid-memberships-pro' ); ?></th>
			<th align="center"><?php _e( 'Total Billed', 'paid-memberships-pro' ); ?></th>
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
					<?php echo esc_html( $pmpro_invoice->cardtype ); ?>
					<?php echo _x( 'ending in', 'credit card type {ending in} xxxx', 'sweetdate' ); ?>
					<?php echo last4( $pmpro_invoice->accountnumber ) ?>
					<br/>
					<small>
						<?php esc_html_e( 'Expiration', 'paid-memberships-pro' ); ?>:
						<?php echo esc_html( $pmpro_invoice->expirationmonth ); ?>
						/<?php echo esc_html( $pmpro_invoice->expirationyear ); ?>
					</small>
				<?php } elseif ( $pmpro_invoice->payment_type ) { ?>
					<?php echo esc_html( $pmpro_invoice->payment_type ); ?>
				<?php } ?>
			</td>
			<td><?php echo esc_html( $pmpro_invoice->membership_level->name ); ?></td>
			<td align="center">
				<?php if ( $pmpro_invoice->total != '0.00' ) { ?>
					<?php if ( ! empty( $pmpro_invoice->tax ) ) { ?>
						<?php esc_html_e( 'Subtotal', 'paid-memberships-pro' ); ?>:
						<?php echo esc_html( $pmpro_currency_symbol ); ?><?php echo number_format( $pmpro_invoice->subtotal, 2 ); ?>
						<br/>
						<?php esc_html_e( 'Tax', 'paid-memberships-pro' ); ?>:
						<?php echo esc_html( $pmpro_currency_symbol ); ?><?php echo number_format( $pmpro_invoice->tax, 2 ); ?>
						<br/>
						<?php if ( ! empty( $pmpro_invoice->couponamount ) ) { ?>
							<?php esc_html_e( 'Coupon', 'paid-memberships-pro' ); ?>:
							(<?php echo esc_html( $pmpro_currency_symbol ); ?>
							<?php echo number_format( $pmpro_invoice->couponamount, 2 ); ?>)
							<br/>
						<?php } ?>
						<strong>
							<?php esc_html_e( 'Total', 'paid-memberships-pro' ); ?>:
							<?php echo esc_html( $pmpro_currency_symbol ); ?>
							<?php echo number_format( $pmpro_invoice->total, 2 ) ?>
						</strong>
					<?php } else { ?>
						<?php echo esc_html( $pmpro_currency_symbol ); ?><?php echo number_format( $pmpro_invoice->total, 2 ) ?>
					<?php } ?>
				<?php } else { ?>
					<small class="pmpro_grey"><?php echo esc_html( $pmpro_currency_symbol ); ?>0</small>
				<?php } ?>
			</td>
		</tr>
		</tbody>
	</table>
	<?php
} else {
	//Show all invoices for user if no invoice ID is passed
	$invoices = $wpdb->get_results( "SELECT *, UNIX_TIMESTAMP(timestamp) as timestamp FROM $wpdb->pmpro_membership_orders WHERE user_id = '$current_user->ID' ORDER BY timestamp DESC" );
	if ( $invoices ) {
		?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<thead>
			<tr>
				<th><?php esc_html_e( 'Date', 'paid-memberships-pro' ); ?></th>
				<th><?php esc_html_e( 'Invoice #', 'paid-memberships-pro' ); ?></th>
				<th><?php esc_html_e( 'Total Billed', 'paid-memberships-pro' ); ?></th>
				<th>&nbsp;</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $invoices as $invoice ) {
				?>
				<tr>
					<td><?php echo date( get_option( "date_format" ), $invoice->timestamp ) ?></td>
					<td>
						<a href="<?php echo esc_url( pmpro_url( "invoice", "?invoice=" . $invoice->code ) ); ?>">
							<?php echo esc_html( $invoice->code ); ?>
						</a>
					</td>
					<td><?php echo esc_html( $pmpro_currency_symbol ); ?><?php echo esc_html( $invoice->total ); ?></td>
					<td>
						<a href="<?php echo pmpro_url( "invoice", "?invoice=" . $invoice->code ) ?>" class="tiny radius button bordered">
							<?php esc_html_e( 'View Invoice', 'paid-memberships-pro' ); ?>
						</a>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php
	} else {
		?>
		<p><?php esc_html_e( 'No invoices found.', 'paid-memberships-pro' ); ?></p>
		<?php
	}
}
?>
<nav id="nav-below" class="navigation row" role="navigation">

	<?php if ( $pmpro_invoice ) { ?>
		<div class="nav-prev five columns">
			<a href="<?php echo pmpro_url( "invoice" ) ?>" class="small radius button bordered">
				<?php esc_html_e( '&larr; View All Invoices', 'paid-memberships-pro' ); ?>
			</a>
		</div>

	<?php } ?>
	<div class="one columns">&nbsp;</div>
	<div class="nav-next six columns text-right">
		<a href="<?php echo pmpro_url( "account" ) ?>" class="small radius button secondary">
			<?php esc_html_e( 'View Your Membership Account &rarr;', 'paid-memberships-pro' ); ?>
		</a>
	</div>
</nav><br>
