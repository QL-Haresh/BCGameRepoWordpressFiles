<?php
/**
 * Functions and Class for handeling and manipulating hexa colors.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Validate & Sanitize if a given string is a color formatted in hexidecimal notation?
 *
 * @since 1.0.0
 * @access public
 * @param string $hex Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @param bool $validate_only Return validation results only (bool true|false).
 *                            If false, sanitized hexa value is returned (with '#' prepended).
 * @return bool|string
 */
function hybridext_color_santize_hex( $hex, $validate_only = false ) {
	if ( !is_string( $hex ) )
		return false;
	$hex = trim( $hex );

	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	}
	elseif ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}

	$hex = '#' . $hex;

	/* Regex match. */
	//if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
	/* Regex match for 3 or 6 digit hex */
	if ( 0 === preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $hex ) ) {
		return false;
	} else {
		if ( $validate_only ) {
			return true;
		} else {
			if ( intval( strlen( $hex ) ) == 4 )
				return $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2] . $hex[3] . $hex[3];
			elseif ( intval( strlen( $hex ) ) == 7 )
				return $hex;
			else
				return false;
		}
	}
}

/**
 * Converts a hex color to RGB.  Returns the RGB values as an array.
 *
 * @since 1.0.0
 * @access public
 * @param string $hex
 * @return array
 */
function hybridext_hex_to_rgb( $hex ) {

	// Remove "#" if it was added
	$color = trim( $hex, '#' );

	// Return empty array if invalid value was sent
	if ( ! ( 3 === strlen( $color ) ) && ! ( 6 === strlen( $color ) ) ) {
		return array();
	}

	// If the color is three characters, convert it to six.
	if ( 3 === strlen( $color ) ) {
		$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
	}

	// Get the red, green, and blue values
	$red   = hexdec( $color[0] . $color[1] );
	$green = hexdec( $color[2] . $color[3] );
	$blue  = hexdec( $color[4] . $color[5] );

	// Return the RGB colors as an array
	return array( 'r' => $red, 'g' => $green, 'b' => $blue );
}

/**
 * Frontend Function for Lightening a color
 *
 * @since 1.0.0
 * @access public
 * @param string $color Hexa color
 * @param int $intensity intensity of operation
 * @return string Hexa Color
 */
function hybridext_color_lighten( $color, $intensity = 30 ) {
	return hybridext_color( $color, 'lighten', $intensity );
}

/**
 * Frontend Function for Darkening a color
 *
 * @since 1.0.0
 * @access public
 * @param string $color Hexa color
 * @param int $intensity intensity of operation
 * @return string Hexa Color
 */
function hybridext_color_darken( $color, $intensity = 30 ) {
	return hybridext_color( $color, 'darken', $intensity );
}

/**
 * Frontend Function for Increasing a color (lighten a dark color by $light_intensity and darken a light color by $dark_intensity)
 *
 * @since 1.0.0
 * @access public
 * @param string $color Hexa color
 * @param int $light_intensity intensity of lightening
 * @param int $dark_intensity intensity of darkening. If empty, $light_intensity is used.
 * @return string Hexa Color
 */
function hybridext_color_increase( $color, $light_intensity = 30, $dark_intensity = '' ) {
	return hybridext_color( $color, 'increase', $light_intensity, $dark_intensity );
}

/**
 * Frontend Function for Decreasing a color (darken a dark color by $dark_intensity and lighten a light color by $light_intensity)
 *
 * @since 1.0.0
 * @access public
 * @param string $color Hexa color
 * @param int $dark_intensity intensity of darkening.
 * @param int $light_intensity intensity of lightening. If empty, $dark_intensity is used.
 * @return string Hexa Color
 */
function hybridext_color_decrease( $color, $dark_intensity = 30, $light_intensity = '' ) {
	return hybridext_color( $color, 'decrease', $dark_intensity, $light_intensity );
}

/**
 * Interface Function to the color manipulation class
 *
 * @since 1.0.0
 * @access public
 * @param string $color Hexa color
 * @param string $operation Operation to perform: increase | decrease | lighten | darken
 * @param int $intensity1 intensity of operation
 * @param int $intensity2 (Optional) intensity of second operation in case of 'increase' or 'decrease'
 * @return string Hexa Color
 */
function hybridext_color( $color, $operation, $intensity1, $intensity2 = 0 ) {
	static $hybridext_color = null; // cache

	if ( $hybridext_color === null ) {
		$hybridext_color = new HybridExtend_Color();
	}

	switch( $operation ) {
		case 'lighten' :
			$color = $hybridext_color->lighten( $color, $intensity1 );
			break;
		case 'darken' :
			$color = $hybridext_color->darken( $color, $intensity1 );
			break;
		case 'increase' :
			$intensity2 = ( empty( $intensity2 ) ) ? $intensity1 : $intensity2;
			$color = $hybridext_color->increase( $color, $intensity1, $intensity2 );
			break;
		case 'decrease' :
			$intensity2 = ( empty( $intensity2 ) ) ? $intensity1 : $intensity2;
			$color = $hybridext_color->decrease( $color, $intensity1, $intensity2 );
			break;
		case 'isdark' :
			return $hybridext_color->isdark( $color );
			break;
	}

	return $color;

}

/**
 * The Main Color Manipulation Class
 * 
 * @since 1.0.0
 * @access public
 */
class HybridExtend_Color {

	/**
	 * Empty Construct
	 */
	public function __construct() {}

	/**
	 * Sanitize Intensity
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string|int $intensity 
	 * @return int
	 */
	function sanitize_intensity( $intensity ) {
		if ( intval( $intensity ) >= 0 && intval( $intensity ) <= 100 )
			return $intensity;
		elseif ( intval( $intensity ) > 100 )
			return 100;
		else
			return 0;
	}

	/**
	 * Lighten a hexa color value
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string $hex - hex color value to be manipulated
	 * @param int $intensity
	 * @return string
	 */
	public function lighten( $hex, $intensity ) {
		$hex = hybridext_color_santize_hex( $hex ); // Returns sanitized color with '#'
		if ( empty( $hex ) )
			return null;
		$intensity = $this->sanitize_intensity( $intensity );

		$new_hex = '';
		$decs['R'] = hexdec( $hex[1] . $hex[2] );
		$decs['G'] = hexdec( $hex[3] . $hex[4] );
		$decs['B'] = hexdec( $hex[5] . $hex[6] );

		foreach ( $decs as $i => $dec ) {
			$new_decimal = $dec + round( ( ( 255 - $dec ) / 100 ) * $intensity );
			$new_hex_component = dechex( $new_decimal ); 
			if ( strlen( $new_hex_component) < 2 ) { 
				$new_hex_component = "0" . $new_hex_component;
			} 
			$new_hex .= $new_hex_component; 
		}

		return '#' . $new_hex;
	}

	/**
	 * Darken a hexa color value
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string $hex - hex color value to be manipulated
	 * @param int $intensity
	 * @return string
	 */
	public function darken( $hex, $intensity ) {
		$hex = hybridext_color_santize_hex( $hex ); // Returns sanitized color with '#'
		if ( empty( $hex ) )
			return null;
		$intensity = $this->sanitize_intensity( $intensity );

		$new_hex = '';
		$decs['R'] = hexdec( $hex[1] . $hex[2] );
		$decs['G'] = hexdec( $hex[3] . $hex[4] );
		$decs['B'] = hexdec( $hex[5] . $hex[6] );

		foreach ( $decs as $i => $dec ) {
			$new_decimal = $dec - round( ( $dec / 100 ) * $intensity );
			$new_hex_component = dechex( $new_decimal );
			if ( strlen( $new_hex_component ) < 2 ) {
				$new_hex_component = "0" . $new_hex_component;
			}
			$new_hex .= $new_hex_component;
		}

		return '#' . $new_hex;
	}

	/**
	 * Check if a hexa color value is a 'dark' color
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string $hex - hex color value to be manipulated
	 * @return bool
	 */
	public function isdark( $hex ) {
		$hex = hybridext_color_santize_hex( $hex ); // Returns sanitized color with '#'
		if ( empty( $hex ) )
			return null;

		$redhex  = substr( $hex, 1, 2 );
		$greenhex = substr( $hex, 3, 2 );
		$bluehex = substr( $hex, 5, 2 );

		// $var_r, $var_g and $var_b are the three decimal fractions to be input to our RGB-to-HSL conversion routine
		$var_r = ( hexdec( $redhex) ) / 255;
		$var_g = ( hexdec( $greenhex) ) / 255;
		$var_b = ( hexdec( $bluehex) ) / 255;

		// Input is $var_r, $var_g and $var_b from above
		// Output is HSL equivalent as $h, $s and $l — these are expressed as fractions of 1, like the input values
		$var_min = min( $var_r, $var_g, $var_b );
		$var_max = max( $var_r, $var_g, $var_b );
		$del_max = $var_max - $var_min;

		$l = ( $var_max + $var_min ) / 2;

		if ( $del_max == 0 ) {
			$h = 0;
			$s = 0;
		} else {
			if ( $l < 0.5 ){
				$s = $del_max / ( $var_max + $var_min );
			} else {
				$s = $del_max / ( 2 - $var_max - $var_min );
			};

			$del_r = ((( $var_max - $var_r) / 6) + ( $del_max / 2)) / $del_max;
			$del_g = ((( $var_max - $var_g) / 6) + ( $del_max / 2)) / $del_max;
			$del_b = ((( $var_max - $var_b) / 6) + ( $del_max / 2)) / $del_max;

			if ( $var_r == $var_max ) {
				$h = $del_b - $del_g;
			} elseif ( $var_g == $var_max ) {
				$h = ( 1 / 3 ) + $del_r - $del_b;
			} elseif ( $var_b == $var_max ){
				$h = ( 2 / 3 ) + $del_g - $del_r;
			};

			if ( $h < 0 ) {
				$h += 1;
			};
			if ( $h > 1 ) {
				$h -= 1;
			};
		};

		//So now we have the colour as an HSL value, in the variables $h, $s and $l. These three output variables are again held as fractions of 1 at this stage, rather than as degrees and percentages. So e.g., cyan (180° 100% 50%) would come out as $h = 0.5, $s = 1, and $l =  0.5.
		if ( $l < 0.5 ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Increase a hexa color value
	 * Lightens the color if it is dark and vica versa
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string $hex - hex color value to be manipulated
	 * @param int $factor1 - intensity factor for lightening
	 * @param int $factor2 - intensity factor for darkening (optional: if absent, $factor 1 is used)
	 * @return string
	 */
	public function increase( $hex, $factor1, $factor2 ) {
		if ( $this->isdark( $hex ) )
			$output = $this->lighten( $hex, $factor1 );
		else
			$output = $this->darken( $hex, $factor2 );
		return $output;
	}

	/**
	 * Decreases a hexa color value
	 * Darkens the color if it is dark and vica versa
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string $hex - hex color value to be manipulated
	 * @param int $factor1 - intensity factor for darkening
	 * @param int $factor2 - intensity factor for lightening (optional: if absent, $factor 1 is used)
	 * @return string
	 */
	public function decrease( $hex, $factor1, $factor2 ) {
		if ( $this->isdark( $hex ) )
			$output = $this->darken( $hex, $factor1 );
		else
			$output = $this->lighten( $hex, $factor2 );
		return $output;
	}

	/**
	 * Test
	 */
	// public function test(){
	// 	$color = hybridext_color_lighten( '000', 100 );
	// 	var_dump( '000 ' . $color);
	// 	$color = hybridext_color_darken( 'fff', 100 );
	// 	var_dump( 'fff ' . $color);
	// 	$color = hybridext_color_lighten( '000', 0 );
	// 	var_dump( '000 ' . $color);
	// 	$color = hybridext_color_darken( 'fff', 0 );
	// 	var_dump( 'fff ' . $color);
	// 	$color = hybridext_color_lighten( '000', 200 );
	// 	var_dump( '000 ' . $color);
	// 	$color = hybridext_color_darken( 'fff', 200 );
	// 	var_dump( 'fff ' . $color);
	// 	$color = hybridext_color_increase( '#000' );
	// 	var_dump( '000 ' . $color);
	// 	$color = hybridext_color_increase( '#fff' );
	// 	var_dump( 'fff ' . $color);
	// 	$color = hybridext_color_decrease( '#000' );
	// 	var_dump( '000 ' . $color);
	// 	$color = hybridext_color_decrease( '#fff' );
	// 	var_dump( 'fff ' . $color);
	// }

}