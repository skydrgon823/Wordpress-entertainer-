<h3 class="ste-block ste-mt-0 ste-text-xl"><?php esc_html_e( 'Templates Settings', 'stax-buddy-builder' ); ?></h3>

<p><?php esc_html_e( 'You can override the BuddyPress\' default templates with the ones you\'ve created using Elementor using the form below.', 'stax-buddy-builder' ); ?></p>

<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" enctype="multipart/form-data">
	<div class="ste-flex ste-flex-wrap ste--mx-2">

		<?php foreach ( $data as $slug => $setting ) : ?>
			<div class="ste-my-2 ste-w-full md:ste-w-1/2 xl:ste-w-1/2">
				<div class="ste-mx-2 ste-flex ste-flex-wrap ste-justify-between ste-items-center ste-border-2 ste-border-solid ste-border-ash-300 ste-rounded ste-bg-ash-100 ste-p-4 hover:ste-border-ash-500">
					<?php if ( isset( $setting['title'] ) ) : ?>
						<div class="ste-mb-3 ste-flex ste-items-center">
							<?php if ( isset( $setting['pro'] ) && $setting['pro'] ) : ?>
								<span class="ste-inline-block ste-text-white ste-text-xs ste-bg-red-500 ste-rounded-full ste-px-3 ste-font-normal ste-mr-2">PRO</span>
							<?php endif; ?>
							<label for="<?php echo $slug; ?>"
								   class="ste-inline-block ste-w-full ste-text-gray-700 ste-text-lg ste-font-medium">
								<?php echo $setting['title']; ?>
							</label>
						</div>
					<?php endif; ?>

					<?php if ( isset( $setting['inner'] ) ) : ?>
						<?php foreach ( $setting['inner'] as $key => $item ) : ?>
							<div class="ste-w-full ste-mb-4 last:ste-mb-0">
								<div class="ste-w-full ste-mb-3 ste-flex ste-items-center">
									<?php if ( isset( $item['pro'] ) && $item['pro'] ) : ?>
										<span class="ste-inline-block ste-text-white ste-text-xs ste-bg-red-500 ste-rounded-full ste-px-2 ste-font-normal ste-mr-2">PRO</span>
									<?php endif; ?>
									<label class="ste-inline-block ste-text-gray-700 ste-text-lg ste-font-medium">
										<?php echo $item['title']; ?>
									</label>
								</div>
								<?php include 'parts/settings-box.php'; ?>
							</div>
						<?php endforeach; ?>
					<?php else : ?>

						<?php
						$item = $setting;
						$key  = $slug;
						include 'parts/settings-box.php';
						?>

					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<input type="hidden" name="action" value="bpb_settings">

	<div class="ste-mt-5">
		<button type="submit"
				class="ste-bg-green-500 ste-text-md ste-text-white ste-py-2 ste-pl-4 ste-pr-6 ste-rounded ste-border-0 ste-shadow-xl hover:ste-shadow-lg ste-cursor-pointer ste-uppercase">
				<span class="ste-flex ste-items-center">
					<span class="dashicons dashicons-yes"></span>
					<span class="ste-leading-normal ste-ml-2"><?php esc_html_e( 'Save', 'stax-buddy-builder' ); ?></span>
				</span>
		</button>
	</div>
</form>
