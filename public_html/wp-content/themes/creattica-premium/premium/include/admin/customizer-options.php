<?php
/**
 * Defines customizer premium options
 *
 * This file is loaded at 'after_setup_theme' hook with 10 priority.
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
 * Build the Customizer options (panels, sections, settings)
 *
 * Always remember to mention specific priority for non-static options like:
 *     - options being added based on a condition (eg: if woocommerce is active)
 *     - options which may get removed (eg: logo_size, headings_fontface)
 *     - options which may get rearranged (eg: logo_background_type)
 *     This will allow other options inserted with priority to be inserted at
 *     their intended place.
 *
 * @since 1.0
 * @access public
 * @return array
 */
if ( !function_exists( 'hoot_premium_customizer_options' ) ) :
function hoot_premium_customizer_options() {

	// Stores all the settings to be added
	$settings = array();

	// Stores all the sections to be added
	$sections = array();

	// Stores all the panels to be added
	$panels = array();

	// Theme default colors and fonts
	extract( apply_filters( 'hoot_theme_options_defaults', array(
		// Lite version
		'accent_color'         => '#e23348',
		'accent_font'          => '#ffffff',
		'box_background'       => '#f5f5f5',
		'box_background_patt'  => 'hybrid/extend/images/patterns/4.png',
		'site_background'      => '#ffffff',
		// Premium only options
		'highlight_color'            => '#ffffff',
		'menu_icons_color'           => '#888888',
		'leftbar_background'         => '#ffffff',
		'logo_background'            => '#e23348',
		// 'menu_dropdown_background'   => '#f5f5f5',
		'pageheader_background'      => '#ffffff',
		'subfooter_background'       => '#ffffff',
		'footer_background'          => '#ffffff',
		// 'leftbar_color'               => '#888888',
		'font_logo_size'             => '36px',
		'font_logo_face'             => '"Open Sans", sans-serif',
		'font_logo_style'            => 'uppercase bold',
		'font_logo_color'            => '#444444',
		'font_tagline_size'          => '14px',
		'font_tagline_face'          => '"Open Sans", sans-serif',
		'font_tagline_style'         => 'none',
		'font_tagline_color'         => '#888888',
		'font_nav_menu_size'         => '15px',
		'font_nav_menu_face'         => '"Open Sans", sans-serif',
		'font_nav_menu_style'        => 'uppercase',
		'font_nav_menu_color'        => '#888888',
		'font_nav_dropdown_size'     => '13px',
		'font_nav_dropdown_style'    => 'none',
		'font_nav_dropdown_color'    => '#777777',
		'font_body_size'             => '14px',
		'font_body_face'             => '"Open Sans", sans-serif',
		'font_body_style'            => 'none',
		'font_body_color'            => '#777777',
		'link_color'                 => '#e23348',
		'link_hover_color'           => '#a8173e',
		'font_h3_size'               => '16px',
		'font_h3_face'               => '"Open Sans", sans-serif',
		'font_h3_style'              => 'none',
		'font_h3_color'              => '#444444',
		'font_h1_size'               => '25px',
		'font_h1_style'              => 'none',
		'font_h1_color'              => '#444444',
		'font_h2_size'               => '20px',
		'font_h2_style'              => 'none',
		'font_h2_color'              => '#444444',
		'font_h4_size'               => '16px',
		'font_h4_style'              => 'none',
		'font_h4_color'              => '#444444',
		'font_h5_size'               => '14px',
		'font_h5_style'              => 'none',
		'font_h5_color'              => '#444444',
		'font_h6_size'               => '14px',
		'font_h6_style'              => 'none',
		'font_h6_color'              => '#444444',
		'font_sidebar_heading_size'  => '14px',
		'font_sidebar_heading_face'  => '"Open Sans", sans-serif',
		'font_sidebar_heading_style' => 'uppercase bold',
		'font_sidebar_heading_color' => '#777777',
		'font_sidebar_size'          => '14px',
		'font_sidebar_style'         => 'none',
		'font_sidebar_color'         => '#777777',
		'font_footer_heading_size'   => '14px',
		'font_footer_heading_face'   => '"Open Sans", sans-serif',
		'font_footer_heading_style'  => 'uppercase bold',
		'font_footer_heading_color'  => '#777777',
		'font_footer_size'           => '14px',
		'font_footer_style'          => 'none',
		'font_footer_color'          => '#888888',
	) ) );

	// Directory path for radioimage buttons
	$imagepath =  HYBRIDEXTEND_INCURI . 'admin/images/';

	/*** Add Options (Panels, Sections, Settings) ***/

	/** Section **/

	$section = 'title_tagline';

	$settings['disable_lightbox'] = array(
		'label'       => __( 'Disable Lightbox', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'priority'    => '115',
		'description' => array(
			'type' => 'yellow',
			'text' => __('Check this if you do not want image links to open in a lightbox throughout your site.<hr>Users can always add the <code>no-lightbox</code> class to links to stop them from opening in lightbox. Example:<hr><code>&lt;a href="image.png" class="no-lightbox"&gt;Content&lt;/a&gt;</code>', 'creattica-premium'),
		),
	);

	if ( hybridextend_theme_supports( 'hybridextend-waypoints', 'sticky-leftbar' ) ) {
		$settings['disable_sticky_leftbar'] = array(
			'label'       => __( 'Disable Sticky Leftbar', 'creattica-premium' ),
			'section'     => $section,
			'type'        => 'checkbox',
			'priority'    => '115',
			'description' => __( 'Check this if you do not want to display a fixed Logo/menu in leftbar when a user scrolls down the page.', 'creattica-premium' ),
		);
	}

	if ( hybridextend_theme_supports( 'hybridextend-scrollpoints', 'goto-top' ) ) {
		$settings['disable_goto_top'] = array(
			'label'       => __( "Disable 'Goto Top' Button", 'creattica-premium' ),
			'section'     => $section,
			'type'        => 'checkbox',
			'priority'    => '115',
			'description' => __( 'Check this to hide "Top" button when a user scrolls down the page.', 'creattica-premium' ),
		);
	}

	if ( hybridextend_theme_supports( 'hybridextend-scrollpoints', 'menu-scroll' ) ) {
		$settings['scrollpadding'] = array(
			'label'       => __( "Custom padding for scrollpoints", 'creattica-premium' ),
			'section'     => $section,
			'type'        => 'text',
			'default'     => '50',
			'priority'    => '115',
			'description' => __( 'This is the distance from the top of the screen when the page scrolls down to a scrollpoint.', 'creattica-premium' ),
			'input_attrs' => array(
				'placeholder' => __( 'default: 50', 'creattica-premium' ),
			),
		);
	}

	/** Section **/

	$section = 'colors';

	$settings['highlight_color'] = array(
		'label'       => __( 'Highlight Color', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $highlight_color,
		'description' => array(
			'type' => 'yellow',
			'text' => __('For highlighting backgrounds.<hr>It is best to choose a highlight color which is close to the content background color. For example, for a white site, select highlight color as light gray.', 'creattica-premium'),
		),
	);

	$settings['menu_icons_color'] = array(
		'label'       => __( 'Header Nav Menu - Icon Color', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $menu_icons_color,
		'description' => sprintf( __('You can add icons to your navigation menu from the %sMenu Management screen%s.', 'creattica-premium'), '<a href="' . esc_url( admin_url('nav-menus.php') ) . '" target="_blank">', '</a>' ),
	);

	/** Section **/

	$section = 'backgrounds';

	$settings['leftbar_background_type'] = array(
		'label'       => __( 'Leftbar Background', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'transparent' => __('None', 'creattica-premium'),
			'background'  => __('Custom Color', 'creattica-premium'),
		),
		'default'     => 'transparent',
	);

	$settings['leftbar_background'] = array(
		'label'           => __( 'Leftbar Background Color', 'creattica-premium' ),
		'section'         => $section,
		'type'            => 'color',
		'default'         => $leftbar_background,
		'active_callback' => 'hoot_callback_leftbar_background',
	);

	// 'logo_background_type' setting exists in lite version. Rewrite it here.
	$settings['logo_background_type'] = array(
		'label'       => __( 'Logo Background', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radio',
		'priority'    => '395', // Mention priority to override previous priority set in lite version
		'choices'     => array(
			'transparent' => __('None', 'creattica-premium'),
			'accent'      => __('Accent Color', 'creattica-premium'),
			'background'  => __('Custom Color', 'creattica-premium'),
		),
		'default'     => 'accent',
	);

	$settings['logo_background'] = array(
		'label'           => __( 'Logo Background Custom Color', 'creattica-premium' ),
		'section'         => $section,
		'type'            => 'color',
		'priority'        => '395', // Mention priority to keep it along with radio above
		'default'         => $logo_background,
		'active_callback' => 'hoot_callback_logo_background',
	);

	// $settings['menu_dropdown_background'] = array(
	// 	'label'       => __( 'Menu Dropdown Background', 'creattica-premium' ),
	// 	'section'     => $section,
	// 	'type'        => 'color',
	// 	'default'     => $menu_dropdown_background,
	// );

	$settings['pageheader_background'] = array(
		'label'       => __( 'Page Title Header Background', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $pageheader_background,
		'description' => __( 'This is the Page Header area at top (below Logo and Menu) containing Page/Post Title and Meta details like author, categories etc.', 'creattica-premium' ),
	);

	$settings['pageheader_background_location'] = array(
		'label'       => __( 'Apply Background only when Page Header is:', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'stretch'   => $imagepath . 'pageheader-stretch.png',
			'incontent' => $imagepath . 'pageheader-incontent.png',
			'both'      => $imagepath . 'pageheader-both.png',
			'none'      => $imagepath . 'pageheader-none.png',
		),
		'default'     => 'stretch',
	);

	$settings['subfooter_background'] = array(
		'label'       => __( 'Sub Footer Background', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $subfooter_background,
		'description' => sprintf( __("This background will be used for subfooter if active (i.e. you have added widgets in the %s'
			Subfooter' Widget area%s)", 'creattica-premium'), '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>' ),
	);

	$settings['footer_background'] = array(
		'label'       => __( 'Footer Background', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'betterbackground',
		'default'     => array(
			'color'      => $footer_background,
		),
	);

	/** Section **/

	$section = 'typography';

	$sections[ $section ] = array(
		'title'       => __( 'Typography', 'creattica-premium' ),
		'priority'    => '23', // Non static options must have a priority
	);

	// $settings['leftbar_color'] = array(
	// 	'label'       => __( 'Leftbar Font', 'creattica-premium' ),
	// 	'section'     => $section,
	// 	'type'        => 'color',
	// 	'default'     => $leftbar_color,
	// );

	$settings['font_logo'] = array(
		'label'       => __( 'Logo Text', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_logo_size,
			'face'  => $font_logo_face,
			'style' => $font_logo_style,
			'color' => $font_logo_color,
		),
		'description' => sprintf( __("For 'Plain Text' Logo option. Site Title can be changed via %sWordPress Settings%s.", 'creattica-premium'), '<a href="' . esc_url( admin_url('options-general.php') ) . '" target="_blank">', '</a>' ),
	);

	$settings['font_tagline'] = array(
		'label'       => __( 'Logo Tagline', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_tagline_size,
			'face'  => $font_tagline_face,
			'style' => $font_tagline_style,
			'color' => $font_tagline_color,
		),
		'description' => sprintf( __("For 'Plain Text' Logo option. Site Tagline can be changed via %sWordPress Settings%s.", 'creattica-premium'), '<a href="' . esc_url( admin_url('options-general.php') ) . '" target="_blank">', '</a>' ),
	);

	$settings['font_nav_menu'] = array(
		'label'       => __( 'Menu Font', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_nav_menu_size,
			'face'  => $font_nav_menu_face,
			'style' => $font_nav_menu_style,
			'color' => $font_nav_menu_color,
		),
	);

	$settings['font_nav_dropdown'] = array(
		'label'       => __( 'Menu Dropdown Font', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_nav_dropdown_size,
			'style' => $font_nav_dropdown_style,
			'color' => $font_nav_dropdown_color,
		),
		'options'     => array( 'size', 'style', 'color' ),
	);

	$settings['font_body'] = array(
		'label'       => __( 'Body Content', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_body_size,
			'face'  => $font_body_face,
			'style' => $font_body_style,
			'color' => $font_body_color,
		),
	);

	$settings['link_color'] = array(
		'label'       => __( 'Link Color', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $link_color,
	);

	$settings['link_hover_color'] = array(
		'label'       => __( 'Link Hover Color', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $link_hover_color,
	);

	$settings['font_h3'] = array(
		'label'       => __( 'General Headings (Heading 3)', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_h3_size,
			'face'  => $font_h3_face,
			'style' => $font_h3_style,
			'color' => $font_h3_color,
		),
	);

	$settings['font_h1'] = array(
		'label'       => __( 'Heading 1', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_h1_size,
			'style' => $font_h1_style,
			'color' => $font_h1_color,
		),
		'options'     => array( 'size', 'style', 'color' ),
	);

	$settings['font_h2'] = array(
		'label'       => __( 'Heading 2', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_h2_size,
			'style' => $font_h2_style,
			'color' => $font_h2_color,
		),
		'options'     => array( 'size', 'style', 'color' ),
	);

	$settings['font_h4'] = array(
		'label'       => __( 'Heading 4', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_h4_size,
			'style' => $font_h4_style,
			'color' => $font_h4_color,
		),
		'options'     => array( 'size', 'style', 'color' ),
	);

	$settings['font_h5'] = array(
		'label'       => __( 'Heading 5', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_h5_size,
			'style' => $font_h5_style,
			'color' => $font_h5_color,
		),
		'options'     => array( 'size', 'style', 'color' ),
	);

	$settings['font_h6'] = array(
		'label'       => __( 'Heading 6', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_h6_size,
			'style' => $font_h6_style,
			'color' => $font_h6_color,
		),
		'options'     => array( 'size', 'style', 'color' ),
	);

	$settings['font_sidebar_heading'] = array(
		'label'       => __( 'Sidebar Widget Heading', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_sidebar_heading_size,
			'face'  => $font_sidebar_heading_face,
			'style' => $font_sidebar_heading_style,
			'color' => $font_sidebar_heading_color,
		),
	);

	$settings['font_sidebar'] = array(
		'label'       => __( 'Sidebar Widget Text', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_sidebar_size,
			'style' => $font_sidebar_style,
			'color' => $font_sidebar_color,
		),
		'options'     => array( 'size', 'style', 'color' ),
	);

	$settings['font_footer_heading'] = array(
		'label'       => __( 'Footer Widget Heading', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_footer_heading_size,
			'face'  => $font_footer_heading_face,
			'style' => $font_footer_heading_style,
			'color' => $font_footer_heading_color,
		),
	);

	$settings['font_footer'] = array(
		'label'       => __( 'Footer Widget Text', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'typography',
		'default'     => array(
			'size'  => $font_footer_size,
			'style' => $font_footer_style,
			'color' => $font_footer_color,
		),
		'options'     => array( 'size', 'style', 'color' ),
	);

	/** Section **/

	$section = 'slider_html';

	$settings['wt_cpt_slider_a'] = array(
		'label'       => __( 'Select a Slider', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'select',
		'choices'     => HybridExtend_Options_Helper::cpt( 'hoot_slider', false, __( 'Select Slider', 'creattica-premium' ) ),
		'description' => sprintf( __('You can Create New Sliders from the %sAdd New Slider%s screen.', 'creattica-premium'), '<a href="' . esc_url( admin_url('post-new.php?post_type=hoot_slider') ) . '" target="_blank">', '</a>' ),
	);

	/** Section **/

	$section = 'slider_img';

	$settings['wt_cpt_slider_b'] = array(
		'label'       => __( 'Select a Slider', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'select',
		'choices'     => HybridExtend_Options_Helper::cpt( 'hoot_slider', false, __( 'Select Slider', 'creattica-premium' ) ),
		'description' => sprintf( __('You can Create New Sliders from the %sAdd New Slider%s screen.', 'creattica-premium'), '<a href="' . esc_url( admin_url('post-new.php?post_type=hoot_slider') ) . '" target="_blank">', '</a>' ),
	);

	/** Section **/

	$section = 'archives';

	$settings['archive_type'] = array(
		'label'       => __( 'Archive (Blog) Layout', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'priority'    => '225',
		'choices'     => array(
			'big'     => $imagepath . 'archive-big.png',
			'medium'  => $imagepath . 'archive-medium.png',
			'small'   => $imagepath . 'archive-small.png',
			'mosaic2' => $imagepath . 'archive-mosaic2.png',
			'mosaic3' => $imagepath . 'archive-mosaic3.png',
			// 'mosaic4' => $imagepath . 'archive-mosaic4.png',
		),
		'default'     => 'big',
		'description' => __("Set the post style for archive pages like your Blog.<hr>* Big Image<br />* Medium Image (Non Cropped)<br />* Small Image (Cropped)<br />* Mosaic 2 column<br />* Mosaic 3 column", 'creattica-premium'),
	);

	if ( current_theme_supports( 'custom-404' ) ) :

		/** Section **/

		$section = 'page_404';

		$sections[ $section ] = array(
			'title'       => __( '404 Page', 'creattica-premium' ),
			'priority'    => '47',
		);

		$settings['404_page'] = array(
			'label'       => __( '404 (Not Found) Page', 'creattica-premium' ),
			'section'     => $section,
			'type'        => 'radio',
			'choices'     => array(
				'default' => __( "Theme's default 404 page", 'creattica-premium' ),
				'custom' => __( 'Custom 404 page', 'creattica-premium' ),
			),
			'default'     => 'default',
		);

		$settings['404_custom_page'] = array(
			'label'           => __( 'Custom 404 Page Content', 'creattica-premium' ),
			'section'         => $section,
			'type'            => 'select',
			'choices'         => HybridExtend_Options_Helper::pages(),
			'description'     => __( 'Select a custom page to be used as content for the 404 Not Found page', 'creattica-premium' ),
			'active_callback' => 'hoot_callback_404_custom_page',
		);

	endif;

	/** Section **/

	$section = 'code';

	$sections[ $section ] = array(
		'title'       => __( 'Custom Code', 'creattica-premium' ),
	);

	$settings['custom_js'] = array(
		'label'       => __( 'Custom Javascript (Google Analytics Code)', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'textarea',
		'description' => __( 'You can add custom JS snippets (like Google Analytics code) here. These settings will stay unaffected by Theme updates', 'creattica-premium' ),
		'sanitize_callback' => 'hoot_custom_sanitize_textarea_allowscript',
	);

	$settings['custom_js_inheader'] = array(
		'label'       => __( 'Include Custom Javascript in Header', 'creattica-premium' ),
		'sublabel'    => __( 'By default, javascript is added in footer.', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'description' => __( 'Check this if you want to include it in <code>&lt;head&gt;</code> tag.<br />This can be useful for adding scripts like <strong>Google Analytics</strong> code. Do note that adding long javascript in header increases page load time.', 'creattica-premium' ),
	);


	/*** Return Options Array ***/
	return apply_filters( 'hoot_premium_customizer_options', array(
		'settings' => $settings,
		'sections' => $sections,
		'panels'   => $panels,
	) );

}
endif;

/**
 * Add Options (settings, sections and panels) to HybridExtend_Customize class options object
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'hoot_premium_add_customizer_options' ) ) :
function hoot_premium_add_customizer_options() {

	$hybridextend_customize = HybridExtend_Customize::get_instance();

	// Modify Lite version Options

	// Deprecated. Added to 'hoot_theme_customizer_options' filter as we avoid using
	// recursive merge as unfortunately a lot of users are still on PHP < 5.3
	// $mergesettings = $mergesections = array();
	// $mergesettings['load_minified']['section'] = 'tweaks';
	// $mergesections['typography']['description'] = '';
	// // $mergesettings['logo_custom']['options']['line1']['font']['choices'] =
	// // $mergesettings['logo_custom']['options']['line2']['font']['choices'] =
	// // $mergesettings['logo_custom']['options']['line3']['font']['choices'] =
	// // $mergesettings['logo_custom']['options']['line4']['font']['choices'] = array(
	// // 				'standard' => __('Body Font', 'creattica-premium'),
	// // 				'heading' => __('Logo Font', 'creattica-premium'),
	// // 				);
	// $mergesettings['frontpage_sections']['choices']['slider_html'] =
	// $mergesections['slider_html']['title'] = __('Frontpage - Slider A', 'creattica-premium');
	// $mergesettings['frontpage_sections']['choices']['slider_img'] =
	// $mergesections['slider_img']['title'] = __('Frontpage - Slider B', 'creattica-premium');

	// $hybridextend_customize->edit_settings( $mergesettings, 'recursive' );
	// $hybridextend_customize->edit_sections( $mergesections, 'recursive' );

	$hybridextend_customize->remove_settings( 'logo_size' );
	$hybridextend_customize->remove_settings( 'logo_fontface' );
	$hybridextend_customize->remove_settings( array( 'wt_html_slider_min_height' ) );
	// $hybridextend_customize->remove_settings( 'logo_background_type' ); // Redundant as $hybridextend_customize->add_options merges the settings

	// Add Options
	$options = hoot_premium_customizer_options();
	$hybridextend_customize->add_options( array(
		'settings' => $options['settings'],
		'sections' => $options['sections'],
		'panels' => $options['panels'],
		) );

	// Remove Premium infobutton
	// $hybridextend_customize->remove_infobuttons( 'premium' );

}
endif;
add_action( 'init', 'hoot_premium_add_customizer_options', 0 ); // cannot hook into 'after_setup_theme' as this hook is already being executed (this file is loaded at after_setup_theme @priority 10) (hooking into same hook from within while hook is being executed leads to undesirable effects as $GLOBALS[$wp_filter]['after_setup_theme'] has already been ksorted)
// Hence, we hook into 'init' @priority 0, so that settings array gets populated before 'widgets_init' action ( which itself is hooked to 'init' at priority 1 ) for creating widget areas ( settings array is needed for creating defaults when user value has not been stored )
// Since this file is loaded after lite version, hoot_premium_add_customizer_options() will execute after hoot_add_customizer_options() even if we set same priority 0 [can be added at later priority to ensure loading after lite version settings as premium does not contain settings needed for registering widget areas during widgets_init hook]

/**
 * Modify default WordPress Settings Sections and Panels
 *
 * @since 1.1.3
 * @param object $wp_customize
 * @return void
 */
function hoot_premium_customizer_modify_default_options( $wp_customize ) {

	if ( function_exists( 'wp_get_custom_css' ) )
		$wp_customize->get_control( 'custom_css' )->section = 'code';
	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'custom-css' ) ) {
		$wp_customize->get_control( 'wpcom_custom_css_content_width_control' )->section = 'code';
		$wp_customize->get_control( 'jetpack_css_mode_control' )->section = 'code';
		$wp_customize->get_control( 'jetpack_css_preprocessors_control' )->section = 'code';
	}

}
add_action( 'customize_register', 'hoot_premium_customizer_modify_default_options', 100 );

/**
 * Modify Options array directly
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hoot_theme_modify_customizer_options( $options ) {

	for ( $slide = 1; $slide <= 4; $slide++ ) {
		unset( $options['settings']["wt_html_slide_{$slide}"] );
		unset( $options['settings']["wt_html_slide_{$slide}-background"] );
		unset( $options['settings']["wt_img_slide_{$slide}"] );
	}

	// $options['settings']['load_minified']['section'] = 'tweaks';
	// $options['settings']['load_minified']['priority'] = '2005';
	$options['sections']['colors']['description'] = '';
	$options['sections']['backgrounds']['description'] = '';
	// $options['sections']['typography']['description'] = '';
	$options['settings']['frontpage_sections']['choices']['slider_html'] = __( 'Slider A', 'creattica-premium');
	$options['sections']['slider_html']['title'] = __('Frontpage - Slider A', 'creattica-premium');
	$options['sections']['slider_html']['description'] = '';
	$options['settings']['frontpage_sections']['choices']['slider_img'] = __( 'Slider B', 'creattica-premium');
	$options['sections']['slider_img']['title'] = __('Frontpage - Slider B', 'creattica-premium');
	$options['sections']['slider_img']['description'] = '';

	return $options;
}
add_filter( 'hoot_theme_customizer_options', 'hoot_theme_modify_customizer_options', 9 );

/**
 * Modify labels for $logofont
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hoot_theme_modify_options_logofont( $logofont ) {
	return array(
		'heading'  => __("Logo Font (set in 'Typography' section)", 'creattica-premium'),
		'heading2' => __("Heading Font (set in 'Typography' section)", 'creattica-premium'),
		'standard' => __('Body Font', 'creattica-premium'),
		);
}
add_filter( 'hoot_theme_options_logofont', 'hoot_theme_modify_options_logofont' );

/**
 * Add theme specific option specific css
 *
 * @since 1.0
 * @access public
 * @return void
 */
function hoot_theme_premium_customizer_inlinecss() {
	echo '<style>' . '#customize-control-pageheader_background{margin-bottom:0;padding-bottom:5px;}#customize-control-pageheader_background_location .customize-control-title {font-size:12px;}' . '#customize-control-leftbar_background,#customize-control-logo_background{margin-top:-15px;}#customize-control-leftbar_background .customize-control-title,#customize-control-logo_background .customize-control-title{font-size:12px;}' . '</style>';
}
add_action( 'customize_controls_print_styles', 'hoot_theme_premium_customizer_inlinecss' );

/**
 * Callback Functions for customizer settings
 */

function hoot_callback_leftbar_background( $control ) {
	$selector = $control->manager->get_setting('leftbar_background_type')->value();
	return ( $selector == 'background' ) ? true : false;
}
function hoot_callback_logo_background( $control ) {
	$selector = $control->manager->get_setting('logo_background_type')->value();
	return ( $selector == 'background' ) ? true : false;
}
function hoot_callback_404_custom_page( $control ) {
	$selector = $control->manager->get_setting('404_page')->value();
	return ( $selector == 'custom' ) ? true : false;
}