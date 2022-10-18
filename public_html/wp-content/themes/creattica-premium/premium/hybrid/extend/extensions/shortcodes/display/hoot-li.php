<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

global $hoot_theme;
$icon = ( empty( $hoot_theme->iconlist ) ) ? '' : '<i class="fa-li ' . hybridextend_sanitize_fa( $hoot_theme->iconlist ) . '"></i>';

return "<li>$icon" . do_shortcode( $content ) . "</li>";