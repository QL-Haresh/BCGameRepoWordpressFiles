<?php
global $hoot_theme;

if ( !isset( $hoot_theme->slider ) || empty( $hoot_theme->slider ) )
	return;

// Ok, so we have a slider to show. Now, lets display the slider.

/* Let developers alter slider via global $hoot_theme */
do_action( 'hoot_slider_start', 'image' );

/* Create Data attributes for javascript settings for this slider */
$atts = $class = '';
if ( isset( $hoot_theme->sliderSettings ) && is_array( $hoot_theme->sliderSettings ) ) {
	if ( isset( $hoot_theme->sliderSettings['class'] ) )
		$class .= ' ' . sanitize_html_class( $hoot_theme->sliderSettings['class'] );
	if ( isset( $hoot_theme->sliderSettings['id'] ) )
		$atts .= ' id="' . sanitize_html_class( $hoot_theme->sliderSettings['id'] ) . '"';
	foreach ( $hoot_theme->sliderSettings as $setting => $value )
		$atts .= ' data-' . $setting . '="' . esc_attr( $value ) . '"';
}

/* Start Slider Template */
$slide_count = 1; ?>
<div class="hootslider-image-wrapper">
<ul class="lightSlider<?php echo $class; ?>"<?php echo $atts; ?>><?php
	foreach ( $hoot_theme->slider as $key => $slide ) :
		$hoot_theme->slider[$key]['status'] = 'current';

		$slide = wp_parse_args( $slide, array(
			'image' => '',
			'caption' => '',
			'caption_bg' => 'dark-on-light',
			'button' => '',
			'url' => '',
		) );

		if ( !empty( $slide['image'] ) ) :

			?><li class="lightSlide hootslider-image-slide hootslider-image-slide-<?php echo $slide_count; $slide_count++; ?>">

				<?php if ( !empty( $slide['url'] ) && empty( $slide['button'] ) )
					echo '<a href="' . esc_url( $slide['url'] ) . '" ' . hybridextend_get_attr( 'hootslider-image-slide-link' ) . '>'; ?>
					<?php $intimageid = intval( $slide['image'] );
					$imageid = ( !empty( $intimageid ) && is_numeric( $slide['image'] ) ) ? $slide['image'] : hybridextend_get_attachid_url( $slide['image'] );
					if ( !empty( $imageid ) )
						echo wp_get_attachment_image( $imageid, apply_filters( 'hoot_imageslider_imgsize', 'full' ), '', array( 'class' => 'hootslider-image-slide-img' ) );
					else
						echo '<img class="hootslider-image-slide-img" src="' . esc_url( $slide['image'] ) . '" />';
					?>
				<?php if ( !empty( $slide['url'] ) && empty( $slide['button'] ) )
					echo '</a>'; ?>

				<div class="hootslider-image-slide-content">
					<?php if ( !empty( $slide['caption'] ) ) : ?>
						<?php $caption_class = 'titlefont style-' . sanitize_html_class( $slide['caption_bg'] ); ?>
						<div <?php hybridextend_attr( 'hootslider-image-slide-caption', '', $caption_class ) ?>><?php echo wp_kses_post( wpautop( $slide['caption'] ) ); ?></div>
					<?php endif; ?>
					<?php if ( !empty( $slide['url'] ) && !empty( $slide['button'] ) ) : ?>
						<a href="<?php echo esc_url( $slide['url'] ) ?>" <?php hybridextend_attr( 'hootslider-image-slide-button', 'image-slider', 'button button-small' ); ?>><?php echo esc_html( $slide['button'] ) ?></a>
					<?php endif; ?>
				</div>

			</li><?php

		endif;
		unset( $hoot_theme->slider[$key]['status'] );
	endforeach; ?>
</ul>
</div>