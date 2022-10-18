<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

if( ( !empty( $last ) && ( 'yes' == $last || 'on' == $last || 'true' == $last || true === $last ) ) ) {
	$clearfix = '<div class="clearfix"></div>';
	$lastclass = ' last';
} else $clearfix = $lastclass = '' ;

return '<div class="hcolumn-3-4 shortcode-column' . $lastclass . '">' . do_shortcode( $content ) . '</div>' . $clearfix;