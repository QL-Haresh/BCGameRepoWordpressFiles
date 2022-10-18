<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

/* Reset any previous lists */
global $hoot_theme;
$hoot_theme->iconlist = '';

if ( !empty( $icon ) ) {
	$class = ' class="fa-ul"';
	$hoot_theme->iconlist = $icon;
}

return "<ul$class>" . do_shortcode( str_replace( array( '[/hoot_li]<br />', '[/hoot_li]<br>' ), '[/hoot_li]', $content ) ) . "</ul>";