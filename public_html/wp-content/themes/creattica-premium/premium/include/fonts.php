<?php
/**
 * Functions for sending list of google fonts.
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
 * Get all google font families set by the user in theme options and return args to form enqueue url.
 * The theme should create google url using these args like so:
 * add_query_arg( $query_args, '//fonts.googleapis.com/css' );
 * @todo load only the required variants
 *
 * @since 1.0
 * @access public
 * @return array
 */
function hoot_google_fonts_url_args() {
	$args = array();
	$hybridextend_customize = HybridExtend_Customize::get_instance();
	$settings = $hybridextend_customize->get_options('settings');
	$font_families = array();

	foreach ( $settings as $id => $setting ) {
		if ( isset( $setting['type'] ) && 'fontface' == $setting['type'] ) {
			$value = hoot_get_mod( $id );
			if ( !empty( $value ) )
				$font_families[] = $value;
		}
	}

	$font_families = array_unique( $font_families );
	if ( !empty( $font_families ) ) {
		$fonts_list = hybridextend_enum_font_faces('google-fonts');
		foreach ( $font_families as $key => $font )
			if ( isset( $fonts_list[ $font ] ) )
				$font_families[ $key ] = $fonts_list[ $font ] . ':' . apply_filters( 'hoot_google_fonts_styles', '300,400,400i,500,600,700,700i,800' );
			else
				unset( $font_families[ $key ] );
		$subset = apply_filters( 'hoot_google_fonts_character_sets', 'latin' ); // 'latin,latin-ext'
		$args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( $subset ),
		);
	}

	return apply_filters( 'hoot_google_fonts_query_args', $args, $font_families );
}
add_filter( 'hoot_google_fonts_enqueue_url_args', 'hoot_google_fonts_url_args' );

/**
 * Remove the filter added for lite version (to append fixed google fonts), and let those
 * fonts occur in their natural order as stated in hybridextend_googlefonts_list()
 *
 * Both /hoot-theme/fonts.php and /premium/hoot-theme/fonts.php are loaded at
 * after_setup_theme @priority 10. But since this file is loaded after /hoot-theme/fonts.php,
 * we dont need to hook it into any action.
 */
remove_filter( 'hybridextend_fonts_list', 'hoot_theme_fonts_list' );