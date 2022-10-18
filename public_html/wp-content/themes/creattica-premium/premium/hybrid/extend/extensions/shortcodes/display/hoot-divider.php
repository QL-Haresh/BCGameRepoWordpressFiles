<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

$scheme = 'highlight';
if ( !empty( $color ) ) {
	if ( !empty( $bright ) && 'yes' == $bright ) {
		$scheme = $color;
	} else {
		$scheme = $color . 'light';
	}
}

$class = "shortcode-divider style-" . sanitize_html_class( $scheme );

return  '<div ' . hoot_sc_attr( 'class', $class ) . '>' .
			( ( !empty( $top ) && ( 'yes' == $top || 'on' == $top || 'true' == $top || true === $top ) ) ?
				'<a href="#page-wrapper" class="style-' . sanitize_html_class( $scheme ) . '">' . __('Top', 'hybrid-core') . "</a>" :
				'' ) .
		'</div>';