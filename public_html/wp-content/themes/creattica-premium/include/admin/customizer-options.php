<?php
/**
 * Defines customizer options
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
if ( !function_exists( 'hoot_theme_customizer_options' ) ) :
function hoot_theme_customizer_options() {

	// Stores all the settings to be added
	$settings = array();

	// Stores all the sections to be added
	$sections = array();

	// Stores all the panels to be added
	$panels = array();

	// Theme default colors and fonts
	extract( apply_filters( 'hoot_theme_options_defaults', array(
		'accent_color'         => '#e23348',
		'accent_font'          => '#ffffff',
		'box_background'       => '#ffffff',
		'box_background_patt'  => 'hybrid/extend/images/patterns/4.png',
		'site_background'      => '#ffffff',
	) ) );

	// Directory path for radioimage buttons
	$imagepath =  HYBRIDEXTEND_INCURI . 'admin/images/';

	// Logo Sizes (different range than standard typography range)
	$logosizes = array();
	$logosizerange = range( 14, 110 );
	foreach ( $logosizerange as $isr )
		$logosizes[ $isr . 'px' ] = $isr . 'px';
	$logosizes = apply_filters( 'hoot_theme_options_logosizes', $logosizes);

	// Logo Font Options for Lite version
	$logofont = apply_filters( 'hoot_theme_options_logofont', array(
					'heading' => __("Logo Font (set in 'Logo' section)", 'creattica-premium'),
					'standard' => __('Standard Body Font', 'creattica-premium'),
					) );

	/*** Add Options (Panels, Sections, Settings) ***/

	/** Section **/

	$section = 'links';

	$sections[ $section ] = array(
		'title'       => __( 'Demo Install / Support', 'creattica-premium' ),
		'priority'    => '2',
	);

	$lcontent = '';
	$lcontent .= '<a class="hoot-cust-link" href="' .
				 'https://demo.wphoot.com/creattica/' .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="fas fa-eye"></i> ' .
				 __( "Demo", 'creattica-premium') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 __( "Demo the theme features and options with sample content.", 'creattica-premium') .
				 '</span></a>';
	$ocdilink = ( class_exists( 'Hoot_Theme_Premium' ) ) ? ( ( class_exists( 'OCDI_Plugin' ) ) ? admin_url( 'themes.php?page=pt-one-click-demo-import' ) : 'https://wphoot.com/support/creattica/#docs-section-demo-content' ) : 'https://wphoot.com/support/creattica/#docs-section-demo-content-free';
	$lcontent .= '<a class="hoot-cust-link" href="' .
				 esc_url( $ocdilink ) .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="fas fa-upload"></i> ' .
				 esc_html__( "1 Click Installation", 'creattica-premium') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 esc_html__( "Install demo content to make your site look exactly like the Demo Site. Use it as a starting point instead of starting from scratch.", 'creattica-premium') .
				 '</span></a>';
	$lcontent .= '<a class="hoot-cust-link" href="' .
				 'https://wphoot.com/support/' .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '<i class="far fa-life-ring"></i> ' .
				 __( "Documentation / Support", 'creattica-premium') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 __( "Get theme related support for both free and premium users.", 'creattica-premium') .
				 '</span></a>';
	$lcontent .= '<a class="hoot-cust-link" href="' .
				 'https://wordpress.org/support/theme/creattica/reviews/#new-post' .
				 '" target="_blank"><span class="hoot-cust-link-head">' .
				 '5 <i class="fas fa-star"></i> ' .
				 __( "Rate Us", 'creattica-premium') . 
				 '</span><span class="hoot-cust-link-desc">' .
				 /* translators: five stars */
				 sprintf( esc_html__( 'If you are happy with the theme, please give us a %1$s rating on wordpress.org. Thanks in advance!', 'creattica-premium'), '<span style="color:#0073aa;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ) .
				 '</span></a>';

	$settings['linksection'] = array(
		// 'label'       => __( 'Misc Links', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'content',
		'priority'    => '8', // Non static options must have a priority
		'content'     => $lcontent,
	);

	/** Section **/

	$section = 'title_tagline';

	$sections[ $section ] = array(
		'title'       => __( 'Setup &amp; Layout', 'creattica-premium' ),
	);

	$settings['site_width'] = array(
		'label'       => __( 'Max. Site Width (pixels)', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'1260' => __('Wide', 'creattica-premium'), // 1260 + 270
			'1080' => __('Standard', 'creattica-premium'), // 1080 + 270
			'780'  => __('Compact', 'creattica-premium'), // 780 + 270 = 1050
		),
		'default'     => '1260',
	);

	$settings['sidebar_desc'] = array(
		'label'       => __( 'Multiple Sidebars', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'content',
		'content'     => sprintf( __( 'This theme can display different sidebar content on different pages of your site with the %1sCustom Sidebars%2s plugin. Simply install and activate the plugin to use it with this theme. Or if you are using %3sJetpack%4s, you can use the %5s"Widget Visibility"%6s module.', 'creattica-premium' ), '<a href="https://wordpress.org/plugins/custom-sidebars/screenshots/" target="_blank">', '</a>', '<a href="https://wordpress.org/plugins/jetpack/" target="_blank">', '</a>', '<a href="https://jetpack.com/support/widget-visibility/" target="_blank">', '</a>' ),
	);

	$settings['sidebar'] = array(
		'label'       => __( 'Sidebar Layout (Site-wide)', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'wide-right'         => $imagepath . 'sidebar-wide-right.png',
			'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
			'wide-left'          => $imagepath . 'sidebar-wide-left.png',
			'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
			'full-width'         => $imagepath . 'sidebar-full.png',
			'none'               => $imagepath . 'sidebar-none.png',
		),
		'default'     => 'wide-right',
		'description' => __("Set the default sidebar width and position for your site.", 'creattica-premium'),
	);

	$settings['sidebar_fp'] = array(
		'label'       => __( 'Sidebar Layout (for Front Page)', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'wide-right'         => $imagepath . 'sidebar-wide-right.png',
			'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
			'wide-left'          => $imagepath . 'sidebar-wide-left.png',
			'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
			'full-width'         => $imagepath . 'sidebar-full.png',
			'none'               => $imagepath . 'sidebar-none.png',
		),
		'default'     => ( ( 'page' == get_option('show_on_front' ) ) ? 'full-width' : 'wide-right' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'description' => sprintf( esc_html__( 'This is sidebar for the Frontpage Content Module in %1$sFrontpage Modules Settings%2$s', 'creattica-premium' ), '<a href="' . esc_url( admin_url( 'customize.php?autofocus[control]=frontpage_content_desc' ) ) . '" rel="focuslink" data-focustype="control" data-href="frontpage_content_desc">', '</a>' ),
	);

	$settings['sidebar_archives'] = array(
		'label'       => __( 'Sidebar Layout (for Blog/Archives)', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'wide-right'         => $imagepath . 'sidebar-wide-right.png',
			'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
			'wide-left'          => $imagepath . 'sidebar-wide-left.png',
			'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
			'full-width'         => $imagepath . 'sidebar-full.png',
			'none'               => $imagepath . 'sidebar-none.png',
		),
		'default'     => 'wide-right',
	);

	$settings['sidebar_pages'] = array(
		'label'       => __( 'Sidebar Layout (for Pages)', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'wide-right'         => $imagepath . 'sidebar-wide-right.png',
			'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
			'wide-left'          => $imagepath . 'sidebar-wide-left.png',
			'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
			'full-width'         => $imagepath . 'sidebar-full.png',
			'none'               => $imagepath . 'sidebar-none.png',
		),
		'default'     => 'wide-right',
	);

	$settings['sidebar_posts'] = array(
		'label'       => __( 'Sidebar Layout (for single Posts)', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'wide-right'         => $imagepath . 'sidebar-wide-right.png',
			'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
			'wide-left'          => $imagepath . 'sidebar-wide-left.png',
			'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
			'full-width'         => $imagepath . 'sidebar-full.png',
			'none'               => $imagepath . 'sidebar-none.png',
		),
		'default'     => 'wide-right',
	);

	$settings['load_minified'] = array(
		'label'       => __( 'Load Minified Styles and Scripts (when available)', 'creattica-premium' ),
		'sublabel'    => __( 'Checking this option reduces data size, hence increasing page load speed.', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'checkbox',
		// 'default'     => 1,
	);

	$settings['leftbar_menu_superfish'] = array(
		'label'       => __( '[Menu]: Expand Dropdown on Hover', 'creattica-premium' ),
		'sublabel'    => __( 'By default menu dropdowns (sub-menus) expand on clicking.', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'checkbox',
		// 'default'     => '1',
	);

	$settings['mobile_menu'] = array(
		'label'       => __( '[Mobile Menu]:', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'inline' => __('Inline - Menu Slide Downs to open', 'creattica-premium'),
			'fixed'  => __('Fixed - Menu opens on the left', 'creattica-premium'),
		),
		'default'     => 'fixed',
		'priority'    => '95', // @todo remove
	);

	// $settings['mobile_submenu_click'] = array(
	// 	'label'       => __( "[Mobile Menu]: Submenu opens on 'Click'", 'creattica-premium' ),
	// 	'section'     => $section,
	// 	'type'        => 'checkbox',
	// 	'default'     => 1,
	// 	'priority'    => '95', // @todo remove
	// 	'description' => __( "Uncheck this option to make all Submenus appear in 'Open' state. By default, submenus open on clicking (i.e. single tap on mobile).", 'creattica-premium' ),
	// );

	$settings['leftbar_bottom_mobile_display'] = array(
		'label'       => __( '[Mobile]: Show Leftbar Bottom area on mobile', 'creattica-premium' ),
		'sublabel'    => __( 'By default this area is hidden on mobile.', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'default'     => '1',
	);

	$settings['leftbar_bottom_mobile_location'] = array(
		'label'           => __( '[Mobile]: Leftbar Bottom Area location on mobile', 'creattica-premium' ),
		'sublabel'        => __( 'By default this area is hidden on mobile.', 'creattica-premium' ),
		'section'         => $section,
		'type'            => 'radio',
		'choices'         => array(
			'top'     => __( 'Top (next to header)', 'creattica-premium'),
			'bottom'  => __( 'Bottom (next to footer)', 'creattica-premium'),
		),
		'default'         => 'bottom',
		'active_callback' => 'hoot_callback_leftbar_bottom_mobile_location',
	);

	/** Section **/

	$section = 'logo';

	$sections[ $section ] = array(
		'title'       => __( 'Logo', 'creattica-premium' ),
	);

	$settings['logo_fontface'] = array(
		'label'       => __( 'Logo Font (Free Version)', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => 114, // Non static options must have a priority
		'choices'     => array(
			'standard' => __( 'Standard Font (Open Sans)', 'creattica-premium'),
			'cursive'  => __( 'Cursive Font (Pacifico)', 'creattica-premium'),
		),
		'default'     => 'standard',
	);

	$settings['logo_background_type'] = array(
		'label'       => __( 'Logo Background', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radio',
		'priority'    => '115', // Non static options must have a priority
		'choices'     => array(
			'transparent' => __('None', 'creattica-premium'),
			'accent'      => __('Accent Color', 'creattica-premium'),
		),
		'default'     => 'accent',
	);

	$settings['logo'] = array(
		'label'       => __( 'Site Logo', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'text'        => __('Default Text (Site Title)', 'creattica-premium'),
			'custom'      => __('Custom Text', 'creattica-premium'),
			'image'       => __('Image Logo', 'creattica-premium'),
			'mixed'       => __('Image &amp; Default Text (Site Title)', 'creattica-premium'),
			'mixedcustom' => __('Image &amp; Custom Text', 'creattica-premium'),
		),
		'default'     => 'text',
		'description' => sprintf( __('Use %sSite Title%s as default text logo', 'creattica-premium'), '<a href="' . esc_url( admin_url('options-general.php') ) . '" target="_blank">', '</a>' ),
	);

	$settings['logo_size'] = array(
		'label'       => __( 'Logo Size', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'select',
		'priority'    => '125', // Non static options must have a priority
		'choices'     => array(
			'tiny'               => __( 'Tiny', 'creattica-premium'),
			'tiny displayfont'   => __( 'Tiny (emphasized)', 'creattica-premium'),
			'small'              => __( 'Small', 'creattica-premium'),
			'small displayfont'  => __( 'Small (emphasized)', 'creattica-premium'),
			'medium'             => __( 'Medium', 'creattica-premium'),
			'medium displayfont' => __( 'Medium (emphasized)', 'creattica-premium'),
			'large'              => __( 'Large', 'creattica-premium'),
			'large displayfont'  => __( 'Large (emphasized)', 'creattica-premium'),
			'huge'               => __( 'Huge', 'creattica-premium'),
			'huge displayfont'   => __( 'Huge (emphasized)', 'creattica-premium'),
		),
		'default'     => 'medium displayfont',
		'active_callback' => 'hoot_callback_logo_size',
	); // Removed in premium

	$settings['site_title_icon'] = array(
		'label'           => __( 'Site Title Icon (Optional)', 'creattica-premium' ),
		'section'         => $section,
		'type'            => 'icon',
		// 'default'         => 'fa-anchor fas',
		'description'     => __( 'Leave empty to hide icon.', 'creattica-premium' ),
		'active_callback' => 'hoot_callback_site_title_icon',
	);

	$settings['site_title_icon_size'] = array(
		'label'           => __( 'Site Title Icon Size', 'creattica-premium' ),
		'section'         => $section,
		'type'            => 'select',
		'choices'         => $logosizes,
		'default'         => '50px',
		'active_callback' => 'hoot_callback_site_title_icon',
	);

	$logo_custom_line_options = array(
		'text' => array(
			'label'       => __( 'Line Text', 'creattica-premium' ),
			'type'        => 'text',
		),
		'size' => array(
			'label'       => __( 'Line Size', 'creattica-premium' ),
			'type'        => 'select',
			'choices'     => $logosizes,
			'default'     => '24px',
		),
		'font' => array(
			'label'       => __( 'Line Font', 'creattica-premium' ),
			'type'        => 'select',
			'choices'     => $logofont,
			'default'     => 'heading',
		),
	);

	$settings['logo_custom'] = array(
		'label'           => __( 'Custom Logo Text', 'creattica-premium' ),
		'section'         => $section,
		'type'            => 'sortlist',
		'choices'         => array(
			'line1' => __('Line 1', 'creattica-premium'),
			'line2' => __('Line 2', 'creattica-premium'),
			'line3' => __('Line 3', 'creattica-premium'),
			'line4' => __('Line 4', 'creattica-premium'),
		),
		'default'     => array(
			'line3'  => array( 'sortitem_hide' => 1, ),
			'line4'  => array( 'sortitem_hide' => 1, ),
		),
		'options'         => array(
			'line1' => $logo_custom_line_options,
			'line2' => $logo_custom_line_options,
			'line3' => $logo_custom_line_options,
			'line4' => $logo_custom_line_options,
		),
		'attributes'      => array(
			'hideable' => true,
			'sortable' => false,
		),
		'active_callback' => 'hoot_callback_logo_custom',
	);

	$settings['show_tagline'] = array(
		'label'           => __( 'Show Tagline', 'creattica-premium' ),
		'sublabel'        => __( 'Display site description as tagline below logo.', 'creattica-premium' ),
		'section'         => $section,
		'type'            => 'checkbox',
		'default'         => 1,
		'active_callback' => 'hoot_callback_show_tagline',
	);

	/** Section **/

	$section = 'colors';

	// Redundant as 'colors' section is added by WP. But we still add it for brevity
	$sections[ $section ] = array(
		'title'       => __( 'Colors', 'creattica-premium' ),
		// 'description' => __( 'Control even more color options in the premium version for fonts, backgrounds, contrast, highlight, accent etc.', 'creattica-premium' ),
	);

	$settings['accent_color'] = array(
		'label'       => __( 'Accent Color', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $accent_color,
	);

	$settings['accent_font'] = array(
		'label'       => __( 'Font Color on Accent Color', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'color',
		'default'     => $accent_font,
	);

	if ( current_theme_supports( 'woocommerce' ) ) :
		$settings['woocommerce-colors-plugin'] = array(
			'label'       => __( 'Woocommerce Styling', 'creattica-premium' ),
			'section'     => $section,
			'type'        => 'content',
			'priority'    => '185', // Non static options must have a priority
			'content'     => sprintf( __('Looks like you are using Woocommerce. Install %sthis plugin%s to change colors and styles for WooCommerce elements like buttons etc.', 'creattica-premium'), '<a href="https://wordpress.org/plugins/woocommerce-colors/" target="_blank">', '</a>' ),
		);
	endif;

	/** Section **/

	$section = 'backgrounds';

	$sections[ $section ] = array(
		'title'       => __( 'Backgrounds', 'creattica-premium' ),
		// 'description' => __( 'The premium version comes with background options for different sections of your site like header, menu dropdown, content area, logo background, footer etc.', 'creattica-premium' ),
	);

	$settings['background'] = array(
		'label'       => __( 'Site Background', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'betterbackground',
		'priority'    => 185,
		'default'     => array(
			'color'      => $site_background,
		),
	);

	$settings['box_background'] = array(
		'label'       => __( 'Content Box Background', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'betterbackground',
		'priority'    => 185,
		'default'     => array(
			'color'      => $box_background,
			'pattern'    => $box_background_patt,
		),
	);

	/** Section **/

	$section = 'frontpage';

	$sections[ $section ] = array(
		'title'       => __( 'Frontpage - Modules', 'creattica-premium' ),
	);

	$widget_area_options = array(
		'columns' => array(
			'label'   => __( 'Columns', 'creattica-premium' ),
			'type'    => 'select',
			'choices' => array(
				'100'         => __('One Column [100]', 'creattica-premium'),
				'50-50'       => __('Two Columns [50 50]', 'creattica-premium'),
				'33-66'       => __('Two Columns [33 66]', 'creattica-premium'),
				'66-33'       => __('Two Columns [66 33]', 'creattica-premium'),
				'25-75'       => __('Two Columns [25 75]', 'creattica-premium'),
				'75-25'       => __('Two Columns [75 25]', 'creattica-premium'),
				'33-33-33'    => __('Three Columns [33 33 33]', 'creattica-premium'),
				'25-25-50'    => __('Three Columns [25 25 50]', 'creattica-premium'),
				'25-50-25'    => __('Three Columns [25 50 25]', 'creattica-premium'),
				'50-25-25'    => __('Three Columns [50 25 25]', 'creattica-premium'),
				'25-25-25-25' => __('Four Columns [25 25 25 25]', 'creattica-premium'),
			),
		),
		'modulebg' => array(
			'label'       => '',
			'type'        => 'content',
			'content'     => '<div class="button">' . __( 'Module Background', 'creattica-premium' ) . '</div>',
		),
	);

	$settings['frontpage_sections'] = array(
		'label'       => __( 'Frontpage Widget Areas', 'creattica-premium' ),
		'sublabel'    => sprintf( __("<ul><li>Sort different sections of the Frontpage in the order you want them to appear.</li><li>You can add content to widget areas from the %1sWidgets Management screen%2s.</li><li>You can disable areas by clicking the 'eye' icon. (This will hide them on the Widgets screen as well)</li></ul>", 'creattica-premium'), '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>' ),
		'section'     => $section,
		'type'        => 'sortlist',
		'choices'     => array(
			'slider_html' => __('HTML Slider', 'creattica-premium'),
			'slider_img'  => __('Image Slider', 'creattica-premium'),
			'area_a'      => __('Widget Area A', 'creattica-premium'),
			'area_b'      => __('Widget Area B', 'creattica-premium'),
			'area_c'      => __('Widget Area C', 'creattica-premium'),
			'area_d'      => __('Widget Area D', 'creattica-premium'),
			'area_e'      => __('Widget Area E', 'creattica-premium'),
			'content'     => __('Frontpage Content', 'creattica-premium'),
		),
		'default'     => array(
			// 'content' => array( 'sortitem_hide' => 1, ),
			'area_b'  => array( 'columns' => '50-50' ),
		),
		'options'     => array(
			'slider_html' => array(
				'modulebg' => array(
					'label'       => '',
					'type'        => 'content',
					'content'     => '<div class="button">' . __( 'Module Background', 'creattica-premium' ) . '</div>',
				),
			),
			'slider_img'  => array(
				'modulebg' => array(
					'label'       => '',
					'type'        => 'content',
					'content'     => '<div class="button">' . __( 'Module Background', 'creattica-premium' ) . '</div>',
				),
			),
			'area_a'      => $widget_area_options,
			'area_b'      => $widget_area_options,
			'area_c'      => $widget_area_options,
			'area_d'      => $widget_area_options,
			'area_e'      => $widget_area_options,
			'content'     => array(
							'title' => array(
								'label'       => __( 'Title (optional)', 'creattica-premium' ),
								'type'        => 'text',
							),
							'modulebg' => array(
								'label'       => '',
								'type'        => 'content',
								'content'     => '<div class="button">' . __( 'Module Background', 'creattica-premium' ) . '</div>',
							), ),
		),
		'attributes'  => array(
			'hideable'      => true,
			'sortable'      => true,
			'open-state'    => 'area_a',
		),
		// 'description' => sprintf( __('You must first save the changes you make here and refresh this screen for widget areas to appear in the Widgets panel (in customizer). Once you save the settings, you can add content to these widget areas using the %sWidgets Management screen%s.', 'creattica-premium'), '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>' ),
	);

	$settings['frontpage_content_desc'] = array(
		'label'       => __( "Frontpage Content", 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'content',
		'content'     => sprintf( __( "The 'Frontpage Content' module in above list will show<ul style='list-style:disc;margin:1em 0 0 2em;'><li>the <strong>'Blog'</strong> if you have <strong>Your Latest Posts</strong> selectd in %3sReading Settings%4s</li><li>the <strong>Page Content</strong> of the page set as Front page if you have <strong>A static page</strong> selected in %3sReading Settings%4s</li></ul>", 'creattica-premium' ), '<a href="' . esc_url( admin_url('options-reading.php') ) . '" target="_blank">', '</a>', '<a href="' . esc_url( admin_url('options-reading.php') ) . '" target="_blank">', '</a>' ),
	);

	$settings["frontpage_sectionbg_slider_html"] =
	$settings["frontpage_sectionbg_slider_img"] = array(
		'label'       => '',
		'section'     => $section,
		'type'        => 'group',
		'priority'    => 205,
		'startwrap'   => 'fp-section-bg-button',
		'button'      => __( 'Module Background', 'creattica-premium' ),
		'options'     => array(
			'description' => array(
				'label'       => '',
				'type'        => 'content',
				'content'     => '<span class="hoot-module-bg-title">' . __('Slider Background', 'creattica-premium') . '</span>',
			),
			'type' => array(
				'label'   => __( 'Background Type', 'creattica-premium' ),
				'type'    => 'radio',
				'choices' => array(
					'none'        => __('None', 'creattica-premium'),
					'highlight'   => __('Highlight Color', 'creattica-premium'),
					'accent'      => __('Accent Color', 'creattica-premium'),
				),
				'default' => 'none',
			),
		),
	);

	$frontpagemodule_bg = apply_filters( 'hoot_theme_frontpage_widgetarea_sectionbg_index', array(
		'area_a'      => __('Widget Area A', 'creattica-premium'),
		'area_b'      => __('Widget Area B', 'creattica-premium'),
		'area_c'      => __('Widget Area C', 'creattica-premium'),
		'area_d'      => __('Widget Area D', 'creattica-premium'),
		'area_e'      => __('Widget Area E', 'creattica-premium'),
		'content'     => __('Frontpage Content', 'creattica-premium'),
		) );

	foreach ( $frontpagemodule_bg as $fpgmodid => $fpgmodname ) {

		$settings["frontpage_sectionbg_{$fpgmodid}"] = array(
			'label'       => '', // __( 'Module Background', 'creattica-premium' ),
			'section'     => $section,
			'type'        => 'group',
			'priority'    => 205,
			'startwrap'   => 'fp-section-bg-button',
			'button'      => __( 'Module Background', 'creattica-premium' ),
			'options'     => array(
				'description' => array(
					'label'       => '',
					'type'        => 'content',
					'content'     => '<span class="hoot-module-bg-title">' . $fpgmodname . '</span>',
				),
				'type' => array(
					'label'   => __( 'Background Type', 'creattica-premium' ),
					'type'    => 'radio',
					'choices' => array(
						'none'        => __('None', 'creattica-premium'),
						'highlight'   => __('Highlight Color', 'creattica-premium'),
						'image'       => __('Image', 'creattica-premium'),
					),
					// 'default' => ( ( $fpgmodid == 'area_b' ) ? 'image' :
					// 											( ( $fpgmodid == 'area_d' ) ? 'highlight' : 'none' )
					// 			 ),
					'default' => ( ( $fpgmodid == 'area_b' ) ? 'image' : 'none' ),
				),
				'image' => array(
					'label'       => __( "Background Image (Select 'Image' above)", 'creattica-premium' ),
					'type'        => 'image',
					'default' => ( ( $fpgmodid == 'area_b' ) ? HYBRID_PARENT_URI . 'images/modulebg.jpg' : '' ),
				),
				'parallax' => array(
					'label'   => __( 'Apply Parallax Effect to Background Image', 'creattica-premium' ),
					'type'    => 'checkbox',
					'default' => ( ( $fpgmodid == 'area_b' ) ? 1 : 0 ),
				),
			),
		);

	} // end for

	/** Section **/

	$section = 'slider_html';

	$sections[ $section ] = array(
		'title'       => __( 'Frontpage - HTML Slider', 'creattica-premium' ),
		// 'description' => __( 'The premium version comes with a separate Slider type allowing creation of Unlimited slides.', 'creattica-premium' ),
	);

	$settings['wt_html_slider_width'] = array(
		'label'       => __( 'Slider Padding', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'boxed'   => __('With Padding', 'creattica-premium'),
			'stretch' => __('Stretched (no padding)', 'creattica-premium'),
		),
		'default'     => 'stretch',
	);

	$settings['wt_html_slider_min_height'] = array(
		'label'       => __( 'Minimum Slider Height (in pixels)', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'text',
		'priority'    => 215, // Non static options must have a priority
		'default'     => 375,
		'description' => __('Leave empty to let the slider height adjust automatically.', 'creattica-premium'),
		'input_attrs' => array(
			'placeholder' => __( '(in pixels)', 'creattica-premium' ),
		),
	);

	for ( $slide = 1; $slide <= 4; $slide++ ) {

		$settings["wt_html_slide_{$slide}"] = array(
			'label'       => sprintf( __( 'Slide %s Content', 'creattica-premium' ), $slide),
			'section'     => $section,
			'type'        => 'group',
			'priority'    => 215, // Non static options must have a priority
			'button'      => sprintf( __( 'Edit Slide %s', 'creattica-premium' ), $slide),
			'options'     => array(
				'description' => array(
					'label'       => '',
					'type'        => 'content',
					'content'     => '<span class="hoot-module-bg-title">' . sprintf( __( 'Slide %s Content', 'creattica-premium' ), $slide) . '</span>',
				),
				'image' => array(
					'label'       => __( 'Showcase Image (Right Column)', 'creattica-premium' ),
					'type'        => 'content',
					'description' => __( 'If the page below has a featured image, it will be used as the Showcase Image (image in right column)', 'creattica-premium' ),
				),
				'content' => array(
					'label'       => __( 'Content (Left Column)', 'creattica-premium' ),
					'type'        => 'select',
					'choices'     => array( __( 'Select Page', 'creattica-premium' ) ) + HybridExtend_Options_Helper::pages(),
				),
				'content_bg' => array(
					'label'   => __( 'Content Styling', 'creattica-premium' ),
					'type'    => 'select',
					'default' => 'light-on-dark',
					'choices' => array(
						'dark'          => __('Dark Font', 'creattica-premium'),
						'light'         => __('Light Font', 'creattica-premium'),
						'dark-on-light' => __('Dark Font / Light Background', 'creattica-premium'),
						'light-on-dark' => __('Light Font / Dark Background', 'creattica-premium'),
					),
				),
				'button' => array(
					'label'       => __( 'Button Text', 'creattica-premium' ),
					'type'        => 'text',
				),
				'url' => array(
					'label'       => __( 'Button URL', 'creattica-premium' ),
					'type'        => 'url',
					'description' => __( 'Leave empty if you do not want to show the button.', 'creattica-premium' ),
					'input_attrs' => array(
						'placeholder' => 'http://',
					),
				),
			),
		);

		$settings["wt_html_slide_{$slide}-background"] = array(
			'label'       => sprintf( __( 'Slide %s Background', 'creattica-premium' ), $slide),
			'section'     => $section,
			'type'        => 'betterbackground',
			'priority'    => 215, // Non static options must have a priority
			'default'     => array(
				'color'      => '#dddddd',
			),
			'options'     => array( 'color', 'image', 'pattern' ),
		);

	} // end for

	/** Section **/

	$section = 'slider_img';

	$sections[ $section ] = array(
		'title'       => __( 'Frontpage - Image Slider', 'creattica-premium' ),
		// 'description' => __( 'The premium version comes with a separate Slider type allowing creation of Unlimited slides.', 'creattica-premium' ),
	);

	$settings['wt_img_slider_width'] = array(
		'label'       => __( 'Slider Padding', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'boxed'   => __('With Padding', 'creattica-premium'),
			'stretch' => __('Stretched (no padding)', 'creattica-premium'),
		),
		'default'     => 'stretch',
	);

	for ( $slide = 1; $slide <= 4; $slide++ ) { 

		$settings["wt_img_slide_{$slide}"] = array(
			'label'       => '',//sprintf( __( 'Slide %s Content', 'creattica-premium' ), $slide),
			'section'     => $section,
			'type'        => 'group',
			'priority'    => 225, // Non static options must have a priority
			'button'      => sprintf( __( 'Edit Slide %s', 'creattica-premium' ), $slide),
			'options'     => array(
				'description' => array(
					'label'       => '',
					'type'        => 'content',
					'content'     => '<span class="hoot-module-bg-title">' . sprintf( __( 'Slide %s Content', 'creattica-premium' ), $slide) . '</span>' . __( '<em>To hide this slide, simply leave the Image empty.</em>', 'creattica-premium' ),
				),
				'image' => array(
					'label'       => __( 'Slide Image', 'creattica-premium' ),
					'type'        => 'image',
					'description' => __( 'The main showcase image.', 'creattica-premium' ),
					'default'     => HYBRID_PARENT_URI . 'images/placeholder' . $slide . '.png',
				),
				'caption' => array(
					'label'       => __( 'Slide Caption (optional)', 'creattica-premium' ),
					'type'        => 'textarea',
					'default'     => __( 'This is a sample description text for the slide.', 'creattica-premium'),
					'description' => __('You can use the <code>&lt;h3&gt;Lorem Ipsum Dolor&lt;/h3&gt;</code> tag to create styled heading.', 'creattica-premium'),
				),
				'caption_bg' => array(
					'label'   => __( 'Caption Styling', 'creattica-premium' ),
					'type'    => 'select',
					'default' => 'light-on-dark',
					'choices' => array(
						'dark'          => __('Dark Font', 'creattica-premium'),
						'light'         => __('Light Font', 'creattica-premium'),
						'dark-on-light' => __('Dark Font / Light Background', 'creattica-premium'),
						'light-on-dark' => __('Light Font / Dark Background', 'creattica-premium'),
					),
				),
				'url' => array(
					'label'       => __( 'Slide Link', 'creattica-premium' ),
					'type'        => 'url',
					'description' => __( 'Leave empty if you do not want to link the slide.', 'creattica-premium' ),
					'input_attrs' => array(
						'placeholder' => 'http://',
					),
				),
				'button' => array(
					'label'       => __( 'Slide Button Text', 'creattica-premium' ),
					'type'        => 'text',
					'description' => __( 'Leave empty if you do not want to show the button and instead link the slide image (if you have a url set in the above field)', 'creattica-premium' ),
				),
			),
		);

	} // end for

	/** Section **/

	$section = 'archives';

	$sections[ $section ] = array(
		'title'       => __( 'Archives (Blog, Cats, Tags)', 'creattica-premium' ),
	);

	$settings['archive_post_content'] = array(
		'label'       => __( 'Post Items Content', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'none' => __('None', 'creattica-premium'),
			'excerpt' => __('Post Excerpt', 'creattica-premium'),
			'full-content' => __('Full Post Content', 'creattica-premium'),
		),
		'default'     => 'excerpt',
		'description' => __( 'Content to display for each post in the list', 'creattica-premium' ),
	);

	$settings['archive_post_meta'] = array(
		'label'       => __( 'Meta Information for Post List Items', 'creattica-premium' ),
		'sublabel'    => __( 'Check which meta information to display for each post item in the archive list.', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'author'   => __('Author', 'creattica-premium'),
			'date'     => __('Date', 'creattica-premium'),
			'cats'     => __('Categories', 'creattica-premium'),
			'tags'     => __('Tags', 'creattica-premium'),
			'comments' => __('No. of comments', 'creattica-premium')
		),
		'default'     => 'author, date, cats, comments',
	);

	$settings['excerpt_length'] = array(
		'label'       => __( 'Excerpt Length', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'text',
		'description' => __( 'Number of words in excerpt. Default is 105. Leave empty if you dont want to change it.', 'creattica-premium' ),
		'input_attrs' => array(
			'placeholder' => __( 'default: 105', 'creattica-premium' ),
		),
	);

	$settings['read_more'] = array(
		'label'       => __( "'Read More' Text", 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'text',
		'description' => __( "Replace the default 'Read More' text. Leave empty if you dont want to change it.", 'creattica-premium' ),
		'input_attrs' => array(
			'placeholder' => __( 'default: READ MORE &rarr;', 'creattica-premium' ),
		),
	);

	/** Section **/

	$section = 'singular';

	$sections[ $section ] = array(
		'title'       => __( 'Single (Posts, Pages)', 'creattica-premium' ),
	);

	$settings['page_header_full'] = array(
		'label'       => __( 'Stretch Page Header to Full Width', 'creattica-premium' ),
		'sublabel'    => '<img src="' . $imagepath . 'page-header.png">',
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'default'    => __('Default (Archives, Blog, Woocommerce etc.)', 'creattica-premium'),
			'posts'      => __('For All Posts', 'creattica-premium'),
			'pages'      => __('For All Pages', 'creattica-premium'),
			'no-sidebar' => __('Always override for full width pages (any page which has no sidebar)', 'creattica-premium'),
		),
		'default'     => 'default, pages, no-sidebar',
		'description' => __('This is the Page Header area containing Page/Post Title and Meta details like author, categories etc.', 'creattica-premium'),
	);

	$settings['post_featured_image'] = array(
		'label'       => __( 'Display Featured Image', 'creattica-premium' ),
		'sublabel'    => __( 'Display featured image at the beginning of post/page content.', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'default'     => 1,
	);

	$settings['post_meta'] = array(
		'label'       => __( 'Meta Information on Posts', 'creattica-premium' ),
		'sublabel'    => __( "Check which meta information to display on an individual 'Post' page", 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'author'   => __('Author', 'creattica-premium'),
			'date'     => __('Date', 'creattica-premium'),
			'cats'     => __('Categories', 'creattica-premium'),
			'tags'     => __('Tags', 'creattica-premium'),
			'comments' => __('No. of comments', 'creattica-premium')
		),
		'default'     => 'author, date, cats, tags, comments',
	);

	$settings['page_meta'] = array(
		'label'       => __( 'Meta Information on Page', 'creattica-premium' ),
		'sublabel'    => __( "Check which meta information to display on an individual 'Page' page", 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'choices'     => array(
			'author'   => __('Author', 'creattica-premium'),
			'date'     => __('Date', 'creattica-premium'),
			'comments' => __('No. of comments', 'creattica-premium')
		),
		'default'     => 'author, date, comments',
	);

	$settings['post_meta_location'] = array(
		'label'       => __( 'Meta Information location', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radio',
		'choices'     => array(
			'top'    => __('Top (below title)', 'creattica-premium'),
			'bottom' => __('Bottom (after content)', 'creattica-premium'),
		),
		'default'     => 'top',
	);

	$settings['post_prev_next_links'] = array(
		'label'       => __( 'Previous/Next Posts', 'creattica-premium' ),
		'sublabel'    => __( 'Display links to Prev/Next Post links at the end of post content.', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'checkbox',
		'default'     => 1,
	);

	$settings['contact-form'] = array(
		'label'       => __( 'Contact Form', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'content',
		'content'     => sprintf( __('This theme comes bundled with CSS required to style %sContact-Form-7%s forms. Simply install and activate the plugin to add Contact Forms to your pages.', 'creattica-premium'), '<a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">', '</a>'), // @todo update link to docs
	);

	/** Section **/

	$section = 'footer';

	$sections[ $section ] = array(
		'title'       => __( 'Footer', 'creattica-premium' ),
	);

	$settings['footer'] = array(
		'label'       => __( 'Footer Layout', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'radioimage',
		'choices'     => array(
			'1-1' => $imagepath . '1-1.png',
			'2-1' => $imagepath . '2-1.png',
			'2-2' => $imagepath . '2-2.png',
			'2-3' => $imagepath . '2-3.png',
			'3-1' => $imagepath . '3-1.png',
			'3-2' => $imagepath . '3-2.png',
			'3-3' => $imagepath . '3-3.png',
			'3-4' => $imagepath . '3-4.png',
			'4-1' => $imagepath . '4-1.png',
		),
		'default'     => '4-1',
		'description' => sprintf( __('You must first save the changes you make here and refresh this screen for footer columns to appear in the Widgets panel (in customizer).<hr> Once you save the settings here, you can add content to footer columns using the %sWidgets Management screen%s.', 'creattica-premium'), '<a href="' . esc_url( admin_url('widgets.php') ) . '" target="_blank">', '</a>' ),
	);

	$settings['site_info'] = array(
		'label'       => __( 'Site Info Text (footer)', 'creattica-premium' ),
		'section'     => $section,
		'type'        => 'textarea',
		'default'     => __( '<!--default-->', 'creattica-premium'),
		'description' => sprintf( __('Text shown in footer. Useful for showing copyright info etc.<hr>Use the <code>&lt;!--default--&gt;</code> tag to show the default Info Text.<hr>Use the <code>&lt;!--year--&gt;</code> tag to insert the current year.<hr>Always use %sHTML codes%s for symbols. For example, the HTML for &copy; is <code>&amp;copy;</code>', 'creattica-premium'), '<a href="http://ascii.cl/htmlcodes.htm" target="_blank">', '</a>' ),
	);

	if ( current_theme_supports( 'woocommerce' ) ) :

		/** Section **/

		$section = 'hoot_woocommerce';

		$sections[ $section ] = array(
			'title'       => __( 'WooCommerce (Creattica)', 'creattica-premium' ),
			'priority'    => '150', // Non static options must have a priority // Sample: Menus(100) Widgets(110) Woocommerce (200) Additional CSS(200)
		);

		$wooproducts = range( 0, 99 );
		for ( $wpr=0; $wpr < 4; $wpr++ )
			unset( $wooproducts[$wpr] );
		$settings['wooshop_products'] = array(
			'label'       => __( 'Total Products per page', 'creattica-premium' ),
			'section'     => $section,
			'type'        => 'select',
			'priority'    => '355', // Non static options must have a priority
			'choices'     => $wooproducts,
			'default'     => '12',
			'description' => __( 'Total number of products to show on the Shop page', 'creattica-premium' ),
		);

		$settings['wooshop_product_columns'] = array(
			'label'       => __( 'Product Columns', 'creattica-premium' ),
			'section'     => $section,
			'type'        => 'select',
			'priority'    => '355', // Non static options must have a priority
			'choices'     => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
			),
			'default'     => '3',
			'description' => __( 'Number of products to show in 1 row on the Shop page', 'creattica-premium' ),
		);

		$settings['sidebar_wooshop'] = array(
			'label'       => __( 'Sidebar Layout (Woocommerce Shop/Archives)', 'creattica-premium' ),
			'section'     => $section,
			'type'        => 'radioimage',
			'priority'    => '355', // Non static options must have a priority
			'choices'     => array(
				'wide-right'         => $imagepath . 'sidebar-wide-right.png',
				'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
				'wide-left'          => $imagepath . 'sidebar-wide-left.png',
				'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
				'full-width'         => $imagepath . 'sidebar-full.png',
				'none'               => $imagepath . 'sidebar-none.png',
			),
			'default'     => 'wide-right',
			'description' => __("Set the default sidebar width and position for WooCommerce Shop and Archives pages like product categories etc.", 'creattica-premium'),
		);

		$settings['sidebar_wooproduct'] = array(
			'label'       => __( 'Sidebar Layout (Woocommerce Single Product Page)', 'creattica-premium' ),
			'section'     => $section,
			'type'        => 'radioimage',
			'priority'    => '355', // Non static options must have a priority
			'choices'     => array(
				'wide-right'         => $imagepath . 'sidebar-wide-right.png',
				'narrow-right'       => $imagepath . 'sidebar-narrow-right.png',
				'wide-left'          => $imagepath . 'sidebar-wide-left.png',
				'narrow-left'        => $imagepath . 'sidebar-narrow-left.png',
				'full-width'         => $imagepath . 'sidebar-full.png',
				'none'               => $imagepath . 'sidebar-none.png',
			),
			'default'     => 'wide-right',
			'description' => __("Set the default sidebar width and position for WooCommerce product page", 'creattica-premium'),
		);

	endif;


	/*** Return Options Array ***/
	return apply_filters( 'hoot_theme_customizer_options', array(
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
if ( !function_exists( 'hoot_theme_add_customizer_options' ) ) :
function hoot_theme_add_customizer_options() {

	$hybridextend_customize = HybridExtend_Customize::get_instance();

	// Add Options
	$options = hoot_theme_customizer_options();
	$hybridextend_customize->add_options( array(
		'settings' => $options['settings'],
		'sections' => $options['sections'],
		'panels' => $options['panels'],
		) );

}
endif;
add_action( 'init', 'hoot_theme_add_customizer_options', 0 ); // cannot hook into 'after_setup_theme' as this hook is already being executed (this file is loaded at after_setup_theme @priority 10) (hooking into same hook from within while hook is being executed leads to undesirable effects as $GLOBALS[$wp_filter]['after_setup_theme'] has already been ksorted)
// Hence, we hook into 'init' @priority 0, so that settings array gets populated before 'widgets_init' action ( which itself is hooked to 'init' at priority 1 ) for creating widget areas ( settings array is needed for creating defaults when user value has not been stored )

/**
 * Enqueue custom scripts to customizer screen
 *
 * @since 1.0
 * @return void
 */
function hoot_theme_customizer_enqueue_scripts() {
	// Enqueue Styles
	wp_enqueue_style( 'hoot-theme-customize-styles', HYBRIDEXTEND_INCURI . 'admin/css/customize.css', array(),  HYBRIDEXTEND_VERSION );
	// Enqueue Scripts
	wp_enqueue_script( 'hoot-theme-customize-script', HYBRIDEXTEND_INCURI . 'admin/js/customize-controls.js', array( 'jquery', 'wp-color-picker', 'customize-controls', 'hybridextend-customize-script' ), HYBRIDEXTEND_VERSION, true );
}
// Load scripts at priority 12 so that Hoot Customizer Interface (11) / Custom Controls (10) have loaded their scripts
add_action( 'customize_controls_enqueue_scripts', 'hoot_theme_customizer_enqueue_scripts', 12 );

/**
 * Modify default WordPress Settings Sections and Panels
 *
 * @since 1.0
 * @param object $wp_customize
 * @return void
 */
function hoot_customizer_modify_default_options( $wp_customize ) {

	$wp_customize->get_control( 'custom_logo' )->section = 'logo';
	$wp_customize->get_control( 'custom_logo' )->priority = 145;
	$wp_customize->get_control( 'custom_logo' )->width = 240;
	$wp_customize->get_control( 'custom_logo' )->height = 120;
	// $wp_customize->get_control( 'custom_logo' )->type = 'image'; // Stored value becomes url instead of image ID (fns like the_custom_logo() dont work)
	// Defaults: [type] => cropped_image, [width] => 150, [height] => 150, [flex_width] => 1, [flex_height] => 1, [button_labels] => array(...), [label] => Logo
	$wp_customize->get_control( 'custom_logo' )->active_callback = 'hoot_callback_logo_image';

	if ( function_exists( 'get_site_icon_url' ) )
		$wp_customize->get_control( 'site_icon' )->priority = 10;

	$wp_customize->get_section( 'static_front_page' )->priority = 3;

	// $wp_customize->get_section( 'title_tagline' )->panel = 'general';
	// $wp_customize->get_section( 'title_tagline' )->priority = 1;
	// $wp_customize->get_section( 'colors' )->panel = 'styling';

	// global $wp_version;
	// if ( version_compare( $wp_version, '4.3', '>=' ) ) // 'Creating Default Object from Empty Value' error before 4.3 since 'nav_menus' panel did not exist ( we did have 'nav' section till 4.1.9 i.e. before 4.2 )
	// 	$wp_customize->get_panel( 'nav_menus' )->priority = 999;
	// This will set the priority, however will give a 'Creating Default Object from Empty Value' error first.
	// $wp_customize->get_panel( 'widgets' )->priority = 999;

}
add_action( 'customize_register', 'hoot_customizer_modify_default_options', 100 );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since 1.0
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function hoot_customizer_customize_register( $wp_customize ) {
	// $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'hoot_customizer_customize_register' );

/**
 * Callback Functions for customizer settings
 */

function hoot_callback_logo_size( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'text' || $selector == 'mixed' ) ? true : false;
}
function hoot_callback_site_title_icon( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'text' || $selector == 'custom' ) ? true : false;
}
function hoot_callback_logo_image( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'image' || $selector == 'mixed' || $selector == 'mixedcustom' ) ? true : false;
}
function hoot_callback_logo_custom( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'custom' || $selector == 'mixedcustom' ) ? true : false;
}
function hoot_callback_show_tagline( $control ) {
	$selector = $control->manager->get_setting('logo')->value();
	return ( $selector == 'text' || $selector == 'custom' || $selector == 'mixed' || $selector == 'mixedcustom' ) ? true : false;
}
function hoot_callback_leftbar_bottom_mobile_location( $control ) {
	$selector = $control->manager->get_setting('leftbar_bottom_mobile_display')->value();
	return ( !empty( $selector ) ) ? true : false;
}

/**
 * Specific Sanitization Functions for customizer settings
 * See specific settings above for more details.
 */
function hoot_custom_sanitize_textarea_allowscript( $value ) {
	global $allowedposttags;
	// Allow javascript to let users ad code for ads etc.
	$allow = array_merge( $allowedposttags, array(
		'script' => array( 'async' => true, 'charset' => true, 'defer' => true, 'src' => true, 'type' => true ),
	) );
	return wp_kses( $value , $allow );
}

/**
 * Helper function to return the theme mod value.
 * If no value has been saved, it returns $default provided by the theme.
 * If no $default provided, it checks the default value specified in the customizer settings..
 * 
 * @since 1.0
 * @access public
 * @param string $name
 * @param mixed $default
 * @return mixed
 */
function hoot_get_mod( $name, $default = NULL ) {

	if ( is_customize_preview() ) {

		// We cannot use "if ( !empty( $mod ) )" as this will become false for empty values, and hence fallback to default. isset() is not an option either as $mod is always set. Hence we calculate the default here, and supply it as second argument to get_theme_mod()
		$default = ( $default !== NULL ) ? $default : hybridextend_customize_get_default( $name );
		$mod = get_theme_mod( $name, $default );

		return apply_filters( 'hoot_get_mod_customize', $mod, $name, $default );

	} else {

		// Return Value
		$returnvalue = false;

		// Cache
		static $mods = NULL;

		// Set cache with database values
		if ( !$mods ) {
			$mods = get_theme_mods();
			$mods = apply_filters( 'hoot_get_mod', $mods );
		}

		// Return value if set
		if ( isset( $mods[$name] ) ) {
			$returnvalue = $mods[$name];
		}
		// Return default if provided
		elseif ( $default !== NULL ) {
			$returnvalue = $default;
		}
		// Return default theme option value specified in customizer settings
		else {
			$default = hybridextend_customize_get_default( $name );
			if ( !empty( $default ) )
				$returnvalue = $default;
		}

		// Filter applied as in get_theme_mod() core function
		$returnvalue = apply_filters( "theme_mod_{$name}", $returnvalue );

		if ( $returnvalue !== false ) {
			// Sanitize Value
			$returnvalue = apply_filters( 'hoot_sanitize_get_mod', $returnvalue, $name );
		}

		return $returnvalue;

	}

}

/* Transition filter for version 1.9.0 : Doesnt resolve customizer but hopefully user will visit atleast one admin screen before customizer */
add_filter( 'hoot_get_mod', 'hoot_transition_get_mods', 2 );

/**
 * Function for seamless transition for changed option/values in version 1.9.0
 * Updated 1.9.0 for frontpage sidebar option
 *
 * @since 1.9.0
 * @access public
 * @return void
 */
function hoot_transition_get_mods( $mods ) {

	if ( !isset( $mods['sidebar_fp'] ) ) {
		if ( 'page' == get_option('show_on_front' ) ) {
			if ( function_exists( 'hoot_get_meta_option' ) && hoot_get_meta_option( 'sidebar_type', get_option( 'page_on_front' ) ) == 'custom' ) {
				$mods['sidebar_fp'] = hoot_get_meta_option( 'sidebar', get_option( 'page_on_front' ) );
			} else {
				$mods['sidebar_fp'] = 'full-width';
			}
		} else {
			$mods['sidebar_fp'] = ( isset( $mods['sidebar_archives'] ) ) ? $mods['sidebar_archives'] : ( isset( $mods['sidebar'] ) ? $mods['sidebar'] : 'wide-right' );
		}
		set_theme_mod( 'sidebar_fp', $mods['sidebar_fp'] );
	}

	// var_dump(get_theme_mods());exit;
	return $mods;
}

/**
 * Sanitization function for return value of theme mod
 * jnes No chan-ni applied
 * 
 * @since 1.0
 * @access public
 * @param mixed $value
 * @param string $name
 * @return mixed
 */
function hoot_sanitize_get_mod( $value, $name ) {

	/** Get Setting array from the Customizer Class **/
	$hybridextend_customize = HybridExtend_Customize::get_instance();
	$settings = $hybridextend_customize->get_options('settings');

	/** Load Sanitization functions if not loaded already (for frontend) **/
	if ( !function_exists( 'hybridextend_sanitize_enum' ) )
		require_once( HYBRIDEXTEND_DIR . 'includes/sanitization.php' );
	/** Load Sanitization functions if not loaded already (from frontend) **/
	if ( !function_exists( 'hybridextend_customize_sanitize_text' ) )
		require_once( HYBRIDEXTEND_DIR . 'customize/sanitization.php' );

	if ( isset( $settings[ $name ] ) ) {

		/** Sanitize values **/
		if ( isset( $settings[ $name ]['type'] ) && !empty( $settings[ $name ]['sanitize_callback'] ) && function_exists( $settings[ $name ]['sanitize_callback'] ) ) {

			$fn_name = $settings[ $name ]['sanitize_callback'];
			return $fn_name( $value );

		} elseif ( isset( $settings[ $name ]['type'] ) ) {
			switch ( $settings[ $name ]['type'] ) {

				// Text Field
				case 'text':
					$value = sanitize_text_field( $value ); // Alternately, can also use "hybridextend_customize_sanitize_text" to use wp_kses() instead
					break;

				// Textarea Field
				case 'textarea':
					$value = hybridextend_sanitize_textarea( $value );
					break;

				// Select, Radio, Image Radio
				case 'select':
				case 'radio':
				case 'radioimage':
					$value = hybridextend_sanitize_enum( $value, $settings[ $name ]['choices'] );
					break;

				// Image / Upload Field
				case 'image':
				case 'upload':
					$value = esc_url( $value );
					break;

				// URL Field
				case 'url':
					$value = esc_url( $value );
					break;

				// Range Field
				case 'range':
					$value = hybridextend_customize_sanitize_range( $value );
					break;

				// Dropdown Pages Field
				case 'dropdown-pages':
					$value = absint( $value );
					break;

				// Color Field
				case 'color':
					$value = sanitize_hex_color( $value );
					break;

				// Checkbox Field
				case 'checkbox':
					$value = hybridextend_sanitize_checkbox( $value );
					break;

				// MultiCheckbox Field
				case 'bettercheckbox':
					if ( !empty( $settings[ $name ]['choices'] ) && is_array( $settings[ $name ]['choices'] ) )
						$value = hybridextend_customize_sanitize_multicheckbox( $value, $name );
					else
						$value = hybridextend_sanitize_checkbox( $value );
					break;

				// Icon Field
				case 'icon':
					$value = hybridextend_sanitize_icon( $value, $name );
					break;

				// Sortlist Field
				case 'sortlist':
					$value = hybridextend_customize_sanitize_sortlist( $value, $name );
					break;

			} // endswitch
		} // endif

	} // endif

	return $value;
}