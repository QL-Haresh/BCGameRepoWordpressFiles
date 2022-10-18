<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

$first_char = substr( $content, 0, 1 );
$rest_text = substr( $content, 1, strlen( $content ) );

return '<span class="shortcode-dropcap">' . $first_char . '</span>' . do_shortcode( $rest_text );