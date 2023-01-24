<?php
namespace Postslist\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Postslist
 *
 * Elementor widget for postslist.
 *
 * @since 1.0.0
 */
class Postslist extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'postslist';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Posts List', 'postslist' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general-elements' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'postslist' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'postslist' ),
			]
		);

        $this->add_control(
            'post_type',
            [
                'label' => __( 'Post Type', 'postslist' ),
                'type' => Controls_Manager::SELECT,
                'options' => postslist_post_types(),
                'default' => 'post',

            ]
        );
			
		$this->add_control(
            'num_posts',
            [
                'label' => __( 'Number Of Post(s)', 'postslist' ),
                'type' => Controls_Manager::TEXT,
                'default' => '3',

            ]
        );
			
		$this->add_control(
            'excerpt_length',
            [
                'label' => __( 'Excerpt Length)', 'postslist' ),
                'type' => Controls_Manager::TEXT,
                'default' => '20',

            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __( 'Category ID', 'elementor' ),
                'description' => __('Comma separated list of category ids','elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'post_type' => 'post'
                ]
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'postslist' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
            'border-color',
            [
                'label' => __( 'Border color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '.postslist .postslist-border-color' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'circle-color',
            [
                'label' => __( 'Time Marker color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '.postslist .in-view.postslist-marker-color:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		$post_args = postslist_post_settings($settings);
		$posts = postslist_post_data($post_args);

		$length = $settings['excerpt_length'];
		?>
        <div class="elementor-row">
                <?php foreach($posts as $key => $post){
                    setup_postdata($post);

					$featured_img_url = get_the_post_thumbnail_url($post->ID,'full');

					$excerpt = strip_tags($post->post_content); 
					$excerpt = substr($excerpt, 0, $length);
					$result = substr($excerpt, 0, strrpos($excerpt, ' '));

					$title_text = $post->post_title;
					if(strlen($title_text) > 20) {						
						$title = substr($title_text, 0, 20).'...';
					}else {
						$title =  $title_text;
					}
					

                ?>
                    <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-<?php echo $post->ID; ?>" data-id="<?php echo $post->ID; ?>" data-element_type="column">
					<div class="elementor-column-wrap elementor-element-populated">
                        <div class="posts-image">
							<img src="<?php echo $featured_img_url; ?>" class="attachment-large size-large" alt="<?php echo $post->post_title;?>">
                        </div>
                         <div class="date-time"><span><?php echo date('d, F-Y', strtotime($post->post_date));?></span> | <span><?php echo get_comments_number($post->ID);?> Comment(s)</span></div>
						 <div class="post-content-container">
							 <h3 class="post-title"><?php echo $title;?></h3>
							 <div class="post-content"><?php echo $result; ?></div>
							 <div class="read-more"><a href="<?php echo get_permalink( $post->ID ); ?>">Read More</a></div>
                        </div>
                    </div>
                  </div>
                <?php
                    }
                wp_reset_postdata();
                ?>
        </div>
        <?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<div class="title">
			{{{ settings.title }}}
		</div>
		<?php
	}
}
