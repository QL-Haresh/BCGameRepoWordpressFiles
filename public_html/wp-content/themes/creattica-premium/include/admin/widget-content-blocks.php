<?php
/**
 * Content Blocks Widget
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
* Class Hoot_Content_Blocks_Widget
*/
class Hoot_Content_Blocks_Widget extends HybridExtend_WP_Widget {

	function __construct() {

		$settings['id'] = 'hoot-content-blocks-widget';
		$settings['name'] = __( 'Hoot > Content Blocks (Pages)', 'creattica-premium' );
		$settings['widget_options'] = array(
			'description'	=> __('Display Styled Content Blocks.', 'creattica-premium'),
			// 'classname'		=> 'hoot-content-blocks-widget', // CSS class applied to frontend widget container via 'before_widget' arg
		);
		$settings['control_options'] = array();
		$settings['form_options'] = array(
			//'name' => can be empty or false to hide the name
			array(
				'name'		=> __( "Title (optional)", 'creattica-premium' ),
				'id'		=> 'title',
				'type'		=> 'text',
			),
			array(
				'name'		=> __( 'Blocks Style', 'creattica-premium' ),
				'id'		=> 'style',
				'type'		=> 'images',
				'std'		=> 'style1',
				'options'	=> array(
					'style1'	=> HYBRIDEXTEND_INCURI . 'admin/images/content-block-style-1.png',
					'style2'	=> HYBRIDEXTEND_INCURI . 'admin/images/content-block-style-2.png',
					'style3'	=> HYBRIDEXTEND_INCURI . 'admin/images/content-block-style-3.png',
					'style4'	=> HYBRIDEXTEND_INCURI . 'admin/images/content-block-style-4.png',
				),
			),
			array(
				'name'		=> __( 'No. Of Columns', 'creattica-premium' ),
				'id'		=> 'columns',
				'type'		=> 'select',
				'std'		=> '4',
				'options'	=> array(
					'1'	=> __( '1', 'creattica-premium' ),
					'2'	=> __( '2', 'creattica-premium' ),
					'3'	=> __( '3', 'creattica-premium' ),
					'4'	=> __( '4', 'creattica-premium' ),
					'5'	=> __( '5', 'creattica-premium' ),
				),
			),
			array(
				'name'		=> __( 'Border', 'creattica-premium' ),
				'desc'		=> __( 'Top and bottom borders.', 'creattica-premium' ),
				'id'		=> 'border',
				'type'		=> 'select',
				'std'		=> 'none none',
				'options'	=> array(
					'line line'		=> __( 'Top - Line || Bottom - Line', 'creattica-premium' ),
					'line shadow'	=> __( 'Top - Line || Bottom - StrongLine', 'creattica-premium' ),
					'line none'		=> __( 'Top - Line || Bottom - None', 'creattica-premium' ),
					'shadow line'	=> __( 'Top - StrongLine || Bottom - Line', 'creattica-premium' ),
					'shadow shadow'	=> __( 'Top - StrongLine || Bottom - StrongLine', 'creattica-premium' ),
					'shadow none'	=> __( 'Top - StrongLine || Bottom - None', 'creattica-premium' ),
					'none line'		=> __( 'Top - None || Bottom - Line', 'creattica-premium' ),
					'none shadow'	=> __( 'Top - None || Bottom - StrongLine', 'creattica-premium' ),
					'none none'		=> __( 'Top - None || Bottom - None', 'creattica-premium' ),
				),
			),
			array(
				'name'		=> __( 'Content Boxes', 'creattica-premium' ),
				'id'		=> 'boxes',
				'type'		=> 'group',
				'options'	=> array(
					'item_name'	=> __( 'Content Box', 'creattica-premium' ),
				),
				'fields'	=> array(
					array(
						'name'		=> __( 'Title/Content/Image', 'creattica-premium' ),
						'desc'		=> __( 'Page Title, Content and Featured Image will be used.', 'creattica-premium' ),
						'id'		=> 'page',
						'type'		=> 'select',
						'options'	=> HybridExtend_WP_Widget::get_wp_list('page'),
					),
					array(
						'name'		=> __( 'Content', 'creattica-premium' ),
						'id'		=> 'excerpt',
						'type'		=> 'select',
						'std'		=> 'excerpt',
						'options'	=> array(
							'excerpt'	=> __( 'Display Excerpt', 'creattica-premium' ),
							'content'	=> __( 'Display Full Content', 'creattica-premium' ),
							'none'		=> __( 'None', 'creattica-premium' ),
						),
					),
					array(
						'name'		=> __( 'Custom Excerpt Length', 'creattica-premium' ),
						'desc'		=> __( 'Select \'Display Excerpt\' in option above. Leave empty for default excerpt length.', 'creattica-premium' ),
						'id'		=> 'excerptlength',
						'type'		=> 'text',
						'settings'	=> array( 'size' => 3, ),
						'sanitize'	=> 'absint',
					),
					array(
						'name'		=> __('Link Text (optional)', 'creattica-premium'),
						'id'		=> 'link',
						'type'		=> 'text'),
					array(
						'name'		=> __('Link URL', 'creattica-premium'),
						'id'		=> 'url',
						'type'		=> 'text',
						'sanitize'	=> 'url'),
					array(
						'name'		=> __('Icon', 'creattica-premium'),
						'desc'		=> __( 'Use an icon instead of featured image of the page selected above.', 'creattica-premium' ),
						'id'		=> 'icon',
						'type'		=> 'icon',
					),
					array(
						'name'		=> __( 'Icon Style', 'creattica-premium' ),
						'id'		=> 'icon_style',
						'type'		=> 'select',
						'std'		=> 'circle',
						'options'	=> array(
							'none'		=> __( 'None', 'creattica-premium' ),
							'circle'	=> __( 'Circle', 'creattica-premium' ),
							'square'	=> __( 'Square', 'creattica-premium' ),
						),
					),
				),
			),
			array(
				'name'		=> __( 'Widget CSS', 'creattica-premium' ),
				'id'		=> 'customcss',
				'type'		=> 'collapse',
				'fields'	=> array(
					array(
						'name'		=> __( 'Custom CSS Class', 'creattica-premium' ),
						'desc'		=> __( 'Give this widget a custom css classname', 'creattica-premium' ),
						'id'		=> 'class',
						'type'		=> 'text',
					),
					array(
						'name'		=> __( 'Margin Top', 'creattica-premium' ),
						'desc'		=> __( '(in pixels) Leave empty to load default margins', 'creattica-premium' ),
						'id'		=> 'mt',
						'type'		=> 'text',
						'settings'	=> array( 'size' => 3 ),
						'sanitize'	=> 'integer',
					),
					array(
						'name'		=> __( 'Margin Bottom', 'creattica-premium' ),
						'desc'		=> __( '(in pixels) Leave empty to load default margins', 'creattica-premium' ),
						'id'		=> 'mb',
						'type'		=> 'text',
						'settings'	=> array( 'size' => 3 ),
						'sanitize'	=> 'integer',
					),
					array(
						'name'		=> __( 'Widget ID', 'creattica-premium' ),
						'id'		=> 'widgetid',
						'type'		=> '<span class="widgetid" data-baseid="' . $settings['id'] . '">' . __( 'Save this widget to view its ID', 'creattica-premium' ) . '</span>',
					),
				),
			),
		);

		$settings = apply_filters( 'hoot_content_blocks_widget_settings', $settings );

		parent::__construct( $settings['id'], $settings['name'], $settings['widget_options'], $settings['control_options'], $settings['form_options'] );

	}

	/**
	 * Echo the widget content
	 */
	function display_widget( $instance, $before_title = '', $title='', $after_title = '' ) {
		extract( $instance, EXTR_SKIP );
		include( hybridextend_locate_widget( 'content-blocks' ) ); // Loads the widget/content-blocks or template-parts/widget-content-blocks.php template.
	}

}

/**
 * Register Widget
 */
function hoot_content_blocks_widget_register(){
	register_widget('Hoot_Content_Blocks_Widget');
}
add_action('widgets_init', 'hoot_content_blocks_widget_register');