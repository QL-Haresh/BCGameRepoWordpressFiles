<?php
/**
 * Hoot Premium hooked into the framework
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
 * The Hoot_Theme_Premium class launches the theme premium extension setup.
 * It follows the Hoot_Theme structure closely. See Hoot_Theme class for further details.
 * 
 * @since 1.0
 * @access public
 */
if ( !class_exists( 'Hoot_Theme_Premium' ) ) {
	class Hoot_Theme_Premium {

		/**
		 * Constructor method to controls the load order of the required files
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		function __construct() {

			/* Initialize the actions and filters. */
			add_action( 'after_setup_theme', array( $this, 'hooks' ), 3 );

			/* Load theme includes. Must keep priority 10 for theme constants to be available. */
			add_action( 'after_setup_theme', array( $this, 'includes' ), 10 );

			/* Theme setup on the 'after_setup_theme' hook. Must keep priority 10 for framework to load properly. */
			add_action( 'after_setup_theme', array( $this, 'theme_setup' ), 10 );

			/* Insert Custom Javascript code from the user */
			add_action( 'init', array( $this, 'custom_user_js' ) );

			/* Register OCDI files -> after_setup_theme is too late to hook into, hence added to construct */
			add_filter( 'pt-ocdi/import_files', array( $this, 'hoot_theme_ocdi_import' ) );

		}

		/**
		 * Adds the actions and filters.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		function hooks() {

			/* Add premium background options */
			add_filter( 'hybridextend_enum_background_pattern', array( $this, 'background_pattern' ), 6 );

			/* Unload premium upsell/about page for premium themes */
			$unload_upsell = apply_filters( 'hoot_unload_upsell', array( 'hoot_load_about_subpage', 'hoot_customize_load_trt' ) );
			foreach ( $unload_upsell as $unload_hookname ) {
				add_filter( $unload_hookname, array( $this, 'unload_upsell' ) );
			}

			/* Change Premium Section content in Customizer */
			add_filter( 'hoot_premium_customize_section_pro_template', array( $this, 'customize_section_pro_template' ) );

			/* Theme Manager Page Name */
			add_filter( 'hybridextend_theme_manager_name', array( $this, 'theme_manager_name' ) );

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

			/* Load enqueue functions */
			require_once( HYBRIDEXTEND_PREMIUM_INC . 'enqueue.php' );

			/* Load the google font functions. */
			require_once( HYBRIDEXTEND_PREMIUM_INC . 'fonts.php' );

			/* Load the custom css functions */
			require_once( HYBRIDEXTEND_PREMIUM_INC . 'css.php' );

			/* Load the Theme Specific HTML attributes */
			require_once( HYBRIDEXTEND_PREMIUM_INC . 'attr.php' );

			/* Load the misc template functions. */
			require_once( HYBRIDEXTEND_PREMIUM_INC . 'template-helpers.php' );

			/* Load Customizer Options Extend */
			if ( file_exists( HYBRIDEXTEND_PREMIUM_INC . 'admin/customizer-options.php' ) )
				require_once( HYBRIDEXTEND_PREMIUM_INC . 'admin/customizer-options.php' );

			/* Load Meta Options */
			if ( file_exists( HYBRIDEXTEND_PREMIUM_INC . 'admin/meta-options.php' ) )
				require_once( HYBRIDEXTEND_PREMIUM_INC . 'admin/meta-options.php' );

			/* Load Megamenu Options */
			if ( file_exists( HYBRIDEXTEND_PREMIUM_INC . 'admin/megamenu-options.php' ) )
				require_once( HYBRIDEXTEND_PREMIUM_INC . 'admin/megamenu-options.php' );

			/* Load CPT Options */
			if ( file_exists( HYBRIDEXTEND_PREMIUM_INC . 'admin/cpt.php' ) )
				require_once( HYBRIDEXTEND_PREMIUM_INC . 'admin/cpt.php' );

			/* Load Taxonomy Options */
			if ( file_exists( HYBRIDEXTEND_PREMIUM_INC . 'admin/taxonomies.php' ) )
				require_once( HYBRIDEXTEND_PREMIUM_INC . 'admin/taxonomies.php' );

		}

		/**
		 * Add theme supports. This is how the theme hooks into the framework and loads proper modules.
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		function theme_setup() {

			/* Hybrid Extend */

			// Add meta support if needed
			add_theme_support( 'hybridextend-options-meta' );

			// Add Shortcodes
			add_theme_support( 'hybridextend-shortcodes' );

			// Add CPT
			add_theme_support( 'hybridextend-cpt' );

			// Add Taxonomies
			// add_theme_support( 'hybridextend-taxonomies' );

			// Add Mega Menu
			add_theme_support( 'hybridextend-megamenu', array( 'menuitem_icon' ) );

			// Custom 404 page option
			add_theme_support( 'custom-404' );

			// Add Scrollpoints Support for goto-top
			add_theme_support( 'hybridextend-scrollpoints', array( 'goto-top', 'menu-scroll' ) );

			// Add Waypoints Support for sticky-leftbar, goto-top
			// circliful is not a part of core hoot framework, we plug it in via in hoot-theme. We add its support into 'hybridextend-waypoints' feature as circliful is triggered using waypoints to start animation
			add_theme_support( 'hybridextend-waypoints', array( 'goto-top', 'sticky-leftbar', 'circliful' ) );

			// Add Theme Manager Support
			add_theme_support( 'hybridextend-theme-manager', array( 'autoupgrade', 'import', 'export' ) );

			/* Theme Addons */

			// Add Lightbox Support (thus light-gallery)
			add_theme_support( 'hoot-lightbox' );
			add_theme_support( 'light-gallery' );

			// Add Slider Support for shortcodes
			add_theme_support( 'hoot-light-slider' );

		}

		/**
		 * Register OCDI Import Files
		 *
		 * @since 4.7
		 * @return array
		 */
		function hoot_theme_ocdi_import() {
			$files = array(
				array(
					'import_file_name'           => __( 'Creattica Demo', 'creattica-premium' ),
					'import_file_url'            => 'https://demo.wphoot.com/downloads/creattica-content-free.xml',
					'import_widget_file_url'     => 'https://demo.wphoot.com/downloads/creattica-widgets-free.wie',
					'import_customizer_file_url' => 'https://demo.wphoot.com/downloads/creattica-customize-free.dat',
					'import_preview_image_url'   => HYBRID_PARENT_URI . 'screenshot.jpg',
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					'import_notice'              => sprintf( esc_html__( 'You are using the free version of the theme.%1$sSome features (available only in the premium version) will not get imported - You may see %2$s"Could not import"%3$s message for these features in the log once the installation is finished. You can safely ignore these messages.', 'creattica-premium' ), '<br />', '<em>', '</em>' ),
					'preview_url'                => 'https://demo.wphoot.com/creattica/',
				),
			);
			if ( class_exists( 'Hoot_Theme_Premium' ) ) {
				if ( isset( $files[0]['import_notice'] ) ) unset( $files[0]['import_notice'] );
				if ( isset( $files[0]['import_file_url'] ) ) $files[0]['import_file_url'] = str_replace( '-free', '', $files[0]['import_file_url'] );
				if ( isset( $files[0]['import_widget_file_url'] ) ) $files[0]['import_widget_file_url'] = str_replace( '-free', '', $files[0]['import_widget_file_url'] );
				if ( isset( $files[0]['import_customizer_file_url'] ) ) $files[0]['import_customizer_file_url'] = str_replace( '-free', '', $files[0]['import_customizer_file_url'] );
			}
			return $files;
		}

		/**
		 * Insert Custom Javascript code by the user
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		function custom_user_js() {
			if ( hoot_get_mod( 'custom_js_inheader' ) )
				add_action( 'wp_head', array( $this, 'insert_custom_user_js' ) );
			else
				add_action( 'wp_footer', array( $this, 'insert_custom_user_js' ) );
		}

		/**
		 * Insert Custom Javascript code by the user in either header or footer
		 *
		 * @since 1.0
		 * @access public
		 * @return void
		 */
		function insert_custom_user_js() {
			$custom_js = trim( hoot_get_mod( 'custom_js', '' ) );
			if ( !empty( $custom_js ) ) {
				if ( !strpos( $custom_js, '<script>' ) && !strpos( $custom_js, '</script>' ) ) {
					$start = '<script>'; $end = '</script>';
				} else $start = $end = '';
				// $custom_js = preg_replace( '/^' . preg_quote('<script>', '/') . '/', '', $custom_js);
				// $custom_js = preg_replace( '/' . preg_quote('</script>', '/') . '$/', '', $custom_js);
				echo "\n" . $start . htmlspecialchars_decode( $custom_js ) . $end . "\n";
			}
		}

		/**
		 * Make theme specific premium background patterns available for loading into the options
		 *
		 * @since 1.0
		 * @param array $locations
		 * @return array
		 */
		function background_pattern( $patterns ) {
			$relative = trailingslashit( substr( PREMIUM_URI . 'images/patterns' , strlen( HYBRID_PARENT_URI ) ) );
			$patterns = $patterns + array(
				$relative . '01.jpg' => PREMIUM_URI . 'images/patterns/01_preview.jpg',
			);
			return $patterns;
		}

		/**
		 * Unload premium upsell page for premium themes
		 *
		 * @since 1.0
		 * @access public
		 * @param bool $load
 		 * @return bool
		 */
		function unload_upsell( $load ) {
			return false;
		}

		/**
		 * Change Premium Section content in Customizer
		 *
		 * @since 1.0
		 * @access public
		 * @param bool $load
 		 * @return bool
		 */
		function customize_section_pro_template( $load ) {
			return '
				<span class="hoot-premium-content hoot-premium-active">
					<# if ( data.demo_text && data.demo ) { #>
						<a href="{{ data.demo }}" class="button button-secondary" target="_blank"><i class="fas fa-eye"></i> {{ data.demo_text }}</a>
					<# } #>
					<# if ( data.docs_text && data.docs ) { #>
						<a href="{{ data.docs }}" class="button button-secondary" target="_blank"><i class="far fa-life-ring"></i> {{ data.docs_text }}</a>
					<# } #>
					<# if ( data.rating_text && data.rating ) { #>
						<a href="{{ data.rating }}" class="button button-secondary" target="_blank"><i class="fas fa-star"></i> {{ data.rating_text }}</a>
					<# } #>
				</span>
				';
		}

		/**
		 * Theme Manager Page Name
		 *
		 * @since 1.0
		 * @access public
		 * @param string
 		 * @return string
		 */
		function theme_manager_name( $name ) {
			return __( 'Hoot Theme Manager', 'creattica-premium' );
		}

	} // end class
} // end if