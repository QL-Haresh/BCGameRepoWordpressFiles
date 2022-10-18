"use strict";

(function ($) {

	if( 'undefined' == typeof hybridExtendData )
		window.hybridExtendData = {};

	/*** Scrollpoints ***/

	$.fn.hybridExtendScroller = function (options) {

		var scrollSpeed = 500,
			scrollPadding = 50;
		if( 'undefined' != typeof hybridExtendData && 'undefined' != typeof hybridExtendData.customScrollerSpeed )
			scrollSpeed = hybridExtendData.customScrollerSpeed;
		if( 'undefined' != typeof hybridExtendData && 'undefined' != typeof hybridExtendData.customScrollerPadding )
			scrollPadding = hybridExtendData.customScrollerPadding;

		// Options
		var settings = $.extend({
			urlLoad: false,                 // value: boolean
			speed: scrollSpeed,         // value: integer
			padding: scrollPadding,     // value: integer
		}, options);

		// Scroll to hash
		if (typeof scrollToHash == 'undefined') {
			var scrollToHash = function( target ) {
				target = target.replace("#scrollpoint-", "#"); // replace first occurence
				if ( target.length > 1 ){
					var $target = $( target );
					if ( $target.length ) {
						var destin = $target.offset().top - settings.padding;
						$("html:not(:animated),body:not(:animated)").animate({ scrollTop: destin}, settings.speed );
						return true;
					}
				}
				return false;
			};
		};

		return this.each(function () {
			if( 'undefined' == typeof hybridExtendData || 'undefined' == typeof hybridExtendData.scroller || 'enable' == hybridExtendData.scroller ) {

				if ( settings.urlLoad ) {
					if( 'undefined' == typeof hybridExtendData || 'undefined' == typeof hybridExtendData.scrollerPageLoad || 'enable' == hybridExtendData.scrollerPageLoad ) {
						var target = window.location.hash;
						if ( target ) {
							target = target.split("&");
							target = target[0].split("?");
							target = target[0].split("=");
							scrollToHash(target[0]);
						}
					}
				} else {

					var $self = $(this),
						executed = '',
						parseHref = $self.attr('href');

					if(typeof parseHref != 'undefined')
						parseHref = parseHref.replace(/#([A-Za-z0-9\-\_]+)/g,'#scrollpoint-$1');
					else
						return; // Bugfix: We can break the $.each() loop at a particular iteration by making the callback function return false. Returning non-false is the same as a continue statement in a for loop; it will skip immediately to the next iteration.

					// Add namespace so when new page is loaded, the hashtag has unique namespace (this ways the script overtakes browser behavior to scroll to hashtag on pageload)
					if( 'undefined' == typeof hybridExtendData || 'undefined' == typeof hybridExtendData.scrollerPageLoad || 'enable' == hybridExtendData.scrollerPageLoad )
						$self.attr('href', parseHref);

					$self.on('click', function(e) {
						// Only if href points to current url
						// $self.context.pathname is empty in IE11, so we need to scrape off this test
						// if ( $self.context.pathname == window.location.pathname ) {
							executed = '';
							if ( $self.context.hash )
								executed = scrollToHash( $self.context.hash );
							if ( !executed && $self.attr('data-scrollto') )
								executed = scrollToHash( $self.attr('data-scrollto') );
							if ( executed )
								e.preventDefault();
						// }
					});

				}

			}
		});

	};

}(jQuery));