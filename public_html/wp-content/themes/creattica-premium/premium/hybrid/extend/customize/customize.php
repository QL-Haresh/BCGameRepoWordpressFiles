<?php
/**
 * HybridExtend Customize framework is an extended version of the
 * Customizer Library v1.3.0, Copyright 2010 - 2014, WP Theming http://wptheming.com
 * and is licensed under GPLv2
 *
 * This file is loaded at 'after_setup_theme' hook with 5 priority.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/** Include HybridExtend Customize files **/
// require_once HYBRIDEXTEND_PREMIUM_DIR . 'customize/functions.php';

/** Include custom controls **/
foreach ( glob( HYBRIDEXTEND_PREMIUM_DIR . 'customize/control-*.php' ) as $file_path ) {
	include_once( $file_path );
}

/**
 * Enqueue scripts to customizer screen
 *
 * @since 2.0.0
 * @return void
 */
function hybridextend_premium_customize_enqueue_scripts() {

	wp_enqueue_style( 'hybridextend-premium-customize-styles', HYBRIDEXTEND_PREMIUM_URI . 'customize/assets/style.css', array(),  HYBRIDEXTEND_VERSION );
	wp_enqueue_script( 'hybridextend-premium-customize-script', HYBRIDEXTEND_PREMIUM_URI . 'customize/assets/script.js', array( 'jquery', 'wp-color-picker', 'customize-controls' ), HYBRIDEXTEND_VERSION, true );

}
// Load scripts at priority 11 so that HybridExtend Customizer Custom Controls have loaded their scripts
add_action( 'customize_controls_enqueue_scripts', 'hybridextend_premium_customize_enqueue_scripts', 11 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since 2.0.0
 * @return void
 */
function hybridextend_premium_customize_preview_js() {

	if ( file_exists( HYBRIDEXTEND_PREMIUM_INC . 'admin/js/customize-preview.js' ) )
		wp_enqueue_script( 'hybridextend_premium_customize_preview', HYBRIDEXTEND_PREMIUM_INCURI . 'admin/js/customize-preview.js', array( 'customize-preview' ), HYBRIDEXTEND_VERSION, true );

}
add_action( 'customize_preview_init', 'hybridextend_premium_customize_preview_js' );