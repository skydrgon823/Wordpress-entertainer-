<?php

/**
 * Template Name: BuddyPress - Members Online
 *
 * @package BuddyPress
 * @subpackage Theme
 */

get_header( 'buddypress' ); 

?>

<!-- MAIN SECTION
================================================ -->
<section>
	<div id="main" class="search-result text-center">
		<div class="row">
			<div id="main-content" class="<?php echo apply_filters('kleo_bp_directory_main_cols','twelve');?> columns">

				<?php do_action( 'bp_before_directory_members' ); ?>

				<?php do_action( 'bp_before_directory_members_content' ); ?>		
				
				<div id="members-dir-list" class="members dir-list">

					<!--Search List-->
					<div class="search-list twelve">

						<?php do_action( 'bp_before_members_loop' ); ?>


						<?php if ( bp_has_members( bp_ajax_querystring( 'members' ). '&type=online&per_page='.sq_option('buddypress_perpage') ) ) : ?>


							<?php do_action( 'bp_before_directory_members_list' ); ?>

										<div class="item-list search-list" id="members-list">
							<?php while ( bp_members() ) : bp_the_member(); ?>

								<div class="four columns">
									<div class="search-item">
									<div class="avatar">
										<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar('type=full&width=94&height=94&class='); ?></a>
										<?php do_action('bp_members_inside_avatar');?>
									</div>
										<?php do_action('bp_members_meta');?>
									<div class="search-body">
										<?php do_action( 'bp_directory_members_item' ); ?>
									</div>
									<div class="bp-member-dir-buttons">
									<?php do_action('bp_directory_members_item_last');?>
									</div>
									</div>
								</div>

							<?php endwhile; ?>
										</div>

							<?php do_action( 'bp_after_directory_members_list' ); ?>

							<?php bp_member_hidden_fields(); ?>

										<!-- Pagination -->
										<div class="row">
												<div  class="twelve columns pagination-centered">
														<div class="pagination" id="pag-bottom">
																<div id="member-dir-pag-bottom" class="pagination-links">
																<?php bp_members_pagination_links(); ?>
																</div>
														</div>
												</div>
										</div>
										<!--end  Pagination-->
						<?php else: ?>

							<div id="message" class="alert-box">
								<?php esc_html_e( "Sorry, no members were found.", 'sweetdate'); ?>
							</div>

						<?php endif; ?>

						<?php do_action( 'bp_after_members_loop' ); ?>

					</div><!--end Search List-->

				</div><!-- #members-dir-list -->

				<?php do_action( 'bp_directory_members_content' ); ?>

				<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

				<?php do_action( 'bp_after_directory_members_content' ); ?>

			</div><!--end twelve-->

			<?php do_action( 'bp_after_directory_members' ); ?>

		</div><!--end row-->
	</div><!--end main-->

</section>
	<!--END MAIN SECTION-->

<?php do_action( 'bp_after_directory_members_page' ); ?>

<?php get_footer( 'buddypress' ); ?>
