<?php
/**
 * Custom Taxonomies array for the theme.
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
 * Defines an array of taxonomies settings that will be used to register Custom taxonomies
 * When creating the 'id' fields, make sure to use all lowercase and no spaces. ID can be max.
 * 32 characters
 *
 * Child themes can modify the taxonomies array using the 'hybridextend_theme_taxonomies' filter hook.
 *
 * @since 1.0
 * @param object $hybridextend_taxonomies
 * @return void
 */
function hoot_taxonomies( $hybridextend_taxonomies ) {

	$options = array();

	/*$options['hoot_slider_group'] = array(
		'object_type' => 'hoot_slider',
		'args' => array(
			'labels'	=> array(
				'name'				=> __( 'Slide Sets', 'creattica-premium' ),
				'singular_name'		=> __( 'Slide Set', 'creattica-premium' ),
				),
			'public'			=> false,
			'show_ui'			=> true,
			'hierarchical'		=> true,
			),
		);*/

	// Add taxonomy options to main class options object
	$hybridextend_taxonomies->add_options( $options );

}

/* Hook into action to add options */
add_action( 'hybridextend_taxonomies_loaded', 'hoot_taxonomies', 5, 1 );