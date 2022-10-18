<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

global $hoot_theme;

$hoot_theme->tabset[] = array(
	'title' => ( ( empty( $title ) ) ? '-' : $title ),
	'content' => $content
	);

return '';