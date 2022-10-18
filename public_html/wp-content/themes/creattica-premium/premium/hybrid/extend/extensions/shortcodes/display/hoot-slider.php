<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

/* Do nothing if we dont have an id */
$id = intval( $id );
if ( empty( $id ) )
	return;

/* Get slider type to display */
$type = hoot_get_meta_option( 'type', $id );

/* Reset any previous slider */
global $hoot_theme;
$hoot_theme->slider = array();
$hoot_theme->sliderSettings = array();

/* Create slider settings object */
$hoot_theme->sliderSettings['source'] = 'shortcode';
$hoot_theme->sliderSettings['slider_id'] = $id;
$hoot_theme->sliderSettings['type'] = $type;
$hoot_theme->sliderSettings['class'] = 'shortcode-hootslider';
$hoot_theme->sliderSettings['auto'] = ( hoot_get_meta_option( 'auto', $id ) ) ? 'true' : 'false';
$hoot_theme->sliderSettings['pause'] = hoot_get_meta_option( 'pause', $id );
if ( 'html' == $type ) {
	$hoot_theme->sliderSettings['min_height'] = hoot_get_meta_option( 'minheight', $id );
}
if ( 'image' == $type || 'carousel' == $type ) {
	// if ( hoot_get_meta_option( 'adaptiveheight', $id ) ) {
		$hoot_theme->sliderSettings['adaptiveheight'] = 'true'; // Default Setting
	// } else {
	// 	$hoot_theme->sliderSettings['adaptiveheight'] = 'false';
	// 	$hoot_theme->sliderSettings['class'] .= ' fixedheight';
	// }
}
if ( 'carousel' == $type ) {
	$hoot_theme->sliderSettings['item'] = hoot_get_meta_option( 'item', $id );
}
$hoot_theme->sliderSettings['display'] = hoot_get_meta_option( 'display', $id );
$hoot_theme->sliderSettings['posts'] = intval( hoot_get_meta_option( 'posts', $id ) );
$hoot_theme->sliderSettings['category'] = intval( hoot_get_meta_option( 'category', $id ) );
$hoot_theme->sliderSettings['content_bg'] = hoot_get_meta_option( 'content_bg', $id );
$hoot_theme->sliderSettings['html_content_type'] = hoot_get_meta_option( 'html_content_type', $id );
$hoot_theme->sliderSettings['excerptlength'] = intval( hoot_get_meta_option( 'excerptlength', $id ) );
// https://github.com/sachinchoolur/lightslider/issues/118
// https://github.com/sachinchoolur/lightslider/issues/119#issuecomment-93283923
$hoot_theme->sliderSettings['slidemove'] = '1';

/* Set slider template location */
$hoot_theme->sliderSettings['template'] = "template-parts/slider-{$type}";

/* Let developers alter slider */
do_action( 'hoot_slidersettings_loaded', $type );

/* Create slider object */
if ( $hoot_theme->sliderSettings['display'] == 'posts' ) :

	// Temporarily remove read more links from excerpts
	if ( 'carousel' != $type ) {
		hoot_remove_readmore_link();
	};

	$args = array();
	if ( !empty( $hoot_theme->sliderSettings['posts'] ) )
		$args['posts_per_page'] = $hoot_theme->sliderSettings['posts'];
	if ( !empty( $hoot_theme->sliderSettings['category'] ) )
		$args['category'] = $hoot_theme->sliderSettings['category'];
	$recentposts = get_posts( apply_filters( 'hoot_slider_recentposts_args', $args ) );

	$counter = 0;
	$slider = array();
	global $post;
	foreach ( $recentposts as $post ) : setup_postdata( $post );
		$key = 'g' . $counter;
		$counter++;

		// $slideimage = ( has_post_thumbnail( $post->ID ) ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ) : '';
		// $slider[$key]['image'] = ( !empty( $slideimage[0] ) ) ? $slideimage[0] : '';
		$slider[$key]['image'] = ( has_post_thumbnail( $post->ID ) ) ? get_post_thumbnail_id( $post->ID ) : '';
		$slider[$key]['url'] = esc_url( get_permalink( $post->ID ) );

		switch ( $type ) {

			case 'image':
				$slider[$key]['caption'] = get_the_title( $post->ID );
				$slider[$key]['caption_bg'] = $hoot_theme->sliderSettings['content_bg'];
				// $slider[$key]['button']
				break;

			case 'html':
				$slideimage = wp_get_attachment_image_src( $slider[$key]['image'], 'full' );
				$slider[$key]['background']['type'] = 'custom';
				$slider[$key]['background']['image'] = ( !empty( $slideimage[0] ) ) ? $slideimage[0] : '';
				$slider[$key]['image'] = '';

				$slider[$key]['content'] = '<h3>' . get_the_title( $post->ID ) . '</h3>';
				if ( $hoot_theme->sliderSettings['html_content_type'] == 'excerpt') {
					if ( empty( $hoot_theme->sliderSettings['excerptlength'] ) )
						$slider[$key]['content'] .= get_the_excerpt();
					else
						$slider[$key]['content'] .= hybridextend_get_excerpt( $hoot_theme->sliderSettings['excerptlength'] );
				} elseif ( $hoot_theme->sliderSettings['html_content_type'] == 'content') {
					$slider[$key]['content'] .= get_the_content();
				}
				$slider[$key]['content_bg'] = $hoot_theme->sliderSettings['content_bg'];

				$read_more = esc_html( hoot_get_mod('read_more') );
				$slider[$key]['button'] = ( empty( $read_more ) ) ? sprintf( __( 'Read More %s', 'hybrid-core' ), '&rarr;' ) : $read_more;
				break;

			case 'carousel':
				$slider[$key]['content'] = '<h3 class="carousel-post-title"><a href="' . $slider[$key]['url'] . '">' . get_the_title( $post->ID ) . '</a></h3>';
				if ( $hoot_theme->sliderSettings['html_content_type'] == 'excerpt') {
					if ( empty( $hoot_theme->sliderSettings['excerptlength'] ) )
						$slider[$key]['content'] .= get_the_excerpt();
					else
						$slider[$key]['content'] .= hybridextend_get_excerpt( $hoot_theme->sliderSettings['excerptlength'] );
				} elseif ( $hoot_theme->sliderSettings['html_content_type'] == 'content') {
					$slider[$key]['content'] .= get_the_content();
				}
				break;
		}

	endforeach; 
	wp_reset_postdata();

	$hoot_theme->slider = $slider;
	do_action( 'hoot_scsliderposts_object', $type, $id, $args );

	// Reinstate read more links to excerpts
	if ( 'carousel' != $type ) {
		hoot_reinstate_readmore_link();
	};

else:

	switch ( $type ) {
		case 'image':
			$hoot_theme->slider = hoot_get_meta_option( 'image_slider', $id );
			break;
		case 'html':
			$hoot_theme->slider = hoot_get_meta_option( 'html_slider', $id );
			break;
		case 'carousel':
			$hoot_theme->slider = hoot_get_meta_option( 'carousel_slider', $id );
			break;
	}

endif;

/* Let developers alter slider */
do_action( 'hoot_slider_loaded', $type, $id );

/* Finally get Slider Template HTML */
ob_start();
get_template_part( $hoot_theme->sliderSettings['template'] );
return ob_get_clean();