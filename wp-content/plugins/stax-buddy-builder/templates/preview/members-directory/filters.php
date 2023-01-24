<div class="subnav-filters filters no-ajax" id="subnav-filters">
    <div class="bpb-listing-type" data-component="members">
        <span class="bpb-list-mode">
            <span class="dashicons dashicons-list-view"></span>
        </span>
        <span class="bpb-grid-mode bpb-active">
            <span class="dashicons dashicons-grid-view"></span>
        </span>
    </div>

    <div class="subnav-search clearfix">
        <div class="dir-search members-search bp-search" data-bp-search="members">
            <form action="" method="get" class="bp-dir-search-form" id="dir-members-search-form" role="search">
                <label for="dir-members-search" class="bp-screen-reader-text">Search Members...</label>
                <input id="dir-members-search" name="members_search" type="search" placeholder="Search Members...">
                <button type="submit" id="dir-members-search-submit" class="nouveau-search-submit"
                        name="dir_members_search_submit">
                    <span class="dashicons dashicons-search" aria-hidden="true"></span>
                    <span id="button-text" class="bp-screen-reader-text">Search</span>
                </button>

            </form>
        </div>
    </div>

    <div id="dir-filters" class="component-filters clearfix">
        <div id="members-order-select" class="last filter">
            <label class="bp-screen-reader-text" for="members-order-by">
                <span>Order By:</span>
            </label>
            <div class="select-wrap">
                <select id="members-order-by" data-bp-filter="members">
                    <option value="active">Last Active</option>
                    <option value="newest">Newest Registered</option>
                    <option value="alphabetical">Alphabetical</option>
                </select>
                <span class="select-arrow" aria-hidden="true"></span>
            </div>
        </div>
    </div>

</div>
