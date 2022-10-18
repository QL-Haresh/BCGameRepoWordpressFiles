<?php
/**
 * Functions for sanitizing and building CSS styles to be printed.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Wrapper function to create general CSS style
 *
 * @since 2.0.0
 * @access public
 * @param array $args Arguments array
 *                    'selector'  => html class/id/tag
 *                    'property'  => string: name of the css property
 *                                   array: array of $property_name => $value, $idtag, $important
 *                    'value'     => value of the css property
 *                                   (redundant if $property is an array)
 *                    'idtag'     => setting id used in wp admin (may be used in live preview js)
 *                                   used for fetching background and typography settings
 *                                   (redundant if $property is an array)
 *                    'media'     => media-query
 *                    'important' => (redundant if $property is an array)
 * @return void
 */
function hybridextend_add_css_rule( $args ) {

	global $hybridextend_style_builder;

	extract( wp_parse_args( $args, array(
		'selector'         => '',
		'property'         => '',
		'value'            => '',
		'idtag'            => '',
		'media'            => '',
		'important'        => false,
		'typography_reset' => false,
	) ) );

	if ( empty( $selector ) || empty( $property) )
		return;

	if ( !is_array( $property ) ) {

		$hybridextend_style_builder->add( $selector, $property, $value, $idtag, $media, $important, $typography_reset );

	} else {

		foreach ( $property as $addproperty => $prop_value ) {
			if ( !is_array( $prop_value ) && !empty( $prop_value ) ) {
				$addvalue = $prop_value;
				$addidtag = $addimportant = $addreset = '';
			} else {
				$addvalue     = ( empty( $prop_value[0] ) ) ? '' : $prop_value[0];
				$addidtag     = ( empty( $prop_value[1] ) ) ? '' : $prop_value[1];
				$addimportant = ( empty( $prop_value[2] ) ) ? '' : $prop_value[2];
				$addreset     = ( empty( $prop_value[3] ) ) ? '' : $prop_value[3];
			}
			$hybridextend_style_builder->add( $selector, $addproperty, $addvalue, $addidtag, $media, $addimportant, $addreset );
		}

	}
}

/**
 * A class of helper functions to create styles
 * 
 * @since 2.0.0
 */
class HybridExtend_Style_Builder {

	/**
	 * The one instance of HybridExtend_Customize.
	 *
	 * @since 2.0.0
	 * @access private
	 * @var HybridExtend_Customize The one instance for the singleton.
	 */
	private static $instance;

	/**
	 * The array for storing css rules.
	 *
	 * @since 2.0.0
	 * @access public
	 * @var array Holds the css rules array.
	 */
	public $cssrules = array();

	/**
	 * The array for storing media specific css rules.
	 *
	 * @since 2.0.0
	 * @access public
	 * @var array Holds the media specific css rules array.
	 */
	public $mediarules = array();

	/**
	 * The string with rendered css rules.
	 *
	 * @since 2.0.0
	 * @access public
	 * @var array Holds the media specific css rules array.
	 */
	public $dynamiccss = '';

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * Singleton from outside of this class.
	 *
	 * @since 2.0.0
	 * @access protected
	 */
	protected function __construct() {

		/**
		 * Hook into 'wp_enqueue_scripts' as 'wp_add_inline_style()' requires stylesheet $handle to be
		 * already registered. Main stylesheet with handle 'style' is registered by the framework via
		 * 'wp_enqueue_scripts' hook at priority 0
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'build_css' ), 99 );

	}

	/**
	 * Create CSS styles from an array.
	 *
	 * @since 2.0.0
	 * @access public
	 * @return void
	 */
	function build_css(){

		/** Allow hook for themes to add css rules **/

		do_action( 'hybridextend_dynamic_cssrules' );

		/** Build Inline CSS **/

		$css = '';
		if ( defined( 'HYBRIDEXTEND_DEBUG' ) )
			$loadminified = ( HYBRIDEXTEND_DEBUG ) ? false : true;
		else
			$loadminified = hoot_get_mod( 'load_minified', 0 );
		if ( !$loadminified ) {
			$n = "\n";
			$t = "\t";
		} else {
			$n = $t = '';
		}

		if ( !empty( $this->cssrules ) ) {
			$css .= $this->build_css_string( $this->cssrules, $n, $t );
		}

		if ( !empty( $this->mediarules ) ) {
			foreach ( $this->mediarules as $mediaquery => $mediarules ) {
				$css .= $n . '@media ' . $mediaquery . '{';
				$css .= $this->build_css_string( $mediarules, $n, $t ) . $n;
				$css .= '}';
			}
		}

		/** Allow CSS to be modified **/

		$this->dynamiccss = wp_strip_all_tags( apply_filters( 'hybridextend_dynamic_css', $css ) );

		/** Print CSS **/

		$handle = ( is_child_theme() ) ? 'hybridextend-template-style' : 'hybridextend-style';
		if ( !empty( $this->dynamiccss ) ) {
			wp_add_inline_style( $handle, $this->dynamiccss );
		}

	}

	/**
	 * Create CSS string
	 *
	 * @since 2.0.0
	 * @access public
	 * @param array $cssrules
	 * @param string $n Line ending
	 * @param string $t Starting Tab
	 * @return string
	 */
	function build_css_string( $cssrules, $n = '', $t = '' ){

		$css = '';

		foreach ( $cssrules as $selector => $rules ) {
			if ( is_array( $rules ) ) {
				$css .= $n . $selector . ' {';
				foreach ( $rules as $property => $value ) {
					if ( !empty( $value['value'] ) ) {
						$important = ( !empty( $value['important'] ) ) ? ' !important' : '';
						$css .= $n . $t . $property . ': ' . $value['value'] . $important . ';';
					}
				}
				$css .= $n . '} ';
			}
		}

		return $css;
	}

	/**
	 * Add CSS styles to cssrules array.
	 * The entire property array is replaced for a selector
	 * Example: Changing only color['value'] is not possible. The entire 'color'
	 *          array (value, important, idtag) will replace the old 'color' array if exists.
	 *
	 * @since 2.0.0
	 * @access public
	 * @param string $selector html class/id/tag
	 * @param string $property name of the css property
	 * @param string $value value of the css property
	 * @param string $idtag setting id used in wp admin (may be used in live preview js)
	 *                      used for fetching background and typography settings
	 * @param string $media media-query
	 * @param bool|string $important
	 * @param bool $typography_reset Used for 'typography' property
	 * @return void
	 */
	function add( $selector, $property, $value = '', $idtag = '', $media = '', $important = false, $typography_reset = false ){
		$merge = $this->css_rule_sanitized_array( $property, $value, $idtag, $important, $typography_reset );
		if ( !empty( $merge ) ) { // Sanitization passed

			if ( !empty( $media ) ) {
				if ( empty( $this->mediarules[$media][$selector] ) )
					$this->mediarules[$media][$selector] = array();
				$this->mediarules[$media][$selector] = array_merge( $this->mediarules[$media][$selector], $merge );
			} else {
				if ( empty( $this->cssrules[$selector] ) )
					$this->cssrules[$selector] = array();
				$this->cssrules[$selector] = array_merge( $this->cssrules[$selector], $merge );
			}

		}
	}

	/**
	 * Get CSS styles from cssrules array.
	 *
	 * @since 2.0.0
	 * @access public
	 * @return array|false
	 */
	function get( $selector = '' ){
		if ( !empty( $selector ) ) {
			if ( isset( $this->cssrules[$selector] ) )
				return $this->cssrules[$selector];
			else
				return false;
		} else
			return $this->cssrules;
	}

	/**
	 * Get CSS styles from mediarules array.
	 *
	 * @since 2.0.0
	 * @access public
	 * @return array|false
	 */
	function get_media( $media = '', $selector = '' ){
		if ( !empty( $media ) ) {
			if ( isset( $this->mediarules[$media] ) ) {
				if ( !empty( $selector ) ) {
					if ( isset( $this->mediarules[$media][$selector] ) )
						return $this->mediarules[$media][$selector];
					else
						return false;
				} else
					return $this->mediarules[$media];
			} else
				return false;
		} else {
			return $this->mediarules;
		}
	}

	/**
	 * Remove CSS styles from cssrules array.
	 *
	 * @since 2.0.0
	 * @access public
	 * @return string
	 */
	function remove( $selectors ){
		if ( is_array( $selectors ) ) {
			foreach ( $selectors as $selector ) {
				if ( isset( $this->cssrules[$selector] ) )
					unset( $this->cssrules[$selector] );
			}
		} elseif ( isset( $this->cssrules[$selectors] ) ) {
			unset( $this->cssrules[$selectors] );
		}
	}

	/**
	 * Remove CSS styles from mediarules array.
	 *
	 * @since 2.0.0
	 * @access public
	 * @return string
	 */
	function remove_media( $media, $selectors ){
		if ( isset( $this->mediarules[$media] ) ) {
			if ( is_array( $selectors ) ) {
				foreach ( $selectors as $selector ) {
					if ( isset( $this->mediarules[$media][$selector] ) )
						unset( $this->mediarules[$media][$selector] );
				}
			} elseif ( isset( $this->mediarules[$media][$selectors] ) ) {
				unset( $this->mediarules[$media][$selectors] );
			}
		}
	}

	/**
	 * Remove CSS styles from cssrules array.
	 *
	 * @since 2.0.0
	 * @access public
	 * @return string
	 */
	function remove_style( $selector, $property ){
		if ( !empty( $this->cssrules[$selector] ) && isset( $this->cssrules[$selector][$property] ) )
			unset( $this->cssrules[$selector][$property] );
	}

	/**
	 * Remove CSS styles from mediarules array.
	 *
	 * @since 2.0.0
	 * @access public
	 * @return string
	 */
	function remove_media_style( $media, $selector, $property ){
		if ( isset( $this->mediarules[$media] ) && !empty( $this->mediarules[$media][$selector] ) && isset( $this->mediarules[$media][$selector][$property] ) )
			unset( $this->mediarules[$media][$selector][$property] );
	}

	/**
	 * Create general CSS style
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string $property name of the css property
	 * @param string $value value of the css property
	 * @param string $idtag setting id used in wp admin (may be used in live preview js)
	 *                      used for fetching background and typography settings
	 * @param bool|string $important
	 * @param bool $typography_reset Used for 'typography' property
	 * @return mixed empty if sanitization failed, else the sanitized property array
	 */
	function css_rule_sanitized_array( $property, $value = '', $idtag = '', $important = false, $typography_reset = false ) {

		if ( empty( $property ) )
			return '';
		if ( $property == 'background' || $property == 'font' || $property == 'typography' ) {
			if ( empty( $value ) && empty( $idtag ) ) return '';
		} else {
			if ( empty( $value ) ) return '';
		}

		/** Load Sanitization functions if not loaded already (for frontend) **/
		if ( !function_exists( 'hybridextend_sanitize_enum' ) )
			require_once( HYBRIDEXTEND_DIR . 'includes/sanitization.php' );

		/** Sanitize CSS values **/
		if ( $value != 'inherit' ) : switch( $property ):

			case 'color':
			case 'background-color':
			case 'border-color':
			case 'border-right-color':
			case 'border-bottom-color':
			case 'border-top-color':
			case 'border-left-color':
				if ( 'none' == $value || 'transparent' == $value )
					$value = 'transparent';
				else
					// sanitize color. hybridextend_sanitize_color() will return null if $value is not a formatted hex color
					$value = hybridextend_sanitize_color( $value );
				break;

			case 'background':
				if ( !empty( $value ) ) {
					if ( 'none' == $value || 'transparent' == $value ) {
						$value = 'none';
					} else {
						// sanitize for background color. hybridextend_sanitize_color() will return null if $value is not a formatted hex color
						$value = hybridextend_sanitize_color( $value );
					}
				} elseif ( !empty( $idtag ) ) {
					// use the background function for multiple background properties
					return $this->background( $idtag, $important );
				}
				break;

			case 'background-image':
				$value = 'url("' . esc_url( $value ) . '")';
				break;

			case 'background-repeat':
				$recognized = hybridextend_enum_background_repeat();
				$value = array_key_exists( $value, $recognized ) ? $value : '';
				break;

			case 'background-position':
				$recognized = hybridextend_enum_background_position();
				$value = array_key_exists( $value, $recognized ) ? $value : '';
				break;

			case 'background-attachment':
				$recognized = hybridextend_enum_background_attachment();
				$value = array_key_exists( $value, $recognized ) ? $value : '';
				break;

			case 'box-shadow':
			case '-moz-box-shadow':
			case '-webkit-box-shadow':
				$value = esc_attr( $value );
				break;

			case 'typography':
			case 'font':
				if ( !empty( $value ) ) {
					$property = 'font-family';
					$recognized = hybridextend_enum_font_faces();
					$value = stripslashes( $value );
					$value = array_key_exists( $value, $recognized ) ? $value : '';
				} elseif ( !empty( $idtag ) ) {
					// use the typography function for multiple font properties
					return $this->typography( $idtag, $important, $typography_reset );
				}
				break;

			case 'font-family':
				$recognized = hybridextend_enum_font_faces();
				$value = stripslashes( $value );
				$value = array_key_exists( $value, $recognized ) ? $value : '';
				break;

			case 'font-style':
				$recognized = array( 'inherit', 'initial', 'italic', 'normal', 'oblique' );
				$value = in_array( $value, $recognized ) ? $value : '';
				break;

			case 'font-weight':
				$value_check = intval( $value );
				if ( !empty( $value_check ) ) {
					// for numerical weights like 300, 600 etc.
					$value = $value_check;
				} else {
					// for strings like 'bold', 'light', 'lighter' etc.
					$recognized = array( 'bold', 'bolder', 'inherit', 'initial', 'lighter', 'normal' );
					$value = in_array( $value, $recognized ) ? $value : '';
				}
				break;

			case 'text-decoration':
				$recognized = array( 'blink', 'inherit', 'initial', 'line-through', 'overline', 'underline' );
				$value = in_array( $value, $recognized ) ? $value : '';
				break;

			case 'text-transform':
				$recognized = array( 'capitalize', 'inherit', 'initial', 'lowercase', 'none', 'uppercase' );
				$value = in_array( $value, $recognized ) ? $value : '';
				break;

			case 'font-size':
			case 'padding':
			case 'padding-right':
			case 'padding-bottom':
			case 'padding-left':
			case 'padding-top':
			case 'margin':
			case 'margin-right':
			case 'margin-bottom':
			case 'margin-left':
			case 'margin-top':
			case 'height':
			case 'max-height':
			case 'min-height':
			case 'width':
			case 'max-width':
			case 'min-width':
				$value_check = preg_replace('/px|em|rem/','', $value);
				$value_check = intval( $value_check );
				$value = ( !empty( $value_check ) || '0' === $value_check || 0 === $value_check ) ? $value : '';
				break;

			case 'opacity':
				$value_check = intval( $value );
				$value = ( !empty( $value_check ) || '0' === $value_check || 0 === $value_check ) ? $value : '';
				break;

		endswitch; endif;

		// Allow custom sanitization by child themes
		$value = apply_filters( 'hybridextend_style_builder_css_rule_sanitized_array', $value, $property );

		/** Return **/
		if ( empty( $value ) ) {
			// if $value is empty => failed sanitization checks
			return '';
		} else {
			return array(
				$property => array(
					'value'     => $value,
					'important' => $important,
					'idtag'     => $idtag,
				),
			);
		}

	}

	/**
	 * Create CSS styles from a background type.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string $idtag
	 * @param bool|string $important
	 * @return mixed empty if sanitization failed, else array of sanitized properties arrays
	 */
	function background( $idtag, $important = false ) {

		if ( empty( $idtag ) || !is_string( $idtag ) )
			return '';

		/** Variables **/
		$background = array();
		$properties = array( 'color', 'type', 'pattern', 'image', 'repeat', 'position', 'attachment' );

		foreach ( $properties as $property )
			$background[ $property ] = hoot_get_mod( "{$idtag}-{$property}" );

		return $this->backgroundarray( $background,  $idtag, $important );
	}

	/**
	 * Create CSS styles from a background type.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param array $background background values as an array
	 * @param string $idtag
	 * @param bool|string $important
	 * @return mixed empty if sanitization failed, else array of sanitized properties arrays
	 */
	function backgroundarray( $background, $idtag = '', $important = false ) {

		if ( empty( $idtag ) || !is_string( $idtag ) )
			$idtag = '';

		/** Variables **/
		$output = array();
		$properties = array( 'color', 'type', 'pattern', 'image', 'repeat', 'position', 'attachment' );

		$background_defaults = array(
			'color' => '',
			'type' => 'predefined',
			'pattern' => 0,
			'image' => '',
			'repeat' => '',
			'position' => '',
			'attachment' => '',
		);
		$background = wp_parse_args( $background, $background_defaults );
		extract( $background, EXTR_SKIP );

		/** Load Sanitization functions if not loaded already (for frontend) **/
		if ( !function_exists( 'hybridextend_sanitize_enum' ) )
			require_once( HYBRIDEXTEND_DIR . 'includes/sanitization.php' );

		/** Create CSS Rules **/
		if ( !empty( $color ) ) {
			$output['color'] = $this->css_rule_sanitized_array( 'background-color', $color, $idtag . '-color', $important );
		}

		if ( 'custom' == $type ) {
			if ( !empty( $image ) ) {

				$output['image'] = $this->css_rule_sanitized_array( 'background-image', $image, $idtag . '-image', $important );

				if ( !empty( $repeat ) )
					$output['repeat'] = $this->css_rule_sanitized_array( 'background-repeat', $repeat, $idtag . '-repeat', $important );

				if ( !empty( $position ) ) 
					$output['position'] = $this->css_rule_sanitized_array( 'background-position', $position, $idtag . '-position', $important );

				if ( !empty( $attachment ) )
					$output['attachment'] = $this->css_rule_sanitized_array( 'background-attachment', $attachment, $idtag . '-attachment', $important );

			}
		}
		else { // 'predefined' == $type
			if ( !empty( $pattern ) ) { // skip if pattern = 0 (i.e. user has selected 'No Pattern')

				$recognized = hybridextend_enum_background_pattern();
				if ( array_key_exists( $pattern, $recognized ) ) {
					$background_image = HYBRID_PARENT_URI . $pattern;
					$output['image'] = $this->css_rule_sanitized_array( 'background-image', $background_image, $idtag . '-pattern', $important );
					$output['repeat'] = $this->css_rule_sanitized_array( 'background-repeat', 'repeat', '', $important );
				}

			}
		}

		$output = apply_filters( 'hybridextend_style_builder_background', $output, $idtag );

		/** Return **/
		if ( is_array( $output ) && !empty( $output ) ) {
			$return = array();
			foreach ( $output as $rule ) {
				if ( !empty( $rule ) && is_array( $rule ) )
					$return = array_merge( $return, $rule );
			}
			return $return;
		}
		return ''; // if $output is empty => failed sanitization checks

	}

	/**
	 * Create CSS styles from a typography type.
	 *
	 * @since 1.1.1
	 * @access public
	 * @param string $idtag
	 * @param bool|string $important
	 * @param bool $reset Reset earlier css rules from stylesheets etc.
	 * @return mixed empty if sanitization failed, else array of sanitized properties arrays
	 */
	function typography( $idtag, $important = false, $reset = false ) {

		if ( empty( $idtag ) || !is_string( $idtag ) )
			return;

		/** Variables **/
		$typography = array();
		$properties = array( 'size', 'face', 'style', 'color' );

		foreach ( $properties as $property )
			$typography[ $property ] = hoot_get_mod( "{$idtag}-{$property}" );

		return $this->typographyarray( $typography, $idtag, $important, $reset );
	}

	/**
	 * Create CSS styles from a typography type.
	 *
	 * @since 1.1.1
	 * @access public
	 * @param array $typography typography values as an array
	 * @param string $idtag
	 * @param bool|string $important
	 * @param bool $reset Reset earlier css rules from stylesheets etc.
	 * @return mixed empty if sanitization failed, else array of sanitized properties arrays
	 */
	function typographyarray( $typography, $idtag = '', $important = false, $reset = false ) {

		if ( empty( $idtag ) || !is_string( $idtag ) )
			$idtag = '';

		/** Variables **/
		$output = array();
		$properties = array( 'size', 'face', 'style', 'color' );

		$typography_defaults = array(
			'size' => '',
			'face' => '',
			'style' => '',
			'color' => '',
		);
		$typography = wp_parse_args( $typography, $typography_defaults );
		extract( $typography, EXTR_SKIP );

		/** Load Sanitization functions if not loaded already (for frontend) **/
		if ( !function_exists( 'hybridextend_sanitize_enum' ) )
			require_once( HYBRIDEXTEND_DIR . 'includes/sanitization.php' );

		/** Create CSS Rules **/
		if ( !empty( $color ) ) {
			$output['color'] = $this->css_rule_sanitized_array( 'color', $color, $idtag . '-color', $important );
		}

		if ( !empty( $size ) ) {
			$output['font-size'] = $this->css_rule_sanitized_array( 'font-size', $size, $idtag . '-size', $important );
		}

		if ( !empty( $face ) ) {
			$output['font-family'] = $this->css_rule_sanitized_array( 'font-family', $face, $idtag . '-face', $important );
		}

		if ( !empty( $style ) ) {
			switch ( $style ) {
				case 'italic':
					$output['font-style'] = $this->css_rule_sanitized_array( 'font-style', 'italic', $idtag . '-style', $important );
					break;
				case 'bold':
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', 'bold', $idtag . '-style', $important );
					break;
				case 'bold italic':
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', 'bold', $idtag . '-style', $important );
					$output['font-style'] = $this->css_rule_sanitized_array( 'font-style', 'italic', $idtag . '-style', $important );
					break;
				case 'semibold':
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', '600', $idtag . '-style', $important );
					break;
				case 'semibold italic':
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', '600', $idtag . '-style', $important );
					$output['font-style'] = $this->css_rule_sanitized_array( 'font-style', 'italic', $idtag . '-style', $important );
					break;
				case 'lighter':
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', 'lighter', $idtag . '-style', $important );
					break;
				case 'lighter italic':
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', 'lighter', $idtag . '-style', $important );
					$output['font-style'] = $this->css_rule_sanitized_array( 'font-style', 'italic', $idtag . '-style', $important );
					break;
				case 'uppercase':
					$output['text-transform'] = $this->css_rule_sanitized_array( 'text-transform', 'uppercase', $idtag . '-style', $important );
					break;
				case 'uppercase italic':
					$output['text-transform'] = $this->css_rule_sanitized_array( 'text-transform', 'uppercase', $idtag . '-style', $important );
					$output['font-style'] = $this->css_rule_sanitized_array( 'font-style', 'italic', $idtag . '-style', $important );
					break;
				case 'uppercase bold':
					$output['text-transform'] = $this->css_rule_sanitized_array( 'text-transform', 'uppercase', $idtag . '-style', $important );
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', 'bold', $idtag . '-style', $important );
					break;
				case 'uppercase bold italic':
					$output['text-transform'] = $this->css_rule_sanitized_array( 'text-transform', 'uppercase', $idtag . '-style', $important );
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', 'bold', $idtag . '-style', $important );
					$output['font-style'] = $this->css_rule_sanitized_array( 'font-style', 'italic', $idtag . '-style', $important );
					break;
				case 'uppercase semibold':
					$output['text-transform'] = $this->css_rule_sanitized_array( 'text-transform', 'uppercase', $idtag . '-style', $important );
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', '600', $idtag . '-style', $important );
					break;
				case 'uppercase semibold italic':
					$output['text-transform'] = $this->css_rule_sanitized_array( 'text-transform', 'uppercase', $idtag . '-style', $important );
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', '600', $idtag . '-style', $important );
					$output['font-style'] = $this->css_rule_sanitized_array( 'font-style', 'italic', $idtag . '-style', $important );
					break;
				case 'uppercase lighter':
					$output['text-transform'] = $this->css_rule_sanitized_array( 'text-transform', 'uppercase', $idtag . '-style', $important );
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', 'lighter', $idtag . '-style', $important );
					break;
				case 'uppercase lighter italic':
					$output['text-transform'] = $this->css_rule_sanitized_array( 'text-transform', 'uppercase', $idtag . '-style', $important );
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', 'lighter', $idtag . '-style', $important );
					$output['font-style'] = $this->css_rule_sanitized_array( 'font-style', 'italic', $idtag . '-style', $important );
					break;
				case 'none': default:
					break;
			}
			if ( $reset ) {
				if ( empty( $output['font-style'] ) )
					$output['font-style'] = $this->css_rule_sanitized_array( 'font-style', 'normal', $idtag . '-style', $important );
				if ( empty( $output['text-transform'] ) )
					$output['text-transform'] = $this->css_rule_sanitized_array( 'text-transform', 'none', $idtag . '-style', $important );
				if ( empty( $output['font-weight'] ) )
					$output['font-weight'] = $this->css_rule_sanitized_array( 'font-weight', 'normal', $idtag . '-style', $important );
			}
		}

		$output = apply_filters( 'hybridextend_style_builder_typography', $output, $idtag );

		/** Return **/
		if ( is_array( $output ) && !empty( $output ) ) {
			$return = array();
			foreach ( $output as $rule ) {
				if ( !empty( $rule ) && is_array( $rule ) )
					$return = array_merge( $return, $rule );
			}
			return $return;
		}
		return ''; // if $output is empty => failed sanitization checks

	}

	/**
	 * Instantiate or return the one HybridExtend_Customize instance.
	 *
	 * @since 2.0.0
	 * @access public
	 * @return HybridExtend_Customize
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

/* Initialize class */
global $hybridextend_style_builder;
$hybridextend_style_builder = HybridExtend_Style_Builder::get_instance();