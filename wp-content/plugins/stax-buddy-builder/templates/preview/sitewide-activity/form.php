<h2 class="bp-screen-reader-text">Post Update</h2>

<div id="bp-nouveau-activity-form" class="activity-update-form">
	<form name="whats-new-form" method="post" id="whats-new-form" class="activity-form activity-form-expanded">
		<div id="whats-new-avatar">
			<a href="#">
				<img src="<?php echo esc_url( BPB_ASSETS_URL . 'img/member-avatar-50x50.png' ); ?>"
					 class="avatar user-1-avatar avatar-50 photo" width="50" height="50" alt="Profile photo of admin">
			</a>
		</div>
		<div id="whats-new-content">
			<div id="whats-new-textarea">
				<textarea name="whats-new" cols="50" rows="4" placeholder="What's new, admin?"
						  aria-label="Post what's new" id="whats-new" class="bp-suggestions"
						  style="resize: vertical; height: auto;"></textarea>
			</div>
		</div>
		<div id="whats-new-options" style="display: block; opacity: 1;">
			<div id="whats-new-post-in-box"">
				<select name="whats-new-post-in" aria-label="Post in" id="whats-new-post-in">
					<option value="profile">Post in: Profile</option>
					<option value="group">Post in: Group</option>
				</select>
				<ul id="whats-new-post-in-box-items">
					<li><input type="text" id="activity-autocomplete" placeholder="Start typing the group name..."></li>
				</ul>
		</div>
		<div id="whats-new-submit">
			<input type="submit" id="aw-whats-new-submit" class="button" name="aw-whats-new-submit"
				   value="Post Update">
			<input type="reset" id="aw-whats-new-reset" class="text-button small" value="Cancel">
		</div>
	</form>
</div>
