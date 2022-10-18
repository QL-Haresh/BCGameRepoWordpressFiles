<?php
/**
 * Vcards Widget
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
* Class Hoot_Vcards_Widget
*/
class Hoot_Vcards_Widget extends HybridExtend_WP_Widget {

	function __construct() {

		$settings['id'] = 'hoot-vcards-widget';
		$settings['name'] = __( 'Hoot > Vcards', 'creattica-premium' );
		$settings['widget_options'] = array(
			'description'	=> __('Display ID Cards for Testimonials, Teams etc.', 'creattica-premium'),
			// 'classname'		=> 'hoot-vcards-widget', // CSS class applied to frontend widget container via 'before_widget' arg
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
				'name'		=> __( 'Vcards', 'creattica-premium' ),
				'id'		=> 'vcards',
				'type'		=> 'group',
				'options'	=> array(
					'item_name'	=> __( 'Vcard', 'creattica-premium' ),
				),
				'fields'	=> array(
					array(
						'name'		=> __('Image', 'creattica-premium'),
						'id'		=> 'image',
						'type'		=> 'image',
					),
					array(
						'name'		=> __('Text', 'creattica-premium'),
						'id'		=> 'content',
						'type'		=> 'textarea',
					),
					array(
						'name'		=> '<span style="font-size:12px;"><em>' . __('Use &lt;h4&gt; tag for headlines. Example', 'creattica-premium') . '</em></span>',
						'id'		=> 'content_desc',
						'type'		=> '<br /><code style="font-size: 11px;">' . __( '&lt;h4&gt;John Doe&lt;/h4&gt;<br>&lt;cite&gt;Designation Subtext&lt;/cite&gt;<br>Some description about John..<br>&lt;a href="http://url.com"&gt;Website&lt;/a&gt;', 'creattica-premium' ) . '</code>',
					),
					array(
						'name'		=> __( 'Social Icon 1', 'creattica-premium' ),
						'id'		=> 'icon1',
						'type'		=> 'select',
						'options'	=> hybridextend_enum_social_profiles(),
					),
					array(
						'name'		=> __( 'URL 1', 'creattica-premium' ),
						'id'		=> 'url1',
						'type'		=> 'text',
						'sanitize'	=> 'vcard_links_sanitize_url',
					),
					array(
						'name'		=> __( 'Social Icon 2', 'creattica-premium' ),
						'id'		=> 'icon2',
						'type'		=> 'select',
						'options'	=> hybridextend_enum_social_profiles(),
					),
					array(
						'name'		=> __( 'URL 2', 'creattica-premium' ),
						'id'		=> 'url2',
						'type'		=> 'text',
						'sanitize'	=> 'vcard_links_sanitize_url',
					),
					array(
						'name'		=> __( 'Social Icon 3', 'creattica-premium' ),
						'id'		=> 'icon3',
						'type'		=> 'select',
						'options'	=> hybridextend_enum_social_profiles(),
					),
					array(
						'name'		=> __( 'URL 3', 'creattica-premium' ),
						'id'		=> 'url3',
						'type'		=> 'text',
						'sanitize'	=> 'vcard_links_sanitize_url',
					),
					array(
						'name'		=> __( 'Social Icon 4', 'creattica-premium' ),
						'id'		=> 'icon4',
						'type'		=> 'select',
						'options'	=> hybridextend_enum_social_profiles(),
					),
					array(
						'name'		=> __( 'URL 4', 'creattica-premium' ),
						'id'		=> 'url4',
						'type'		=> 'text',
						'sanitize'	=> 'vcard_links_sanitize_url',
					),
					array(
						'name'		=> __( 'Social Icon 5', 'creattica-premium' ),
						'id'		=> 'icon5',
						'type'		=> 'select',
						'options'	=> hybridextend_enum_social_profiles(),
					),
					array(
						'name'		=> __( 'URL 5', 'creattica-premium' ),
						'id'		=> 'url5',
						'type'		=> 'text',
						'sanitize'	=> 'vcard_links_sanitize_url',
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

		$settings = apply_filters( 'hoot_vcards_widget_settings', $settings );

		parent::__construct( $settings['id'], $settings['name'], $settings['widget_options'], $settings['control_options'], $settings['form_options'] );

	}

	/**
	 * Echo the widget content
	 */
	function display_widget( $instance, $before_title = '', $title='', $after_title = '' ) {
		extract( $instance, EXTR_SKIP );
		include( hybridextend_locate_widget( 'vcards' ) ); // Loads the widget/vcards or template-parts/widget-vcards.php template.
	}

}

/**
 * Register Widget
 */
function hoot_vcards_widget_register(){
	register_widget('Hoot_Vcards_Widget');
}
add_action('widgets_init', 'hoot_vcards_widget_register');

/**
 * Custom Sanitization Function
 */
function hoot_vcards_sanitize_url( $value, $name, $instance ){
	if ( $name == 'vcard_links_sanitize_url' ) {

		$key = array_search( $value, $instance );
		if ( !$key ) return false;
		$key = substr( $key, -1 );

		if ( !empty( $instance["icon{$key}"] ) && $instance["icon{$key}"] == 'fa-skype' )
			$new = sanitize_user( $value, true );
		elseif ( !empty( $instance["icon{$key}"] ) && $instance["icon{$key}"] == 'fa-envelope' )
			$new = is_email( $value );
		else
			$new = esc_url_raw( $value );

		return $new;
	}
	return $value;
}
add_filter('widget_admin_sanitize_field', 'hoot_vcards_sanitize_url', 10, 3);