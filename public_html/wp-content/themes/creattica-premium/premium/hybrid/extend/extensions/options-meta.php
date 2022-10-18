<?php
/**
 * Meta Admin for Hoot Options framework.
 * This file is loaded at 'after_setup_theme' hook with 14 priority.
 *
 * Even though Hoot Options is loaded at 'after_setup_theme' hook with 4 priority, it doesn't fire untill
 * the 'init' hook. So this extension should take care of hooking into proper actions to display options.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Helper function to return the theme meta option value.
 * If no value has been saved, it returns $default.
 * If no $default provided, it checks the default meta options array.
 *
 * Similar to 'hoot_get_mod()' in 'hoot/options/includes/helpers.php'
 * 
 * @since 1.1.0
 * @access public
 * @param string $name Use empty string to return entire option values array.
 * @param int $post_id (Optional - if used within the loop)
 * @param mixed $default
 * @param string $meta_key The meta key from which to get values. Themes should use default 'main_box'
 *                         as ID for the main meta options box id for brevity.
 *                         (in hoot-theme/admin/meta-options.php)
 * @return mixed
 */
function hoot_get_meta_option( $name, $post_id = 0, $default = NULL, $meta_key = 'main_box' ) {

	/*** Return meta option value if set ***/

	// cache
	static $options = NULL;

	// If no post id, get current post's ID (in the loop)
	$post_id = intval( $post_id );
	if ( empty( $post_id ) )
		$post_id = get_the_ID();

	// If single meta option is needed
	if ( !empty( $post_id ) ) {

		// Populate cache if not available
		$options[ $post_id ] = empty( $options[ $post_id ] ) ? array() : $options[ $post_id ];
		if ( !isset( $options[ $post_id ][ $meta_key ] ) ) {
			// Use post id instead of global posts else loops like WC Shop will return 'product'
			$post_type = get_post_type( $post_id );
			$values = get_post_meta( $post_id, '_hoot_meta_' . $meta_key , true );
			$values = apply_filters( 'hoot_get_meta_option_value', $values, $post_id, $post_type, $meta_key );
			$options[ $post_id ][ $meta_key ] = get_post_meta( $post_id, '_hoot_meta_' . $meta_key , true );
		}

		// Return all meta options if requested
		if ( empty( $name ) )
			return $options[ $post_id ][ $meta_key ];

		// Return single meta option if available
		if ( isset( $options[ $post_id ][ $meta_key ][ $name ] ) ) {
			// Add exceptions: If a value has been set but is empty, this gives the option to return default values in such cases. Simply return $override as (bool)true.
			$override = apply_filters( 'hoot_get_meta_option_empty_values', false, $options[ $post_id ][ $meta_key ][ $name ] );
			if ( $override !== true )
				return $options[ $post_id ][ $meta_key ][ $name ];
		}

	}

	/*** Return default if provided ***/

	if ( $default !== NULL )
		return $default;

	/*** Return default theme meta value ***/

	static $defaults = NULL; // cache

	// Get the default values from meta options array
	if ( $defaults === NULL ) {
		global $hoot_options_meta_admin;
		$options_array = $hoot_options_meta_admin->get_options();

		if ( !empty( $options_array ) ) {
			foreach ( $options_array as $post_type => $metaboxes ) {
				foreach ( $metaboxes as $metabox_id => $metabox_settings ) {
					foreach ( $metabox_settings['options'] as $op ) {

						if ( isset( $op['id'] ) && isset( $op['default'] ) && isset( $op['type'] ) ) {
							// Do we need sanitization here? This is not user input data.
							if ( has_filter( 'hybridextend_sanitize_' . $op['type'] ) ) {
								$defaults[ $post_type ][ $metabox_id ][ $op['id'] ] = apply_filters( 'hybridextend_sanitize_' . $op['type'], $op['default'], $op );
							} else {
								$defaults[ $post_type ][ $metabox_id ][ $op['id'] ] = $op['default'];
							}
						}

					}
				}
			}
		}

	}

	if ( !empty( $post_id ) ) {
		$current_post_type = get_post_type( $post_id );
		if ( isset( $defaults[ $current_post_type ][ $meta_key ][ $name ] ) )
			return $defaults[ $current_post_type ][ $meta_key ][ $name ];
	}

	/*** We dont have anything! ***/
	return false;
}

/**
 * Meta Admin class to handle Metaboxes in the admin
 * 
 * @since 1.1.0
 */
class Hoot_Options_Meta_Admin {

	/**
	 * Holds the instance of this class.
	 *
	 * @since 1.1.0
	 * @access private
	 * @var object
	 */
	private static $instance;

	/**
	 * Metabox Options Array
	 *
	 * @since 1.1.0
	 * @access private
	 * @var array
	 */
	private $meta_options = array();

	/**
	 * Fire up the Hoot Meta boxes
	 *
	 * @since 1.1.0
	 */
	public function __construct() {

		if ( !is_admin() )
			return;

		/* Load the required Options Extension for building options */
		require_once( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/options/init.php' );
		add_action( 'hoot_meta_options_enqueue', array( $this, 'options_enqueue' ), 10, 3 );

		/* Initialize Options Array */
		$this->meta_options = array();

		/* Hook in to create new metabozes */
		add_action( 'init', array( $this, 'add_meta_boxes' ) );

	}

	/*
	 * Hook in to create new metaboxes
	 *
	 * @since 2.0.0
	 */
	function add_meta_boxes() {

		/* Checks if meta options are available */
		if ( !empty( $this->meta_options ) ) {

			/* Hook to action 'add_meta_boxes' for required post types */
			foreach ( $this->meta_options as $post_type => $options ) {
				add_action( "add_meta_boxes_{$post_type}", array( $this, 'add_meta_box' ), 10, 1 ); 
			} 

			/* Save meta values properly */
			add_action( 'save_post', array( $this, 'save_postdata' ) ); 

			/* Add the required scripts and styles */
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles_scripts' ) );

		}

	}

	/*
	 * Define default metabox settings
	 *
	 * @since 1.1.0
	 */
	function metabox_settings() {

		$metabox = array(
			'title'    => __( 'Options', 'hybrid-core' ),
			'context'  => 'normal', // 'normal', 'advanced', or 'side'
			'priority' => 'high', // 'high', 'core', 'default' or 'low'
			'options'  => array()
		);

		return $metabox;
	}

	/**
	 * Create Meta Boxes.
	 *
	 * @since 1.1.0
	 * @param object $post
	 */
	function add_meta_box( $post ) {

		// Get post type. Return false or string.
		$post_type = get_post_type( $post );

		// Add all metaboxes for $post_type
		if ( $post_type &&
			 isset( $this->meta_options[ $post_type ] ) &&
			 is_array( $this->meta_options[ $post_type ] )
			 ) :

			if ( current_user_can( 'edit_post_meta', $post->ID ) ) {
				$metabox_default_settings = $this->metabox_settings();

				foreach ( $this->meta_options[ $post_type ] as $metabox_id => $metabox_settings ) {
					$metabox_settings = wp_parse_args( $metabox_settings, $metabox_default_settings );

					add_meta_box( HYBRIDEXTEND_THEMESLUG . '-' . $metabox_id,
								  $metabox_settings['title'],
								  array( $this, 'meta_box' ), // Callback Function
								  $post_type,
								  $metabox_settings['context'],
								  $metabox_settings['priority'],
								  array( $post_type, $metabox_id ) // Callback Args
								  );
				}
			}

		endif;

	}

	/**
	 * Loads the required stylesheets and scripts
	 *
	 * @since 1.1.0
	 */
	function enqueue_admin_styles_scripts( $hook ) {
		if ( $hook == 'post-new.php' || $hook == 'post.php' ) :

			$screen = get_current_screen();
			$post_type = $screen->post_type;
			$supported_post_types = array_keys( $this->meta_options );

			if ( in_array( $post_type, $supported_post_types ) ) :
				wp_enqueue_style( 'font-awesome' ); // hybridextend-font-awesome
			endif;

			do_action( 'hoot_meta_options_enqueue', $hook, $post_type, $supported_post_types );

		endif;
	}

	/**
	 * Loads Hoot Options stylesheets and scripts
	 *
	 * @since 2.0.0
	 */
	function options_enqueue( $hook, $post_type, $supported_post_types ) {
		if ( in_array( $post_type, $supported_post_types ) ) {
			global $hoot_options;
			$hoot_options->enqueue( $hook );
		}
	}

	/**
	 * Builds out the meta box.
	 *
	 * @since 1.1.0
	 * @param object $post
	 * @param array $args arguments passed to callback function
	 */
	function meta_box( $post, $args ) {
		$post_type = $args['args'][0];
		$metabox_id = $args['args'][1];
		$meta_values = get_post_meta( $post->ID, '_hoot_meta_' . $metabox_id , true );

		wp_nonce_field( basename( __FILE__ ), 'hoot-meta-box-nonce' ); ?>

		<label class="screen-reader-text" for="excerpt"><?php echo $this->meta_options[ $post_type ][ $metabox_id ]['title'] ?></label>

		<div id="<?php echo ( sanitize_html_class( 'hoot-meta-box-' . $metabox_id ) ); ?>" class="hoot-meta-box hootoptions">
			<?php
			if ( !empty ( $this->meta_options[ $post_type ][ $metabox_id ]['options'] ) )
			Hoot_Options::build(
				$this->meta_options[ $post_type ][ $metabox_id ]['options'],
				$meta_values,
				'hoot' . '-' . $metabox_id
				);
			?>
		</div>

	<?php
	}

	/**
	 * Save the meta box.
	 *
	 * @since 1.1.0
	 * @access public
	 * @param int $post_id The ID of the current post being saved.
	 * @param object $post The post object currently being saved.
	 */
	function save_postdata( $post_id, $post = '' ){

		/* Verify nonce */
		if ( ! isset( $_POST['hoot-meta-box-nonce'] ) || ! wp_verify_nonce( $_POST['hoot-meta-box-nonce'], basename( __FILE__ ) ) )
			return $post_id;

		/* verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		/* Security - Check permissions */
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* Check User Permissions */
		if ( !current_user_can( 'edit_post_meta', $post_id ) )
			return $post_id;

		// OK, we're authenticated

		/* Build save array */
		if ( isset( $_POST['post_type'] ) && isset( $this->meta_options[ $_POST['post_type'] ] ) ):
			foreach ( $this->meta_options[ $_POST['post_type'] ] as $metabox_id => $metabox_settings ) {

				$input = $_POST[ 'hoot' . '-' . $metabox_id ];
				$clean = array();
				foreach ( $metabox_settings['options'] as $option ) {
					$validated = Hoot_Options::validate_option( $input, $option );
					if ( is_array( $validated ) )
						$clean = array_merge( $clean, $validated );
				}

				update_post_meta( $post_id, '_hoot_meta_' . $metabox_id, $clean );
			}
		endif;

	}

	/**
	 * Add meta option to meta_options Array
	 *
	 * @since 2.0.0
	 * @access public
	 * @return void
	 */
	public function add_options( $meta_options = array() ) {
		$meta_options = apply_filters( 'hoot_theme_meta_options' , $meta_options );
		$this->meta_options = array_merge( $this->meta_options, $meta_options );
	}

	/**
	 * Get meta_options Array
	 *
	 * @since 2.0.0
	 * @access public
	 * @return array
	 */
	public function get_options() {
		return $this->meta_options;
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
global $hoot_options_meta_admin;
$hoot_options_meta_admin = Hoot_Options_Meta_Admin::get_instance();

/* Hook into this action to add meta options */
do_action( 'hoot_options_meta_admin_loaded', $hoot_options_meta_admin );