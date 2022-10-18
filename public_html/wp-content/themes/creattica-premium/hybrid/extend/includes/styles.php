<?php
/**
 * Functions for handling theme main stylesheets in the frontend.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Register Main styles. */
add_action( 'wp_enqueue_scripts', 'hybridextend_register_styles', 0 );

/* Load Main styles. It's a good practice to load any other stylesheet before the main style. Hence users can enqueue custom stylesheets at default priority 10, so that the main style.css is always loaded at the end. */
add_action( 'wp_enqueue_scripts', 'hybridextend_enqueue_styles', 12 );

/* Load the development stylsheet (unminified) in script debug mode. */
add_filter( 'stylesheet_uri', 'hybridextend_min_stylesheet_uri', 5, 2 );
remove_filter( 'stylesheet_uri', 'hybrid_min_stylesheet_uri', 5 );

/* Optional: Since we dont need any of the framework styles (one-five), we can remove this filter. This file is loaded at priority 2 while framework scripts is loaded at -95, so its ok to remove filter here. */
remove_filter( 'wp_enqueue_scripts', 'hybrid_registr_styles', 0 );

/* Load admin stylesheet files */
add_action( 'admin_enqueue_scripts', 'hybridextend_register_adminstyles' );

/**
 * Registers stylesheets for the framework. This function merely registers styles with WordPress using
 * the wp_register_style() function. It does not load any stylesheets on the site. If a theme wants to 
 * register its own custom styles, it should do so on the 'wp_enqueue_scripts' hook.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function hybridextend_register_styles() {

	/* Get styles. */
	$styles = hybridextend_get_styles();

	/* Loop through each style and register it. */
	foreach ( $styles as $style => $args ) {

		$defaults = array( 
			'handle'  => $style, 
			'src'     => '',
			'deps'    => null,
			'version' => false,
			'media'   => 'all'
		);

		$args = wp_parse_args( $args, $defaults );

		if ( !empty( $args['src'] ) ) {
			wp_register_style(
				sanitize_key( $args['handle'] ),
				esc_url( $args['src'] ),
				is_array( $args['deps'] ) ? $args['deps'] : null,
				preg_replace( '/[^a-z0-9_\-.]/', '', strtolower( $args['version'] ) ),
				esc_attr( $args['media'] )
			);
		}

	}

}

/**
 * Tells WordPress to load the styles using the wp_enqueue_style() function.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function hybridextend_enqueue_styles() {

	/* Get styles. */
	$styles = hybridextend_get_styles();

	/* Loop through each style and enqueue it. */
	foreach ( $styles as $style => $args )
		if ( !empty( $args['src'] ) )
			wp_enqueue_style( sanitize_key( $style ) );

}

/**
 * Returns an array of the available styles for use in themes.
 *
 * @since 1.0.0
 * @access public
 * @return array
 */
function hybridextend_get_styles() {

	/* Initialize */
	$styles = array();

	/* If a child theme is active, add the parent theme's style. */
	// Cannot use 'hybridextend_locate_style()' as the function will always return child
	// theme stylesheet. Hence we have to manually locate and add parent stylesheet.
	if ( is_child_theme() ) {

		if ( defined( 'HYBRIDEXTEND_DEBUG' ) )
			$loadminified = ( HYBRIDEXTEND_DEBUG ) ? false : true;
		else
			$loadminified = hoot_get_mod( 'load_minified', 0 );

		/* Get the parent theme stylesheet (if a '.min' version of the stylesheet exists, use it) */
		if ( $loadminified && file_exists( HYBRID_PARENT . "style.min.css" ) )
			$src = HYBRID_PARENT_URI . "style.min.css";
		else
			// We can skip file_exists for src as parent style.css will always be there.
			$src = HYBRID_PARENT_URI . "style.css";

		$styles['hybridextend-template-style'] = array(
			'src' => $src,
			'version' => HYBRIDEXTEND_THEME_VERSION,
			);
	}

	/* Add the active theme style. */
	$styles['hybridextend-style'] = array(
		'src' => get_stylesheet_uri(),
		'version' => ( is_child_theme() ) ? HYBRIDEXTEND_CHILDTHEME_VERSION : HYBRIDEXTEND_THEME_VERSION,
		);

	/* Return the array of styles. */
	return apply_filters( 'hybridextend_styles', $styles );
}

/**
 * Filters the 'stylesheet_uri' returned by get_stylesheet_uri() to allow theme developers to offer a
 * minimized version of their main 'style.css' file. It will detect if a 'style.min.css' file is available
 * and use it if HYBRIDEXTEND_DEBUG is disabled.
 *
 * @since 1.0.0
 * @access public
 * @param string  $stylesheet_uri      The URI of the active theme's stylesheet.
 * @param string  $stylesheet_dir_uri  The directory URI of the active theme's stylesheet.
 * @return string $stylesheet_uri
 */
function hybridextend_min_stylesheet_uri( $stylesheet_uri, $stylesheet_dir_uri ) {

	if ( defined( 'HYBRIDEXTEND_DEBUG' ) )
		$loadminified = ( HYBRIDEXTEND_DEBUG ) ? false : true;
	else
		$loadminified = hoot_get_mod( 'load_minified', 0 );

	/* Use the .min stylesheet if available. */
	if ( $loadminified ) {

		/* Remove the stylesheet directory URI from the file name. */
		$stylesheet = str_replace( trailingslashit( $stylesheet_dir_uri ), '', $stylesheet_uri );

		/* Change the stylesheet name to 'style.min.css'. */
		$stylesheet = str_replace( '.css', ".min.css", $stylesheet );

		/* If the stylesheet exists in the stylesheet directory, set the stylesheet URI to the dev stylesheet. */
		if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $stylesheet ) )
			$stylesheet_uri = esc_url( trailingslashit( $stylesheet_dir_uri ) . $stylesheet );

	}

	/* Return the theme stylesheet. */
	return $stylesheet_uri;

}

/**
 * Registers the admin stylesheet files.  The function does not load the stylesheet.  
 * It merely registers it with WordPress.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function hybridextend_register_adminstyles() {

	if ( apply_filters( 'hoot_force_theme_fa', true, 'admin' ) )
		wp_deregister_style( 'font-awesome' ); // Bug Fix for plugins using older font-awesome library
	$style_uri = hybridextend_locate_style( HYBRIDEXTEND_CSS . 'font-awesome' );
	wp_register_style( 'font-awesome', $style_uri, false, '5.0.10' ); // hybridextend-font-awesome

}