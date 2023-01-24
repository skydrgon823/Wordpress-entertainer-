<?php
class Kleo_Validation_preg_replace {	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Kleo_Options 1.0.0
	*/
	function __construct($field, $value, $current) {
		$this->field = $field;
		$this->value = $value;
		$this->current = $current;
		$this->validate();
	}

	/**
	 * Field Render Function.
	 *
	 * Takes the vars and validates them
	 *
	 * @since Kleo_Options 1.0.0
	*/
	function validate() {
		$this->value = preg_replace_callback($this->field['preg']['pattern'], $this->field['preg']['replacement'], $this->value);
	}

}