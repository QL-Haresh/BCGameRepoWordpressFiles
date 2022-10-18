<?php
/**
 * Functions for outputting common site data in the `<head>` area of a site.
 *
 * @package    HybridCore
 * @subpackage Includes
 * @author     Justin Tadlock <justintadlock@gmail.com>
 * @copyright  Copyright (c) 2008 - 2017, Justin Tadlock
 * @link       https://themehybrid.com/hybrid-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Adds common theme items to <head>.
add_action( 'wp_head', 'hybrid_meta_charset',   0 );
add_action( 'wp_head', 'hybrid_meta_viewport',  1 );
add_action( 'wp_head', 'hybrid_meta_generator', 1 );
add_action( 'wp_head', 'hybrid_link_pingback',  3 );

/**
 * Adds the meta charset to the header.
 *
 * @since  2.0.0
 * @access public
 * @return void
 */
function hybrid_meta_charset() {

	printf( '<meta charset="%s" />' . "\n", esc_attr( get_bloginfo( 'charset' ) ) );
}

/**
 * Adds the meta viewport to the header.
 *
 * @since  2.0.0
 * @access public
 */
function hybrid_meta_viewport() {

	echo '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";
}

/**
 * Adds the theme generator meta tag.  This is particularly useful for checking theme users' version
 * when handling support requests.
 *
 * @since  3.0.0
 * @access public
 * @return void
 */
function hybrid_meta_generator() {
	$theme     = wp_get_theme( get_template() );
	$generator = sprintf( '<meta name="generator" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );

	echo apply_filters( 'hybrid_meta_generator', $generator );
}

/**
 * Adds the pingback link to the header.
 *
 * @since  2.0.0
 * @access public
 * @return void
 */
function hybrid_link_pingback() {

	if ( 'open' === get_option( 'default_ping_status' ) )
		printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}