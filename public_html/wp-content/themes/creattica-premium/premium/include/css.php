<?php
/**
 * Add custom css to frontend.
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/* Modify lite theme CSS */
add_action( 'hybridextend_dynamic_cssrules', 'hoot_premium_prepare_cssrules', 7 );

/* Add CSS built from premium options to the dynamic CSS array */
add_action( 'hybridextend_dynamic_cssrules', 'hoot_premium_dynamic_cssrules', 9 );

/* Add user input Custom CSS */
add_filter( 'hybridextend_dynamic_css', 'hoot_premium_custom_user_css', 6, 2 );

/**
 * Modify lite theme CSS if needed
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hoot_premium_prepare_cssrules() {

	$remove = array(
		// 'h1, h2, h3, h4, h5, h6, .title, .titlefont',
		'#site-title',
	);

	global $hybridextend_style_builder;
	$hybridextend_style_builder->remove( $remove );
}

/**
 * Custom CSS built from user premium theme options
 * For proper sanitization, always use functions from hoot/includes/sanitization.php
 * and hoot/customizer/sanitization.php
 *
 * @since 1.0
 * @access public
 * @param array $cssrules array of css rules
 * @param array $vars misc option values
 * @return array
 */
function hoot_premium_dynamic_cssrules( $cssrules, $vars = array() ) {

	/*** Settings Values ***/

	/* Lite Settings */

	$settings = array();
	// $settings['grid_width']           = intval( hoot_get_mod( 'site_width' ) );
	// $settings['leftbar_width']        = 270;
	$settings['accent_color']         = hoot_get_mod( 'accent_color' );
	$settings['accent_color_dark']    = hybridext_color_decrease( $settings['accent_color'], 12, 12 );
	$settings['accent_font']          = hoot_get_mod( 'accent_font' );
	// $settings['logo_fontface']        = hoot_get_mod( 'logo_fontface' );
	$settings['box_background_color'] = hoot_get_mod( 'box_background-color' );
	$settings['content_bg_color']     = $settings['box_background_color'];
	// $settings['site_title_icon_size'] = hoot_get_mod( 'site_title_icon_size' );
	// $settings['site_title_icon']      = hoot_get_mod( 'site_title_icon' );
	// $settings['logo']                 = hoot_get_mod( 'logo' );
	// $settings['logo_custom']          = apply_filters( 'hoot_logo_custom_text', hybridextend_sortlist( hoot_get_mod( 'logo_custom' ) ) );

	// $wtmodule_bg = array( 'area_a', 'area_b', 'area_c', 'area_d', 'area_e', 'content' );
	// foreach ( $wtmodule_bg as $wtmname ) {
	// 	$settings['wtm_sectionbg'][ $wtmname . '_type'] = hoot_get_mod( "frontpage_sectionbg_{$wtmname}-type", 'none' );
	// 	$settings['wtm_sectionbg'][ $wtmname . '_image'] = hoot_get_mod( "frontpage_sectionbg_{$wtmname}-image" );
	// 	$settings['wtm_sectionbg'][ $wtmname . '_parallax'] = hoot_get_mod( "frontpage_sectionbg_{$wtmname}-parallax" );
	// }

	/* Premium Settings */

	$settings['highlight_color']          = hoot_get_mod( 'highlight_color' );
	$settings['highlight_color_light']    = hybridext_color_increase( $settings['highlight_color'], 10 );
	$settings['highlight_color_tone']     = hybridext_color_increase( $settings['highlight_color'], 13.5 );
	$settings['highlight_color_tonedark'] = hybridext_color_increase( $settings['highlight_color'], 18.5 );
	$settings['font_body_size']           = hoot_get_mod( 'font_body-size' );
	$settings['font_body_face']           = hoot_get_mod( 'font_body-face' );
	$settings['font_body_color']          = hoot_get_mod( 'font_body-color' );
	$settings['font_body_light']          = hybridext_color_increase( $settings['font_body_color'], 12.5 );
	$settings['font_body_dark']           = hybridext_color_decrease( $settings['font_body_color'], 14 );
	// $settings['font_body_darker']         = hybridext_color_decrease( $settings['font_body_color'], 30 ); // Ideally 43
	$settings['font_h2_size']             = hoot_get_mod( 'font_h2-size' );
	$settings['font_h3_size']             = hoot_get_mod( 'font_h3-size' );
	$settings['font_h3_face']             = hoot_get_mod( 'font_h3-face' );
	$settings['font_h4_size']             = hoot_get_mod( 'font_h4-size' );
	$settings['font_h5_size']             = hoot_get_mod( 'font_h5-size' );
	$settings['font_h6_size']             = hoot_get_mod( 'font_h6-size' );
	$settings['link_color']               = hoot_get_mod( 'link_color' );
	$settings['link_hover_color']         = hoot_get_mod( 'link_hover_color' );
	$settings['font_nav_menu_face']       = hoot_get_mod( 'font_nav_menu-face' );
	$settings['leftbar_background_type']  = hoot_get_mod( 'leftbar_background_type' );
	$settings['leftbar_background']       = hoot_get_mod( 'leftbar_background' );
	$settings['leftbar_background_tone']  = ( $settings['leftbar_background_type'] == 'background' ) ?
	                                            hybridext_color_increase( $settings['leftbar_background'], 13.5 ) :
	                                            $settings['highlight_color_tone'];
	$settings['logo_background_type']     = hoot_get_mod( 'logo_background_type' );
	$settings['logo_background']          = hoot_get_mod( 'logo_background' );
	$settings['menu_icons_color']         = hoot_get_mod( 'menu_icons_color' );
	$settings['pageheader_background']    = hoot_get_mod( 'pageheader_background' );
	$settings['pageheader_background_location'] = hoot_get_mod( 'pageheader_background_location' );
	$settings['subfooter_background']     = hoot_get_mod( 'subfooter_background' );
	$settings['footer_background_color']  = hoot_get_mod( 'footer_background-color' );
	$settings['font_footer_color']        = hoot_get_mod( 'font_footer-color' );
	$settings['font_footer_dark']         = hybridext_color_darken( $settings['font_footer_color'], 33 ); // Ideally 50 // Sample: #666 => #444, #aaa => #c6c6c6, #888 => #5b5b5b
	$settings['font_footer_light']        = hybridext_color_lighten( $settings['font_footer_color'], 14 );

	// Troubleshooting
	// echo '<!-- '; print_r($settings); echo ' -->';

	extract( apply_filters( 'hoot_custom_css_settings', $settings, 'premium' ) );

	/*** Add Dynamic CSS ***/

	/* Base Typography and HTML */

	hybridextend_add_css_rule( array(
						'selector'  => 'body',
						'property'  => 'typography',
						'idtag'     => 'font_body',
					) );

	hybridextend_add_css_rule( array(
						'selector'         => 'h1, h2, h3, h4, h5, h6, .title',
						'property'         => 'typography',
						'idtag'            => 'font_h3',
						'typography_reset' => true,
					) );

	hybridextend_add_css_rule( array(
						'selector'         => 'h1',
						'property'         => 'typography',
						'idtag'            => 'font_h1',
						'typography_reset' => true,
					) );

	hybridextend_add_css_rule( array(
						'selector'         => 'h2',
						'property'         => 'typography',
						'idtag'            => 'font_h2',
						'typography_reset' => true,
					) );

	hybridextend_add_css_rule( array(
						'selector'         => 'h4',
						'property'         => 'typography',
						'idtag'            => 'font_h4',
						'typography_reset' => true,
					) );

	hybridextend_add_css_rule( array(
						'selector'         => 'h5',
						'property'         => 'typography',
						'idtag'            => 'font_h5',
						'typography_reset' => true,
					) );

	hybridextend_add_css_rule( array(
						'selector'         => 'h6',
						'property'         => 'typography',
						'idtag'            => 'font_h6',
						'typography_reset' => true,
					) );

	// Redundant: Already added above in 'h1, h2, h3, h4, h5, h6, .title' to use font_h3 typography
	// hybridextend_add_css_rule( array(
	// 					'selector'  => '.title',
	// 					'property'  => 'font-size',
	// 					'value'     => $font_h3_size,
	// 					'idtag'     => 'font_h3-size',
	// 				) );

	hybridextend_add_css_rule( array(
						'selector'  => '.titlefont',
						'property'  => 'font-family',
						'value'     => $font_h3_face,
						'idtag'     => 'font_h3-face',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => 'hr',
						'property'  => 'color',
						'value'     => $highlight_color_tonedark,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => 'blockquote',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'border-color' => array( $highlight_color_tone ),
							'color'        => array( $font_body_light ),
							'font-size'    => array( $font_h6_size, 'font_h6-size' ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => 'a',
						'property'  => 'color',
						'value'     => $link_color,
						'idtag'     => 'link_color',
					) ); // Overriding non premium dynamic css

	hybridextend_add_css_rule( array(
						'selector'  => 'a:hover',
						'property'  => 'color',
						'value'     => $link_hover_color,
						'idtag'     => 'link_hover_color',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.invert-typo',
						'property'  => 'background',
						'value'     => $font_body_color,
						'idtag'     => 'font_body_color',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.enforce-typo',
						'property'  => 'color',
						'value'     => $font_body_color,
						'idtag'     => 'font_body_color',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.enforce-body-font',
						'property'  => 'font-family',
						'value'     => $font_body_face,
						'idtag'     => 'font_body-face',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.highlight-typo',
						'property'  => 'background',
						'value'     => $highlight_color,
						'idtag'     => 'highlight_color',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.table-striped tbody tr:nth-child(odd) td, .table-striped tbody tr:nth-child(odd) th',
						'property'  => 'background',
						'value'     => $highlight_color,
						'idtag'     => 'highlight_color',
					) );

	/* Images, WP Gallery and Objects */

	hybridextend_add_css_rule( array(
						'selector'  => '.gallery, ul.wp-block-gallery',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'border-color' => array( $highlight_color_tone ),
							'background'   => array( $highlight_color, 'highlight_color' ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.gallery .gallery-caption',
						'property'  => 'color',
						'value'     => $font_body_color,
						'idtag'     => 'font_body_color',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.wp-caption',
						'property'  => 'background',
						'value'     => $highlight_color,
						'idtag'     => 'highlight_color',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.wp-caption-text, .wp-block-image figcaption',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'border-color' => array( $highlight_color_tone ),
							'color'        => array( $font_body_color, 'font_body_color' ),
							),
					) );

	/* Layout */

	hybridextend_add_css_rule( array(
						'selector'  =>  '#leftbar, #main',
						'property'  => 'border-color',
						'value'     => $highlight_color_light,
						'media'     => 'only screen and (min-width: 970px)',
					) );

	/* Header (Leftbar, Header, Main Nav Menu) */
	// Leftbar

	hybridextend_add_css_rule( array(
						'selector'  => '.leftbar-top',
						'property'  => 'border-color',
						'value'     => $leftbar_background_tone,
					) );

	$leftbar_background = ( $leftbar_background_type == 'background' ) ? $leftbar_background : 'none';
	hybridextend_add_css_rule( array(
						'selector'  => '.leftbar',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							// 'color'      => array( $leftbar_color, 'leftbar_color' ),
							'background' => array( $leftbar_background, 'leftbar_background' ),
							),
					) );

	if ( $leftbar_background_type == 'background' )
	hybridextend_add_css_rule( array(
						'selector'  => '#header.stuck',
						'property'  => 'background',
						'value'     => $leftbar_background,
					) );
	else
	hybridextend_add_css_rule( array(
						'selector'  => '#header.stuck',
						'property'  => 'background',
						'idtag'     => 'background',
					) );

	// hybridextend_add_css_rule( array(
	// 					'selector'  => '.leftbar-section .widget-title',
	// 					'property'  => 'font-size',
	// 					'value'     => $font_body_size,
	// 					'idtag'     => 'font_body-size',
	// 				) );

	hybridextend_add_css_rule( array(
					'selector'  => '.leftbar-section .social-icons-icon',
					'property'  => ' color ',
					'value'     => $font_body_dark,
				) );

	hybridextend_add_css_rule( array(
						'selector'  => '.leftbar-section i.fa-search',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'color'     => array( $font_body_dark ),
							'font-size' => array( $font_body_size, 'font_body-size' ),
							),
					) );

	/* Header (Leftbar, Header, Main Nav Menu) */
	// Header Layout

	hybridextend_add_css_rule( array(
						'selector'  => '#header-aside', // Replaces: .header-aside-search, .header-aside-custom
						'property'  => 'border-color',
						'value'     => $leftbar_background_tone,
						'media'     => 'only screen and (max-width: 969px)',
					) );



	/* Header (Leftbar, Header, Main Nav Menu) */
	// Logo

	if ( $logo_background_type == 'background' ) {
		hybridextend_add_css_rule( array(
						'selector'  => '#site-logo',
						'property'  => 'background',
						'value'     => $logo_background,
						'idtag'     => 'logo_background',
					) );
	}

	hybridextend_add_css_rule( array(
						'selector'         => '#site-title',
						'property'         => 'typography',
						'idtag'            => 'font_logo',
						'typography_reset' => true,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '#site-description',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'opacity'    => array( '1' ), // Reset styles from stylesheets
							'typography' => array( '', 'font_tagline', false, true ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.site-title-body-font',
						'property'  => 'font-family',
						'value'     => $font_body_face,
						'idtag'     => 'font_body-face',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.site-title-heading-font',
						'property'  => 'font-family',
						'value'     => $font_h3_face,
						'idtag'     => 'font_h3-face',
					) );

	/* Menu */

	hybridextend_add_css_rule( array(
						'selector'         => '.menu-items > li > a',
						'property'         => 'typography',
						'idtag'            => 'font_nav_menu',
						'typography_reset' => true,
					) );

	hybridextend_add_css_rule( array(
						'selector'         => '.menu-items',
						'property'         => 'font-family',
						'value'            => $font_nav_menu_face,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.site-header .hybridextend-megamenu-icon',
						'property'  => 'color',
						'value'     => $menu_icons_color,
						'idtag'     => 'menu_icons_color',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.menu-items .sub-menu' . ',' . '.mobilemenu-fixed .menu-toggle',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'typography'   => array( '', 'font_nav_dropdown', '', true ),
							),
					) );

	// hybridextend_add_css_rule( array(
	// 					'selector'  => '.sf-menu ul' . ',' . '.mobilemenu-fixed .menu-toggle',
	// 					'property'  => array(
	// 						// property  => array( value, idtag, important, typography_reset ),
	// 						'background'   => array( $menu_dropdown_background, 'menu_dropdown_background' ),
	// 						'border-color' => array( $menu_dropdown_tone ),
	// 						),
	//				) );

	hybridextend_add_css_rule( array(
						'selector'         => '.menu-toggle',
						'property'         => 'typography',
						'idtag'            => 'font_nav_menu',
						'typography_reset' => true,
					) );

	if ( $leftbar_background_type == 'background' )
	hybridextend_add_css_rule( array(
						'selector'  => '.mobilemenu-fixed .menu-toggle, .mobilemenu-fixed .menu-items',
						'property'  => 'background',
						'value'     => $leftbar_background,
						'media'     => 'only screen and (max-width: 799px)',
					) );
	else
	hybridextend_add_css_rule( array(
						'selector'  => '.mobilemenu-fixed .menu-toggle, .mobilemenu-fixed .menu-items',
						'property'  => 'background',
						'idtag'     => 'background',
						'media'     => 'only screen and (max-width: 799px)',
					) );

	// hybridextend_add_css_rule( array(
	// 					'selector'  => '.menu-items' . ',' . '.mobilemenu-fixed .menu-items',
	// 					'property'  => 'background',
	// 					'value'     => $menu_dropdown_background,
	// 					'idtag'     => 'menu_dropdown_background',
	// 					'media'     => 'only screen and (max-width: 799px)',
	// 				) );

	hybridextend_add_css_rule( array(
						'selector'         => '.menu-items > li > a',
						'property'         => 'typography',
						'idtag'            => 'font_nav_dropdown',
						'typography_reset' => false,
						'media'            => 'only screen and (max-width: 969px)',
					) );

	/* Main #Content */

	if ( $pageheader_background_location == 'stretch' )
		$pageheader_background_selector = '#loop-meta.pageheader-bg-stretch';
	elseif ( $pageheader_background_location == 'incontent' )
		$pageheader_background_selector = '#content #loop-meta.pageheader-bg-incontent';
	elseif ( $pageheader_background_location == 'both' )
		$pageheader_background_selector = '#loop-meta.pageheader-bg-both, #content #loop-meta.pageheader-bg-both';
	if ( !empty( $pageheader_background_selector ) )
		hybridextend_add_css_rule( array(
						'selector'  => $pageheader_background_selector,
						'property'  => 'background',
						'value'     => $pageheader_background,
						'idtag'     => 'pageheader_background',
					) );
	else
		hybridextend_add_css_rule( array(
						'selector'  => '#page-wrapper #loop-meta.loop-meta-wrap', // '#loop-meta.loop-meta-wrap, #content #loop-meta.loop-meta-wrap',
						'property'  => 'background',
						'value'     => 'none',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '#loop-meta',
						'property'  => 'border-color',
						'value'     => $highlight_color_tonedark,
					) );

	hybridextend_add_css_rule( array(
						'selector'         => '.loop-title',
						'property'         => 'typography',
						'idtag'            => 'font_h2',
						'typography_reset' => true,
					) );

	// hybridextend_add_css_rule( array(
	// 					'selector'  => '#content .loop-title',
	// 					'property'  => 'font-size',
	// 					'value'     => $font_h4_size,
	// 					'idtag'     => 'font_h4-size',
	// 				) );

	hybridextend_add_css_rule( array(
						'selector'  => '.entry-content',
						'property'  => 'border-color',
						'value'     => $highlight_color_tone,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.entry-footer .entry-byline',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'color'        => array( $link_color, 'link_color' ), // Overriding non premium dynamic css
							'border-color' => array( $highlight_color_tone ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.loop-nav',
						'property'  => 'border-color',
						'value'     => $highlight_color_tone,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '#comments-number',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'font-size' => array( $font_body_size, 'font_body-size' ),
							'color'     => array( $font_body_light ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.comment li.comment',
						'property'  => 'border-color',
						'value'     => $highlight_color_tone,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.comment-by-author' . ',' . '.comment-meta-block, .comment-edit-link',
						'property'  => 'color',
						'value'     => $font_body_light,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.comment.bypostauthor > article',
						'property'  => 'background',
						'value'     => $highlight_color,
						'idtag'     => 'highlight_color',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.comment.bypostauthor + #respond',
						'property'  => 'background',
						'value'     => $highlight_color,
						'idtag'     => 'highlight_color',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.comment-ping',
						'property'  => 'border-color',
						'value'     => $highlight_color_tone,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '#reply-title',
						'property'  => 'font-size',
						'value'     => $font_body_size,
						'idtag'     => 'font_body-size',
					) );

	/* Main #Content for Index (Archive / Blog List) */

	hybridextend_add_css_rule( array(
						'selector'  => '.entry-grid-content .entry-title',
						'property'  => 'font-size',
						'value'     => $font_h4_size,
						'idtag'     => 'font_h4-size',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.archive-big .entry-grid, .archive-medium .entry-grid, .archive-small .entry-grid' . ',' .
										'.archive-mosaic .entry-grid',
						'property'  => 'border-color',
						'value'     => $highlight_color_tone,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.archive-mosaic .entry-title',
						'property'  => 'font-size',
						'value'     => $font_h6_size,
						'idtag'     => 'font_h6-size',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.archive-mosaic .mosaic-sub',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background'   => array( $highlight_color, 'highlight_color' ),
							'border-color' => array( $highlight_color_tone ),
							),
					) );

	/* Shortcodes */

	hybridextend_add_css_rule( array(
						'selector'  => '.style-accent, .shortcode-button.style-accent, .style-accentlight',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background' => array( $accent_color, 'accent_color' ),
							'color'      => array( $accent_font, 'accent_font' ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.shortcode-button.style-accent:hover',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background' => array( $accent_color_dark ),
							'color'      => array( $accent_font, 'accent_font' ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.style-highlight, .style-highlightlight',
						'property'  => 'background',
						'value'     => $highlight_color,
						'idtag'     => 'highlight_color',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.shortcode-toggle-head',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background'   => array( $highlight_color, 'highlight_color' ),
							'border-color' => array( $highlight_color_tone ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.shortcode-toggle-box',
						'property'  => 'border-color',
						'value'     => $highlight_color_tone,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '#page-wrapper ul.shortcode-tabset-nav li',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background'   => array( $highlight_color, 'highlight_color' ),
							'border-color' => array( $highlight_color_tone ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '#page-wrapper ul.shortcode-tabset-nav li.current',
						'property'  => 'border-bottom-color',
						'value'     => $content_bg_color,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.shortcode-tabset-box',
						'property'  => 'border-color',
						'value'     => $highlight_color_tone,
					) );

	/* Sliders */

	hybridextend_add_css_rule( array(
						'selector'  => '.hootslider-html-slide h1, .hootslider-html-slide h2, .hootslider-html-slide h3, .hootslider-html-slide h4, .hootslider-html-slide h5, .hootslider-html-slide h6',
						'property'  => 'font-size',
						'value'     => $font_h3_size,
						'idtag'     => 'font_h3-size',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.hootslider-image-slide-caption h1, .hootslider-image-slide-caption h2, .hootslider-image-slide-caption h3, .hootslider-image-slide-caption h4, .hootslider-image-slide-caption h5, .hootslider-image-slide-caption h6',
						'property'  => 'font-size',
						'value'     => $font_h3_size,
						'idtag'     => 'font_h3-size',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.hootslider-carousel-slide .lightSlideCarousel',
						'property'  => 'border-color',
						'value'     => $highlight_color_tone,
					) );

	// Lets skip this, and let it stay in em
	// hybridextend_add_css_rule( array(
	// 					'selector'  => '.hootslider-carousel-slide .lightSlideCarousel h1, .hootslider-carousel-slide .lightSlideCarousel h2, .hootslider-carousel-slide .lightSlideCarousel h3, .hootslider-carousel-slide .lightSlideCarousel h4, .hootslider-carousel-slide .lightSlideCarousel h5, .hootslider-carousel-slide .lightSlideCarousel h6, .hootslider-carousel-slide .lightSlideCarousel .title',
	// 					'property'  => 'font-size',
	// 					'value'     => $font_h6_size,
	// 					'idtag'     => 'font_h6-size',
	// 				) );

	/* Frontpage */

	hybridextend_add_css_rule( array(
						'selector'  => '.frontpage-area.area-highlight',
						'property'  => 'background',
						'value'     => $highlight_color,
						'idtag'     => 'highlight_color',
					) );

	/* Sidebars and Widgets */

	hybridextend_add_css_rule( array(
						'selector'         => '.sidebar',
						'property'         => 'typography',
						'idtag'            => 'font_sidebar',
						'typography_reset' => true,
					) );

	hybridextend_add_css_rule( array(
						'selector'         => '.sidebar .widget-title',
						'property'         => 'typography',
						'idtag'            => 'font_sidebar_heading',
						'typography_reset' => true,
					) );

	// hybridextend_add_css_rule( array(
	// 					'selector'  => '.widget h3',
	// 					'property'  => 'font-size',
	// 					'value'     => $font_h4_size,
	// 					'idtag'     => 'font_h4-size',
	// 				) );

	hybridextend_add_css_rule( array(
						'selector'  => '.topborder-line' . ', ' . '.bottomborder-line',
						'property'  => 'border-color',
						'value'     => $highlight_color_tone,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.topborder-shadow:before, .bottomborder-shadow:after',
						'property'  => 'background',
						'value'     => $highlight_color_tonedark,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.cta-style1 .cta-headline',
						'property'  => 'font-size',
						'value'     => $font_h2_size,
						'idtag'     => 'font_h2-size',
					) );

	// hybridextend_add_css_rule( array(
	// 					'selector'  => '.content-block-content h4',
	// 					'property'  => 'font-size',
	// 					'value'     => $font_h6_size,
	// 					'idtag'     => 'font_h6-size',
	// 				) );

	hybridextend_add_css_rule( array(
						'selector'  =>  '.content-block-style2 .content-block.highlight-typo' . ',' .
										'.content-block-style3 .content-block',
						'property'  => 'border-color',
						'value'     => $highlight_color_light,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.hoot-blogposts-title',
						'property'  => 'border-color',
						'value'     => $highlight_color_tone,
					) );

	// hybridextend_add_css_rule( array(
	// 					'selector'  => '.contact-info-icon i',
	// 					'property'  => 'color',
	// 					'value'     => $font_body_darker,
	// 				) );

	hybridextend_add_css_rule( array(
						'selector'  =>  '.vcard' . ',' . 
										'.vcard-links' . ',' .
										'.vcard-link' . ',' .
										'.vcard-link:first-child',
						'property'  => 'border-color',
						'value'     => $highlight_color_light,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.vcard-content h4, .vcard-content h1, .vcard-content h2, .vcard-content h3, .vcard-content h5, .vcard-content h6',
						'property'  => 'font-size',
						'value'     => $font_h3_size,
						'idtag'     => 'font_h3-size',
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.number-block-content h4, .number-block-content h1, .number-block-content h2, .number-block-content h3, .number-block-content h5, .number-block-content h6',
						'property'  => 'font-size',
						'value'     => $font_h3_size,
						'idtag'     => 'font_h3-size',
					) );

	/* Third Party Plugins */

	hybridextend_add_css_rule( array(
						'selector'  => '.hoot-jetpack-style .entry-the-content .sharedaddy',
						'property'  => 'border-color',
						'value'     => $highlight_color_tone,
					) );

	/* Footer */

	hybridextend_add_css_rule( array(
						'selector'  => '#sub-footer',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background'   => array( $subfooter_background, 'subfooter_background' ),
							'border-color' => array( $highlight_color_tone ),
							),
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '.footer',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background'   => array( '', 'footer_background' ),
							'typography'   => array( '', 'font_footer', false, true ),
							),
					) );

		hybridextend_add_css_rule( array(
						'selector'  => '.footer h1, .footer h2, .footer h3, .footer h4, .footer h5, .footer h6, .footer .title',
						'property'  => 'color',
						'value'     => $font_footer_dark,
					) );

	hybridextend_add_css_rule( array(
						'selector'         => '.footer .widget-title',
						'property'         => 'typography',
						'idtag'            => 'font_footer_heading',
						'typography_reset' => true,
					) );

	hybridextend_add_css_rule( array(
						'selector'  => '#post-footer',
						'property'  => array(
							'background'   => array( $footer_background_color ),
							'color'        => array( $font_footer_light ),
							),
					) );

}

/**
 * Add custom css rules added by user in Theme Options
 *
 * @since 1.0
 * @access public
 * @param string $css css string
 * @param array $vars misc option values
 * @return array
 */
function hoot_premium_custom_user_css( $css, $vars = array() ) {

	$user_css = '';

	// Add Custom Post/Page CSS (include static page set as frontpage)
	if ( is_singular() ) {
		$page_css = hoot_get_meta_option( 'page_css' );
		$user_css .= ( !empty( $page_css ) ) ?  "\n" . $page_css : '';
	}

	// Add Custom CSS for page set as Blog Page
	if ( is_home() && !is_front_page() ) {
		$post_id = get_option( 'page_for_posts' );
		$page_css = hoot_get_meta_option( 'page_css', $post_id );
		$user_css .= ( !empty( $page_css ) ) ?  "\n" . $page_css : '';
	}

	// Add Custom CSS for page set as Shop Page
	if ( current_theme_supports( 'woocommerce' ) && is_shop() ) {
		$post_id = get_option( 'woocommerce_shop_page_id' );
		$page_css = hoot_get_meta_option( 'page_css', $post_id );
		$user_css .= ( !empty( $page_css ) ) ?  "\n" . $page_css : '';
	}

	// Allow child themes to modify and Return css string
	$user_css = apply_filters( 'hoot_premium_custom_user_css', $user_css, $css, $vars );
	return $css . $user_css;
}
