<div class="bp-wrap">
	<div id="item-body" class="item-body">
		<nav class="bp-navs bp-subnavs no-ajax user-subnav" id="subnav" role="navigation" aria-label="Activity menu">
			<ul class="subnav">
				<li id="just-me-personal-li"
					class="bp-personal-sub-tab current selected"
					data-bp-user-scope="just-me">
					<a href="#" id="just-me">
						Personal
					</a>
				</li>
				<li id="activity-mentions-personal-li"
					class="bp-personal-sub-tab"
					data-bp-user-scope="mentions">
					<a href="#" id="activity-mentions">
						Mentions
					</a>
				</li>
				<li id="activity-favs-personal-li"
					class="bp-personal-sub-tab" data-bp-user-scope="favorites">
					<a href="#" id="activity-favs">
						Favorites
					</a>
				</li>
				<li id="activity-friends-personal-li"
					class="bp-personal-sub-tab" data-bp-user-scope="friends">
					<a href="#" id="activity-friends">
						Friends
					</a>
				</li>
				<li id="activity-groups-personal-li"
					class="bp-personal-sub-tab" data-bp-user-scope="groups">
					<a href="#" id="activity-groups">
						Groups
					</a>
				</li>
			</ul>
		</nav>

		<h2 class="bp-screen-title bp-screen-reader-text">Member Activities</h2>
		<h2 class="bp-screen-reader-text">Post Update</h2>

		<div id="bp-nouveau-activity-form" class="activity-update-form">
			<form name="whats-new-form" method="post" id="whats-new-form" class="activity-form activity-form-expanded">
				<div id="whats-new-avatar">
					<a href="#">
						<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar-50x50.png' ); ?>"
							 class="avatar user-1-avatar avatar-50 photo" width="50" height="50"
							 alt="Profile photo of admin">
					</a>
				</div>
				<div id="whats-new-content">
					<div id="whats-new-textarea">
				<textarea name="whats-new" cols="50" rows="4"
						  placeholder="What's new, admin?" aria-label="Post what's new"
						  id="whats-new" class="bp-suggestions"
						  style="resize: vertical; height: auto;"></textarea>
					</div>
				</div>
				<div id="whats-new-options" style="display: block; opacity: 1;">
					<div id="whats-new-submit" class="in-profile">
						<input type="submit" id="aw-whats-new-submit"
							   class="button" name="aw-whats-new-submit"
							   value="Post Update">
						<input type="reset"
							   id="aw-whats-new-reset"
							   class="text-button small"
							   value="Cancel">
					</div>
				</div>
			</form>
		</div>

		<div class="subnav-filters filters no-ajax" id="subnav-filters">
			<div class="subnav-search clearfix">
				<div class="feed">
					<a href="#" class="bp-tooltip" data-bp-tooltip="RSS Feed">
						<span class="bp-screen-reader-text">RSS</span>
					</a>
				</div>
			</div>

			<div id="comp-filters" class="component-filters clearfix">
				<div id="activity-filter-select" class="last filter">
					<label for="activity-filter-by" class="bp-screen-reader-text">
						<span>Show:</span>
					</label>
					<div class="select-wrap">
						<select id="activity-filter-by" data-bp-filter="activity">
							<option value="0">— Everything —</option>
							<option value="">Example</option>
							<option value="">Example</option>
							<option value="">Example</option>
						</select>
						<span class="select-arrow" aria-hidden="true"></span>
					</div>
				</div>
			</div>
		</div>

		<div id="activity-stream" class="activity single-user">
			<ul class="activity-list item-list bp-list">
				<li class="groups activity_update activity-item" id="activity-105" data-bp-activity-id="105"
					data-bp-timestamp="1589790450">
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
								<a href="#">admin</a> posted an update in the group
								<a href="#">
									<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/group-avatar-50x50.png' ); ?>"
										 class="avatar group-1-avatar avatar-20 photo" width="20" height="20"
										 alt="Group logo of ExampleGroup">test
								</a>
								<a href="#"
								   class="view activity-time-since bp-tooltip"
								   data-bp-tooltip="View Discussion">
									<span class="time-since">7 days ago</span>
								</a>
							</p>
						</div>

						<div class="activity-inner">
							<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
								has been the industry's standard dummy text ever since the 1500s, when an unknown
								printer took a galley of type and scrambled it to make a type specimen book. It has
								survived not only five centuries, but also the leap into electronic typesetting,
								remaining essentially unchanged. It was popularised in the 1960s with the release of
								Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
								publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
						</div>

						<div class=" activity-meta action">
							<div class="generic-button">
								<a id="acomment-comment-105"
								   class="button acomment-reply bp-primary-action bp-tooltip"
								   data-bp-tooltip="Comment" aria-expanded="false"
								   href="#" role="button">
									<span class="bp-screen-reader-text">Comment</span>
									<span class="comment-count">0</span>
								</a>
							</div>
							<div class="generic-button">
								<a href="#"
								   class="button fav bp-secondary-action bp-tooltip"
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
						<form action="#" method="post" id="ac-form-105" class="ac-form" style="display: block;">
							<div class="ac-reply-avatar">
								<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar.png' ); ?>"
									 class="avatar user-1-avatar avatar-50 photo" width="50" height="50"
									 alt="Profile picture of admin">
							</div>
							<div class="ac-reply-content">
								<div class="ac-textarea">
									<label for="ac-input-105" class="bp-screen-reader-text">Comment</label>
									<textarea id="ac-input-105" class="ac-input bp-suggestions"
											  name="ac_input_105"></textarea>
								</div>
								<input type="submit" name="ac_form_submit" value="Post">
								<button type="button" class="ac-reply-cancel">Cancel</button>
							</div>
						</form>
					</div>
				</li>

				<li class="groups activity_update activity-item" id="activity-105" data-bp-activity-id="105"
					data-bp-timestamp="1589790450">
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
								<a href="#">admin</a> posted an update in the group
								<a href="#">
									<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/group-avatar-50x50.png' ); ?>"
										 class="avatar group-1-avatar avatar-20 photo" width="20" height="20"
										 alt="Group logo of ExampleGroup">test
								</a>
								<a href="#"
								   class="view activity-time-since bp-tooltip"
								   data-bp-tooltip="View Discussion">
									<span class="time-since">7 days ago</span>
								</a>
							</p>
						</div>

						<div class="activity-inner">
							<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
								has been the industry's standard dummy text ever since the 1500s, when an unknown
								printer took a galley of type and scrambled it to make a type specimen book. It has
								survived not only five centuries, but also the leap into electronic typesetting,
								remaining essentially unchanged. It was popularised in the 1960s with the release of
								Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
								publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
						</div>

						<div class=" activity-meta action">
							<div class="generic-button">
								<a id="acomment-comment-105"
								   class="button acomment-reply bp-primary-action bp-tooltip"
								   data-bp-tooltip="Comment" aria-expanded="false"
								   href="#" role="button">
									<span class="bp-screen-reader-text">Comment</span>
									<span class="comment-count">0</span>
								</a>
							</div>
							<div class="generic-button">
								<a href="#"
								   class="button fav bp-secondary-action bp-tooltip"
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
				</li>
			</ul>
		</div>
	</div>
</div>
