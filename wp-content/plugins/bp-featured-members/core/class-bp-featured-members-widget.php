<?php
/**
 * Plugin widget to list featured members using widget
 *
 * @package bp-featured-members
 */

// Exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * BP_Featured_Members_List_Widget widget
 */
class BP_Featured_Members_List_Widget extends WP_Widget {

	/**
	 * The constructor.
	 *
	 * @param string $name Widget name.
	 * @param array  $widget_options Widget options.
	 */
	public function __construct( $name = '', $widget_options = array() ) {

		if ( empty( $name ) ) {
			$name = __( 'BuddyPress Featured Members', 'bp-featured-members' );
		}

		parent::__construct( false, $name, $widget_options );
	}

	/**
	 * Display widget content.
	 *
	 * @param array $args widget args.
	 * @param array $instance current widget instance.
	 */
	public function widget( $args, $instance ) {

		$avatar_size = isset( $instance['avatar_size'] ) ? $instance['avatar_size'] : '';
		$member_type = isset( $instance['member_type'] ) ? $instance['member_type'] : '';

		bp_featured_members()->set( 'max', $instance['max'] );
		bp_featured_members()->set( 'avatar_size', $avatar_size );
		bp_featured_members()->set( 'view', $instance['view'] );
		bp_featured_members()->set( 'context', 'widget' );
		bp_featured_members()->set( 'member_type', $member_type );

		$slide_auto           = ( $instance['slide_auto'] ) ? true : false;
		$slide_pause_on_hover = ( $instance['slide_pauseOnHover'] ) ? true : false;
		$slide_controls       = ( $instance['slide_controls'] ) ? true : false;
		$slide_loop           = ( $instance['slide_loop'] ) ? true : false;

		$slider_settings = array(
			'item'           => $instance['slide_item'],
			'slide-margin'   => $instance['slide_slideMargin'],
			'mode'           => $instance['slide_mode'], // slide.
			'speed'          => $instance['slide_speed'],
			'auto'           => $slide_auto,
			'pause-on-hover' => $slide_pause_on_hover,
			'controls'       => $slide_controls,
			'loop'           => $slide_loop,
		);

		bp_featured_members()->set( 'slider-settings', $slider_settings );

		if ( $avatar_size > BP_AVATAR_THUMB_WIDTH ) {
			bp_featured_members()->set( 'avatar_type', 'full' );
		} else {
			bp_featured_members()->set( 'avatar_type', 'thumb' );
		}

		// log loop start.
		bp_featured_members()->start_loop();

		echo $args['before_widget'];

		echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $instance['title'] , $instance, $this->id_base ) ) . $args['after_title'] ;

		?>

		<div class="bp-featured-members-widget">
			<?php bp_fm_load_members_list( $instance['view'], 'widget' ); ?>
		</div>

		<?php echo $args['after_widget']; ?>

		<?php
		bp_featured_members()->end_loop();// mark loop end.
	}

	/**
	 * Update widget settings.
	 *
	 * @param array $new_instance new widget settings.
	 * @param array $old_instance old widget settings.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$avatar_size = isset( $new_instance['avatar_size'] ) ? strip_tags( $new_instance['avatar_size'] ) : '';
		$member_type = isset( $new_instance['member_type'] ) ? $new_instance['member_type'] : '';

		$view_options            = bp_fm_get_views_options();
		$view                    = key_exists( $new_instance['view'], $view_options ) ? $new_instance['view'] : 'list';
		$instance                = $old_instance;
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['max']         = strip_tags( $new_instance['max'] );
		$instance['avatar_size'] = $avatar_size;
		$instance['view']        = $view;
		// not validating as admins are not supposed to be fooling around.
		$instance['member_type']        = $member_type;
		$instance['slide_item']         = strip_tags( $new_instance['slide_item'] );
		$instance['slide_slideMargin']  = strip_tags( $new_instance['slide_slideMargin'] );
		$instance['slide_mode']         = $new_instance['slide_mode']; // slide, fade.
		$instance['slide_speed']        = strip_tags( $new_instance['slide_speed'] );
		$instance['slide_auto']         = $new_instance['slide_auto'];
		$instance['slide_pauseOnHover'] = $new_instance['slide_pauseOnHover'];
		$instance['slide_controls']     = $new_instance['slide_controls'];
		$instance['slide_loop']         = $new_instance['slide_loop'];

		return $instance;
	}

	/**
	 * Display widget settings form.
	 *
	 * @param Object $instance widget instance.
	 *
	 * @return void
	 */
	public function form( $instance ) {

		$defaults = array(
			'title'              => __( 'Featured Members', 'bp-featured-members' ),
			'max'                => 5,
			'avatar_size'        => '',
			'view'               => 'list',
			'member_type'        => '',
			'slide_item'         => 3,
			'slide_slideMargin'  => 0,
			'slide_mode'         => 'slide', // slide, fade.
			'slide_speed'        => 400,
			'slide_auto'         => true,
			'slide_pauseOnHover' => false,
			'slide_controls'     => false,
			'slide_loop'         => true,
		);

		$instance             = wp_parse_args( (array) $instance, $defaults );
		$title                = strip_tags( $instance['title'] );
		$max                  = strip_tags( $instance['max'] );
		$avatar_size          = strip_tags( $instance['avatar_size'] );
		$view                 = $instance['view'];
		$member_type          = $instance['member_type'];
		$view_options         = bp_fm_get_views_options();
		$member_types         = bp_get_member_types( array(), 'objects' );
		$slide_item           = strip_tags( $instance['slide_item'] );
		$slide_slide_margin   = strip_tags( $instance['slide_slideMargin'] );
		$slide_mode           = $instance['slide_mode'];
		$slide_speed          = strip_tags( $instance['slide_speed'] );
		$slide_auto           = $instance['slide_auto'];
		$slide_pause_on_hover = $instance['slide_pauseOnHover'];
		$slide_controls       = $instance['slide_controls'];
		$slide_loop           = $instance['slide_loop'];

		?>
		<p>
			<label>
				<?php _e( 'Title:', 'bp-featured-members' ); ?><br/>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>"
				       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
				       value="<?php echo esc_attr( $title ); ?>" class="widefat"/>
			</label>
		</p>

		<p>
			<label>
				<?php _e( 'Max. number of users to show:', 'bp-featured-members' ); ?>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'max' ); ?>" name="<?php echo $this->get_field_name( 'max' ); ?>" type="text" value="<?php echo esc_attr( $max ); ?>" />
			</label>
		</p>

        <p>
            <label>
				<?php _e( 'Avatar size:', 'bp-featured-members' ); ?>
                <input class="tiny-text" id="<?php echo $this->get_field_id( 'avatar_size' ); ?>" name="<?php echo $this->get_field_name( 'avatar_size' ); ?>" type="text" value="<?php echo esc_attr( $avatar_size ); ?>" />
            </label>
        </p>

        <?php if ( ! empty( $member_types ) ) : ?>
            <p>
                <label>
					<?php _e( 'Filter by Member Type', 'bp-featured-members' ); ?>
                    <select id="<?php echo $this->get_field_id( 'member_type' ); ?>" name ="<?php echo $this->get_field_name( 'member_type' ); ?>">
                        <option value="" <?php selected( $member_type, "" ); ?> ><?php _e( 'N/A', 'bp-featured-members' );?> </option>
						<?php foreach ( $member_types as $member_type_obj ): ?>
                            <option value="<?php echo esc_attr( $member_type_obj->name ); ?>" <?php selected( $member_type, $member_type_obj->name ); ?> ><?php echo esc_html( $member_type_obj->labels['singular_name'] ); ?></option>
						<?php endforeach; ?>
                    </select>
                </label>
                <br/>
            </p>
        <?php endif;?>

        <p>
			<label>
				<?php _e( 'Display View:', 'bp-featured-members' ); ?>
				<select class="bpfm-widget-admin-widget-view-options" id="<?php echo $this->get_field_id( 'view' ); ?>" name="<?php echo $this->get_field_name( 'view' ); ?>">
                    <?php foreach ( $view_options as $value => $option_name ) : ?>
						<option value="<?php echo $value ?>" <?php selected( $view, $value ) ?>><?php echo $option_name; ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</p>
        <?php $display_style = $view == 'slider' ? 'block': 'none';?>
        <div class="bpfm-widget-admin-widget-slide-options" style="display:<?php echo $display_style;?>;">
            <h3><?php _e( 'Slide Options', 'bp-featured-members' );?></h3>
        <p>
            <label>
				<?php _e( 'Slide items:', 'bp-featured-members' ); ?>
                <input class="tiny-text" id="<?php echo $this->get_field_id( 'slide_items' ); ?>" name="<?php echo $this->get_field_name( 'slide_item' ); ?>" type="text" value="<?php echo esc_attr( $slide_item ); ?>" />
            </label>
        </p>

        <p>
            <label>
				<?php _e( 'Slide margin:', 'bp-featured-members' ); ?>
                <input class="tiny-text" id="<?php echo $this->get_field_id( 'slide_slideMargin' ); ?>" name="<?php echo $this->get_field_name( 'slide_slideMargin' ); ?>" type="text" value="<?php echo esc_attr( $slide_slide_margin ); ?>" />
            </label>
        </p>

        <p>
            <label>
				<?php _e( 'Slide mode', 'bp-featured-members' ); ?>
                <select id="<?php echo $this->get_field_id( 'slide_mode' ); ?>" name="<?php echo $this->get_field_name( 'slide_mode' ); ?>">
                    <option value="slide" <?php selected( $slide_mode, 'slide' )?>><?php _e( 'Slide', 'bp-featured-members' ); ?></option>
                    <option value="fade" <?php selected( $slide_mode, 'fade' )?>><?php _e( 'Fade', 'bp-featured-members' ); ?></option>
                </select>
            </label>
        </p>

        <p>
            <label>
				<?php _e( 'Slide speed:', 'bp-featured-members' ); ?>
                <input class="tiny-text" id="<?php echo $this->get_field_id( 'slide_speed' ); ?>" name="<?php echo $this->get_field_name( 'slide_speed' ); ?>" type="text" value="<?php echo esc_attr( $slide_speed ); ?>" />
            </label>
        </p>

        <p>
            <label>
				<?php _e( 'Slide auto:', 'bp-featured-members' ); ?>
                <select id="<?php echo $this->get_field_id( 'slide_auto' ); ?>" name="<?php echo $this->get_field_name( 'slide_auto' ); ?>">
                    <option value="1" <?php selected( $slide_auto, 1 )?>><?php _e( 'True', 'bp-featured-members' ) ?></option>
                    <option value="0" <?php selected( $slide_auto, 0 )?>><?php _e( 'False', 'bp-featured-members' ) ?></option>
                </select>
            </label>
        </p>

        <p>
            <label>
	            <?php _e( 'Slide pause Hover:', 'bp-featured-members' ); ?>
                <select id="<?php echo $this->get_field_id( 'slide_pauseOnHover' ); ?>" name="<?php echo $this->get_field_name( 'slide_pauseOnHover' ); ?>">
                    <option value="1" <?php selected( $slide_pause_on_hover, 1 )?>><?php _e( 'True', 'bp-featured-members' ) ?></option>
                    <option value="0" <?php selected( $slide_pause_on_hover, 0 )?>><?php _e( 'False', 'bp-featured-members' ) ?></option>
                </select>
            </label>
        </p>

        <p>
            <label>
		        <?php _e( 'Slide controls:', 'bp-featured-members' ); ?>
                <select id="<?php echo $this->get_field_id( 'slide_controls' ); ?>" name="<?php echo $this->get_field_name( 'slide_controls' ); ?>">
                    <option value="1" <?php selected( $slide_controls, 1 )?>><?php _e( 'True', 'bp-featured-members' ) ?></option>
                    <option value="0" <?php selected( $slide_controls, 0 )?>><?php _e( 'False', 'bp-featured-members' ) ?></option>
                </select>
            </label>
        </p>

        <p>
            <label>
				<?php _e( 'Slide loop:', 'bp-featured-members' ); ?>
                <select id="<?php echo $this->get_field_id( 'slide_loop' ); ?>" name="<?php echo $this->get_field_name( 'slide_loop' ); ?>">
                    <option value="1" <?php selected( $slide_loop, 1 )?>><?php _e( 'True', 'bp-featured-members' ) ?></option>
                    <option value="0" <?php selected( $slide_loop, 0 )?>><?php _e( 'False', 'bp-featured-members' ) ?></option>
                </select>
            </label>
        </p>
</div>
		<?php
	}
}

/**
 * Register Widget
 */
function bp_featured_members_register_widgets() {
	register_widget( 'BP_Featured_Members_List_Widget' );
}

add_action( 'bp_widgets_init', 'bp_featured_members_register_widgets' );
