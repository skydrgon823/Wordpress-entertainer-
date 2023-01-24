<?php
namespace Leadin\admin\widgets;

use Leadin\AssetsManager;

/**
 * Class for Elementor form selector control
 */
class ElementorMeetingSelect extends \Elementor\Base_Data_Control {

	/**
	 * Returns control internal name
	 */
	public function get_type() {
		return 'leadinmeetingselect';
	}

	/**
	 * Load assets needed for control
	 */
	public function enqueue() {
		AssetsManager::enqueue_elementor_script();
	}

	/**
	 * Default settings for control
	 */
	protected function get_default_settings() {
		return array(
			'label_block'         => true,
			'rows'                => 3,
			'hsptmeeting_options' => array(),
		);
	}

	/**
	 * Render Function
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-hbspt-meeting-selector" data-id="<?php echo esc_html( $control_uid ); ?>"></div>
		<?php
	}

}

