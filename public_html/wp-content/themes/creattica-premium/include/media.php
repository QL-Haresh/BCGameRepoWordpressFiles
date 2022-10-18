<?php
/**
 * Handle media (i.e. images, attachments) for the theme.
 * This file is loaded via the 'after_setup_theme' hook at priority '10'
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/* Filter the Frameworks's default custom image sizes to be used through the theme */
add_filter( 'hybridextend_custom_image_sizes', 'hoot_theme_custom_image_sizes', 5 );

/**
 * Add custom image sizes to be used throughout the theme.
 * Also define whether to show the custom image size in the Image Editor in the Post Editor.
 *
 * Note, When using hybridextend_get_image_size_name(), any span below 3 gets upgraded to span3, thereby
 * getting bigger images which display much better on smaller screens (where all spans become 100%)
 * Effectively, this means on a grid of 1260, custom images sizes should have atleast 315px width as
 * images below this width would not be used by this function.
 *
 * Note, order of sizes in this array matters. hybridextend_get_image_size_name() automatically returns the
 * first image size it finds matching the width needed (and matching crop criteria).
 *
 * @since 1.0
 * @access public
 * @param array $sizes Default custom image sizes.
 * @return array
 */
function hoot_theme_custom_image_sizes( $sizes ) {
	$sizes = array(
		// Let span3 use 'hoot-medium-thumb' as we need width to be 420 for mobile
		// // 240 x 180 (suitable for span3, calculated using logic of hybridextend_get_image_size_name fn)
		// 'hoot-small-thumb' => array(
		// 	'label'          => __( 'Small Thumbnail', 'creattica-premium' ),
		// 	'width'          => 315,
		// 	'height'         => 215,
		// 	'crop'           => true,
		// 	'show_in_editor' => false,
		// ),
		// 393 x 180 (suitable for span4, calculated using logic of hybridextend_get_image_size_name fn)
		'hoot-medium-thumb' => array(
			'label'          => __( 'Medium Thumbnail', 'creattica-premium' ),
			'width'          => 420, // 240 x 165
			'height'         => 290, // 240 x 165
			'crop'           => true,
			'show_in_editor' => false,
		),
		// 393 x 180 (width is 425 instead of 420, so that 'hoot-medium-thumb' stays as default for span4. 'hoot-preview' is used for archive-medium post thumbnails)
		'hoot-preview' => array(
			'label'          => __( 'Preview', 'creattica-premium' ),
			'width'          => 425,
			'height'         => 550,
			'crop'           => false,
			'show_in_editor' => false,
		),
		// (suitable for span6, calculated using logic of hybridextend_get_image_size_name fn)
		'hoot-large-thumb' => array(
			'label'          => __( 'Large Thumbnail', 'creattica-premium' ),
			'width'          => 550,
			'height'         => 250,
			'crop'           => true,
			'show_in_editor' => false,
		),
		// 740 x 340 (suitable for span8, calculated using logic of hybridextend_get_image_size_name fn)
		'hoot-wide' => array(
			'label'          => __( 'Wide', 'creattica-premium' ),
			'width'          => 840, // 550x310
			'height'         => 470, // 550x310
			'crop'           => true,
			'show_in_editor' => false,
		),
		// 835 x 340 (suitable for span9, calculated using logic of hybridextend_get_image_size_name fn)
		'hoot-extra-wide' => array(
			'label'          => __( 'Extra Wide', 'creattica-premium' ),
			'width'          => 945, // 550x310
			'height'         => 530, // 550x310
			'crop'           => true,
			'show_in_editor' => false,
		),
	);
	return $sizes;
}
