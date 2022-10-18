<?php
/**
 * Builds out customize options
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Configure and add panels, sections, settings/controls for the theme customizer
 *
 * @since 2.0.0
 * @param object $wp_customize The global customizer object.
 * @return void
 */
function hybridextend_customize_register( $wp_customize ) {

	$hybridextend_customize = HybridExtend_Customize::get_instance();
	$options = $hybridextend_customize->get_options();
	if ( empty( $options ) ) {
		return;
	}

	/** Add the panels **/
	if ( !empty( $options['panels'] ) && is_array( $options['panels'] ) ) {
		hybridextend_customize_add_panels( $options['panels'], $wp_customize );
	}

	/** Add the sections **/
	if ( !empty( $options['sections'] ) && is_array( $options['sections'] ) ) {
		hybridextend_customize_add_sections( $options['sections'], $wp_customize );
	}

	/** Exit if no settings to add **/
	if ( empty( $options['settings'] ) || !is_array( $options['settings'] ) )
		return;

	/** Objects added.. Use this hook instead of 'customize_register' hook to remove or modify any Customizer object, and to access the Customizer Manager. For adding, continue using 'customize_register' **/
	do_action( 'hybridextend_customize_registered', $wp_customize, $hybridextend_customize );

	// Sets the priority for each control added
	$loop = 0;

	// Add certain style attributes to allowed kses list (for description etc)
	add_filter( 'safe_style_css', 'hybridextend_customize_display_style_ksescss' );

	/** Loop through each of the settings **/
	foreach ( $options['settings'] as $id => $setting ) :
		if ( isset( $setting['type'] ) ) :

			/** Prepare Setting **/

			// Apply a default sanitization if one isn't set and
			// set blank active_callback if one isn't set
			$setting = wp_parse_args( $setting, array(
				'label'             => '',
				'section'           => '',
				'sanitize_callback' => hybridextend_customize_get_sanitization( $setting['type'], $setting, $id ),
				'active_callback'   => '',
			) );

			// Set Priority (increment priority by 10 to allow child themes to insert controls in between)
			if ( ! isset( $setting['priority'] ) || ! is_numeric( $setting['priority'] ) ) {
				$loop += 10;
				$setting['priority'] = $loop;
			}
			if ( defined( 'HYBRIDEXTEND_DEBUG' ) && true === HYBRIDEXTEND_DEBUG )
				hybridextend_debug_info( "[{$setting['priority']}] {$id}\n" );

			// Set and prepare description
			$setting['description'] = empty( $setting['description'] ) ? '' : $setting['description'];
			$setting['description'] =  ( is_array( $setting['description'] ) ) ? (
										( !empty( $setting['description']['text'] ) ) ? $setting['description']['text'] : ''
										) : $setting['description'];

			/** Add selective refresh if available **/
			// Note: cannot apply selective_refresh for a setting which is used in any control's active_callback functions to determine if that control is active or not.

			if ( !empty( $setting['selective_refresh'] ) && is_array( $setting['selective_refresh'] ) && is_string( $setting['selective_refresh'][0] ) && !empty( $setting['selective_refresh'][1] ) && is_array( $setting['selective_refresh'][1] ) && !empty( $setting['selective_refresh'][1]['selector'] ) && !empty( $setting['selective_refresh'][1]['settings'] ) && !empty( $setting['selective_refresh'][1]['render_callback'] ) ) {
				$setting['transport'] = 'postMessage';
				$wp_customize->selective_refresh->add_partial( $setting['selective_refresh'][0], $setting['selective_refresh'][1] );
			}
			if ( isset( $setting['selective_refresh'] ) ) unset( $setting['selective_refresh'] );

			/** Add the setting **/

			hybridextend_customize_add_setting( $wp_customize, $id, $setting );

			/** Adds control **/

			switch ( $setting['type'] ) :

				/* input Text */
				case 'text':
				case 'url':
				case 'select':
				case 'radio':
				case 'checkbox':
				case 'range':
				case 'dropdown-pages':
					$wp_customize->add_control( $id, $setting );
					break;

				/* Textarea */
				case 'textarea':
					$wp_customize->add_control( $id, $setting );
					break;

				/* Color Picker */
				case 'color':
					$wp_customize->add_control(
						new WP_Customize_Color_Control( $wp_customize, $id, $setting )
					);
					break;

				/* Image Upload */
				case 'image':
					$wp_customize->add_control(
						new WP_Customize_Image_Control( $wp_customize, $id, array(
							'label'             => $setting['label'],
							'section'           => $setting['section'],
							'sanitize_callback' => $setting['sanitize_callback'],
							'priority'          => $setting['priority'],
							'active_callback'   => $setting['active_callback'],
							'description'       => $setting['description']
						) )
					);
					break;

				/* File Upload */
				case 'upload':
					$wp_customize->add_control(
						new WP_Customize_Upload_Control( $wp_customize, $id, array(
							'label'             => $setting['label'],
							'section'           => $setting['section'],
							'sanitize_callback' => $setting['sanitize_callback'],
							'priority'          => $setting['priority'],
							'active_callback'   => $setting['active_callback'],
							'description'       => $setting['description']
						) )
					);
					break;

				/* Allow custom controls to hook into interface */
				default:
					do_action( 'hybridextend_customize_control_interface', $wp_customize, $id, $setting );

			endswitch;

		endif;
	endforeach;

	// Remove style attributes added to allowed kses list (for description etc)
	add_filter( 'safe_style_css', 'hybridextend_customize_display_style_ksescss' );

}

function hybridextend_customize_display_style_ksescss( $styles ) {
	$styles[] = 'display';
	$styles[] = 'list-style'; // list-style-type already in safe list
	return $styles;
};

add_action( 'customize_register', 'hybridextend_customize_register', 99 );

/**
 * Add the customizer panels
 * 
 * @since 2.0.0
 * @param array $panels
 * @return void
 */
function hybridextend_customize_add_panels( $panels, $wp_customize ) {

	$loop = 0;

	foreach ( $panels as $id => $panel ) {
		if ( ! isset( $panel['description'] ) ) {
			$panel['description'] = FALSE;
		}
		if ( ! isset( $panel['priority'] ) || ! is_numeric( $panel['priority'] ) ) {
			$loop += 10;
			$panel['priority'] = $loop;
		}
		if ( defined( 'HYBRIDEXTEND_DEBUG' ) && true === HYBRIDEXTEND_DEBUG )
			hybridextend_debug_info( "Panel [{$panel['priority']}] {$id}\n" );
		$wp_customize->add_panel( $id, $panel );
	}

}

/**
 * Add the customizer sections
 *
 * @since 2.0.0
 * @param array $sections
 * @return void
 */
function hybridextend_customize_add_sections( $sections, $wp_customize ) {

	$loop = 0;

	foreach ( $sections as $id => $section ) {
		if ( ! isset( $section['description'] ) ) {
			$section['description'] = FALSE;
		}
		if ( ! isset( $section['priority'] ) || ! is_numeric( $section['priority'] ) ) {
			$loop += 5;
			$section['priority'] = $loop;
		}
		if ( defined( 'HYBRIDEXTEND_DEBUG' ) && true === HYBRIDEXTEND_DEBUG )
			hybridextend_debug_info( "Section [{$section['priority']}] {$id}\n" );
		$wp_customize->add_section( $id, $section );
	}

}

/**
 * Add the setting and proper sanitization
 *
 * @since 2.0.0
 * @param string $id
 * @param array $setting
 * @return void
 */
function hybridextend_customize_add_setting( $wp_customize, $id, $setting ) {

	$setting_default = array(
		'default'              => NULL,
		'option_type'          => 'theme_mod',
		'capability'           => 'edit_theme_options',
		'theme_supports'       => NULL,
		'transport'            => NULL,
		'sanitize_callback'    => 'wp_kses_post',
		'sanitize_js_callback' => NULL
	);

	// Setting defaults
	$add_setting = array_merge( $setting_default, $setting );

	// Arguments for $wp_customize->add_setting
	$wp_customize->add_setting( $id, array(
			'default'              => $add_setting['default'],
			'type'                 => $add_setting['option_type'],
			'capability'           => $add_setting['capability'],
			'theme_supports'       => $add_setting['theme_supports'],
			'transport'            => $add_setting['transport'],
			'sanitize_callback'    => $add_setting['sanitize_callback'],
			'sanitize_js_callback' => $add_setting['sanitize_js_callback']
		)
	);

}

/**
 * Enqueue scripts to customizer screen
 *
 * @since 2.0.0
 * @return void
 */
function hybridextend_customize_enqueue_scripts() {

	// Enqueue Styles
	wp_enqueue_style( 'font-awesome' ); // hybridextend-font-awesome
	wp_enqueue_style( 'hybridextend-customize-styles', HYBRIDEXTEND_URI . 'customize/assets/style.css', array(),  HYBRIDEXTEND_VERSION );

	// Enqueue Scripts
	wp_enqueue_script( 'hybridextend-customize-script', HYBRIDEXTEND_URI . 'customize/assets/script.js', array( 'jquery', 'wp-color-picker', 'customize-controls' ), HYBRIDEXTEND_VERSION, true );

	// Localize Script
	$data = apply_filters( 'hybridextend_customize_control_footer_js_data_object', array() );
	global $wp_version;
	$data['bcomp'] = ( version_compare( $wp_version, '4.3', '>=' ) ) ? 'no' : 'yes'; // Compare to 4.3 and not 4.3.0
	if ( is_array( $data ) && !empty( $data ) )
		wp_localize_script( 'hybridextend-customize-script', '_hybridextend_customize_data', $data );

}
// Load scripts at priority 11 so that HybridExtend Customizer Custom Controls have loaded their scripts
add_action( 'customize_controls_enqueue_scripts', 'hybridextend_customize_enqueue_scripts', 11 );