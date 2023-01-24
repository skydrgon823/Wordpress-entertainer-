<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'BBoss_Global_Search_ACF' ) ) {

	/**
	 *
	 * BuddyPress Global Search  - search posts
	 * **************************************
	 *
	 *
	 */
	class BBoss_Global_Search_ACF extends BBoss_Global_Search_Type {

		private $acf_field_group;

		/**
		 * A real constructor. Since we do want multiple copies of this class.
		 * The idea is to have one object for each searchable custom post type.
		 *
		 * @since 1.0.0
		 */
		public function __construct( $acf_field_group ) {
			$this->acf_field_group = $acf_field_group;
		}

		public function sql( $search_term, $only_totalrow_count = false ) {	
			
			$rule = get_post_meta( $this->acf_field_group, 'rule', true );
			
			$param = $rule['param'];
			
			$param_post = strpos($param, 'post');
			$param_page = strpos($param, 'page');
			
			//Return if field is not for user, post, page screen.
			if ( $param != 'ef_user' && $param_post != 0 && $param_page != 0 ) {
				return;
			}

			global $wpdb;
			$query_placeholder1 = array();
			$query_placeholder2 = array();
			
			//Getting ACF meta_key
			$acf_meta_objects = $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key LIKE %s", $this->acf_field_group, 'field_%'), ARRAY_A);
			
			if ( ! empty( $acf_meta_objects ) ) {
				$count_acf_meta_objects = count($acf_meta_objects);
				
				foreach ( $acf_meta_objects as $key => $acf_meta_obj ) {
					$acf_meta_obj = maybe_unserialize( $acf_meta_obj[ 'meta_value' ] );
					$acf_meta_key = $acf_meta_obj[ 'name' ];

					$metasql .= " (		({$wpdb->prefix}postmeta.meta_key = '$acf_meta_key' ) 
									AND ({$wpdb->prefix}postmeta.meta_value LIKE '%%%s%%')
								)";
									
					$usersql .= " (		({$wpdb->prefix}usermeta.meta_key = '$acf_meta_key' ) 
									AND ({$wpdb->prefix}usermeta.meta_value LIKE '%%%s%%')
								)";
									
					if ( ( $count_acf_meta_objects - 1 ) != $key ) { 
						$metasql .= "OR";
						$usersql .= "OR";
					}
				}
				
			}
			
			if ( $param_post === 0 ) {
				//Posts query begins
				$sql = " SELECT ";

				if ( $only_totalrow_count ) {
					$sql .= " COUNT( DISTINCT id ) ";
				} else {
					$sql .= " DISTINCT id, 'posts' as type, post_title LIKE '%%%s%%' AS relevance, post_date as entry_date ";
				}

				$sql .= " FROM 
							{$wpdb->prefix}posts INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID={$wpdb->prefix}postmeta.post_id 
						WHERE 
							1=1 
							AND {$wpdb->prefix}posts.post_type IN ('post','page')
							AND {$wpdb->prefix}posts.post_status = 'publish'
							AND (
									$metasql
								) ";

				$query_placeholder1[] = $search_term;

				for( $i = 1; $i <= $count_acf_meta_objects; $i++ ) {
					$query_placeholder1[] = $search_term;
				}
				//Post query ends
				$sql = $wpdb->prepare( $sql, $query_placeholder1 );
			}
			
			 
			if ( $param == 'ef_user' ) {
				//User query begins
				$user_sql = " SELECT ";

				if ( $only_totalrow_count ) {
					$user_sql .= " COUNT( DISTINCT id ) ";
				} else {
					$user_sql .= " DISTINCT id, 'members' as type, display_name LIKE '%%%s%%' AS relevance, user_registered as entry_date ";
				}

				$user_sql .= " FROM 
							{$wpdb->prefix}users INNER JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id 
						WHERE 
							1=1
							AND (
									$usersql
								) ";

				$query_placeholder2[] = $search_term;

				for( $i = 1; $i <= $count_acf_meta_objects; $i++ ) {
					$query_placeholder2[] = $search_term;
				}
				$sql = $wpdb->prepare( $user_sql, $query_placeholder2 );
			}
		
			return apply_filters( 
                'BBoss_Global_Search_ACF_sql', 
                $sql, 
                array( 
                    'search_term'           => $search_term,
                    'only_totalrow_count'   => $only_totalrow_count,
                ) 
            );
		}

		protected function generate_html( $template_type = '' ) {
			
			$post_ids = array();
			foreach( $this->search_results['items'] as $item_id=>$item_html ){
				$post_ids[] = $item_id;
			}

			//now we have all the posts
			//lets do a wp_query and generate html for all posts
			$query = new WP_Query( array( 'post_type' =>array( 'post', 'page' ), 'post__in'=>$post_ids ) );
			if( $query->have_posts() ){
				while( $query->have_posts() ){
					$query->the_post();	
					$result = array(
						'id'	=> get_the_ID(),
						'type'	=> $this->type,
						'title'	=> get_the_title(),
						'html'	=> buddyboss_global_search_buffer_template_part( 'loop/post', $template_type, false ),
					);

					$this->search_results['items'][get_the_ID()] = $result;
				}
			}
			wp_reset_postdata();
		}

	}

// End class BBoss_Global_Search_ACF
}
?>