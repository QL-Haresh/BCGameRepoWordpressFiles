<?php
/**
 * Hoot Shortcodes Admin - Shortcode Generator
 * This file is loaded via the 'after_setup_theme' hook at priority '14'
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Shortcodes Admin class. This wraps everything up nicely.
 *
 * @since 1.1.0
 */
class Hoot_Shortcodes_Admin {

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

		/* Add the Shortcode Generator page */
		add_action( 'admin_menu', array( $this, 'scgen_add_page' ) );

		/* Add the required scripts and styles */
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles_scripts' ) );

	}

	/**
	 * Add Shortcode Generator Page
	 *
	 * @since 2.2.5
	 */
	function scgen_add_page() {
		add_theme_page( 'Shortcode Generator', 'Shortcode Generator', 'edit_posts', 'scgenerator', array( $this, 'init' ) );
	}

	/**
	 * Loads the required stylesheets and scripts
	 *
	 * @since 1.1.0
	 */
	function enqueue_admin_styles_scripts( $hook ) {

		if ( 'appearance_page_scgenerator' == $hook ) {

			/* Enqueue Supporting Scripts and Styles */
			wp_enqueue_style( 'font-awesome' ); // hybridextend-font-awesome

			/* Enqueue Shortcode Generator Styles */
			$style_uri = hybridextend_locate_style( HYBRIDEXTEND_CSS . 'shortcode-gen' );
			wp_enqueue_style( 'hoot-shortcode-gen', $style_uri, array(),  HYBRIDEXTEND_VERSION );

			/* Enqueue Shortcode Generator Scripts */
			$script_uri = hybridextend_locate_script( HYBRIDEXTEND_JS . 'shortcode-gen' );
			wp_enqueue_script( 'hoot-shortcode-gen', $script_uri, array( 'jquery','wp-color-picker','hoot-options-media-uploader' ), HYBRIDEXTEND_VERSION, true );

			/* Loads Hoot Options stylesheets and scripts */
			global $hoot_options;
			$hoot_options->enqueue( $hook );

		}

	}

	/**
	 * Init Shortcode Generator
	 * 
	 * @since 1.1.0
	 * @access public
	 * @return void
	 */
	public function init() {

		/* Load the required Options Extension for building options */
		require_once( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/options/init.php' );

		/* Get shortcodes array */
		$this->shortcodes = Hoot_Shortcodes::get_shortcodes_array();

		/* Print Page */
		?>
		<div id="hoot-sc-generator-wrap" class="wrap">
			<h1><?php echo __( 'Creattica Shortcode Generator', 'hybrid-core' ); ?></h1>
			<div id="hoot-sc-generator">
				<div class="hoot-sc-generator-menu">
					<h4><?php _e( 'Select Shortcode', 'hybrid-core' ) ?></h4>
					<div class="hoot-sc-generator-list">
						<ul>
							<?php
							$hoot_scgen_title = '';
							foreach( $this->shortcodes as $key => $settings ) {
								if ( !isset( $settings['type'] ) )
									continue;
								// Skip if internal
								if ( 'shortcode' == $settings['type'] && isset( $settings['options'] ) ) {
									$hoot_scgen_title = ( empty( $hoot_scgen_title ) ) ? $settings['title'] : $hoot_scgen_title;
									echo '<li>'
										. '<a href="#" data-shortcode="' . esc_attr( $key ) . '">'
										. esc_html( $settings['title'] )
										. '</a>'
										. '</li>';
								} elseif ( 'title' == $settings['type'] ) {
									echo '<li class="subhead">' . esc_html( $settings['title'] ) . '</li>';
								}
							} ?>
						</ul>
					</div>
				</div>
				<div class="hoot-sc-generator-content-wrap">
					<div class="hoot-sc-generator-content">
						<h2><?php printf( __( '%s Shortcode', 'hybrid-core' ), "<span>$hoot_scgen_title</span>" ) ?></h2>
						<div class="hoot-sc-generator-content-inner hootoptions">
							<?php $this->fields(); ?>
						</div>
					</div>
				</div>
				<div class="hoot-sc-generator-toolbar"><div class="hoot-sc-generator-toolbar-inner">
					<a href="#" class="button button-primary button-large hoot-sc-generator-insert">Create Shortcode</a>
					<textarea rows="9" readonly="readonly" onclick="this.select()"></textarea>
				</div></div>
			</div>
		</div>
		<?php

	}

	/**
	 * Render Shortcode Generator's Shortcode Fields
	 *
	 * @since 1.1.0
	 */
	function fields() {
		foreach( $this->shortcodes as $key => $settings ) {

			// Skip if not a shortcode
			if ( !isset( $settings['type'] ) || 'shortcode' !== $settings['type'] || !isset( $settings['options'] ) )
				continue;

			// Filter Options
			$check = array();
			foreach( $settings['options'] as $opkey => $opvalue ) {
				if ( isset( $opvalue['id'] ) ) {

					// Check for duplicates
					if ( !in_array( $opvalue['id'], $check ) )
						$check[] = $opvalue['id'];
					else
						unset( $settings['options'][ $opkey ] );

					/* Process Fields (Add required data attributes) */
					if ( 'info' == $opvalue['type'] ) { //Do nothing. Keep this check here.
					}
					// Main Content Field
					elseif ( 'content' == $opvalue['id'] ) {
						$settings['options'][ $opkey ]['data']['sctype'] = 'content';
					}
					// Setup groups as content field
					elseif ( 'group' == $opvalue['type'] ) {
						$settings['options'][ $opkey ]['data']['sctype'] = 'content';
						$settings['options'][ $opkey ]['data']['scname'] = $opvalue['id'];
						foreach( $opvalue['fields'] as $groupkey => $groupfield ) {
							if ( 'info' == $groupfield['type'] ) { // Do nothing. Keep this check here.
							} elseif ( 'content' === $groupfield['id'] ) {
								$settings['options'][ $opkey ]['fields'][ $groupkey ]['data']['subsctype'] = 'content';
							} elseif ( !isset( $groupfield['hide_as_attribute'] ) || true !== $groupfield['hide_as_attribute'] ) {
								$settings['options'][ $opkey ]['fields'][ $groupkey ]['data']['subsctype'] = 'attribute';
								$settings['options'][ $opkey ]['fields'][ $groupkey ]['data']['subscname'] = $groupfield['id'];
							}
						}
					}
					// Attributes Fields
					elseif ( !isset( $opvalue['hide_as_attribute'] ) || true !== $opvalue['hide_as_attribute'] ) {
						$settings['options'][ $opkey ]['data']['sctype'] = 'attribute';
						$settings['options'][ $opkey ]['data']['scname'] = $opvalue['id'];
					}
					// Custom Filtering
					$settings['options'][ $opkey ] = apply_filters( 'hoot_shortcode_generator_single_option', $settings['options'][ $opkey ] );

				}
			}

			// Prepare Options Array for Shortcode
			$options = array();
			$options[] = array(
				'type' => 'html',
				'std'  => '<div class="hoot-sc-section hoot-sc-section-' . esc_attr( $key ) . '" data-shortcode="' . esc_attr( $key ) . '">',
				);
			$options = array_merge( $options, $settings['options'] );
			$options[] = array(
				'type' => 'html',
				'std'  => '</div>',
				);

			// Display Fields
			Hoot_Options::build( $options, array(), 'hoot-scfield-' . $key );

		}
	}

}