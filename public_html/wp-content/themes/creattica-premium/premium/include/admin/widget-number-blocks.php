<?php
/**
 * Number Blocks Widget
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
* Class Hoot_Number_Blocks_Widget
*/
class Hoot_Number_Blocks_Widget extends HybridExtend_WP_Widget {

	function __construct() {

		$settings['id'] = 'hoot-number-blocks-widget';
		$settings['name'] = __( 'Hoot > Number Blocks', 'creattica-premium' );
		$settings['widget_options'] = array(
			'description'	=> __('Display Styled Number Blocks', 'creattica-premium'),
			// 'classname'		=> 'hoot-number-blocks-widget', // CSS class applied to frontend widget container via 'before_widget' arg
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
				'name'		=> __( 'Number Boxes', 'creattica-premium' ),
				'id'		=> 'boxes',
				'type'		=> 'group',
				'options'	=> array(
					'item_name'	=> __( 'Number Box', 'creattica-premium' ),
				),
				'fields'	=> array(
					array(
						'name'		=> __( 'Circle percentage (Required)', 'creattica-premium' ),
						'desc'		=> __( 'A number between 0-100 used to calculate the circle length around the number', 'creattica-premium' ),
						'id'		=> 'percent',
						'type'		=> 'text',
						'std'		=> '75',
						'sanitize'	=> 'integer',
					),
					array(
						'name'		=> __( 'Display Number (Optional)', 'creattica-premium' ),
						'desc'		=> __( 'Leave empty to use above percentage (a % sign will be automatically added)', 'creattica-premium' ),
						'id'		=> 'number',
						'type'		=> 'text',
						// 'std'		=> '75', // Having a default value creates a bug when user intentionally leaves the field blank
						'sanitize'	=> 'integer',
					),
					array(
						'name'		=> __( 'Color', 'creattica-premium' ),
						'id'		=> 'color',
						'type'		=> 'color',
						'std'		=> '#e7ac44',
					),
					array(
						'name'		=> __( 'Text', 'creattica-premium' ),
						'id'		=> 'content',
						'type'		=> 'textarea',
					),
					array(
						'name'		=> '<span style="font-size:12px;"><em>' . __('Use &lt;h4&gt; tag for headlines. Example', 'creattica-premium') . '</em></span>',
						'id'		=> 'content_desc',
						'type'		=> '<br><code style="font-size: 11px;">' . __( '&lt;h4&gt;Skill/Feature Title&lt;/h4&gt;<br>Some description about this feature..<br>&lt;a href="http://example.com"&gt;Link Text&lt;/a&gt;', 'creattica-premium' ) . '</code>',
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

		$settings = apply_filters( 'hoot_number_blocks_widget_settings', $settings );

		parent::__construct( $settings['id'], $settings['name'], $settings['widget_options'], $settings['control_options'], $settings['form_options'] );

	}

	/**
	 * Echo the widget content
	 */
	function display_widget( $instance, $before_title = '', $title='', $after_title = '' ) {
		extract( $instance, EXTR_SKIP );
		include( hybridextend_locate_widget( 'number-blocks' ) ); // Loads the widget/number-blocks or template-parts/widget-number-blocks.php template.
	}

}

/**
 * Register Widget
 */
function hoot_number_blocks_widget_register(){
	register_widget('Hoot_Number_Blocks_Widget');
}
add_action('widgets_init', 'hoot_number_blocks_widget_register');