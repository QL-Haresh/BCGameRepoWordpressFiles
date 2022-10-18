<?php
/**
 * Functions for handling JavaScript.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Register scripts. */
add_action( 'wp_enqueue_scripts', 'hybridextend_register_scripts', 0 );

/* Load scripts. It's a good practice to load any other script before the main script. Hence users can enqueue scripts at default priority 10, so that the main script.js is always loaded at the end. */
add_action( 'wp_enqueue_scripts', 'hybridextend_enqueue_scripts', 12 );

/**
 * Registers JavaScript files using the wp_register_script() function.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function hybridextend_register_scripts() {

	/* Get scripts. */
	$scripts = hybridextend_get_scripts();

	/* Loop through each script and register it. */
	foreach ( $scripts as $script => $args ) {

		$defaults = array( 
			'handle'    => $script, 
			'src'       => '',
			'deps'      => null,
			'version'   => false,
			'in_footer' => true
		);

		$args = wp_parse_args( $args, $defaults );

		if ( !empty( $args['src'] ) ) {
			wp_register_script(
				sanitize_key( $args['handle'] ),
				esc_url( $args['src'] ),
				is_array( $args['deps'] ) ? $args['deps'] : null,
				preg_replace( '/[^a-z0-9_\-.]/', '', strtolower( $args['version'] ) ),
				is_bool( $args['in_footer'] ) ? $args['in_footer'] : ''
			);
		}

	}

}

/**
 * Tells WordPress to load the scripts using the wp_enqueue_script() function.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function hybridextend_enqueue_scripts() {

	/* Get scripts. */
	$scripts = hybridextend_get_scripts();

	/* Loop through each script and enqueue it. */
	foreach ( $scripts as $script => $args )
		if ( !empty( $args['src'] ) )
			wp_enqueue_script( sanitize_key( $script ) );

}

/**
 * Returns an array of the available scripts for use in themes.
 *
 * @since 2.1.0
 * @access public
 * @return array
 */
function hybridextend_get_scripts() {

	/* Initialize */
	$scripts = array();

	if ( defined( 'HYBRIDEXTEND_DEBUG' ) )
		$loadminified = ( HYBRIDEXTEND_DEBUG ) ? false : true;
	else
		$loadminified = hoot_get_mod( 'load_minified', 0 );

	/* If a child theme is active, add the parent theme's scripts. */
	// Cannot use 'hybridextend_locate_script()' as the function will always return child
	// theme script. Hence we have to manually locate and add parent script.
	if ( is_child_theme() ) {

		/* Get the parent theme script (if a '.min' version of the script exists, use it) */
		if ( $loadminified && file_exists( trailingslashit( HYBRID_PARENT ) . 'script.min.js' ) ) {
			$src = HYBRID_PARENT_URI . 'script.min.js';
		} elseif ( file_exists( trailingslashit( HYBRID_PARENT ) . 'script.js' ) ) {
			$src = HYBRID_PARENT_URI . 'script.js';
		}

		if ( !empty( $src ) )
			$scripts['hybridextend-template-script'] = array(
				'src' => $src,
				'version' => HYBRIDEXTEND_THEME_VERSION,
				);
	}

	/* Add the active theme script (if a '.min' version of the script exists, use it) */
	// CHILD_THEME_{DIR|URI} uses get_stylesheet_directory function and hence refers to active theme
	if ( $loadminified && file_exists( HYBRID_CHILD . 'script.min.js' ) ) {
		$scriptsrc = HYBRID_CHILD_URI . 'script.min.js';
	} elseif ( file_exists( HYBRID_CHILD . 'script.js' ) ) {
		$scriptsrc = HYBRID_CHILD_URI . 'script.js';
	}

	if ( !empty( $scriptsrc ) )
		$scripts['hybridextend-theme-script'] = array(
			'src' => $scriptsrc,
			'version' => ( is_child_theme() ) ? HYBRIDEXTEND_CHILDTHEME_VERSION : HYBRIDEXTEND_THEME_VERSION,
			);

	/* Return the array of scripts. */
	return apply_filters( 'hybridextend_scripts', $scripts );
}