<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

$boxicon = '';
$scheme = 'none';

// Type
if ( !empty( $type ) ) {
	switch ( $type ) {
		case 'success':	$boxicon = 'fa-check';
						$scheme = 'green';
						break;
		case 'warning':	$boxicon = 'fa-exclamation-triangle';
						$scheme = 'orange';
						break;
		case 'error':	$boxicon = 'fa-times';
						$scheme = 'red';
						break;
		case 'info':	$boxicon = 'fa-info-circle';
						$scheme = 'blue';
						break;
		case 'note':	$boxicon = 'fa-pencil';
						$scheme = 'yellow';
						break;
		case 'flag':	$boxicon = 'fa-flag';
						$scheme = 'amber';
						break;
		case 'pushpin':	$boxicon = 'fa-thumb-tack';
						$scheme = 'cyan';
						break;
		case 'setting':	$boxicon = 'fa-cog';
						$scheme = 'white';
						break;
	}
}

// Color Scheme
if ( !empty( $color ) )
	$scheme = $color;

// Icon
if ( !empty( $icon ) )
	$boxicon = $icon;

// Display
$class = "shortcode-box border-box style-" . sanitize_html_class( $scheme ) . "light table";
if ( !empty( $boxicon ) ) $boxicon = "<div class='shortcode-box-icon table-cell-mid'><i class='" . hybridextend_sanitize_fa( $boxicon ) . "'></i></div>";
$style = '';
if ( !empty( $background ) ) $style .= " background: " . hybridext_color_santize_hex( $background ) . ";";
if ( !empty( $text ) )       $style .= " color: " . hybridext_color_santize_hex( $text ) . ";";

return '<div ' . hoot_sc_attr( 'class', $class ) . hoot_sc_attr( 'style', $style ) . '>' .
			$boxicon .
			'<div class="shortcode-box-content table-cell-mid">' .
				do_shortcode( $content ) .
			'</div>' .
		'</div>';