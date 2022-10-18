<?php
/**
 * Interface building functions for Options page
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Hoot_Options_Interface {

	/**
	 * Generates the options fields that are used in the form.
	 * This function displays options using theme options page settings if $is_options_page is true,
	 * else, it can render option fields for any $options array with $settings value (example: meta fields).
	 *
	 * @since 1.0.0
	 * @deprecated bool $is_options_page If displaying options page
	 * @param array $options Options array
	 * @param array $settings Options values
	 * @param string $prefix Options namespace
	 */
	static function hootoptions_fields( $deprecated  = false, $options = array(), $settings = array(), $prefix = '' ) {

		if ( $deprecated  !== false ) {
			deprecated_argument( __FUNCTION__, '2.0.0' );
		}

		$prefix = ( $prefix ) ? $prefix : HYBRIDEXTEND_THEMESLUG;

		if ( empty( $options ) )
			return;

		foreach ( $options as $field ) {

			/* Make compatible with new syntax */
			if ( isset( $field['default'] ) )
				$field['std'] = $field['default'];

			/* Start interface for singular field */
			if ( !isset( $field['type'] ) ) :
				continue;

			/* Heading for Navigation */
			elseif ( $field['type'] == 'heading' ) :
				echo '<div class="section-header"><p>' . esc_html( $field['name'] ) . '</p></div>' ."\n";

			/* Subheadings for Navigation */
			elseif ( $field['type'] == 'subheading' ) :
				echo '<div class="section-header"><p>' . esc_html( $field['name'] ) . '</p></div>' ."\n";

			/* Raw HTML */
			elseif ( $field['type'] == 'html' ) :

				if ( isset( $field['std'] ) ) {
					echo $field['std'];
				};

			/* Other Field Types */
			else:
				$val = '';

				// Set default value to $val
				if ( isset( $field['std'] ) ) {
					$val = $field['std'];
				};

				// If the option is already saved, override $val
				if ( $field['type'] != 'info' ) {
					if ( isset( $settings[ $field['id'] ] ) ) {
						$val = $settings[ $field['id'] ];
						// Striping slashes of non-array options and non-code options
						if ( ! is_array($val) && 
							 ! ( $field['type'] == 'textarea' && !empty( $field['settings']['code'] ) )
							) {
							$val = stripslashes( $val );
						}
					}
				};

				// Print the field HTML
				self::hootoptions_field( $prefix, '', $field, $val, true );

			endif;

		}

	}

	/**
	 * Generates the options field that are used in the form.
	 *
	 * @since 1.0.0
	 * @param string $prefix Unique prefix slug ( In top-tier non-group fields, this is the same as HYBRIDEXTEND_THEMESLUG.
	 *                       In group fields, this gets suffixed by [groupid] )
	 * @param string $group_name string of the form ['groupid'] for prefixing to field names
	 * @param array $field Structure details array for the field to be generated
	 * @param array $val Option's value by user as stored in database
	 * @param boolean $echo echo or return the output
	 */
	static function hootoptions_field( $prefix, $group_name='', $field, $val, $echo=true ) {
		global $allowedtags;
		$options_allowedtags = $allowedtags;
		$options_allowedtags['br'] = array();
		$options_allowedtags['a']['target'] = true;
		$options_allowedtags['img']['src'] = array();

		$output = '';
		$field_prefix = $prefix . $group_name;
		$field_id_prefix = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower( $prefix . '-' . str_replace( '][', '-', $group_name ) ) ); 
		if ( !empty( $group_name ) )
			$field_id_prefix .= '-';

		// Wrap all options
		if ( $field['type'] != "info" ) {

			// Keep all ids lowercase with no spaces
			$field['id'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower( $field['id'] ) );

			$id = 'section-' . $field_id_prefix . $field['id'];

			$class = 'section';
			if ( isset( $field['type'] ) ) {
				$class .= ' section-' . $field['type'];
			}
			if ( isset( $field['class'] ) ) {
				$class .= ' ' . $field['class'];
			}

			$data = '';
			if ( isset( $field['data'] ) && is_array( $field['data'] ) ) {
				foreach ( $field['data'] as $data_name => $data_value ) {
					$data .= ' data-' . esc_attr( $data_name ) . '="' . esc_attr( $data_value ) . '"';
				}
				$data .= ' ';
			}

			$output .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '"' . $data . '>'."\n";
			if ( isset( $field['name'] ) ) {
				$output .= '<h4 class="heading">' . esc_html( $field['name'] ) . '</h4>' . "\n";
			}
			if ( ( $field['type'] != 'editor' ) && ( $field['type'] != 'group' ) ) {
				$output .= '<div class="option">' . "\n" . '<div class="controls">' . "\n";
			}
			else {
				$output .= '<div class="option">' . "\n" . '<div class="' . $field['type'] . '-controls">' . "\n";
			}
		}

		// If there is a description save it for labels
		$explain_value = $explain_append = '';
		if ( isset( $field['desc'] ) ) {
			$explain_value = $field['desc'];
		}

		// Set the placeholder if one exists
		$placeholder = '';
		if ( isset( $field['placeholder'] ) ) {
			$placeholder = ' placeholder="' . esc_attr( $field['placeholder'] ) . '"';
		}

		switch ( $field['type'] ) {

		// Basic text input
		case 'text':
			$output .= '<input id="' . esc_attr( $field_id_prefix . $field['id'] ) . '" class="hoot-of-input" name="' . esc_attr( $field_prefix . '[' . $field['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '"' . $placeholder . ' />';
			break;

		// Password input
		case 'password':
			$output .= '<input id="' . esc_attr( $field_id_prefix . $field['id'] ) . '" class="hoot-of-input" name="' . esc_attr( $field_prefix . '[' . $field['id'] . ']' ) . '" type="password" value="' . esc_attr( $val ) . '" />';
			break;

		// Textarea
		case 'textarea':
			$rows = '8';

			if ( isset( $field['settings']['rows'] ) ) {
				$custom_rows = $field['settings']['rows'];
				if ( is_numeric( $custom_rows ) ) {
					$rows = $custom_rows;
				}
			}

			$codeclass = ( isset( $field['settings']['code'] ) && true === $field['settings']['code'] ) ? ' code' : '';
			$val = ( isset( $field['settings']['code'] ) && true === $field['settings']['code'] ) ? htmlspecialchars_decode( $val ) : $val;

			$output .= '<textarea id="' . esc_attr( $field_id_prefix . $field['id'] ) . '" class="hoot-of-input' . $codeclass . '" name="' . esc_attr( $field_prefix . '[' . $field['id'] . ']' ) . '" rows="' . $rows . '"' . $placeholder . '>' . esc_textarea( $val ) . '</textarea>';
			break;

		// Select Box
		case 'select':
			$output .= '<select class="hoot-of-input" name="' . esc_attr( $field_prefix . '[' . $field['id'] . ']' ) . '" id="' . esc_attr( $field_id_prefix . $field['id'] ) . '">';

			foreach ($field['options'] as $key => $option ) {
				$esc_key = esc_attr( $key );
				$output .= '<option'. selected( $val, $esc_key, false ) .' value="' . $esc_key . '">' . esc_html( $option ) . '</option>';
			}
			$output .= '</select>';
			break;


		// Radio Box
		case "radio":
			$name = $field_prefix .'['. $field['id'] .']';
			foreach ($field['options'] as $key => $option) {
				$id = $field_id_prefix . $field['id'] .'-'. $key;
				$output .= '<input class="hoot-of-input hoot-of-radio" type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .' /><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label>';
			}
			break;

		// Image Selectors
		case "images":
			$name = $field_prefix .'['. $field['id'] .']';
			$output .= '<div class="hoot-of-radio-img-box">';
			foreach ( $field['options'] as $key => $option ) {
				$selected = '';
				if ( $val != '' && ($val == $key) ) {
					$selected = ' hoot-of-radio-img-selected';
				}
				$output .= '<input type="radio" id="' . esc_attr( $field_id_prefix . $field['id'] .'-'. $key) . '" class="hoot-of-radio-img-radio" value="' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '" '. checked( $val, $key, false ) .' />';
				$output .= '<div class="hoot-of-radio-img-label">' . esc_html( $key ) . '</div>';
				$output .= '<div class="hoot-of-radio-img-img' . $selected .'" data-selector="' . $field_id_prefix . $field['id'] .'-'. $key . '"><img src="' . esc_url( $option ) . '" alt="' . $option .'" /></div>';
				// onclick="document.getElementById(\''. esc_attr( $field_id_prefix . $field['id'] .'-'. $key) .'\').checked=true;"
			}
			$output .= '</div>';
			break;

		// Checkbox
		case "checkbox":
			$output .= '<input id="' . esc_attr( $field_id_prefix . $field['id'] ) . '" class="checkbox hoot-of-input" type="checkbox" name="' . esc_attr( $field_prefix . '[' . $field['id'] . ']' ) . '" '. checked( $val, 1, false) .' value="yes" />';
			$output .= '<label class="explain" for="' . esc_attr( $field_id_prefix . $field['id'] ) . '">' . wp_kses( $explain_value, $options_allowedtags) . '</label>';
			break;

		// Multicheck
		case "multicheck":
			foreach ($field['options'] as $key => $option) {
				$checked = '';
				$label = $option;
				$option = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));

				$id = $field_id_prefix . $field['id'] . '-'. $option;
				$name = $field_prefix . '[' . $field['id'] . '][' . $option .']';

				if ( isset($val[$option]) ) {
					$checked = checked($val[$option], 1, false);
				}

				$output .= '<input id="' . esc_attr( $id ) . '" class="checkbox hoot-of-input" type="checkbox" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
			}
			break;

		// Color picker
		case "color":
			$default_color = '';
			if ( isset( $field['std'] ) ) {
				if ( empty( $val ) )
					$val = $field['std'];
				if ( $val !=  $field['std'] )
					$default_color = ' data-default-color="' . $field['std'] . '" ';
			}
			$output .= '<input name="' . esc_attr( $field_prefix . '[' . $field['id'] . ']' ) . '" id="' . esc_attr( $field_id_prefix . $field['id'] ) . '" class="hoot-of-color"  type="text" value="' . esc_attr( $val ) . '"' . $default_color .' />';

			break;

		// Uploader
		case "upload":
			$output .= Hoot_Options::uploader( $field_id_prefix . $field['id'], $val, null, $field_prefix . '[' . $field['id'] . ']' );

			break;

		// Typography
		case 'typography':

			$font_size = $font_style = $font_face = $font_color = '';

			$typography_defaults = array(
				'size' => '',
				'face' => '',
				'style' => '',
				'color' => ''
			);

			$typography_stored = wp_parse_args( $val, $typography_defaults );

			$typography_options = array(
				'size' => hybridextend_enum_font_sizes(),
				'face' => hybridextend_enum_font_faces(),
				'style' => hybridextend_enum_font_styles(),
				'color' => true
			);

			if ( isset( $field['options'] ) ) {
				$custom_font_faces = ( isset( $field['options']['face'] ) ) ? true : false;
				$typography_options = wp_parse_args( $field['options'], $typography_options );
			} else {
				$custom_font_faces = false;
				$explain_value .= ' ' . __( 'Some Fonts do not support all styles (bold, lighter, italic) For such fonts "Normal" style will be used.', 'hybrid-core' );
			}

			// Font Size
			if ( $typography_options['size'] !== false ) {
				$font_size = '<select class="hoot-of-typography-size" name="' . esc_attr( $field_prefix . '[' . $field['id'] . '][size]' ) . '" id="' . esc_attr( $field_id_prefix . $field['id'] . '_size' ) . '">';
				$sizes = $typography_options['size'];
				foreach ( $sizes as $i ) {
					$size = $i . 'px';
					$font_size .= '<option value="' . esc_attr( $size ) . '" ' . selected( $typography_stored['size'], $size, false ) . '>' . esc_html( $size ) . '</option>';
				}
				$font_size .= '</select>';
			}

			// Font Face
			if ( $typography_options['face'] !== false ) {
				$faces = $typography_options['face'];

				if ( $custom_font_faces ) :

					$font_face = '<div class="hoot-of-typography-face-select-box">';
					$font_face .= '<select class="hoot-of-typography-face" name="' . esc_attr( $field_prefix . '[' . $field['id'] . '][face]' ) . '" id="' . esc_attr( $field_id_prefix . $field['id'] . '_face' ) . '">';
					foreach ( $faces as $key => $face ) {
						$font_face .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['face'], $key, false ) . '>' . esc_html( $face ) . '</option>';
					}
					$font_face .= '</select>';
					$font_face .= '</div>';

				else:

					$font_face = '<div class="hoot-of-typography-face-container">';
						$font_face .= '<input type="hidden" class="hoot-of-typography-face" name="' . esc_attr( $field_prefix . '[' . $field['id'] . '][face]' ) . '" id="' . esc_attr( $field_id_prefix . $field['id'] . '_face' ) . '" value="' . esc_attr( $typography_stored['face'] ) . '">';

						$font_face .= '<div class="hoot-of-typography-face-picked">';
							$font_face .= '<div class="hoot-of-typography-face-picked-text">';
								if ( isset( $faces[ $typography_stored['face'] ] ) )
									$font_face .= $faces[ $typography_stored['face'] ];
							$font_face .= '</div>';
						$font_face .= '</div>';

						$font_face .= '<div class="hoot-of-typography-face-picker-box">';
							$font_face .= '<div class="hoot-of-typography-face-picker-box-content">';
							$font_count = 0;
							foreach ( $faces as $key => $face ) {
								$selected = ( $typography_stored['face'] == $key ) ? ' selected' : '';
								$font_face .= '<div data-value="' . esc_attr( $key ) . '" class="hoot-of-typography-face-option' . $selected . '">';
									$font_face .= '<span>' . esc_html( $face ) . '</span>';
									$font_face .= '<div class="hoot-of-typography-face-option-preview" style="background-position: 0 ' . ( -30 * $font_count ) .'px;"></div>';
								$font_face .= '</div>';
								$font_count++;
							}
							$font_face .= '</div>';
						$font_face .= '</div>';

					$font_face .= '</div>';

				endif;

			}

			// Font Styles
			if ( $typography_options['style'] !== false ) {
				$font_style = '<select class="hoot-of-typography-style" name="'.$field_prefix.'['.$field['id'].'][style]" id="'. $field_id_prefix . $field['id'].'_style">';
				$styles = $typography_options['style'];
				foreach ( $styles as $key => $style ) {
					$font_style .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['style'], $key, false ) . '>'. $style .'</option>';
				}
				$font_style .= '</select>';
			}

			// Font Color
			if ( $typography_options['color'] !== false ) {
				$default_color = '';
				if ( isset($field['std']['color']) ) {
					if ( empty( $val ) )
						$val = $field['std']['color'];
					if ( $val !=  $field['std']['color'] )
						$default_color = ' data-default-color="' . $field['std']['color'] . '" ';
				}
				$font_color = '<input name="' . esc_attr( $field_prefix . '[' . $field['id'] . '][color]' ) . '" id="' . esc_attr( $field_id_prefix . $field['id'] . '_color' ) . '" class="hoot-of-color hoot-of-typography-color  type="text" value="' . esc_attr( $typography_stored['color'] ) . '"' . $default_color .' />';
			}

			// Add Output
			$output .= '<div class="hoot-of-typography">';
			$output .= $font_size . $font_face . $font_style . $font_color;
			$output .= '</div>';

			break;

		// Background
		case 'background':

			$background_defaults = array(
				'color' => '',
				'type' => 'predefined',
				'pattern' => '0',
				'image' => '',
				'repeat' => 'repeat',
				'position' => 'top center',
				'attachment'=>'scroll',
			);
			$background = wp_parse_args( $val, $background_defaults );

			$background_options = array(
				'color' => true,
				'repeat' => true,
				'position' => true,
				'attachment' => true,
			);
			if ( isset( $field['options'] ) ) {
				$background_options = wp_parse_args( $field['options'], $background_options );
			}

			// Background Color
			if ( $background_options['color'] ) :
				$default_color = '';
				if ( isset( $field['std']['color'] ) ) {
					if ( empty( $val ) )
						$val = $field['std']['color'];
					if ( $val !=  $field['std']['color'] )
						$default_color = ' data-default-color="' . $field['std']['color'] . '" ';
				}
				$output .= '<input name="' . esc_attr( $field_prefix . '[' . $field['id'] . '][color]' ) . '" id="' . esc_attr( $field_id_prefix . $field['id'] . '_color' ) . '" class="hoot-of-color hoot-of-background-color"  type="text" value="' . esc_attr( $background['color'] ) . '"' . $default_color .' />';
			endif;

			// Background Type
			$background_types = hybridextend_enum_background_type();
			foreach ( $background_types as $key => $option) {
				$key = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));
				$id = $field_id_prefix . $field['id'] .'_type-'. $key;
				$output .= '<div class="hoot-of-background hoot-of-background-type"><input class="hoot-of-input hoot-of-radio" type="radio" name="' . esc_attr( $field_prefix .'['. $field['id'] .'][type]' ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $background['type'], $key, false) .' /><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label></div>';
			}
			$output .= '<div class="clear"></div>' . "\n";

			// Predefined Pattern Block
			$output .= '<div class="show-on-select" data-selector="hoot-of-background-type">' . "\n";
			$class = ($background['type'] == 'predefined') ? '' : ' hoothide';
			$output .= '<div class="hoot-of-background-type-predefined show-on-select-block hoot-of-radio-img-box' . $class . '">';

			// Background Patterns
			$patterns = hybridextend_enum_background_pattern();
			foreach ( $patterns as $key => $option ) {
				$id = $field_id_prefix . $field['id'] .'_pattern-'. $key;
				$selected = '';
				if ( $background['pattern'] == $key ) {
					$selected = ' hoot-of-radio-img-selected';
				}
				$output .= '<input type="radio" id="' . sanitize_html_class( $id ) . '" class="hoot-of-radio-img-radio hoot-of-background hoot-of-background-pattern" value="' . esc_attr( $key ) . '" name="' . esc_attr( $field_prefix .'['. $field['id'] .'][pattern]' ) . '" '. checked( $background['pattern'], $key, false ) .' />';
				$output .= '<div class="hoot-of-radio-img-label">' . esc_html( $key ) . '</div>';
				$output .= '<div class="hoot-of-radio-img-img hoot-of-background-pattern-img' . $selected .'"><img src="' . esc_url( $option ) . '" onclick="document.getElementById(\''. sanitize_html_class( $id ) .'\').checked=true;" /></div>';
			}

			// Custom Image Block
			$output .= '</div>' . "\n";
			$class = ($background['type'] == 'custom') ? '' : ' hoothide';
			$output .= '<div class="hoot-of-background-type-custom show-on-select-block' . $class . '">';

			// Background Image
			$output .= Hoot_Options::uploader( $field_id_prefix . $field['id'], $background['image'], null, $field_prefix . '[' . $field['id'] . '][image]' );

			// Start Background Properties
			$class = 'hoot-of-background-properties';
			if ( '' == $background['image'] )
				$class .= ' hoothide';
			if ( $background_options['repeat'] || $background_options['position'] || $background_options['attachment'] )
				$output .= '<div class="' . esc_attr( $class ) . '">';

			// Background Repeat
			if ( $background_options['repeat'] ) :
				$output .= '<select class="hoot-of-background hoot-of-background-repeat" name="' . esc_attr( $field_prefix . '[' . $field['id'] . '][repeat]'  ) . '" id="' . esc_attr( $field_id_prefix . $field['id'] . '_repeat' ) . '">';
				$repeats = hybridextend_enum_background_repeat();

				foreach ($repeats as $key => $repeat) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['repeat'], $key, false ) . '>'. esc_html( $repeat ) . '</option>';
				}
				$output .= '</select>';
			endif;

			// Background Position
			if ( $background_options['position'] ) :
				$output .= '<select class="hoot-of-background hoot-of-background-position" name="' . esc_attr( $field_prefix . '[' . $field['id'] . '][position]' ) . '" id="' . esc_attr( $field_id_prefix . $field['id'] . '_position' ) . '">';
				$positions = hybridextend_enum_background_position();

				foreach ($positions as $key=>$position) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['position'], $key, false ) . '>'. esc_html( $position ) . '</option>';
				}
				$output .= '</select>';
			endif;

			// Background Attachment
			if ( $background_options['attachment'] ) :
				$output .= '<select class="hoot-of-background hoot-of-background-attachment" name="' . esc_attr( $field_prefix . '[' . $field['id'] . '][attachment]' ) . '" id="' . esc_attr( $field_id_prefix . $field['id'] . '_attachment' ) . '">';
				$attachments = hybridextend_enum_background_attachment();

				foreach ($attachments as $key => $attachment) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['attachment'], $key, false ) . '>' . esc_html( $attachment ) . '</option>';
				}
				$output .= '</select>';
			endif;

			// End Background Properties
			if ( $background_options['repeat'] || $background_options['position'] || $background_options['attachment'] )
				$output .= '</div>';

			// Custom Image Block, End show on select
			$output .= '</div>' . "\n";
			$output .= '</div>' . "\n";

			break;

		// Sort List
		case "sortlist":
			$sortlist_defaults = array();
			$order = 999;
			foreach ( $field['options'] as $key => $option ) {
				$order++;
				$keyid = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));
				$sortlist_defaults[ $keyid ] = $order . ',1';
			}
			$val = wp_parse_args( $val, $sortlist_defaults );
			$sortlist = hoot_map_sortlist( $val, $field['options'] );

			$namelist = array();
			foreach ( $field['options'] as $key => $option ) {
				$keyid = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));
				$id = $field_id_prefix . $field['id'] . '-'. $keyid;
				$name = $field_prefix . '[' . $field['id'] . '][' . $keyid .']';

				$output .= '<input id="' . esc_attr( $id ) . '-input" class="hoot-of-sortlist-input" name="' . esc_attr( $name ) . '" type="hidden" value="' . esc_attr( $val[ $keyid ] ) . '" />';

				$namelist[ $keyid ] = $option;
			}

			$order = 1;
			$output .= '<ul id="' . $field_id_prefix . $field['id'] . '" class="hoot-of-sort-list">';
			foreach ( $sortlist['order'] as $keyid => $itemval ) {
				$invisible = ( $sortlist['display'][ $keyid ] ) ? '' : ' invisible';
				$id = $field_id_prefix . $field['id'] . '-' . $keyid;
				$output .= '<li id="' . esc_attr( $id ) . '" class="hoot-of-sort-list-item ' . $invisible . '">';
				$output .= '<i class="fas fa-sort"></i>';
				$output .= '<span>' . esc_html( $namelist[ $keyid ] ) . '</span>';
				if ( isset( $field['settings']['hideable'] ) && 
					( true === $field['settings']['hideable'] || 'true' === $field['settings']['hideable'] ) )
					$output .= '<i class="visibility fas fa-eye"></i>';
				$output .= '</li>';
			}
			$output .= '</ul>';

			break;

		// Icons
		case "icon":
			$iconvalue = hybridextend_sanitize_fa( $val );

			$output .= '<input id="' . esc_attr( $field_id_prefix . $field['id'] ) . '" class="hoot-of-icon" name="' . esc_attr( $field_prefix . '[' . $field['id'] . ']' ) . '" type="hidden" value="' . esc_attr( $iconvalue ) . '" />';

			$output .= '<div id="' . esc_attr( $field_id_prefix . $field['id'] . '-icon-picked' ) . '" class="hoot-of-icon-picked"><i class="' . esc_attr( $iconvalue ) . '"></i><span>' . __( 'Select Icon', 'hybrid-core' ) . '</span></div>';

			$output .= '<div id="' . esc_attr( $field_id_prefix . $field['id'] . '-icon-picker-box' ) . '" class="hoot-of-icon-picker-box">';

				$output .= '<div class="hoot-of-icon-picker-list"><i class="fas fa-ban hoot-of-icon-none" data-value="0" data-category=""><span>' . __( 'Remove Icon', 'hybrid-core' ) . '</span></i></div>';

				$section_icons = hybridextend_enum_icons('icons');
				foreach ( hybridextend_enum_icons('sections') as $s_key => $s_title ) {
					$output .= "<h4>$s_title</h4>";
					$output .= '<div class="hoot-of-icon-picker-list">';
					foreach ( $section_icons[$s_key] as $i_key => $i_class ) {
						$selected = ( $iconvalue == $i_class ) ? 'selected' : '';
						$output .= "<i class='$i_class $selected' data-value='$i_class' data-category='$s_key'></i>";
					}
					$output .= '</div>';
				}

			$output .= '</div>';

			break;

		// Editor
		case 'editor':
			$output .= '<div class="explain">' . wp_kses( $explain_value, $options_allowedtags ) . '</div>'."\n";
			echo $output;
			$textarea_name = esc_attr( $field_prefix . '[' . $field['id'] . ']' );
			$default_editor_settings = array(
				'textarea_name' => $textarea_name,
				'media_buttons' => false,
				'tinymce' => true,
				//'tinymce' => array( 'plugins' => 'wordpress' )
			);
			$editor_settings = array();
			if ( isset( $field['settings'] ) ) {
				$editor_settings = $field['settings'];
			}
			$editor_settings = array_merge( $default_editor_settings, $editor_settings );
			wp_editor( $val, $field_id_prefix . $field['id'], $editor_settings );
			$output = '';
			break;

		// Info
		case "info":
			$id = '';
			$class = 'section';
			if ( isset( $field['id'] ) ) {
				$id = 'id="' . esc_attr( $field_id_prefix . $field['id'] ) . '" ';
			}
			if ( isset( $field['type'] ) ) {
				$class .= ' section-' . $field['type'];
			}
			if ( isset( $field['class'] ) ) {
				$class .= ' ' . $field['class'];
			}

			$output .= '<div ' . $id . 'class="' . esc_attr( $class ) . '">' . "\n";
			if ( isset($field['name']) ) {
				$output .= '<h4 class="heading">' . esc_html( $field['name'] ) . '</h4>' . "\n";
			}
			if ( isset( $field['desc'] ) ) {
				$output .= $field['desc'] . "\n";
			}
			$output .= '</div>' . "\n";
			break;

		// Groups
		case "group":
			$output .= '<div class="explain">' . wp_kses( $explain_value, $options_allowedtags ) . '</div>'."\n";

			$settings_defaults = array(
				'add_button' => __( 'Add Another Entry', 'hybrid-core' ),
				'remove_button' => __( 'Remove Entry', 'hybrid-core' ),
				'repeatable' => false,
				'sortable' => false,
				'toggleview' => true );
			if ( !empty( $field['settings']['sortable'] ) ) // check if sorting is set and true
				$settings_defaults['title'] = __( 'Group', 'hybrid-core' );
			$settings = ( isset( $field['settings'] ) ) ?
						wp_parse_args( $field['settings'], $settings_defaults ) :
						$settings_defaults;
			$parsedfield = $field;
			$parsedfield['settings'] = $settings;

			$output .= '<div class="hoot-of-group-wrap">'
						. ( ( $settings['toggleview'] ) ? '<div class="hoot-of-group-toggle-all"><i class="fas fa-toggle-on fa-toggle-off"></i></div>' : '' )
						. '<div class="hoot-of-groups'
						. ( ( $settings['repeatable'] ) ? ' repeatable' : '' )
						. ( ( $settings['sortable'] ) ? ' sortable' : '' )
						. '" data-index="' . esc_attr( $field_id_prefix . $field['id'] ) . '">';

			$val = ( is_array( $val ) ) ? $val : array( 'g0' => array() );
			foreach ( $val as $groupID => $groupVal ) {
				$output .= self::hootoptions_group( $prefix, $group_name, $groupID, $parsedfield, $groupVal, false );
			}

			$output .= '</div>'; // end hoot-of-groups

			$repeater_html = self::hootoptions_group( $prefix, $group_name, '975318642', $parsedfield, array(), false );
			$output .= '<script type="text/javascript">
				( function($){
					if (typeof window.hoot_of_helper == "undefined")
						window.hoot_of_helper = {};
					window.hoot_of_helper["' . esc_attr( $field_id_prefix . $field['id'] ) . '"] = ' . json_encode( $repeater_html ) . ';
				} )( jQuery );
			</script>';

			if ( $settings['repeatable'] ) {
				$intArray = array();
				foreach ( array_keys( $val ) as $akey )
					$intArray[] = intval( substr( $akey, 1 ) );
				$output .= '<button class="button add-group-button" data-iterator="' . max( $intArray ) . '">' . esc_html( $settings['add_button'] ) . '</button>';
			}

			$output .= '</div>';
			break;

		} // end switch

		$output .= '<div class="clear"></div>' . "\n";

		if ( $field['type'] != "info" ) {
			$output .= '</div>';
			if ( ( $field['type'] != "checkbox" ) && ( $field['type'] != "editor" ) && ( $field['type'] != "group" ) ) {
				$output .= '<div class="explain">' . wp_kses( $explain_value, $options_allowedtags) . $explain_append . '</div>'."\n";
			}
			$output .= '</div></div>'."\n";
		}

		if ( $echo )
			echo $output;
		else
			return $output;
	}

	/**
	 * Generates the group.
	 *
	 * @since 1.0.0
	 * @param string $prefix Unique prefix slug ( In top-tier non-group fields, this is the same as HYBRIDEXTEND_THEMESLUG.
	 *                       In group fields, this gets suffixed by [groupid] )
	 * @param string $group_name string of the form ['groupid'] for prefixing to field names
	 * @param numeric $groupID Group ID (iterator) for this instance
	 * @param array $field Structure details array for the field to be generated
	 * @param array $groupVal Group's value by user as stored in database
	 * @param boolean $echo echo or return the output
	 */
	static function hootoptions_group( $prefix, $group_name='', $groupID, $field, $groupVal, $echo=true ) {

		$output = '';
		$output .= '<div class="hoot-of-group" data-iteration="' . $groupID . '">';

			if ( !empty( $field['settings']['title'] ) )
				$output .= '<div class="hoot-of-group-title">'
							. ( ( $field['settings']['sortable'] ) ? '<i class="fas fa-sort"></i>' : '' )
							. esc_html( $field['settings']['title'] )
							. '<i class="fas fa-caret-down hoot-of-group-toggle"></i>'
							. '</div>';

			$output .= '<div class="hoot-of-group-content">';

				foreach ( $field['fields'] as $groupfield ) {
					$fieldval = '';
					if ( isset( $groupfield['default'] ) )
						$groupfield['std'] = $groupfield['default'];
					if ( isset( $groupfield['std'] ) )
						$fieldval = $groupfield['std'];
					// If the option is already saved, override $groupVal
					if ( $groupfield['type'] != 'info') {
						if ( isset( $groupVal[ $groupfield['id'] ] ) ) {
							$fieldval = $groupVal[ $groupfield['id'] ];
							// Striping slashes of non-array options
							if ( !is_array($fieldval) ) {
								$fieldval = stripslashes( $fieldval );
							}
						}
					}
					$instance_name = $group_name . '[' . $field['id'] . ']' . '[' . $groupID . ']';
					$output .= self::hootoptions_field( $prefix, $instance_name , $groupfield, $fieldval, false );
				}

				if ( $field['settings']['repeatable'] )
					$output .= '<button class="button remove-group-button">' . esc_html( $field['settings']['remove_button'] ) . '</button>';

			$output .= '</div>';

		$output .= '</div>';

		if ( $echo )
			echo $output;
		else
			return $output;
	}

}