<?php
/**
 * Blog Widget
 *
 * @package    Hoot
 * @subpackage Creattica
 */

/**
* Class Hoot_Blog_Widget
*/
class Hoot_Blog_Widget extends HybridExtend_WP_Widget {

	function __construct() {

		$settings['id'] = 'hoot-blog-widget';
		$settings['name'] = __( 'Hoot > Blog Posts', 'creattica-premium' );
		$settings['widget_options'] = array(
			'description'	=> __('Display Blog (typically used in Frontpage Widget Areas to display Blog Posts)', 'creattica-premium'),
			//'help'			=> __('The sidebar layout will be same as you set it in Customizer &gt; Setup &amp; Layout &gt; Sidebar Layout (for Blog)', 'creattica-premium'),
			// 'classname'		=> 'hoot-blog-widget', // CSS class applied to frontend widget container via 'before_widget' arg
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
				'name'		=> __( 'Category', 'creattica-premium' ),
				'desc'		=> __( 'Leave empty to display posts from all categories.', 'creattica-premium' ),
				'id'		=> 'category',
				'type'		=> 'select',
				'options'	=> array( '0' => '' ) + HybridExtend_WP_Widget::get_tax_list('category') ,
			),
			// array(
			// 	'name'		=> __( 'Display Sidebar', 'creattica-premium' ),
			// 	'desc'		=> __('Sidebar layout can be set in Customizer &gt; Setup &amp; Layout &gt; Sidebar Layout (for Blog)', 'creattica-premium'),
			// 	'id'		=> 'sidebar',
			// 	'type'		=> 'checkbox',
			// ),
			// Deprecated for proper pagination, now undeprecated
			array(
				'name'		=> __( 'Number of Posts to show', 'creattica-premium' ),
				'desc'		=> __( 'Default: 3', 'creattica-premium' ),
				'id'		=> 'count',
				'type'		=> 'text',
				'sanitize'	=> 'absint',
			),
			array(
				'name'		=> __( 'Custom Content After Widget', 'creattica-premium' ),
				'id'		=> 'post_content',
				'type'		=> 'textarea',
				'settings'	=> array( 'rows' => 3 ),
			),
		);

		$settings = apply_filters( 'hoot_blog_widget_settings', $settings );

		parent::__construct( $settings['id'], $settings['name'], $settings['widget_options'], $settings['control_options'], $settings['form_options'] );

	}

	/**
	 * Echo the widget content
	 */
	function display_widget( $instance, $before_title = '', $title='', $after_title = '' ) {
		extract( $instance, EXTR_SKIP );
		include( hybridextend_locate_widget( 'blog' ) ); // Loads the widget/blog or template-parts/widget-blog.php template.
	}

}

/**
 * Register Widget
 */
function hoot_blog_widget_register(){
	register_widget('Hoot_Blog_Widget');
}
add_action('widgets_init', 'hoot_blog_widget_register');