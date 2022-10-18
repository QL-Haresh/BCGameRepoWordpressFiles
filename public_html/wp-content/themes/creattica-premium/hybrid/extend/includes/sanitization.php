<?php
/**
 * Sanitization functions and filters
 * 
 * These filters can be used for options and pretty much anywhere else, while
 * functions can be used directly in customizer
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Sanitization for text input
 *
 * @link http://developer.wordpress.org/reference/functions/sanitize_text_field/
 */
add_filter( 'hybridextend_sanitize_text', 'sanitize_text_field' );

/**
 * Sanitization for password input
 *
 * @link http://developer.wordpress.org/reference/functions/sanitize_text_field/
 */
add_filter( 'hybridextend_sanitize_password', 'sanitize_text_field' );

/**
 * Sanitization for select input
 *
 * Validates that the selected option is a valid option.
 */
add_filter( 'hybridextend_sanitize_select', 'hybridextend_sanitize_enum', 10, 2 );

/**
 * Sanitization for radio input
 *
 * Validates that the selected option is a valid option.
 */
add_filter( 'hybridextend_sanitize_radio', 'hybridextend_sanitize_enum', 10, 2 );

/**
 * Sanitization for image selector
 *
 * Validates that the selected option is a valid option.
 */
add_filter( 'hybridextend_sanitize_images', 'hybridextend_sanitize_enum', 10, 2 );

/**
 * Validates that the $input is one of the avilable choices
 * for that specific option.
 *
 * @param string $input
 * @returns string $output
 */

if ( !function_exists( 'hybridextend_sanitize_enum' ) ):
function hybridextend_sanitize_enum( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['options'] ) ) {
		$output = $input;
	}
	return $output;
}
endif;

/**
 * Sanitization for textarea field
 *
 * @param $input string
 * @return $output sanitized string
 */
if ( !function_exists( 'hybridextend_sanitize_textarea' ) ):
function hybridextend_sanitize_textarea( $input ) {
	global $allowedposttags;
	$output = wp_kses( $input, $allowedposttags);
	return $output;
}
endif;
add_filter( 'hybridextend_sanitize_textarea', 'hybridextend_sanitize_textarea' );

/**
 * Sanitization for checkbox input
 *
 * @param $input string (1 or empty) checkbox state
 * @return $output '1' or false
 */
if ( !function_exists( 'hybridextend_sanitize_checkbox' ) ):
function hybridextend_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = '1';
	} else {
		$output = false;
	}
	return $output;
}
endif;
add_filter( 'hybridextend_sanitize_checkbox', 'hybridextend_sanitize_checkbox' );

/**
 * Sanitization for multicheck
 *
 * @param array of checkbox values
 * @return array of sanitized values ('1' or false)
 */
if ( !function_exists( 'hybridextend_sanitize_multicheck' ) ):
function hybridextend_sanitize_multicheck( $input, $option ) {
	$output = '';
	if ( is_array( $input ) ) {
		foreach( $option['options'] as $key => $value ) {
			$output[$key] = false;
		}
		foreach( $input as $key => $value ) {
			if ( array_key_exists( $key, $option['options'] ) && $value ) {
				$output[$key] = '1';
			}
		}
	}
	return $output;
}
endif;
add_filter( 'hybridextend_sanitize_multicheck', 'hybridextend_sanitize_multicheck', 10, 2 );

/**
 * File upload sanitization.
 *
 * Returns a sanitized filepath if it has a valid extension.
 *
 * @param string $input filepath
 * @returns string $output filepath
 */
if ( !function_exists( 'hybridextend_sanitize_upload' ) ):
function hybridextend_sanitize_upload( $input ) {
	$output = '';
	$filetype = wp_check_filetype( $input );
	if ( $filetype["ext"] ) {
		$output = esc_url( $input );
	}
	return $output;
}
endif;
add_filter( 'hybridextend_sanitize_upload', 'hybridextend_sanitize_upload' );

/**
 * Sanitization for color input.
 * If validation fails, we dont want to save empty string. Instead saving default value is more ideal.
 *
 * @param string $input Color value. "#" may or may not be prepended to the string.
 * @return string Color in hexidecimal notation. "#" is prepended
 */
if ( !function_exists( 'hybridextend_sanitize_color' ) ):
function hybridextend_sanitize_color( $input, $option = array() ) {
	$default = ( isset( $option['std'] ) ) ? $option['std'] : '';
	// $output = apply_filters( 'hybridextend_sanitize_hex', $input, $default );
	if ( strpos( $input, ',' ) !== false )
		$output = apply_filters( 'hybridextend_sanitize_rgba', $input );
	else
		$output = apply_filters( 'hybridextend_sanitize_hex', $input );
	if ( !$output && $default ) {
		if ( strpos( $default, ',' ) !== false )
			$output = apply_filters( 'hybridextend_sanitize_rgba', $default );
		else
			$output = apply_filters( 'hybridextend_sanitize_hex', $default );
	}
	return $output;
}
endif;
add_filter( 'hybridextend_sanitize_color', 'hybridextend_sanitize_color', 10, 2 );

/**
 * Sanitize a color represented in hexidecimal notation.
 * Used to sanitize color value for color fields in color, typography, background options
 *
 * @param string Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @param string The value that this function should return if it cannot be recognized as a color.
 * @return string Color in hexidecimal notation. "#" is prepended
 */
if ( !function_exists( 'hybridextend_sanitize_hex' ) ):
function hybridextend_sanitize_hex( $hex, $default = '' ) {
	if ( $hex = hybridext_color_santize_hex( $hex ) ) {
		return $hex;
	}
	if ( !is_array( $default ) ) {
		if ( $default = hybridext_color_santize_hex( $default ) ) {
			return $default;
		}
	}
	return null;
}
endif;
add_filter( 'hybridextend_sanitize_hex', 'hybridextend_sanitize_hex', 10, 2 );

/**
 * Sanitize a color represented in rgba notation.
 * Used to sanitize color value for color fields in color, typography, background options
 *
 * @param string Color in rgba notation.
 * @param string The value that this function should return if it cannot be recognized as a color.
 * @return string Color in rgba notation.
 */
if ( !function_exists( 'hybridextend_sanitize_rgba' ) ):
function hybridextend_sanitize_rgba( $rgba, $default = '' ) {
	$rgba = str_replace( array( 'rgb', 'rgba', '(' , ')' ), '', $rgba );
	$array = explode( ',', $rgba );
	if ( !is_array( $array ) || count( $array ) < 3 ) {
		$default = str_replace( array( 'rgb', 'rgba', '(' , ')' ), '', $default );
		$array = explode( ',', $default );
	}
	if ( is_array( $array ) && count( $array ) > 2 ) {
		$array = array_map( "floatval", $array );
		$r = ( $array[0] >= 0 && $array[0] <= 255 ) ? $array[0] : false;
		$g = ( $array[1] >= 0 && $array[1] <= 255 ) ? $array[1] : false;
		$b = ( $array[2] >= 0 && $array[2] <= 255 ) ? $array[2] : false;
		$a = 1;
		if ( isset( $array[3] ) ) {
			$array[3] = ( $array[3] > 1 && $array[3] <= 100 ) ? $array[3]/100 : $array[3];
			$a = ( $array[3] >= 0 && $array[3] <= 1 ) ? $array[3] : false;
		}
		if ( $r !== false && $g !== false && $b !== false && $a !== false )
			return 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $a . ')';
	}
	return null;
}
endif;
add_filter( 'hybridextend_sanitize_rgba', 'hybridextend_sanitize_rgba', 10, 2 );

/**
 * Sanitization for editor input.
 *
 * Returns unfiltered HTML if user has permissions.
 *
 * @param string $input
 * @returns string $output
 */
if ( !function_exists( 'hybridextend_sanitize_editor' ) ):
function hybridextend_sanitize_editor( $input ) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$output = $input;
	}
	else {
		// global $allowedtags;
		// $output = wpautop( wp_kses( $input, $allowedtags ) );
		global $allowedposttags;
		$output = wpautop( wp_kses( $input, $allowedposttags ) );
	}
	return $output;
}
endif;
add_filter( 'hybridextend_sanitize_editor', 'hybridextend_sanitize_editor' );

/**
 * Sanitization for sortlist
 *
 * @param array of values
 * @return array of sanitized values
 */
if ( !function_exists( 'hybridextend_sanitize_sortlist' ) ):
function hybridextend_sanitize_sortlist( $input, $option ) {
	$output = '';
	if ( is_array( $input ) ) {
		$order=999;
		foreach( $option['options'] as $key => $value ) {
			$order++;
			if ( isset( $input[$key] ) ) {
				$parts = array_map( 'intval', explode( ",", $input[$key] ) );
				$output[$key] =( $parts[0] > 0 && $parts[0] <= count( $option['options'] ) && ( $parts[1] === 0 || $parts[1] === 1) ) ?
					$parts[0] . ',' . $parts[1] :
					$order . ',1';
			} else {
				$output[$key] = $order . ',1';
			}
		}
	}
	return $output;
}
endif;
add_filter( 'hybridextend_sanitize_sortlist', 'hybridextend_sanitize_sortlist', 10, 2 );

/**
 * Sanitization for icon
 *
 * @returns string $input if it is valid
 */
if ( !function_exists( 'hybridextend_sanitize_icon' ) ):
function hybridextend_sanitize_icon( $input, $option ) {
	$recognized = hybridextend_enum_icons();
	if ( in_array( $input, $recognized ) ) {
		return $input;
	}
	return apply_filters( 'hybridextend_sanitize_default_icon', '' );
}
endif;
add_filter( 'hybridextend_sanitize_icon', 'hybridextend_sanitize_icon', 10, 2 );

/**
 * Sanitization for group
 *
 * @returns string $input if it is valid
 */
if ( !function_exists( 'hybridextend_sanitize_group' ) ):
function hybridextend_sanitize_group( $input, $option ) {
	$output = array();

	foreach( $input as $groupID => $groupInput ) {
		$groupID = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $groupID ) );

		if ( isset( $option['fields'] ) ) :
		foreach ( $option['fields'] as $field ) {

			if ( ! isset( $field['id'] ) ) {
				continue;
			}

			// Edge case. Generally speaking, a group should not contain export/import feature.
			if ( ! isset( $field['type'] ) || $field['type'] == 'heading' || $field['type'] == 'subheading' || $field['type'] == 'info' || $field['type'] == 'html' || $field['type'] == 'import' || $field['type'] == 'export' ) {
				continue;
			}

			$fieldID = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $field['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $field['type'] && ! isset( $groupInput[$fieldID] ) ) {
				$groupInput[$fieldID] = false;
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $field['type'] && ! isset( $groupInput[$fieldID] ) ) {
				foreach ( $field['options'] as $key => $value ) {
					$groupInput[$fieldID][$key] = false;
				}
			}

			// If this is a regular save (even when a user has input an 'empty' value)
			// Or this is a reset, and the group 'std' has a 'gN' array with the field value also in it
			if ( isset ( $groupInput[$fieldID] ) )
				$subfield_val = $groupInput[$fieldID];
			// Else, this is a reset. Check if group-field has a 'std' of its own.
			elseif ( isset( $field['std'] ) )
				$subfield_val = $field['std'];
			// Else, this is a reset. And we have no standard values available.
			else
				$subfield_val = null;

			if ( has_filter( 'hybridextend_sanitize_' . $field['type'] ) ) {
				$output[$groupID][$fieldID] = apply_filters( 'hybridextend_sanitize_' . $field['type'], $subfield_val, $field );
			}

		}
		endif;

	}
	return $output;
}
endif;
add_filter( 'hybridextend_sanitize_group', 'hybridextend_sanitize_group', 10, 2 );

/**
 * Sanitization for background option.
 *
 * @returns array $output
 */
if ( !function_exists( 'hybridextend_sanitize_background' ) ):
function hybridextend_sanitize_background( $input, $option ) {

	$output = wp_parse_args( $input, array(
		'color' => '',
		'type' => 'predefined',
		'pattern' => '0',
		'image'  => '',
		'repeat'  => 'repeat',
		'position' => 'top center',
		'attachment' => 'scroll'
	) );

	$color_default = ( isset( $option['std']['color'] ) ) ? $option['std']['color'] : '';

	$output['color'] = apply_filters( 'hybridextend_sanitize_hex', $output['color'], $color_default );
	$output['type'] = apply_filters( 'hybridextend_sanitize_background_type', $output['type'] );
	$output['pattern'] = apply_filters( 'hybridextend_sanitize_background_pattern', $output['pattern'] );
	$output['image'] = apply_filters( 'hybridextend_sanitize_upload', $output['image'] );
	$output['repeat'] = apply_filters( 'hybridextend_sanitize_background_repeat', $output['repeat'] );
	$output['position'] = apply_filters( 'hybridextend_sanitize_background_position', $output['position'] );
	$output['attachment'] = apply_filters( 'hybridextend_sanitize_background_attachment', $output['attachment'] );

	return $output;
}
endif;
add_filter( 'hybridextend_sanitize_background', 'hybridextend_sanitize_background', 10, 2 );

/**
 * Sanitization for background type
 *
 * @returns $value if it is valid
 */
if ( !function_exists( 'hybridextend_sanitize_background_type' ) ):
function hybridextend_sanitize_background_type( $value ) {
	$recognized = hybridextend_enum_background_type();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'hybridextend_sanitize_default_background_type', current( $recognized ) );
}
endif;
add_filter( 'hybridextend_sanitize_background_type', 'hybridextend_sanitize_background_type' );

/**
 * Sanitization for background pattern
 *
 * @returns $value if it is valid
 */
if ( !function_exists( 'hybridextend_sanitize_background_pattern' ) ):
function hybridextend_sanitize_background_pattern( $value ) {
	$recognized = hybridextend_enum_background_pattern();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'hybridextend_sanitize_default_background_pattern', current( $recognized ) );
}
endif;
add_filter( 'hybridextend_sanitize_background_pattern', 'hybridextend_sanitize_background_pattern' );

/**
 * Sanitization for background repeat
 *
 * @returns string $value if it is valid
 */
if ( !function_exists( 'hybridextend_sanitize_background_repeat' ) ):
function hybridextend_sanitize_background_repeat( $value ) {
	$recognized = hybridextend_enum_background_repeat();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'hybridextend_sanitize_default_background_repeat', current( $recognized ) );
}
endif;
add_filter( 'hybridextend_sanitize_background_repeat', 'hybridextend_sanitize_background_repeat' );

/**
 * Sanitization for background position
 *
 * @returns string $value if it is valid
 */
if ( !function_exists( 'hybridextend_sanitize_background_position' ) ):
function hybridextend_sanitize_background_position( $value ) {
	$recognized = hybridextend_enum_background_position();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'hybridextend_sanitize_default_background_position', current( $recognized ) );
}
endif;
add_filter( 'hybridextend_sanitize_background_position', 'hybridextend_sanitize_background_position' );

/**
 * Sanitization for background attachment
 *
 * @returns string $value if it is valid
 */
if ( !function_exists( 'hybridextend_sanitize_background_attachment' ) ):
function hybridextend_sanitize_background_attachment( $value ) {
	$recognized = hybridextend_enum_background_attachment();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'hybridextend_sanitize_default_background_attachment', current( $recognized ) );
}
endif;
add_filter( 'hybridextend_sanitize_background_attachment', 'hybridextend_sanitize_background_attachment' );

/**
 * Sanitization for typography option.
 */
if ( !function_exists( 'hybridextend_sanitize_typography' ) ):
function hybridextend_sanitize_typography( $input, $option ) {

	$output = wp_parse_args( $input, array(
		'size'  => '',
		'face'  => '',
		'style' => '',
		'color' => ''
	) );

	if ( empty( $option['options'] ) )
		$option['options'] = array();
	$options = wp_parse_args( $option['options'], array(
		'size'  => array(),
		'face'  => array(),
		'style' => array(),
		'color' => true,
	) );

	// Skip if ['options']['face'] is set to false
	if ( ! $options['face'] === false )
		$output['face']  = apply_filters( 'hybridextend_sanitize_font_face', $output['face'], $options['face'] );

	// Skip if ['options']['size'] is set to false
	if ( ! $options['size'] === false )
		$output['size']  = apply_filters( 'hybridextend_sanitize_font_size', $output['size'], $options['size'] );

	// Skip if ['options']['style'] is set to false
	if ( ! $options['style'] === false )
		$output['style'] = apply_filters( 'hybridextend_sanitize_font_style', $output['style'], $options['style'] );

	// Skip if ['options']['color'] is set to false
	if ( ! $options['color'] === false )
		$output['color'] = apply_filters( 'hybridextend_sanitize_hex', $output['color'] );

	return $output;
}
endif;
add_filter( 'hybridextend_sanitize_typography', 'hybridextend_sanitize_typography', 10, 2 );

/**
 * Sanitization for font face
 */
if ( !function_exists( 'hybridextend_sanitize_font_face' ) ):
function hybridextend_sanitize_font_face( $value, $recognized = array() ) {
	$recognized = ( is_array( $recognized ) && !empty( $recognized ) ) ? $recognized : hybridextend_enum_font_faces();
	$value = stripslashes( $value );
	if ( array_key_exists( $value, $recognized ) ) { 
		return $value;
	}
	return apply_filters( 'hybridextend_sanitize_default_font_face', current( array_keys( $recognized ) ) );
}
endif;
add_filter( 'hybridextend_sanitize_font_face', 'hybridextend_sanitize_font_face', 10, 2 );

/**
 * Sanitization for font size
 */
if ( !function_exists( 'hybridextend_sanitize_font_size' ) ):
function hybridextend_sanitize_font_size( $value, $recognized = array() ) {
	$recognized = ( is_array( $recognized ) && !empty( $recognized ) ) ? $recognized : hybridextend_enum_font_sizes();
	$value_check = preg_replace('/px/','', $value);
	if ( in_array( (int) $value_check, $recognized ) ) {
		return $value;
	}
	//return apply_filters( 'hybridextend_sanitize_default_font_size', $recognized ); // bug:returns an array. Example case: default is set to 15px, and custom typography only has 6 to 13px. On save, an array ($recognized) gets stores instead of string.
	return apply_filters( 'hybridextend_sanitize_default_font_size', '', $recognized );
}
endif;
add_filter( 'hybridextend_sanitize_font_size', 'hybridextend_sanitize_font_size', 10, 2 );

/**
 * Sanitization for font style
 */
if ( !function_exists( 'hybridextend_sanitize_font_style' ) ):
function hybridextend_sanitize_font_style( $value, $recognized = array() ) {
	$recognized = ( is_array( $recognized ) && !empty( $recognized ) ) ? $recognized : hybridextend_enum_font_styles();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'hybridextend_sanitize_default_font_style', current( $recognized ) );
}
endif;
add_filter( 'hybridextend_sanitize_font_style', 'hybridextend_sanitize_font_style', 10, 2 );