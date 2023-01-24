<?php

use Buddy_Builder\Library\Documents\BuddyPress;

$active_post_id   = false;
$active_post_name = false;
?>


<select name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="ste-w-full">
	<option value="0"
			selected><?php esc_html_e( '-- BuddyPress Default --', 'stax-buddy-builder' ); ?></option>

	<?php if ( ! empty( $item['posts'] ) ) : ?>
		<?php foreach ( $item['posts'] as $post ) : ?>

			<option value="<?php echo $post['id']; ?>"<?php echo $post['status'] ? ' selected' : ''; ?>>
				<?php echo $post['title']; ?>
			</option>

			<?php
			if ( $post['status'] === true ) {
				$active_post_id   = $post['id'];
				$active_post_name = $post['title'];
			}
			?>
		<?php endforeach; ?>
	<?php endif; ?>
</select>
<div class="ste-flex ste-justify-between  ste-w-full">
	<div class="ste-mt-4 ste-flex ste-leading-none">
		<?php
		printf(
			'<a href="%1$s" class="ste-text-xs ste-text-blue-500 ste-no-underline focus:ste-shadow-none">' .
			'<span class="dashicons dashicons-plus ste-text-sm ste-leading-none"></span> ' .
			'%2$s' .
			'</a>',
			add_query_arg(
				[
					'action'                               => 'elementor_new_post',
					'post_type'                            => 'elementor_library',
					'template_type'                        => 'bpb-buddypress',
					BuddyPress::REMOTE_CATEGORY_META_KEY   => $key,
					BuddyPress::SET_AS_ACTIVE_CATEGORY_KEY => 1,
					'_wpnonce'                             => wp_create_nonce( 'elementor_action_new_post' ),
				],
				admin_url( 'edit.php' )
			),
			esc_html__( 'Create new', 'stax-buddy-builder' )
		);
		?>
	</div>


	<?php

	if ( $active_post_id ) {
		$post_id = $active_post_id;

		$parent_post_id = wp_is_post_revision( $post_id );

		if ( $parent_post_id ) {
			$post_id = $parent_post_id;
		}
		?>
		<div class="ste-mt-4 ste-flex ste-leading-none">
			<?php
			printf(
				'<a href="%1$s" class="ste-text-xs ste-text-blue-500 ste-no-underline focus:ste-shadow-none">' .
				'<span class="dashicons dashicons-edit ste-text-sm ste-leading-none"></span> ' .
				'%2$s' .
				'</a>',
				add_query_arg(
					[
						'post'   => $post_id,
						'action' => 'elementor',
					],
					admin_url( 'post.php' )
				),
				esc_html__( 'Edit current', 'stax-buddy-builder' )
			);
			?>
		</div>

		<?php
	}
	?>
</div>
