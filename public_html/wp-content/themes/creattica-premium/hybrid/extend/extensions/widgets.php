<?php
/**
 * Functions for registering and setting theme widgets. This file loads an abstract class to help
 * build widgets, and loads individual widget classes for building widgets into the backend and
 * loading their template for displaying in frontend
 *
 * 'init' hook is too late to load widgets since 'widgets_init' is used to initialize widgets.
 * Since this file is hooked at 'after_setup_theme' (priority 14), we can safely load widgets here.
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Loads all available widgets for the theme. Since these are extended classes of 'HybridExtend_WP_Widget', hence
 * this function should only be called 'HybridExtend_WP_Widget' class has been defined.
 *
 * @since 1.0.0
 * @access public
 */
function hybridext_load_widgets() {

	/* Locations for widgets */
	$locations = apply_filters( 'hybridextend_load_widgets', array(
		HYBRIDEXTEND_INC . 'admin/widget-*.php',
		) );

	/* Loads all available widgets for the theme. */
	foreach ( $locations as $location ) {
		foreach ( glob( $location ) as $filename ) {
			include_once( $filename );
		}
	}

}

/**
 * Load widget stylesheets and scripts for the backend.
 *
 * @since 1.1.0
 * @access public
 */
if ( is_admin() ) {
	function hybridext_enqueue_admin_widget_styles_scripts( $hook ) {

		/* Load hybridext widgets for SiteOrigin Page Builder plugin on Edit screens */
		$widgetload = ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && ( defined( 'SITEORIGIN_PANELS_VERSION' ) && version_compare( SITEORIGIN_PANELS_VERSION, '2.0' ) >= 0 ) ) ? true : false;

		if ( 'widgets.php' == $hook || $widgetload ):

			/* Enqueue Styles */
			wp_enqueue_style( 'font-awesome' ); // hybridextend-font-awesome
			$style_uri = hybridextend_locate_style( HYBRIDEXTEND_CSS . 'admin-widgets' );
			wp_enqueue_style( 'hybridext-admin-widgets', $style_uri, array(),  HYBRIDEXTEND_VERSION );

			/* Enqueue Color Picker Styles */
			wp_enqueue_style( 'wp-color-picker' );

			/* Enqueue Scripts including color-picker */
			// Load admin-widgets in footer to maintain script hierarchy
			$script_uri = hybridextend_locate_script( HYBRIDEXTEND_JS . 'admin-widgets' );
			wp_enqueue_script( 'hybridext-admin-widgets', $script_uri, array( 'jquery', 'wp-color-picker' ), HYBRIDEXTEND_VERSION, true );

			/* Enqueues all scripts, styles, settings, and templates necessary to use all media JavaScript APIs. */
			wp_enqueue_media();

		endif;

	}
	add_action( 'admin_enqueue_scripts', 'hybridext_enqueue_admin_widget_styles_scripts' );
}

/**
 * Abstract Widgets Class for creating and displaying widgets. This file is only loaded if the theme supports
 * the 'hybridextend-widgets' feature.
 * 
 * @credit() Derived from Vantage theme code by Greg Priday http://SiteOrigin.com
 *           Licensed under GPL
 * 
 * @since 1.0.0
 * @access public
 */
abstract class HybridExtend_WP_Widget extends WP_Widget {

	protected $form_options;
	protected $repeater_html;
	protected $widgetid;

	/**
	 * Register the widget and load the Widget options
	 * 
	 * @since 1.0.0
	 */
	function __construct( $id, $name, $widget_options = array(), $control_options = array(), $form_options = array() ) {
		$this->form_options = $form_options;
		$this->widgetid = $id;
		parent::__construct( $id, $name, $widget_options, $control_options );

		$this->initialize();
	}

	/**
	 * Initialize this widget in whatever way we need to. Runs before rendering widget or form.
	 *
	 * @since 1.0.0
	 */
	function initialize(){ }

	/**
	 * Display the widget.
	 *
	 * @since 1.0.0
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		$args = wp_parse_args( $args, array(
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		) );

		$defaults = array();
		foreach( $this->form_options as $option ) {
			if ( isset( $option['id'] ) ) {
				$defaults[ $option['id'] ] = ( isset( $option['std'] ) ) ? $option['std'] : '';
			}
		}
		$instance = wp_parse_args( $instance, $defaults );
		global $hybridext_currentwidget;
		$hybridext_currentwidget = array(
			'id'       => $this->widgetid,
			'widget'   => $args,
			'instance' => $instance,
		);

		echo $args['before_widget'];
			$title = ( !empty( $instance['title'] ) ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$this->display_widget( $instance, $args['before_title'], $title, $args['after_title'] );
		echo $args['after_widget'];
	}

	/**
	 * Echo the widget content
	 * Subclasses should over-ride this function to generate their widget code.
	 * Convention: Subclasses should include the template from the theme/widgets folder.
	 *
	 * @since 1.0.0
	 * @param array $args
	 */
	function display_widget( $instance, $before_title = '', $title='', $after_title = '' ) {
		die('function HybridExtend_WP_Widget::display_widget() must be over-ridden in a sub-class.');
	}

	/**
	 * Update the widget instance.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array|void
	 */
	public function update( $new_instance, $old_instance ) {
		$new_instance = $this->sanitize( $new_instance, $this->form_options );
		return $new_instance;
	}

	/**
	 * Display the widget form.
	 *
	 * @since 1.0.0
	 * @param array $instance
	 * @return string|void
	 */
	public function form( $instance ) {
		$form_id = 'hybridext-widget-form-' . md5( uniqid( rand(), true ) );
		$class_name = str_replace( '_', '-', strtolower( get_class($this) ) ); ?>

		<div class="hybridext-widget-form hybridext-widget-form-<?php echo esc_attr( $class_name ) ?>" id="<?php echo $form_id ?>" data-class="<?php echo get_class($this) ?>">

			<?php if ( !empty( $this->widget_options['help'] ) ) : ?>
				<div class="hybridext-widget-form-help"><?php echo $this->widget_options['help']; ?></div>
			<?php endif;

			foreach( $this->form_options as $key => $field ) {
				$field = wp_parse_args( (array) $field, array( 	'name'     => '',
																'desc'     => '',
																'id'       => '',
																'type'     => '',
																'settings' => array(),
																'std'      => '',
																'options'  => array(),
																'fields'   => array(),
														) );
				if ( empty( $field['id'] ) || empty( $field['type'] ) ) continue;

				$value = false;
				if ( isset( $instance[ $field['id'] ] ) ) $value = $instance[ $field['id'] ];
				elseif ( !empty( $field['std'] ) ) $value = $field['std'];

				$this->render_field( $field, $value, false );
			} ?>
			<script type="text/javascript">
				( function($){
					if (typeof window.hybridext_widget_helper == 'undefined')
						window.hybridext_widget_helper = {};
					<?php /*if (typeof window.hybridext_widget_helper["<?php echo get_class($this) ?>"] == 'undefined')*/ // This creates unexpected results as the script is first instancized in template widget __i__ ?>
						window.hybridext_widget_helper["<?php echo get_class($this) ?>"] = <?php echo json_encode( $this->repeater_html ) ?>;
					if (typeof $.fn.hybridextSetupWidget != 'undefined') { // console.log('inline calls setup');
						$('#<?php echo $form_id ?>').hybridextSetupWidget(); // Needed for Customizer
					}
				} )( jQuery );
			</script>
		</div><?php
	}

	/**
	 * Render a form field
	 *
	 * @since 1.0.0
	 * @param $field
	 * @param $value
	 * @param $repeater
	 */
	function render_field( $field, $value, $repeater = array() ){
		extract( $field, EXTR_SKIP );

		?><div class="hybridext-widget-field hybridext-widget-field-type-<?php echo ( strlen( $type ) < 15 ) ? sanitize_html_class( $type ) : 'custom' ?> hybridext-widget-field-<?php echo sanitize_html_class( $id ) ?>"><?php

			if ( !empty( $name ) && $type != 'checkbox' && $type != 'separator' && $type != 'group' && $type != 'collapse' ) { ?>
				<label for="<?php echo $this->hybridext_get_field_id( $id, $repeater ) ?>"><?php echo $name ?>:</label>
			<?php }

			switch( $type ) {
				case 'text' :
					if ( isset( $settings['size'] ) && is_numeric( $settings['size'] ) ) {
						$size = ' size="' . $settings['size'] . '"';
						$class = '';
					} else {
						$size = '';
						$class = ' widefat';
					}
					?><input type="text" name="<?php echo $this->hybridext_get_field_name( $id, $repeater ) ?>" id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) ?>" value="<?php echo esc_attr( $value ) ?>" class="hybridext-widget-input<?php echo $class; ?>" <?php echo $size; ?> /><?php
					break;

				case 'textarea' :
					if ( isset( $settings['rows'] ) && is_numeric( $settings['rows'] ) ) {
						$rows = intval( $settings['rows'] );
					} else {
						$rows = 4;
					}
					?><textarea name="<?php echo $this->hybridext_get_field_name( $id, $repeater ) ?>" id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) ?>" class="widefat hybridext-widget-input" rows="<?php echo $rows; ?>"><?php echo esc_textarea( $value ) ?></textarea><?php
					break;

				case 'separator' :
					?><div class="hybridext-widget-field-separator"></div><?php
					$style = ( !empty( $desc ) ) ? ' style="margin-bottom:0;"' : '';
					if ( !empty( $name ) ) echo "<h4{$style}>{$name}</h4>";
					break;

				case 'checkbox':
					?><label for="<?php echo $this->hybridext_get_field_id( $id, $repeater ) ?>">
						<input type="checkbox" name="<?php echo $this->hybridext_get_field_name( $id, $repeater ) ?>" id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) ?>" class="hybridext-widget-input" <?php checked( !empty( $value ) ) ?> />
						<?php echo $name ?>
					</label><?php
					break;

				case 'select':
					?><select name="<?php echo $this->hybridext_get_field_name( $id, $repeater ) ?>" id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) ?>" class="hybridext-widget-input widefat">
						<?php foreach( $options as $k => $v ) : ?>
							<option value="<?php echo esc_attr($k) ?>" <?php selected($k, $value) ?>><?php echo esc_html($v) ?></option>
						<?php endforeach; ?>
					</select><?php
					break;

				case 'smallselect':
					?><select name="<?php echo $this->hybridext_get_field_name( $id, $repeater ) ?>" id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) ?>" class="hybridext-widget-input hybridextsmallselect">
						<?php foreach( $options as $k => $v ) : ?>
							<option value="<?php echo esc_attr($k) ?>" <?php selected($k, $value) ?>><?php echo esc_html($v) ?></option>
						<?php endforeach; ?>
					</select><?php
					break;

				case 'radio': case 'images':
					?><ul id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) ?>-list" class="hybridext-widget-list hybridext-widget-list-<?php echo $type ?>">
						<?php foreach( $options as $k => $v ) : ?>
							<li class="hybridext-widget-list-item">
								<input type="radio" class="hybridext-widget-input" name="<?php echo $this->hybridext_get_field_name( $id, $repeater ) ?>" id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) . '-' . sanitize_html_class( $k ) ?>" value="<?php echo esc_attr($k) ?>" <?php checked( $k, $value ) ?>>
								<label for="<?php echo $this->hybridext_get_field_id( $id, $repeater ) . '-' . sanitize_html_class( $k ) ?>"><?php echo ( 'radio' === $type ) ? $v : "<img class='hybridext-widget-image-picker-img' src='" . esc_url( $v ) . "'>" ?></label>
							</li>
						<?php endforeach; ?>
					</ul><?php
					break;

				case 'icon':
					$iconvalue = hybridextend_sanitize_fa( $value );
					?><input id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) ?>" class="hybridext-icon" name="<?php echo $this->hybridext_get_field_name( $id, $repeater ) ?>" type="hidden" value="<?php echo esc_attr( $iconvalue ) ?>" />
					<div id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) . '-icon-picked' ?>" class="hybridext-icon-picked"><i class="<?php echo esc_attr( $iconvalue ) ?>"></i><span><?php _e( 'Select Icon', 'hybrid-core' ) ?></span></div>
					<div class="clear"></div>
					<div id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) . '-icon-picker-box' ?>" class="hybridext-icon-picker-box">
						<div class="hybridext-icon-picker-list"><i class="fas fa-ban hybridext-icon-none" data-value="0" data-category=""><span><?php _e( 'Remove Icon', 'hybrid-core' ) ?></span></i></div>
						<?php
						$section_icons = hybridextend_enum_icons('icons');
						foreach ( hybridextend_enum_icons('sections') as $s_key => $s_title ) { ?>
							<h4><?php echo $s_title ?></h4>
							<div class="hybridext-icon-picker-list"><?php
							foreach ( $section_icons[$s_key] as $i_key => $i_class ) {
								$selected = ( $iconvalue == $i_class ) ? ' selected' : '';
								?><i class='<?php echo $i_class . $selected; ?>' data-value='<?php echo $i_class; ?>' data-category='<?php echo $s_key ?>'></i><?php
							} ?>
							</div><?php
						}
						?>
					</div><?php
					break;

				case 'image':
					?><input id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) ?>" class="hybridext-image" name="<?php echo $this->hybridext_get_field_name( $id, $repeater ) ?>" type="hidden" value="<?php echo esc_attr( $value ) ?>" />
					<div id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) . '-image-selected' ?>" class="hybridext-image-selected" data-title="<?php _e( 'Select Image', 'hybrid-core' ) ?>" data-update="<?php _e( 'Set Image', 'hybrid-core' ) ?>" data-library="image"><span class="hybridext-image-selected-img" <?php
						if ( !empty( $value ) ) {
							$post = get_post( $value );
							$src = wp_get_attachment_image_src( $value, 'thumbnail' );
							if( empty( $src ) ) $src = wp_get_attachment_image_src( $value, 'thumbnail', true );
							if ( !empty( $src[0] ) ) echo 'style="background-image: url(' . esc_attr( $src[0] ) . ')"';
						}
						?>></span><span class="hybridext-image-selected-label"><?php _e( 'Add Image', 'hybrid-core' ) ?></span></div>
						<a href="#" class="hybridext-image-remove"><?php _e( 'Remove Image', 'hybrid-core' ) ?></a>
					<?php
					break;

				case 'color':
					$default_color = ( !empty( $field['std'] ) ) ? 'data-default-color="' . $field['std'] . '"' : '';
					?><input id="<?php echo $this->hybridext_get_field_id( $id, $repeater ) ?>" class="hybridext-color" name="<?php echo $this->hybridext_get_field_name( $id, $repeater ) ?>" type="input" value="<?php echo esc_attr( $value ) ?>" <?php echo $default_color; ?> />
					<?php
					break;

				case 'group':
					$repeater[] = $id;
					?><div class="hybridext-widget-field-group" data-id="<?php echo esc_attr( $id ) ?>">
						<?php if ( !empty( $name ) ): ?>
							<div class="hybridext-widget-field-group-top">
								<h3><?php echo $name ?> <i class="fas fa-sort"></i></h3>
							</div>
						<?php endif; ?>
						<?php $item_name = isset( $options['item_name'] ) ? $options['item_name'] : ''; ?>
						<div class="hybridext-widget-field-group-items<?php if ( !empty( $options['sortable'] ) ) echo ' issortable'; ?>">
							<?php
							if ( !empty( $value ) ) {
								if ( isset( $options['maxlimit'] ) ) {
									$maxlimitcheck = absint( $options['maxlimit'] );
								}
								$groupcount = 1;
								foreach( $value as $k =>$v ) {
									$this->render_group( $k, $v, $fields, $item_name, $repeater );
									if ( !empty( $maxlimitcheck ) && $groupcount >= $maxlimitcheck ) break;
									else $groupcount++;
								}
							} else $maxlimitcheck = 0; ?>
						</div>
						<?php
							ob_start();
							$this->render_group( 975318642, array(), $fields, $item_name, $repeater );
							$html = ob_get_clean();
							$this->repeater_html[$id] = $html;
							$maxlimit = ( isset( $options['maxlimit'] ) ) ? ' data-limit="' . absint( $options['maxlimit'] ) . '"' : '';
							$limitmsg = ( isset( $options['limitmsg'] ) ) ? ' data-limitmsg="' . esc_attr( $options['limitmsg'] ) . '"' : '';
						?>
						<div id="add-<?php echo rand(1000, 9999); ?>" class="hybridext-widget-field-group-add<?php if ( !empty($maxlimitcheck) && $groupcount >= $maxlimitcheck ) echo ' maxreached'; ?>" data-iterator="<?php echo is_array( $value ) ? max( array_keys( $value ) ) : 0; ?>" <?php echo $maxlimit.$limitmsg ?>><?php _e('Add', 'hybrid-core') ?></div>
					</div>
					<?php
					break;

				case 'collapse':
					$repeater[] = $id;
					$bodystyle = ( isset( $settings['state'] ) && ( $settings['state'] == 'open' ) ) ? ' style="display:block;"' : '';
					?><div class="hybridext-widget-field-collapse" data-id="<?php echo esc_attr( $id ) ?>">
						<div class="hybridext-collapse-head"><h3><?php if ( !empty( $name ) ) echo esc_html($name); else _e('Group', 'hybrid-core') ?> <i class="fas fa-sort"></i></h3></div>
						<div class="hybridext-collapse-body"<?php echo $bodystyle; ?>>
							<?php foreach( $fields as $field ) {
								$field = wp_parse_args( (array) $field, array( 	'name'     => '',
																				'desc'     => '',
																				'id'       => '',
																				'type'     => '',
																				'settings' => array(),
																				'std'      => '',
																				'options'  => array(),
																				'fields'   => array(),
																		) );
								$fieldvalue = false;
								if ( isset( $value[ $field['id'] ] ) ) $fieldvalue = $value[ $field['id'] ];
								elseif ( !empty( $field['std'] ) ) $fieldvalue = $field['std'];
								$this->render_field( $field, $fieldvalue, $repeater );
							} ?>
						</div>
					</div>
					<?php
					break;

				default:
					echo str_replace( array( '%id%', '%class%', '%name%', '%value%' ),
									  array( $this->hybridext_get_field_id( $id, $repeater ), 'hybridext-widget-input', $this->hybridext_get_field_name( $id, $repeater ), $value ),
									  $type );
					break;

			}

			if ( ! empty( $desc ) )
				echo '<div class="hybridext-widget-field-description"><small>' . wp_kses_post( $desc ) . '</small></div>';
			echo '<div class="clear"></div>';

		?></div><?php
	}

	/**
	 * Render a group field
	 *
	 * @since 1.0.0
	 * @param $field
	 * @param $value
	 * @param $repeater
	 */
	function render_group( $key, $value, $fields, $item_name = '', $repeater = array() ){
		if ( empty( $fields ) ) return;

		$repeater[] = intval( $key ); ?>
		<div class="hybridext-widget-field-group-item">
			<div class="hybridext-widget-field-group-item-top">
				<div class="hybridext-widget-field-group-remove">X</div>
				<h4><i class="fas fa-arrows-alt"></i><i class="fas fa-caret-down"></i> <?php echo esc_html( $item_name ) ?></h4>
			</div>
			<div class="hybridext-widget-field-group-item-form">
				<?php foreach( $fields as $field ) {
					$field = wp_parse_args( (array) $field, array( 	'name'     => '',
																	'desc'     => '',
																	'id'       => '',
																	'type'     => '',
																	'settings' => array(),
																	'std'      => '',
																	'options'  => array(),
																	'fields'   => array(),
															) );
					$fieldvalue = isset( $value[ $field['id'] ] ) ? $value[ $field['id'] ] : false;
					$fieldvalue = ( !$fieldvalue && !empty( $field['std'] ) ) ? $field['std'] : $fieldvalue;
					$this->render_field( $field, $fieldvalue, $repeater );
				} ?>
			</div>
		</div><?php
	}

	/**
	 * @since 1.0.0
	 * @param $id
	 * @param array $repeater
	 * @return mixed|string
	 */
	public function hybridext_get_field_name( $id, $repeater = array() ) {
		if ( empty( $repeater ) ) return $this->get_field_name( $id );
		else {
			$repeater_extras = '';
			foreach( $repeater as $r )
				$repeater_extras .= '[' . $r . ']';
			$repeater_extras .= '[' . esc_attr( $id ) . ']';
			$name = $this->get_field_name('{{{FIELD_NAME}}}');
			$name = str_replace( '[{{{FIELD_NAME}}}]', $repeater_extras, $name );
			return $name;
		}
	}

	/**
	 * Get the ID of this field.
	 *
	 * @since 1.0.0
	 * @param $id
	 * @param array $repeater
	 * @return string
	 */
	public function hybridext_get_field_id( $id, $repeater = array() ) {
		if ( empty( $repeater ) ) return $this->get_field_id( $id );
		else {
			$ids = $repeater;
			$ids[] = $id;
			return $this->get_field_id( implode( '-', $ids ) );
		}
	}

	/**
	 * Sanitize field values to store in database
	 *
	 * @since 1.1.7
	 * @param $instance
	 * @param $fields
	 */
	public function sanitize( $instance, $fields ) {
		foreach ( $fields as $field ) {

			/* Skip if the field does not have an id/type */
			if ( !isset( $field['id'] ) || !isset( $field['type'] ) )
				continue;

			/* Skip if instance value is not set (except for checkbox) */
			$id = $field['id'];
			if ( !isset( $instance[ $id ] ) && $field['type'] != 'checkbox' )
				continue;

			/* Sanitize field values */
			switch ( $field['type'] ) {
				case 'textarea':
					global $allowedposttags;
					$instance[ $id ] = wp_kses( $instance[ $id ], $allowedposttags);
					break;
				case 'checkbox':
					$instance[ $id ] = ( !empty( $instance[ $id ] ) ) ? 1 : 0;
					break;
				case 'select': case 'radio': case 'images':
					$instance[ $id ] = ( isset( $field['options'][ $instance[ $id ] ] ) ) ? $instance[ $id ] : '';
					break;
				case 'icon':
					$icons = hybridextend_enum_icons();
					$instance[ $id ] = ( in_array( $instance[ $id ], $icons ) ) ? $instance[ $id ] : '';
					break;
				case 'group':
					foreach ( $instance[ $id ] as $i => $subinstance ) {
						$instance[ $id ][ $i ] = $this->sanitize( $subinstance, $field['fields'] );
					}
					break;
				case 'collapse':
					$instance[$id] = $this->sanitize( $instance[$id], $field['fields'] );
					break;
			}

			/* Custom sanitizations for specific field. Example, a text input has a url */
			if ( isset( $field['sanitize'] ) ) {
				switch( $field['sanitize'] ) {
					case 'url':
						$instance[ $id ] = esc_url_raw( $instance[ $id ] );
						break;
					case 'integer':
						if ( $instance[ $id ] !== '0' && $instance[ $id ] !== 0 ) {
							$instance[ $id ] = intval( $instance[ $id ] );
							$instance[ $id ] = ( !empty( $instance[ $id ] ) ) ? $instance[ $id ] : '';
						}
						break;
					case 'absint':
						if ( $instance[ $id ] !== '0' && $instance[ $id ] !== 0 ) {
							$instance[ $id ] = absint( $instance[ $id ] );
							$instance[ $id ] = ( !empty( $instance[ $id ] ) ) ? $instance[ $id ] : '';
						}
						break;
					case 'email':
						$instance[ $id ] = ( is_email( $instance[ $id ] ) ) ? sanitize_email( $instance[ $id ] ) : '';
						break;
					// Allow custom sanitization functions
					default:
						$instance[ $id ] = apply_filters( 'widget_admin_sanitize_field', $instance[ $id ], $field['sanitize'], $instance );
				}
			}

		}
		return $instance;
	}

	/**
	 * Helper function to get a list for option values
	 *
	 * @since 1.0.0
	 * @param $post_type
	 * @param $number Set to -1 to show all
	 *                @see: http://codex.wordpress.org/Class_Reference/WP_Query#Pagination_Parameters
	 * @return array
	 */
	// @todo more post types and taxonomies
	static function get_wp_list( $post_type = 'page', $number = false ) {
		$number = intval( $number );
		if ( false === $number || empty( $number ) ) {
			$number = HYBRIDEXTEND_ADMIN_LIST_ITEM_COUNT;

			if ( $post_type == 'page' ) {
				static $options_pages = array(); // cache
				if ( empty( $options_pages ) )
					$options_pages = self::get_pages( $number );
				return $options_pages;
			}

		} else {

			if ( $post_type == 'page' ) {
				$pages = self::get_pages( $number );
				return $pages;
			}

		}
	}

	/**
	 * Helper function to get a list of taxonomies
	 *
	 * @since 1.1.1
	 * @param $taxonomy
	 * @return array
	 */
	static function get_tax_list( $taxonomy = 'category' ) {
		static $options_tax = array(); // cache
		if ( empty( $options_tax[ $taxonomy ] ) ) {
			$options_tax[ $taxonomy ] = array();
			$object = (array) get_terms( array( 'taxonomy' => $taxonomy ) );
			foreach ( $object as $term )
				$options_tax[ $taxonomy ][$term->term_id] = $term->name;
		}
		return $options_tax[ $taxonomy ];
	}

	/**
	 * Get pages array
	 *
	 * @since 1.0.0
	 * @param int $number Set to -1 for all pages
	 * @param string $post_type for custom post types
	 * @return array
	 */
	static function get_pages( $number, $post_type = 'page' ){
		// Doesnt allow -1 as $number
		// $options_pages_obj = get_pages("sort_column=post_parent,menu_order&number=$number");
		// $options_pages[''] = __( 'Select a page:', 'hybrid-core' );
		$options_pages = array();
		$number = intval( $number );
		$the_query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => $number, 'orderby' => 'post_title', 'order' => 'ASC' ) );
		// Prietable plugin (wpalchemy) bug compatibility: We cannot run a custom loop (with
		// $the_query->the_post() ) since this will set global $post (initially empty before looping
		// through custom query). Even wp_reset_postdata() doesnt set global $post back to empty
		// wpalchemy uses global $post->ID, and hence gets the ID of last page instead of empty (at
		// a later hook, it would have got its easy table's post ID)
		// All this happens in Metabox.php file in easy-pricing-tables (hooked to 'admin_init' at 10)
		// if ( $the_query->have_posts() ) :
		// 	while ( $the_query->have_posts() ) : $the_query->the_post();
		// 		$options_pages[ get_the_ID() ] = get_the_title();
		// 	endwhile;
		// 	wp_reset_postdata();
		// endif;
		if ( !empty( $the_query->posts ) )
			foreach ( $the_query->posts as $post ) if( !empty( $post->ID ) )
				$options_pages[ $post->ID ] = ( empty( $post->post_title ) ) ? '' : apply_filters( 'the_title', $post->post_title, $post->ID );
		return $options_pages;
	}

}


/* Loads all available widget classes for the theme. */
hybridext_load_widgets();