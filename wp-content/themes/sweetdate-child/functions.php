<?php
/**
 * Theme functions and definitions.
 * For SweetDate theme.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

/*
 * If your child theme has more than one .css file (eg. ie.css, style.css, main.css) then 
 * you will have to make sure to maintain all of the parent theme dependencies.
 *
 * Make sure you're using the correct handle for loading the parent theme's styles.
 * Failure to use the proper tag will result in a CSS file needlessly being loaded twice.
 * This will usually not affect the site appearance, but it's inefficient and extends your page's loading time.
 *
 * @link https://codex.wordpress.org/Child_Themes
 */
 
add_action( 'user_register', 'add_user_to_buddypress' );

function add_user_to_buddypress( $user_id )
{
if ( ! class_exists( 'BPNetworkUsers' ) )
return;
BPNetworkUsers::add_user( $user_id, 2 );
}

// Remove admin from the member directory
function bpdev_exclude_users($qs=false,$object=false){
    
    $excluded_user='1'; // Id's to remove, separated by comma
	
    if($object != 'members' && $object != 'friends')// hide admin to members & friends 
    return $qs;
	
    $args=wp_parse_args($qs);

    if(!empty($args['user_id']))
    return $qs;	
	
    if(!empty($args['exclude']))
		$args['exclude'] = $args['exclude'].','.$excluded_user;
    else
		$args['exclude'] = $excluded_user;
	
    $qs = build_query($args);

    return $qs;
	
}
add_action('bp_ajax_querystring','bpdev_exclude_users',20,2);

// once admin is removed, we must recount the members !
function bpfr_hide_get_total_filter($count){
    return $count-1;
}
add_filter('bp_get_total_member_count','bpfr_hide_get_total_filter');


function my_pmpro_checkout_after_password() {

	if(!empty($_REQUEST['firstname']))
		$firstname = $_REQUEST['firstname'];
	else
		$firstname = "";
	if(!empty($_REQUEST['lastname']))
		$lastname = $_REQUEST['lastname']; 
	else
		$lastname = "";

?>
	<div>
		<label for="firstname">First Name</label>
		<input id="firstname" name="firstname" type="text" class="input" size="30" value="<?=esc_attr($firstname)?>" />
	</div>
	<div>
		<label for="lastname">Last Name</label>
		<input id="lastname" name="lastname" type="text" class="input" size="30" value="<?=esc_attr($lastname)?>" />
	</div>
<script>
jQuery(document).ready(function(){
	$('select#field_1619').on('change', function (e) {
		var optionSelected = $("option:selected", this);
		var valueSelected = this.value;
		str = valueSelected.replace(/\s/g, '-').toLowerCase();
		str = str.replace("\\", "");
		str = str.replace("\/", "-");
		$('div.field_food-type, div.field_specialty, div.field_musical, div.field_variety-entertainment, div.field_equipment-personel, div.field_art, div.field_crafts').hide();
		$('div.field_'+str).show();
	});
})
</script>
<?php $level = $_REQUEST['level'];

	$string = strtolower(preg_replace('/\s+/', '-', $_REQUEST['field_1619']));
	$string = preg_replace('/[^A-Za-z0-9\-]/', '-', $string); // Removes special chars.
	$labelid =  preg_replace('/-+/', '-', $string); ?>
	<style><?php echo 'div.xprofile_field.field_'.$labelid; ?>{display: block; }</style>
	<?php if(!empty($level) && $level !="1") {
		echo '<div>';
		if ( bp_is_active( 'xprofile' ) ) :
			if ( bp_has_profile( 
				array('profile_group_id' => 2, 'fetch_field_data' => false) 
					) ) :
				while ( bp_profile_groups() ) : bp_the_profile_group();
					while ( bp_profile_fields() ) : bp_the_profile_field(); ?>	
					<div <?php bp_field_css_class( 'xprofile_field' ); ?> id="field_<?php bp_the_profile_field_id(); ?>">
					<?php $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
							$field_type->edit_field_html(); ?>
					</div>
					<?php endwhile;				
					endwhile;
				endif;		
		endif;
	echo '</div>';
	}
}
add_action('pmpro_checkout_after_email', 'my_pmpro_checkout_after_password');

//update the user after checkout
function my_update_first_and_last_name_after_checkout($user_id)
{
	if(isset($_REQUEST['firstname']))
	{
		$firstname = $_REQUEST['firstname'];
		$lastname = $_REQUEST['lastname'];
		$level = $_REQUEST['level'];
		$field_1619 = $_REQUEST['field_1619'];
		$field_1682 = $_REQUEST['field_1682'];
		$field_1709 = $_REQUEST['field_1709'];
		$field_1725 = $_REQUEST['field_1725'];
		$field_1741 = $_REQUEST['field_1741'];
		$field_1760 = $_REQUEST['field_1760'];
		$field_1769 = $_REQUEST['field_1769'];
		$field_1781 = $_REQUEST['field_1781'];
	}
	elseif(isset($_SESSION['firstname']))
	{
		//maybe in sessions?
		$firstname = $_SESSION['firstname'];
		$lastname = $_SESSION['lastname'];	
		$level = $_SESSION['level'];	
		$field_1619 = $_SESSION['field_1619'];
		$field_1682 = $_SESSION['field_1682'];
		$field_1709 = $_SESSION['field_1709'];
		$field_1725 = $_SESSION['field_1725'];
		$field_1741 = $_SESSION['field_1741'];
		$field_1760 = $_SESSION['field_1760'];
		$field_1769 = $_SESSION['field_1769'];
		$field_1781 = $_SESSION['field_1781'];
		
		//unset
		unset($_SESSION['firstname']);
		unset($_SESSION['lastname']);		
		unset($_SESSION['level']);		
		unset($_SESSION['field_1619']);
		unset($_SESSION['field_1682']);
		unset($_SESSION['field_1709']);
		unset($_SESSION['field_1725']);
		unset($_SESSION['field_1741']);
		unset($_SESSION['field_1760']);
		unset($_SESSION['field_1769']);
		unset($_SESSION['field_1781']);
	}
	
	if(isset($firstname))	
		update_user_meta($user_id, "first_name", $firstname);
	if(isset($lastname))
		update_user_meta($user_id, "last_name", $lastname);
		

	if(isset($level))
		$role= ($level ==2) ? "vendor_member" : "subscriber";
		$user = new WP_User( $user_id ); // create a new user object for this user
		$user->set_role( $role );
	if(isset($field_1619) && $level ==2)
	$custom_field = "1619";
	xprofile_set_field_data($custom_field, $user_id, $field_1619);

	if(isset($field_1682) && $field_1619 == 'Food Type'){
		$field_id ="1682";
		xprofile_set_field_data($field_id, $user_id, $field_1682);
	}
	if(isset($field_1709) && $field_1619 == 'Specialty'){
		$field_id ="1709";
		xprofile_set_field_data($field_id, $user_id, $field_1709);
	}
	if(isset($field_1725) && $field_1619 == 'Musical'){
		$field_id ="1725";
		xprofile_set_field_data($field_id, $user_id, $field_1725);
	}
	if(isset($field_1741) && $field_1619 == 'Variety Entertainment'){
		$field_id ="1741";
		xprofile_set_field_data($field_id, $user_id, $field_1741);
	}
	if(isset($field_1760) && $field_1619 == 'Equipment/Personel'){
		$field_id ="1760";
		xprofile_set_field_data($field_id, $user_id, $field_1760);
	}
	if(isset($field_1769) && $field_1619 == 'Art'){
		$field_id ="1769";
		xprofile_set_field_data($field_id, $user_id, $field_1769);
	}
	if(isset($field_1781) && $field_1619 == 'Crafts' ){
		$field_id ="1781";
		xprofile_set_field_data($field_id, $user_id, $field_1781);
	}
	
}
add_action('pmpro_after_checkout', 'my_update_first_and_last_name_after_checkout');

//require the fields
function my_pmpro_registration_checks()
{
	global $pmpro_msg, $pmpro_msgt, $current_user, $pmpro_level;
	$firstname = $_REQUEST['firstname'];
	$lastname = $_REQUEST['lastname'];
	$field_1619 = $_REQUEST['field_1619'];
	if($firstname && $lastname && $field_1619 || $current_user->ID)
	{
		return true;
	}
	else
	{
		$pmpro_msg = "The first name, last name and Type of Vendor are required field.";
		$pmpro_msgt = "pmpro_error";
		return false;
	}
}
add_filter("pmpro_registration_checks", "my_pmpro_registration_checks");

function buddypress_exclude_users_by_role( $args ) {
    // do not exclude in admin.
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return $args;
    }
 
    $excluded = isset( $args['exclude'] ) ? $args['exclude'] : array();
 
    if ( ! is_array( $excluded ) ) {
        $excluded = explode( ',', $excluded );
    }
 
    $role     = 'administrator';// change to the role to be excluded.
    $user_ids = get_users( array( 'role' => $role, 'fields' => 'ID' ) );
 
    $excluded = array_merge( $excluded, $user_ids );
 
    $args['exclude'] = $excluded;
 
    return $args;
}

add_filter( 'bp_after_has_members_parse_args', 'buddypress_exclude_users_by_role' );


