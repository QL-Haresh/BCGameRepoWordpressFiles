<?php
/**
 * Extra Sanitization functions for customizer
 * These are used in addition / on top of hybridextend core sanitization functions
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Get default sanitization function name for add_setting type
 *
 * @since 2.0.0
 * @param string $type
 * @param array $setting
 * @param string $id
 * @return string
 */
function hybridextend_customize_get_sanitization( $type, $setting = array(), $id = '' ) {

	$return = false;

	/* Override WordPress's default types */

	if ( 'text' == $type )
		$return = 'hybridextend_customize_sanitize_text';

	elseif ( 'textarea' == $type )
		$return = 'hybridextend_customize_sanitize_textarea';

	elseif ( 'url' == $type )
		$return = 'esc_url';

	elseif ( 'select' == $type || 'radio' == $type )
		$return = 'hybridextend_customize_sanitize_choices';

	elseif ( 'checkbox' == $type )
		$return = 'hybridextend_customize_sanitize_checkbox';

	elseif ( 'range' == $type )
		$return = 'hybridextend_customize_sanitize_range';

	elseif ( 'dropdown-pages' == $type )
		$return = 'absint';

	elseif ( 'color' == $type )
		$return = 'sanitize_hex_color';

	elseif ( 'upload' == $type || 'image' == $type )
		$return = 'hybridextend_customize_sanitize_file_url';

	/* If a custom control is being used, let them define their sanitization function, and return */

	return apply_filters( 'hybridextend_customize_sanitization_function', $return, $type, $setting, $id );

}

/**
 * Sanitize a string to allow only tags in the allowedtags array.
 *
 * @since 2.0.0
 * @param string $value The unsanitized string.
 * @return string The sanitized string.
 */
function hybridextend_customize_sanitize_text( $value ) {
	global $allowedposttags;
	return wp_kses( $value , $allowedposttags );
}

/**
 * Sanitize a string to allow only tags in the allowedposttags array.
 *
 * @since 2.0.2
 * @param string $value The unsanitized string.
 * @return string The sanitized string.
 */
function hybridextend_customize_sanitize_textarea( $value ) {
	global $allowedposttags;
	return wp_kses( $value , $allowedposttags );
}

/**
 * Sanitize a checkbox to only allow 0 or 1
 *
 * @since 2.0.0
 * @param boolean $value The unsanitized value.
 * @return boolean The sanitized boolean.
 */
function hybridextend_customize_sanitize_checkbox( $value ) {
	if ( !empty( $value ) ) {
		return 1;
	} else {
		return 0;
	}
}

/**
 * Sanitize a value from a list of allowed values.
 *
 * @since 2.0.0
 * @param mixed $value The value to sanitize.
 * @param mixed $setting The setting for which the sanitizing is occurring.
 * @return mixed The sanitized value.
 */
function hybridextend_customize_sanitize_choices( $value, $setting ) {
	if ( is_object( $setting ) )
		$setting = $setting->id;

	$choices = hybridextend_customize_get_choices( $setting );

	if ( ! array_key_exists( $value, $choices ) )
		$value = hybridextend_customize_get_default( $setting );

	return $value;
}

/**
 * Sanitize the url of uploaded media.
 *
 * @since 2.0.0
 * @param string $value The url to sanitize
 * @return string $output The sanitized url.
 */
function hybridextend_customize_sanitize_file_url( $value ) {

	$output = '';

	$filetype = wp_check_filetype( $value );
	if ( $filetype["ext"] ) {
		$output = esc_url_raw( $value );
	}

	return $output;
}

/**
 * Sanitizes a range value
 *
 * @since 2.0.0
 * @param string $color
 * @return string|null
 */
function hybridextend_customize_sanitize_range( $value ) {

	if ( is_numeric( $value ) ) {
		return $value;
	}

	return 0;
}
