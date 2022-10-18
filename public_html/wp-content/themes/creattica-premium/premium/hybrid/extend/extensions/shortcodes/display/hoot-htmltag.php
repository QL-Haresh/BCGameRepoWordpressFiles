<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

if ( empty( $tag ) ) $tag = 'div';
if ( empty( $class ) ) $class = '';
$style = ( empty( $style ) ) ? '' : strip_tags( $style );

return "<{$tag}" . hoot_sc_attr( 'class', $class ) . hoot_sc_attr( 'style', $style ) . ">" . do_shortcode( $content ) . "</{$tag}>";