<?php
/**
 * HTML attribute filters.
 * Most of these functions filter the generic values from the framework found in hybrid/inc/functions-attr.php
 * Attributes for non-generic structural elements (mostly theme specific) can be loaded in this file.
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/* Add Filters */
add_filter( 'hybrid_attr_content', 'hoot_theme_premium_attr_content' );
add_filter( 'hybrid_attr_loop-meta-wrap', 'hoot_theme_premium_attr_loop_meta_wrap', 9 );
add_filter( 'hybrid_attr_leftbar-inner', 'hoot_theme_premium_attr_leftbar_inner', 15 );

/* Misc Filters */
add_filter( 'hybrid_attr_vcard-img', 'hoot_theme_attr_vcard_img' );

/**
 * Modify Main content container of the page attributes.
 *
 * @since 1.6
 * @access public
 * @param array $attr
 * @return array
 */
function hoot_theme_premium_attr_content( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];

	if ( is_404() && 'custom' == hoot_get_mod('404_page') )
		$attr['class'] .= ' custom-404-content';

	return $attr;
}

/**
 * Loop meta attributes.
 *
 * @since 1.0
 * @param array $attr
 * @return array
 */
function hoot_theme_premium_attr_loop_meta_wrap( $attr ) {
	$attr['id'] = 'loop-meta';
	// $attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];

	/* Overwrite free versions default class pageheader-bg-default */
	$background = hoot_get_mod( 'pageheader_background_location' );
	if ( empty( $background ) )
		$background = 'stretch';
	$attr['class'] = " loop-meta-wrap pageheader-bg-{$background}";

	return $attr;
}

/**
 * Leftbar inner attributes.
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */

function hoot_theme_premium_attr_leftbar_inner( $attr ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	if ( hybridextend_theme_supports( 'hybridextend-waypoints', 'sticky-leftbar' ) && !hoot_get_mod('disable_sticky_leftbar') )
		$attr['class'] .= ' hoot-sticky-leftbar';
	return $attr;
}

/**
 * vcard Image
 *
 * @since 1.0
 * @access public
 * @param array $attr
 * @return array
 */
function hoot_theme_attr_vcard_img( $attr ) {
	// $attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];
	$attr['alt'] = __( 'vCard Image', 'creattica-premium' );
	$attr['itemprop'] = 'image';
	return $attr;
}