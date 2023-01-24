<h3 class="ste-block ste-mt-0 ste-text-xl"><?php esc_html_e( 'Templates', 'stax-buddy-builder' ); ?></h3>

<div class="ste-flex ste-flex-wrap ste--mx-2">
	<?php foreach ( $templates as $template ) : ?>
		<div class="ste-w-full md:ste-w-1/2 lg:ste-w-1/3 ste-my-2">
			<div class="ste-px-2">
				<div class="ste-rounded ste-border ste-border-solid ste-border-ash-400 ste-overflow-hidden">
					<div class="">
					<img class="ste-w-full" src="<?php echo esc_url( $template['image'] ); ?>">
					</div>
					<div class="ste-text-center ste-px-4 ste-my-4">
						<h3 class="ste-m-0 ste-text-gray-800">
							<?php echo esc_html( $template['name'] ); ?>
						</h3>
					</div>

					<div class="ste-text-center ste-px-4 ste-my-4">
						<?php if ( $template['is_imported'] ) : ?>
							<span class="ste-bg-ash-600 ste-text-white ste-font-medium ste-py-2 ste-px-4 ste-rounded ste-block ste-cursor-default">
								<?php esc_html_e( 'Imported', 'stax-buddy-builder' ); ?>
							</span>
						<?php else : ?>
							<a href="<?php echo esc_url( $template['url'] ); ?>"
							   class="bpb-import-starter ste-no-underline ste-bg-green-500 ste-text-white ste-font-medium ste-py-2 ste-px-4 ste-rounded ste-block hover:ste-text-white hover:ste-shadow-lg">
								<?php esc_html_e( 'Import', 'stax-buddy-builder' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
