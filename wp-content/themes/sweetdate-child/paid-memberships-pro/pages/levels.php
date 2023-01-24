<?php
global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;

$pmpro_levels      = pmpro_getAllLevels( false, true );
$pmpro_level_order = pmpro_getOption( 'level_order' );


/* SWEET ADDED */
$newoptions       = sq_option( 'membership' );
$restrict_options = kleo_memberships();
$popular          = $newoptions['kleo_membership_popular'];
/* END - SWEET ADDED */

if ( ! empty( $pmpro_level_order ) ) {
	$order = explode( ',', $pmpro_level_order );

	//reorder array
	$reordered_levels = array();
	foreach ( $order as $level_id ) {
		foreach ( $pmpro_levels as $key => $level ) {
			if ( $level_id == $level->id ) {
				$reordered_levels[] = $pmpro_levels[ $key ];
			}
		}
	}

	$pmpro_levels = $reordered_levels;
} else {
	/* SWEET ADDED */
	$kleo_pmpro_levels_order = isset( $newoptions['kleo_pmpro_levels_order'] ) ? $newoptions['kleo_pmpro_levels_order'] : null;
	$pmpro_levels_sorted     = array();
	$pmpro_levels            = array_filter( $pmpro_levels );

	if ( is_array( $kleo_pmpro_levels_order ) ) {
		asort( $kleo_pmpro_levels_order );

		foreach ( $kleo_pmpro_levels_order as $k => $v ) {
			if ( ! empty( $pmpro_levels[ $k ] ) ) {
				$pmpro_levels_sorted[ $k ] = $pmpro_levels[ $k ];
				unset( $pmpro_levels[ $k ] );
			}
		}
		$pmpro_levels_sorted = $pmpro_levels_sorted + $pmpro_levels;
		$pmpro_levels        = $pmpro_levels_sorted;
	}
	/* END - SWEET ADDED */
}

$pmpro_levels = apply_filters( "pmpro_levels_array", $pmpro_levels );

if ( $pmpro_msg ) {
	?>
	<div class="message <?php echo strip_tags( $pmpro_msgt ); ?>"><?php echo wp_kses_post( $pmpro_msg ); ?></div>
	<?php
}
?>
<div class="row membership">
	<?php

	/* Get columns class text */
	$levelsno   = count( $pmpro_levels );
	$levelsno   = ( $levelsno == 0 ) ? 1 : $levelsno;
	$level_cols = 12 / $levelsno;

	switch ( $level_cols ) {
		case "1":
			$level_cols = "one";
			break;
		case "2":
			$level_cols = "two";
			break;
		case "3":
			$level_cols = "three";
			break;
		case "4":
			$level_cols = "four";
			break;
		case "6":
			$level_cols = "six";
			break;
		case "12":
			$level_cols = "twelve";
			break;
		default:
			$level_cols = "three";
			break;
	}
	$level_cols = apply_filters( 'kleo_pmpro_level_columns', $level_cols );


	foreach ( $pmpro_levels as $level ) {
		/* Current level */
		if ( isset( $current_user->membership_level->ID ) ) {
			$current_level = ( $current_user->membership_level->ID == $level->id );
		} else {
			$current_level = false;
		}

		?>
		<div class="<?php echo esc_attr( $level_cols ); ?> columns">
			<ul class="pricing-table kleo-level-<?php echo esc_attr( $level->id ); ?><?php if ( $popular == $level->id ) {
				echo ' popular';
			} ?>">
				<li class="title"><?php echo esc_html( $level->name ); ?></li>
				<li class="description">
					<?php
					if ( pmpro_isLevelFree( $level ) ) {
						$cost_text = "<strong>" . esc_html__( "Free", 'paid-memberships-pro' ) . "</strong>";
					} else {
						$cost_text = pmpro_getLevelCost( $level, true, true );
					}
					$expiration_text = pmpro_getLevelExpiration( $level );

					if ( ! empty( $cost_text ) ) {
						echo wp_kses_post( ( $cost_text ) );
					}
					if ( $expiration_text ) {
						?>
						<br/><span class="pmpro_level-expiration"><?php echo wp_kses_post( $expiration_text ); ?></span>
						<?php
					}
					?>
				</li>
				<li class="price">
					<?php
					$l_price = explode( ".", pmpro_formatPrice( $level->initial_payment ) );

					if ( pmpro_isLevelFree( $level ) || $level->initial_payment === "0.00" ) {
						echo esc_html( $l_price[0] );

					} else {
						echo esc_html( $l_price[0] );
						if ( isset( $l_price[1] ) ) {
							echo '.' . esc_html( $l_price[1] );
						}
					} ?>
				</li>
				<?php if ( $level->description ) { ?>
					<li class="bullet-item extra-description"><?php echo wp_kses_post( $level->description ); ?></li>
				<?php } ?>

				<?php
				global $kleo_pay_settings;
				foreach ( $kleo_pay_settings as $set ) {
					if ( $restrict_options[ $set['name'] ]['showfield'] != 2 ) { ?>
						<li class="bullet-item <?php if ( $restrict_options[ $set['name'] ]['type'] == 1 || ( $restrict_options[ $set['name'] ]['type'] == 2 && isset( $restrict_options[ $set['name'] ]['levels'] ) && is_array( $restrict_options[ $set['name'] ]['levels'] ) && in_array( $level->id, $restrict_options[ $set['name'] ]['levels'] ) ) ) {
							esc_html_e( "unavailable", 'paid-memberships-pro' );
						} ?>"><?php echo wp_kses_post( $set['front'] ); ?></li>
						<?php
					}
				}

				do_action( 'kleo_pmpro_after_membership_table_items', $level );
				?>

				<li class="cta-button">

					<?php
					$label = esc_html__( 'Select', 'paid-memberships-pro' );
					$href  = pmpro_url( "checkout", "?level=" . $level->id, "https" );
					$class = 'button radius small secondary';

					if ( empty( $current_user->membership_level->ID ) ) {
						if ( $popular == $level->id ) {
							$class = 'button radius';
						}
					} elseif ( ! $current_level ) {
						if ( $popular == $level->id ) {
							$class = 'button radius';
						}
					} elseif ( $current_level ) {
						if ( pmpro_isLevelExpiringSoon( $current_user->membership_level ) && $current_user->membership_level->allow_signups ) {
							$label = __( 'Renew', 'paid-memberships-pro' );
						} else {
							$class = 'button radius small secondary disabled';
							$label = __( 'Your&nbsp;Level', 'paid-memberships-pro' );
							$href  = pmpro_url( "account" );
						}
					}
					?>
					<a class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $label ); ?></a>

				</li>
			</ul>
		</div>
		<?php
	}
	?>

</div>


<nav id="nav-below" class="navigation" role="navigation" style="display: inline-block;">
	<div class="nav-previous alignleft">
		<?php if ( ! empty( $current_user->membership_level->ID ) ) { ?>
			<a href="<?php echo pmpro_url( "account" ) ?>"
			   class="small radius button link-button"><?php _e( '&larr; Return to Your Account', 'paid-memberships-pro' ); ?></a>
		<?php } else { ?>
			<a href="<?php echo home_url() ?>"
			   class="small radius button link-button"><?php _e( '&larr; Return to Home', 'paid-memberships-pro' ); ?></a>
		<?php } ?>
	</div>
	<br>&nbsp;<br><br>
</nav>