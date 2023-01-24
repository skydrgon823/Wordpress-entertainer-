<div class="subnav-filters filters no-ajax" id="subnav-filters">
    <div class="bpb-listing-type" data-component="groups">
        <span class="bpb-list-mode">
            <span class="dashicons dashicons-list-view"></span>
        </span>
        <span class="bpb-grid-mode bpb-active">
            <span class="dashicons dashicons-grid-view"></span>
        </span>
    </div>

    <div class="subnav-search clearfix">
        <div class="dir-search groups-search bp-search" data-bp-search="groups">
            <form action="" method="get" class="bp-dir-search-form" id="dir-groups-search-form" role="search">
                <label for="dir-groups-search" class="bp-screen-reader-text">Search Groups...</label>

                <input id="dir-groups-search" name="groups_search" type="search" placeholder="Search Groups...">

                <button type="submit" id="dir-groups-search-submit" class="nouveau-search-submit"
                        name="dir_groups_search_submit">
                    <span class="dashicons dashicons-search" aria-hidden="true"></span>
                    <span id="button-text" class="bp-screen-reader-text">Search</span>
                </button>

            </form>
        </div>
    </div>

    <div id="comp-filters" class="component-filters clearfix">
        <div id="groups-order-select" class="last filter">
            <label for="groups-order-by" class="bp-screen-reader-text">
                <span>Order By:</span>
            </label>
            <div class="select-wrap">
                <select id="groups-order-by" data-bp-filter="groups">
                    <option value="active">Last Active</option>
                    <option value="popular">Most Members</option>
                    <option value="newest">Newly Created</option>
                    <option value="alphabetical">Alphabetical</option>
                </select>
                <span class="select-arrow" aria-hidden="true"></span>
            </div>
        </div>
    </div>
</div>
