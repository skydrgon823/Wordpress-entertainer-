<div class="subnav-filters filters no-ajax" id="subnav-filters">
    <div class="subnav-search clearfix">
        <div class="feed">
            <a href="#" class="bp-tooltip" data-bp-tooltip="RSS Feed">
                <span class="bp-screen-reader-text">RSS</span>
            </a>
        </div>

        <div class="dir-search activity-search bp-search" data-bp-search="activity">
            <form action="" method="get" class="bp-dir-search-form" id="dir-activity-search-form" role="search">
                <label for="dir-activity-search" class="bp-screen-reader-text">Search Activity...</label>
                <input id="dir-activity-search" name="activity_search" type="search" placeholder="Search Activity...">
                <button type="submit" id="dir-activity-search-submit" class="nouveau-search-submit"
                        name="dir_activity_search_submit">
                    <span class="dashicons dashicons-search" aria-hidden="true"></span>
                    <span id="button-text" class="bp-screen-reader-text">Search</span>
                </button>

            </form>
        </div>
    </div>

    <div id="dir-filters" class="component-filters clearfix">
        <div id="activity-filter-select" class="last filter">
            <label class="bp-screen-reader-text" for="activity-filter-by">
                <span>Show:</span>
            </label>
            <div class="select-wrap">
                <select id="activity-filter-by" data-bp-filter="activity">
                    <option value="0">— Everything —</option>
                    <option value="new_member">New Members</option>
                    <option value="updated_profile">Profile Updates</option>
                    <option value="activity_update">Updates</option>
                    <option value="friendship_accepted,friendship_created">Friendships</option>
                    <option value="created_group">New Groups</option>
                    <option value="joined_group">Group Memberships</option>
                    <option value="group_details_updated">Group Updates</option>
                    <option value="new_blog_post">Posts</option>
                    <option value="new_blog_comment">Comments</option>
                </select>
                <span class="select-arrow" aria-hidden="true"></span>
            </div>
        </div>
    </div>

</div>
