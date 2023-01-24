<?php

// loads the shortcodes class, wordpress is loaded with it
require_once( 'shortcodes.class.php' );

// get popup type
$popup = trim( $_GET['popup'] );
$shortcode = new kleo_shortcodes( $popup );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head></head>
<body>
<div id="kleo-popup">

	<div id="kleo-shortcode-wrap">
		
		<div id="kleo-sc-form-wrap">
		
			<div id="kleo-sc-form-head">
			
				<?php echo $shortcode->popup_title; ?>
			
			</div>
			<!-- /#kleo-sc-form-head -->
			
			<form method="post" id="kleo-sc-form">
			
				<table id="kleo-sc-form-table">
				
					<?php echo $shortcode->output; ?>
					
					<tbody>
						<tr class="form-row">
							<?php if( ! $shortcode->has_child ) : ?><td class="label">&nbsp;</td><?php endif; ?>
							<td class="field"><a href="#" class="button-primary kleo-insert">Insert Shortcode</a></td>							
						</tr>
					</tbody>
				
				</table>
				<!-- /#kleo-sc-form-table -->
				
			</form>
			<!-- /#kleo-sc-form -->
		
		</div>
		<!-- /#kleo-sc-form-wrap -->
		
		<div class="clear"></div>
		
	</div>
	<!-- /#kleo-shortcode-wrap -->

</div>
<!-- /#kleo-popup -->

</body>
</html>