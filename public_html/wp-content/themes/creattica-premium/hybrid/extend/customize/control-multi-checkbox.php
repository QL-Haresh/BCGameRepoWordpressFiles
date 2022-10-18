<?php
/**
 * Customize for Multiple Checkbox (bettercheckbox), extend the WP customizer
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Better Checkbox Control Class extends the WP customizer
 *
 * @since 2.0.0
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
class HybridExtend_Customize_Bettercheckbox_Control extends WP_Customize_Control {

	/**
	 * @since 2.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'bettercheckbox';

	/**
	 * Define variable to whitelist sublabel parameter
	 *
	 * @since 2.0.0
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

			case 'bettercheckbox' :

				?>
				<span class="<?php if ( !empty( $this->choices ) && is_array( $this->choices ) ) echo 'bettercheckbox-multi'; else echo 'bettercheckbox-single'; ?>">
				<?php

					if ( ! empty( $this->label ) ) : ?>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php endif;

					if ( ! empty( $this->description ) ) : ?>
						<span class="description customize-control-description"><?php echo $this->description ; ?></span>
					<?php endif;

					if ( ! empty( $this->sublabel ) ) : ?>
						<span class="description customize-control-sublabel"><?php echo $this->sublabel ; ?></span>
					<?php endif;

					if ( !empty( $this->choices ) && is_array( $this->choices ) ) {

						$multi_values = ( !is_array( $this->value() ) ) ? explode( ',', $this->value() ) : $this->value();
						$multi_values = array_map( 'trim', $multi_values );

						foreach ( $this->choices as $value => $label ) :
							?>
							<label>
								<input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> />
								<?php echo esc_html( $label ); ?><br/>
							</label>
							<?php
						endforeach;
						?>

						<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
						<?php

					} else {
						?>
						<input type="checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
						<?php
					}

				?>
				</span>
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
function hybridextend_customize_bettercheckbox_control_interface ( $wp_customize, $id, $setting ) {
	if ( isset( $setting['type'] ) ) :
		if ( $setting['type'] == 'bettercheckbox' || $setting['type'] == 'multicheckbox' ) {
			$setting['type'] = 'bettercheckbox';
			$wp_customize->add_control(
				new HybridExtend_Customize_Bettercheckbox_Control( $wp_customize, $id, $setting )
			);
		}
	endif;
}
add_action( 'hybridextend_customize_control_interface', 'hybridextend_customize_bettercheckbox_control_interface', 10, 3 );
endif;

/**
 * Modify the settings array and prepare bettercheckbox settings for Customizer Library Interface functions
 *
 * @since 2.0.0
 * @param array $value
 * @param string $key
 * @param array $setting
 * @param int $count
 * @return void
 */
function hybridextend_customize_prepare_bettercheckbox_settings( $value, $key, $setting, $count ) {

	if ( $setting['type'] == 'checkbox' ) {
		$setting['type'] = 'bettercheckbox';
		$value[ $key ] = $setting;
	}

	return $value;

}
add_filter( 'hybridextend_customize_prepare_settings', 'hybridextend_customize_prepare_bettercheckbox_settings', 10, 4 );

/**
 * Add sanitization function
 *
 * @since 2.0.0
 * @param string $name
 * @param string $type
 * @param array $setting
 * @return string
 */
function hybridextend_customize_bettercheckbox_sanitization_function( $name, $type, $setting ) {
	if ( $type == 'bettercheckbox' ) {
		if ( !empty( $setting['choices'] ) && is_array( $setting['choices'] ) )
			$name = 'hybridextend_customize_sanitize_multicheckbox';
		else
			$name = 'hybridextend_customize_sanitize_checkbox';
	}
	return $name;
}
add_filter( 'hybridextend_customize_sanitization_function', 'hybridextend_customize_bettercheckbox_sanitization_function', 5, 3 );

/**
 * Sanitize multicheckbox value to allow only allowed choices.
 *
 * @since 2.0.0
 * @param string $value The unsanitized string.
 * @param mixed $setting The setting for which the sanitizing is occurring.
 * @return string The sanitized value.
 */
function hybridextend_customize_sanitize_multicheckbox( $value, $setting ) {
	if ( is_object( $setting ) )
		$setting = $setting->id;

	$choices = hybridextend_customize_get_choices( $setting );
	$multi_values = array_map( 'trim', explode( ',', $value ) );
	$return = array();

	foreach ( $multi_values as $key ) {
		if ( array_key_exists( $key, $choices ) )
			$return[] = $key;
	}

	return implode( ',', $return );
}