<?php
class Kleo_Options_button_set_hide_bellow {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since Kleo_Options 1.0.0
    */
    function __construct($field = array(), $value ='', $parent) {
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
        $class = (isset($this->field['class'])) ? $this->field['class'] : '';
        $next_to_hide = (isset($this->field['next_to_hide'])) ? $this->field['next_to_hide'] : '1';
        echo '<fieldset class="buttonset_hide">';
            foreach($this->field['options'] as $k => $v) {
                echo '<input data-amount="' . $next_to_hide . '"  data-allow="' . $v['allow'] . '" type="radio" id="' . $this->field['id'] . '_' . array_search($k,array_keys($this->field['options'])) . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" class="' . $class . ' squeen-opts-buttonset-hide-below" value="' . $k . '" ' . checked($this->value, $k, false) . '/>';
                echo '<label for="' . $this->field['id'] . '_' . array_search($k,array_keys($this->field['options'])) . '">' . $v['name'] . '</label>';
            }
        echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? '&nbsp;&nbsp;<span class="description">' . $this->field['desc'] . '</span>' : '';
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
        wp_enqueue_style('squeen-opts-jquery-ui-css');
        wp_enqueue_script(
            'squeen-opts-field-button_set_hide_bellow-js', 
            SQUEEN_OPTIONS_URL . 'fields/button_set_hide_bellow/field_button_set_hide_bellow.js', 
            array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'),
            time(),
            true
        );
    }
}
