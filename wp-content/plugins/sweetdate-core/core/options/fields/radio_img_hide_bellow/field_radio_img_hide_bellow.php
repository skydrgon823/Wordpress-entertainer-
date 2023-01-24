<?php
class Kleo_Options_radio_img_hide_bellow {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since Kleo_Options 1.0.0
    */
    function __construct($field = array(), $value = '', $parent = '') {
        $this->field = $field;
		$this->value = $value;
		$this->args = $parent->args;
    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since Kleo_Options 1.0.0
    */
    function render() {
        $class = (isset($this->field['class'])) ?  $this->field['class'] : '';
        $next_to_hide = (isset($this->field['next_to_hide'])) ? $this->field['next_to_hide'] : '1';
        echo '<fieldset>';
        foreach($this->field['options'] as $k => $v) {
            $selected = (checked($this->value, $k, false) != '') ? ' squeen-radio-img-selected' : '';
            echo '<label class="squeen-radio-img' . $selected . ' squeen-radio-img-' . $this->field['id'] . '" for="' . $this->field['id'] . '_' . array_search($k,array_keys($this->field['options'])) . '" >';
            echo '<input type="radio" id="' . $this->field['id'] . '_' . array_search($k,array_keys($this->field['options'])) . '" data-allow="' . $v['allow'] . '"  name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" class="' . $class . ' squeen-opts-radio_img-hide-below" value="' . $k . '" ' .checked($this->value, $k, false) . ' onclick="jQuery:kleo_radio_img_hide_bellow_select(\'' . $this->field['id'] . '_' . array_search($k,array_keys($this->field['options'])) . '\', \'' . $this->field['id'] . '\');" data-amount="'.$next_to_hide.'" />';
            echo '<img src="' . $v['img'] . '" alt="' . $v['title'] . '" />';
            echo '<br/><span>' . $v['title'] . '</span>';
            echo '</label>';
        }
        echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? '<br/><span class="description">' . $this->field['desc'] . '</span>' : '';
        echo '</fieldset>';
    }

    /**
     * Enqueue Function.
     *
     * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
     *
     * @since Kleo_Options 1.0.0
    */
    function enqueue() {
        wp_enqueue_script(
            'squeen-opts-field-radio_img_hide-bellow-js', 
            SQUEEN_OPTIONS_URL . 'fields/radio_img_hide_bellow/field_radio_img_hide_bellow.js', 
            array('jquery'),
            time(),
            true
        );
    }
}
