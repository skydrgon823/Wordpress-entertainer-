<?php

add_action ('init', 'bds_upgrade');
function bds_upgrade ()
{
	$db_version = 100;

	$settings = get_option ('bds_settings');
	if ($settings === false)
	{
		$settings = new stdClass;
		$settings->db_version = 0;
	}

	$installed = $settings->db_version;
	if ($installed >= $db_version)  return false;

	$settings->db_version = $db_version;
	update_option ('bds_settings', $settings);

	if ($installed < 100)
	{
		bds_create_locations ();
	}
}

add_action ('plugins_loaded', 'bds_translate');
function bds_translate ()
{
	load_plugin_textdomain ('bp-distance-search');
}

add_filter ('bp_xprofile_get_field_types', 'bds_location');
function bds_location ($types)
{
	$new = array ('bds_location' => 'BDS_XProfile_Field_Type_Location');
	$types = array_merge ($types, $new);

	return $types;
}

add_action ('wp_enqueue_scripts', 'bds_register_script_front');
function bds_register_script_front(){
	$option = get_option ('bds_settings');
	$key = isset ($option->api_key)? $option->api_key: '';
	$api = 'https://maps.googleapis.com/maps/api/js?libraries=places&key='. $key;
	wp_enqueue_script('bds-google-maps', $api);
}

add_action ('admin_enqueue_scripts', 'bds_register_script_admin');
function bds_register_script_admin (){
	$option = get_option ('bds_settings');
	$key = isset ($option->api_key)? $option->api_key: '';
	$api = 'https://maps.googleapis.com/maps/api/js?libraries=places&key='. $key;
//
//	Register the script. The script handle will be passed to BP Profile Search.
//
	wp_register_script ('bds-google-maps', $api);
}

class BDS_XProfile_Field_Type_Location extends BP_XProfile_Field_Type {

	public function __construct() {
		parent::__construct();

		$this->category = _x( 'Geolocation', 'xprofile field type category', 'bp-distance-search' );
		$this->name = _x( 'Google Place Autocomplete', 'xprofile field type', 'bp-distance-search' );

		$this->set_format( '/^.*$/', 'replace' );
		$this->do_settings_section = true;

		do_action( 'bds_xprofile_field_type_location', $this );
	}

	public function edit_field_html( array $raw_properties = array() ) {

		if ( isset( $raw_properties['user_id'] ) ) {
			unset( $raw_properties['user_id'] );
		}

		$r = bp_parse_args( $raw_properties, array(
			'type'  => 'text',
			'value' => bp_get_the_profile_field_edit_value(),
			'placeholder' => __( 'Start typing, then select a location', 'bp-distance-search' ),
		) ); 
	?>
		<legend id="<?php bp_the_profile_field_input_name(); ?>-1">
			<?php bp_the_profile_field_name(); ?>
			<?php bp_the_profile_field_required_label(); ?>
		</legend>

		<?php

		wp_enqueue_script ('bds-location', plugins_url ('bp-distance-search/bds-location.js'), array ('bds-google-maps'));
		$icon_title = __('get current location', 'bp-distance-search');
		$id = 'field_'. bp_get_the_profile_field_id ();
		do_action( bp_get_the_profile_field_errors_action() ); ?>

		<input <?php echo $this->get_edit_field_html_elements( $r ); ?> style="min-width: 30em;" aria-labelledby="<?php bp_the_profile_field_input_name(); ?>-1" aria-describedby="<?php bp_the_profile_field_input_name(); ?>-3">
		<span id="<?php echo $id; ?>_icon" style="cursor: pointer;" title="<?php echo $icon_title; ?>" class="dashicons dashicons-location"></span>
		<input type="hidden" id="<?php echo $id; ?>_lat" name="Lat_<?php echo $id; ?>" />
		<input type="hidden" id="<?php echo $id; ?>_lng" name="Lng_<?php echo $id; ?>" /> 
		<?php if ( bp_get_the_profile_field_description() ) : ?>
			<p class="description" id="<?php bp_the_profile_field_input_name(); ?>-3"><?php bp_the_profile_field_description(); ?></p>
		<?php endif; ?>
		<script>
			jQuery(function ($) {
				bds_autocomplete('<?php echo $id; ?>', '<?php echo $id; ?>_lat', '<?php echo $id; ?>_lng');
				$('#<?php echo $id; ?>_icon').click(function () {
					bds_locate('<?php echo $id; ?>', '<?php echo $id; ?>_lat', '<?php echo $id; ?>_lng')
				});
			});
		</script>
<?php
	}

	public function admin_field_html( array $raw_properties = array() ) {

		$r = bp_parse_args( $raw_properties, array(
			'type' => 'text'
		) ); ?>

		<label for="<?php bp_the_profile_field_input_name(); ?>" class="screen-reader-text"><?php
			/* translators: accessibility text */
			esc_html_e( 'Google Place Autocomplete', 'bp-distance-search' );
		?></label>
		<input <?php echo $this->get_edit_field_html_elements( $r ); ?>>

		<?php
	}

	public function admin_new_field_html( BP_XProfile_Field $current_field, $control_type = '' )
	{
		$type = array_search( get_class( $this ), bp_xprofile_get_field_types() );
		if ( false === $type ) {
			return;
		}

		$option = get_option ('bds_settings');
		$key = isset ($option->api_key)? $option->api_key: '';

		$class = $current_field->type != $type ? 'display: none;' : '';
?>
		<div id="<?php echo esc_attr( $type ); ?>" class="postbox bp-options-box" style="<?php echo esc_attr( $class ); ?> margin-top: 15px;">
			<h3><?php esc_html_e( 'Please enter your Google API key:', 'bp-distance-search' ); ?></h3>
			<div class="inside" aria-live="polite" aria-atomic="true" aria-relevant="all">
				<p>
					<input name="field-settings[bds_api_key]" value="<?php echo $key; ?>" style="width: 80%;" />
				</p>
			</div>
		</div>
<?php
	}

	public function admin_save_settings( $field_id, $settings )
	{
		$option = get_option ('bds_settings');
		$option->api_key = $settings['bds_api_key'];
		update_option ('bds_settings', $option);
	}
}

add_action ('xprofile_data_before_delete', 'bds_delete_location');
function bds_delete_location ($data)
{
	global $wpdb;

	$field = new BP_XProfile_Field ($data->field_id);
	if ($field->type != 'bds_location')  return false;

	$table_name = $wpdb->prefix. 'bds_locations';
	$query = "DELETE FROM $table_name WHERE (user_id = %d AND field_id = %d);";

	$query = $wpdb->prepare ($query, $data->user_id, $data->field_id);
	return $wpdb->query ($query);
}

add_action ('xprofile_data_before_save', 'bds_save_location');
function bds_save_location ($data)
{
	global $wpdb;

	$field = new BP_XProfile_Field ($data->field_id);
	if ($field->type != 'bds_location')  return false;

	if ($_POST['Lat_field_'. $data->field_id] === '')  return false;
	
	$table_name = $wpdb->prefix. 'bds_locations';
	$lat = $_POST['Lat_field_'. $data->field_id];
	$lng = $_POST['Lng_field_'. $data->field_id];
	$query = "INSERT INTO $table_name
		(user_id, field_id, location, lat, lng) VALUES (%d, %d, %s, %f, %f)
		ON DUPLICATE KEY UPDATE location = %s, lat = %f, lng = %f;";

	$query = $wpdb->prepare ($query, $data->user_id, $data->field_id, $data->value, $lat, $lng, $data->value, $lat, $lng);
	return $wpdb->query ($query);
}

function bds_create_locations ()
{
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate ();
	$table_name = $wpdb->prefix. 'bds_locations';

	$sql = "CREATE TABLE $table_name (
		user_id			BIGINT(20) UNSIGNED NOT NULL,
		field_id		BIGINT(20) UNSIGNED NOT NULL,
		location		VARCHAR(255) NOT NULL,
		lat				FLOAT(10,6) NOT NULL,
		lng				FLOAT(10,6) NOT NULL,
		PRIMARY KEY  (user_id, field_id)
	) $charset_collate;";

    require_once (ABSPATH. 'wp-admin/includes/upgrade.php');
    dbDelta ($sql);
}

//
// This is the code to interface BP Profile Search. For a full explanation see:
// https://dontdream.it/bp-profile-search/custom-profile-field-types/
//

// action hook to register your field type with BP Profile Search
add_action ('bps_custom_field', 'bds_location_setup');
add_action ('bp_ps_custom_field', 'bds_location_setup');
function bds_location_setup ($f)
{
	if ($f->type != 'bds_location')  return;				// not your field type

	$f->format = 'location';								// your field format must be 'location'
	$f->script_handle = 'bds-google-maps';					// your script registered with wp_register_script()
	$f->search = 'bds_location_search';						// your function to search by distance
	$f->sort_directory = 'bds_location_sort_directory';		// your optional function to sort by distance
	$f->get_value = 'bds_location_get_value';				// your optional function to get the distance
}

// your function to search by distance
// adapt this function to use your own lat-long table
function bds_location_search ($f)
{
	global $wpdb;

	if ($f->filter == 'distance')
	{
		$table_name = $wpdb->prefix. 'bds_locations';
		$lat = $f->value['lat'];
		$lng = $f->value['lng'];
		$distance = $f->value['distance'];
		$R = ($f->value['units'] == 'km')? 6371: 3959;

		$sql = array ('select' => '', 'where' => array ());
		$sql['select'] = "SELECT user_id FROM $table_name";
		$where = "acos (
			sin (radians (lat)) * sin (radians (%f)) +
			cos (radians (lat)) * cos (radians (%f)) * cos (radians (lng) - radians (%f))) < %f";
		$sql['where'][] = $wpdb->prepare ($where, $lat, $lat, $lng, $distance/$R);
		$sql['where'][] = $wpdb->prepare ('field_id = %d', $f->id);

		$sql = apply_filters ('bps_field_sql', $sql, $f);
		$sql = apply_filters ('bp_ps_field_sql', $sql, $f);
		$query = $sql['select']. ' WHERE '. implode (' AND ', $sql['where']);

		$results = $wpdb->get_col ($query);
		return $results;
	}
	else
	{
		if (function_exists ('bps_xprofile_search'))
			return bps_xprofile_search ($f);
		if (function_exists ('bp_ps_xprofile_search'))
			return bp_ps_xprofile_search ($f);
	}
}

// your optional function to sort by distance
// this function provides the ability to sort the search results by distance
function bds_location_sort_directory ($sql, $object, $f, $order)
{
	global $wpdb;

	if (isset ($f->filter) && $f->filter == 'distance')
	{
		$lat = $f->value['lat'];
		$lng = $f->value['lng'];

		$object->uid_name = 'user_id';
		$object->uid_table = $wpdb->prefix. 'bds_locations';

		$sql['select'] = "SELECT u.user_id AS id FROM {$object->uid_table} u";
		$sql['where'] = str_replace ('u.ID', 'u.user_id', $sql['where']);
		$sql['where'][] = "u.user_id IN (SELECT ID FROM {$wpdb->users} WHERE user_status = 0)";
		$sql['where'][] = $wpdb->prepare ("u.field_id = %d", $f->id);
		$orderby = "ORDER BY acos (
			sin (radians (lat)) * sin (radians (%f)) +
			cos (radians (lat)) * cos (radians (%f)) * cos (radians (lng) - radians (%f)))";
		$sql['orderby'] = $wpdb->prepare ($orderby, $lat, $lat, $lng);
		$sql['order'] = $order;

		return $sql;
	}
	else
	{
		if (function_exists ('bps_xprofile_sort_directory'))
			return bps_xprofile_sort_directory ($sql, $object, $f, $order);
		if (function_exists ('bp_ps_xprofile_sort_directory'))
			return bp_ps_xprofile_sort_directory ($sql, $object, $f, $order);	// for completeness
	}
}

// your optional function to get the distance
// this function provides the ability to show the distance in the member details section
function bds_location_get_value ($f)
{
	global $wpdb;
	global $members_template;

	if (isset ($f->filter) && $f->filter == 'distance')
	{
		if ($members_template->current_member == 0)
		{
			$users = wp_list_pluck ($members_template->members, 'ID');
			BP_XProfile_ProfileData::get_value_byid ($f->id, $users);
		}

		$table_name = $wpdb->prefix. 'bds_locations';
		$lat = $f->value['lat'];
		$lng = $f->value['lng'];
		$R = ($f->value['units'] == 'km')? 6371: 3959;
		$user = $members_template->member->ID;

		$query = "SELECT (%f * acos (
			sin (radians (lat)) * sin (radians (%f)) +
			cos (radians (lat)) * cos (radians (%f)) * cos (radians (lng) - radians (%f)))) AS distance
			FROM $table_name
			WHERE field_id = %d AND user_id = %d";

		$query = $wpdb->prepare ($query, $R, $lat, $lat, $lng, $f->id, $user);

		$f->d_format = 'distance';
		$f->d_value = BP_XProfile_ProfileData::get_value_byid ($f->id, $user);
		$f->d_distance = $wpdb->get_var ($query);
		$f->d_units = $f->value['units'];
	}
	else
	{
		if (function_exists ('bps_xprofile_get_value'))
			bps_xprofile_get_value ($f);
		if (function_exists ('bp_ps_xprofile_get_value'))
			bp_ps_xprofile_get_value ($f);	// for completeness
	}
}

//
// End of the interface to BP Profile Search.
//
