<?php
/**
 * Customizer Functions
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Function to modify the settings array and prepare it for Customizer Library Interface functions
 * 
 * @since 2.0.0
 * @access public
 * @param array $settings
 * @return array
 */
function hybridextend_customize_prepare_settings( $settings ){

	// Return array
	$value = array();

	// Unique count to create id
	static $count = 1;

	foreach ( $settings as $key => $setting ) {
		if ( isset( $setting['type'] ) ) :

			$new_value = array();
			$new_value = apply_filters( 'hybridextend_customize_prepare_settings', $new_value, $key, $setting, $count );
			if ( !empty( $new_value ) )
				$value = array_merge( $value, $new_value );
			else
				$value[ $key ] = $setting;

			$count++;

		else:

			// Add setting as is
			$value[ $key ] = $setting;

		endif;
	}

	// Return prepared settings array
	return $value;
}
add_filter( 'hybridextend_customize_add_settings', 'hybridextend_customize_prepare_settings', 999 );

/**
 * Helper function to return defaults
 *
 * @since 2.0.0
 * @param string
 * @return mixed $default
 */
function hybridextend_customize_get_default( $setting ) {

	$hybridextend_customize = HybridExtend_Customize::get_instance();
	$settings = $hybridextend_customize->get_options('settings');

	if ( isset( $settings[$setting]['default'] ) )
		return $settings[$setting]['default'];
	else
		return '';

}

/**
 * Helper function to return choices
 *
 * @since 2.0.0
 * @param string
 * @return mixed $default
 */
function hybridextend_customize_get_choices( $setting ) {

	$hybridextend_customize = HybridExtend_Customize::get_instance();
	$settings = $hybridextend_customize->get_options('settings');

	if ( isset( $settings[$setting]['choices'] ) ) {
		return $settings[$setting]['choices'];
	}

}

/**
 * Helper function to remove all custom theme mods
 *
 * @since 2.0.0
 * @param string
 * @return mixed $default
 */
function hybridextend_remove_theme_mods() {

	$hybridextend_customize = HybridExtend_Customize::get_instance();
	$settings = $hybridextend_customize->get_options('settings');

	if ( !empty( $settings ) && is_array( $settings ) ) {
		foreach( $settings as $id => $setting ) {
			remove_theme_mod( $id );
		}
	}
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since 2.0.0
 * @return void
 */
function hybridextend_customize_preview_js() {

	if ( file_exists( HYBRIDEXTEND_INC . 'admin/js/customize-preview.js' ) )
		wp_enqueue_script( 'hybridextend_customize_preview', HYBRIDEXTEND_INCURI . 'admin/js/customize-preview.js', array( 'customize-preview' ), HYBRIDEXTEND_VERSION, true );

}
add_action( 'customize_preview_init', 'hybridextend_customize_preview_js' );