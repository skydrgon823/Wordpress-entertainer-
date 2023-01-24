<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('BBoss_Global_Search_Posts_Comments')):

	/**
	 *
	 * BuddyPress Global Search  - search posts
	 * **************************************
	 *
	 *
	 */
	class BBoss_Global_Search_Posts_Comments extends BBoss_Global_Search_Type {
		private $type = 'posts_comments';
		
		/**
		 * Insures that only one instance of Class exists in memory at any
		 * one time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 *
		 * @see buddyboss_global_search()
		 *
		 * @return object BBoss_Global_Search_Posts
		 */
		public static function instance() {
			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been run previously
			if (null === $instance) {
				$instance = new BBoss_Global_Search_Posts_Comments();

				//add_action( 'bboss_global_search_settings_item_posts', array( $instance, 'print_search_options' ) );
			}

			// Always return the instance
			return $instance;
		}

		/**
		 * A dummy constructor to prevent this class from being loaded more than once.
		 *
		 * @since 1.0.0
		 */
		private function __construct() { /* Do nothing here */
		}
		
		public function sql( $search_term, $only_totalrow_count=false ){

			global $wpdb;
			$query_placeholder = array(); 
			
			$sql = " SELECT ";
			
			if( $only_totalrow_count ){
				$sql .= " COUNT( DISTINCT comment_ID ) ";
			} else {
				$sql .= " DISTINCT comment_ID AS id, 'posts_comments' as type, comment_content LIKE %s AS relevance, comment_date as entry_date  ";
				$query_placeholder[] = '%'. $search_term .'%';
			}


			$sql .= " FROM {$wpdb->comments} WHERE 1=1 AND comment_content LIKE %s AND comment_approved = 1 ";

			$query_placeholder[] = '%'.$search_term .'%';

			$sql = $wpdb->prepare( $sql, $query_placeholder );

            return apply_filters( 
                'BBoss_Global_Search_Posts_Comments_sql',
                $sql, 
                array( 
                    'search_term'           => $search_term,
                    'only_totalrow_count'   => $only_totalrow_count,
                ) 
            );
		}
		
		protected function generate_html( $template_type='' ) {
			$comment_ids = array();
			foreach( $this->search_results['items'] as $item_id=>$item_html ){
				$comment_ids[] = $item_id;
			}

			//now we have all the posts
			//lets do a wp_query and generate html for all posts
			$comment_query = new WP_Comment_Query( array( 'comment__in'=> $comment_ids ) );
			$_comments = $comment_query->comments;

			foreach ( $_comments as $_comment ) {
				global $current_comment;
				$current_comment = $_comment;

				$result = array(
					'id'	=> $_comment->comment_ID,
					'type'	=> $this->type,
					'title'	=> $this->search_term,
					'html'	=> buddyboss_global_search_buffer_template_part( 'loop/comment', $template_type, false ),
				);

				$this->search_results['items'][$_comment->comment_ID] = $result;
			}
		}
	}

// End class BBoss_Global_Search_Posts

endif;
?>