<?php
/**
 * Add custom css to frontend.
 *
 * @package    Hoot
 * @subpackage Creattica
 */

// Add action at 5 for adding css rules (premium hooks in at 6-9).
// Child themes can hook in at priority 10.
add_action( 'hybridextend_dynamic_cssrules', 'hoot_dynamic_cssrules', 5 );

/**
 * Custom CSS built from user theme options
 * For proper sanitization, always use functions from hoot/includes/sanitization.php
 * and hoot/customizer/sanitization.php
 *
 * @since 1.0
 * @access public
 */
function hoot_dynamic_cssrules() {

	/*** Settings Values ***/

	/* Lite Settings */

	$settings = array();
	$settings['grid_width']           = intval( hoot_get_mod( 'site_width' ) );
	$settings['leftbar_width']        = 270;
	$settings['accent_color']         = hoot_get_mod( 'accent_color' );
	// $settings['accent_color_dark']    = hybridext_color_decrease( $settings['accent_color'], 12, 12 );
	$settings['accent_font']          = hoot_get_mod( 'accent_font' );
	$settings['logo_fontface']        = hoot_get_mod( 'logo_fontface' );
	$settings['box_background_color'] = hoot_get_mod( 'box_background-color' );
	$settings['content_bg_color']     = $settings['box_background_color'];
	$settings['site_title_icon_size'] = hoot_get_mod( 'site_title_icon_size' );
	$settings['logo']                 = hoot_get_mod( 'logo' );
	$settings['logo_custom']          = apply_filters( 'hoot_logo_custom_text', hybridextend_sortlist( hoot_get_mod( 'logo_custom' ) ) );

	// $wtmodule_bg = array( 'area_a', 'area_b', 'area_c', 'area_d', 'area_e', 'content' );
	// foreach ( $wtmodule_bg as $wtmname ) {
	// 	$settings['wtm_sectionbg'][ $wtmname . '_type'] = hoot_get_mod( "frontpage_sectionbg_{$wtmname}-type", 'none' );
	// 	$settings['wtm_sectionbg'][ $wtmname . '_image'] = hoot_get_mod( "frontpage_sectionbg_{$wtmname}-image" );
	// 	$settings['wtm_sectionbg'][ $wtmname . '_parallax'] = hoot_get_mod( "frontpage_sectionbg_{$wtmname}-parallax" );
	// }

	extract( apply_filters( 'hoot_custom_css_settings', $settings, 'lite' ) );

	/*** Add Dynamic CSS ***/

	/* Hoot Grid */

	hybridextend_add_css_rule( array(
						'selector'  => '.hgrid',
						'property'  => 'max-width',
						'value'     => $grid_width . 'px',
						'idtag'     => 'grid_width',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '#page-wrapper',
						'property'  => 'width',
						'value'     => ( $grid_width + $leftbar_width ) . 'px',
					) );

	/* Base Typography and HTML */

	hybridextend_add_css_rule( array(
						'selector'  => 'a',
						'property'  => 'color',
						'value'     => $accent_color,
						'idtag'     => 'accent_color',
					) ); // Overridden in premium

	hybridextend_add_css_rule( array(
						'selector'  => '.accent-typo',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background' => array( $accent_color, 'accent_color' ),
							'color'      => array( $accent_font, 'accent_font' ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.invert-typo',
						'property'  => 'color',
						'value'     => $content_bg_color,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.enforce-typo',
						'property'  => 'background',
						'value'     => $content_bg_color,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => 'input[type="submit"], #submit, .button',
						'property'  => array(
							// property    => array( value, idtag, important, typography_reset ),
							'color'        => array( $accent_color, 'accent_color' ),
							'border-color' => array( $accent_color, 'accent_color' ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => 'input[type="submit"]:hover, #submit:hover, .button:hover, input[type="submit"]:focus, #submit:focus, .button:focus',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background' => array( $accent_color, 'accent_color' ),
							'color'      => array( $accent_font, 'accent_font' ),
							),
					) );

	/* Layout */

	hybridextend_add_css_rule( array(
						'selector'  => 'body',
						'property'  => 'background',
						'idtag'     => 'background',
					) );

	hybridextend_add_css_rule( array(
					'selector'  => '#main',
					'property'  => 'background',
					'idtag'     => 'box_background',
				) );

	/* Header (Topbar, Header, Main Nav Menu) */
	// Text Logo

	if ( 'cursive' == $logo_fontface ) { // Override @logoFontFamily if selected in options
		hybridextend_add_css_rule( array(
						'selector'  => '#site-title',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'font-family' => array( '"Pacifico", sans-serif' ),
							'text-transform' => array( 'none', '', true )
							),
					) ); // Removed in premium
	}

	/* Header (Topbar, Header, Main Nav Menu) */
	// Logo (with icon)

	if ( intval( $site_title_icon_size ) ) {
		hybridextend_add_css_rule( array(
						'selector'  => '.site-logo-with-icon #site-title i',
						'property'  => 'font-size',
						'value'     => $site_title_icon_size,
						'idtag'     => 'site_title_icon_size',
					) );
	}

	/* Header (Topbar, Header, Main Nav Menu) */
	// Custom Logo

	if ( 'custom' == $logo || 'mixedcustom' == $logo ) {
		if ( is_array( $logo_custom ) && !empty( $logo_custom ) ) {
			$lcount = 1;
			foreach ( $logo_custom as $logo_custom_line ) {
				if ( !$logo_custom_line['sortitem_hide'] && !empty( $logo_custom_line['size'] ) ) {
					hybridextend_add_css_rule( array(
						'selector'  => '#site-logo-custom .site-title-line' . $lcount . ',#site-logo-mixedcustom .site-title-line' . $lcount,
						'property'  => 'font-size',
						'value'     => $logo_custom_line['size'],
					) );
				}
				$lcount++;
			}
		}
	}

	/* Main #Content */

	hybridextend_add_css_rule( array(
						'selector'  => '.entry-footer .entry-byline',
						'property'  => 'color',
						'value'     => $accent_color,
						'idtag'     => 'accent_color',
					) ); // Overridden in premium

	/* Light Slider */

	hybridextend_add_css_rule( array(
						'selector'  => '.lSSlideOuter .lSPager.lSpg > li:hover a, .lSSlideOuter .lSPager.lSpg > li.active a',
						'property'  => 'background-color',
						'value'     => $accent_color,
						'idtag'     => 'accent_color',
					) );

	/* Sliders */

	hybridextend_add_css_rule( array(
						'selector'  => '.hootslider-html-slide-button' . ',' . '.hootslider-image-slide-button',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background' => array( $accent_color, 'accent_color' ),
							'color'      => array( $accent_font, 'accent_font' ),
							),
					) );

	/* Frontpage */

	hybridextend_add_css_rule( array(
						'selector'  => '.frontpage-area.module-bg-accent',
						'property'  => 'background-color',
						'value'     => $accent_color,
						'idtag'     => 'accent_color',
					) );

	// Set as inline CSS
	// foreach ( $wtmodule_bg as $wtmname ) {
	// 	if ( $wtm_sectionbg[ $wtmname . '_type'] == 'image' && !empty( $wtm_sectionbg[ $wtmname . '_image'] ) && empty( $wtm_sectionbg[ $wtmname . '_parallax'] ) ) {
	// 		hybridextend_add_css_rule( array(
	// 					'selector'  => "#frontpage-{$wtmname}",
	// 					'property'  => 'background-image',
	// 					'value'     => $wtm_sectionbg[ $wtmname . '_image'],
	// 					'idtag'     => "frontpage_sectionbg_{$wtmname}-image",
	// 				) );
	// 	}
	// }

	/* Sidebars and Widgets */

	hybridextend_add_css_rule( array(
						'selector'  => '.content-block-style3 .enforce-typo',
						'property'  => 'background-color',
						'value'     => $accent_font,
						'idtag'     => 'accent_font',
					) );

	hybridextend_add_css_rule( array(
						'selector' => '.content-block-style4 .content-block-icon.icon-style-none' . ',' . '.content-block-icon i',
						'property' => 'color',
						'value'    => $accent_color,
						'idtag'    => 'accent_color',
					) );

	hybridextend_add_css_rule( array(
						'selector' => '.content-block-style4 .icon-style-circle, .content-block-style4 .icon-style-square',
						'property' => 'border-color',
						'value'    => $accent_color,
						'idtag'    => 'accent_color',
					) );

	/* Plugins */

	hybridextend_add_css_rule( array(
						'selector'  => '#infinite-handle span' . ',' . '.lrm-form a.button, .lrm-form button, .lrm-form button[type=submit], .lrm-form #buddypress input[type=submit], .lrm-form input[type=submit]',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background' => array( $accent_color, 'accent_color' ),
							'color'      => array( $accent_font, 'accent_font' ),
							),
					) );

	/* Footer */

	// hybridextend_add_css_rule( array(
	// 					'selector'  => '#sub-footer' . ',' . '#footer' . ',' . '#post-footer',
	// 					'property'  => 'background',
	// 					'value'     => $content_bg_color,
	// 				) );

}