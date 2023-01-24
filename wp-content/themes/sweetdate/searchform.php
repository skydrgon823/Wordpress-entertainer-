<form role="search" method="get" id="searchform" class="custom" action="<?php echo home_url( '/' ); ?>">
	<div class="row collapse">
		<div class="nine columns">
			<input type="text" value="<?php if ( isset( $_GET ) && isset( $_GET['s'] ) ) {
				echo esc_attr( $_GET['s'] );
			} ?>" name="s" id="s">
		</div>
		<div class="three columns">
			<input type="submit" class="button radius small secondary expand postfix" id="searchsubmit"
			       value="<?php esc_html_e( "Search", 'sweetdate' ); ?>">
		</div>
	</div>
</form>
