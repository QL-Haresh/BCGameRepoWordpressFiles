<?php
/**
 * Premium extension for Hybrid Extend framework
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * The Hybrid_Extend_Premium class launches the premium framework.
 * 
 * @since 2.0.0
 * @access public
 */
if ( !class_exists( 'Hybrid_Extend_Premium' ) ) {
	class Hybrid_Extend_Premium {

		/**
		 * Constructor method to controls the load order of the required files for running 
		 * the framework.
		 *
		 * @since 2.0.0
		 * @access public
		 * @return void
		 */
		function __construct() {

			/* Define framework, parent theme, and child theme constants. */
			add_action( 'after_setup_theme', array( $this, 'constants' ), 1 );

			/* Initialize the actions and filters. */
			add_action( 'after_setup_theme', array( $this, 'hooks' ), 3 );

			/* Load the customize framework. */
			add_action( 'after_setup_theme', array( $this, 'customize' ), 5 );

			/* Load framework includes. */
			add_action( 'after_setup_theme', array( $this, 'includes' ), 13 );

			/* Load the framework extensions. */
			add_action( 'after_setup_theme', array( $this, 'extensions' ), 14 );

		}

		/**
		 * Defines the constant paths for use within the core framework, parent theme, and child theme.  
		 *
		 * @since 2.0.0
		 * @access public
		 * @return void
		 */
		function constants() {

			// Set Theme Location Constants
			define( 'PREMIUM_DIR', trailingslashit( HYBRID_PARENT . 'premium' ) );
			define( 'PREMIUM_URI', trailingslashit( HYBRID_PARENT_URI . 'premium' ) );
			define( 'HYBRIDEXTEND_PREMIUM_DIR', trailingslashit( PREMIUM_DIR . 'hybrid/extend' ) );
			define( 'HYBRIDEXTEND_PREMIUM_URI', trailingslashit( PREMIUM_URI . 'hybrid/extend' ) );
			define( 'HYBRIDEXTEND_PREMIUM_INC', trailingslashit( PREMIUM_DIR . 'include' ) );
			define( 'HYBRIDEXTEND_PREMIUM_INCURI', trailingslashit( PREMIUM_URI . 'include' ) );

			// Set the template name
			define( 'HYBRIDEXTEND_TEMPLATE', preg_replace( '/ ?premium/i', '', HYBRIDEXTEND_THEME_NAME ) );

			// Sets the theme slug
			$theme_slug = strtolower( preg_replace( '/[^a-zA-Z0-9]+/', '_', trim( HYBRIDEXTEND_THEME_NAME ) ) );
			// if ( ! defined( 'CHILDTHEME_INDEPENDENT_SLUG' ) || CHILDTHEME_INDEPENDENT_SLUG !== true )
			// 	$theme_slug = preg_replace( '/_?child/', '', $theme_slug ); // instead of '/_?child$/'
			define( 'HYBRIDEXTEND_THEMESLUG', preg_replace( '/_?premium/', '', $theme_slug ) ); // instead of '/_?premium$/'

		}

		/**
		 * Adds the actions and filters.
		 *
		 * @since 2.0.0
		 * @access public
		 * @return void
		 */
		function hooks() {

			/* Make text widgets shortcode aware. */
			add_filter( 'widget_text', 'do_shortcode' );

			/* Add premium widget locations to get loaded in the Widget extension */
			add_filter( 'hybridextend_load_widgets', array( $this, 'load_widgets' ) );

			/* Add premium background options */
			add_filter( 'hybridextend_enum_background_pattern', array( $this, 'background_pattern' ), 5 );

		}

		/**
		 * Loads the framework files supported by themes and template-related functions/classes.
		 * Functionality in these files should not be expected within the theme setup function.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		function includes() {

			/* Load the template functions. */
			require_once( HYBRIDEXTEND_PREMIUM_DIR . 'includes/template.php' );

			/* Load the google font functions. */
			require_once( HYBRIDEXTEND_PREMIUM_DIR . 'includes/fonts-google.php' );

		}

		/**
		 * Load extensions (external projects).
		 *
		 * @since 2.0.0
		 * @access public
		 * @return void
		 */
		function extensions() {

			/* Load the Shortcodes extension if supported. */
			require_if_theme_supports( 'hybridextend-shortcodes', HYBRIDEXTEND_PREMIUM_DIR . 'extensions/shortcodes/init.php' );

			/* Load the CPT extension if supported. */
			require_if_theme_supports( 'hybridextend-cpt', HYBRIDEXTEND_PREMIUM_DIR . 'extensions/cpt.php' );

			/* Load the Taxonomies extension if supported. */
			require_if_theme_supports( 'hybridextend-taxonomies', HYBRIDEXTEND_PREMIUM_DIR . 'extensions/taxonomies.php' );

			/* Load the Meta extension if supported. */
			require_if_theme_supports( 'hybridextend-options-meta', HYBRIDEXTEND_PREMIUM_DIR . 'extensions/options-meta.php' );

			/* Load the Megamenu extension if supported. */
			require_if_theme_supports( 'hybridextend-megamenu', HYBRIDEXTEND_PREMIUM_DIR . 'extensions/megamenu/init.php' );

			/* Load the Scroller extension if supported. */
			if ( current_theme_supports( 'hybridextend-scrollpoints' ) || current_theme_supports( 'hybridextend-waypoints' ) )
				require( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/scroller.php' );

			/* Load the theme manager extension. */
			if ( is_admin() && current_user_can( 'edit_theme_options' ) )
				require_if_theme_supports( 'hybridextend-theme-manager', HYBRIDEXTEND_PREMIUM_DIR . 'extensions/theme-manager/init.php' );

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
			require_once( HYBRIDEXTEND_PREMIUM_DIR . 'customize/customize.php' );

		}

		/**
		 * Add premium widget locations to get loaded in the Widget extension
		 *
		 * @since 2.0.0
		 * @param array $locations
		 * @return array
		 */
		function load_widgets( $locations ) {
			$locations[] = HYBRIDEXTEND_PREMIUM_INC . 'admin/widget-*.php';
			return $locations;
		}

		/**
		 * Make premium background patterns available for loading into the options
		 *
		 * @since 2.0.0
		 * @param array $locations
		 * @return array
		 */
		function background_pattern( $patterns ) {
			$relative = trailingslashit( substr( HYBRIDEXTEND_PREMIUM_URI . 'images/patterns' , strlen( HYBRID_PARENT_URI ) ) );
			$patterns = $patterns + array(
				$relative . '1.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/1_preview.jpg',
				$relative . '2.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/2_preview.jpg',
				$relative . '3.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/3_preview.jpg',
				$relative . '4.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/4_preview.jpg',
				$relative . '5.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/5_preview.jpg',
				$relative . '6.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/6_preview.jpg',
				$relative . '7.png' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/7_preview.jpg',
				$relative . '8.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/8_preview.jpg',
				$relative . '9.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/9_preview.jpg',
				$relative . '10.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/10_preview.jpg',
				$relative . '11.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/11_preview.jpg',
				$relative . '12.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/12_preview.jpg',
				$relative . '13.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/13_preview.jpg',
				$relative . '14.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/14_preview.jpg',
				$relative . '15.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/15_preview.jpg',
				$relative . '16.png' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/16_preview.jpg',
				$relative . '17.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/17_preview.jpg',
				$relative . '18.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/18_preview.jpg',
				$relative . '19.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/19_preview.jpg',
				$relative . '20.png' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/20_preview.jpg',
				$relative . '21.png' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/21_preview.jpg',
				$relative . '22.png' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/22_preview.jpg',
				$relative . '23.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/23_preview.jpg',
				$relative . '24.png' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/24_preview.jpg',
				$relative . '25.jpg' => HYBRIDEXTEND_PREMIUM_URI . 'images/patterns/25_preview.jpg',
			);
			return $patterns;
		}

	} // end class
} // end if