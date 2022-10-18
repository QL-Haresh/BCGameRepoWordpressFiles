<?php
/**
 * General Variables available: $name, $params, $args, $content
 * $args has been 'extract'ed
 */

return '<pre>' . htmlspecialchars( strip_tags($content) ) . '</pre>';