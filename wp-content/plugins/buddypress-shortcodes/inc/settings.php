<?php
class BuddyPressShortcodesSettings{


    public function __construct() {
        add_action( 'init', array( &$this, 'init' ) );
        register_activation_hook( __FILE__, array( &$this, 'add_options_defaults' ) );
        add_action( 'admin_init', array( &$this, 'register_settings' ) );
        add_action( 'admin_menu', array( &$this, 'register_settings_page' ) );
    }

    function init() {
        $options = get_option( 'webim_options' );
        if( !is_admin() ) {
            if( isset( $options[ 'chk_default_options_css' ] ) && $options[ 'chk_default_options_css' ] ) {
                	wp_register_style('css-bootstrap', plugins_url('/buddypress-shortcodes/css/bootstrap.css'));
					wp_enqueue_style('css-bootstrap');
            }
            if( isset( $options[ 'chk_default_options_js' ]) && $options[ 'chk_default_options_js' ] ) {
                	wp_register_script('js-bootstrap', plugins_url('/buddypress-shortcodes/js/bootstrap.js'));
					wp_enqueue_script('js-bootstrap');
            }
        } 
        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
            return;
        }
    }

    function register_settings_page() {
        add_options_page( __( 'BuddyPress Shortcodes', 'webim' ), __( 'BuddyPress Shortcodes', 'webim' ), 'manage_options', __FILE__, array( &$this, 'settings_form') );
    }

    function add_options_defaults() {
            $arr = array(
                'chk_default_options_css'       => '1',
                'chk_default_options_js'        => '1'
            );
            update_option( 'webim_options', $arr );
    }

    function register_settings() {
        register_setting( 'webim_plugin_options', 'webim_options' );
    }

    function settings_form() {
        ?>
        <div class="wrap">
            <h2>Bootstrap Shortcodes Options</h2>
            <form method="post" action="options.php">
                <?php settings_fields( 'webim_plugin_options' ); ?>
                <?php $options = get_option( 'webim_options'); ?>
                <table class="form-table">

                    <tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>

                    <tr valign="top" style="border-top:#dddddd 1px solid;">
                        <th scope="row">Twitter Bootstrap CSS</th>
                        <td>
                            <label><input name="webim_options[chk_default_options_css]" type="checkbox" value="1" <?php if ( isset( $options[ 'chk_default_options_css' ] ) ) { checked( '1', $options[ 'chk_default_options_css' ] ); } ?> /> Load Twitter Bootstrap css file</label><br /><span style="color:#666666;margin-left:2px;">Uncheck this if you already include Bootstrap css on your template</span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Twitter Bootstrap JS</th>
                        <td>
                            <label><input name="webim_options[chk_default_options_js]" type="checkbox" value="1" <?php if ( isset( $options[ 'chk_default_options_js' ] ) ) { checked( '1', $options[ 'chk_default_options_js' ] ); } ?> /> Load Twitter Bootstrap javascript file</label><br /><span style="color:#666666;margin-left:2px;">Uncheck this if you already include Bootstrap javascript on your template</span>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>
            </form>

        </div><?php
    }
}
$bscodes = new BuddyPressShortcodesSettings();
