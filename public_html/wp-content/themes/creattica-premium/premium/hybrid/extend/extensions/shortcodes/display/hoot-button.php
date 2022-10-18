<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

if ( empty( $url ) ) $url = '';
if ( empty( $target ) ) $target = 'self';
if ( empty( $size ) ) $size = 'medium';
if ( empty( $color ) ) $color = 'accent';

$class = "shortcode-button button border-box " . esc_attr( "button-$size size-$size style-$color" );
if ( !empty( $align ) ) $class .= " align" . esc_attr( $align );

$style = '';
if ( !empty( $background ) ) $style .= " background: " . hybridext_color_santize_hex( $background ) . ";";
if ( !empty( $text ) )       $style .= " color: " . hybridext_color_santize_hex( $text ) . ";";

return '<a href="' . esc_url( $url ) . '" ' . hoot_sc_attr( 'target', $target ) . hoot_sc_attr( 'class', $class ) . hoot_sc_attr( 'style', $style ) . '>' . strip_tags( $content ) . '</a>';