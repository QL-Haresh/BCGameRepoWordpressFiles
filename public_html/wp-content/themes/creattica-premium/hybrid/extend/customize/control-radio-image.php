<?php
/**
 * Customize for radioimage, extend the WP customizer
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Radioimage Control Class extends the WP customizer
 *
 * @since 2.0.0
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
class HybridExtend_Customize_Radioimage_Control extends WP_Customize_Control {

	/**
	 * @since 2.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'radioimage';

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

			case 'radioimage' :
				if ( empty( $this->choices ) )
					return;

				$name = '_customize-radio-' . $this->id;

				if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;

				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo $this->description ; ?></span>
				<?php endif;

				if ( ! empty( $this->sublabel ) ) : ?>
					<span class="description customize-control-sublabel"><?php echo $this->sublabel ; ?></span>
				<?php endif;

				foreach ( $this->choices as $value => $image ) :
					$checked = checked( $this->value(), $value, false );
					?>
					<label class="hybridextend-customize-radioimage<?php if ($checked) echo ' radiocheck' ?>">
						<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); echo $checked; ?> />
						<img src="<?php echo esc_url( $image ); ?>" />
					</label>
					<?php
				endforeach;

				echo '<div class="clear"></div>';

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
function hybridextend_customize_radioimage_control_interface ( $wp_customize, $id, $setting ) {
	if ( isset( $setting['type'] ) ) :
		if ( $setting['type'] == 'radioimage' ) {
			$wp_customize->add_control(
				new HybridExtend_Customize_Radioimage_Control( $wp_customize, $id, $setting )
			);
		}
	endif;
}
add_action( 'hybridextend_customize_control_interface', 'hybridextend_customize_radioimage_control_interface', 10, 3 );
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
function hybridextend_customize_radioimage_sanitization_function( $name, $type, $setting ) {
	if ( $type == 'radioimage' )
		$name = 'hybridextend_customize_sanitize_choices';
	return $name;
}
add_filter( 'hybridextend_customize_sanitization_function', 'hybridextend_customize_radioimage_sanitization_function', 5, 3 );