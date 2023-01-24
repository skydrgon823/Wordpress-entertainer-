<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ES_Reports_Data' ) ) {
	/**
	 * Get Reports Data
	 * Class ES_Reports_Data
	 *
	 * @since 4.3.2
	 */
	class ES_Reports_Data {

		/**
		 * Get total Contacts
		 *
		 * @since 4.3.2
		 */
		public static function get_total_contacts() {
			return ES()->contacts_db->get_total_contacts();
		}

		/**
		 * Get total subscribed contacts in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 * @since 4.3.2
		 * @since 4.3.5 Modified ES_DB_Lists_Contacts::get_total_subscribed_contacts to
		 * ES()->lists_contacts_db->get_total_subscribed_contacts
		 * @since 4.3.6 Modified function name from get_subscribed_contacts_count to get_subscribed_contacts_count
		 */
		public static function get_total_subscribed_contacts( $days = 0 ) {
			return ES()->lists_contacts_db->get_subscribed_contacts_count( $days );
		}

		/**
		 * Get total unsubscribed contacts in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 * @since 4.3.2
		 * @since 4.3.5 Modified ES_DB_Lists_Contacts::get_total_unsubscribed_contacts to
		 * ES()->lists_contacts_db->get_total_unsubscribed_contacts
		 * @since 4.3.6 Modified function name from get_total_unsubscribed_contacts to get_unsubscribed_contacts_count
		 */
		public static function get_total_unsubscribed_contacts( $days = 0 ) {
			return ES()->lists_contacts_db->get_unsubscribed_contacts_count( $days );
		}

		/**
		 * Get total unconfiremed contacts in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 * @since 4.5.7
		 */
		public static function get_total_unconfirmed_contacts( $days = 0 ) {
			return ES()->lists_contacts_db->get_unconfirmed_contacts_count( $days );
		}

		/**
		 * Get total contacts have opened emails in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 * @since 4.3.2
		 *
		 * @modify 4.4.0 Now, we are calculating stats from actions table
		 */
		public static function get_total_contacts_opened_emails( $days = 60, $distinct = true ) {
			return ES()->actions_db->get_total_contacts_opened_message( $days, $distinct );
		}

		/**
		 * Get total contacts have clicked on links in emails in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 * @since 4.3.2
		 *
		 * @modify 4.4.0
		 */
		public static function get_total_contacts_clicks_links( $days = 60, $distinct = true ) {
			return ES()->actions_db->get_total_contacts_clicks_links( $days, $distinct );
		}

		/**
		 * Get total emails sent in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 * @since 4.4.0
		 */
		public static function get_total_emails_sent( $days = 60, $distinct = true ) {
			return ES()->actions_db->get_total_emails_sent( $days, $distinct );
		}

		/**
		 * Get total contacts lost in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 */
		public static function get_total_contact_lost( $days = 60, $distinct = true ) {
			return ES()->actions_db->get_total_contact_lost( $days, $distinct );
		}

		/**
		 * Get contacts growth
		 *
		 * @param int $days
		 *
		 * @return array
		 *
		 * @since 4.4.0
		 */
		public static function get_contacts_growth( $days = 60 ) {

			$contacts = ES()->contacts_db->get_total_contacts_by_date();

			$total = ES()->contacts_db->get_total_subscribed_contacts_before_days( $days );

			$data = array();
			for ( $i = $days; $i >= 0; $i -- ) {
				$date = gmdate( 'Y-m-d', strtotime( '-' . $i . ' days' ) );

				$count = isset( $contacts[ $date ] ) ? $contacts[ $date ] : 0;

				$total += $count;

				$data[ $date ] = $total;
			}

			return $data;
		}


		/**
		 * Get contacts growth percentage
		 *
		 * @param int $days
		 *
		 * @return float|integer
		 *
		 * @since 4.8.0
		 */
		public static function get_contacts_growth_percentage( $days = 60 ) {

			$this_week_contacts       = ES()->contacts_db->get_total_subscribed_contacts_by_date( $days );
			$last_week_total_contacts = (int) ES()->contacts_db->get_total_subscribed_contacts_between_days( $days );
			$this_week_total_contacts = 0;
			if ( count( $this_week_contacts ) > 0 ) {
				foreach ( $this_week_contacts as $date => $contact_count ) {
					$this_week_total_contacts += $contact_count;
				}
			}
			if ( $last_week_total_contacts <= 0 && $this_week_total_contacts <= 0 ) {
				return 0;
			} elseif ( $last_week_total_contacts <= 0 && $this_week_total_contacts > 0 ) {
				return 100;
			} else {
				return round( ( $this_week_total_contacts - $last_week_total_contacts ) / $last_week_total_contacts * 100, 2 );
			}
		}

		/**
		 * Collect dashboard reports data
		 *
		 * @return array
		 *
		 * @since 4.4.0
		 */
		public static function get_dashboard_reports_data( $source, $refresh = false, $days = 60, $campaign_count = 5 ) {

			/**
			 * - Get Total Contacts
			 * - Get Total Forms
			 * - Get Total Lists
			 * - Get Total Campaigns
			 * - Get Last 3 months contacts data
			 * - Total Email Opened in last 60 days
			 * - Total Message Sent in last 60 days
			 * - Avg. Email Click rate
			 */
			$cache_key = 'dashboard_reports_data';

			if ( ! $refresh ) {

				$cached_data = ES_Cache::get_transient( $cache_key );

				if ( ! empty( $cached_data ) ) {
					return $cached_data;
				}
			}

			$total_contacts            = self::get_total_contacts();
			$total_forms               = ES()->forms_db->count_forms();
			$total_lists               = ES()->lists_db->count_lists();
			$total_campaigns           = ES()->campaigns_db->get_total_campaigns();
			$total_contacts_subscribed = self::get_total_subscribed_contacts( $days );

			$total_email_opens  = self::get_total_contacts_opened_emails( $days, false );
			$total_links_clicks = self::get_total_contacts_clicks_links( $days, false );
			$total_message_sent = self::get_total_emails_sent( $days, false );
			$total_contact_lost = self::get_total_unsubscribed_contacts( $days );
			$contacts_growth    = self::get_contacts_growth();

			$total_open_rate  = 0;
			$total_click_rate = 0;
			$total_lost_rate  = 0;
			if ( $total_message_sent > 0 ) {
				$total_open_rate  = ( $total_email_opens ) / $total_message_sent;
				$total_click_rate = ( $total_links_clicks ) / $total_message_sent;
				$total_lost_rate  = ( $total_contact_lost ) / $total_message_sent;
			}

			$avg_open_rate  = 0;
			$avg_click_rate = 0;
			if ( $total_message_sent > 0 ) {
				$avg_open_rate  = ( $total_email_opens * 100 ) / $total_message_sent;
				$avg_click_rate = ( $total_links_clicks * 100 ) / $total_message_sent;
			}

			/**
			 * - Get recent 10 campaigns
			 *      - Get total open (3)
			 *      - Get total clicks (4)
			 *      - Get total unsubscribe (5)
			 */

			$data = array();

			$can_show = self::can_show_campaign_stats( $source );
			if ( $can_show ) {

				$data = self::get_campaign_stats( $campaign_count );
			}

			$reports_data = array(
				'total_contacts'            => number_format( $total_contacts ),
				'total_lists'               => number_format( $total_lists ),
				'total_forms'               => number_format( $total_forms ),
				'total_campaigns'           => number_format( $total_campaigns ),
				'total_contacts_subscribed' => number_format( $total_contacts_subscribed ),
				'total_email_opens'         => number_format( $total_email_opens ),
				'total_message_sent'        => number_format( $total_message_sent ),
				'total_contact_lost'        => number_format( $total_contact_lost ),
				'avg_open_rate'             => number_format( $avg_open_rate, 2 ),
				'avg_click_rate'            => number_format( $avg_click_rate, 2 ),
				'total_open_rate'           => number_format( $total_open_rate, 2 ),
				'total_click_rate'          => $total_click_rate,
				'total_lost_rate'           => $total_lost_rate,
				'contacts_growth'           => $contacts_growth,
			);

			$data = array_merge( $data, $reports_data );

			ES_Cache::set_transient( $cache_key, $data, 1 * HOUR_IN_SECONDS );

			return $data;
		}

		/**
		 * Get Campaigns Stats
		 *
		 * @return array
		 *
		 * @since 4.7.8
		 */
		public static function get_campaign_stats( $total_campaigns = 5 ) {

			global $wpdb;

			$campaigns = ES_DB_Mailing_Queue::get_recent_campaigns( $total_campaigns );

			$campaigns_data = array();
			if ( ! empty( $campaigns ) && count( $campaigns ) > 0 ) {

				foreach ( $campaigns as $key => $campaign ) {

					$message_id  = $campaign['id'];
					$campaign_id = $campaign['campaign_id'];

					if ( 0 == $campaign_id ) {
						continue;
					}

					$results = $wpdb->get_results( $wpdb->prepare( "SELECT type, count(DISTINCT (contact_id) ) as total FROM {$wpdb->prefix}ig_actions WHERE message_id = %d AND campaign_id = %d GROUP BY type", $message_id, $campaign_id ), ARRAY_A );

					$stats     = array();
					$type      = '';
					$type_text = '';

					if ( count( $results ) > 0 ) {

						foreach ( $results as $result ) {

							$type  = $result['type'];
							$total = $result['total'];

							switch ( $type ) {
								case IG_MESSAGE_SENT:
									$type_text = 'total_sent';
									break;
								case IG_MESSAGE_OPEN:
									$type_text = 'total_opens';
									break;
								case IG_LINK_CLICK:
									$type_text = 'total_clicks';
									break;
								case IG_CONTACT_UNSUBSCRIBE:
									$type_text = 'total_unsubscribe';
									break;
							}

							$stats[ $type_text ] = $total;
						}
					}

					$stats = wp_parse_args(
						$stats,
						array(
							'total_sent'        => 0,
							'total_opens'       => 0,
							'total_clicks'      => 0,
							'total_unsubscribe' => 0,
						)
					);

					if ( 0 != $stats['total_sent'] ) {
						$campaign_opens_rate  = ( $stats['total_opens'] * 100 ) / $stats['total_sent'];
						$campaign_clicks_rate = ( $stats['total_clicks'] * 100 ) / $stats['total_sent'];
						$campaign_losts_rate  = ( $stats['total_unsubscribe'] * 100 ) / $stats['total_sent'];
					} else {
						$campaign_opens_rate  = 0;
						$campaign_clicks_rate = 0;
						$campaign_losts_rate  = 0;
					}

					$campaign_type = ES()->campaigns_db->get_column( 'type', $campaign_id );

					if ( 'newsletter' === $campaign_type ) {
						$type = __( 'Broadcast', 'email-subscribers' );
					} elseif ( 'post_notification' === $campaign_type ) {
						$type = __( 'Post Notification', 'email-subscribers' );
					} elseif ( 'post_digest' === $campaign_type ) {
						$type = __( 'Post Digest', 'email-subscribers' );
					}

					$start_at  = gmdate( 'd F', strtotime( $campaign['start_at'] ) );
					$finish_at = gmdate( 'd F', strtotime( $campaign['finish_at'] ) );

					$campaigns_data[ $key ]                         = $stats;
					$campaigns_data[ $key ]['title']                = $campaign['subject'];
					$campaigns_data[ $key ]['hash']                 = $campaign['hash'];
					$campaigns_data[ $key ]['status']               = $campaign['status'];
					$campaigns_data[ $key ]['campaign_type']        = $campaign_type;
					$campaigns_data[ $key ]['type']                 = $type;
					$campaigns_data[ $key ]['campaign_opens_rate']  = round( $campaign_opens_rate );
					$campaigns_data[ $key ]['campaign_clicks_rate'] = round( $campaign_clicks_rate );
					$campaigns_data[ $key ]['campaign_losts_rate']  = round( $campaign_losts_rate );
					$campaigns_data[ $key ]['start_at']             = $start_at;
					$campaigns_data[ $key ]['finish_at']            = $finish_at;
				}
			}
			$data['campaigns'] = $campaigns_data;

			return $data;
		}

		public static function can_show_campaign_stats( $source = '' ) {
			if ( 'es_dashboard' === $source && ! ES()->is_pro() ) {
				return false;
			}

			return true;
		}
	}
}
