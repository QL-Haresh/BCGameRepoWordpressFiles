<?php
/**
 * List of core shortcodes and their settings.
 * * Themes can modify this array (add/unset) using the 'hoot_shortcodes' filter available in
 *   'hoot/extensions/shortcodes/init.php'. The preferred location for this is in the
 *   'hoot-theme/admin/shortcodes.php' file which is included automatically if available.
 * * Themes can override display templates of core shortcodes by adding {key}.php file in the
 *   'hoot-theme/shortcodes' directory of the theme folder
 * 
 * Hence, for a theme to add a new shortcode:
 * 1. In the 'hoot-theme/admin/shortcodes.php' hook into 'hoot_shortcodes' filter to add the new
 *    shortcode key and its settings to this array. This will add the shortcode to the Shortcode
 *    generator.
 * 2. Add the display template in the 'hoot-theme/shortcodes' directory . Name this template file
 *    as {key}.php where {key} is the name of the shortcode.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Array with $key => $settings values
 * Keys should be unique as they will be used as shortcode names.
 *   Example: [unique_key]Lorem Ipsum...[/unique_key]
 * Settings are used in backend to create shortcode generator. Options arrays are for options used
 * in Hoot Options Framework
 *
 * Any field with 'id' set to 'content' will be used for the Shortcode content. This will make the
 * shortcode a closing shortcode (i.e. it will have a [/name] tag at the end). Ofcourse, for each
 * shortcode, there should only be 1 content id.
 * Rest of the fields will be used as attributes (unless 'hide_as_attribute' is set to (bool) true
 * for the field)
 *
 * Attributes do not support groups, but main content can be a group.
 */

return array(

	'title_content' => array(
		'title' => __( 'Content', 'hybrid-core' ),
		'type' => 'title',
	),

	'hoot_list' => array(
		'title' => __( 'Icon Lists', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Choose Icon', 'hybrid-core' ),
				'id' => 'icon',
				'type' => 'icon' ),
			array(
				'name' => __( 'List Items', 'hybrid-core' ),
				'id' => 'hoot_li',
				'type' => 'group',
				'settings' => array(
					//'title' => __( 'List Item', 'hybrid-core' ),
					'add_button' => __( 'Add Another List Item', 'hybrid-core' ),
					'remove_button' => __( 'Remove List Item', 'hybrid-core' ),
					'repeatable' => true,    // Default false
					'sortable' => false,     // Default false
					'toggleview' => false, ), // Default true
				'fields' => array(
					array(
						//'name' => __( 'List Item', 'hybrid-core' ),
						'id' => 'content',
						'type' => 'text' ),
					), ),
		),
	),

	'hoot_li' => array(
		'type' => 'internal',
	),

	'hoot_box' => array(
		'title' => __( 'Notice Box', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Box Text', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea',
				),
			array(
				'name' => __( 'Preset Type', 'hybrid-core' ),
				'id' => 'type',
				'type' => 'select',
				'options' => array(
					'' => '',
					'success' => __( 'Success', 'hybrid-core' ),
					'warning' => __( 'Warning', 'hybrid-core' ),
					'error' => __( 'Error', 'hybrid-core' ),
					'info' => __( 'Info', 'hybrid-core' ),
					'note' => __( 'Note', 'hybrid-core' ),
					'flag' => __( 'Flag', 'hybrid-core' ),
					'pushpin' => __( 'Pushpin', 'hybrid-core' ),
					'setting' => __( 'Setting', 'hybrid-core' ), ) ),
			array(
				'name' => __( 'Color', 'hybrid-core' ),
				'desc' => __( 'You can leave this empty if you are using a Preset Type above.', 'hybrid-core' ),
				'id' => 'color',
				'type' => 'select',
				'options' => hoot_shortcode_styles() ),
			array(
				'name' => __( 'Choose Icon', 'hybrid-core' ),
				'desc' => __( 'You can leave this empty if you are using a Preset Type above.', 'hybrid-core' ),
				'id' => 'icon',
				'type' => 'icon' ),
			array(
				'name' => __( 'Background Color', 'hybrid-core' ),
				'desc' => __( 'You can leave this empty if you are using a Preset Type or Color above.', 'hybrid-core' ),
				'id' => 'background',
				'type' => 'color' ),
			array(
				'name' => __( 'Text Color', 'hybrid-core' ),
				'desc' => __( 'You can leave this empty if you are using a Preset Type or Color above.', 'hybrid-core' ),
				'id' => 'text',
				'type' => 'color' ),
		),
	),

	'hoot_toggle' => array(
		'title' => __( 'Toggle Box', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Title', 'hybrid-core' ),
				'id' => 'title',
				'type' => 'text' ),
			array(
				'name' => __( 'Content', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea' ),
			array(
				'name' => __( 'Initial State', 'hybrid-core' ),
				'desc' => __("Check this to set Toggle box as 'open'. By default, toggle boxes are closed on page load.", 'hybrid-core' ),
				'id' => 'open',
				'type' => 'checkbox', ),
		),
	),

	'hoot_tabset' => array(
		'title' => __( 'Tab Set', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Tabs', 'hybrid-core' ),
				'id' => 'hoot_tab',
				'type' => 'group',
				'settings' => array(
					'title' => __( 'Tab', 'hybrid-core' ),
					'add_button' => __( 'Add Another Tab', 'hybrid-core' ),
					'remove_button' => __( 'Remove Tab', 'hybrid-core' ),
					'repeatable' => true,    // Default false
					'sortable' => true,     // Default false
					'toggleview' => true, ), // Default true
				'fields' => array(
					array(
						'name' => __( 'Tab Title', 'hybrid-core' ),
						'id' => 'title',
						'type' => 'text' ),
					array(
						'name' => __( 'Tab Content', 'hybrid-core' ),
						'id' => 'content',
						'type' => 'textarea',
						'settings' => array( 'rows' => 3 ), ),
					), ),
		),
	),

	'hoot_tab' => array(
		'type' => 'internal',
	),

	'hoot_code' => array(
		'title' => __( 'Code Block', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Display Code', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea', ),
		),
	),

	'title_columns' => array(
		'title' => __( 'Columns', 'hybrid-core' ),
		'type' => 'title',
	),

	'hoot_one_half' => array(
		'title' => __( 'Column - One Half / Two Fourth', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Column Content', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea', ),
			array(
				'name' => __( 'Last Column?', 'hybrid-core' ),
				'desc' => __( 'Is this the last column in its row?', 'hybrid-core' ),
				'id' => 'last',
				'type' => 'checkbox', ),
		),
	),

	'hoot_one_third' => array(
		'title' => __( 'Column - One Third', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Column Content', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea', ),
			array(
				'name' => __( 'Last Column?', 'hybrid-core' ),
				'desc' => __( 'Is this the last column in its row?', 'hybrid-core' ),
				'id' => 'last',
				'type' => 'checkbox', ),
		),
	),

	'hoot_two_third' => array(
		'title' => __( 'Column - Two Third', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Column Content', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea', ),
			array(
				'name' => __( 'Last Column?', 'hybrid-core' ),
				'desc' => __( 'Is this the last column in its row?', 'hybrid-core' ),
				'id' => 'last',
				'type' => 'checkbox', ),
		),
	),

	'hoot_one_fourth' => array(
		'title' => __( 'Column - One Fourth', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Column Content', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea', ),
			array(
				'name' => __( 'Last Column?', 'hybrid-core' ),
				'desc' => __( 'Is this the last column in its row?', 'hybrid-core' ),
				'id' => 'last',
				'type' => 'checkbox', ),
		),
	),

	'hoot_three_fourth' => array(
		'title' => __( 'Column - Three Fourth', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Column Content', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea', ),
			array(
				'name' => __( 'Last Column?', 'hybrid-core' ),
				'desc' => __( 'Is this the last column in its row?', 'hybrid-core' ),
				'id' => 'last',
				'type' => 'checkbox', ),
		),
	),

	'hoot_one_fifth' => array(
		'title' => __( 'Column - One Fifth', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Column Content', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea', ),
			array(
				'name' => __( 'Last Column?', 'hybrid-core' ),
				'desc' => __( 'Is this the last column in its row?', 'hybrid-core' ),
				'id' => 'last',
				'type' => 'checkbox', ),
		),
	),

	'hoot_two_fifth' => array(
		'title' => __( 'Column - Two Fifth', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Column Content', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea', ),
			array(
				'name' => __( 'Last Column?', 'hybrid-core' ),
				'desc' => __( 'Is this the last column in its row?', 'hybrid-core' ),
				'id' => 'last',
				'type' => 'checkbox', ),
		),
	),

	'hoot_three_fifth' => array(
		'title' => __( 'Column - Three Fifth', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Column Content', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea', ),
			array(
				'name' => __( 'Last Column?', 'hybrid-core' ),
				'desc' => __( 'Is this the last column in its row?', 'hybrid-core' ),
				'id' => 'last',
				'type' => 'checkbox', ),
		),
	),

	'hoot_four_fifth' => array(
		'title' => __( 'Column - Four Fifth', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Column Content', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea', ),
			array(
				'name' => __( 'Last Column?', 'hybrid-core' ),
				'desc' => __( 'Is this the last column in its row?', 'hybrid-core' ),
				'id' => 'last',
				'type' => 'checkbox', ),
		),
	),

	'title_display' => array(
		'title' => __( 'Display Elements', 'hybrid-core' ),
		'type' => 'title',
	),

	'hoot_dropcap' => array(
		'title' => __( 'Dropcap', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Dropcap Text', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'text',
				),
		),
	),

	'hoot_highlight' => array(
		'title' => __( 'Highlight Text', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Highlight Text', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'text',
				),
			array(
				'name' => __( 'Color', 'hybrid-core' ),
				'desc' => __( 'You can leave this empty if you are using a Preset Type above.', 'hybrid-core' ),
				'id' => 'color',
				'type' => 'select',
				'options' => hoot_shortcode_styles() ),
		),
	),

	'hoot_button' => array(
		'title' => __( 'Buttons', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Button Text', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'text',
				),
			array(
				'name' => __( 'URL (link)', 'hybrid-core' ),
				'id' => 'url',
				'std' => 'http://',
				'type' => 'text',
				),
			array(
				'name' => __( 'Open Link In', 'hybrid-core' ),
				'id' => 'target',
				'type' => 'select',
				'options' => array(
					'self' => __( 'Same Window', 'hybrid-core' ),
					'blank' => __( 'New Blank Window', 'hybrid-core' ), ) ),
			array(
				'name' => __( 'Size', 'hybrid-core' ),
				'id' => 'size',
				'type' => 'select',
				'options' => array(
					'small' => __( 'Small', 'hybrid-core' ),
					'medium' => __( 'Medium', 'hybrid-core' ),
					'large' => __( 'Large', 'hybrid-core') ) ),
			array(
				'name' => __( 'Align', 'hybrid-core' ),
				'id' => 'align',
				'type' => 'select',
				'options' => array(
					'' => '',
					'left' => __( 'Left', 'hybrid-core' ),
					'right' => __( 'Right', 'hybrid-core' ),
					'center' => __( 'Center', 'hybrid-core' ), ) ),
			array(
				'name' => __( 'Color', 'hybrid-core' ),
				'desc' => __( 'Select a predefined color set, or set your custom colors below.', 'hybrid-core' ),
				'id' => 'color',
				'type' => 'select',
				'options' => hoot_shortcode_styles() ),
			array(
				'name' => __( 'Background Color', 'hybrid-core' ),
				'desc' => __( 'You can leave this empty if you are using a predefined color set above.', 'hybrid-core' ),
				'id' => 'background',
				'type' => 'color' ),
			array(
				'name' => __( 'Text Color', 'hybrid-core' ),
				'desc' => __( 'You can leave this empty if you are using a predefined color set above.', 'hybrid-core' ),
				'id' => 'text',
				'type' => 'color' ),
		),
	),

	'hoot_icon' => array(
		'title' => __( 'Icon', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Choose Icon', 'hybrid-core' ),
				'id' => 'icon',
				'type' => 'icon' ),
			array(
				'name' => __( 'Color', 'hybrid-core' ),
				'desc' => __( '(Optional)', 'hybrid-core' ),
				'id' => 'color',
				'type' => 'color' ),
			array(
				'name' => __( 'Size', 'hybrid-core' ),
				'id' => 'size',
				'type' => 'select',
				'std' => 14,
				'options' => hoot_sc_range( 9, 100, '', ' ' . __( 'px', 'hybrid-core') ) ),
		),
	),

	'hoot_social_profile' => array(
		'title' => __( 'Social Profile', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Icon Size', 'hybrid-core' ),
				'id' => 'size',
				'type' => 'select',
				'std' => 'medium',
				'options' => array(
					'small' => __( 'Small', 'hybrid-core' ),
					'medium' => __( 'Medium', 'hybrid-core' ),
					'large' => __( 'Large', 'hybrid-core' ),
					'huge' => __( 'Huge', 'hybrid-core' ),
					) ),
			array(
				'name' => __( 'Icon', 'hybrid-core' ),
				'id' => 'icon',
				'type' => 'select',
				'options' => hybridextend_enum_social_profiles( false ),
				),
			array(
				'name' => __( 'URL (enter email address for Email)', 'hybrid-core' ),
				'id' => 'url',
				'std' => 'http://',
				'type' => 'text',
				),
			array(
				'name' => __( 'Open Link In', 'hybrid-core' ),
				'id' => 'target',
				'type' => 'select',
				'options' => array(
					'self' => __( 'Same Window', 'hybrid-core' ),
					'blank' => __( 'New Blank Window', 'hybrid-core' ), ) ),
		),
	),

	'hoot_divider' => array(
		'title' => __( 'Divider', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Color', 'hybrid-core' ),
				'desc' => __( 'Leave empty for default colors.', 'hybrid-core' ),
				'id' => 'color',
				'type' => 'select',
				'options' => hoot_shortcode_styles() ),
			array(
				'name' => __( 'Use Bright Color Scheme', 'hybrid-core' ),
				'desc' => __("If you have selected a 'color' above, you can use the bright color scheme instead of the default.", 'hybrid-core' ),
				'id' => 'bright',
				'type' => 'select',
				'options' => array(
					'' => '',
					'yes' => __( 'Bright', 'hybrid-core' ),
					) ),
			array(
				'name' => __("Show 'Goto Top' Link", 'hybrid-core' ),
				'id' => 'top',
				'type' => 'checkbox', ),
		),
	),

	'hoot_htmltag' => array(
		'title' => __( 'Div / Span', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'HTML Tags', 'hybrid-core' ),
				'desc' => __( 'This shortcode is often used by developer users for special cases. Feel free to ignore it if you dont know what it does.', 'hybrid-core' ),
				'type' => 'info',
				),
			array(
				'name' => __( 'HTML Tag', 'hybrid-core' ),
				'id' => 'tag',
				'type' => 'select',
				'options' => array(
					'div' => __( 'Div', 'hybrid-core' ),
					'span' => __( 'Span', 'hybrid-core' ), ) ),
			array(
				'name' => __( 'Classes', 'hybrid-core' ),
				'id' => 'class',
				'type' => 'text',
				),
			array(
				'name' => __( 'Styles', 'hybrid-core' ),
				'id' => 'style',
				'desc' => __( 'CSS Rules to apply to the div/span', 'hybrid-core' ),
				'type' => 'textarea',
				'settings' => array( 'rows' => 2, 'code' => true ),
				),
			array(
				'name' => __( 'Content', 'hybrid-core' ),
				'id' => 'content',
				'type' => 'textarea' ),
		),
	),

	'hoot_clear' => array(
		'title' => __( 'Clear Floats', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => '',
				'desc' => __( 'This shortcode does not have any options.', 'hybrid-core' ),
				'type' => 'info',
				),
		),
	),

	'title_media' => array(
		'title' => __( 'Media', 'hybrid-core' ),
		'type' => 'title',
	),

	'hoot_slider' => array(
		'title' => __( 'Slider / Carousel', 'hybrid-core' ),
		'type' => 'shortcode',
		'options' => array(
			array(
				'name' => __( 'Select Slider / Carousel', 'hybrid-core' ),
				'desc' => sprintf( __( 'You can Create New Sliders from the %1$sAdd New Slider%2$s screen.', 'hybrid-core' ), '<a href="' . esc_url( admin_url('post-new.php?post_type=hoot_slider') ) . '" target="_blank">', '</a>' ),
				'id' => 'id',
				'type' => 'select',
				'options' => HybridExtend_Options_Helper::cpt('hoot_slider', false ), ),
		),
	),

);