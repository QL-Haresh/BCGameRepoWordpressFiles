<?php
/**
 * Functions for Options page
 * (available both frontend and backend)
 *
 * @package    HybridExtend
 * @subpackage HybridHoot
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Utility function to map a sortlist option value to Sort and Display arrays
 * 
 * Function for options. Similar function exists for customizer as well
 * 
 * @since 1.0.0
 * @access public
 * @param array $value
 * @param array $sanitize_array Optional array to sanitize return values
 * @param string $return Can have value 'order' or 'display' or empty to return an array of both
 * @return void
 */
function hoot_map_sortlist( $value, $sanitize_array = array(), $return = '' ) {
	$list = array(
		'order' => array(),
		'display' => array(),
		);
	if ( !is_array( $value ) )
		return $list;

	foreach( $value as $key => $val ) {
		$valparts = explode( ",", trim( $val ) );

		if ( !empty( $sanitize_array ) )
			$sanitzed = ( isset ( $sanitize_array[ $key ] ) ) ? true : false;
		else
			$sanitzed = true;

		if ( $sanitzed ) {
			$list['order'][ $key ] = intval( $valparts[0] );
			$list['display'][ $key ] = intval( $valparts[1] );
		}
	};

	asort( $list['order'] );

	if ( 'order' == $return )
		return $list['order'];
	elseif ( 'display' == $return )
		return $list['display'];
	else
		return $list;
}

/**
 * A special sorting function for usort'ing sortlist option types
 * 
 * @since 1.0.0
 */
function hoot_sort_slarray( $a, $b ) {
	$a = explode( ",", $a );
	$b = explode( ",", $b );
	$c1 = intval( $a[0] );
	$c2 = intval( $b[0] );
	if ( $c1 == $c2 )
		return 0;
	return ( $c1 < $c2 ) ? -1 : 1;
}