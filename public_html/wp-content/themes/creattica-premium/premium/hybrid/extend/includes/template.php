<?php
/**
 * Functions for loading template parts. These functions are helper functions or more flexible functions 
 * than what core WordPress currently offers with template part loading.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Add premium widget template locations
 *
 * @since 2.0.0
 * @param array $templates
 * @param string $name
 * @return array
 */
function hybridextend_premium_widget_template_hierarchy( $templates, $name ) {

	$base_premium = str_replace( HYBRID_PARENT, '', PREMIUM_DIR );

	// Lets rebuild the locations
	$templates = array();

	if ( '' !== $name ) {
		$templates[] = $base_premium . "widget-{$name}.php";
		$templates[] = "widget-{$name}.php";
		$templates[] = $base_premium . "widget/{$name}.php";
		$templates[] = "widget/{$name}.php";
		$templates[] = $base_premium . "template-parts/widget-{$name}.php";
		$templates[] = "template-parts/widget-{$name}.php";
	}

	$templates[] = $base_premium . 'widget.php';
	$templates[] = 'widget.php';
	$templates[] = $base_premium . 'widget/widget.php';
	$templates[] = 'widget/widget.php';
	$templates[] = $base_premium . 'template-parts/widget.php';
	$templates[] = 'template-parts/widget.php';

	return $templates;
}
add_filter( 'hybridextend_widget_template_hierarchy', 'hybridextend_premium_widget_template_hierarchy', 5, 2 );

/**
 * A function for locating a shortcode template. This works similar to the WordPress `get_*()` template
 * functions. It's purpose is for loading a shortcode display template.
 *
 * @since 1.1.0
 * @access public
 * @param string $name
 * @return void
 */
function hybridextend_locate_shortcode( $name = '' ) {
	if ( '' !== $name ) {

		$templates = array();

		// Add these locations for easy child theme templates
		$templates[] = "shortcode-{$name}.php";
		$templates[] = "shortcodes/{$name}.php";
		$templates[] = "template-parts/shortcode-{$name}.php";

		// Add shortcode templates in premium theme
		$base_premium_theme = str_replace( HYBRID_PARENT, '', HYBRIDEXTEND_PREMIUM_INC );
		$templates[] = $base_premium_theme . "shortcodes/{$name}.php";

		// Add shortcode templates in premium framework extension
		$base_premium_core = str_replace( HYBRID_PARENT, '', HYBRIDEXTEND_PREMIUM_DIR );
		$templates[] = $base_premium_core . "extensions/shortcodes/display/{$name}.php";

		// Add shortcode templates in theme
		// Added for brevity only as shortcodes is a premium feature
		$base_theme = str_replace( HYBRID_PARENT, '', HYBRIDEXTEND_INC );
		$templates[] = $base_theme . "shortcodes/{$name}.php";

		// Add shortcode templates in framework
		// Added for brevity only as shortcodes is a premium feature
		$base_core = str_replace( HYBRID_PARENT, '', HYBRIDEXTEND_DIR );
		$templates[] = $base_core . "extensions/shortcodes/display/{$name}.php";

		// Apply filters and return
		$templates = apply_filters( 'hybridextend_shortcode_template_hierarchy', $templates, $name, $base_premium_theme, $base_premium_core, $base_theme, $base_core );
		return locate_template( $templates, false );

	}
}

/* == utility == */

/**
 * Helper function for getting the minified script/style uri if available.
 *
 * @since 2.0.0
 * @access public
 * @param array $locations
 * @param string $location
 * @param string $type
 * @param bool $loadminified
 * @return array
 */
function hybridextend_premium_locate_uri( $locations, $location, $type, $loadminified ) {

	/** Rewrite Locations **/

	$locations = array();
	if ( is_child_theme() ) {

		if ( $loadminified )
			$locations['child-premium-min'] = array(
				'path' => HYBRID_CHILD . 'premium/' . $location . '.min.' . $type,
				'uri'  => HYBRID_CHILD_URI . 'premium/' . $location . '.min.' . $type,
				);

		$locations['child-premium'] = array(
			'path' => HYBRID_CHILD . 'premium/' . $location . '.' . $type,
			'uri'  => HYBRID_CHILD_URI . 'premium/' . $location . '.' . $type,
			);

		if ( $loadminified )
			$locations['child-default-min'] = array(
				'path' => HYBRID_CHILD . $location . '.min.' . $type,
				'uri'  => HYBRID_CHILD_URI . $location . '.min.' . $type,
				);

		$locations['child-default'] = array(
			'path' => HYBRID_CHILD . $location . '.' . $type,
			'uri'  => HYBRID_CHILD_URI . $location . '.' . $type,
			);

	}

	if ( $loadminified )
		$locations['premium-min'] = array(
			'path' => HYBRID_PARENT . 'premium/' . $location . '.min.' . $type,
			'uri'  => HYBRID_PARENT_URI . 'premium/' . $location . '.min.' . $type,
			);

	$locations['premium'] = array(
		'path' => HYBRID_PARENT . 'premium/' . $location . '.' . $type,
		'uri'  => HYBRID_PARENT_URI . 'premium/' . $location . '.' . $type,
		);

	if ( $loadminified )
		$locations['default-min'] = array(
			'path' => HYBRID_PARENT . $location . '.min.' . $type,
			'uri'  => HYBRID_PARENT_URI . $location . '.min.' . $type,
			);

	$locations['default'] = array(
		'path' => HYBRID_PARENT . $location . '.' . $type,
		'uri'  => HYBRID_PARENT_URI . $location . '.' . $type,
		);

	return $locations;

}
add_filter( 'hybridextend_locate_uri', 'hybridextend_premium_locate_uri', 5, 4 );