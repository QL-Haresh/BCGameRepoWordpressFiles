<?php
/**
 * Hoot Options extension to build options on various non-customizer screens
 * This extension doesnt do anything on its own. Other extensions can use Options to build
 * options and avoid repetetive code.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* This file may be included multiple times by extensions. Ideally it should be included using include_once/require_once, but lets add a check anyways. */
if ( !class_exists( 'Hoot_Options' ) ) :

	/**
	 * Hoot Options sub-extension class
	 *
	 * @since 2.0.0
	 */
	class Hoot_Options {

		/**
		 * Holds the instance of this class.
		 *
		 * @since 2.0.0
		 * @access private
		 * @var object
		 */
		private static $instance;

		/**
		 * Init Hoot Options
		 *
		 * @since 2.0.0
		 */
		public function __construct() {

			/* Load functions */
			require_once HYBRIDEXTEND_PREMIUM_DIR . 'extensions/options/includes.php';

			/* Load files needed in admin */
			if ( !is_admin() )
				return;

			/* Enqueue and localize scripts */
			/* Scripts should be enqueued only when needed. The calling function must call enqueue method separatey */
			// add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		}

		/*
		 * Enqueue and localize scripts
		 *
		 * @since 2.0.0
		 */
		function enqueue( $hook = '' ) {

			/* Load Options javascript */
			$script_uri = hybridextend_locate_script( HYBRIDEXTEND_JS . 'hootoptions' );
			wp_enqueue_script( 'hoot-options-script', $script_uri, array( 'jquery', 'wp-color-picker' ), HYBRIDEXTEND_VERSION );

			/* Load Uploader javascript */
			$load = apply_filters( 'hoot_options_load_media_scripts', true, $hook );
			if ( $load ) {

				/* Enqueue WP Media */
				if ( function_exists( 'wp_enqueue_media' ) )
					wp_enqueue_media();

				/* Load Hoot Media javascript */
				$script_uri = hybridextend_locate_script( HYBRIDEXTEND_JS . 'hootoptions-media-uploader' );
				wp_enqueue_script( 'hoot-options-media-uploader', $script_uri, array( 'jquery' ), HYBRIDEXTEND_VERSION );

				/* Send data (translation strings) */
				wp_localize_script( 'hoot-options-media-uploader', 'hootoptions_l10n', array(
					'upload' => __( 'Upload', 'hybrid-core' ),
					'remove' => __( 'Remove', 'hybrid-core' )
				) );

			}

			/* Load Styles */
			wp_enqueue_style( 'wp-color-picker' );
			$style_uri = hybridextend_locate_style( HYBRIDEXTEND_CSS . 'hootoptions' );
			wp_enqueue_style( 'hootoptions', $style_uri, false,  HYBRIDEXTEND_VERSION );

		}

		/**
		 * Wrapper for Building Options Interface
		 *
		 * Parameters:
		 * @var string $_id - A token to identify this field (the name).
		 * @var string $_value - The value of the field, if present.
		 * @var string $_desc - An optional description of the field.
		 * @since 2.0.0
		 */
		static function build( $options = array(), $settings = array(), $prefix = '' ) {

			/* Load interface class */
			if ( !class_exists( 'Hoot_Options_Interface' ) )
				require_once HYBRIDEXTEND_PREMIUM_DIR . 'extensions/options/interface.php';

			/* Create interface */
			Hoot_Options_Interface::hootoptions_fields( false, $options, $settings, $prefix );

		}

		/**
		 * Validate Single Options.
		 *
		 * @since 1.1.0
		 * @param array $input Array of values inputted by user
		 * @param array $option Single option array
		 * @return NULL|array Clean array of ( 'id' => 'validated value' )
		 */
		static function validate_option( $input, $option ) {

			if ( ! isset( $option['id'] ) ) {
				return;
			}

			if ( ! isset( $option['type'] ) || $option['type'] == 'heading' || $option['type'] == 'subheading' || $option['type'] == 'info' || $option['type'] == 'html' || $option['type'] == 'import' || $option['type'] == 'export' ) {
				return;
			}

			$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = false;
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = false;
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'hybridextend_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'hybridextend_sanitize_' . $option['type'], $input[$id], $option );
				return $clean;
			}

			return;

		}

		/**
		 * Media Uploader Using the WordPress Media Library.
		 *
		 * @var string $_id - A token to identify this field (the name).
		 * @var string $_value - The value of the field, if present.
		 * @var string $_desc - An optional description of the field.
		 * @since 1.0.0
		 */
		static function uploader( $_id, $_value, $_desc = '', $_name = '' ) {

			$output = '';
			$id = '';
			$class = '';
			$int = '';
			$value = '';
			$name = '';

			$id = strip_tags( strtolower( $_id ) );

			// If a value is passed and we don't have a stored value, use the value that's passed through.
			if ( $_value != '' && $value == '' ) {
				$value = $_value;
			}

			if ($_name != '') {
				$name = $_name;
			}
			else {
				$name = HYBRIDEXTEND_THEMESLUG . '[' . $id . ']';
			}

			if ( $value ) {
				$class = ' has-file';
			}
			$output .= '<input id="' . esc_attr( $id ) . '" class="upload' . $class . '" type="text" name="' . esc_attr( $name ) . '" value="' . $value . '" placeholder="' . __('No file chosen', 'hybrid-core') .'" />' . "\n";
			if ( function_exists( 'wp_enqueue_media' ) ) {
				if ( ( $value == '' ) ) {
					$output .= '<input id="upload-' . esc_attr( $id ) . '" class="upload-button button" type="button" value="' . __( 'Upload', 'hybrid-core' ) . '" />' . "\n";
				} else {
					$output .= '<input id="remove-' . esc_attr( $id ) . '" class="remove-file button" type="button" value="' . __( 'Remove', 'hybrid-core' ) . '" />' . "\n";
				}
			} else {
				$output .= '<p><i>' . __( 'Upgrade your version of WordPress for full media support.', 'hybrid-core' ) . '</i></p>';
			}

			if ( $_desc != '' ) {
				$output .= '<span class="hoot-of-metabox-desc">' . $_desc . '</span>' . "\n";
			}

			$output .= '<div class="screenshot" id="' . $id . '-image">' . "\n";

			if ( $value != '' ) {
				$remove = '<a class="remove-image">Remove</a>';
				$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
				if ( $image ) {
					$output .= '<img src="' . $value . '" alt="" />' . $remove;
				} else {
					$parts = explode( "/", $value );
					for( $i = 0; $i < sizeof( $parts ); ++$i ) {
						$title = $parts[$i];
					}

					// No output preview if it's not an image.
					$output .= '';

					// Standard generic output if it's not an image.
					$title = __( 'View File', 'hybrid-core' );
					$output .= '<div class="no-image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">'.$title.'</a></span></div>';
				}
			}
			$output .= '</div>' . "\n";
			return $output;
		}

		/**
		 * Returns the instance.
		 *
		 * @since 2.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

	}

	/* Initialize class */
	global $hoot_options;
	$hoot_options = Hoot_Options::get_instance();

endif;