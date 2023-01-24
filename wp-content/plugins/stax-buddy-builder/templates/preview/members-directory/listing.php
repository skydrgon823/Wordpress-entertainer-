<?php

$members_item = bpb_get_shortcode_str( 'members-directory-item', true );
$render       = bpb_is_template_populated( 'members-directory-item' );

$list_classes = [ 'grid' ];

$list_classes[] = bpb_get_column_class( $columns['desktop'] );
$list_classes[] = bpb_get_column_class( $columns['tablet'], 'tablet' );
$list_classes[] = bpb_get_column_class( $columns['mobile'], 'mobile' );

?>

<div id="members-dir-list" class="members dir-list">
	<div class="bp-pagination top" data-bp-pagination="upage">
		<div class="pag-count top">
			<p class="pag-data">
				Viewing 1 - active members </p>
		</div>

		<div class="bp-pagination-links top">
			<p class="pag-data">
				<span class="page-numbers current">1</span>
				<a class="page-numbers" href="#">2</a>
				<a class="next page-numbers" href="#">→</a></p>
		</div>
	</div>

	<ul id="members-list"
		class="item-list members-list bp-list <?php echo esc_attr( implode( ' ', $list_classes ) ); ?>">
		<li class="item-entry odd is-online is-current-user" data-bp-item-id="1" data-bp-item-component="members">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $members_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
								 class="avatar user-1-avatar avatar-150 photo" width="150" height="150"
								 alt="Profile picture of John Doe">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title member-name">
								<a href="#">John Doe</a>
							</h2>

							<p class="item-meta last-activity">
								active 38 seconds ago
							</p>

							<ul class="members-meta action">
								<li id="friendship-button-24" class="friendship-button not_friends generic-button">
									<button id="friend-24" class="friendship-button not_friends add" rel="add">
										Add Friend
									</button>
								</li>
							</ul>
						</div>

						<div class="user-update">
							<p class="update"> - "I just did snorkeling for the first time. It was so […]"
								<span class="activity-read-more">
									<a href="#" rel="nofollow">View</a>
								</span>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry even" data-bp-item-id="2" data-bp-item-component="members">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $members_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
								 class="avatar user-10-avatar avatar-150 photo" width="150" height="150"
								 alt="Profile picture of Monta Ellis">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title member-name">
								<a href="#">Monta Ellis</a>
							</h2>

							<p class="item-meta last-activity">active 11 minutes ago</p>

							<ul class=" members-meta action">
								<li id="friendship-button-10" class="friendship-button is_friend generic-button">
									<button id="friend-10" class="friendship-button is_friend remove" rel="remove">
										Cancel Friendship
									</button>
								</li>
							</ul>
						</div>

						<div class="user-update">
							<p class="update"> - "Can't believe what's happening in my back garden. My dog destroyed
								every […]"
								<span class="activity-read-more">
									<a href="#" rel="nofollow">View</a>
								</span>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry odd" data-bp-item-id="3" data-bp-item-component="members">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $members_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
								 class="avatar user-1-avatar avatar-150 photo" width="150" height="150"
								 alt="Profile picture of Simon Andy">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title member-name">
								<a href="#">Simon Andy</a>
							</h2>

							<p class="item-meta last-activity">
								active 20 seconds ago
							</p>

							<ul class="members-meta action">
								<li id="friendship-button-24" class="friendship-button not_friends generic-button">
									<button id="friend-24" class="friendship-button not_friends add" rel="add">
										Add Friend
									</button>
								</li>
							</ul>
						</div>

						<div class="user-update">
							<p class="update"> - "I'm starting a club for all the golf lovers. If you enjoy golf your
								are […]"
								<span class="activity-read-more">
									<a href="#" rel="nofollow">View</a>
								</span>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry even" data-bp-item-id="4" data-bp-item-component="members">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $members_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
								 class="avatar user-10-avatar avatar-150 photo" width="150" height="150"
								 alt="Profile picture of Jessica Smith">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title member-name">
								<a href="#">Jessica Smith</a>
							</h2>

							<p class="item-meta last-activity">active 9 minutes ago</p>

							<ul class=" members-meta action">
								<li id="friendship-button-10" class="friendship-button is_friend generic-button">
									<button id="friend-10" class="friendship-button is_friend remove" rel="remove">
										Cancel Friendship
									</button>
								</li>
							</ul>
						</div>

						<div class="user-update">
							<p class="update"> - "My daughter just asked me when are we getting a cat. What should I do
								now? […]"
								<span class="activity-read-more">
									<a href="#" rel="nofollow">View</a>
								</span>
							</p>
						</div
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry odd" data-bp-item-id="5" data-bp-item-component="members">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $members_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
								 class="avatar user-1-avatar avatar-150 photo" width="150" height="150"
								 alt="Profile picture of Tom Morty">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title member-name">
								<a href="#">Tom Morty</a>
							</h2>

							<p class="item-meta last-activity">active 10 seconds ago</p>

							<ul class="members-meta action">
								<li id="friendship-button-24" class="friendship-button not_friends generic-button">
									<button id="friend-24" class="friendship-button not_friends add" rel="add">
										Add Friend
									</button>
								</li>
							</ul>
						</div>

						<div class="user-update">
							<p class="update"> - "End of school. Hi there holiday! Yeeeeeeeee […]"
								<span class="activity-read-more">
									<a href="#" rel="nofollow">View</a>
								</span>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry even" data-bp-item-id="6" data-bp-item-component="members">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $members_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
								 class="avatar user-10-avatar avatar-150 photo" width="150" height="150"
								 alt="Profile picture of Jim Wilson">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title member-name">
								<a href="#">Jim Wilson</a>
							</h2>

							<p class="item-meta last-activity">active 55 minutes ago</p>

							<ul class=" members-meta action">
								<li id="friendship-button-10" class="friendship-button is_friend generic-button">
									<button id="friend-10" class="friendship-button is_friend remove" rel="remove">
										Cancel Friendship
									</button>
								</li>
							</ul>
						</div>

						<div class="user-update">
							<p class="update"> - "What's up guys? I'm throwing a party tonight, so anyone is welcomed at
								my place. Bring some […]"
								<span class="activity-read-more">
									<a href="#" rel="nofollow">View</a>
								</span>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry odd" data-bp-item-id="7" data-bp-item-component="members">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $members_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
								 class="avatar user-1-avatar avatar-150 photo" width="150" height="150"
								 alt="Profile picture of Monica Wayne">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title member-name">
								<a href="#">Monica Wayne</a>
							</h2>

							<p class="item-meta last-activity">
								active 50 seconds ago
							</p>

							<ul class=" members-meta action">
								<li id="friendship-button-10" class="friendship-button is_friend generic-button">
									<button id="friend-10" class="friendship-button is_friend remove" rel="remove">
										Cancel Friendship
									</button>
								</li>
							</ul>
						</div>

						<div class="user-update">
							<p class="update"> - "What's up with this new app? What's tiktok or whatever is called. I
								think I'm gonna […]"
								<span class="activity-read-more">
									<a href="#" rel="nofollow">View</a>
								</span>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</li>

		<li class="item-entry even" data-bp-item-id="8" data-bp-item-component="members">
			<div class="list-wrap">
				<?php if ( $render ) : ?>
					<?php echo do_shortcode( $members_item ); ?>
				<?php else : ?>
					<div class="item-avatar">
						<a href="#">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
								 class="avatar user-10-avatar avatar-150 photo" width="150" height="150"
								 alt="Profile picture of Travis John">
						</a>
					</div>

					<div class="item">
						<div class="item-block">
							<h2 class="list-title member-name">
								<a href="#">Travis John</a>
							</h2>

							<p class="item-meta last-activity">active 28 minutes ago</p>

							<ul class=" members-meta action">
								<li id="friendship-button-10" class="friendship-button is_friend generic-button">
									<button id="friend-10" class="friendship-button is_friend remove" rel="remove">
										Cancel Friendship
									</button>
								</li>
							</ul>
						</div>

						<div class="user-update">
							<p class="update"> - "Just bricked my phone, cuz I'm and engineer. But don't worry guys, I'm
								an […]"
								<span class="activity-read-more">
									<a href="#" rel="nofollow">View</a>
								</span>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</li>
	</ul>


	<div class="bp-pagination bottom" data-bp-pagination="upage">
		<div class="pag-count bottom">
			<p class="pag-data">Viewing 1 - 20 of 27 active members</p>
		</div>

		<div class="bp-pagination-links bottom">
			<p class="pag-data">
				<span class="page-numbers current">1</span>
				<a class="page-numbers" href="#">2</a>
				<a class="next page-numbers" href="#">→</a></p>
		</div>
	</div>


</div>
