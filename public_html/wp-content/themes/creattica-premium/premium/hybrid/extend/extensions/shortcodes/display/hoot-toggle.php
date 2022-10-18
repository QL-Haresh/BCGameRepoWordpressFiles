<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

if ( empty( $title ) )
	$title = '';
if ( !empty( $open ) && ( 'yes' == $open || 'on' == $open || 'true' == $open || true === $open ) ) {
	$icon = '<i class="fas fa-minus"></i>';
	$headclass = ' shortcode-toggle-active';
	$boxclass = '';
} else {
	$icon = '<i class="fas fa-plus"></i>';
	$headclass = '';
	$boxclass = ' hide';
}

return "<div class='shortcode-toggle'>" .
			"<div class='shortcode-toggle-head{$headclass}'>" . $icon . strip_tags( $title ) . "</div>" .
			"<div class='shortcode-toggle-box{$boxclass}'>" . do_shortcode( $content ) . "</div>" .
		"</div>";