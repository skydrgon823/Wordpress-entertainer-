<script>
var $webim = jQuery.noConflict();
(function($webim) {
$webim(document).ready(function() {
	// Setup the array of shortcode options
	$webim.shortcode_select = {
		'0' : $webim([]),
	    'shortcode-buddypress-activity' : $webim('#shortcode-buddypress-activity'),
		'shortcode-activity-carousel' : $webim('#shortcode-activity-carousel'),
		'shortcode-buddypress-groups-carousel' : $webim('#shortcode-buddypress-groups-carousel'),
		'shortcode-buddypress-groups-grid' : $webim('#shortcode-buddypress-groups-grid'),
		'shortcode-buddypress-members-carousel' : $webim('#shortcode-buddypress-members-carousel'),
		'shortcode-buddypress-members-grid' : $webim('#shortcode-buddypress-members-grid')
	};
	// Hide each option section
	$webim.each($webim.shortcode_select, function() {
		this.css({ display: 'none' });
	});
	// Show the selected option section
	$webim('#shortcode-select').change(function() {
		$webim.each($webim.shortcode_select, function() {
			this.css({ display: 'none' });
		});
		$webim.shortcode_select[$webim(this).val()].css({
			display: 'block'
		});
	});
});
})($webim);
</script>
<!-- LOAD scripts -->
<div class="webim-shortcode-form-header">BuddyPress Shortcodes</div>    
	<div class="webim-page-content">
		<form name="webim_shortcode_form" action="#">
			<div id="shortcode_wrap">
				<div id="shortcode_panel" class="current">
					<fieldset>
						<h4>Select a shortcode</h4>
						<div class="option">
							<label for="shortcode-select">Shortcode</label>
							<select id="shortcode-select" name="shortcode-select">
								<option value="0"></option>
								<optgroup label="Activity Shortcodes">
								<option value="shortcode-buddypress-activity">Activity</option>
								<option value="shortcode-activity-carousel">Activity Carousel</option>
								</optgroup>
								<optgroup label="Groups Shortcodes">
								<option value="shortcode-buddypress-groups-carousel">Groups Carousel</option>
								<option value="shortcode-buddypress-groups-grid">Groups Grid</option>
								</optgroup>
								<optgroup label="Members Shortcodes">
								<option value="shortcode-buddypress-members-carousel">Members Carousel</option>
								<option value="shortcode-buddypress-members-grid">Members Grid</option>
								</optgroup>
								<option value="0"></option>
							</select>
						</div>
                        <!--//////////////////////////////
						////	ACTIVITY
						//////////////////////////////-->
						<div id="shortcode-buddypress-activity" class="shortcode-option">
						<div class="webim-form-header"><p>ACTIVITY</p></div>
						<table>
						<tr>
								<td><label for="webim-option-buddypress-activity-title">Title</label></td>
								<td><input type="text" id="webim-option-buddypress-activity-title"  value="Your Title" /></td>
						</tr>
						<tr>
								<td><label for="webim-option-buddypress-activity-number">Number of Activity to Display</label></td>
								<td><input type="text" id="webim-option-buddypress-activity-number"  value="12" /></td>
						</tr>
						</table>
						<p class="webim-submit-button">
							<input id="webim-send-buddypress-activity" class="button button-primary button-large" value="Send to Editor" type="submit" />
						</p>
						</div>	
                        <!--//////////////////////////////
						////	ACTIVITY CAROUSEL
						//////////////////////////////-->
						<div id="shortcode-activity-carousel" class="shortcode-option">
						<div class="webim-form-header"><p>ACTIVITY CAROUSEL</p></div>
						<table>
						<tr>
								<td><label for="webim-activity-carousel-number">Number of Activity to Display</label></td>
								<td><input type="text" id="webim-activity-carousel-number"  value="12" /></td>
						</tr>
						<tr>
								<td><label for="webim-activity-carousel-color">Activity Content Color</label></td>
								<td><input id="webim-activity-carousel-color" class="webim-colorpicker" type="text" value="#f00000" data-default-color="#f00000" /></td>
						</tr>
						</table>
						<p class="webim-submit-button">
							<input id="webim-send-activity-carousel" class="button button-primary button-large" value="Send to Editor" type="submit" />
						</p>
						</div>	
                        <!--//////////////////////////////
						////	GROUPS CAROUSEL
						//////////////////////////////-->
						<div id="shortcode-buddypress-groups-carousel" class="shortcode-option">
						<div class="webim-form-header"><p>GROUPS CAROUSEL</p></div>
						<table>
						<tr>
								<td><label for="webim-option-buddypress-groups-carousel-title">Title</label></td>
								<td><input type="text" id="webim-option-buddypress-groups-carousel-title"  value="Your Title" /></td>
						</tr>
						<tr>
						<td><label>Type of Groups to Display</label></td>
						<td><select id="webim-option-buddypress-groups-carousel-type">
										<option value="popular">Popular</option>
										<option value="active">Active</option>
										<option value="alphabetical">Alphabetical</option>
										<option value="newest">Newest</option>
									</select>
						</td>
						</tr>
						<tr>
								<td><label for="webim-option-buddypress-groups-carousel-visible">Number of Groups visible at a Time</label></td>
								<td><input type="text" id="webim-option-buddypress-groups-carousel-visible"  value="3" /></td>
						</tr>
						<tr>
								<td><label for="webim-option-buddypress-groups-carousel-number">Number of Groups to Display</label></td>
								<td><input type="text" id="webim-option-buddypress-groups-carousel-number"  value="12" /></td>
						</tr>
						</table>
						<p class="webim-submit-button">
							<input id="webim-send-buddypress-groups-carousel" class="button button-primary button-large" value="Send to Editor" type="submit" />
						</p>
						</div>	
                       <!--//////////////////////////////
						////	GROUPS GRID
						//////////////////////////////-->
						<div id="shortcode-buddypress-groups-grid" class="shortcode-option">
						<div class="webim-form-header"><p>GROUPS GRID</p></div>
						<table>
						<tr>
								<td><label for="webim-option-buddypress-groups-grid-title">Title</label></td>
								<td><input type="text" id="webim-option-buddypress-groups-grid-title"  value="Your Title" /></td>
						</tr>
						<tr>
						<td><label>Type of Groups to Display</label></td>
						<td><select id="webim-option-buddypress-groups-grid-type">
										<option value="popular">Popular</option>
										<option value="active">Active</option>
										<option value="alphabetical">Alphabetical</option>
										<option value="newest">Newest</option>
									</select>
						</td>
						</tr>
						<tr>
								<td><label for="webim-option-buddypress-groups-grid-number">Number of Groups to Display</label></td>
								<td><input type="text" id="webim-option-buddypress-groups-grid-number"  value="12" /></td>
						</tr>
						<tr>
						<td><label>Choose No of Groups in a Row</label></td>
						<td><select id="webim-option-buddypress-groups-per-row">
										<option value="6">Two</option>
										<option value="4">Three</option>
										<option value="3">Four</option>
										<option value="2">Six</option>
									</select>
						</td>
						</tr>
						</table>
						<p class="webim-submit-button">
							<input id="webim-send-buddypress-groups-grid" class="button button-primary button-large" value="Send to Editor" type="submit" />
						</p>
						</div>	
                       <!--//////////////////////////////
						////	MEMBERS CAROUSEL
						//////////////////////////////-->
						<div id="shortcode-buddypress-members-carousel" class="shortcode-option">
						<div class="webim-form-header"><p>MEMBERS CAROUSEL</p></div>
						<table>
						<tr>
								<td><label for="webim-option-buddypress-members-carousel-title">Title</label></td>
								<td><input type="text" id="webim-option-buddypress-members-carousel-title"  value="Your Title" /></td>
						</tr>
						<tr>
						<td><label>Type of Members to Display</label></td>
						<td><select id="webim-option-buddypress-members-carousel-type">
										<option value="popular">Popular</option>
										<option value="active">Active</option>
										<option value="alphabetical">Alphabetical</option>
										<option value="newest">Newest</option>
									</select>
						</td>
						</tr>
						<tr>
								<td><label for="webim-option-buddypress-members-carousel-visible">Number of Members visible at a Time</label></td>
								<td><input type="text" id="webim-option-buddypress-members-carousel-visible"  value="3" /></td>
						</tr>
						<tr>
								<td><label for="webim-option-buddypress-members-carousel-number">Number of Members to Display</label></td>
								<td><input type="text" id="webim-option-buddypress-members-carousel-number"  value="12" /></td>
						</tr>
						</table>
						<p class="webim-submit-button">
							<input id="webim-send-buddypress-members-carousel" class="button button-primary button-large" value="Send to Editor" type="submit" />
						</p>
						</div>	
                       <!--//////////////////////////////
						////	MEMBERS GRID
						//////////////////////////////-->
						<div id="shortcode-buddypress-members-grid" class="shortcode-option">
						<div class="webim-form-header"><p>MEMBERS GRID</p></div>
						<table>
						<tr>
								<td><label for="webim-option-buddypress-members-grid-title">Title</label></td>
								<td><input type="text" id="webim-option-buddypress-members-grid-title"  value="Your Title" /></td>
						</tr>
						<tr>
						<td><label>Type of Members to Display</label></td>
						<td><select id="webim-option-buddypress-members-grid-type">
										<option value="popular">Popular</option>
										<option value="active">Active</option>
										<option value="alphabetical">Alphabetical</option>
										<option value="newest">Newest</option>
									</select>
						</td>
						</tr>
						<tr>
								<td><label for="webim-option-buddypress-members-grid-number">Number of Members to Display</label></td>
								<td><input type="text" id="webim-option-buddypress-members-grid-number"  value="12" /></td>
						</tr>
						<tr>
						<td><label>Choose No of Members in a Row</label></td>
						<td><select id="webim-option-buddypress-members-per-row">
										<option value="6">Two</option>
										<option value="4">Three</option>
										<option value="3">Four</option>
										<option value="2">Six</option>
									</select>
						</td>
						</tr>
						</table>
						<p class="webim-submit-button">
							<input id="webim-send-buddypress-members-grid" class="button button-primary button-large" value="Send to Editor" type="submit" />
						</p>
						</div>	
						<!--//////////////////////////////
						////	Shortcodes end Here
						//////////////////////////////-->
					</fieldset>			
				</div>
			</div><!-- CLOSE shortcode_wrap -->
		</form><!-- CLOSE webim_shortcode_form -->
</div><!-- CLOSE webim-page-content -->