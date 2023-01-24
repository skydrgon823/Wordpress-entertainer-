<?php

$activity_item = bpb_get_shortcode_str( 'sitewide-activity-item', true );
$render        = bpb_is_template_populated( 'sitewide-activity-item' );

?>

<div id="activity-stream" class="activity">
	<ul class="activity-list item-list bp-list">
		<li class="activity activity_update activity-item date-recorded-1593624040" id="activity-632"
			data-bp-activity-id="632" data-bp-timestamp="1593624040">
			<?php if ( $render ) : ?>
				<?php echo do_shortcode( $activity_item ); ?>
			<?php else : ?>
				<div class="activity-avatar item-avatar">
					<a href="#">
						<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
							 class="avatar user-1-avatar avatar-150 photo" width="150" height="150"
							 alt="Profile picture of admin">
					</a>
				</div>

				<div class="activity-content">
					<div class="activity-header">
						<p>
							<a href="#">admin</a> posted an update
							<a href="#" class="view activity-time-since bp-tooltip" data-bp-tooltip="View Discussion">
								<span class="time-since">5 hours ago</span>
							</a>
						</p>
					</div>

					<div class="activity-inner">
						<p>"We are what we pretend to be, so we must be careful about what we pretend to be." ― Kurt
							Vonnegut, Mother Night</p>
					</div>

					<div class=" activity-meta action">
						<div class="generic-button">
							<a id="acomment-comment-632" class="button acomment-reply bp-primary-action bp-tooltip"
							   data-bp-tooltip="Comment" aria-expanded="false" href="#" role="button">
								<span class="bp-screen-reader-text">Comment</span>
								<span class="comment-count">0</span>
							</a>
						</div>
						<div class="generic-button">
							<a href="#" class="button fav bp-secondary-action bp-tooltip"
							   data-bp-tooltip="Mark as Favorite"
							   aria-pressed="false">
								<span class="bp-screen-reader-text">Mark as Favorite</span>
							</a>
						</div>
						<div class="generic-button">
							<a href="#"
							   class="button item-button bp-secondary-action bp-tooltip delete-activity confirm"
							   data-bp-tooltip="Delete">
								<span class="bp-screen-reader-text">Delete</span>
							</a>
						</div>
					</div>
				</div>


				<div class="activity-comments">
					<form action="#" method="post" id="ac-form-632" class="ac-form">
						<div class="ac-reply-avatar">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar-50x50.png' ); ?>"
								 class="avatar user-1-avatar avatar-50 photo" width="50" height="50"
								 alt="Profile picture of admin">
						</div>
						<div class="ac-reply-content">
							<div class="ac-textarea">
								<label for="ac-input-632" class="bp-screen-reader-text">Comment</label>
								<textarea id="ac-input-632" class="ac-input bp-suggestions"
										  name="ac_input_632"></textarea>
							</div>

							<input type="submit" name="ac_form_submit" value="Post">
							<button type="button" class="ac-reply-cancel">Cancel</button>
						</div>
					</form>
				</div>

			<?php endif; ?>
		</li>

		<li class="activity activity_comment activity-item" id="activity-714" data-bp-activity-id="714"
			data-bp-timestamp="1593428214">

			<?php if ( $render ) : ?>
				<?php echo do_shortcode( $activity_item ); ?>
			<?php else : ?>

				<div class="activity-avatar item-avatar">
					<a href="#">
						<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
							 class="avatar user-1-avatar avatar-150 photo" width="150" height="150"
							 alt="Profile picture of Robertino"> </a>
				</div>

				<div class="activity-content">
					<div class="activity-header">
						<p>
							<a href="#">Robertino</a> posted a new activity comment
							<a href="#" class="view activity-time-since bp-tooltip" data-bp-tooltip="View Discussion">
								<span class="time-since">9 days ago</span>
							</a>
						</p>
					</div>

					<div class="activity-inner">
						<p>The use of COBOL cripples the mind; its teaching should, therefore, be regarded as a criminal
							offense. (Edsgar Dijkstra)</p>
					</div>

					<div class=" activity-meta action">
						<div class="generic-button">
							<a class="button view bp-secondary-action bp-tooltip" data-bp-tooltip="View Conversation"
							   href="#" role="button">
								<span class="bp-screen-reader-text">View Conversation</span>
							</a>
						</div>
						<div class="generic-button">
							<a href="#" class="button fav bp-secondary-action bp-tooltip"
							   data-bp-tooltip="Mark as Favorite" aria-pressed="false">
								<span class="bp-screen-reader-text">Mark as Favorite</span>
							</a>
						</div>
						<div class="generic-button">
							<a href="#"
							   class="button item-button bp-secondary-action bp-tooltip delete-activity confirm"
							   data-bp-tooltip="Delete">
								<span class="bp-screen-reader-text">Delete</span>
							</a>
						</div>
					</div>
				</div>


				<div class="activity-comments">
					<ul>
						<li id="acomment-715" class="comment-item" data-bp-activity-comment-id="715">
							<div class="acomment-avatar item-avatar">
								<a href="#">
									<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
										 class="avatar user-1-avatar avatar-50 photo" width="50" height="50"
										 alt="Profile picture of Robertino"> </a>
							</div>

							<div class="acomment-meta">
								<a href="#">Robertino</a> replied <a href="#" class="activity-time-since">
									<time class="time-since" datetime="2020-06-29 10:57:06"
										  data-bp-timestamp="1593428226">1 week, 2 days ago
									</time>
								</a>
							</div>

							<div class="acomment-content">
								<p>“We are what we pretend to be, so we must be careful about what we pretend to
									be.”<br>― Kurt Vonnegut, Mother Night
								</p>
							</div>

							<div class=" activity-meta action">
								<div class="generic-button">
									<a class="acomment-reply bp-primary-action" id="acomment-reply-714-from-715"
									   href="#acomment-715">
										Reply
									</a>
								</div>
								<div class="generic-button">
									<a class="delete acomment-delete confirm bp-secondary-action" rel="nofollow"
									   href="#">
										Delete
									</a>
								</div>
							</div>
						</li>
					</ul>
				</div>
			<?php endif; ?>
		</li>

		<li class="groups activity_update activity-item date-recorded-1589581413" id="activity-611"
			data-bp-activity-id="611" data-bp-timestamp="1589581413">
			<?php if ( $render ) : ?>
				<?php echo do_shortcode( $activity_item ); ?>
			<?php else : ?>
				<div class="activity-avatar item-avatar">
					<a href="#">
						<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
							 class="avatar user-14-avatar avatar-150 photo" width="150" height="150"
							 alt="Profile picture of MaliVai Washington">
					</a>

				</div>

				<div class="activity-content">
					<div class="activity-header">
						<p>
							<a href="#">MaliVai Washington</a> posted an update in the group
							<a href="#">
								<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/group-avatar-28x28.png' ); ?>"
									 class="avatar group-40-avatar avatar-20 photo" width="20" height="20"
									 alt="Group logo of Black Swan">
								Black Swan
							</a>
							<a href="#" class="view activity-time-since bp-tooltip" data-bp-tooltip="View Discussion">
								<span class="time-since">2 months ago</span>
							</a>
						</p>
					</div>

					<div class="activity-inner">
						<p>Some cause happiness wherever they go; others, whenever they go. (Oscar Wilde)</p>
					</div>

					<div class=" activity-meta action">
						<div class="generic-button">
							<a id="acomment-comment-611" class="button acomment-reply bp-primary-action bp-tooltip"
							   data-bp-tooltip="Comment" aria-expanded="false" href="#" role="button">
								<span class="bp-screen-reader-text">Comment</span>
								<span class="comment-count">0</span>
							</a>
						</div>
						<div class="generic-button">
							<a href="#" class="button fav bp-secondary-action bp-tooltip"
							   data-bp-tooltip="Mark as Favorite"
							   aria-pressed="false">
								<span class="bp-screen-reader-text">Mark as Favorite</span>
							</a>
						</div>
						<div class="generic-button">
							<a href="#"
							   class="button item-button bp-secondary-action bp-tooltip delete-activity confirm"
							   data-bp-tooltip="Delete">
								<span class="bp-screen-reader-text">Delete</span>
							</a>
						</div>
					</div>
				</div>

				<div class="activity-comments">
					<form action="#" method="post" id="ac-form-611" class="ac-form">
						<div class="ac-reply-avatar">
							<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar-50x50.png' ); ?>"
								 class="avatar user-1-avatar avatar-50 photo" width="50" height="50"
								 alt="Profile picture of admin"></div>
						<div class="ac-reply-content">
							<div class="ac-textarea">
								<label for="ac-input-611" class="bp-screen-reader-text">Comment</label>
								<textarea id="ac-input-611" class="ac-input bp-suggestions"
										  name="ac_input_611"></textarea>
							</div>

							<input type="submit" name="ac_form_submit" value="Post">
							<button type="button" class="ac-reply-cancel">Cancel</button>
						</div>
					</form>
				</div>

			<?php endif; ?>
		</li>

		<li class="load-more">
			<a href="#">Load More</a>
		</li>
	</ul>
</div>
