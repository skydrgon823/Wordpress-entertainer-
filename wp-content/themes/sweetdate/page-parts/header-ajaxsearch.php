<div class="searchHidden" id="ajax_search_container">
	<form action="<?php echo esc_url( get_home_url() ); ?>" id="ajax_searchform" method="get" class="custom">
		<div class="row collapse">
			<div class="nine columns">
				<input autocomplete="off" type="text" id="ajax_s" name="s" value="<?php if ( isset( $_REQUEST['s'] ) ) {
					echo esc_attr( $_REQUEST['s'] );
				} ?>">
			</div>
			<div class="three columns">
				<button id="kleo_ajaxsearch" class="button radius small secondary expand postfix">
					<i class="icon icon-search"></i>
				</button>
			</div>
		</div>
	</form>
	<div class="kleo_ajax_results"></div>
</div>
