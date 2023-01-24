<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ES_Campaign_Admin' ) ) {
	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Admin Settings
	 *
	 * @package    Email_Subscribers
	 * @subpackage Email_Subscribers/admin
	 */
	class ES_Campaign_Admin {
	
		// class instance
		public static $instance;
	
		/**
		 * Campaign ID
		 */
		private $campaign_data = array();
	
		// class constructor
		public function __construct() {
			$this->init();
		}

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
	
			return self::$instance;
		}
	
		public function init() {
			$this->register_hooks();
		}
	
		public function register_hooks() {
	
			add_action( 'admin_init', array( $this, 'process_submission' ) );
	
			// Add tracking fields data
			add_filter( 'ig_es_campaign_data', array( $this, 'add_tracking_fields_data' ) );
	
			// Check campaign wise open tracking is enabled.
			add_filter( 'ig_es_track_open', array( $this, 'is_open_tracking_enabled' ), 10, 4 );

			add_action( 'ig_es_before_' . IG_CAMPAIGN_TYPE_POST_NOTIFICATION . '_content_settings', array( $this, 'show_save_as_template' ) );
			add_action( 'ig_es_before_' . IG_CAMPAIGN_TYPE_POST_DIGEST . '_content_settings', array( $this, 'show_save_as_template' ) );
			add_action( 'ig_es_before_' . IG_CAMPAIGN_TYPE_NEWSLETTER . '_content_settings', array( $this, 'show_save_as_template' ) );

			add_action( 'ig_es_' . IG_CAMPAIGN_TYPE_POST_NOTIFICATION . '_data', array( $this, 'add_post_notification_data' ) );

			add_action( 'ig_es_' . IG_CAMPAIGN_TYPE_POST_DIGEST . '_data', array( $this, 'add_post_notification_data' ) );

			if ( ! ES()->is_pro() ) {
				// Add newsletter scheduler data
				add_filter( 'ig_es_' . IG_CAMPAIGN_TYPE_NEWSLETTER . '_data', array( $this, 'add_broadcast_scheduler_data' ) );
			}

			add_action( 'wp_ajax_ig_es_draft_campaign', array( $this, 'draft_campaign' ) );
			add_action( 'wp_ajax_ig_es_get_campaign_preview', array( $this, 'get_campaign_preview' ) );
			add_action( 'wp_ajax_ig_es_save_as_template', array( $this, 'save_as_template' ) );
		}
	
		public function setup_campaign() {
			$campaign_id = $this->get_campaign_id_from_url();
			if ( ! empty( $campaign_id ) ) {
				$campaign = new ES_Campaign( $campaign_id );
				if ( $campaign->exists ) {
					$this->campaign_data = (array) $campaign;
					if ( empty( $this->campaign_data['meta']['editor_type'] ) ) {
						$this->campaign_data['meta']['editor_type'] = IG_ES_CLASSIC_EDITOR;
					}
				}
			} else {
				$this->campaign_data['type']                = $this->get_campaign_type_from_url();
				$this->campaign_data['meta']['editor_type'] = $this->get_editor_type_from_url();
			}
		}
	
		public function get_campaign_id_from_url() {
			$campaign_id = ig_es_get_request_data( 'list' );
			return $campaign_id;
		}
	
		public function get_campaign_type_from_url() {
			$current_page = ig_es_get_request_data( 'page' );
	
			$campaign_type = '';
			if ( 'es_newsletters' === $current_page ) {
				$campaign_type = IG_CAMPAIGN_TYPE_NEWSLETTER;
			} elseif ( 'es_notifications' === $current_page ) {
				$campaign_type = IG_CAMPAIGN_TYPE_POST_NOTIFICATION;
			}
	
			return $campaign_type;
		}

		public function get_editor_type_from_url() {
			$editor_type = ig_es_get_request_data( 'editor-type' );
			if ( empty( $editor_type ) ) {
				$editor_type = IG_ES_DRAG_AND_DROP_EDITOR;
			}
			return $editor_type;
		}
	
		public static function set_screen( $status, $option, $value ) {
			return $value;
		}
	
		/**
		 * Method to process campaign submission.
		 *
		 * @since 4.4.7
		 */
		public function process_submission() {
	
			$campaign_action = ig_es_get_request_data( 'ig_es_campaign_action' );

			if ( ! empty( $campaign_action ) ) {

				$campaign_nonce = ig_es_get_request_data( 'ig_es_campaign_nonce' );
				
				// Verify nonce.
				if ( wp_verify_nonce( $campaign_nonce, 'ig-es-campaign-nonce' ) ) {
					$campaign_data = ig_es_get_request_data( 'campaign_data', array(), false );
					$list_id       = ! empty( $campaign_data['list_ids'] ) ? $campaign_data['list_ids'] : '';
					$template_id   = ! empty( $campaign_data['template_id'] ) ? $campaign_data['template_id'] : '';
					$subject       = ! empty( $campaign_data['subject'] ) ? $campaign_data['subject'] : '';
	
					// Check if user has added required data for creating campaign.
					if ( ! empty( $campaign_data['subject'] ) && ! empty( $campaign_data['body'] ) && ! empty( $subject ) ) {
						$is_updating_campaign              = ! empty( $campaign_data['id'] ) ? true : false;
						$campaign_data['base_template_id'] = $template_id;
						$campaign_data['list_ids']         = $list_id;
						$meta                              = ! empty( $campaign_data['meta'] ) ? $campaign_data['meta'] : array();
						$meta['scheduling_option']         = ! empty( $campaign_data['scheduling_option'] ) ? $campaign_data['scheduling_option'] : 'schedule_now';
						$meta['es_schedule_date']          = ! empty( $campaign_data['es_schedule_date'] ) ? $campaign_data['es_schedule_date'] : '';
						$meta['es_schedule_time']          = ! empty( $campaign_data['es_schedule_time'] ) ? $campaign_data['es_schedule_time'] : '';
						$meta['pre_header']                = ! empty( $campaign_data['pre_header'] ) ? $campaign_data['pre_header'] : '';
	
						if ( ! empty( $meta['list_conditions'] ) ) {
							$meta['list_conditions'] = IG_ES_Campaign_Rules::remove_empty_conditions( $meta['list_conditions'] );
						}
	
						$campaign_data['meta'] = maybe_serialize( $meta );

						if ( 'schedule' === $campaign_action ) {
							$campaign_data['status'] = IG_ES_CAMPAIGN_STATUS_SCHEDULED;
						} elseif ( 'activate' === $campaign_action ) {
							$campaign_data['status'] = IG_ES_CAMPAIGN_STATUS_ACTIVE;
						}
	
						$campaign_saved = self::save_campaign( $campaign_data );

						$campaign_created = ! $is_updating_campaign && $campaign_saved;
						$campaign_updated = $is_updating_campaign && $campaign_saved;
						
						$campaign_scheduled = false;

						if ( 'schedule' === $campaign_action ) {
							$campaign_scheduled = self::schedule_campaign( $campaign_data );
						}

						$campaign_action = '';
						if ( $campaign_scheduled ) {
							$campaign_action = 'campaign_scheduled';
						} elseif ( $campaign_updated ) {
							$campaign_action = 'campaign_updated';
						} elseif ( $campaign_created ) {
							$campaign_action = 'campaign_created';
						} else {
							$campaign_action = 'error';
						}
	
						$campaign_url = admin_url( 'admin.php?page=es_campaigns&action=' . $campaign_action );
	
						wp_safe_redirect( $campaign_url );
						exit();
					}
				} else {
					$message = __( 'Sorry, you are not allowed to add/edit campaign.', 'email-subscribers' );
					ES_Common::show_message( $message, 'error' );
				}
			}
		}
	
		public function render() {
	
			global $wpdb;
	
			$campaign_id   = ig_es_get_request_data( 'list' );
			$submitted     = ig_es_get_request_data( 'ig_es_campaign_submitted' );
			$campaign_data = ig_es_get_request_data( 'campaign_data', array(), false );
			$message_data  = array();

			$campaign_action = ig_es_get_request_data( 'ig_es_campaign_action' );

			if ( ! empty( $campaign_action ) ) {
	
				if ( empty( $campaign_data['subject'] ) ) {
					$message      = __( 'Please add a campaign subject.', 'email-subscribers' );
					$message_data = array(
						'message' => $message,
						'type'    => 'error',
					);
				}
			}
	
			$this->show_campaign_form( $message_data );
		}
	
		/**
		 * Method to display newsletter setting form
		 *
		 * @param array $campaign_data Posted campaign data
		 *
		 * @since  4.4.2 Added $campaign_data param
		 */
		public function show_campaign_form( $message_data = array() ) {
			
			$from_email = ES_Common::get_ig_option( 'from_email' );

			$campaign_data = $this->campaign_data;
	
			$campaign_id        = ! empty( $campaign_data['id'] ) ? $campaign_data['id'] : 0;
			$campaign_from_name = ! empty( $campaign_data['from_name'] ) ? $campaign_data['from_name'] : get_option( 'ig_es_from_name' );
			$campaign_email     = ! empty( $campaign_data['from_email'] ) ? $campaign_data['from_email'] : $from_email;
			$campaign_reply_to  = ! empty( $campaign_data['reply_to_email'] ) ? $campaign_data['reply_to_email'] : $from_email;
			$campaign_subject   = ! empty( $campaign_data['subject'] ) ? $campaign_data['subject'] : $this->get_campaign_default_subject();
			$campaign_status    = ! empty( $campaign_data['status'] ) ? (int) $campaign_data['status'] : IG_ES_CAMPAIGN_STATUS_IN_ACTIVE;
			$campaign_type      = ! empty( $campaign_data['type'] ) ? $campaign_data['type']               : '';
			$editor_type        = ! empty( $campaign_data['meta']['editor_type'] ) ? $campaign_data['meta']['editor_type']               : '';
			$campaign_text      = '';
			$gallery_page_url   = admin_url( 'admin.php?page=es_gallery' );

			if ( IG_CAMPAIGN_TYPE_POST_NOTIFICATION === $campaign_type ) {
				$campaign_text = __( 'Post notification', 'email-subscribers' );
			} elseif ( IG_CAMPAIGN_TYPE_POST_DIGEST === $campaign_type ) {
				$campaign_text = __( 'Post digest', 'email-subscribers' );
			} elseif ( IG_CAMPAIGN_TYPE_NEWSLETTER === $campaign_type ) {
				$campaign_text = __( 'Broadcast', 'email-subscribers' );
			}
			?>
	
			<div id="edit-campaign-form-container" data-editor-type="<?php echo esc_attr( $editor_type ); ?>" class="<?php echo esc_attr( $editor_type ); ?> font-sans pt-1.5 wrap">
				<?php
				if ( ! empty( $message_data ) ) {
					$message = $message_data['message'];
					$type    = $message_data['type'];
					ES_Common::show_message( $message, $type );
				}
				?>
				<form action="#" method="POST" id="campaign_form">
					<input type="hidden" id="campaign_id" name="campaign_data[id]" value="<?php echo esc_attr( $campaign_id ); ?>"/>
					<input type="hidden" id="campaign_status" name="campaign_data[status]" value="<?php echo esc_attr( $campaign_status ); ?>"/>
					<input type="hidden" id="campaign_type" name="campaign_data[type]" value="<?php echo esc_attr( $campaign_type ); ?>"/>
					<input type="hidden" id="editor_type" name="campaign_data[meta][editor_type]" value="<?php echo esc_attr( $editor_type ); ?>"/>
					<?php wp_nonce_field( 'ig-es-campaign-nonce', 'ig_es_campaign_nonce' ); ?>
					<fieldset class="block es_fieldset">
						<div class="mx-auto wp-heading-inline max-w-7xl">
							<header class="mx-auto max-w-7xl">
								<div class="md:flex md:items-center md:justify-between">
									<div class="flex md:3/5 lg:w-7/12 xl:w-3/5">
										<div class=" min-w-0 md:w-3/5 lg:w-1/2">
										   <nav class="text-gray-400 my-0" aria-label="Breadcrumb">
											<ol class="list-none p-0 inline-flex">
													<li class="flex items-center text-sm tracking-wide">
														<a class="hover:underline" href="admin.php?page=es_campaigns"><?php echo esc_html__( 'Campaigns', 'email-subscribers' ); ?>
														</a>
														<svg class="fill-current w-2.5 h-2.5 mx-2 mt-mx" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path></svg>
													</li>
													<li class="flex items-center text-sm tracking-wide">
														<a href="<?php echo esc_url( $gallery_page_url ); ?>&campaign-type=<?php echo esc_attr( $campaign_type ); ?>&campaign-id=<?php echo esc_attr( $campaign_id ); ?>"><?php echo esc_html__( 'Select template'); ?></a>
														<svg class="fill-current w-2.5 h-2.5 mx-2 mt-mx" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path></svg>
													</li>
											</ol>
										   </nav>
	
											<h2 class="campaign-heading-label -mt-1 text-2xl font-medium text-gray-700 sm:leading-7 sm:truncate" data-post-notification-type-text="<?php echo esc_attr__( 'Post notification', 'email-subscribers' ); ?>" data-post-digest-type-text="<?php echo esc_attr__( 'Post digest', 'email-subscribers' ); ?>">
												<?php echo esc_html( $campaign_text ); ?>
											</h2>
										</div>
										<div class="flex pt-4 md:-mr-8 lg:-mr-16 xl:mr-0 md:ml-8 lg:ml-16 xl:ml-20">
											<ul class="ig-es-tabs overflow-hidden">
												<li id="campaign_content_menu" class="relative float-left px-1 pb-2 text-center list-none cursor-pointer active ">
													<span class="mt-1 text-base font-medium tracking-wide text-gray-400 active"><?php echo esc_html__( 'Content', 'email-subscribers' ); ?></span>
												</li>
												<li id="campaign_summary_menu" class="relative float-left px-1 pb-2 ml-5 text-center list-none cursor-pointer hover:border-2 ">
													<span class="mt-1 text-base font-medium tracking-wide text-gray-400"><?php echo esc_html__( 'Summary', 'email-subscribers' ); ?></span>
												</li>
											</ul>
										</div>
									</div>
									<div class="flex md:mt-0 xl:ml-4">

										<div class="inline-block text-left">
											<button id="view_campaign_preview_button" type="button"
													class="ig-es-inline-loader inline-flex justify-center w-full py-1.5 text-sm font-medium leading-5 text-indigo-600 transition duration-150 ease-in-out border border-indigo-500 rounded-md cursor-pointer select-none hover:text-indigo-500 hover:shadow-md focus:outline-none focus:shadow-outline-indigo focus:shadow-lg hover:border-indigo-600 md:px-2 lg:px-3 xl:px-4">
													<span>
													<?php
														echo esc_html__( 'Preview', 'email-subscribers' );
													?>
													</span>
													<svg class="es-btn-loader animate-spin h-4 w-4 text-indigo"
																	xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
														<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
																stroke-width="4"></circle>
														<path class="opacity-75" fill="currentColor"
																d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
													</svg>
											</button>
										</div>
										<div class="inline-block text-left md:mr-2 md:ml-2">
											<button id="view_campaign_summary_button" type="button"
													class="inline-flex justify-center w-full py-1.5 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-indigo-600 border border-indigo-500 rounded-md cursor-pointer select-none focus:outline-none focus:shadow-outline-indigo focus:shadow-lg hover:bg-indigo-500 hover:text-white  hover:shadow-md md:px-2 lg:px-3 xl:px-4">
													<?php
													echo esc_html__( 'Next', 'email-subscribers' );
													?>
												<svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 20 20" class="w-3 h-3 my-1 ml-2 -mr-1 text-white hover:text-white">
													<path d="M9 5l7 7-7 7"></path>
												</svg>
											</button>
										</div>
	
										<div id="view_campaign_content_button" class="flex hidden mt-4 md:mt-0">
											<button type="button"
													class="inline-flex justify-center w-full py-1.5 text-sm font-medium leading-5 text-indigo-600 transition duration-150 ease-in-out border border-indigo-500 rounded-md cursor-pointer select-none pre_btn md:px-1 lg:px-3 xl:px-4 hover:text-indigo-500 hover:border-indigo-600 hover:shadow-md focus:outline-none focus:shadow-outline-indigo focus:shadow-lg ">
											<svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" viewBox="0 0 20 20" class="w-3 h-3 my-1 mr-1"><path d="M15 19l-7-7 7-7"></path></svg><?php echo esc_html__( 'Previous', 'email-subscribers' ); ?>
											</button>
										</div>
	
										<span id="campaign_summary_actions_buttons_wrapper" class="hidden md:ml-2 xl:ml-2">
											<button type="submit" id="ig_es_save_campaign_btn" name="ig_es_campaign_action" class="inline-flex justify-center w-24 py-1.5 text-sm font-medium leading-5 text-indigo-600 transition duration-150 ease-in-out border border-indigo-500 rounded-md cursor-pointer select-none pre_btn md:px-1 lg:px-3 xl:px-4 hover:text-indigo-500 hover:border-indigo-600 hover:shadow-md focus:outline-none focus:shadow-outline-indigo focus:shadow-lg" value="save">
												<span class="ig_es_campaign_send_option_text">
													<?php echo esc_html__( 'Save', 'email-subscribers' ); ?>
												</span>
											</button>
											<?php
												do_action( 'ig_es_show_' . $campaign_type . '_campaign_summary_action_buttons', $campaign_data );
											?>
										</span>
									</div>
								</div>
							</header>
						</div>
						<div class="mx-auto max-w-7xl">
							<hr class="wp-header-end">
						</div>
						<div class="mx-auto mt-6 es_campaign_first max-w-7xl">
							<div>
								<div class="bg-white rounded-lg shadow-md">
									<div class="md:flex">
										<div class="campaign_main_content py-4 pl-2">
											<div class="block px-4 py-2">
												<label for="ig_es_campaign_subject" class="text-sm font-medium leading-5 text-gray-700"><?php echo esc_html__( 'Subject', 'email-subscribers' ); ?></label>
												<input id="ig_es_campaign_subject" class="block w-full mt-1 text-sm leading-5 border-gray-400 rounded-md shadow-sm form-input" name="campaign_data[subject]" value="<?php echo esc_attr( $campaign_subject ); ?>"/>
											</div>
											
		
											<div class="w-full px-4 pt-1 pb-2 mt-1 message-label-wrapper">
												<label for="message" class="text-sm font-medium leading-5 text-gray-700"><?php echo esc_html__( 'Message', 'email-subscribers' ); ?></label>
												<?php
												if ( IG_ES_CLASSIC_EDITOR === $editor_type ) {
													$editor_id       = 'edit-es-campaign-body';
													$editor_content  = ! empty( $campaign_data['body'] ) ? $campaign_data['body'] : $this->get_campaign_default_content();
													$editor_settings = array(
														'textarea_name' => 'campaign_data[body]',
														'textarea_rows' => 40,
														'media_buttons' => true,
														'tinymce'      => true,
														'quicktags'    => true,
														'editor_class' => 'wp-campaign-body-editor',
													);
													add_filter( 'tiny_mce_before_init', array( 'ES_Common', 'override_tinymce_formatting_options' ), 10, 2 );
													add_filter( 'mce_external_plugins', array( 'ES_Common', 'add_mce_external_plugins' ) );
													wp_editor( $editor_content, $editor_id, $editor_settings );
													$this->show_avaialable_keywords();
												} else {
													?>
													<textarea id="campaign-dnd-editor-data" name="campaign_data[meta][dnd_editor_data]" style="display:none;">
														<?php
															$dnd_editor_data = ! empty( $campaign_data['meta']['dnd_editor_data'] ) ? $campaign_data['meta']['dnd_editor_data'] : $this->get_campaign_default_content();
															echo esc_html( $dnd_editor_data );
														?>
													</textarea>
													<script>
														jQuery(document).ready(function(){
															let editor_data = jQuery('#campaign-dnd-editor-data').val().trim();
															if ( '' !== editor_data ) {
																let is_valid_json = ig_es_is_valid_json( editor_data );
																if ( is_valid_json ) {
																	editor_data = JSON.parse( editor_data );
																}
																window.esVisualEditor.importMjml(editor_data);
															}
														});
													</script>
													<?php
												}
												?>
											</div>
											<?php do_action( 'ig_es_after_campaign_left_pan_settings', $campaign_data ); ?>
										</div>
										<div class="campaign_side_content ml-2 bg-gray-100 rounded-r-lg">
											<?php 
												do_action( 'ig_es_before_' . $campaign_type . '_content_settings', $campaign_data );
											?>
											<?php 
												do_action( 'ig_es_' . $campaign_type . '_content_settings', $campaign_data );
											?>
											<div class="block pt-1 mx-4">
												<div class="hidden" id="campaign-preview-popup">
													<div class="fixed top-0 left-0 z-50 flex items-center justify-center w-full h-full" style="background-color: rgba(0,0,0,.5);">
														<div id="campaign-preview-main-container" class="absolute h-auto pt-2 ml-16 mr-4 text-left bg-white rounded shadow-xl z-80 w-1/2 md:max-w-5xl lg:max-w-7xl md:pt-3 lg:pt-2">
															<div class="py-2 px-4">
																<div class="flex border-b border-gray-200 pb-2">
																	<h3 class="w-full text-2xl text-left">
																		<?php 
																			echo esc_html__( 'Campaign Preview', 'email-subscribers' );
																		?>
																	</h3>
																	<div class="flex">
																		<div class="campaign-preview-options flex">
																			<div id="browser-preview-tab" class="campaign-preview-option cursor-pointer text-sm font-normal text-gray-600 active" title="<?php echo esc_attr__( 'Preview in browser', 'email-subscribers' ); ?>">
																				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
																					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
																				</svg>
																			</div>
																			<?php do_action( 'ig_es_campaign_preview_tab_options', $campaign_data ); ?>
																		</div>
																		<button id="close-campaign-preview-popup" class="text-sm font-medium tracking-wide text-gray-700 select-none no-outline focus:outline-none focus:shadow-outline-red hover:border-red-400 active:shadow-lg">
																			<svg class="h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
																				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
																			</svg>
																		</button>
																	</div>
																	
																</div>
															</div>
															<div id="campaign-browser-preview-container">
																<p class="mx-4 mb-2">
																	<?php echo esc_html__( 'There could be a slight variation on how your customer will view the email content.', 'email-subscribers' ); ?>
																</p>
																<div id="campaign-preview-iframe-container" class="py-4 list-decimal popup-preview">
																</div>
															</div>
															<?php
																do_action( 'ig_es_campaign_preview_options_content', $campaign_data );
															?>
														</div>
													</div>
												</div>
		
												<?php do_action( 'ig_es_after_campaign_content_left_pan_settings', $campaign_data ); ?>
											</div>
										</div>
									</div>
									<?php
									if ( IG_ES_DRAG_AND_DROP_EDITOR === $editor_type ) {
										?>
										<div class="campaign-drag-and-drop-editor-container">
										<?php
										$editor_settings = array(
											'attributes' => array(
												'data-html-textarea-name'  => 'campaign_data[body]',
												'data-is-in-campaign-flow' => 'yes',
											),
										);
										( new ES_Drag_And_Drop_Editor() )->show_editor( $editor_settings );
										?>
										</div>
										<?php
										$this->show_avaialable_keywords();
									}
									?>
								</div>
							</div>
					</fieldset>
	
					<fieldset class="es_fieldset">
	
						<div class="mt-7 hidden mx-auto es_campaign_second max-w-7xl">
							<?php
							$inline_preview_data = $this->get_campaign_inline_preview_data( $campaign_data );
							?>
							<div class="max-w-7xl">
								<div class="bg-white rounded-lg shadow md:flex">
									<div class="py-4 my-4 campaign_main_content pt-3 pl-2">
										<div class="block pb-2 mx-4">
											<span class="text-sm font-medium text-gray-500">
												<?php echo esc_html__( 'Email Content Preview', 'email-subscribers' ); ?>
											</span>
										</div>
	
										<div class="block pb-2 mx-4 mt-4 inline_campaign-popup-preview-container">
											<div class="block">
												<span class="text-2xl font-normal text-gray-600 campaign_preview_subject">
													<?php 
														echo ! empty( $campaign_data['subject'] ) ? esc_html( $campaign_data['subject'] ) : '';
													?>
											</span>
											</div>
											<div class="block mt-3">
												<span class="text-sm font-bold text-gray-800 campaign_preview_contact_name"><?php echo ! empty( $inline_preview_data['contact_name'] ) ? esc_html( $inline_preview_data['contact_name'] ) : ''; ?></span>
												<span class="pl-1 text-sm font-medium text-gray-700 campaign_preview_contact_email"><?php echo ! empty( $inline_preview_data['contact_email'] ) ? esc_html( '&lt;' . $inline_preview_data['contact_email'] . '&gt;' ) : ''; ?></span>
											</div>
											<div class="block mt-3 campaign_preview_content"></div>
										</div>
									</div>
	
									<div class="campaign_side_content ml-2 bg-gray-100 rounded-r-lg">
										<div class="ig-es-campaign-sender block pt-4 pb-2 mx-4 border-b border-gray-200">
											<a id="toggle-sender-details" href="#" class="ig-es-campaign-sender-label pt-3 text-sm font-medium leading-5">
												<?php echo esc_html__( 'Sender details', 'email-subscribers' ); ?>
												<svg xmlns="http://www.w3.org/2000/svg" class="detail-hidden-icons inline-block h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
													<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
												</svg>
											</a>
											<div id="sender-details-container" style="display:none;">
												<div class="flex w-full pb-1">
													<div class="w-4/12 text-sm font-normal text-gray-600">
														<label for="from_name" class="text-sm font-medium leading-10 text-gray-700">
															<?php echo esc_html__( 'From Name', 'email-subscribers' ); ?>
														</label>
													</div>
													<div class="w-8/12">
														<input id="from_name" class="block w-full mt-1 text-sm leading-5 border-gray-400 rounded-md shadow-sm form-input" name="campaign_data[from_name]" value="<?php echo esc_attr( $campaign_from_name ); ?>"/>
													</div>
												</div>
												<div class="flex w-full pb-1">
													<div class="w-4/12 text-sm font-normal text-gray-600">
														<label for="from_email" class="text-sm font-medium leading-10 text-gray-700"><?php echo esc_html__( 'From Email', 'email-subscribers' ); ?></label>
													</div>
													<div class="w-8/12">
														<input id="from_email" class="block w-full mt-1 text-sm leading-5 border-gray-400 rounded-md shadow-sm form-input" name="campaign_data[from_email]" value="<?php echo esc_attr( $campaign_email ); ?>"/>
													</div>
												</div>
												<div class="flex w-full pb-1">
													<div class="w-4/12 text-sm font-normal text-gray-600">
														<label for="reply_to" class="text-sm font-medium leading-10 text-gray-700"><?php echo esc_html__( 'Reply To', 'email-subscribers' ); ?></label>
													</div>
													<div class="w-8/12">
														<input id="reply_to" class="block w-full mt-1 text-sm leading-5 border-gray-400 rounded-md shadow-sm form-input" name="campaign_data[reply_to_email]" value="<?php echo esc_attr( $campaign_reply_to ); ?>"/>
													</div>
												</div>
											</div>
										</div>
										
										<div class="ig-es-campaign-rules block pt-2 pb-4 mx-4 border-b border-gray-200">
											<span id="ig_es_total_contacts">
													<h2 class='text-sm font-normal text-gray-600'>
														<span class=""><?php echo esc_html__( 'Total recipients:', 'email-subscribers' ); ?> </span>
														<span class='text-base font-medium text-gray-700'>
															<span class='ig_es_list_contacts_count'></span>
														</span>
													</h2>
											</span>
											<?php do_action( 'ig_es_show_campaign_rules', $campaign_id, $campaign_data ); ?>
										</div>
										<?php
										do_action( 'ig_es_after_campaign_right_pan_settings', $campaign_data );
										$enable_open_tracking = ! empty( $campaign_data['meta']['enable_open_tracking'] ) ? $campaign_data['meta']['enable_open_tracking'] : get_option( 'ig_es_track_email_opens', 'yes' );
										?>
										<div class="ig-es-campaign-tracking-options pt-2 pb-4 mx-4">
											<div class="flex w-full">
												<div class="w-11/12 text-sm font-normal text-gray-600"><?php echo esc_html__( 'Open tracking', 'email-subscribers' ); ?>
												</div>
												<div>
													<label for="enable_open_tracking" class="inline-flex items-center cursor-pointer ">
													<span class="relative">
														<input id="enable_open_tracking" type="checkbox" class="absolute w-0 h-0 opacity-0 es-check-toggle"
															name="campaign_data[meta][enable_open_tracking]" value="yes"  <?php checked( $enable_open_tracking, 'yes' ); ?>/>
														<span class="block w-8 h-5 bg-gray-300 rounded-full shadow-inner es-mail-toggle-line"></span>
														<span class="absolute inset-y-0 left-0 block w-3 h-3 mt-1 ml-1 transition-all duration-300 ease-in-out bg-white rounded-full shadow es-mail-toggle-dot focus-within:shadow-outline"></span>
													</span>
													</label>
												</div>
											</div>
											<?php do_action( 'ig_es_after_campaign_tracking_options_settings', $campaign_data ); ?>
										</div>
										<?php do_action( 'ig_es_' . $campaign_type . '_scheduling_options_settings', $campaign_data ); ?>
									</div>
	
								</div>
							</div>
						</div>
	
					</fieldset>
				</form>
			</div>
	
			<?php
		}

		/**
		 * Get default subject for campaign
		 * 
		 * @return string $default_subject
		 * 
		 * @since 5.3.3
		 */
		public function get_campaign_default_subject() {
			$campaign_data   = $this->campaign_data;
			$campaign_type   = $campaign_data['type'];
			$default_subject = apply_filters( 'ig_es_' . $campaign_type . '_default_subject', '', $campaign_data );
			return $default_subject;
		}

		/**
		 * Get default content for campaign
		 *
		 * @return string $default_content
		 * 
		 * @since 5.3.3
		 */
		public function get_campaign_default_content() {
			$campaign_data   = $this->campaign_data;
			$campaign_type   = $campaign_data['type'];
			$default_content = apply_filters( 'ig_es_' . $campaign_type . '_default_content', '', $campaign_data );
			return $default_content;
		}

		/**
		 * Show option to save campaign as template
		 *
		 * @return void
		 * 
		 * @since 5.3.3
		 */
		public function show_save_as_template() {
			?>
			<div class="ig-es-campaign-templates-wrapper block mx-4 pb-3 border-b border-gray-200 pt-4 pb-4">
				<button id="save_campaign_as_template_button" name="ig_es_campaign_action" class="block edit-conditions rounded-md border text-indigo-600 border-indigo-500 text-sm leading-5 font-medium transition ease-in-out duration-150 select-none inline-flex justify-center hover:text-indigo-500 hover:border-indigo-600 hover:shadow-md focus:outline-none focus:shadow-outline-indigo focus:shadow-lg mt-1 px-1.5 py-1 mr-1 cursor-pointer" value="save_as_template">
						<?php echo esc_html__( 'Save as template', 'email-subscribers' ); ?>
				</button>
				<img class="es-loader inline-flex align-middle pl-2 h-5 w-7" src="<?php echo esc_url( ES_PLUGIN_URL ); ?>lite/admin/images/spinner-2x.gif" style="display:none;"/>
				<span class="es-saved-success es-icon" style="display:none;"><?php esc_html_e( 'Template saved succesfully.', 'email-subscribers' ); ?></span>
				<br/><span class="es-saved-error es-icon" style="display:none;"><?php esc_html_e( 'Something went wrong. Please try again later', 'email-subscribers' ); ?></span>
			</div>
			<?php
		}
	
		/**
		 * Save campaign data
		 *
		 * @param array $campaign_data
		 * @return boolean $campaign_saved
		 * 
		 * @since 5.3.3
		 */
		public static function save_campaign( $campaign_data ) {
	
			$campaign_saved = false;
			if ( ! empty( $campaign_data['body'] ) ) {
				$campaign_id   = ! empty( $campaign_data['id'] ) ? $campaign_data['id'] : 0;
				$campaign_type = ! empty( $campaign_data['type'] ) ? $campaign_data['type'] : IG_ES_DRAG_AND_DROP_EDITOR;
	

				$campaign_data['name'] = $campaign_data['subject'];
				$campaign_data['slug'] = sanitize_title( sanitize_text_field( $campaign_data['name'] ) );
		
				$campaign_data = apply_filters( 'ig_es_campaign_data', $campaign_data );
				$campaign_data = apply_filters( 'ig_es_' . $campaign_type . '_data', $campaign_data );

				if ( ! empty( $campaign_id ) ) {
					$campaign_saved = ES()->campaigns_db->save_campaign( $campaign_data, $campaign_id );
				}
			}

			return $campaign_saved;
		}

		/**
		 * Schedule a campaign
		 *
		 * @param array $data
		 * @return boolean $campaign_scheduled
		 * 
		 * @since 5.3.3
		 */
		public static function schedule_campaign( $data ) {
			
			$campaign_scheduled = false;
			if ( ! empty( $data['id'] ) ) {
				$campaign_id   = ! empty( $data['id'] ) ? $data['id'] : 0;
				$campaign_meta = ES()->campaigns_db->get_campaign_meta_by_id( $campaign_id );

				$notification = ES_DB_Mailing_Queue::get_notification_by_campaign_id( $campaign_id );
				$data['body'] = ES_Common::es_process_template_body( $data['body'], $data['base_template_id'], $campaign_id );

				$guid = ES_Common::generate_guid( 6 );
				$data = array(
					'hash'        => $guid,
					'campaign_id' => $campaign_id,
					'subject'     => $data['subject'],
					'body'        => $data['body'],
					'status'      => '',
					'start_at'    => ! empty( $campaign_meta['date'] ) ? $campaign_meta['date'] : '',
					'finish_at'   => '',
					'created_at'  => ig_get_current_date_time(),
					'updated_at'  => ig_get_current_date_time(),
					'meta'        => maybe_serialize( array( 'type' => 'newsletter' ) ),
				);

				$should_queue_emails = false;
				$mailing_queue_id    = 0;

				// Add notification to mailing queue if not already added.
				if ( empty( $notification ) ) {
					$data['count']       = 0;
					$mailing_queue_id    = ES_DB_Mailing_Queue::add_notification( $data );
					$mailing_queue_hash  = $guid;
					$should_queue_emails = true;
				} else {
					$mailing_queue_id    = $notification['id'];
					$mailing_queue_hash  = $notification['hash'];
					$notification_status = $notification['status'];
					// Check if notification is not sending or already sent then only update the notification.
					if ( ! in_array( $notification_status, array( 'Sending', 'Sent' ), true ) ) {
						// Don't update this data.
						$data['hash']        = $notification['hash'];
						$data['campaign_id'] = $notification['campaign_id'];
						$data['created_at']  = $notification['created_at'];

						// Check if list has been updated, if yes then we need to delete emails from existing lists and requeue the emails from the updated lists.
						$should_queue_emails = true;
						$data['count']       = 0;

						$notification = ES_DB_Mailing_Queue::update_notification( $mailing_queue_id, $data );
					}
				}

				if ( ! empty( $mailing_queue_id ) ) {
					if ( $should_queue_emails ) {
						$list_ids = '';
						// Delete existing sending queue if any already present.
						ES_DB_Sending_Queue::delete_sending_queue_by_mailing_id( array( $mailing_queue_id ) );
						$is_inserted = ES_DB_Sending_Queue::do_insert_from_contacts_table( $mailing_queue_id, $mailing_queue_hash, $campaign_id, $list_ids );
						if ( $is_inserted ) {
							$campaign_scheduled = true;
						}
					}

					$mailing_queue = ES_DB_Mailing_Queue::get_mailing_queue_by_id( $mailing_queue_id );
					if ( ! empty( $mailing_queue ) ) {

						$queue_start_at    = $mailing_queue['start_at'];
						$current_timestamp = time();
						$sending_timestamp = strtotime( $queue_start_at );

						// Check if campaign sending time has come.
						if ( ! empty( $sending_timestamp ) && $sending_timestamp <= $current_timestamp ) {
							$request_args = array(
								'action'        => 'ig_es_trigger_mailing_queue_sending',
								'campaign_hash' => $mailing_queue_hash,
							);
							// Send an asynchronous request to trigger sending of campaign emails.
							IG_ES_Background_Process_Helper::send_async_ajax_request( $request_args, true );
						}
					}
				}
			}

			return $campaign_scheduled;
		}
	
		public function add_campaign_body_data( $campaign_data ) {
	
			$template_id = ! empty( $campaign_data['template_id'] ) ? $campaign_data['template_id'] : 0;
			$campaign_id = ! empty( $campaign_data['id'] ) ? $campaign_data['id'] : 0;
			if ( ! empty( $campaign_data['body'] ) ) {
				$current_user = wp_get_current_user();
				$username     = $current_user->user_login;
				$useremail    = $current_user->user_email;
				$display_name = $current_user->display_name;
	
				$contact_id = ES()->contacts_db->get_contact_id_by_email( $useremail );
				$first_name = '';
				$last_name  = '';
	
				// Use details from contacts data if present else fetch it from wp profile.
				if ( ! empty( $contact_id ) ) {
					$contact_data = ES()->contacts_db->get_by_id( $contact_id );
					$first_name   = $contact_data['first_name'];
					$last_name    = $contact_data['last_name'];
				} elseif ( ! empty( $display_name ) ) {
					$contact_details = explode( ' ', $display_name );
					$first_name      = $contact_details[0];
					// Check if last name is set.
					if ( ! empty( $contact_details[1] ) ) {
						$last_name = $contact_details[1];
					}
				}
	
				$campaign_body = $campaign_data['body'];
				$campaign_body = ES_Common::es_process_template_body( $campaign_body, $template_id, $campaign_id );
				$campaign_body = ES_Common::replace_keywords_with_fallback( $campaign_body, array(
					'FIRSTNAME' => $first_name,
					'NAME'      => $username,
					'LASTNAME'  => $last_name,
					'EMAIL'     => $useremail
				) );

				$campaign_type = $campaign_data['type'];

				$campaign_data['body'] = $campaign_body;

				if ( IG_CAMPAIGN_TYPE_POST_NOTIFICATION === $campaign_type ) {
					$campaign_data = self::replace_post_notification_merge_tags_with_sample_post( $campaign_data );
				} elseif ( IG_CAMPAIGN_TYPE_POST_DIGEST === $campaign_type ) {
					$campaign_data = self::replace_post_digest_merge_tags_with_sample_posts( $campaign_data );
				}

				$campaign_body = ! empty( $campaign_data['body'] ) ? $campaign_data['body'] : '';

				// If there are blocks in this content, we shouldn't run wpautop() on it.
				$priority = has_filter( 'the_content', 'wpautop' );

				if ( false !== $priority ) {
					// Remove wpautop to avoid p tags.
					remove_filter( 'the_content', 'wpautop', $priority );
				}
	
				$campaign_body = apply_filters( 'the_content', $campaign_body );
	
				$campaign_data['body'] = $campaign_body;

				return $campaign_data;
			}
	
		}
	
		/**
		 * Method to draft a campaign
		 *
		 * @return $response Broadcast response.
		 *
		 * @since 4.4.7
		 */
		public function draft_campaign() {
	
			check_ajax_referer( 'ig-es-admin-ajax-nonce', 'security' );
	
			$response = array();
	
			$campaign_data = ig_es_get_request_data( 'campaign_data', array(), false );
	
			/**
			 * To allow insert of new campaign data,
			 * we are specifically setting $campaign_id to null when id is empty in $campaign_data
			 */
			$campaign_id   = ! empty( $campaign_data['id'] ) ? $campaign_data['id'] : null;
			$campaign_type = ! empty( $campaign_data['type'] ) ? $campaign_data['type'] : IG_ES_DRAG_AND_DROP_EDITOR;
			$is_updating   = ! empty( $campaign_id ) ? true : false;
			$list_id       = ! empty( $campaign_data['list_ids'] ) ? $campaign_data['list_ids'] : '';
			$template_id   = ! empty( $campaign_data['template_id'] ) ? $campaign_data['template_id'] : '';
	
			$campaign_data['base_template_id'] = $template_id;
			$campaign_data['list_ids']         = $list_id;
			$campaign_data['status']           = ! empty( $campaign_data['status'] ) ? (int) $campaign_data['status'] : 0;
			$meta                              = ! empty( $campaign_data['meta'] ) ? $campaign_data['meta'] : array();
			$meta['pre_header']                = ! empty( $campaign_data['pre_header'] ) ? $campaign_data['pre_header'] : '';
	
			if ( ! empty( $meta['list_conditions'] ) ) {
				$meta['list_conditions'] = IG_ES_Campaign_Rules::remove_empty_conditions( $meta['list_conditions'] );
			}
	
			$campaign_data['meta'] = maybe_serialize( $meta );
			$campaign_data['name'] = $campaign_data['subject'];
			$campaign_data['slug'] = sanitize_title( sanitize_text_field( $campaign_data['name'] ) );
	
			$campaign_data = apply_filters( 'ig_es_campaign_data', $campaign_data );
			$campaign_data = apply_filters( 'ig_es_' . $campaign_type . '_data', $campaign_data );
	
			$result = ES()->campaigns_db->save_campaign( $campaign_data, $campaign_id );
	
			if ( ! empty( $result ) ) {
				if ( ! $is_updating ) {
					// In case of insert, result is campaign id.
					$response['campaign_id'] = $result;
				} else {
					// In case of update, only update flag is returned.
					$response['campaign_id'] = $campaign_id;
				}
				wp_send_json_success( $response );
			} else {
				wp_send_json_error();
			}
	
		}
	
		/**
		 * Method to get preview HTML for campaign
		 *
		 * @return $response
		 *
		 * @since 4.4.7
		 */
		public function get_campaign_preview() {
	
			check_ajax_referer( 'ig-es-admin-ajax-nonce', 'security' );
	
			$response = array();
	
			$preview_type  = ig_es_get_request_data( 'preview_type' );
			$campaign_data = ig_es_get_request_data( 'campaign_data', array(), false );
	
			$template_data                = array();
			$template_data['content']     = ! empty( $campaign_data['body'] ) ? $campaign_data['body'] : '';
			$template_data['template_id'] = ! empty( $campaign_data['template_id'] ) ? $campaign_data['template_id'] : '';
			$template_data['campaign_id'] = ! empty( $campaign_data['id'] ) ? $campaign_data['id'] : 0;
	
			$campaign_data            = $this->add_campaign_body_data( $campaign_data );
			$response['preview_html'] = $campaign_data['body'];
	
			if ( 'inline' === $preview_type ) {
				$inline_preview_data = $this->get_campaign_inline_preview_data( $campaign_data );
				$response            = array_merge( $response, $inline_preview_data );
			}
	
			if ( ! empty( $response ) ) {
				wp_send_json_success( $response );
			} else {
				wp_send_json_error();
			}
	
		}
	
		/**
		 * Method to get campaign inline preview data.
		 *
		 * @param array $campaign_data Broadcast data.
		 *
		 * @return array $preview_data
		 *
		 * @since 4.4.7
		 */
		public function get_campaign_inline_preview_data( $campaign_data = array() ) {
			$list_id      = ! empty( $campaign_data['list_ids'] ) ? $campaign_data['list_ids'] : 0;
			$preview_data = array();
			$first_name   = '';
			$last_name    = '';
			$email        = '';
	
			if ( ! empty( $list_id ) ) {
				// Check if multiple lists selection is enabled.
				if ( is_array( $list_id ) && ! empty( $list_id ) ) {
					// Since we need to get only one sample email for showing the preview, we can get it from the first list itself.
					$list_id = $list_id[0];
				}
				$subscribed_contacts = ES()->lists_contacts_db->get_subscribed_contacts_from_list( $list_id );
				if ( ! empty( $subscribed_contacts ) ) {
					$subscribed_contact = array_shift( $subscribed_contacts );
					$contact_id         = ! empty( $subscribed_contact['contact_id'] ) ? $subscribed_contact['contact_id'] : 0;
					if ( ! empty( $contact_id ) ) {
						$subscriber_data = ES()->contacts_db->get_by_id( $contact_id );
						if ( ! empty( $subscriber_data ) ) {
							$first_name = ! empty( $subscriber_data['first_name'] ) ? $subscriber_data['first_name'] : '';
							$last_name  = ! empty( $subscriber_data['last_name'] ) ? $subscriber_data['first_name'] : '';
							$email      = ! empty( $subscriber_data['email'] ) ? $subscriber_data['email'] : '';
						}
					}
				}
			}
	
			$preview_data['campaign_subject'] = ! empty( $campaign_data['subject'] ) ? esc_html( $campaign_data['subject'] ) : '';
			$preview_data['contact_name']     = esc_html( $first_name . ' ' . $last_name );
			$preview_data['contact_email']    = esc_html( $email );
	
			return $preview_data;
		}
	
		/**
		 * Function to add values of checkbox fields incase they are not checked.
		 *
		 * @param array $campaign_data
		 *
		 * @return array $campaign_data
		 *
		 * @since 4.4.7
		 */
		public function add_tracking_fields_data( $campaign_data = array() ) {
	
			$campaign_meta = ! empty( $campaign_data['meta'] ) ? maybe_unserialize( $campaign_data['meta'] ) : array();
	
			if ( empty( $campaign_meta['enable_open_tracking'] ) ) {
				$campaign_meta['enable_open_tracking'] = 'no';
			}
	
			$campaign_data['meta'] = maybe_serialize( $campaign_meta );
	
			return $campaign_data;
		}
	
		/**
		 * Method to check if open tracking is enabled campaign wise.
		 *
		 * @param bool  $is_track_email_opens Is open tracking enabled.
		 * @param int   $contact_id Contact ID.
		 * @param int   $campaign_id Campaign ID.
		 * @param array $link_data Link data.
		 *
		 * @return bool $is_track_email_opens Is open tracking enabled.
		 *
		 * @since 4.4.7
		 */
		public function is_open_tracking_enabled( $is_track_email_opens, $contact_id, $campaign_id, $link_data ) {
			if ( ! empty( $link_data ) ) {
				$campaign_id = ! empty( $link_data['campaign_id'] ) ? $link_data['campaign_id'] : 0;
				if ( ! empty( $campaign_id ) ) {
					$campaign = ES()->campaigns_db->get( $campaign_id );
					if ( ! empty( $campaign ) ) {
						$campaign_type = $campaign['type'];

						$supported_campaign_types = array(
							IG_CAMPAIGN_TYPE_NEWSLETTER, 
							IG_CAMPAIGN_TYPE_POST_NOTIFICATION, 
							IG_CAMPAIGN_TYPE_POST_DIGEST, 
							IG_CAMPAIGN_TYPE_WORKFLOW_EMAIL 
						);

						$is_supported_type = in_array( $campaign_type, $supported_campaign_types, true );
						if ( $is_supported_type ) {
							$campaign_meta        = maybe_unserialize( $campaign['meta'] );
							$is_track_email_opens = ! empty( $campaign_meta['enable_open_tracking'] ) ? $campaign_meta['enable_open_tracking'] : $is_track_email_opens;
						}
					}
				}
			}
	
			return $is_track_email_opens;
		}

		public function add_post_notification_data( $campaign_data ) {

			$categories         = ! empty( $campaign_data['es_note_cat'] ) ? $campaign_data['es_note_cat'] : array();
			$es_note_cat_parent = $campaign_data['es_note_cat_parent'];
			$categories         = ( ! empty( $es_note_cat_parent ) && in_array( $es_note_cat_parent, array( '{a}All{a}', '{a}None{a}' ), true ) ) ? array( $es_note_cat_parent ) : $categories;

			// Check if custom post types are selected.
			if ( ! empty( $campaign_data['es_note_cpt'] ) ) {
				// Merge categories and selected custom post types.
				$categories = array_merge( $categories, $campaign_data['es_note_cpt'] );
			}
			

			$campaign_data['categories'] = ES_Common::convert_categories_array_to_string( $categories );

			return $campaign_data;
		}

		public static function replace_post_notification_merge_tags_with_sample_post( $campaign_data ) {

			if ( ! empty( $campaign_data['id'] ) ) {

				$args         = array(
					'numberposts' => '1',
					'order'       => 'DESC',
					'post_status' => 'publish',
				);
				$recent_posts = wp_get_recent_posts( $args, OBJECT );
	
				if ( count( $recent_posts ) > 0 ) {
					$post = array_shift( $recent_posts );
	
					$post_id          = $post->ID;
					$template_id      = $campaign_data['id'];
					$campaign_body    = ! empty( $campaign_data['body'] ) ? $campaign_data['body'] : '';
					$campaign_subject = ! empty( $campaign_data['subject'] ) ? $campaign_data['subject'] : '';

					$campaign_subject = ES_Handle_Post_Notification::prepare_subject( $campaign_subject, $post );
					$campaign_body    = ES_Handle_Post_Notification::prepare_body( $campaign_body, $post_id, $template_id );

					$campaign_data['subject'] = $campaign_subject;
					$campaign_data['body']    = $campaign_body;
				}
			}

			return $campaign_data;
		}

		public static function replace_post_digest_merge_tags_with_sample_posts( $campaign_data ) {
			
			if ( ! empty( $campaign_data['id'] ) && class_exists( 'ES_Post_Digest' ) ) {
				$ignore_stored_post_ids = true;
				$ignore_last_run        = true;
				$campaign_id 			= $campaign_data['id'];
				$campaign_body 			= $campaign_data['body'];
				$post_ids               = ES_Post_Digest::get_post_id_for_post_digest( $campaign_id, $ignore_stored_post_ids, $ignore_last_run );
				$campaign_body          = ES_Post_Digest::process_post_digest_template( $campaign_body, $post_ids );
				$campaign_data['body']  = $campaign_body;
			}

			return $campaign_data;
		}

		/**
		 * Add required broadcast schedule date/time data
		 *
		 * @param array $data
		 *
		 * @return array $data
		 *
		 * @since 4.4.7
		 */
		public function add_broadcast_scheduler_data( $data ) {

			$scheduling_option = ! empty( $data['scheduling_option'] ) ? $data['scheduling_option'] : 'schedule_now';

			$schedule_str = '';

			if ( 'schedule_now' === $scheduling_option ) {
				// Get time without GMT offset, as we are adding later on.
				$schedule_str = current_time( 'timestamp', false );
			}

			if ( ! empty( $schedule_str ) ) {
				$gmt_offset_option = get_option( 'gmt_offset' );
				$gmt_offset        = ( ! empty( $gmt_offset_option ) ) ? $gmt_offset_option : 0;
				$schedule_date     = gmdate( 'Y-m-d H:i:s', $schedule_str - ( $gmt_offset * HOUR_IN_SECONDS ) );

				$data['start_at'] = $schedule_date;
				$meta             = ! empty( $data['meta'] ) ? maybe_unserialize( $data['meta'] ) : array();
				$meta['type']     = 'one_time';
				$meta['date']     = $schedule_date;
				$data['meta']     = maybe_serialize( $meta );
			}

			return $data;
		}

		public function show_avaialable_keywords() {
			?>
			<div class="campaign-keyword-wrapper mt-1 p-4 w-full border border-gray-300">
				<!-- Start-IG-Code -->
				<p id="post_notification" class="pb-2 border-b border-gray-300">
					<a href="https://www.icegram.com/documentation/es-what-are-the-available-keywords-in-the-post-notifications/?utm_source=es&amp;utm_medium=in_app&amp;utm_campaign=view_docs_help_page" target="_blank"><?php esc_html_e( 'Available Keywords', 'email-subscribers' ); ?></a> <?php esc_html_e( 'for Post Notification: ', 'email-subsribers' ); ?>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{FIRSTNAME | fallback:'there'}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{LASTNAME}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{NAME | fallback:'there'}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{EMAIL}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{DATE}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{POSTTITLE}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{POSTIMAGE}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{POSTEXCERPT}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{POSTDESC}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{POSTAUTHOR}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{POSTAUTHORAVATAR}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{POSTAUTHORAVATARLINK-ONLY}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{POSTLINK}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{POSTLINK-WITHTITLE}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{POSTLINK-ONLY}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{POSTFULL}}</span>
				</p>
				<!-- End-IG-Code -->
				<p id="newsletter" class="py-2 border-b border-gray-300">
					<a href="https://www.icegram.com/documentation/es-what-are-the-available-keywords-in-the-newsletters/?utm_source=es&amp;utm_medium=in_app&amp;utm_campaign=view_docs_help_page" target="_blank"><?php esc_html_e( 'Available Keywords', 'email-subscribers' ); ?></a> <?php esc_html_e( 'for Broadcast:', 'email-subscribers' ); ?>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{FIRSTNAME | fallback:'there'}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{LASTNAME}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{NAME | fallback:'there'}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{EMAIL}}</span>
				</p>
				<!-- Start-IG-Code -->
				<div id="post_digest" class="pt-2 pb-0">
					<span style="font-size: 0.8em; margin-left: 0.3em; padding: 2px; background: #e66060; color: #fff; border-radius: 2px; ">Pro</span>&nbsp;
					<a href="https://www.icegram.com/send-post-digest-using-email-subscribers-plugin/?utm_source=es&amp;utm_medium=in_app&amp;utm_campaign=view_post_digest_post" target="_blank"><?php esc_html_e( 'Available Keywords', 'email-subscribers' ); ?></a> <?php esc_html_e( 'for Post Digest:', 'email-subscribers' ); ?>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{FIRSTNAME | fallback:'there'}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{LASTNAME}}</span>
					<span class="ig-es-workflow-variable-outer inline-block px-2 py-2 mr-2 mb-2 text-xs font-bold bg-gray-100 hover:bg-gray-300 rounded-md ">{{NAME | fallback:'there'}}</span>
					<div class="post_digest_block"> {{POSTDIGEST}} <br/><?php esc_html_e( 'Any keywords related Post Notification', 'email-subscribers' ); ?> <br/>{{/POSTDIGEST}} </div>
				</div>
			</div>
			<!-- End-IG-Code -->
			<?php
		}
		
		/**
		 * Save campaign as a template
		 */
		public function save_as_template() {

			check_ajax_referer( 'ig-es-admin-ajax-nonce', 'security' );
	
			$response = array();
	
			$campaign_data    = ig_es_get_request_data( 'campaign_data', array(), false );
			$campaign_type    = ! empty( $campaign_data['type'] ) ? $campaign_data['type'] : IG_ES_DRAG_AND_DROP_EDITOR;
			$campaign_body    = ! empty( $campaign_data['body'] ) ? $campaign_data['body'] : '';
			$campaign_subject = ! empty( $campaign_data['subject'] ) ? $campaign_data['subject'] : '';
			
			if ( ! empty( $campaign_subject) && ! empty( $campaign_body ) ) {
				
				$template_data = array(
					'post_title'   => $campaign_subject,
					'post_content' => $campaign_body,
					'post_type'    => 'es_template',
					'post_status'  => 'publish',
				);
	
				$template_id       = wp_insert_post( $template_data );
				$is_template_added = ! ( $template_id instanceof WP_Error );
				
				if ( $is_template_added ) {
					
					$editor_type = ! empty( $campaign_data['meta']['editor_type'] ) ? $campaign_data['meta']['editor_type'] : '';
	
					$is_dnd_editor = IG_ES_DRAG_AND_DROP_EDITOR === $editor_type;
	
					if ( $is_dnd_editor ) {
						$dnd_editor_data = array();
						if ( ! empty( $campaign_data['meta']['dnd_editor_data'] ) ) {
							$dnd_editor_data = json_decode( $campaign_data['meta']['dnd_editor_data'] );
							update_post_meta( $template_id, 'es_dnd_editor_data', $dnd_editor_data );
						}
					} else {
						$custom_css = ! empty( $campaign_data['meta']['es_custom_css'] ) ? $campaign_data['meta']['es_custom_css'] : '';
						update_post_meta( $template_id, 'es_custom_css', $custom_css );
					}
	
					update_post_meta( $template_id, 'es_editor_type', $editor_type );
					update_post_meta( $template_id, 'es_template_type', $campaign_type );
					
					$response['template_id'] = $template_id;
				}

				if ( ! empty( $response['template_id'] ) ) {
					wp_send_json_success( $response );
				} else {
					wp_send_json_error();
				}
			}

			return $response;
		}
	}

}

ES_Campaign_Admin::get_instance();
