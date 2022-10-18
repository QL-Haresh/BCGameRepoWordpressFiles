jQuery(document).ready(function($) {
	"use strict";

	/*** Init lightslider ***/

	if( 'undefined' == typeof hootData || 'undefined' == typeof hootData.lightSlider || 'enable' == hootData.lightSlider ) {
		if (typeof $.fn.lightSlider != 'undefined') {
			$(".lightSlider").each(function(i){
				var self = $(this),
					settings = {
						item: 1,
						slideMove: 1, // https://github.com/sachinchoolur/lightslider/issues/118
						slideMargin: 0,
						mode: "slide",
						auto: true,
						loop: true,
						slideEndAnimatoin: false,
						slideEndAnimation: false,
						pause: 5000,
						adaptiveHeight: true,
						},
					selfData = self.data(),
					responsiveitem = (parseInt(selfData.responsiveitem)) ? parseInt(selfData.responsiveitem) : 2,
					breakpoint = (parseInt(selfData.breakpoint)) ? parseInt(selfData.breakpoint) : 960,
					customs = {
						item: selfData.item,
						slideMove: selfData.slidemove,
						slideMargin: selfData.slidemargin,
						mode: selfData.mode,
						auto: selfData.auto,
						loop: selfData.loop,
						slideEndAnimatoin: selfData.slideendanimation,
						slideEndAnimation: selfData.slideendanimation,
						pause: selfData.pause,
						adaptiveHeight: selfData.adaptiveheight,
						};
				$.extend(settings,customs);
				if( settings.item >= 2 ) { /* Its a carousel */
					settings.responsive =  [ {	breakpoint: breakpoint,
												settings: {
													item: responsiveitem,
													}
												}, ];
				}
				self.lightSlider(settings);
			});
		}
	}

	/*** Superfish Navigation ***/

	if( 'undefined' == typeof hootData || 'undefined' == typeof hootData.superfish || 'enable' == hootData.superfish ) {
		if (typeof $.fn.superfish != 'undefined') {
			$('.sf-menu').superfish({
				delay: 1000,					// delay on mouseout 
				animation: {height: 'show'},	// animation for submenu open. Do not use 'toggle' #bug
				animationOut: {opacity:'hide'},	// animation for submenu hide
				speed: 200,						// faster animation speed 
				speedOut: 'fast',				// faster animation speed
				disableHI: false,				// set to true to disable hoverIntent detection // default = false
			});
		}
	} else {
		$('.menu-items > .menu-item-has-children').on( 'click', function(e){
			$(this).siblings().find('.sub-menu').slideUp('fast').removeClass('navitemOpen').siblings('a').removeClass('navitemOpen');
		});
		$('.menu-item-has-children > a').on( 'click', function(e){
			e.preventDefault();
			$(this).toggleClass('navitemOpen').siblings('.sub-menu').slideToggle('fast').toggleClass('navitemOpen');
		});
		// Open state by default for current page
		$('.current-menu-item').parents('ul.sub-menu').show().addClass('navitemOpen').siblings('a').addClass('navitemOpen');
	}

	/*** Responsive Navigation ***/

	if( 'undefined' == typeof hootData || 'undefined' == typeof hootData.menuToggle || 'enable' == hootData.menuToggle ) {
		$( '.menu-toggle' ).click( function() {
			if ( $(this).parent().is('.mobilemenu-fixed') )
				$(this).parent().toggleClass( 'mobilemenu-open' );
			else
				$( this ).siblings( '.wrap, .menu-items' ).slideToggle();
			$( this ).toggleClass( 'active' );
		});
		$('body').click(function (e) {
			if (!$(e.target).is('.nav-menu *, .nav-menu'))
				$( '.menu-toggle.active' ).click();
		});
	}

	/*** Header Serach ***/
	var $headerSearchContainer = $('.header-aside-search');
	if ($headerSearchContainer.length) {
		$('.header-aside-search i.fa-search').on('click', function(){
			$headerSearchContainer.toggleClass('expand');
		});
	}

	/*** Responsive Videos : Target your .container, .wrapper, .post, etc. ***/

	if( 'undefined' == typeof hootData || 'undefined' == typeof hootData.fitVids || 'enable' == hootData.fitVids ) {
		if (jQuery.fn.fitVids)
			$("#content").fitVids();
	}

	/*** Responsive Leftbar Bottom Area ***/

	var $leftbarBottom = $('#leftbar-bottom.mobile-bottom'),
		$header        = $('#header'),
		$footer        = $('#footer');

	function mediaqueryDesktop(mql){
		if (mql.matches)
			$leftbarBottom.insertAfter( $header );
	}

	function mediaqueryMobile(mql){
		if (mql.matches)
			$leftbarBottom.insertAfter( $footer );
	}

	if($leftbarBottom.length) {
		var mqlDesktop = window.matchMedia( 'only screen and (min-width: 970px)' ),
			mqlMobile  = window.matchMedia( 'only screen and (max-width: 969px)' );
		mqlDesktop.addListener(mediaqueryDesktop);
		mqlMobile.addListener(mediaqueryMobile);
		mediaqueryDesktop(mqlDesktop); // call listener function explicitly at run time
		mediaqueryMobile(mqlMobile); // call listener function explicitly at run time
	}

});