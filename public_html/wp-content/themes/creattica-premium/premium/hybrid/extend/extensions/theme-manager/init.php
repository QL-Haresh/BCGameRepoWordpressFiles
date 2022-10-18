<?php
/**
 * Theme Manager Extension
 * This file is loaded at 'after_setup_theme' hook with 14 priority.
 * This file is loaded only when is_admin() and current_user_can( 'edit_theme_options' )
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Theme Manager class. This wraps everything up nicely.
 *
 * @since 2.0.0
 */
class HybridExtend_Theme_Manager {

	/**
	 * Holds the instance of this class.
	 *
	 * @since 2.0.0
	 * @access private
	 * @var object
	 */
	private static $instance;

	/**
	 * Supported modules
	 *
	 * @since 2.0.0
	 * @access public
	 * @var array
	 */
	public $support = array();

	/**
	 * Initialize everything
	 * 
	 * @since 2.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Get Modules Support */
		if ( hybridextend_theme_supports( 'hybridextend-theme-manager', 'autoupgrade' ) )
			$this->support[] = 'autoupgrade';
		if ( hybridextend_theme_supports( 'hybridextend-theme-manager', 'import' ) )
			$this->support[] = 'import';
		if ( hybridextend_theme_supports( 'hybridextend-theme-manager', 'export' ) )
			$this->support[] = 'export';

		/* Add the Theme Manager page */
		add_action( 'admin_menu', array( $this, 'manager_add_page' ) );
		add_action( 'admin_menu', array( $this, 'reorder_custom_options_page' ), 9995 );

		/* Add the required scripts and styles */
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_wp_styles_scripts' ) );

		/* Add modules */
		add_action( 'init', array( $this, 'add_modules' ) );

	}

	/**
	 * Loads the required stylesheets and scripts
	 *
	 * @since 2.0.0
	 */
	function enqueue_wp_styles_scripts( $hook ) {
		if ( 'appearance_page_hybridextendthememanager' == $hook ) {
			$style_uri = hybridextend_locate_style( HYBRIDEXTEND_CSS . 'theme-manager' );
			wp_enqueue_style( 'hybridextend-theme-manager', $style_uri, array(),  HYBRIDEXTEND_VERSION );
		}
	}

	/**
	 * Add Theme Manager Page
	 *
	 * @since 2.0.0
	 */
	function manager_add_page() {

		$name = apply_filters( 'hybridextend_theme_manager_name', __( 'Theme Manager', 'hybrid-core' ) );
		add_theme_page( $name, $name, 'manage_options', 'hybridextendthememanager', array( $this, 'manager_do_page' ) );

	}

	/**
	 * Reorder subpage called "Theme Options" in the appearance menu.
	 *
	 * @since 1.0.0
	 */
	function reorder_custom_options_page() {
		global $submenu;
		$menu_slug = 'hybridextendthememanager';
		$index = '';

		if ( !isset( $submenu['themes.php'] ) ) {
			// probably current user doesn't have this item in menu
			return;
		}

		foreach ( $submenu['themes.php'] as $key => $sm ) {
			if ( $sm[2] == $menu_slug ) {
				$index = $key;
				break;
			}
		}

		if ( ! empty( $index ) ) {
			//$item = $submenu['themes.php'][ $index ];
			//unset( $submenu['themes.php'][ $index ] );
			//array_splice( $submenu['themes.php'], 1, 0, array($item) );
			/* array_splice does not preserve numeric keys, so instead we do our own rearranging. */
			$smthemes = array();
			foreach ( $submenu['themes.php'] as $key => $sm ) {
				if ( $key != $index ) {
					$setkey = $key;
					// Find next available position if current one is taken
					for ( $i = $key; $i < 1000; $i++ ) {
						if( !isset( $smthemes[$i] ) ) {
							$setkey = $i;
							break;
						}
					}
					$smthemes[ $setkey ] = $sm;
					if ( $sm[1] == 'customize' ) { // if ( $sm[2] == 'themes.php' ) {
						$smthemes[ $setkey + 1 ] = $submenu['themes.php'][ $index ];
					}
				}
			}
			hybridextend_array_empty( $submenu['themes.php'], $smthemes );
		}

	}

	/**
	 * Print Theme Manager
	 *
	 * @since 2.0.0
	 */
	function manager_do_page() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'hybrid-core' ) );
		}

		?>
		<div id="hybridextend-theme-mgr" class="wrap">
			<h1><?php echo apply_filters( 'hybridextend_theme_manager_name', __( 'Theme Manager', 'hybrid-core' ) ); ?></h1>
			<?php do_action( 'hybridextend_theme_mgr_page' ); ?>
		</div>
		<?php

	}

	/**
	 * Add Theme Manager Modules
	 *
	 * @since 2.0.0
	 */
	function add_modules() {

		/** Auto Updater **/
		if ( in_array( 'autoupgrade', $this->support ) ) {
			require_once( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/theme-manager/autoupgrade.php' );
			require_once( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/theme-manager/updater.php' );
			new HybridExtend_Theme_Manager_Autoupgrade();
		}

		/** Import **/
		if ( in_array( 'import', $this->support ) ) {
			require_once( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/theme-manager/import.php' );
			new HybridExtend_Theme_Manager_Import();
		}

		/** Export **/
		if ( in_array( 'export', $this->support ) ) {
			require_once( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/theme-manager/export.php' );
			new HybridExtend_Theme_Manager_Export();
		}

	}

	/**
	 * Returns the instance.
	 *
	 * @since 2.0.0
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
global $hybridextend_theme_manager;
$hybridextend_theme_manager = HybridExtend_Theme_Manager::get_instance();