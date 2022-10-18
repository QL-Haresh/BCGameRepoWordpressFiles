<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

/* Reset any previous tabsets */
global $hoot_theme;
$hoot_theme->tabset = array();

/* Generate tab data */
do_shortcode( $content );

/* Display Tabset */

$output = '';

$output .= '<div class="shortcode-tabset">';
if ( !empty( $hoot_theme->tabset ) ) :

	$current = 'class = "current"';
	$count = 1;
	$output .= '<ul class="shortcode-tabset-nav border-box">';
	foreach ( $hoot_theme->tabset as $tab ) {
		$output .= "<li {$current} data-tab='tab{$count}'>" . strip_tags( $tab['title'] ) . "</li>";
		$current = '';
		$count++;
	}
	$output .= '</ul>';

	$current = 'class = "current"';
	$count = 1;
	$output .= '<div class="shortcode-tabset-box border-box">';
	foreach ( $hoot_theme->tabset as $tab ) {
		$output .= "<div {$current} data-tab='tab{$count}'>" . do_shortcode( $tab['content'] ) . "</div>";
		$current = '';
		$count++;
	}
	$output .= '</div>';

endif;
$output .= '</div>';

return $output;