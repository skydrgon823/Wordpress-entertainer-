<?php

$group_item = bpb_get_shortcode_str( 'groups-directory-item', true );
$render     = bpb_is_template_populated( 'groups-directory-item' );

$list_classes = [ 'grid' ];

$list_classes[] = bpb_get_column_class( $columns['desktop'] );
$list_classes[] = bpb_get_column_class( $columns['tablet'], 'tablet' );
$list_classes[] = bpb_get_column_class( $columns['mobile'], 'mobile' );

?>

<div id="groups-dir-list" class="groups dir-list">
	<div class="bp-pagination top" data-bp-pagination="grpage">
		<div class="pag-count top">
			<p class="pag-data">
				Viewing 1 - 20 of 21 groups </p>
		</div>
		<div class="bp-pagination-links top">
			<p class="pag-data">
				<span class="page-numbers current">1</span>
				<a class="page-numbers" href="#">2</a>
				<a class="next page-numbers" href="#">→</a></p>
		</div>
	</div>

	<ul id="groups-list" class="item-list groups-list bp-list <?php echo esc_attr( implode( ' ', $list_classes ) ); ?>">
		<li class="item-entry odd public is-admin is-member group-has-avatar" data-bp-item-id="1"
			data-bp-item-component="groups">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $group_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/group-avatar.png' ); ?>"
								 class="avatar group-21-avatar avatar-150 photo" width="150" height="150"
								 alt="Group logo of Group name">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title groups-title">
								<a href="#" class="bp-group-home-link qrergvdsb-home-link">Group Name</a>
							</h2>
							<p class="item-meta group-details">Public Group / 1 member</p>
							<p class="last-activity item-meta">
								active 9 seconds ago </p>
						</div>
						<div class="group-desc"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
								eiusmod tempor […]</p></div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry even public is-admin is-member group-has-avatar" data-bp-item-id="2"
			data-bp-item-component="groups">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $group_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/group-avatar.png' ); ?>"
								 class="avatar group-20-avatar avatar-150 photo" width="150"
								 height="150"
								 alt="Group logo of Group Name">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title groups-title">
								<a href="#" class="bp-group-home-link ytrh-home-link">Group Name</a>
							</h2>
							<p class="item-meta group-details">Public Group / 1 member</p>
							<p class="last-activity item-meta">
								active 28 seconds ago </p>
						</div>
						<div class="group-desc"><p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
								nisi ut aliquip […]</p></div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry odd private is-admin is-member group-has-avatar" data-bp-item-id="3"
			data-bp-item-component="groups">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $group_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/group-avatar.png' ); ?>"
								 class="avatar group-2-avatar avatar-150 photo" width="150" height="150"
								 alt="Group logo of Group Name">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title groups-title">
								<a href="#" class="bp-group-home-link qwe-home-link">Group Name</a></h2>
							<p class="item-meta group-details">Private Group / 1 member</p>
							<p class="last-activity item-meta">active 8 minutes ago </p>
						</div>
						<div class="group-desc"><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
								dolore eu […]</p></div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry even hidden is-admin is-member group-has-avatar" data-bp-item-id="4"
			data-bp-item-component="groups">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $group_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/group-avatar.png' ); ?>"
								 class="avatar group-2-avatar avatar-150 photo" width="150" height="150"
								 alt="Group logo of Group Name">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title groups-title">
								<a href="#" class="bp-group-home-link qwe-home-link">Group Name</a></h2>
							<p class="item-meta group-details">Hidden Group / 1 member</p>
							<p class="last-activity item-meta">active 8 minutes ago </p>
						</div>
						<div class="group-desc"><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui
								officia […]</p></div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry odd public is-admin is-member group-has-avatar" data-bp-item-id="5"
			data-bp-item-component="groups">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $group_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/group-avatar.png' ); ?>"
								 class="avatar group-2-avatar avatar-150 photo" width="150" height="150"
								 alt="Group logo of Group Name">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title groups-title">
								<a href="#" class="bp-group-home-link qwe-home-link">Group 5</a></h2>
							<p class="item-meta group-details">Public Group / 1 member</p>
							<p class="last-activity item-meta">active 8 minutes ago </p>
						</div>
						<div class="group-desc"><p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem
								accusantium doloremque […]</p></div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry even public is-admin is-member group-has-avatar" data-bp-item-id="6"
			data-bp-item-component="groups">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $group_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/group-avatar.png' ); ?>"
								 class="avatar group-2-avatar avatar-150 photo" width="150" height="150"
								 alt="Group logo of Group Name">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title groups-title">
								<a href="#" class="bp-group-home-link qwe-home-link">Group Name</a></h2>
							<p class="item-meta group-details">Public Group / 1 member</p>
							<p class="last-activity item-meta">active 8 minutes ago </p>
						</div>
						<div class="group-desc"><p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut
								fugit, sed quia consequuntur […]</p></div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry odd public is-admin is-member group-has-avatar" data-bp-item-id="7"
			data-bp-item-component="groups">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $group_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/group-avatar.png' ); ?>"
								 class="avatar group-2-avatar avatar-150 photo" width="150" height="150"
								 alt="Group logo of Group Name">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title groups-title">
								<a href="#" class="bp-group-home-link qwe-home-link">Group Name</a></h2>
							<p class="item-meta group-details">Public Group / 1 member</p>
							<p class="last-activity item-meta">active 8 minutes ago </p>
						</div>
						<div class="group-desc"><p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet,
								consectetur.</p></div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry even public is-admin is-member group-has-avatar" data-bp-item-id="8"
			data-bp-item-component="groups">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $group_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/group-avatar.png' ); ?>"
								 class="avatar group-2-avatar avatar-150 photo" width="150" height="150"
								 alt="Group logo of Group Name">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title groups-title">
								<a href="#" class="bp-group-home-link qwe-home-link">Group Name</a></h2>
							<p class="item-meta group-details">Public Group / 1 member</p>
							<p class="last-activity item-meta">active 8 minutes ago </p>
						</div>
						<div class="group-desc"><p>Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse
								quam nihil molestiae […]</p></div>
					</div>
				<?php endif; ?>
			</div>
		</li>
	</ul>

	<div class="bp-pagination bottom" data-bp-pagination="grpage">
		<div class="pag-count bottom">
			<p class="pag-data">
				Viewing 1 - 20 of 21 groups </p>
		</div>

		<div class="bp-pagination-links bottom">
			<p class="pag-data">
				<span class="page-numbers current">1</span>
				<a class="page-numbers" href="#">2</a>
				<a class="next page-numbers" href="#">→</a></p>
		</div>
	</div>
</div>
