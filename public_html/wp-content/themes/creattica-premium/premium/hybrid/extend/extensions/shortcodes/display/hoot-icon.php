<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

if ( empty( $icon ) ) return '';
if ( !empty( $size ) ) $size = intval( $size );

$style = '';
if ( !empty( $color ) ) $style .= " color: " . hybridext_color_santize_hex( $color ) . ";";
if ( !empty( $size ) ) $style .= " font-size: " . $size . "px;";

return  '<i class="shortcode-icon ' . hybridextend_sanitize_fa( $icon ) . '" ' . hoot_sc_attr( 'style', $style ) . '></i>';