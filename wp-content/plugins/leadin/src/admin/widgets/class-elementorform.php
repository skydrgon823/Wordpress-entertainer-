<?php
namespace Leadin\admin\widgets;

use Leadin\LeadinFilters;
use Elementor\Plugin;
use Elementor\Widget_Base;

/**
 * ElementorForm Widget
 */
class ElementorForm extends Widget_Base {

	/**
	 * Widget internal name.
	 */
	public function get_name() {
		return 'hubspot-form';
	}

	/**
	 * Widget title.
	 */
	public function get_title() {
		return esc_html( 'HubSpot Form' );
	}

	/**
	 * Widget display icon.
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
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
		return array( 'hubspot', 'form', 'leadin' );
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
			'leadin-forms-v2',
			LeadinFilters::get_leadin_forms_script_url(),
			array(),
			LEADIN_PLUGIN_VERSION,
			true
		);
		return array( 'leadin-forms-v2' );
	}

	/**
	 * Widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html( __( 'Form', 'leadin' ) ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'content',
			array(
				'type' => 'leadinformselect',
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
				<div class="hubspot-form-edit-mode" data-attributes="<?php echo esc_attr( json_encode( $content ) ); ?>">
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
				$portal_id = $content['portalId'];
				$form_id   = $content['formId'];
				echo do_shortcode( '[hubspot portal="' . $portal_id . '" id="' . $form_id . '" type="form"]' );
		}
	}
}
