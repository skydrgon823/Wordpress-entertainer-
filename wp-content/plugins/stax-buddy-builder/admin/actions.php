<?php use Buddy_Builder\Templates;

do_action( BPB_HOOK_PREFIX . $current_slug . '_page_container_before' ); ?>

<div class="ste-container ste-mx-auto">
	<div class="ste-flex ste-flex-wrap ste--mx-2">
		<div class="ste-my-2 ste-px-2 ste-w-full lg:ste-w-2/3 overflow-hidden ste-box-border">
			<div class="ste-bg-white ste-p-6 lg:ste-p-10 ste-rounded ste-shadow-lg">
				<?php do_action( BPB_HOOK_PREFIX . $current_slug . '_page_content_before' ); ?>

				<?php do_action( BPB_HOOK_PREFIX . $current_slug . '_page_content' ); ?>

				<?php do_action( BPB_HOOK_PREFIX . $current_slug . '_page_content_after' ); ?>
			</div>
		</div>

		<div class="ste-my-2 ste-px-2 ste-w-full lg:ste-w-1/3 ste-overflow-hidden ste-box-border">

			<?php if ( $current_slug !== 'templates' ) : ?>
				<div class="ste-bg-blue-100 ste-mb-2 ste-p-6 lg:ste-p-10 ste-rounded ste-shadow-lg">
					<div class="sqp_box">
						<h2 class="ste-mt-0 ste-text-lg">
							<span class="dashicons dashicons-book ste-mr-2"></span>
							Ready to use template kits
						</h2>

						<p class="ste-m-0 ste-p-0 ste-mb-4">
							Get started quickly and import our starter template.
						</p>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . Templates::get_instance()->get_slug() ) ); ?>"
						   class="focus:ste-shadow-none">
							Go to templates »
						</a>
					</div>
				</div>

			<?php endif; ?>

			<div class="ste-bg-blue-500  ste-my-2 ste-p-6 lg:ste-p-10 ste-rounded ste-shadow-lg">
				<div class="sqp_box">
					<h2 class="ste-mt-0 ste-text-lg ste-text-white">
						<span class="dashicons dashicons-book ste-mr-2"></span>
						Get more with PRO
					</h2>

					<ul class="ste-m-0 ste-p-0 ste-mb-4 ste-text-white">
						<li>- Get full control over member & group content styling.</li>
						<li>- Fully customize members, groups & activity listing item.</li>
						<li>- Hundreds of new styling controls.</li>
						<li>- New Modern Pro template kit.</li>
						<li>- And many more...</li>
					</ul>
					<a href="https://staxwp.com/go/buddybuilder-pro" target="_blank"
					   class="focus:ste-shadow-none ste-text-white">
						Go PRO »
					</a>
				</div>
			</div>

			<div class="ste-bg-white ste-p-6 lg:ste-p-10 ste-rounded ste-shadow-lg">
				<div class="sqp_box">
					<h2 class="ste-mt-0 ste-text-lg">
						<span class="dashicons dashicons-book ste-mr-2"></span>
						Knowledge Base
					</h2>

					<p class="ste-m-0 ste-p-0 ste-mb-4">
						Have questions? Check out our knowledge base to learn more.
					</p>
					<a href="https://docs.staxwp.com/buddybuilder" target="_blank" rel="noopener"
					   class="focus:ste-shadow-none">
						Visit Knowledge Base »
					</a>
				</div>
			</div>

		</div>
	</div>
</div>
