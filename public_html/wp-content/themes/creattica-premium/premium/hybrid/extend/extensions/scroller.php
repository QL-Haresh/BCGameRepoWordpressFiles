<?php
/**
 * Scroller Extension adds Scrollpoints and Waypoints sub-extensions which can be used by the
 * theme or extensions
 * Pre-packaged Scroller Modules:
 *  > sticky-headers (waypoints)
 *  > goto-top buttons (scrollpoints, waypoints optional)
 * 
 * This file is loaded at 'after_setup_theme' hook with 14 priority.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Scroller class. This wraps everything up nicely.
 *
 * @since 1.1.1
 */
final class HybridExtend_Scroller {

	/**
	 * Holds the instance of this class.
	 *
	 * @since 1.1.1
	 * @access private
	 * @var object
	 */
	private static $instance;

	/**
	 * Supported modules
	 *
	 * @since 1.1.1
	 * @access public
	 * @var array
	 */
	public $support = array();

	/**
	 * Initialize everything
	 * 
	 * @since 1.1.1
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Init Support */
		$this->support = array(
			'waypoints' => array(),
			'scrollpoints' => array(),
		);

		/* Add Modules Support */

		if ( hybridextend_theme_supports( 'hybridextend-scrollpoints', 'menu-scroll' ) )
			$this->support['scrollpoints'][] = 'menu-scroll';

		if ( !hoot_get_mod('disable_goto_top') ) {
			if ( hybridextend_theme_supports( 'hybridextend-scrollpoints', 'goto-top' ) )
				$this->support['scrollpoints'][] = 'goto-top';
			if ( hybridextend_theme_supports( 'hybridextend-waypoints', 'goto-top' ) )
				$this->support['waypoints'][] = 'goto-top';
		}

		if ( !hoot_get_mod('disable_sticky_header') ) {
			if ( hybridextend_theme_supports( 'hybridextend-waypoints', 'sticky-header' ) )
				$this->support['waypoints'][] = 'sticky-header';
		}

		/* Add prepackaged Modules */
		$this->modules();

		/* Add the required scripts and styles */
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_wp_styles_scripts' ) );

	}

	/**
	 * Loads the required stylesheets and scripts
	 *
	 * @since 1.1.1
	 */
	function enqueue_wp_styles_scripts( $hook ) {

		/* Enqueue Waypoint */
		if ( current_theme_supports( 'hybridextend-waypoints' ) ) {
			$script_uri = hybridextend_locate_script( HYBRIDEXTEND_JS . 'jquery.waypoints' );
			wp_enqueue_script( 'waypoints', $script_uri, array( 'jquery' ), '4.0.1', true );
			$script_uri = hybridextend_locate_script( HYBRIDEXTEND_JS . 'sticky' );
			$waypoint_handle = ( !wp_script_is( 'elementor-waypoints', 'registered' ) ) ? 'waypoints' : 'elementor-waypoints'; // Sticky attaches to Waypoint followed by elementor loading Waypoint again => this will refresh Waypoint to default (without Sticky). Hence console error "Waypoint.Sticky is not a constructor"
			wp_enqueue_script( 'waypoints-sticky', $script_uri, array( 'jquery', $waypoint_handle ), '4.0.1', true );
		}

		/* Enqueue Scrollpoints */
		if ( current_theme_supports( 'hybridextend-scrollpoints' ) ) {
			$script_uri = hybridextend_locate_script( HYBRIDEXTEND_JS . 'scrollpoints' );
			wp_enqueue_script( 'hybridextend-scrollpoints', $script_uri, array( 'jquery' ), HYBRIDEXTEND_VERSION, true );
		}

		/* Enqueue Scroller */
		$script_uri = hybridextend_locate_script( HYBRIDEXTEND_JS . 'scroller' );
		wp_enqueue_script( 'hybridextend-scroller', $script_uri, array( 'hybridextend-scrollpoints' ), HYBRIDEXTEND_VERSION, true );

	}

	/**
	 * Add pre-packaged modules
	 *
	 * @since 2.1.0
	 */
	function modules() {

		/* Insert Goto Top Button */
		if ( in_array( 'goto-top', $this->support['scrollpoints'] ) )
			add_action( 'wp_footer', array( $this, 'goto_top_button' ) );

		/* Enable Menu Scroll */
		if ( in_array( 'menu-scroll', $this->support['scrollpoints'] ) )
			add_filter( 'hybrid_attr_menu', array( $this, 'hybridextend_attr_menu' ), 10, 2 );

		/* Enable Sticky Header */
		if ( in_array( 'sticky-header', $this->support['waypoints'] ) )
			add_filter( 'hybrid_attr_header', array( $this, 'hybridextend_attr_header' ), 10, 2 );

	}

	/**
	 * Filter Page <nav> element attributes to enable scroller to all child <a>
	 *
	 * @since 2.1.0
	 * @access public
	 * @param array $attr
	 * @param string $context
	 * @return array
	 */
	function hybridextend_attr_menu( $attr, $context ) {
		$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
		$attr['class'] .= ' scrollpointscontainer';
		return $attr;
	}

	/**
	 * Filter Page <header> element attributes to enable sticky header
	 *
	 * @since 1.1.1
	 * @access public
	 * @param array $attr
	 * @param string $context
	 * @return array
	 */
	function hybridextend_attr_header( $attr, $context ) {
		$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
		$attr['class'] .= ' hybridextend-sticky-header';
		return $attr;
	}

	/**
	 * Insert Top Button
	 *
	 * @since 1.1.1
	 */
	function goto_top_button() {
		$class = ( current_theme_supports( 'hybridextend-waypoints' ) ) ? ' waypoints-goto-top ' : '';
		echo '<a href="#page-wrapper" class="fixed-goto-top' . $class . '">'
			. '<i class="fas fa-chevron-up"></i>'
			. '</a>';
	}

	/**
	 * Returns the instance.
	 *
	 * @since 1.1.1
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
global $hybridextend_scroller;
$hybridextend_scroller = HybridExtend_Scroller::get_instance();