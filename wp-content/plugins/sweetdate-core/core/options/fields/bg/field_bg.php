<?php
class Kleo_Options_bg {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since Kleo_Options 1.0.0
    */
    function __construct($field = array(), $value ='', $parent = '') {
        $this->field = $field;
		$this->value = $value;
		$this->args = $parent->args;
		$this->url = $parent->url;
    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since Kleo_Options 1.0.0
    */
    function render() {
        global $patterns;
        echo '<div class="bg_container">';
        
        echo '<h4>Background type</h4>';
        $type_opts = array('none' => 'None','pattern' => 'Pattern', 'image' => 'Image', 'color' => 'Color');
        echo '<select name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][type]" id="type_'.$this->field['id'].'" class="type">';
        foreach($type_opts as $k => $v) {
            $selected = (isset($this->value['type']) && $this->value['type'] == $k) ? ' selected="selected"' : '';
            echo '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
        }
        echo '</select>';
       
        //pattern
        echo '<fieldset class="kleo-grouped pattern">';
        echo '<h4>Pattern</h4>';
        foreach($patterns as $k => $v) {
            $selected = isset($this->value['pattern'])&&(checked($this->value['pattern'], $k, false) != '') ? ' squeen-radio-img-selected' : '';
            echo '<label class="squeen-radio-img' . $selected . ' squeen-radio-img-pattern_' . $this->field['id'] . '" for="pattern_' . $this->field['id'] . '_' . array_search($k,array_keys($patterns)) . '" >';
            echo '<input type="radio" id="pattern_' . $this->field['id'] . '_' . array_search($k,array_keys($patterns)) . '" name="' . $this->args['opt_name'] .'[' . $this->field['id'] . '][pattern]" value="' . $k . '" ' .((isset($this->value['pattern']) && $this->value['pattern'] == $k )?' checked="checked"':'') . ' onclick="jQuery:kleo_radio_img_select(\'pattern_' . $this->field['id'] . '_' . array_search($k,array_keys($patterns)) . '\', \'pattern_' . $this->field['id'] . '\');" />';
            echo '<img src="' . $v['img'] . '" alt="' . $v['title'] . '" />';
            echo '<br/><span>' . $v['title'] . '</span>';
            echo '</label>';
        }
        echo '</fieldset>';
        
        //upload
        echo '<fieldset class="kleo-grouped image">';
        echo '<h4>Image</h4>';
        echo '<input type="text" id="image_' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][image]" value="' . (isset($this->value['image'])?$this->value['image']:"") . '" />';
        if((isset($this->value['image']) && $this->value['image'] == '') || !isset($this->value['image']) ) {$remove = ' style="display:none;"'; $upload = ''; } else {$remove = ''; $upload = ' style="display:none;"'; }
        echo ' <a data-update="Select File" data-choose="Choose a File" href="javascript:void(0);"class="squeen-opts-upload button-secondary"' . $upload . ' rel-id="image_' . $this->field['id'] . '">' .
             esc_html__( 'Upload', 'sweetdate' ) .
             '</a>';
        echo '<img class="squeen-opts-screenshot" id="squeen-opts-screenshot-image-' . $this->field['id'] . '" src="' . (isset($this->value['image'])?$this->value['image']:"") . '" />';
        echo ' <a href="javascript:void(0);" class="squeen-opts-upload-remove"' . $remove . ' rel-id="image_' . $this->field['id'] . '">' .
             esc_html__('Remove', 'sweetdate') .
             '</a>';
        echo '<br><br>';
        
        //img repeat
        $img_repeat_opts = array('no-repeat' => 'No Repeat', 'repeat' => 'Repeat', 'repeat-x' => 'Repeat X', 'repeat-y' => 'Repeat Y');
        echo '<select id="img_repeat_' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][img_repeat]' . '">';
        foreach($img_repeat_opts as $k => $v) {
            $selected = (isset($this->value['img_repeat']) && $this->value['img_repeat'] == $k) ? ' selected="selected"' : '';
            echo '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
        }
        echo '</select>';
        
        //img vertical
        $img_vertical_opts = array('top' => 'Top', 'bottom' => 'Bottom', 'center' => 'Center');
        echo '<select id="img_vertical_' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][img_vertical]' . '">';
        foreach($img_vertical_opts as $k => $v) {
            $selected = (isset($this->value['img_vertical']) && $this->value['img_vertical'] == $k) ? ' selected="selected"' : '';
            echo '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
        }
        echo '</select>';
        
        //img horizontal
        $img_horizontal_opts = array('left' => 'Left', 'right' => 'Right', 'center' => 'Center');
        echo '<select id="img_horizontal_' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][img_horizontal]' . '">';
        foreach($img_horizontal_opts as $k => $v) {
            $selected = (isset($this->value['img_horizontal']) && $this->value['img_horizontal'] == $k) ? ' selected="selected"' : '';
            echo '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
        }
        echo '</select>';
        
         //img bg size
        $img_size_opts = array('auto' => 'Auto', 'cover' => 'Cover', 'contain' => 'Contain');
        echo '<select id="img_size_' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][img_size]' . '">';
        foreach($img_size_opts as $k => $v) {
            $selected = (isset($this->value['img_size']) && $this->value['img_size'] == $k) ? ' selected="selected"' : '';
            echo '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
        }
        echo '</select>';
        
         //img bg attachment
        $img_attachment_opts = array('scroll' => 'Scroll', 'fixed' => 'Fixed');
        echo '<select id="img_attachment_' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][img_attachment]' . '">';
        foreach($img_attachment_opts as $k => $v) {
            $selected = (isset($this->value['img_attachment']) && $this->value['img_attachment'] == $k) ? ' selected="selected"' : '';
            echo '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
        }
        echo '</select>';
        echo '</fieldset>';

        //color
        echo '<fieldset class="kleo-grouped color pattern image">';
        echo '<h4>Color</h4>';
        if(get_bloginfo('version') >= '3.5') {
            echo '<input type="text" id="color_' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][color]" value="' . (isset($this->value['color'])?$this->value['color']:"") . '" class="popup-colorpicker" style="width: 70px;" data-default-color="' . (isset($this->value['color'])?esc_attr($this->value['color']):"") . '"/>';
        } else {
            echo '<div class="farb-popup-wrapper">';
            echo '<input type="text" id="color_' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][color]" value="' . (isset($this->value['color'])?$this->value['color']:"") . '" class="popup-colorpicker" style="width:70px;"/>';
            echo '<div class="farb-popup"><div class="farb-popup-inside"><div id="' . $this->field['id'] . 'picker" class="color-picker"></div></div></div>';
            echo '</div>';
        }
        echo '</fieldset>';
        
        echo '</div>';
        
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
            'squeen-opts-field-bg-js', 
            SQUEEN_OPTIONS_URL . 'fields/bg/field_bg.js', 
            array('jquery'),
            SQUEEN_THEME_VERSION,
            true
        );
    }

}
