<?php
// Return if no boxes to show
if ( empty( $boxes ) || !is_array( $boxes ) )
	return;

// Get border classes
$top_class = hoot_widget_border_class( $border, 0, 'topborder-');
$bottom_class = hoot_widget_border_class( $border, 1, 'bottomborder-');

// Get custom style attribute
$mt = ( !isset( $customcss['mt'] ) ) ? '' : $customcss['mt'];
$mb = ( !isset( $customcss['mb'] ) ) ? '' : $customcss['mb'];
$custommargin = hoot_widget_margin_style( $mt, $mb );

// Get total columns and set column counter
$columns = ( intval( $columns ) >= 1 && intval( $columns ) <= 5 ) ? intval( $columns ) : 3;
$column = $count = 1;

// Create Data attributes for javascript settings for number visual
$boxesatts = array();
$boxesatts['foregroundcolor'] = hoot_get_mod( 'accent_color' );
$boxesatts['backgroundcolor'] = hoot_get_mod( 'highlight_color' );
$boxesatts['foregroundborderwidth'] = 10;
$boxesatts['backgroundborderwidth'] = 10;
$boxesatts['fontcolor'] = hoot_get_mod( 'font_body-color' );
$boxesatts['percent'] = 100;
$boxesatts['animation'] = '1'; // bug: 0 or '' dont work. instead input random integer like 'no' to stop animation
$boxesatts['animationStep'] = 5;
$boxesatts['percentageTextSize'] = 33;
$boxesatts['textAdditionalCss'] = '';
?>

<div class="number-blocks-widget-wrap <?php echo sanitize_html_class( $top_class ); ?>">
	<div class="number-blocks-widget-box <?php echo sanitize_html_class( $bottom_class ); ?>">
		<div class="number-blocks-widget" <?php echo $custommargin; ?>>

			<?php
			/* Display Title */
			if ( $title )
				echo wp_kses_post( apply_filters( 'hoot_widget_title', $before_title . $title . $after_title, 'number-blocks', $title, $before_title, $after_title ) );
			?>

			<div class="flush-columns">
				<?php foreach ( $boxes as $key => $box ) : ?>
					<?php if ( $column == 1 ) echo '<div class="number-block-row">'; ?>
					<div class="number-block-column <?php echo "hcolumn-1-{$columns} number-block-{$count}"; $count++; ?>">
						<div class="number-block">

							<?php
							$box['number'] = intval( $box['number'] );
							$box['percent'] = intval( $box['percent'] );
							$showpercentsign = ( empty( $box['number'] ) );
							if ( empty( $box['number'] ) ) $box['number'] = $box['percent'];
							if ( empty( $box['percent'] ) ) $box['percent'] = 100;

							if ( !empty( $box['number'] ) ) :
								$atts = '';
								$boxatts = $boxesatts;
								$boxatts['percent'] = $box['percent'];
								$boxatts['display'] = $box['number'];
								$boxatts['displayprefix'] = !empty( $box['displayprefix'] ) ? $box['displayprefix'] : '';
								$boxatts['displaysuffix'] = !empty( $box['displaysuffix'] ) ? $box['displaysuffix'] : '';
								$boxatts['percentsign'] = ( $showpercentsign ) ? '%' : '';
								if ( !empty( $box['color'] ) ) $boxatts['foregroundcolor'] = $box['color'];

								$boxatts =  apply_filters( 'number_box_visual_atts', $boxatts );
								foreach ( $boxatts as $setting => $value )
									$atts .= ' data-' . $setting . '="' . esc_attr( $value ) . '"';

								?><div class="number-block-visual">
									<div class="number-block-circle" <?php echo $atts; ?>></div>
								</div><?php
							endif;
							?>

							<?php if ( !empty( $box['content'] ) ): ?>
								<div class="number-block-content <?php
									if ( !empty( $box['number'] ) ) echo 'number-block-content-hasvisual';
									else echo 'no-visual';
									?>">
									<?php echo wp_kses_post( wpautop( $box['content'] ) ); ?>
								</div>
							<?php endif; ?>

						</div>
					</div>

					<?php
					if ( $column == $columns ) {
						echo '</div>';
						$column = $clearfix = 1;
					} else {
						$clearfix = false;
						$column++;
					}
					?>
				<?php endforeach; ?>
				<?php if ( !$clearfix ) echo '</div>'; ?>
			</div>

		</div>
	</div>
</div>