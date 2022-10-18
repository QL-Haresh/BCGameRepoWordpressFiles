<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

if ( empty( $color ) ) $color = 'none';
$class = "shortcode-highlight style-" . esc_attr( $color ) . "light";

return '<span ' . hoot_sc_attr( 'class', $class ) . '>' . do_shortcode( $content ) . '</span>';