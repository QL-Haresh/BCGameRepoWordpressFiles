<?php
/**
 * Customize for icon picker, extend the WP customizer
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Icon Picker Control Class extends the WP customizer
 *
 * @since 2.0.0
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
class HybridExtend_Customize_Icon_Control extends WP_Customize_Control {

	/**
	 * @since 2.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'icon';

	/**
	 * Define variable to whitelist sublabel parameter
	 *
	 * @since 2.1.0
	 * @access public
	 * @var string
	 */
	public $sublabel = '';

	/**
	 * Render the control's content.
	 * Allows the content to be overriden without having to rewrite the wrapper.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function render_content() {

		switch ( $this->type ) {

			case 'icon' :

				if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;

				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo $this->description ; ?></span>
				<?php endif;

				if ( ! empty( $this->sublabel ) ) : ?>
					<span class="description customize-control-sublabel"><?php echo $this->sublabel ; ?></span>
				<?php endif;

				$iconvalue = hybridextend_sanitize_fa( $this->value() );
				?>
				<input class="hybridextend-customize-control-icon" value="<?php echo esc_attr( $iconvalue ) ?>" <?php $this->link(); ?> type="hidden"/>
				<div class="hybridextend-customize-control-icon-picked hybridextend-flypanel-button" data-flypaneltype="icon"><i class="<?php echo esc_attr( $iconvalue ) ?>"></i><span><?php _e( 'Select Icon', 'hybrid-core' ) ?></span></div>
				<div class="hybridextend-customize-control-icon-remove"><i class="fas fa-ban"></i><span><?php _e( 'Remove Icon', 'hybrid-core' ) ?></span></div>

				<?php
				break;

		}

	}

}
endif;

/**
 * Hook into control display interface
 *
 * @since 2.0.0
 * @param object $wp_customize
 * @param string $id
 * @param array $setting
 * @return void
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
function hybridextend_customize_icon_control_interface ( $wp_customize, $id, $setting ) {
	if ( isset( $setting['type'] ) ) :
		if ( $setting['type'] == 'icon' || $setting['type'] == 'icons' ) {
			$setting['type'] = 'icon';
			$wp_customize->add_control(
				new HybridExtend_Customize_Icon_Control( $wp_customize, $id, $setting )
			);
		}
	endif;
}
add_action( 'hybridextend_customize_control_interface', 'hybridextend_customize_icon_control_interface', 10, 3 );
endif;

/**
 * Add Content to Customizer Panel Footer
 *
 * @since 2.0.0
 * @return void
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
function hybridextend_customize_footer_iconcontent() {

	?>
	<div id="hybridextend-flyicon" class="hybridextend-flypanel">
		<div class="hybridextend-flypanel-header hybridextend-flypanel-nav">
			<div class="primary-actions">
				<span class="hybridextend-flypanel-back" tabindex="-1"><span class="screen-reader-text"><?php _e( 'Back', 'hybrid-core' ) ?></span></span>
			</div>
		</div>
		<div id="hybridextend-flyicon-content" class="hybridextend-flypanel-content">
		</div>
		<div class="hybridextend-flypanel-footer hybridextend-flypanel-nav">
			<div class="primary-actions">
				<span class="hybridextend-flypanel-back" tabindex="-1"><span class="screen-reader-text"><?php _e( 'Back', 'hybrid-core' ) ?></span></span>
			</div>
		</div>
	</div><!-- .hybridextend-flypanel -->
	<?php

}
add_action( 'customize_controls_print_footer_scripts', 'hybridextend_customize_footer_iconcontent' );
endif;

/**
 * Add Content to JS object passed to hybridextend-customize-script
 *
 * @since 2.0.0
 * @return void
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
function hybridextend_customize_controls_icon_control_js_object( $data ) {

	$iconslist = '';
	$section_icons = hybridextend_enum_icons('icons');

	$iconslist .= '<div class="hybridextend-icon-list-wrap">';

	foreach ( hybridextend_enum_icons('sections') as $s_key => $s_title ) {
		$iconslist .= "<h4>$s_title</h4>";
		$iconslist .= '<div class="hybridextend-icon-list">';
		foreach ( $section_icons[$s_key] as $i_key => $i_class ) {
			$iconslist .= "<i class='$i_class' data-value='$i_class' data-category='$s_key'></i>";
		}
		$iconslist .= '</div>';
	}

	$iconslist .= '</div>';

	$data['iconslist'] = $iconslist;
	return $data;

}
add_filter( 'hybridextend_customize_control_footer_js_data_object', 'hybridextend_customize_controls_icon_control_js_object' );
endif;

/**
 * Add sanitization function
 *
 * @since 2.0.0
 * @param string $name
 * @param string $type
 * @param array $setting
 * @return string
 */
function hybridextend_customize_icon_sanitization_function( $name, $type, $setting ) {
	if ( $type == 'icon' )
		$name = 'hybridextend_customize_sanitize_icon';
	return $name;
}
add_filter( 'hybridextend_customize_sanitization_function', 'hybridextend_customize_icon_sanitization_function', 5, 3 );

/**
 * Sanitize icon value to allow only allowed choices.
 *
 * @since 2.0.0
 * @param string $value The unsanitized string.
 * @param mixed $setting The setting for which the sanitizing is occurring.
 * @return string The sanitized value.
 */
function hybridextend_customize_sanitize_icon( $value, $setting ) {
	$choices = hybridextend_enum_icons();

	if ( ! in_array( $value, $choices ) ) {
		if ( is_object( $setting ) )
			$setting = $setting->id;
		$value = hybridextend_customize_get_default( $setting );
	}

	return $value;
}