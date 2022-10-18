<?php
/**
 * Hoot Premium Extension hooked into the theme and framework
 * This file is loaded at the end of 'include/hoot-theme.php'
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
 * Setup Hoot Premium Framework
 *
 * @since 1.0
 * @return void
 */
function hoot_premium_frameworkextend() {

	global $hybrid_extend_premium;

	/* Load the Extend framework */
	require_once( trailingslashit( get_template_directory() ) . 'premium/hybrid/extend/extend-premium.php' );

	/* Launch the Extend framework. */
	$hybrid_extend_premium = new Hybrid_Extend_Premium();

}
add_action( 'hybrid_after_setup', 'hoot_premium_frameworkextend' );

/**
 * Setup Hoot Premium Theme Class
 *
 * @since 1.0
 * @return void
 */
function hoot_theme_premium() {

	global $hoot_theme_premium;

	/* Load the Theme files */
	require_once( trailingslashit( get_template_directory() ) . 'premium/include/hoot-theme-premium.php' );

	/* Launch the Theme Premium */
	$hoot_theme_premium = new Hoot_Theme_Premium();

}
add_action( 'hoot_theme_after_setup', 'hoot_theme_premium' );





/**
 * FIX: hoot_get_mod (get_theme_mod) does not give live value (in customizer screen)
 * when used before 'wp_loaded' action @priority 10
 *
 * Currently customizer-options adds conditional options based on theme support for
 * 'hybridextend-scrollpoints' and 'hybridextend-waypoints' features
 * 
 * We cannot add following hook functions inside customizer-options.php as that file
 * itself is loaded on 'after_setup_theme' hook. (hooking into same hook from within
 * while hook is being executed leads to undesirable effects as
 * GLOBALS[$wp_filter]['after_setup_theme'] has already been ksorted)
 *
 * @todo: find a better home for these functions, and/or find a better logic to display
 * options (based on support for a feature) in customizer-options.php
 */

// is_customize_preview() can be used since WordPress 4.0, else use global $wp_customize;
global $wp_customize;
if ( ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) || isset( $wp_customize ) ) :

	// Hence we remove hybridextend-scrollpoints and hybridextend-waypoints theme support (added at
	// after_setup_theme @priority 10) so scroller.php is not loaded by hoot framework (at
	// after_setup_theme @priority 14).
	add_action( 'after_setup_theme', 'hoot_customizer_fix_remove_support', 12 );

	// Re-add theme support for hybridextend-scrollpoints and hybridextend-waypoints (after_setup_theme
	// @priority 14), so customizer settings array (added at init hook @priority 0) generates related
	// options.
	add_action( 'after_setup_theme', 'hoot_customizer_fix_add_support', 16 );

	// Load the scroller.php file at wp hook (after wp_loaded @priority 10) to init the class
	// if get_theme_mod() allows it
	add_action( 'wp', 'hoot_customizer_fix_load_extension' );

endif;
/** Remove Scroller Support **/
function hoot_customizer_fix_remove_support(){
	remove_theme_support( 'hybridextend-scrollpoints' );
	remove_theme_support( 'hybridextend-waypoints' );
}
/** Add Scroller Support **/
function hoot_customizer_fix_add_support(){
	add_theme_support( 'hybridextend-scrollpoints', array( 'goto-top', 'menu-scroll' ) );
	add_theme_support( 'hybridextend-waypoints', array( 'goto-top', 'sticky-leftbar', 'circliful' ) );
}
/** Load Scroller **/
function hoot_customizer_fix_load_extension(){
	if ( current_theme_supports( 'hybridextend-scrollpoints' ) || current_theme_supports( 'hybridextend-waypoints' ) )
		require( HYBRIDEXTEND_PREMIUM_DIR . 'extensions/scroller.php' );
}