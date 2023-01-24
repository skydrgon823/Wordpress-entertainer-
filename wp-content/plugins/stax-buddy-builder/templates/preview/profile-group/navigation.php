<nav class="main-navs no-ajax bp-navs single-screen-navs horizontal groups-nav" id="object-nav" role="navigation"
	 aria-label="Group menu">
	<ul>
		<?php if ( $show_home ) : ?>
			<li id="home-groups-li" class="bp-groups-tab current selected">
				<a href="#" id="home">
					Home
				</a>
			</li>
		<?php endif; ?>

		<li id="activity-groups-li" class="bp-groups-tab">
			<a href="#" id="activity">
				Activity
			</a>
		</li>

		<li id="members-groups-li" class="bp-groups-tab">
			<a href="#" id="members">
				Members
				<span class="count">2</span>
			</a>
		</li>

		<li id="invite-groups-li" class="bp-groups-tab">
			<a href="#" id="invite">
				Invite
			</a>
		</li>

		<li id="admin-groups-li" class="bp-groups-tab">
			<a href="#" id="admin">
				Manage
			</a>
		</li>
	</ul>
</nav>
