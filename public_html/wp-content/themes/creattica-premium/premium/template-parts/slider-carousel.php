<?php
global $hoot_theme;

if ( !isset( $hoot_theme->slider ) || empty( $hoot_theme->slider ) )
	return;

// Ok, so we have a slider to show. Now, lets display the slider.

/* Let developers alter slider via global $hoot_theme */
do_action( 'hoot_slider_start', 'carousel' );

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
<div class="hootslider-carousel-wrapper">
<ul class="lightSlider<?php echo $class; ?>"<?php echo $atts; ?>><?php
	foreach ( $hoot_theme->slider as $key => $slide ) :
		$hoot_theme->slider[$key]['status'] = 'current';

		$slide = wp_parse_args( $slide, array(
			'image' => '',
			'content' => '',
			'url' => '',
		) );

		if ( !empty( $slide['content'] ) || !empty( $slide['image'] ) ) : ?>
			<li class="lightSlide hootslider-carousel-slide hootslider-carousel-slide-<?php echo $slide_count; $slide_count++; ?>">
				<div class="lightSlideCarousel">

					<?php if ( !empty( $slide['image'] ) ) { ?>
						<div class="hootslider-carousel-slide-image">
							<?php if ( !empty( $slide['url'] ) )
								echo '<a href="' . esc_url( $slide['url'] ) . '" ' . hybridextend_get_attr( 'hootslider-carousel-slide-link' ) . '>'; ?>
							<?php $intimageid = intval( $slide['image'] );
							$imageid = ( !empty( $intimageid ) && is_numeric( $slide['image'] ) ) ? $slide['image'] : hybridextend_get_attachid_url( $slide['image'] );
							if ( !empty( $imageid ) )
								echo wp_get_attachment_image( $imageid, apply_filters( 'hoot_carouselslider_imgsize', 'hoot-preview' ), '', array( 'class' => 'hootslider-carousel-slide-img' ) );
							else
								echo '<img class="hootslider-carousel-slide-img" src="' . esc_url( $slide['image'] ) . '">';
							?>
							<?php if ( !empty( $slide['url'] ) )
								echo '</a>'; ?>
						</div>
					<?php } ?>

					<?php if ( !empty( $slide['content'] ) ) { ?>
						<div <?php hybridextend_attr( 'hootslider-carousel-slide-content', '', '' ); ?>>
							<?php echo wp_kses_post( wpautop( $slide['content'] ) ); ?>
						</div>
					<?php } ?>

				</div>
			</li><?php

		endif;
		unset( $hoot_theme->slider[$key]['status'] );
	endforeach; ?>
</ul>
</div>