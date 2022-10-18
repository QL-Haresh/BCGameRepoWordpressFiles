<?php
/**
 * Customize for group, extend the WP customizer
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Group Control Class extends the WP customizer
 *
 * @since 2.0.0
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
class HybridExtend_Customize_Group_Control extends WP_Customize_Control {

	/**
	 * @since 2.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'group';

	/**
	 * Define variable to whitelist sublabel parameter
	 *
	 * @since 2.1.0
	 * @access public
	 * @var string
	 */
	public $sublabel = '';

	/**
	 * Define variable to whitelist group parameter
	 *
	 * @since 2.0.0
	 * @access public
	 * @var string
	 */
	public $group = '';

	/**
	 * Define variable to whitelist button parameter
	 *
	 * @since 2.2.2
	 * @access public
	 * @var string
	 */
	public $startwrap = '';

	/**
	 * Define variable to whitelist button parameter
	 *
	 * @since 2.0.0
	 * @access public
	 * @var string
	 */
	public $button = '';

	/**
	 * Define variable to whitelist identifier parameter
	 *
	 * @since 2.1.0
	 * @access public
	 * @var string
	 */
	public $identifier = '';

	/**
	 * Renders the control wrapper and calls $this->render_content() for the internals.
	 * Add extra class names
	 *
	 * @since 2.0.0
	 */
	protected function render() {
		$id    = 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) );
		$class = 'customize-control customize-control-' . $this->type . ' hybridextend-customize-control-' . $this->type . $this->group . ' hybridextend-control-id-' . $this->identifier . ' ' . sanitize_html_class( $this->startwrap );

		?><li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
			<?php $this->render_content(); ?>
		</li><?php
	}

	/**
	 * Render the control's content.
	 * Allows the content to be overriden without having to rewrite the wrapper.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function render_content() {

		switch ( $this->type ) {

			case 'group' :

				switch ( $this->group ) {

					case 'start' :

						if ( ! empty( $this->label ) ) : ?>
							<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php endif;

						if ( ! empty( $this->description ) ) : ?>
							<span class="description customize-control-description"><?php echo $this->description ; ?></span>
						<?php endif;

						if ( ! empty( $this->sublabel ) ) : ?>
							<span class="description customize-control-sublabel"><?php echo $this->sublabel ; ?></span>
						<?php endif;

						$button = empty( $this->button ) ? __( 'Edit Group', 'hybrid-core' ) : $this->button;
						?>
						<div class="button hybridextend-flypanel-button" data-flypaneltype="group"><?php echo $button; ?></div>
					<?php
					break;

					case 'end' :
					break;

				}

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
function hybridextend_customize_group_control_interface ( $wp_customize, $id, $setting ) {
	if ( isset( $setting['type'] ) ) :
		switch( $setting['type'] ) {

			case 'group-start':
			case 'groupstart':
				$setting['type'] = 'group';
				$setting['group'] = 'start';
				$wp_customize->add_control(
					new HybridExtend_Customize_Group_Control( $wp_customize, $id, $setting )
				);
				break;

			case 'group-end':
			case 'groupend':
				$setting['type'] = 'group';
				$setting['group'] = 'end';
				$wp_customize->add_control(
					new HybridExtend_Customize_Group_Control( $wp_customize, $id, $setting )
				);
				break;

			case 'group':
			case 'groups':
				$setting['type'] = 'group';
				$wp_customize->add_control(
					new HybridExtend_Customize_Group_Control( $wp_customize, $id, $setting )
				);
				break;

		}
	endif;
}
add_action( 'hybridextend_customize_control_interface', 'hybridextend_customize_group_control_interface', 10, 3 );
endif;

/**
 * Modify the settings array and prepare group settings for Customizer Library Interface functions
 *
 * @since 2.0.0
 * @param array $value
 * @param string $key
 * @param array $setting
 * @param int $count
 * @return void
 */
function hybridextend_customize_prepare_group_settings( $value, $key, $setting, $count ) {

	if ( $setting['type'] == 'group' ) {

		$setting = wp_parse_args( $setting, array(
			'label'       => '',
			'section'     => '',
			'priority'    => '',
			'description' => '',
			'startwrap'   => '',
			'button'      => '',
			'options'     => array(),
			'identifier'  => $key,
			) );

		if( is_array( $setting['options'] ) && !empty( $setting['options'] ) ):

			$value[ "group-{$count}" ] = array(
				'label'       => $setting['label'],
				'section'     => $setting['section'],
				'type'        => 'group',
				'priority'    => $setting['priority'],
				'description' => $setting['description'],
				'startwrap'   => $setting['startwrap'],
				'button'      => $setting['button'],
				'identifier'  => $setting['identifier'],
				'group'       => 'start',
			);

			foreach ( $setting['options'] as $okey => $osetting ) {

				// Add priority & section same as group
				$osetting['priority'] = $setting['priority'];
				$osetting['section'] = $setting['section'];

				$value[ "{$key}-{$okey}" ] = $osetting;

			}

			$value[ "group-{$count}-end" ] = array(
				'section'     => $setting['section'],
				'type'        => 'group',
				'priority'    => $setting['priority'],
				'identifier'  => $setting['identifier'],
				'group'       => 'end',
			);

		endif;

	}

	return $value;

}
add_filter( 'hybridextend_customize_prepare_settings', 'hybridextend_customize_prepare_group_settings', 10, 4 );

/**
 * Add Content to Customizer Panel Footer
 *
 * @since 2.0.0
 * @return void
 */
// Only load in customizer (not in frontend)
if ( class_exists( 'WP_Customize_Control' ) ) :
function hybridextend_customize_footer_groupcontent() {

	?>
	<div id="hybridextend-flygroup" class="hybridextend-flypanel">
		<div class="hybridextend-flypanel-header hybridextend-flypanel-nav">
			<div class="primary-actions">
				<span class="hybridextend-flypanel-back" tabindex="-1"><span class="screen-reader-text"><?php _e( 'Back', 'hybrid-core' ) ?></span></span>
			</div>
		</div>
		<div id="hybridextend-flygroup-content" class="hybridextend-flypanel-content">
		</div>
		<div class="hybridextend-flypanel-footer hybridextend-flypanel-nav">
			<div class="primary-actions">
				<span class="hybridextend-flypanel-back" tabindex="-1"><span class="screen-reader-text"><?php _e( 'Back', 'hybrid-core' ) ?></span></span>
			</div>
		</div>
	</div><!-- .hybridextend-flypanel -->
	<?php

}
add_action( 'customize_controls_print_footer_scripts', 'hybridextend_customize_footer_groupcontent' );
endif;