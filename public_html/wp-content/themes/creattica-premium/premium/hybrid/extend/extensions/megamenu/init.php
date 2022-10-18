<?php
/**
 * Megamenu Extension
 * This file is loaded at 'after_setup_theme' hook with 14 priority.
 * @credit Modified code from http://wordpress.stackexchange.com/questions/33342/
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * HybridExtend Megamenu class.
 *
 * @since 1.1.0
 */
class HybridExtend_Megamenu {

	/**
	 * Holds the instance of this class.
	 *
	 * @since 1.1.0
	 * @access private
	 * @var object
	 */
	private static $instance;

	/**
	 * Holds the menu option fields
	 *
	 * @since 1.1.0
	 * @access private
	 * @var object
	 */
	private $megamenu_options;

	/**
	 * Hook into actions and filters
	 * 
	 * @since 1.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Initialize Options Array */
		$this->megamenu_options = array();

		if ( is_admin() ) {

			/* Add the required scripts and styles */
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles_scripts' ) );

			/* Tell WordPress to use our Walker for Edit Nav */
			global $wp_version;
			if ( version_compare( preg_replace("/[^0-9\.]/", "", $wp_version ), '5.4', '<') )
				add_filter( 'wp_edit_nav_menu_walker', array( $this, 'modify_edit_nav_menu_walker' ) , 100, 2 );

			/* Create custom menu admin fields */
			add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'add_menu_item_custom_fields' ), 10, 4 );

			/* Adds value of new fields to $item object that will be passed to our Walker */
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'setup_nav_item' ) , 100 );

			/* Save value of our custom fields */
			add_action( 'wp_update_nav_menu_item', array( $this, 'update_nav_menu' ), 100, 3 );

		}

	}

	/**
	 * Loads the required stylesheets and scripts
	 *
	 * @since 1.1.0
	 */
	function enqueue_admin_styles_scripts( $hook ) {

		if ( 'nav-menus.php' == $hook ) {

			$style_uri = hybridextend_locate_style( HYBRIDEXTEND_CSS . 'megamenu' );
			wp_enqueue_style( 'hybridextend-megamenu', $style_uri, array(),  HYBRIDEXTEND_VERSION );

			$script_uri = hybridextend_locate_script( HYBRIDEXTEND_JS . 'megamenu' );
			wp_enqueue_script( 'hybridextend-megamenu', $script_uri, array( 'jquery' ), HYBRIDEXTEND_VERSION, true ); // possible future dependency: 'wp-color-picker'

			wp_enqueue_style( 'font-awesome' );

		}

	}

	/**
	 * Tell WordPress to use our Walker for Edit Menu
	 *
	 * @since 1.1.0
	 */
	function modify_edit_nav_menu_walker( $walker, $menu_id ) {
		if ( !class_exists( 'HybridExtend_Edit_Menu_Walker' ) )
			require_once( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/megamenu/class-hybridextend-edit-menu-walker.php' );
		return 'HybridExtend_Edit_Menu_Walker';
	}

	/**
	 * Tell WordPress to use our Walker for Edit Menu
	 *
	 * @since 2.2.7
	 */
	function add_menu_item_custom_fields( $item_id, $item, $depth, $args ) {
		$hybridextend_megamenu_options = $this->get_options();
		include( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/megamenu/part-edit-menu.php' );
	}

	/**
	 * Add value of our custom fields to $item object that will be passed to our walker for Edit Menu
	 *
	 * @since 1.1.0
	 */
	function setup_nav_item( $menu_item ) {
		$values = get_post_meta( $menu_item->ID, '_menu-item-hybridextend_megamenu', true );
		if ( !is_array( $values ) ) $values = array(); // Needed to prevent illegal offstring in certain xampp for $values[ $key ] = '';
		foreach ( $this->megamenu_options as $key => $option ) {
			if ( !isset( $values[ $key ] ) ) {
				$values[ $key ] = '';
				// $values[ $key ] = ( isset( $option['std'] ) ) ? $option['std'] : ''; // Std doesnt work for newly added items
			}
		}
		$menu_item->hybridextend_megamenu = $values;
		return $menu_item;
	}

	/*
	 * Save and Update the Custom Fields in Navigation Menu Items
	 * 
	 * @since 1.1.0
	 * @param int $menu_id
	 * @param int $menu_item_db
	 */
	function update_nav_menu( $menu_id, $menu_item_db_id, $args ) {
		$values = array();
		foreach ( $this->megamenu_options as $key => $option ) {
			$values[ $key ] = isset( $_POST[ 'menu-item-' . $key ][ $menu_item_db_id ] ) ? 
							  $_POST[ 'menu-item-' . $key ][ $menu_item_db_id ] :
							  '';
			if ( isset( $option['top_level'] ) && true === $option['top_level'] )
				$values[ $key . '_top_level' ] = '1';
		}
		update_post_meta( $menu_item_db_id, '_menu-item-hybridextend_megamenu', $values );
	}

	/**
	 * Add menu option to $megamenu_options Array
	 *
	 * @since 2.0.0
	 * @access public
	 * @return void
	 */
	public function add_options( $megamenu_options = array() ) {
		$megamenu_options = apply_filters( 'hybridextend_megamenu_options' , $megamenu_options );
		$this->megamenu_options = array_merge( $this->megamenu_options, $megamenu_options );
	}

	/**
	 * Get options Array
	 *
	 * @since 2.0.0
	 * @access public
	 * @return array
	 */
	public function get_options() {
		return $this->megamenu_options;
	}

	/**
	 * Returns the instance.
	 *
	 * @since 1.1.0
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
global $hybridextend_megamenu;
$hybridextend_megamenu = HybridExtend_Megamenu::get_instance();

/* Hook into this action to add meta options */
do_action( 'hybridextend_megamenu_loaded', $hybridextend_megamenu );