<?php
/**
 * Customize for typography, extend the WP customizer
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Fontface Control Class extends the WP customizer
 *
 * @since 2.0.0
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
class HybridExtend_Customize_Fontface_Control extends WP_Customize_Control {

	/**
	 * @since 2.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'fontface';

	/**
	 * Render the control's content.
	 * Allows the content to be overriden without having to rewrite the wrapper.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function render_content() {

		switch ( $this->type ) {

			case 'fontface' :

				if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;

				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo $this->description ; ?></span>
				<?php endif;

				$value = $this->value();
				$webfonts = hybridextend_enum_font_faces('websafe');
				$googlefonts = hybridextend_enum_font_faces('google-fonts');
				$selectedfont = ( isset( $webfonts[ $value ] ) ) ?
							$webfonts[ $value ] :
							(
								( isset( $googlefonts[ $value ] ) ) ?
								$googlefonts[ $value ] :
								''
							);
				?>

				<input class="hybridextend-customize-control-fontface" value="<?php echo esc_attr( $value ) ?>" <?php $this->link(); ?> type="hidden" data-label="<?php echo esc_attr( $selectedfont ); ?>"/>

				<div class="hybridextend-customize-control-fontface-picked">
					<div><?php echo $selectedfont; ?></div>
				</div>

				<div class="hybridextend-fontface-list">
					<?php $fontclass = ' hybridextend-fontface-websafe';

					// Allow child themes to add custom fonts
					do_action( 'hybridextend_fontface_list', $value );

					foreach ( array(
						__( 'Websafe Fonts', 'hybrid-core' ) => $webfonts,
						__( 'Google Web Fonts', 'hybrid-core' ) => $googlefonts,
						) as $subhead => $fontarray ) {

						$font_count = 0;
						?>
						<h4><?php echo $subhead; ?></h4>
						<?php

						foreach ( $fontarray as $val => $label ) {
							$selected = ( $val == $value ) ? ' selected' : '';
							?>
							<div class="hybridextend-fontface-listitem <?php echo $selected; ?>" data-value="<?php echo esc_attr( $val ) ?>" data-label="<?php echo esc_attr( $label ) ?>">
								<span><?php echo esc_html( $label ); ?></span>
								<div class="hybridextend-fontface-preview<?php echo $fontclass; ?>" style="background-position: 0 <?php echo ( -30 * $font_count ) .'px;'; ?>"></div>
							</div>
							<?php
							$font_count++;
						}

						$fontclass = '';
					}
					?>

				</div>

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
function hybridextend_customize_fontface_control_interface ( $wp_customize, $id, $setting ) {
	if ( isset( $setting['type'] ) ) :
		if ( $setting['type'] == 'fontface' ) {
			$wp_customize->add_control(
				new HybridExtend_Customize_Fontface_Control( $wp_customize, $id, $setting )
			);
		}
	endif;
}
add_action( 'hybridextend_customize_control_interface', 'hybridextend_customize_fontface_control_interface', 10, 3 );
endif;

/**
 * Modify the settings array and prepare typography settings for Customizer Library Interface functions
 *
 * @since 2.0.0
 * @param array $value
 * @param string $key
 * @param array $setting
 * @param int $count
 * @return void
 */
function hybridextend_customize_prepare_typography_settings( $value, $key, $setting, $count ) {

	if ( $setting['type'] == 'typography' ) {

		$setting = wp_parse_args( $setting, array(
			'label'       => '',
			'section'     => '',
			'priority'    => '',
			'choices'     => array(),
			'default'     => array(),
			'description' => '',
			'options'     => array( 'size', 'face', 'style', 'color' ),
			) );
		$setting['choices'] = wp_parse_args( $setting['choices'], array(
			'size'        => array(),
			'face'        => array(),
			'style'       => array(),
			) );
		$setting['default'] = wp_parse_args( $setting['default'], array(
			'size'        => '',
			'face'        => '',
			'style'       => '',
			'color'       => '',
			) );

		if( is_array( $setting['options'] ) && !empty( $setting['options'] ) ):
			$size = in_array( 'size', $setting['options'] );
			$face = in_array( 'face', $setting['options'] );
			$style = in_array( 'style', $setting['options'] );
			$color = in_array( 'color', $setting['options'] );

			if( $size || $face || $style || $color ):

				// Group Start
				$button = "<span class='hybridextend-typo-button' data-controlgroup='{$key}'>";
					if ( $face ) $button .= '<span class="hybridextend-typo-button-unit hybridextend-typo-button-face"></span>';
					if ( $size ) $button .= '<span class="hybridextend-typo-button-unit hybridextend-typo-button-size"></span>';
					if ( $style ) $button .= '<span class="hybridextend-typo-button-unit hybridextend-typo-button-style"></span>';
					if ( $color ) $button .= '<span class="hybridextend-typo-button-unit hybridextend-typo-button-color"></span>';
				$button .= '</span>';

				$value[ "group-{$count}" ] = array(
					'label'       => $setting['label'],
					'section'     => $setting['section'],
					'type'        => 'group',
					'priority'    => $setting['priority'],
					'description' => $setting['description'],
					'button'      => $button,
					'group'       => 'start',
				);

				// Font Color :: (priority & section same as group)
				if ( $color ) :

					$value[ "{$key}-color" ] = array(
						'label'       =>  __( 'Font Color', 'hybrid-core' ),
						'section'     => $setting['section'],
						'type'        => 'color',
						'priority'    => $setting['priority'],
						'default'     => $setting['default']['color'],
						'description' => '',
					);

				endif;

				// Font Size :: (priority & section same as group)
				if ( $size ) :

					$choices = array();
					$fontsizes = ( !empty( $setting['choices']['size'] ) && is_array( $setting['choices']['size'] ) ) ?
						$setting['choices']['size'] : hybridextend_enum_font_sizes();
					foreach ( $fontsizes as $fsz )
						$choices[ $fsz . 'px' ] = $fsz . 'px';

					$value[ "{$key}-size" ] = array(
						'label'       =>  __( 'Font Size', 'hybrid-core' ),
						'section'     => $setting['section'],
						'type'        => 'select',
						'priority'    => $setting['priority'],
						'choices'     => $choices,
						'default'     => $setting['default']['size'],
						'description' => '',
					);

				endif;

				// Font Style :: (priority & section same as group)
				if ( $style ):

					$choices = ( !empty( $setting['choices']['style'] ) && is_array( $setting['choices']['style'] ) ) ?
						$setting['choices']['style'] : hybridextend_enum_font_styles();

					$value[ "{$key}-style" ] = array(
						'label'       =>  __( 'Font Style', 'hybrid-core' ),
						'section'     => $setting['section'],
						'type'        => 'select',
						'priority'    => $setting['priority'],
						'choices'     => $choices,
						'default'     => $setting['default']['style'],
						'description' => array(
							'type' => 'blue',
							'text' => sprintf( __( 'NOTE: Not all fonts support all styles/variants. For example, "Playball" font does not support Bold/Italic/Light variant.<hr />For more info, check the %sGoogle Fonts Library%s', 'hybrid-core' ), '<a href="https://www.google.com/fonts">', '</a>'),
						),
					);

				endif;

				// Font Face :: (priority & section same as group)
				if ( $face ):

					$value[ "{$key}-face" ] = array(
						'label'       =>  __( 'Font Face', 'hybrid-core' ),
						'section'     => $setting['section'],
						'type'        => 'fontface',
						'priority'    => $setting['priority'],
						'choices'     => $setting['choices']['face'],
						'default'     => $setting['default']['face'],
						'description' => '',
					);

				endif;

				// Group End
				$value[ "group-{$count}-end" ] = array(
					'section'     => $setting['section'],
					'type'        => 'group',
					'priority'    => $setting['priority'],
					'group'       => 'end',
				);

			endif;
		endif;

	}

	return $value;

}
add_filter( 'hybridextend_customize_prepare_settings', 'hybridextend_customize_prepare_typography_settings', 10, 4 );

/**
 * Add sanitization function
 *
 * @since 2.0.0
 * @param string $name
 * @param string $type
 * @param array $setting
 * @return string
 */
function hybridextend_customize_fontface_sanitization_function( $name, $type, $setting ) {
	if ( $type == 'fontface' )
		$name = 'hybridextend_customize_sanitize_fontface';
	return $name;
}
add_filter( 'hybridextend_customize_sanitization_function', 'hybridextend_customize_fontface_sanitization_function', 5, 3 );

/**
 * Sanitize fontface value to allow only allowed choices.
 *
 * @since 2.0.0
 * @param string $value The unsanitized string.
 * @param mixed $setting The setting for which the sanitizing is occurring.
 * @return string The sanitized value.
 */
function hybridextend_customize_sanitize_fontface( $value, $setting ) {
	$choices = hybridextend_enum_font_faces();

	if ( ! array_key_exists( $value, $choices ) ) {
		if ( is_object( $setting ) )
			$setting = $setting->id;
		$value = hybridextend_customize_get_default( $setting );
	}

	return $value;
}