<?php
/**
 * Enqueue scripts and styles for the premium theme.
 * This file is loaded via the 'after_setup_theme' hook at priority '10'
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/* Add custom scripts. Set priority to 10 so that the theme's main js is loaded after at priority 11 */
add_action( 'wp_enqueue_scripts', 'hoot_premium_base_enqueue_scripts', 10 );

/* Add extension to theme's main js. Set priority to 12 so that the theme's main js is loaded befire at priority 11 */
add_action( 'wp_enqueue_scripts', 'hoot_premium_extend_enqueue_scripts', 12 );

/* Add custom styles. Set priority to default 10 so that theme's main style is loaded after these styles (at priority 11), and can thus easily override any style without over-qualification.  */
add_action( 'wp_enqueue_scripts', 'hoot_premium_base_enqueue_styles', 10 );

/* Add hootdata variables to pass data to theme javascript */
add_filter( 'hoot_localize_theme_script', 'hoot_premium_localize_theme_script' );

/**
 * Load scripts for the front end.
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hoot_premium_base_enqueue_scripts() {

	/* Load lightGallery if 'light-gallery' or 'hoot-lightbox' is active. */
	if ( !hoot_get_mod('disable_lightbox') &&
		( current_theme_supports( 'hoot-lightbox' ) || current_theme_supports( 'light-gallery' ) )
		) {
		$script_uri = hybridextend_locate_script( 'js/lightGallery' );
		wp_enqueue_script( 'lightGallery', $script_uri, array( 'jquery' ), '1.1.4', true );
	}

	/* Load isotope if needed */
	$archive_type = hoot_get_mod( 'archive_type' );
	// if ( is_home() || is_archive() ) // 'Blog' Widget may be used anywhere
	if ( $archive_type == 'mosaic2' || $archive_type == 'mosaic3' || $archive_type == 'mosaic4' ) {
		$script_uri = hybridextend_locate_script( 'js/isotope.pkgd' );
		wp_enqueue_script( 'isotope', $script_uri, array(), '2.1.1', true );
	}

	/* Load circliful */
	if ( hybridextend_theme_supports( 'hybridextend-waypoints', 'circliful' ) ) {
		$script_uri = hybridextend_locate_script( 'js/jquery.circliful' );
		wp_enqueue_script( 'jquery.circliful', $script_uri, array(), '20160309', true );
	}

}

/**
 * Load scripts for the front end.
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hoot_premium_extend_enqueue_scripts() {
	/* Load Extension to Theme Javascript (add dependency so extension is loaded after hoot-theme) */
	$script_uri = hybridextend_locate_script( 'js/hoot.theme.premium' );
	wp_enqueue_script( 'hoot-theme-premium', $script_uri, array( 'hoot-theme' ), HYBRIDEXTEND_THEME_VERSION, true );
	/* Pass data to hybridextend scrollpoints */
	$padding = intval( hoot_get_mod( 'scrollpadding' ) );
	$data = ( empty( $padding ) ) ? array() : array( 'customScrollerPadding' => $padding );
	$data = apply_filters( 'hybridextend_localize_scrollpoints', $data );
	if ( !empty( $data ) )
		wp_localize_script( 'hybridextend-scrollpoints', 'hybridExtendData', $data );
}

/**
 * Load stylesheets for the front end.
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hoot_premium_base_enqueue_styles() {

	/* Load lightGallery style if 'light-gallery' or 'hoot-lightbox' is active. */
	if ( !hoot_get_mod('disable_lightbox') &&
		( current_theme_supports( 'hoot-lightbox' ) || current_theme_supports( 'light-gallery' ) )
		) {
		$style_uri = hybridextend_locate_style( 'css/lightGallery' );
		wp_enqueue_style( 'lightGallery', $style_uri, false, '1.1.4' );
	}

}

/**
 * Add hootdata variables to pass data to theme javascript
 *
 * @since 1.0
 * @access public
 * @param array $hootdata
 * @return array
 */
function hoot_premium_localize_theme_script( $hootdata ) {

	/* Init Lightbox */
	$hootdata['lightbox'] = ( current_theme_supports( 'hoot-lightbox' ) && !hoot_get_mod( 'disable_lightbox' ) ) ? 'enable' : 'disable';
	// Disable Individual lightbox components (useful for child themes)
	// $hootdata['lightboxImg'] = 'disable';
	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'carousel' ) ) 
		$hootdata['lightboxWpGal'] = 'disable';

	/* Init Gallery (added for brevity) */
	$hootdata['lightGallery'] = ( current_theme_supports( 'light-gallery' ) && !hoot_get_mod( 'disable_lightbox' ) ) ? 'enable' : 'disable';

	/* Init Isotope */
	$hootdata['isotope'] = 'enable';

	/* Add sticky header offset */
	$hootdata['stickyHeaderOffset'] = ( is_active_sidebar( 'hoot-leftbar-top' ) ) ? 0 : -10;

	return $hootdata;

}