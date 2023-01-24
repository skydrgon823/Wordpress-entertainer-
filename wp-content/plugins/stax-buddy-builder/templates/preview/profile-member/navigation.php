<nav class="main-navs no-ajax bp-navs single-screen-navs horizontal users-nav" id="object-nav" role="navigation"
	 aria-label="Member menu">
	<ul>
		<?php if ( $show_home ) : ?>
			<li id="front-personal-li" class="bp-personal-tab">
				<a href="#" id="user-front">
					Home
				</a>
			</li>
		<?php endif; ?>
		<li id="activity-personal-li" class="bp-personal-tab current selected">
			<a href="#" id="user-activity">
				Activity
			</a>
		</li>
		<li id="xprofile-personal-li" class="bp-personal-tab">
			<a href="#" id="user-xprofile">
				Profile
			</a>
		</li>
		<li id="notifications-personal-li" class="bp-personal-tab">
			<a href="#" id="user-notifications">
				Notifications
				<span class="count">10</span>
			</a>
		</li>
		<li id="messages-personal-li" class="bp-personal-tab">
			<a href="#" id="user-messages">
				Messages
				<span class="count">33</span>
			</a>
		</li>
		<li id="friends-personal-li" class="bp-personal-tab">
			<a href="#" id="user-friends">
				Friends
				<span class="count">1220</span>
			</a>
		</li>
		<li id="groups-personal-li" class="bp-personal-tab">
			<a href="#" id="user-groups">
				Groups
				<span class="count">6</span>
			</a>
		</li>
		<li id="settings-personal-li" class="bp-personal-tab">
			<a href="#" id="user-settings">
				Settings
			</a>
		</li>
	</ul>
</nav>
