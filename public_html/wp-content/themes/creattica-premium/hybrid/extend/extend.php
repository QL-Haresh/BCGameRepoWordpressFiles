<?php
/**
 * Extend the Hybrid framework
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * The Hybrid_Extend class extends the Hybrid framework.
 * After calling the Hybrid_Extend class, parent themes should perform a theme setup function on the 
 * 'after_setup_theme' hook with a priority of 10.  Child themes can add theme setup function
 * with a priority of 11. This allows the class to load theme-supported features on the
 * 'after_setup_theme' hook with a priority of 12.
 * 
 * @since 1.0.0
 * @access public
 */
if ( !class_exists( 'Hybrid_Extend' ) ) {
	class Hybrid_Extend {

		/**
		 * Constructor method to controls the load order of the required files for running 
		 * the framework.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		function __construct() {

			/* Define framework, parent theme, and child theme constants. */
			add_action( 'after_setup_theme', array( $this, 'constants' ), 1 );

			/* Load the core functions/classes required by the rest of the framework. */
			add_action( 'after_setup_theme', array( $this, 'core' ), 2 );

			/* Load the customizer framework. */
			add_action( 'after_setup_theme', array( $this, 'customize' ), 5 );

			/* Load the framework extensions. */
			add_action( 'after_setup_theme', array( $this, 'extensions' ), 14 );

		}

		/**
		 * Defines the constant paths for use within the core framework, parent theme, and child theme.  
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		function constants() {

			// Set HybridExtend Version.
			define( 'HYBRIDEXTEND_VERSION', '2.2.7' );

			// Theme directory paths #Hybrid3.0
			define( 'HYBRID_PARENT', trailingslashit( get_template_directory()   ) );
			define( 'HYBRID_CHILD',  trailingslashit( get_stylesheet_directory() ) );
			// Theme directory URIs #Hybrid3.0
			define( 'HYBRID_PARENT_URI', trailingslashit( get_template_directory_uri()   ) );
			define( 'HYBRID_CHILD_URI',  trailingslashit( get_stylesheet_directory_uri() ) );

			// Set Theme Location Constants
			define( 'HYBRIDEXTEND_DIR', trailingslashit( HYBRID_PARENT . 'hybrid/extend' ) );
			define( 'HYBRIDEXTEND_URI', trailingslashit( HYBRID_PARENT_URI . 'hybrid/extend' ) );
			define( 'HYBRIDEXTEND_INC', trailingslashit( HYBRID_PARENT . 'include' ) );
			define( 'HYBRIDEXTEND_INCURI', trailingslashit( HYBRID_PARENT_URI . 'include' ) );
			define( 'HYBRIDEXTEND_CSS', trailingslashit( HYBRIDEXTEND_URI . 'css' ) );
			define( 'HYBRIDEXTEND_IMAGES', trailingslashit( HYBRIDEXTEND_URI . 'images' ) );
			define( 'HYBRIDEXTEND_JS', trailingslashit( HYBRIDEXTEND_URI . 'js' ) );

			// Set default item count to show in a generic list option (query number).
			define( 'HYBRIDEXTEND_ADMIN_LIST_ITEM_COUNT', apply_filters( 'hybridextend_admin_list_item_count', 999 ) );

			// Set theme detail Constants
			global $hybridextend_theme;
			$hybridextend_theme = wp_get_theme();
			if ( is_child_theme() ) {
				define( 'HYBRIDEXTEND_CHILDTHEME_VERSION', $hybridextend_theme->get( 'Version' ) );
				if ( is_object( $hybridextend_theme->parent() ) ) {
					define( 'HYBRIDEXTEND_THEME_VERSION', $hybridextend_theme->parent()->get( 'Version' ) );
					define( 'HYBRIDEXTEND_THEME_NAME', $hybridextend_theme->parent()->get( 'Name' ) );
					define( 'HYBRIDEXTEND_THEME_AUTHOR_URI', $hybridextend_theme->parent()->get( 'AuthorURI' ) );
				} else {
					define( 'HYBRIDEXTEND_THEME_VERSION', '1.0' );
					define( 'HYBRIDEXTEND_THEME_NAME', 'Creattica' );
					define( 'HYBRIDEXTEND_THEME_AUTHOR_URI', 'https://wphoot.com/' );
				}
			} else {
				define( 'HYBRIDEXTEND_THEME_VERSION', $hybridextend_theme->get( 'Version' ) );
				define( 'HYBRIDEXTEND_THEME_NAME', $hybridextend_theme->get( 'Name' ) );
				define( 'HYBRIDEXTEND_THEME_AUTHOR_URI', $hybridextend_theme->get( 'AuthorURI' ) );
			}

		}

		/**
		 * Loads the core framework files. These files are needed before loading anything else in the 
		 * framework because they have required functions for use. Many of the files run filters that 
		 * may be removed in theme setup functions.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		function core() {

			/* Load the data set functions. Also needed for sanitization. */
			require_once( HYBRIDEXTEND_DIR . 'includes/enum.php' );

			/* Load Helper functions */
			require_once( HYBRIDEXTEND_DIR . 'includes/helpers.php' );

			/* Load the scripts functions. */
			require_once( HYBRIDEXTEND_DIR . 'includes/scripts.php' );

			/* Load the styles functions. */
			require_once( HYBRIDEXTEND_DIR . 'includes/styles.php' );

			/* Load the template functions. */
			require_once( HYBRIDEXTEND_DIR . 'includes/template.php' );

			/* Load Color functions */
			require_once( HYBRIDEXTEND_DIR . 'includes/color.php' );

			/* Load the sanitization functions. */
			require_once( HYBRIDEXTEND_DIR . 'includes/sanitization.php' );

		}

		/**
		 * Load HybridExtend Customize framework.
		 *
		 * @since 2.0.0
		 * @access public
		 * @return void
		 */
		function customize() {

			/* Load the HybridExtend Customize framework */
			require_once( HYBRIDEXTEND_DIR . 'customize/customize.php' );

		}

		/**
		 * Load extensions (external projects).
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		function extensions() {

			/* Load the Widgets extension if supported. */
			require_if_theme_supports( 'hybridextend-widgets', HYBRIDEXTEND_DIR . 'extensions/widgets.php' );

		}

	}
}