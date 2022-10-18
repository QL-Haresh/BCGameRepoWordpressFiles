<?php
/**
 * Custom Post Types Extension
 * This file is loaded at 'after_setup_theme' hook with 14 priority.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * HybridExtend CPT class. This wraps everything up nicely.
 *
 * @since 1.1.0
 */
final class HybridExtend_CPT {

	/**
	 * The one instance of HybridExtend_CPT.
	 *
	 * @since 2.0.0
	 * @access private
	 * @var HybridExtend_CPT The one instance for the singleton.
	 */
	private static $instance;

	/**
	 * The array for storing $options.
	 *
	 * @since 2.0.0
	 * @access public
	 * @var array Holds the options array.
	 */
	public $options = array();

	/**
	 * Hook into actions and filters
	 * 
	 * @since 1.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Initialize Options Array */
		$this->options = array();

		/* 'register_post_type()' should only be invoked through the 'init' action */
		add_action( 'init', array( $this, 'register_post_type' ) );

		/* Add custom columns to 'Edit' screen for posts and pages */
		if ( is_admin() ) {

			if ( hybridextend_theme_supports( 'post-thumbnails', 'post' ) ) {
				add_filter( 'manage_posts_columns', array( $this, 'add_custom_columns' ) );
				add_action( 'manage_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );
			}
			if ( hybridextend_theme_supports( 'post-thumbnails', 'page' ) ) {
				add_filter( 'manage_pages_columns', array( $this, 'add_custom_columns' ) );
				add_action( 'manage_pages_custom_column', array( $this, 'custom_columns' ), 10, 2 );
			}

		}

	}

	/**
	 * Register Custom Post Types for the theme
	 * 
	 * @since 1.1.0
	 */
	function register_post_type() {

		/* Process and register CPT */
		foreach ( $this->options as $post_type => $args ) {

			// Default Labels
			$labels = ( isset( $args['labels'] ) ) ? $args['labels'] : array();
			$singular = ( isset( $labels['singular_name'] ) ) ? $labels['singular_name'] : __( 'Item', 'hybrid-core' );
			$plural = ( isset( $labels['name'] ) ) ? $labels['name'] : __( 'Item', 'hybrid-core' );
			$args['labels'] = wp_parse_args( $labels, array(
				'name' 				=> $plural,
				'singular_name' 	=> $singular,
				'menu_name'			=> $plural,
				'name_admin_bar'	=> $plural,
				'all_items'			=> sprintf( __( 'All %s', 'hybrid-core' ), $plural ),
				'add_new' 			=> sprintf( __( 'Add New %s', 'hybrid-core' ), $singular ),
				'add_new_item' 		=> sprintf( __( 'Add New %s', 'hybrid-core' ), $singular ),
				'edit_item' 		=> sprintf( __( 'Edit %s', 'hybrid-core' ), $singular ),
				'new_item' 			=> sprintf( __( 'New %s', 'hybrid-core' ), $singular ),
				'view_item' 		=> sprintf( __( 'View %s', 'hybrid-core' ), $singular ),
				'search_items' 		=> sprintf( __( 'Search %s', 'hybrid-core' ), $plural ),
				'not_found' 		=> sprintf( __( 'No %s found', 'hybrid-core' ), $singular ),
				'not_found_in_trash'=> sprintf( __( 'No %s found in Trash', 'hybrid-core' ), $singular ),
				'parent_item_colon' => sprintf( __( 'Parent %s', 'hybrid-core' ), $singular ),
				) );

			// Default Args
			$args = wp_parse_args( $args, array(
				'public' 				=> false, // Default: false. Governs defaults for exclude_from_search, publicly_queryable, show_in_nav_menus, show_ui
				//'exclude_from_search'	=> true, // Default: value of the opposite of public argument
				//'publicly_queryable'	=> false, // Default: value of public argument
				//'show_ui' 			=> false, // Default: value of public argument
				//'show_in_nav_menus' 	=> false, // Default: value of public argument
				//'show_in_menu' 		=> false, // Default: value of show_ui argument
				'show_in_admin_bar'		=> false, // Default: value of the show_in_menu argument
				//'menu_position' 		=> null, // Default: null - defaults to below Comments (5-100)
				//'menu_icon' 			=> null, // Default: null - defaults to the posts icon
				//'capability_type' 	=> 'post', // Default: "post"
				//'hierarchical' 		=> false, // Default: false
				//'supports' 			=> array( 'title', 'editor' ), // Default: title and editor ( title, editor, author, thumbnail, excerpt, trackbacks, custom-fields, comments, revisions, page-attributes (menu order, hierarchical must be true to show Parent option) post-formats )
				'taxonomies'			=> array(), // An array of registered taxonomies that will be used with this post type
				//'has_archive'			=> false, // Default: false
				//'query_var' 			=> true, // Default: true (Redundant if the 'publicly_queryable' parameter is set false)
				//'can_export'			=> true, // Default: true
				) );

			// Register post type
			register_post_type( $post_type, $args );

		}

	}

	/**
	 * Add custom column to Edit screen in admin
	 *
	 * @since 1.1.0
	 * @param array $custcolumn
	 * @return array
	 */
	function add_custom_columns( $custcolumn ) {
		global $post;

		if ( $post->post_type == 'post' || $post->post_type == 'page' )
			$custcolumn['hybridextend_image_preview'] = __( 'Image Preview', 'hybrid-core' );

		return $custcolumn;
	}


	/**
	 * Add custom column content to Edit screen in admin
	 *
	 * @since 1.1.0
	 * @param array $custcolumn
	 * @param int $post_id
	 */
	function custom_columns( $custcolumn, $post_id ) {

		if ( $custcolumn  == 'hybridextend_image_preview' )
			echo get_the_post_thumbnail( $post_id, 'thumbnail', array( 'style' => ' width: 75px; height: auto; ' ) );

	}

	/**
	 * Add option to Options Array
	 *
	 * @since 2.0.0
	 * @access public
	 * @return void
	 */
	public function add_options( $options = array() ) {
		$options = apply_filters( 'hybridextend_theme_cpt' , $options );
		$this->options = array_merge( $this->options, $options );
	}

	/**
	 * Get options Array
	 *
	 * @since 2.0.0
	 * @access public
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Instantiate or return the one HybridExtend_CPT instance.
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
global $hybridextend_cpt;
$hybridextend_cpt = HybridExtend_CPT::get_instance();

/* Hook into this action to add options */
do_action( 'hybridextend_cpt_loaded', $hybridextend_cpt );