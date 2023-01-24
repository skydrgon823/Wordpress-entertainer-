<?php

class Kleo_Options_typography {

	public $field = array();
	public $value = '';
	public $args = array();

	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Kleo_Options 1.0.0
	 */
	function __construct( $field = array(), $value = '', $parent ) {

		$this->field = (array) $field;
		$this->value = $value;
		$this->args  = $parent->args;

		$fonts = file_get_contents( FRAMEWORK_URL . "/inc/webfonts.txt" );

		$this->field['fonts'] = json_decode( $fonts );

	}

	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since Kleo_Options 1.0.0
	 */
	function render() {

		$class = ( isset( $this->field['class'] ) ) ? 'class="' . $this->field['class'] . '" ' : '';

		echo '<p class="description" style="color:red;">' .
		     wp_kses_post( sprintf( __( 'The fonts provided below are free to use custom fonts from the <a href="%s" target="_blank">Google Web Fonts directory</a>.', 'sweetdate' ), 'http://www.google.com/webfonts' ) ) .
		     '</p>';

		$headings     = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'body' );
		$normal_fonts = array( "Arial", "Georgia", "Verdana", "Tahoma", "Times New Roman" );

		foreach ( $headings as $heading ) {
			echo '<div class="typography">';
			echo "<h3>" . ucfirst( $heading ) . "</h3><br/>";

			//size
			echo '<select class="kleo-font-size" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $heading . '][size]">';
			for ( $i = 8; $i <= 46; $i ++ ) {
				echo '<option ' . selected( $this->value[ $heading ]['size'], $i . "px", false ) . ' value="' . $i . 'px">' . $i . 'px</option>';
			}
			echo '</select>';

			//font
			echo '<select class="kleo-font" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $heading . '][font]" ' . $class . 'rows="6" >';
			echo '<optgroup label="Web Safe Fonts">';
			foreach ( $normal_fonts as $normal ) {
				echo '<option class="web-safe" value="' . $normal . '">' . $normal . '</option>';
			}
			echo '</optgroup>';
			echo '<optgroup label="Google Web Fonts">';
			foreach ( $this->field['fonts']->items as $cut ) {
				foreach ( $cut->variants as $variant ) {
					echo '<option value="' . $cut->family . ':' . $variant . '" ' . selected( $this->value[ $heading ]['font'], $cut->family . ':' . $variant, false ) . '>' . $cut->family . ' - ' . $variant . '</option>';
				}
			}
			echo '</optgroup>';
			echo '</select>';

			echo '<select class="kleo-font-style" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $heading . '][style]">';
			$style_vals = array(
				'normal'      => 'Normal',
				'bold'        => 'Bold',
				'italic'      => "Italic",
				'bold italic' => 'Bold Italic'
			);

			foreach ( $style_vals as $key => $style ) {
				echo '<option ' . selected( $this->value[ $heading ]['style'], $key, false ) . ' value="' . $key . '">' . $style . '</option>';
			}
			echo '</select><br><br>';

			if ( $heading != 'body' ) {
				if ( get_bloginfo( 'version' ) >= '3.5' ) {
					echo '<input type="text" id="color_' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $heading . '][color]" value="' . ( isset( $this->value[ $heading ]['color'] ) ? $this->value[ $heading ]['color'] : "" ) . '" class="popup-colorpicker" style="width: 70px;" data-default-color="' . ( isset( $this->value[ $heading ]['color'] ) ? esc_attr( $this->value[ $heading ]['color'] ) : "" ) . '"/>';
				} else {
					echo '<div class="farb-popup-wrapper">';
					echo '<input type="text" id="color_' . $this->field['id'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $heading . '][color]" value="' . ( isset( $this->value[ $heading ]['color'] ) ? $this->value[ $heading ]['color'] : "" ) . '" class="popup-colorpicker" style="width:70px;"/>';
					echo '<div class="farb-popup"><div class="farb-popup-inside"><div id="' . $this->field['id'] . 'picker" class="color-picker"></div></div></div>';
					echo '</div>';
				}
			}

			$font_fam = explode( ":", $this->value[ $heading ]['font'] );
			?>
			<script type="text/javascript">
				jQuery(document).ready(function () {
					var googlefontlink = "//fonts.googleapis.com/css?family=<?php echo $this->value[ $heading ]['font']; ?>";
					if (jQuery("link[href*='<?php echo $this->value[ $heading ]['font']; ?>']").length === 0) {
						jQuery('link:last').after('<link href="' + googlefontlink + '" rel="stylesheet" type="text/css">');
					}
				});
			</script>
			<?php
			echo '<div class="preview_zone" style="font: ' . $this->value[ $heading ]['style'] . ' ' . $this->value[ $heading ]['size'] . ' \'' . $font_fam[0] . '\'; color: ' . $this->value[ $heading ]['color'] . '">Grumpy wizards make toxic brew for the evil Queen and Jack.</div>';
			echo '</div>';
			echo "</br>";
		}


		echo ( isset( $this->field['desc'] ) && ! empty( $this->field['desc'] ) ) ? ' <span class="description">' . $this->field['desc'] . '</span>' : '';
	}//function

	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since Kleo_Options 1.0.0
	 */
	function enqueue() {
		wp_enqueue_script(
			'squeen-opts-field-typography-js',
			SQUEEN_OPTIONS_URL . 'fields/typography/field_typography.js',
			array( 'jquery' ),
			SQUEEN_THEME_VERSION,
			true
		);
	}
}
