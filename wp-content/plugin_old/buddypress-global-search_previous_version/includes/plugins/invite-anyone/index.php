<?php
/**
 * Patch for remove autocomplete confliction with invite anyone plugin
 * https://wordpress.org/plugins/invite-anyone/
 *
 * Since version 1.1.0
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists ( 'invite_anyone_confliction_remove' ) ):

    class invite_anyone_confliction_remove {

        public function __construct() {
            $this->hooks();
        }

        public function hooks() {
            if( function_exists('invite_anyone_init') ){
                add_action( 'wp', array($this, 'remove_invite_anyone_js') );
            }
        }

        function remove_invite_anyone_js(){
            remove_action( 'wp_head', 'invite_anyone_add_js', 1 );
            add_action( 'wp_enqueue_scripts', array($this, 'invite_anyone_add_js') );
        }

        function invite_anyone_add_js() {

            global $bp;

            if ( $bp->current_action == BP_INVITE_ANYONE_SLUG || ( isset( $bp->action_variables[1] ) && $bp->action_variables[1] == BP_INVITE_ANYONE_SLUG ) ) {

                wp_dequeue_script('invite-anyone-autocomplete-js');
                wp_dequeue_script('invite-anyone-js');

                $min = '-min';
                if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
                    $min = '';
                }

                wp_enqueue_script( 'invite-anyone-autocomplete-js', buddyboss_global_search()->assets_url . "/js/invite-anyone/jquery.autocomplete$min.js", array( 'jquery' ) );

                wp_register_script( 'invite-anyone-js', buddyboss_global_search()->assets_url . '/js/invite-anyone/group-invites-js.js', array( 'invite-anyone-autocomplete-js' ) );
                wp_enqueue_script( 'invite-anyone-js' );

                // Add words that we need to use in JS to the end of the page
                // so they can be translated and still used.
                $params = apply_filters( 'ia_get_js_strings', array(
                    'unsent_invites'     => __( 'Click &ldquo;Send Invites&rdquo; to finish sending your new invitations.', 'buddypress-global-search' ),
                ) );
                wp_localize_script( 'invite-anyone-js', 'IA_js_strings', $params );

            }
        }

    }

    new invite_anyone_confliction_remove();

endif;