/**
 * Theme Customizer enhancements for a better user experience.
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	/*** Site title and description. ***/

	// wp.customize( 'blogname', function( value ) {
	// 	value.bind( function( newval ) {
	// 		$( '.site-logo-text #site-title a' ).html( newval );
	// 	} );
	// } );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '#site-description' ).html( newval );
		} );
	} );

} )( jQuery );
