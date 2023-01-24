<?php

/**
 * Get dynamic translated string
 * @param $string
 * @param $domain
 * @param bool $escape
 *
 * @return string
 */
function sweet_core_translate_dynamic( $string, $domain, $escape = false ) {
	if ( $escape === true ) {
		return esc_html__( $string, $domain );
	}

	return __( $string, $domain );
}

