<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('BBoss_Global_Search_AWPCP')):

	/**
	 *
	 * BuddyPress Global Search  - search AWPCP listings
	 * **************************************
	 *
	 *
	 */
	class BBoss_Global_Search_AWPCP extends BBoss_Global_Search_Type {
		private $type = 'awpcp_ad_listing';
		
		/**
		 * Insures that only one instance of Class exists in memory at any
		 * one time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.9
		 *
		 * @return object BBoss_Global_Search_AWPCP
		 */
		public static function instance() {
			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been run previously
			if (null === $instance) {
				$instance = new BBoss_Global_Search_AWPCP();
			}

			// Always return the instance
			return $instance;
		}
		
		/**
		 * A dummy constructor to prevent this class from being loaded more than once.
		 *
		 * @since 1.0.9
		 */
		private function __construct() { /* Do nothing here */
		}
		
		public function sql( $search_term, $only_totalrow_count=false ){
			global $wpdb, $bp;
			$query_placeholder = array(); 
			
			$sql = " SELECT ";
			
			if( $only_totalrow_count ){
				$sql .= " COUNT( DISTINCT awpcp_table_ads.ad_id ) ";
			} else {
				$sql .= " DISTINCT awpcp_table_ads.ad_id as id, 'awpcp_ad_listing' as type, awpcp_table_ads.ad_title LIKE '%%%s%%' AS relevance, awpcp_table_ads.ad_postdate as entry_date ";
				$query_placeholder[] = $search_term;
			}
						
			$sql .= " FROM " . AWPCP_TABLE_ADS . " awpcp_table_ads 
						
					WHERE 
						1=1 
						AND (
									(awpcp_table_ads.ad_title LIKE '%%%s%%') 
								OR 	(awpcp_table_ads.ad_details LIKE '%%%s%%')
							)
						AND awpcp_table_ads.disabled=0 
				";
			
			$query_placeholder[] = $search_term;
			$query_placeholder[] = $search_term;
			$sql = $wpdb->prepare( $sql, $query_placeholder );
            
            return apply_filters( 
                'BBoss_Global_Search_AWPCP_sql', 
                $sql, 
                array( 
                    'search_term'           => $search_term,
                    'only_totalrow_count'   => $only_totalrow_count,
                ) 
            );
		}
		
		protected function generate_html( $template_type='' ){
			$post_ids = array();
			foreach( $this->search_results['items'] as $item_id=>$item_html ){
				$post_ids[] = $item_id;
			}

			$conditions = array();
			$conditions[] = "ad_id IN ( " . implode( ',', $post_ids ) . " ) ";
			
			$args = array();
			$ads = AWPCP_Ad::get_enabled_ads( $args, $conditions );
			
			$parity = array( 'displayaditemseven', 'displayaditemsodd' );
			
			if( 'ajax'== $template_type ){
				$layout = buddyboss_global_search_buffer_template_part( 'loop/' . $this->type, $template_type, false );
			} else {
				$layout = get_awpcp_option('displayadlayoutcode');

				if ( empty( $layout) ) {
					$layout = awpcp()->settings->get_option_default_value( 'displayadlayoutcode' );
				}
			}
	
			if( !empty( $ads ) ){
				foreach( $ads as  $i => $listing ){
					$rendered_listing = awpcp_do_placeholders( $listing, $layout, $context );
					$rendered_listing = str_replace( "\$awpcpdisplayaditems", $parity[$i % 2], $rendered_listing );

					if ( function_exists( 'awpcp_featured_ads' ) ) {
						$rendered_listing = awpcp_featured_ad_class( $listing->ad_id, $rendered_listing );
					}
					
					$rendered_listing = apply_filters( 'awpcp-render-listing-item', $rendered_listing, $i + 1, $template_type );
					
					$result = array(
						'id'	=> $listing->ad_id,
						'type'	=> $this->type,
						'title'	=> $listing->ad_title,
						'html'	=> $rendered_listing,
					);

					$this->search_results['items'][$listing->ad_id] = $result;
				}
			}
		}
	}

// End class BBoss_Global_Search_AWPCP

endif;
?>