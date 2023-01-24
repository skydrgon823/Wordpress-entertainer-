<?php
namespace Leadin\admin\widgets;

use Elementor\Plugin;
use Elementor\Widget_Base;

/**
 * ElementorMeeting Widget
 */
class ElementorMeeting extends Widget_Base {

	/**
	 * Widget internal name.
	 */
	public function get_name() {
		return 'hubspot-meeting';
	}

	/**
	 * Widget title.
	 */
	public function get_title() {
		return esc_html( 'HubSpot Meeting' );
	}

	/**
	 * Widget display icon.
	 */
	public function get_icon() {
		return 'eicon-calendar';
	}

	/**
	 * Widget help url.
	 */
	public function get_custom_help_url() {
		return 'https://wordpress.org/support/plugin/leadin/';
	}

	/**
	 * Widget category.
	 */
	public function get_categories() {
		return array( 'general', 'hubspot' );
	}

	/**
	 * Widget keywords.
	 */
	public function get_keywords() {
		return array( 'hubspot', 'meeting', 'leadin' );
	}

	/**
	 * Widget style.
	 */
	public function get_style_depends() {
		wp_register_style( 'leadin-elementor', LEADIN_JS_BASE_PATH . '/elementor.css', array(), LEADIN_PLUGIN_VERSION );
		wp_register_style( 'leadin-css', LEADIN_PATH . '/assets/style/leadin.css', array(), LEADIN_PLUGIN_VERSION );
		return array( 'leadin-elementor', 'leadin-css' );
	}

	/**
	 * Widget script.
	 */
	public function get_script_depends() {
		wp_register_script(
			'leadin-meeting',
			'https://static.hsappstatic.net/MeetingsEmbed/ex/MeetingsEmbedCode.js',
			array(),
			LEADIN_PLUGIN_VERSION,
			true
		);
		return array( 'leadin-meeting' );
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html( __( 'Meetings Scheduler', 'leadin' ) ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'content',
			array(
				'type' => 'leadinmeetingselect',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$content  = $settings['content'];

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			?>
				<div class="hubspot-meeting-edit-mode" data-attributes=<?php echo json_encode( $content ); ?>>
				&nbsp;
				</div>

			<?php
			if ( empty( $content ) ) {
				?>
						<div class="hubspot-widget-empty">

						</div>
				<?php
			}
		}

		if ( ! empty( $content ) ) {
				$url = $content['url'];
				echo do_shortcode( '[hubspot url="' . $url . '" type="meeting"]' );
		}
	}
}
