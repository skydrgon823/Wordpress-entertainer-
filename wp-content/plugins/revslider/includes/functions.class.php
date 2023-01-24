<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      https://www.themepunch.com/
 * @copyright 2019 ThemePunch
 */
 
if(!defined('ABSPATH')) exit();

class RevSliderFunctions extends RevSliderData {
	
	public function __construct(){
	}
	
	/**
	 * START: DEPRECATED FUNCTIONS THAT ARE IN HERE FOR OLD ADDONS TO WORK PROPERLY
	 **/
	 
	/**
	 * old version of get_val();
	 * added for compatibility with old AddOns
	 **/
	public static function getVal($arr, $key, $default = ''){
		//echo 'Slider Revolution Notice: Please do not use RevSliderFunctions::getVal() anymore, use $f->get_val()'."\n";
		$f = new RevSliderFunctions();
		return $f->get_val($arr, $key, $default);
	}
	
	/**
	 * old version of class_to_array_single();
	 * added for compatibility with old AddOns
	 **/
	public static function cleanStdClassToArray($arr){
		$f = new RevSliderFunctions();
		return $f->class_to_array_single($arr);
	}
	
	/**
	 * END: DEPRECATED FUNCTIONS THAT ARE IN HERE FOR OLD ADDONS TO WORK PROPERLY
	 **/
	
	
	/**
	 * Get Global Settings
	 * @before: RevSliderOperations::getGeneralSettingsValues()
	 **/
	public function get_global_settings(){
		$gs = get_option('revslider-global-settings', '');
		if(!is_array($gs)){
			$gs = json_decode($gs, true);
		}
		
		return apply_filters('rs_get_global_settings', $gs);
	}
	
	/**
	 * update general settings
	 * @before: RevSliderOperations::updateGeneralSettings()
	 */
	public function set_global_settings($global){
		$global = json_encode($global);
		
		return update_option('revslider-global-settings', $global);
	}
	
	
	/**
	 * throw an error
	 * @before: RevSliderFunctions::throwError()
	 **/
	public function throw_error($message, $code = null){
		if(!empty($code)){
			throw new Exception($message, $code);
		}else{
			throw new Exception($message);
		}
	}
	
	
	/**
	 * get value from array. if not - return alternative
	 * before: RevSliderFunctions::get_val();
	 */
	public function get_val($arr, $key, $default = ''){
		$arr = (array)$arr;	
		
		if(is_array($key)){
			$a = $arr;
			foreach($key as $k => $v){
				$a = $this->get_val($a, $v, $default);				
			}
			return $a;
			/*$val = $default;
			foreach($key as $k => $v){
				$val = (array)$val;
				$val = (isset($val[$v])) ? $val[$v] : $default;
			}*/
		}else{						
			$val = (isset($arr[$key])) ? $arr[$key] : $default;			 		
		}
		return $val;
	}

	
	/**
	 * set parameter
	 * @since: 6.0
	 */
	public function set_val(&$base, $name, $value){
		if(is_array($name)){
			foreach($name as $key){
				if(is_array($base)){
					if(!isset($base[$key])) $base[$key] = array();
					$base = &$base[$key];
				}elseif(is_object($base)){
					if(!isset($base->$key)) $base->$key = new stdClass();
					$base = &$base->$key;
				}
			}
			$base = $value;
		}else{
			$base[$name] = $value;
		}
		//no return required, as the base is given with &$base
		//return $base;
	}
	
	
	/**
	 * get POST variable
	 * before: RevSliderBase::getPostVar();
	 */
	public function get_post_var($key, $default = ''){
		$val = $this->get_var($_POST, $key, $default);
		
		return $val;			
	}
	
	
	/**
	 * get GET variable
	 * before: RevSliderBase::getGetVar();
	 */
	public function get_get_var($key, $default = ''){
		$val = $this->get_var($_GET, $key, $default);
		
		return $val;
	}
	
	
	/**
	 * get POST or GET variable in this order
	 * before: RevSliderBase::getPostGetVar();
	 */
	public function get_request_var($key, $default = ''){
		$val = (array_key_exists($key, $_POST)) ? $this->get_var($_POST, $key, $default) : $this->get_var($_GET, $key, $default);
		
		return $val;
	}
	
	
	/**
	 * get a variable from an array,
	 * before: RevSliderBase::getVar()
	 */
	public function get_var($arr, $key, $default = ''){
		$val = (isset($arr[$key])) ? $arr[$key] : $default;
		
		return $val;
	}
	
	
	/**
	 * check for true and false in all possible ways
	 * @since: 6.0
	 **/
	public function _truefalse($v){
		if($v === 'false' || $v === false || $v === 'off' || $v ===	NULL || $v === 0 || $v === -1){
			$v = false;
		}elseif($v === 'true' || $v === true || $v === 'on'){
			$v = true;
		}
		
		return $v;
	}
	
	
	/**
	 * validate that some value is numeric
	 * before: RevSliderFunctions::validateNumeric
	 */
	public function validate_numeric($val, $fn = 'Field'){
		$this->validate_not_empty($val, $fn);
		
		if(!is_numeric($val))
			$this->throw_error($fn.__(' should be numeric', 'revslider'));
	}
	
	
	/**
	 * validate that some variable not empty
	 * before: RevSliderFunctions::validateNotEmpty
	 */
	public function validate_not_empty($val, $fn = 'Field'){
		if(empty($val) && is_numeric($val) == false)
			$this->throw_error($fn.__(' should not be empty', 'revslider'));
	}
	
	
	
	/**
	 * encode array into json for client side
	 * @before: RevSliderFunctions::jsonEncodeForClientSide()
	 */
	public function json_encode_client_side($arr){
		$json = '';
		
		if(!empty($arr)){
			$json = json_encode($arr);
			$json = addslashes($json);
		}

		$json = (empty($json)) ? '{}' : "'".$json."'";
		
		return $json;
	}
	
	
	/**
	 * turn a string into an array, check also for slashes!
	 * @since: 6.0
	 */
	public function json_decode_slashes($data){
		if(gettype($data) == 'string'){
			$data_decoded = json_decode(stripslashes($data), true);
			if(empty($data_decoded))
				$data_decoded = json_decode($data, true);
			
			$data = $data_decoded;
		}
		
		return $data;
	}
	
	
	/**
	 * Convert std class to array, with all sons
	 * before: RevSliderFunctions::convertStdClassToArray();
	 */
	public function class_to_array($arr){
		$arr = (array)$arr;
		$new = array();
		
		if(!empty($arr)){
			foreach($arr as $key => $item){
				$new[$key]	= (array)$item;
			}
		}else{
			$new = $arr;
		}
		
		return $new;
	}
	
	
	/**
	 * Convert std class to array, single
	 * before: RevSliderFunctions::cleanStdClassToArray();
	 */
	public function class_to_array_single($arr){
		$arr = (array)$arr;
		$new = array();
		
		foreach($arr as $key => $item){
			$new[$key] = $item;
		}
		
		return $new;
	}
	
	/**
	 * Check Array for Value Recursive
	 */
	public function in_array_r($needle, $haystack, $strict = false){
		if(is_array($haystack) && !empty($haystack)){
			foreach($haystack as $item){
				if(($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))){
					return true;
				}
			}
		}
	
		return false;
	}
	
	/**
	 * get attachment image url
	 * before: RevSliderFunctionsWP::getUrlAttachmentImage();
	 */
	public function get_url_attachment_image($id, $size = 'full'){
		$image	= wp_get_attachment_image_src($id, $size);
		$url	= (empty($image)) ? false : $this->get_val($image, 0);
		if($url === false){
			$url = wp_get_attachment_url($id);
		}
		
		return $url;
	}
	
	
	/**
	 * retrieve the image id from the given image url
	 * before: RevSliderFunctionsWP::get_image_id_by_url();
	 */
	public function get_image_id_by_url($image_url){
		global $wpdb;
		
		$attachment_id = false;
		
		if($image_url !== ''){
			$attachment_id = (function_exists('attachment_url_to_postid')) ? attachment_url_to_postid($image_url) : 0;
			//for WP < 4.0.0
			if(0 == $attachment_id){ //get it the old school way
				$upload_dir_paths = wp_upload_dir();
				
				// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
				if(false !== strpos($image_url, $upload_dir_paths['baseurl'])){
					$image_url = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $image_url);
					$image_url = str_replace($upload_dir_paths['baseurl'] . '/', '', $image_url);
					$attachment_id = $wpdb->get_var($wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $image_url));
				}else{
					$attachment_id = false;
				}
			}
		}
		
		return (is_null($attachment_id)) ? false : $attachment_id;
	}
	
	
	/**
	 * get image url from image path.
	 * @before: RevSliderFunctionsWP::getImageUrlFromPath();
	 */
	public function get_image_url_from_path($path){
		if(empty($path)) return '';
		
		//check if the path ends with /, if yes its not a correct image path
		$lc = substr($path, -1);
		if(in_array($lc, array('/', '\\'))) return '';
		
		//protect from absolute url
		$lower = strtolower($path);
		$return = (strpos($lower, 'http://') !== false || strpos($lower, 'https://') !== false || strpos($lower, 'www.') === 0) ? $path : $this->get_base_url().$path;
		
		return ($return !== $this->get_base_url()) ? $return : '';
	}
	
	/**
	 * Check if Path is a Valid Image File	 	
	 **/	 
	public function check_valid_image($url){		
		$pos = strrpos($url, '.', -1);
	    if($pos === false) return false;
	    $ext = strtolower(substr($url, $pos));
	    $img_exts = array('.gif', '.jpg', '.jpeg', '.png');
	    if(in_array($ext, $img_exts)) return $url;
		
	    return false;
	}
	
	/**
	 * get the upload URL of images
	 * before: RevSliderFunctionsWP::getUrlUploads()
	 */
	public static function get_base_url(){
		if(is_multisite() == false){ //without multisite
			$url = content_url().'/';
		}else{	//for multisite
			$upload_dir	= wp_upload_dir();
			$url = $upload_dir['baseurl'].'/';
		}
		
		return $url;
	}
	
	
	/**
	 * strip slashes recursive
	 * @since: 5.0
	 * before: RevSliderBase::stripslashes_deep()
	 */
	public static function stripslashes_deep($value){
		$value = is_array($value) ? array_map(array('RevSliderFunctions', 'stripslashes_deep'), $value) : stripslashes($value);
		
		return $value;
	}
	
	/**
	 * esc attr recursive
	 * @since: 6.0
	 */
	public static function esc_attr_deep($value){
		$value = is_array($value) ? array_map(array('RevSliderFunctions', 'esc_attr_deep'), $value) : esc_attr($value);
		
		return $value;
	}
	
	
	/**
	 * get post types with categories for client side.
	 * before: RevSliderOperations::getPostTypesWithCatsForClient();
	 */
	public function get_post_types_with_categories_for_client(){
		$post_types		= $this->get_post_types_with_categories();
		$globalCounter	= 0;
		$arrOutput		= array();
		foreach($post_types as $postType => $arrTaxWithCats){

			$arrCats = array();
			foreach($arrTaxWithCats as $tax){
				$taxName	= $tax['name'];
				$taxTitle	= $tax['title'];
				$globalCounter++;
				$arrCats['option_disabled_'.$globalCounter] = '---- '.$taxTitle.' ----';
				foreach($tax['cats'] as $catID=>$catTitle){
					$arrCats[$taxName.'_'.$catID] = $catTitle;
				}
			}//loop tax

			$arrOutput[$postType] = $arrCats;

		}//loop types
		
		return $arrOutput;
	}
	
	
	/**
	 * get post types array with taxomonies
	 * before: RevSliderFunctionsWP::getPostTypesWithTaxomonies()
	 */
	public function get_post_types_with_taxonomies(){
		$post_types = $this->get_post_type_assoc();
		
		foreach($post_types as $post_type => $title){
			$post_types[$post_type]	= $this->get_post_type_taxonomies($post_type);
		}
		
		return $post_types;
	}
	
	
	/**
	 * 
	 * get array of post types with categories (the taxonomies is between).
	 * get only those taxomonies that have some categories in it.
	 * before: RevSliderFunctionsWP::getPostTypesWithCats()
	 */
	public function get_post_types_with_categories(){
		$post_types_categories	= array();
		$post_types				= $this->get_post_types_with_taxonomies();
		
		foreach($post_types as $name => $tax){
			$ptwc = array();
			if(!empty($tax)){
				foreach($tax as $tax_name => $tax_title){
					$cats = $this->get_categories_assoc($tax_name);
					if(!empty($cats)){
						$ptwc[] = array(
							'name'	=> $tax_name,
							'title'	=> $tax_title,
							'cats'	=> $cats
						);
					}
				}
			}
			$post_types_categories[$name] = $ptwc;
		}
		
		return $post_types_categories;
	}
	
	
	/**
	 * get all the post types including custom ones
	 * the put to top items will be always in top (they must be in the list)
	 * before: RevSliderFunctionsWP::getPostTypesAssoc()
	 */
	public function get_post_type_assoc($put_to_top = array()){
		$build_in		= array('post' => 'post', 'page'=>'page');
		$custom_types	= get_post_types(array('_builtin' => false));
		
		//top items validation - add only items that in the customtypes list
		$top_updated	= array();
		foreach($put_to_top as $top){
			if(in_array($top, $custom_types) == true){
				$top_updated[$top] = $top;
				unset($custom_types[$top]);
			}
		}
		
		$post_types = array_merge($top_updated, $build_in, $custom_types);
		
		//update label
		foreach($post_types as $key => $type){
			$post_types[$key] = $this->get_post_type_title($type);
		}
		
		return $post_types;
	}
	
	
	/**
	 * return post type title from the post type
	 * before: RevSliderFunctionsWP::getPostTypeTitle()
	 */
	public static function get_post_type_title($post_type){
		$obj_type	= get_post_type_object($post_type);
		$title		= (empty($obj_type)) ? ($post_type) : $obj_type->labels->singular_name;
		
		return $title;
	}
	
	
	/**
	 * get post type taxomonies
	 * before: RevSliderFunctionsWP::getPostTypeTaxomonies()
	 */
	public function get_post_type_taxonomies($post_type){
		$names	= array();
		$tax	= get_object_taxonomies(array('post_type' => $post_type), 'objects');

		if(!empty($tax)){
			foreach($tax as $obj_tax){
				if($post_type === 'product' && !in_array($obj_tax->name, array('product_cat', 'product_tag'))) continue;
				$names[$obj_tax->name] = $obj_tax->labels->name;
			}
		}
		
		return $names;
	}
	
	
	/**
	 * get post categories list assoc - id / title
	 * before: RevSliderFunctionsWP::getCategoriesAssoc()
	 */
	public function get_categories_assoc($taxonomy = 'category'){
		$categories	= array();
		if(strpos($taxonomy, ',') !== false){
			$taxes		= explode(',', $taxonomy);
			foreach($taxes as $tax){
				$cats		= $this->get_categories_assoc($tax);
				$categories	= array_merge($categories, $cats);
			}
		}else{
			$args = array('taxonomy' => $taxonomy);
			$cats = get_categories($args);
			foreach($cats as $cat){
				$num				= $cat->count;
				$id					= $cat->cat_ID;
				$name				= ($num == 1) ? 'item' : 'items';
				$title				= $cat->name . ' ('.$num.' '.$name.')';
				$categories[$id]	= $title;
			}
		}
		
		return $categories;
	}
	
	
	/**
	 * check if css string is rgb
	 * @before: RevSliderFunctions::isrgb()
	 **/
	public function is_rgb($rgba){
		return (strpos($rgba, 'rgb') !== false) ? true : false;
	}
	
	
	/**
	 * change rgba to hex
	 * @since: 5.0
	 */
	public function rgba2hex($rgba){
		if(strtolower($rgba) == 'transparent') return $rgba;
		
		$temp = explode(',', $rgba);
		$rgb = array();
		if(count($temp) == 4) unset($temp[3]);
		foreach($temp as $val){
			$t = dechex(preg_replace('/[^\d.]/', '', $val));
			if(strlen($t) < 2) $t = '0'.$t;
			$rgb[] = $t;
		}
		
		return '#'.implode('', $rgb);
	}
	
	
	/**
	 * check if file is in zip
	 * @since: 5.0
	 */
	public function check_file_in_zip($d_path, $image, $alias, &$alreadyImported, $add_path = false){
		global $wp_filesystem;
		
		$image = (is_array($image)) ? $this->get_val($image, 'url') : $image;
		if(trim($image) !== ''){
			if(strpos($image, 'http') !== false){
				//dont change, as it is an external image
			}else{
				$strip	= false;
				$zimage	= $wp_filesystem->exists($d_path.'images/'.$image);
				if(!$zimage){
					$zimage	= $wp_filesystem->exists(str_replace('//', '/', $d_path.'images/'.$image));
					$strip	= true;
				}
				
				if(!$zimage){
				}else{
					if(!isset($alreadyImported['images/'.$image])){
						//check if we are object folder, if yes, do not import into media library but add it to the object folder
						$uimg = ($strip == true) ? str_replace('//', '/', 'images/'.$image) : $image; //pclzip
						
						$object_library = (strpos($uimg, 'revslider/objects/') === 0) ? true : false;
						
						if($object_library === true){ //copy the image to the objects folder if false
							$objlib = new RevSliderObjectLibrary();
							$importImage = $objlib->_import_object($d_path.'images/'.$uimg);
						}else{
							$importImage = $this->import_media($d_path.'images/'.$uimg, $alias.'/');
						}
						
						if($importImage !== false){
							$alreadyImported['images/'.$image] = $importImage['path'];
							
							$image = $importImage['path'];
						}
					}else{
						$image = $alreadyImported['images/'.$image];
					}
				}
				if($add_path){
					$upload_dir	= wp_upload_dir();
					$cont_url	= $upload_dir['baseurl'];
					if(strpos($image, $cont_url) === false){
						$image = str_replace('uploads/uploads/', 'uploads/', $cont_url . '/' . $image);
					}
				}
			}
		}
		
		return $image;
	}
	
	
	/**
	 * Import media from url
	 * @param string $file_url URL of the existing file from the original site
	 * @param int $folder_name The slidername will be used as folder name in import
	 * @return boolean True on success, false on failure
	 */
	public function import_media($file_url, $folder_name){
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		
		$ul_dir	 = wp_upload_dir();
		$art_dir = 'revslider/';
		$return	 = false;
		
		//if the directory doesn't exist, create it	
		if(!file_exists($ul_dir['basedir'].'/'.$art_dir)) mkdir($ul_dir['basedir'].'/'.$art_dir);
		if(!file_exists($ul_dir['basedir'].'/'.$art_dir.$folder_name)) mkdir($ul_dir['basedir'].'/'.$art_dir.$folder_name);
		
		//rename the file... alternatively, you could explode on "/" and keep the original file name
		$filename = basename($file_url);
		
		$s_dir = str_replace('//', '/', $art_dir.$folder_name.$filename);
		
		if(@fclose(@fopen($file_url, 'r'))){ //make sure the file actually exists
			$save_dir	= $ul_dir['basedir'].'/'.$s_dir;
			$atc_id		= $this->get_image_id_by_url($s_dir);
			
			/**
			 * check if the files have matching md5, if not change the filename
			 * change save_dir so that the file is not
			 **/
			if($atc_id !== false && $atc_id !== NULL){
				if(!is_file($ul_dir['basedir'].'/'.$s_dir) || md5_file($file_url) !== md5_file($ul_dir['basedir'].'/'.$s_dir)){
					$file = explode('.', $filename);
					$nr = 1;
					while(1 === 1){
						$s_dir_2 = $art_dir.$folder_name.$file[0].$nr.'.'.$file[1];
						$save_dir = $ul_dir['basedir'].'/'.$s_dir_2;
						if(is_file($save_dir)){
							if(md5_file($file_url) === md5_file($save_dir)){
								$atc_id = $this->get_image_id_by_url($s_dir_2);
								break;
							}
						}else{
							break;
						}
						
						$nr++;
					}
					
					$atc_id = $this->get_image_id_by_url($s_dir_2);
					$filename = $file[0].$nr.'.'.$file[1];
				}
			}
			//we might have a new $filename here, so do again
			$s_dir = str_replace('//', '/', $art_dir.$folder_name.$filename);
			
			if($atc_id == false || $atc_id == NULL){
				copy($file_url, $save_dir);
				
				$file_info = getimagesize($save_dir);
				
				$artdata = array( //create an array of attachment data to insert into wp_posts table
					'post_author'	 => 1, 
					'post_date'		 => current_time('mysql'),
					'post_date_gmt'	 => current_time('mysql'),
					'post_title'	 => $filename, 
					'post_status'	 => 'inherit',
					'comment_status' => 'closed',
					'ping_status'	 => 'closed',
					'post_name'		 => sanitize_title_with_dashes(str_replace('_', '-', $filename)),
					'post_modified'	 => current_time('mysql'),
					'post_modified_gmt' => current_time('mysql'),
					'post_parent'	 => '',
					'post_type'		 => 'attachment',
					'guid'			 => $ul_dir['baseurl'].'/'.$s_dir,
					'post_mime_type' => $file_info['mime'],
					'post_excerpt'	 => '',
					'post_content'	 => ''
				);
				//insert the database record
				$attach_id = wp_insert_attachment($artdata, $s_dir);
				
				//generate metadata and thumbnails
				add_filter('intermediate_image_sizes_advanced', array('RevSliderFunctions', 'temporary_remove_sizes'), 10, 2);
				
				$rs_meta_create = get_option('rs_image_meta_todo', array());
				if(!isset($rs_meta_create[$attach_id])){
					$rs_meta_create[$attach_id] = $save_dir;
					update_option('rs_image_meta_todo', $rs_meta_create);
				}
				if($attach_data = wp_generate_attachment_metadata($attach_id, $save_dir)){
					wp_update_attachment_metadata($attach_id, $attach_data);
				}
			}else{
				$attach_id = $atc_id;
			}
			
			$art_dir = (!is_multisite()) ? 'uploads/'.$art_dir : $art_dir;
			$s_dir = str_replace('//', '/', $art_dir.$folder_name.$filename);
			
			$return	 = array('id' => $attach_id, 'path' => $s_dir);
		}
		
		return $return;
	}
	
	
	/**
	 * temporary remove image sizes so that only the needed thumb will be created
	 * @since: 6.0
	 **/
	public static function temporary_remove_sizes($sizes, $meta = false){
		foreach($sizes as $size => $values){
			if($size == 'thumbnail'){
				return array($size => $values);
			}
		}
		return $sizes;
	}
	
	
	/**
	 * get contents of the css table
	 * @before: RevSliderOperations::getCaptionsContentArray();
	 */
	public function get_captions_content($handle = false){
		$css = new RevSliderCssParser();
		$this->fill_css();
		
		return $css->db_array_to_array($this->css, $handle);
	}
	
	
	/**
	 * get wp-content path
	 * @before: RevSliderFunctionsWP::getPathUploads()
	 */
	public function get_upload_path(){
		if(is_multisite()){
			global $wpdb;
			$path = (!defined('BLOGUPLOADDIR')) ? ABSPATH . 'wp-content/uploads/sites/' . $wpdb->blogid : BLOGUPLOADDIR;
		}else{
			$wp_dir = WP_CONTENT_DIR;
			$path = (!empty($wp_dir)) ? WP_CONTENT_DIR . '/' : ABSPATH . 'wp-content/uploads/';
		}
		
		return $path;
	}
	
	
	/**
	 * get contents of the static css file
	 * @before: RevSliderOperations::getStaticCss()
	 */
	public function get_static_css(){
		if(!get_option('revslider-static-css')){
			if(file_exists(RS_PLUGIN_PATH . 'public/assets/css/static-captions.css')){
				$css = @file_get_contents(RS_PLUGIN_PATH . 'public/assets/css/static-captions.css');
				$this->update_static_css($css);
			}
		}
		
		return get_option('revslider-static-css', '');
	}
	
	
	/**
	 * get contents of the static css file
	 * @before: RevSliderOperations::updateStaticCss()
	 */
	public function update_static_css($content){
		$content = str_replace(array("\'", '\"', '\\\\'),array("'", '"', '\\'), trim($content));
		
		update_option('revslider-static-css', $content, 'off');

		return $content;
	}
	
	
	/**
	 * print html font import
	 * @before: RevSliderOperations::printCleanFontImport()
	 */
	public function print_clean_font_import(){
		global $revslider_fonts;
		
		$font_first	= true;
		$ret	= '';
		$tcf	= '';
		$tcf2	= '';
		$fonts	= array();
		
		$gs = $this->get_global_settings();
		$fdl = $this->get_val($gs, 'fontdownload', 'off');
		
		if($fdl === 'disable') return $ret;
		
		if(!empty($revslider_fonts['queue'])){
			foreach($revslider_fonts['queue'] as $f_n => $f_s){
				if($f_n !== ''){
					$_variants = $this->get_val($f_s, 'variants', array());
					$_subsets = $this->get_val($f_s, 'subsets', array());
					if(!empty($_variants) || !empty($_subsets)){
						if(!isset($revslider_fonts['loaded'][$f_n])) $revslider_fonts['loaded'][$f_n] = array();
						if(!isset($revslider_fonts['loaded'][$f_n]['variants'])) $revslider_fonts['loaded'][$f_n]['variants'] = array();
						if(!isset($revslider_fonts['loaded'][$f_n]['subsets'])) $revslider_fonts['loaded'][$f_n]['subsets'] = array();
						
						if(strpos($f_n, 'href=') === false){
							$t_tcf = '';
							
							if($font_first == false) $t_tcf .= '%7C'; //'|';
							$t_tcf .= urlencode($f_n).':';
							
							if(!empty($_variants)){
								$mgfirst = true;
								foreach($f_s['variants'] as $mgvk => $mgvv){
									if(in_array($mgvv, $revslider_fonts['loaded'][$f_n]['variants'], true)) continue;
									
									$revslider_fonts['loaded'][$f_n]['variants'][] = $mgvv;
									
									if(!$mgfirst) $t_tcf .= urlencode(',');
									$t_tcf .= urlencode($mgvv);
									$mgfirst = false;
								}
								
								//we did not add any variants, so dont add the font
								if($mgfirst === true) continue;
							}
							
							$fonts[$f_n] = $t_tcf; //we do not want to add the subsets
							
							if(!empty($_subsets)){
								$mgfirst = true;
								foreach($f_s['subsets'] as $ssk => $ssv){
									if(in_array($mgvv, $revslider_fonts['loaded'][$f_n]['subsets'], true)) continue;
									
									$revslider_fonts['loaded'][$f_n]['subsets'][] = $ssv;
									
									if($mgfirst) $t_tcf .= urlencode('&subset=');
									if(!$mgfirst) $t_tcf .= urlencode(',');
									$t_tcf .= urlencode($ssv);
									$mgfirst = false;
								}
							}
							
							$tcf .= $t_tcf;
						}else{
							//$f_n = $this->$this->remove_http($f_n);
							$tcf2 .= html_entity_decode(stripslashes($f_n));
							
							$fonts[$f_n] = $tcf2;
						}
					}
					$font_first = false;
				}
			}
		}
		
		if($fdl === 'preload'){
			if(!empty($fonts)){
				$upload_dir	= wp_upload_dir();
				$base_dir	= $upload_dir['basedir'];
				$base_url	= $upload_dir['baseurl'];
				$rs_google_ts = get_option('rs_google_font', 0);
				
				foreach($fonts as $key => $font){
					//check if we downloaded the font already
					$font = str_replace('%7C', '', $font);
					$font_name = preg_replace('/[^-a-z0-9 ]+/i', '', $key);
					$font_name = strtolower(str_replace(' ', '-', esc_attr($font_name)));
					
					if(!is_file($base_dir.'/revslider/gfonts/'. $font_name . '/' . $font_name . '.woff2') || filemtime($base_dir.'/revslider/gfonts/'. $font_name . '/' . $font_name . '.woff2') < $rs_google_ts){
						if(!is_dir($base_dir.'/revslider/gfonts/')){
							mkdir($base_dir.'/revslider/gfonts/');
						}
						
						if(!is_dir($base_dir.'/revslider/gfonts/'.$font_name)){
							mkdir($base_dir.'/revslider/gfonts/'.$font_name);
						}
						
						$regex_url	= "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
						$url		= 'https://fonts.googleapis.com/css?family='.$font;
						$content	= wp_remote_get($url);
						$body = $this->get_val($content, 'body', '');
						
						if(preg_match_all($regex_url, $body, $found_fonts)){
							foreach($found_fonts as $found_font){
								$found_font = $found_font[0];
								$found_font = rtrim($found_font, ')');
								
								$f_c = wp_remote_get($found_font);
								
								$f_c_body = $this->get_val($f_c, 'body', '');
								
								$file = $base_dir.'/revslider/gfonts/'. $font_name . '/' . $font_name . '.woff2';
								@mkdir(dirname($file));
								@file_put_contents($file, $f_c_body);
								
								break;
							}
						}
					}
					
					$f_raw		= explode(':', $font);
					$weights	= (!empty($f_raw) && is_array($f_raw) && isset($f_raw[1])) ? explode(',', $f_raw[1]) : array('400');
					$f_family	= str_replace('+', ' ', $f_raw[0]);
					
					if(!empty($weights) && is_array($weights)){
						$ret .= '<style type="text/css">';
						foreach($weights as $weight){
							$ret .=
"@font-face {
  font-family: '".$f_family."';
  font-style: normal;
  font-weight: ".$weight.";
  src: local('".$f_family."'), local('".$f_family."'), url(".$base_url.'/revslider/gfonts/'. $font_name . '/' . $font_name . '.woff2'.") format('woff2');
}";
						}
						$ret .= '</style>';
					}
				}
			}
			
		}else{
			$url = $this->modify_fonts_url('https://fonts.googleapis.com/css?family=');
			
			if($tcf !== ''){
				$ret .= '<link href="'.$url.$tcf.'" rel="stylesheet" property="stylesheet" type="text/css" media="all">'."\n"; //id="rev-google-font"
			}
			if($tcf2 !== ''){
				$ret .= html_entity_decode(stripslashes($tcf2));
			}
		}
		
		return apply_filters('revslider_printCleanFontImport', $ret);
	}
	
	
	/**
	 * Change FontURL to new URL (added for chinese support since google is blocked there)
	 * @since: 5.0
	 * @before: RevSliderFront::modify_punch_url()
	 */
	public function modify_fonts_url($url){
		$gs = $this->get_global_settings();
		$df = $this->get_val($gs, 'fonturl', '');
		
		return ($df !== '') ? $df : $url;
	}
	
	
	/**
	 * get recent posts
	 * @since: 5.1.1
	 * before: RevSliderSlider::getPostsFromRecent()
	 */
	public function get_latest_posts($max_posts = false){
		$post_id	= get_the_ID();
		$my_posts	= array();
		$args		= array(
			'post_type' => 'any',
			'suppress_filters' => 0,
			'meta_key'	=> '_thumbnail_id',
			'orderby'	=> 'date',
			'order'		=> 'DESC'
		);
		
		if($max_posts == false){
			$source		= $this->get_val($this->params, 'source');
			$post		= $this->get_val($source, 'post');
			$max_posts	= $this->get_val($post, 'maxPosts', 30);
			$max_posts	= (empty($max_posts) || !is_numeric($max_posts)) ? -1 : $max_posts;
		}else{
			$max_posts = intval($max_posts);
		}
		
		$args['posts_per_page']	= $max_posts;
		$args	= apply_filters('revslider_get_latest_posts', $args, $post_id);
		$posts	= get_posts($args);
		
		if(!empty($posts)){
			foreach($posts as $post){
				$my_posts[] = (method_exists($post, 'to_array')) ? $post->to_array() : (array)$post;
			}
		}
		
		return $my_posts;
	}
	
	
	/**
	 * get popular posts
	 * @since: 5.1.1
	 * @before: RevSliderSlider::getPostsFromPopular();
	 */
	public function get_popular_posts($max_posts = false){
		$post_id	= get_the_ID();
		$my_posts	= array();
		
		if($max_posts == false){
			$source		= $this->get_param('source');
			$post		= $this->get_val($source, 'post');
			$max_posts	= $this->get_val($post, 'maxPosts', 30);
			$max_posts = (empty($max_posts) || !is_numeric($max_posts)) ? -1 : $max_posts;
		}else{
			$max_posts = intval($max_posts);
		}
		
		$args = array(
			'suppress_filters' => 0,
			'posts_per_page' => $max_posts,
			'post_type'	=> 'any',
			'meta_key'  => '_thumbnail_id',
			'orderby'   => 'comment_count',
			'order'     => 'DESC'
		);
		
		$args	= apply_filters('revslider_get_popular_posts', $args, $post_id);
		$posts	= get_posts($args);
		
		foreach($posts as $post){
			$my_posts[] = (method_exists($post, 'to_array')) ? $post->to_array() : (array)$post;
		}
		
		return $my_posts;
	}
	
	
	/**
	 * convert date to the date format that the user chose.
	 * @before: RevSliderFunctionsWP::convertPostDate();
	 */
	public function convert_post_date($date, $with_time = false){
		if(!empty($date)){
			$date = ($with_time) ? date_i18n(get_option('date_format').' '.get_option('time_format'), strtotime($date)) : date_i18n(get_option('date_format'), strtotime($date));
		}
		return $date;
	}
	
	
	/**
	 * return biggest value of object depending on which devices are enabled
	 * @since: 5.0
	 **/
	public function get_biggest_device_setting($obj, $enabled_devices, $default = '########'){
		
		if($this->get_val($enabled_devices, 'd') === true && $this->get_val($obj, array('d', 'v')) != '') return $this->get_val($obj, array('d', 'v'));
		if($default !== '########') return $default;
		if($this->get_val($enabled_devices, 'n') === true && $this->get_val($obj, array('n', 'v')) != '') return $this->get_val($obj, array('n', 'v'));
		if($this->get_val($enabled_devices, 't') === true && $this->get_val($obj, array('t', 'v')) != '') return $this->get_val($obj, array('t', 'v'));
		if($this->get_val($enabled_devices, 'm') === true && $this->get_val($obj, array('m', 'v')) != '') return $this->get_val($obj, array('m', 'v'));
		
		return '';
	}
	
	
	/**
	 * normalize object with device informations depending on what is enabled for the Slider
	 * @since: 5.0
	 **/
	public function normalize_device_settings($obj, $enabled_devices, $return = 'obj', $default = array(), $set_to_if = array(), $use = ','){ //array -> from -> to
		/*d n t m*/
		$obj = $this->fill_device_settings($obj);
		
		if(!empty($set_to_if)){
			foreach($obj as $device => $key){
				foreach($set_to_if as $from => $to){
					if(trim($this->get_val($obj, array($device, 'v'))) == $from) $obj[$device]['v'] = $to;
				}
			}
		}
		
		$_def = '########';
		if(!empty($default)){
			foreach($default as $_d){
				$_def = $_d;
				break;
			}
		}
		
		$inherit_size = $this->get_biggest_device_setting($obj, $enabled_devices, $_def);
		if($enabled_devices['d'] === true){			
			if($this->get_val($obj, array('d', 'v'), '') === ''){
				$obj['d']['v'] = ($_def !== '########') ? $_def : $inherit_size;
			}else{
				$inherit_size = $obj['d']['v'];
			}
		}else{
			$obj['d']['v'] = $inherit_size;
		}
		
		if($enabled_devices['n'] === true){
			if($this->get_val($obj, array('n', 'v'), '') === ''){
				$obj['n']['v'] = ($_def !== '########') ? $_def : $inherit_size;
			}else{
				$inherit_size = $obj['n']['v'];
			}
		}else{
			$obj['n']['v'] = $inherit_size;
		}
		
		if($enabled_devices['t'] === true){
			if($this->get_val($obj, array('t', 'v'), '') === ''){
				$obj['t']['v'] = ($_def !== '########') ? $_def : $inherit_size;
			}else{
				$inherit_size = $obj['t']['v'];
			}
		}else{
			$obj['t']['v'] = $inherit_size;
		}
		
		if($enabled_devices['m'] === true){
			if($this->get_val($obj, array('m', 'v'), '') === ''){
				$obj['m']['v'] = ($_def !== '########') ? $_def : $inherit_size;
			}else{
				$inherit_size = $obj['m']['v'];
			}
		}else{
			$obj['m']['v'] = $inherit_size;
		}
		
		switch($return){
			case 'obj':
				//order according to: desktop, notebook, tablet, mobile
				$new_obj = array();
				$new_obj['d'] = $obj['d']['v'];
				$new_obj['n'] = $obj['n']['v'];
				$new_obj['t'] = $obj['t']['v'];
				$new_obj['m'] = $obj['m']['v'];
				
				return $new_obj;
			break;
			case 'html-array':
				$html_array = '';
				if($obj['d']['v'] === $obj['n']['v'] && $obj['d']['v'] === $obj['m']['v'] && $obj['d']['v'] === $obj['t']['v']){
					$html_array = $obj['d']['v'];
				}else{
					$html_array = @$obj['d']['v'];
					$html_array .= $use.@$obj['n']['v'];
					$html_array .= $use.@$obj['t']['v'];
					$html_array .= $use.@$obj['m']['v'];
				}
				
				if(!empty($default)){

					//KRIKI CHANGE
					foreach($default as $key => $value){
						if((is_string($html_array) && $html_array == "".$value) || (!(is_string($html_array)) && $html_array == $value)){
							$html_array = '';	
							break;
						}
					}
					/* ALTE ZEUG if(in_array($html_array, $default)){
						$html_array = '';
					}*/
				}
				return $html_array;
			break;
			case 'array':
				$array = array();
				if($obj['d']['v'] === $obj['n']['v'] && $obj['d']['v'] === $obj['m']['v'] && $obj['d']['v'] === $obj['t']['v']){
					$array[$obj['d']['v']] = $obj['d']['v'];
				}else{
					$array[$obj['d']['v']] = $this->get_val($obj, array('d', 'v'));
					$array[$obj['n']['v']] = $this->get_val($obj, array('n', 'v'));
					$array[$obj['t']['v']] = $this->get_val($obj, array('t', 'v'));
					$array[$obj['m']['v']] = $this->get_val($obj, array('m', 'v'));
					if(!empty($array)){
						foreach($array as $k => $v){
							if(trim($v) === ''){
								unset($array[$k]);
							}
						}
					}
				}
				
				return $array;
			break;
		}
		
		return $obj;
	}
	
	
	/**
	 * fill object with default values
	 * @since: 6.0
	 **/
	public function fill_device_settings($obj){
		$push = array('d', 'n', 't', 'm');
		
		if(is_string($obj)){
			$t = $obj;
			$obj = array();
			foreach($push as $p){
				$obj[$p] = array('v' => $t);
			}
		}
		
		foreach($push as $p){
			if(!isset($obj[$p])){
				$obj[$p] = array();
			}
			if(!isset($obj[$p]['v'])){
				$obj[$p]['v'] = '';
				$obj[$p]['u'] = '';
			}
		}
		
		return $obj;
	}
	
	
	/**
	 * change hex to rgba
	 */
    public function hex2rgba($hex, $transparency = false, $raw = false, $do_rgb = false){
        if($transparency !== false){
			$transparency = ($transparency > 0) ? number_format(($transparency / 100), 2, '.', '') : 0;
        }else{
            $transparency = 1;
        }

        $hex = str_replace('#', '', $hex);
		
        if(strlen($hex) == 3){
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        }elseif($this->is_rgb($hex)){
			return $hex;
		}else{
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
		
		$ret = ($do_rgb) ? $r.', '.$g.', '.$b : $r.', '.$g.', '.$b.', '.$transparency;
		
		return ($raw) ? $ret : 'rgba('.$ret.')';
    }
	
	
	/**
	 * returns an object of current system values
	 **/
	public function get_system_requirements(){
		$dir	= wp_upload_dir();
		$basedir = $this->get_val($dir, 'basedir').'/';
		$ml		= ini_get('memory_limit');
		$mlb	= wp_convert_hr_to_bytes($ml);
		$umf	= ini_get('upload_max_filesize');
		$umfb	= wp_convert_hr_to_bytes($umf);
		$pms	= ini_get('post_max_size');
		$pmsb	= wp_convert_hr_to_bytes($pms);
		
		
		$mlg  = ($mlb >= 268435456) ? true : false;
		$umfg = ($umfb >= 33554432) ? true : false;
		$pmsg = ($pmsb >= 33554432) ? true : false;
		
		return array(
			'memory_limit' => array(
				'has' => size_format($mlb),
				'min' => size_format(268435456),
				'good'=> $mlg
			),
			'upload_max_filesize' => array(
				'has' => size_format($umfb),
				'min' => size_format(33554432),
				'good'=> $umfg
			),
			'post_max_size' => array(
				'has' => size_format($pmsb),
				'min' => size_format(33554432),
				'good'=> $pmsg
			),
			'upload_folder_writable'	=> wp_is_writable($basedir),
			'object_library_writable'	=> wp_image_editor_supports(array('methods' => array('resize', 'save'))),
			'server_connect'			=> get_option('revslider-connection', false),
		);
	}
	
	/**
	 * set the rs_google_font to current date, so that it will be redownloaded
	 * @before: RevSliderOperations::deleteGoogleFonts();
	 */
	public function delete_google_fonts(){
		update_option('rs_google_font', time());
	}
	
	
	/**
	 * Remove http:// and https://
	 * @since: 6.0.0
	 **/
	public function remove_http($url){
		return str_replace(array('http://', 'https://'), '//' , $url);
	}
	
	
	/**
	 * go through folders and return all files, $only checking for certain file types
	 **/
	/*public function get_all_files($dir, &$results = array(), $only = false){
		$files = scandir($dir);
		
		foreach($files as $key => $value){
			$add	= true;
			$path	= realpath($dir.DIRECTORY_SEPARATOR.$value);
			if($only !== false){
				$path_parts = pathinfo($path);
				if($this->get_val($path_parts, 'extension') != $only){
					$add = false;
				}
			}
			
			if(!is_dir($path)){
				if($add){
					$results[] = $path;
				}
			}elseif($value != '.' && $value != '..'){
				$this->get_all_files($path, $results, $only);
				if($add){
					$results[] = $path;
				}
			}
		}

		return $results;
	}*/

}

//class RevSliderFunctions extends rs_functions {}
//logConsole( $tpmissiotheme_nav_background_main, "Background" );
?>