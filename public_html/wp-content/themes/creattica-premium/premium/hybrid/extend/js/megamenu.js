jQuery(document).ready(function($) {
	"use strict";

	$('#menu-to-edit').on('sortstop', function( event, ui ) {

		var menuItem = ui.item.eq(0),
			menuItemUpdated = ui.placeholder.eq(0);

		if( menuItemUpdated.hasClass('menu-item-depth-0') )
			menuItem.find('p.hybridext_top_level_only').show();
		else
			menuItem.find('p.hybridext_top_level_only').hide();

	});

	$.fn.hybridextWidgetIconPicker = function() {
		return this.each(function(i,el) {
			var $self       = $(this);
			if ( $self.data('hybridext-icon-setup') === true ) return true;
			else $self.data('hybridext-icon-setup', true);

			var $picker_box = $self.siblings('.hybridext-icon-picker-box'),
				$button     = $self.siblings('.hybridext-icon-picked'),
				$preview    = $button.children('i'),
				$icons      = $picker_box.find('i');

			$button.on( "click", function() {
				$picker_box.toggleClass( 'hybridextshow' );
			});

			$icons.on( "click", function() {
				var iconvalue = $(this).data('value');
				$icons.removeClass('selected');
				var selected = ( ! $(this).hasClass('cmb-icon-none') ) ? 'selected' : '';
				$(this).addClass(selected);
				$preview.removeClass().addClass( selected + ' ' + iconvalue );
				$self.val(iconvalue);
				$self.trigger('change');
				$picker_box.removeClass( 'hybridextshow' );
			});

		});
	};

	$('#menu-to-edit .hybridext-icon').hybridextWidgetIconPicker();
	// $('#menu-to-edit').bind("DOMNodeInserted",function(){
	// 	$('#menu-to-edit .hybridext-icon').hybridextWidgetIconPicker();
	// });
	// console.log(wpNavMenu);
	$( document ).on('menu-item-added', function() {
		$('#menu-to-edit .hybridext-icon').hybridextWidgetIconPicker();
	});

});