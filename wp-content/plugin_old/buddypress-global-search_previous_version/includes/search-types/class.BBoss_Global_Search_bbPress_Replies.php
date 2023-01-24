<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('BBoss_Global_Search_bbPress_Replies')):

	/**
	 *
	 * BuddyPress Global Search  - search bbpress forums replies
	 * **************************************
	 *
	 *
	 */
	class BBoss_Global_Search_bbPress_Replies extends BBoss_Global_Search_bbPress {
		public $type = 'reply';
		
		/**
		 * Insures that only one instance of Class exists in memory at any
		 * one time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 * 
		 * @return object BBoss_Global_Search_Forums
		 */
		public static function instance() {
			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been run previously
			if (null === $instance) {
				$instance = new BBoss_Global_Search_bbPress_Replies();
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
	}

// End class BBoss_Global_Search_Posts

endif;
?>