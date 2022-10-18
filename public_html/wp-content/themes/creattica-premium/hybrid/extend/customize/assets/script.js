jQuery(document).ready(function($) {
	"use strict";


	/*** Sortlist Control ***/

	$('ul.hybridextend-control-sortlist').each(function(){

		/** Prepare Sortlist **/

		var $self = $(this),
			openstate = $self.data('openstate'),
			$listItems = $self.children('li'),
			$listItemHeads = $listItems.children('.hybridextend-sortlistitem-head'),
			$listItemVisibility = $listItemHeads.children('.sortlistitem-display'),
			$listItemOptions = $listItems.children('.hybridextend-sortlistitem-options');

		$listItemHeads.on('click', function(e){
			$(this).children('.sortlistitem-expand').toggleClass('options-open');
			$(this).siblings('.hybridextend-sortlistitem-options').slideToggle('fast');
		});

		if ( openstate ) {
			if ( openstate != 'all' ) {
				$listItemOptions.hide();
				$listItems.filter('[data-choiceid="' + openstate + '"]').children('.hybridextend-sortlistitem-head').click();
			}
		} else $listItemOptions.hide();

		$listItemVisibility.on('click', function(e){
			e.stopPropagation();
			var $liContainer = $(this).closest('li.hybridextend-control-sortlistitem');
			$liContainer.toggleClass('deactivated');
			var hideValue = ( $liContainer.is('.deactivated') ) ? '1' : '0';
			$(this).siblings('input.hybridextend-control-sortlistitem-hide').val(hideValue).trigger('change');
		});

		/** Sortlist Control **/

		var $optionsform = $self.find('input, textarea, select'),
			$input = $self.siblings('input.hybridextend-customize-control-sortlist'),
			updateSortable = function(){
				$optionsform = $self.find('input, textarea, select'); // Get updated list item order
				// JSON.stringify( $optionsform.serializeArray() ) :: serializeArray does not create a multidimensional array. It simpy creates array with name/value pairs
				// Hence use $optionsform.serialize(). For more notes on this issue, see php file.
				$input.val( $optionsform.serialize() ).trigger('change');
			};

		$optionsform.on('change', updateSortable);

		if ( $self.is('.sortable') ) {
			$self.sortable({
				handle: ".sortlistitem-sort",
				placeholder: "hybridextend-control-sortlistitem-placeholder",
				update: function(event, ui) {
					updateSortable();
				},
				// start: function(e, ui){
				// 	ui.placeholder.height(ui.item.height());
				// },
				forcePlaceholderSize: true,
			});
		}

	});


	/*** Radioimage Control ***/

	$('.customize-control-radioimage, .hybridextend-sortlistitem-option-radioimage').each(function(){

		var $radios = $(this).find('input'),
			$labels = $(this).find('.hybridextend-customize-radioimage');

		$radios.on('change',function(){
			$labels.removeClass('radiocheck');
			$(this).parent('.hybridextend-customize-radioimage').addClass('radiocheck');
		});

	});


	/*** Icon Control ***/

	if ( (typeof _hybridextend_customize_data != 'undefined') && (typeof _hybridextend_customize_data.iconslist != 'undefined') ) {

		/** Fly Icon **/

		var $body = $('body'),
			$flyicon = $('#hybridextend-flyicon-content');

		$body.on( "openflypanel", function() {
			var $flypanelbutton = $body.data('flypanelbutton');
			if( $flypanelbutton && $flypanelbutton.data('flypaneltype')=='icon' && $flypanelbutton.data('flypanel')=='open' ) {

				$flyicon.html( _hybridextend_customize_data.iconslist ).data('controlgroup', $flypanelbutton);

				var $flyiconIcons = $flyicon.find('i'),
					$input = $flypanelbutton.siblings('input.hybridextend-customize-control-icon'),
					selected = $input.val(),
					$icondisplay = $flypanelbutton.children('i');

				$flypanelbutton.addClass('flygroup-open');

				if(selected)
					$flyicon.find('i.'+selected.replace(' ', '.')).addClass('selected');

				$flyiconIcons.click( function(event){
					var iconvalue = $(this).data('value');
					$flyiconIcons.removeClass('selected');
					$(this).addClass('selected');
					$input.val( iconvalue ).trigger('change');
					$icondisplay.removeClass().addClass(iconvalue );
					$('.hybridextend-flypanel-back').trigger('click');
				});

				$body.addClass('hybridextend-displaying-flyicon');
				$body.data('flypaneltype','icon');
			}
		});

		$body.on( "closeflypanel", function() {
			$body.removeClass('hybridextend-displaying-flyicon');
			var controlGroup = $flyicon.data('controlgroup');
			if (controlGroup)
				$(controlGroup).removeClass('flygroup-open');
			if($body.data('flypaneltype')=='icon') {
				$body.data('flypaneltype','');
			}
		});

		$('.hybridextend-customize-control-icon-remove').click( function(event){
			var input = $(this).siblings('input.hybridextend-customize-control-icon'),
				icondisplay = $(this).siblings('.hybridextend-customize-control-icon-picked').children('i');
			input.val('').trigger('change');
			icondisplay.removeClass();
			// $('.hybridextend-flypanel-back').trigger('click'); // redundant
		});

	}


	/*** Group Control ***/

	/** Prepare Groups **/

	$( ".hybridextend-customize-control-groupstart" ).each( function( index ) {
		var id = $(this).attr('id'),
			moveBlocks = $(this).nextUntil( '.hybridextend-customize-control-groupend', "li" );
		moveBlocks.addClass('hybridextend-customize-control-group-blocks').attr('data-controlgroup', id);
	});


	/** Fly Groups **/

	var $body = $('body');

	$body.on( "openflypanel", function() {
		var $flypanelbutton = $body.data('flypanelbutton');
		if( $flypanelbutton && $flypanelbutton.data('flypaneltype')=='group' && $flypanelbutton.data('flypanel')=='open' ) {
			var $groupstart = $flypanelbutton.parent('.hybridextend-customize-control-groupstart');
			$groupstart.addClass('flygroup-open');
			var moveBlocks = $groupstart.nextUntil( '.hybridextend-customize-control-groupend', "li" );
			$('#hybridextend-flygroup-content').html('').append(moveBlocks).wrapInner('<ul></ul>');
			$body.addClass('hybridextend-displaying-flygroup');
			$body.data('flypaneltype','group');
		}
	});

	$body.on( "closeflypanel", function() {
		$body.removeClass('hybridextend-displaying-flygroup');
		if($body.data('flypaneltype')=='group') {
			$('#hybridextend-flygroup-content > ul > li').each( function() {
				var controlGroup = $(this).data('controlgroup');
				$(this).insertBefore('#'+controlGroup+'-end');
				$('#'+controlGroup).removeClass('flygroup-open');
			});
			$body.data('flypaneltype','');
		}
	});


	/*** Multi Check Boxes ***/

	$('.customize-control-bettercheckbox .bettercheckbox-multi').each(function(){

		var $control = $(this),
			$multi = $control.find('input[type="checkbox"]'),
			$input = $control.find('input[type="hidden"]');

		$multi.on('change', function(){
			var multiValues = $multi.filter(':checked').map(function(){
				return this.value;
			}).get().join(',');
			$input.val(multiValues).trigger('change');
		});

	});


	/*** Betterbackground Control ***/

	$('.hybridextend-customize-control-betterbackgroundstart').each(function( index ){

		var $blocks = $(this).nextUntil( '.hybridextend-customize-control-betterbackgroundend', "li" ),
			$bbButtons = $blocks.filter('.hybridextend-customize-control-betterbackgroundbutton'),
			$buttons = $bbButtons.find('.hybridextend-betterbackground-button'),
			$typeInput = $bbButtons.find('input.hybridextend-customize-control-betterbackground'),
			$customs = $blocks.filter('.customize-control-color, .customize-control-image, .customize-control-select'),
			$predefineds = $blocks.filter('.customize-control-color, .hybridextend-customize-control-groupstart'),
			showBlocks = function(control){
				if ( control == 'predefined' ) {
					$customs.hide();
					$predefineds.show();
				}
				if ( control == 'custom' ) {
					$predefineds.hide();
					$customs.show();
				}
			};

		$blocks.addClass('hybridextend-customize-control-background-blocks');//.attr('data-controlbackground', id);

		// If we have both custom image and pattern options
		if ( $bbButtons.length ) {

			$blocks.hide();
			$blocks.filter('.hybridextend-customize-control-betterbackgroundbutton').show();

			showBlocks( $typeInput.val() );

			$buttons.on('click',function(){
				var value = $(this).data('value');

				$buttons.removeClass('selected').addClass('deactive');
				$(this).removeClass('deactive').addClass('selected');

				$typeInput.val(value).trigger('change');

				showBlocks(value);
			});

		}

		/* Patterns */

		var $pattPreview = $blocks.find('.hybridextend-betterbackground-button-pattern'),
			$patterns = $blocks.find('.hybridextend-customize-radioimage');

		if ( $pattPreview.length ) {
			$pattPreview.html('').append( $patterns.filter('.radiocheck').children('img').clone() );

			$patterns.on('click',function(){
				$pattPreview.html('').append( $(this).children('img').clone() );
			});
		}

	});


	/**** General / Misc ****/


	/*** bComp Styles ***/

	if ( (typeof _hybridextend_customize_data != 'undefined') && (typeof _hybridextend_customize_data.bcomp != 'undefined') && ( _hybridextend_customize_data.bcomp == 'yes' ) )
			$('body').addClass('hybridextend-bcomp');


	/*** Fly Panels - generic ***/
	// This code doesnt 'do' anything. It just acts as framework for other flypanel types.

	var $body = $("body"),
		$flypanelButtons = $('.hybridextend-flypanel-button'),
		initFly = function() {
			$flypanelButtons.click( function(event){
				if( $body.data('flypanel')=='open' && $(this).data('flypanel')=='open' ) {
					closeFly();
				} else {
					closeFly();
					openFly($(this));
				}
				event.stopPropagation();
			});
			$('.hybridextend-flypanel-back').click( function(event){
				closeFly();
				event.stopPropagation();
			});
			$('.hybridextend-flypanel').click( function(event){
				event.stopPropagation();
			});
			$body.click( function(event){
				if ( ! $(event.target).closest('.media-modal').length )
					closeFly();
			});
		},
		closeFly = function(){
			$body.data('flypanel','close');
			$body.data('flypanelbutton','');
			$flypanelButtons.data('flypanel','close');
			$body.trigger('closeflypanel');
		},
		openFly = function($flypanelButton){
			$body.data('flypanel','open');
			$body.data('flypanelbutton',$flypanelButton);
			$flypanelButton.data('flypanel','open');
			$body.trigger('openflypanel');
		};

	initFly();


});