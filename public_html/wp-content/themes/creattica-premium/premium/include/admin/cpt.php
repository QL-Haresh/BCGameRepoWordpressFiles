<?php
/**
 * CPT (Custom Post Types) array for the theme.
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
 * Defines an array of CPT settings that will be used to register CPT
 * When creating the 'id' fields, make sure to use all lowercase and no spaces. ID can be max.
 * 20 characters and can not contain capital letters or spaces.
 *
 * Child themes can modify the cpt array using the 'hybridextend_theme_cpt' filter hook.
 *
 * @since 1.0
 * @param object $hybridextend_cpt
 * @return void
 */
function hoot_cpt( $hybridextend_cpt ) {

	// define a directory path for using image radio buttons
	// or, use dashicons for icons Ref. https://developer.wordpress.org/resource/dashicons/
	$imagepath =  HYBRIDEXTEND_INCURI . 'admin/images/';

	$options = array();

	$options['hoot_slider'] = array(
		'labels' => array(
			'name' => __( 'Sliders', 'creattica-premium' ),
			'singular_name' => __( 'Slider', 'creattica-premium' ),
			),
		'public' => false,
		'show_ui' => true,
		'menu_icon' => 'dashicons-slides', // dashicons-format-gallery, dashicons-images-alt2
		'supports' => array( 'title' ),
		);

	if ( current_theme_supports( 'hoot-slider' ) ) :
	endif;

	// Add cpt options to main class options object
	$hybridextend_cpt->add_options( $options );

}

/* Hook into action to add options */
add_action( 'hybridextend_cpt_loaded', 'hoot_cpt', 5, 1 );

if ( is_admin() ):

	/**
	 * Add custom column to Edit screen in admin
	 *
	 * @since 1.0
	 * @param array $custcolumn
	 * @return array
	 */
	function hoot_cpt_add_custom_columns( $custcolumn ) {
		global $post;

		if ( $post->post_type == 'hoot_slider' )
			$custcolumn['hoot_slider_shortcode'] = __( 'Shortcode', 'creattica-premium' );

		return $custcolumn;
	}

	/**
	 * Add custom column content to Edit screen in admin
	 *
	 * @since 1.0
	 * @param array $custcolumn
	 * @param int $post_id
	 */
	function hoot_cpt_custom_columns( $custcolumn, $post_id ) {

		if ( $custcolumn  == 'hybridextend_image_preview' )
			echo get_the_post_thumbnail( $post_id, 'thumbnail', array( 'style' => ' width: 75px; height: auto; ' ) );

		if ( $custcolumn  == 'hoot_slider_shortcode' )
			echo '<code>[hoot_slider id="' . $post_id . '"]</code>';

	}

	/* Hook functions for adding columns to Sliders */
	add_filter( 'manage_edit-hoot_slider_columns', 'hoot_cpt_add_custom_columns' );
	add_action( 'manage_hoot_slider_posts_custom_column', 'hoot_cpt_custom_columns', 10, 2 );

endif;