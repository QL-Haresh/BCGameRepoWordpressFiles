<?php
/**
 * Hoot Shortcodes
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Shortcodes class. This wraps everything up nicely.
 *
 * @since 1.1.0
 */
class Hoot_Shortcodes {

	/**
	 * Holds the instance of this class.
	 *
	 * @since 1.1.0
	 * @access private
	 * @var object
	 */
	private static $instance;

	/**
	 * Holds the list of available shortcodes (and their settings+options).
	 *
	 * @since 1.1.0
	 * @access private
	 * @var object
	 */
	private $shortcodes;

	/**
	 * Setup Shortcodes
	 * 
	 * @since 1.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Include Shortcode Helper Functions */
		require_once( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/shortcodes/helpers.php' );

		/* Get shortcodes array */
		$this->shortcodes = $this->get_shortcodes_array();

		if ( is_admin() ) :

			/* Load Shortcode Generator */
			require_once( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/shortcodes/admin.php' );
			new Hoot_Shortcodes_Admin();

		else :

			/* Add Shortcodes to WordPress */
			foreach ( $this->shortcodes as $name => $settings ) {
				if ( isset( $settings['type'] ) && ( 'shortcode' == $settings['type'] || 'internal' == $settings['type'] ) )
					add_shortcode( $name, array( $this, $name ) );
			}

		endif;

	}

	/**
	 * Get Shortcodes (and their settings) available for the theme
	 * 
	 * @since 1.1.0
	 * @access public
	 * @return array
	 */
	public static function get_shortcodes_array() {

		static $shortcodes = null; // cache

		if ( !$shortcodes ) {

			// Preferred location for themes to hook into filter to modify the Shortcodes
			if ( file_exists( HYBRIDEXTEND_PREMIUM_INC . 'admin/shortcodes.php' ) )
				include_once( HYBRIDEXTEND_PREMIUM_INC . 'admin/shortcodes.php' );

			// Get Core Shortcodes
			$shortcodes = require_once ( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/shortcodes/shortcodes.php' );
			$shortcodes = apply_filters( 'hoot_shortcodes', $shortcodes );

		}

		if ( is_array( $shortcodes ) )
			return $shortcodes;
		else
			return array();
	}

	/**
	 * Display Shortcode
	 * 
	 * @since 1.1.0
	 * @access public
	 * @param string $name Function name which is the same as unique Shortcode Name
	 * @param array $params array( {$args}, {$content}, {$shortcode-name} )
	 * @return string
	 */
	public function __call( $name, $params ){

		/* Display only if the call came for a valid registered available shortcode */
		if ( array_key_exists( $name, $this->shortcodes ) &&
			 ( isset( $this->shortcodes[ $name ]['type'] ) && ( 'shortcode' == $this->shortcodes[ $name ]['type'] || 'internal' == $this->shortcodes[ $name ]['type'] ) )
			) {

			$args    = ( isset( $params[0] ) ) ? $params[0] : array();
			$content = ( isset( $params[1] ) ) ? $params[1] : '';
			// $scname  = ( isset( $params[2] ) ) ? $params[2] : ''; // Same as $name

			$content = hybridextend_trim( $content );
			if ( is_array($args) )
				extract( $args, EXTR_OVERWRITE );

			// Loads the hoot-theme/shortcodes/{name}.php or hoot/extensions/shortcodes/display/{name}.php
			$filename = str_replace( '_',  '-', $name );
			$filename = hybridextend_locate_shortcode( $filename ); 
			if ( $filename )
				$html = include( $filename );
			else
				$html = '';

			if ( !empty( $html ) && !is_bool( $html ) && 1 !== $html && 0 !== $html )
				return $html;
		}

	}

	/**
	 * Returns the instance.
	 *
	 * @since 1.1.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}

/* Initialize class */
global $hoot_shortcodes;
$hoot_shortcodes = Hoot_Shortcodes::get_instance();