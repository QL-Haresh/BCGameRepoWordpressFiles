<?php
/**
 * Hoot Theme hooked into the framework
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
 * The Hoot Theme class launches the theme setup.
 *
 * Theme setup functions are performed on the 'after_setup_theme' hook with a priority of 10.
 * Child themes can add theme setup function with a priority of 11. This allows the Hoot
 * framework class to load theme-supported features on the 'after_setup_theme' hook with a
 * priority of 12.
 * Also, hoot constants are available at 'after_setup_theme' hook at a priority of 10 or later.
 * 
 * @since 1.0
 * @access public
 */
if ( !class_exists( 'Hoot_Theme' ) ) {
	class Hoot_Theme {

		/**
		 * Constructor method to controls the load order of the required files
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		function __construct() {

			/* Load theme includes. Must keep priority 10 for theme constants to be available. */
			add_action( 'after_setup_theme', array( $this, 'includes' ), 10 );

			/* Load about page */
			add_action( 'after_setup_theme', array( $this, 'about' ), 10 );

			/* Theme setup on the 'after_setup_theme' hook. Must keep priority 10 for framework to load properly. */
			add_action( 'after_setup_theme', array( $this, 'theme_setup' ), 10 );

		}

		/**
		 * Loads the theme files supported by themes and template-related functions/classes.  Functionality 
		 * in these files should not be expected within the theme setup function.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		function includes() {

			/* Load HTML Schema attributes */
			require_once( HYBRIDEXTEND_INC . 'attr-schema.php' );

			/* Load the Theme Specific HTML attributes */
			require_once( HYBRIDEXTEND_INC . 'attr.php' );

			/* Load enqueue functions */
			require_once( HYBRIDEXTEND_INC . 'enqueue.php' );

			/* Load image sizes. */
			require_once( HYBRIDEXTEND_INC . 'media.php' );

			/* Load the font functions. */
			require_once( HYBRIDEXTEND_INC . 'fonts.php' );

			/* Load menus */
			require_once( HYBRIDEXTEND_INC . 'menus.php' );

			/* Load sidebars */
			require_once( HYBRIDEXTEND_INC . 'sidebars.php' );

			/* Load the custom css functions. */
			require_once( HYBRIDEXTEND_INC . 'css.php' );

			/* Load the misc template functions. */
			require_once( HYBRIDEXTEND_INC . 'template-helpers.php' );

			/* Load Customizer Options */
			$trt = HYBRIDEXTEND_INC . 'admin/trt-customize-pro/class-customize.php';
			$trtload = apply_filters( 'hoot_customize_load_trt', file_exists( $trt ) );
			if ( $trtload ) require_once( $trt );
			require_once( HYBRIDEXTEND_INC . 'admin/customizer-options.php' );

		}

		/**
		 * Load the about page.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		function about() {

			if ( file_exists( HYBRIDEXTEND_INC . 'admin/about.php' ) )
				require_once( HYBRIDEXTEND_INC . 'admin/about.php' );

		}

		/**
		 * Add theme supports. This is how the theme hooks into the framework and loads proper modules.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		function theme_setup() {

			/* Load the theme setup file */
			if ( file_exists( HYBRIDEXTEND_INC . 'theme-setup.php' ) )
				require_once( HYBRIDEXTEND_INC . 'theme-setup.php' );

		}

	} // end class
} // end if

/* Add Premium Extension if exist */
if ( file_exists( trailingslashit( get_template_directory() ) . 'premium/functions.php' ) )
	require_once( trailingslashit( get_template_directory() ) . 'premium/functions.php' );