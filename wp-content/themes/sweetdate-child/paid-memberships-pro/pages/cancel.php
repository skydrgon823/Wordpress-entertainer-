<?php
global $pmpro_msg, $pmpro_msgt, $pmpro_confirm;

if ( $pmpro_msg ) {
	?>
	<div class="pmpro_message <?php echo strip_tags( $pmpro_msgt ); ?>">
		<?php echo wp_kses_post( $pmpro_msg ); ?>
	</div>
	<?php
}
?>

<?php if ( ! $pmpro_confirm ) { ?>

	<p class="strong"><?php esc_html_e( 'Are you sure you want to cancel your membership?', 'sweetdate' ); ?></p>

	<p>
		<a class="pmpro_yeslink yeslink small radius button secondary" href="<?php echo esc_url( pmpro_url( "cancel", "?confirm=true" ) ); ?>">
			<?php esc_html_e( 'Yes, cancel my membership', 'sweetdate' ); ?>
		</a>
		-
		<a class="pmpro_nolink nolink small radius button" href="<?php echo esc_url( pmpro_url( "account" ) ); ?>">
			<?php esc_html_e( 'No, keep my membership', 'paid-memberships-pro' ); ?>
		</a>
	</p>
<?php } else { ?>
	<p>
		<a href="<?php echo esc_url( get_home_url() ); ?>" class="small radius button bordered">
			<?php esc_html_e( 'Click here to go to the home page.', 'paid-memberships-pro' ); ?>
		</a>
	</p>
<?php }
