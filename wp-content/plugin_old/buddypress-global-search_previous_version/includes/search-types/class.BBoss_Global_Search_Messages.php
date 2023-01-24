<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('BBoss_Global_Search_Messages')):

	/**
	 *
	 * BuddyPress Global Search  - search messages
	 * **************************************
	 *
	 *
	 */
	class BBoss_Global_Search_Messages extends BBoss_Global_Search_Type {
		private $type = 'messages';
		
		/**
		 * Insures that only one instance of Class exists in memory at any
		 * one time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 *
		 * @return object BBoss_Global_Search_Messages
		 */
		public static function instance() {
			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been run previously
			if (null === $instance) {
				$instance = new BBoss_Global_Search_Messages();
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
		
		function sql( $search_term, $only_totalrow_count=false ){
			/* FOR INBOX SEARCH ----------
			SELECT 
				m.thread_id, MAX(m.date_sent) AS date_sent 
			FROM 
				wp_bp_messages_recipients r, wp_bp_messages_messages m 
			WHERE 
					m.thread_id = r.thread_id 
				AND r.is_deleted = 0 
				AND ( subject LIKE '%leo%' OR message LIKE '%leo%' ) 
				AND r.user_id = 1 
				AND r.sender_only = 0 
			GROUP BY 
				m.thread_id 
			ORDER BY date_sent DESC LIMIT 0, 10
			*/
			/* FOR SENTBOX SEARCH --------
			SELECT 
				m.thread_id, MAX(m.date_sent) AS date_sent 
			FROM 
				wp_bp_messages_recipients r, wp_bp_messages_messages m 
			WHERE 
					m.thread_id = r.thread_id 
				AND r.is_deleted = 0 
				AND ( subject LIKE '%leo%' OR message LIKE '%leo%' ) 
				AND m.sender_id = r.user_id 
				AND m.sender_id = 1 
			GROUP 
				BY m.thread_id 
			ORDER BY 
				date_sent DESC LIMIT 0, 10
			*/
			/* COMBINED ---------------
			SELECT 
				m.thread_id, MAX(m.date_sent) AS date_sent 
			FROM 
				wp_bp_messages_recipients r, wp_bp_messages_messages m 
			WHERE 
					m.thread_id = r.thread_id 
				AND r.is_deleted = 0 
				AND ( subject LIKE '%leo%' OR message LIKE '%leo%' ) 
				AND (
					( r.user_id = 1 AND r.sender_only = 0 )
					OR
					( m.sender_id = 1 AND m.sender_id = r.user_id )
				) 
			GROUP 
				BY m.thread_id 
			ORDER BY 
				date_sent DESC LIMIT 0, 10
			*/
			/* a better version 
			 	
			SELECT DISTINCT m.id , 'messages' as type, m.message LIKE '%Aliquam%' AS relevance,  m.date_sent AS entry_date 
			FROM 
				wp_bp_messages_messages m LEFT JOIN wp_bp_messages_recipients r ON m.thread_id = r.thread_id 
			WHERE 
					r.is_deleted = 0 
				AND ( m.subject LIKE '%Aliquam%' OR m.message LIKE '%Aliquam%' ) 
				AND (
					( r.user_id = 1 AND r.sender_only = 0 ) 
					OR 
					( m.sender_id = 1 AND m.sender_id = r.user_id ) 
				) 
			 */
			global $wpdb, $bp;
			$sql = " SELECT ";
			
			$query_placeholder = array();
			if( $only_totalrow_count ){
				$sql .= " COUNT( DISTINCT m.id ) ";
			} else {
				$sql .= " DISTINCT m.id , 'messages' as type, m.message LIKE %s AS relevance, m.date_sent AS entry_date ";
				$query_placeholder[] = '%'. $search_term .'%';
			}
			
			$sql .= " FROM 
						{$bp->messages->table_name_messages} m LEFT JOIN {$bp->messages->table_name_recipients} r ON m.thread_id = r.thread_id 
					WHERE 
							r.is_deleted = 0 
						AND ( m.subject LIKE %s OR m.message LIKE %s ) 
						AND (
							( r.user_id = %d AND r.sender_only = 0 ) 
							OR 
							( m.sender_id = %d AND m.sender_id = r.user_id ) 
						) 
				";
						
			$query_placeholder[] = '%'. $search_term .'%';
			$query_placeholder[] = '%'. $search_term .'%';
			$query_placeholder[] = get_current_user_id();
			$query_placeholder[] = get_current_user_id();
			
			$sql = $wpdb->prepare( $sql, $query_placeholder );

			//var_dump( $sql );
			
            return apply_filters( 
                'BBoss_Global_Search_Messages_sql', 
                $sql, 
                array( 
                    'search_term'           => $search_term,
                    'only_totalrow_count'   => $only_totalrow_count,
                ) 
            );
		}
		
		protected function generate_html( $template_type='' ){
			$message_ids = array();
			foreach( $this->search_results['items'] as $message_id=>$item_html ){
				$message_ids[] = $message_id;
			}

			//$message_ids = implode( ',', $message_ids );
			global $wpdb, $bp;
			$messages_sql = "SELECT m.* "
					. " FROM {$bp->messages->table_name_messages} m  "
					. " WHERE m.id IN ( " . implode( ',', $message_ids ) . " ) ";
			$messages = $wpdb->get_results( $messages_sql );

			$recepients_sql = "SELECT r.thread_id, GROUP_CONCAT( DISTINCT r.user_id ) AS 'recepient_ids' "
					. " FROM {$bp->messages->table_name_recipients} r JOIN {$bp->messages->table_name_messages} m ON m.thread_id = r.thread_id "
					. " WHERE m.id IN ( " . implode( ',', $message_ids ) . " ) "
					. " GROUP BY r.thread_id ";
			$threads_recepients = $wpdb->get_results( $recepients_sql );

			foreach( $threads_recepients as $thread_recepients ){
				foreach( $messages as $message ){
					if( $message->thread_id==$thread_recepients->thread_id ){
						$message->recepients = explode( ',', $thread_recepients->recepient_ids );
					}
				}
			}

			foreach( $messages as $message ){
				global $current_message;
				$current_message = $message;

				$result = array(
					'id'	=> $message->id,
					'type'	=> $this->type,
					'title'	=> $this->search_term,
					'html'	=> buddyboss_global_search_buffer_template_part( 'loop/message', $template_type, false ),
				);

				$this->search_results['items'][$message->id] = $result;
			}
				
		}
		
	}

// End class BBoss_Global_Search_Posts

endif;
?>