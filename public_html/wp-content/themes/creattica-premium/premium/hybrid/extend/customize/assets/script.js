jQuery(document).ready(function($) {
	"use strict";


	/*** Typography Control ***/

	$('.hybridextend-typo-button').each(function(){

		var $self = $(this),
			controlGroup = $self.data('controlgroup');

		$self.parent('.button.hybridextend-flypanel-button').addClass('hybridextend-typo-button-wrap').data('flypanelsubtype', 'typography');

		// Face
		var $face = $self.children('.hybridextend-typo-button-face');
		if ( $face.length ) {
			var $faceInput = $('#customize-control-' + controlGroup + '-face').find('input');
			$faceInput.on('change',function(){
				$face.html( $faceInput.data( 'label' ) );
			});
			$face.html( $faceInput.data( 'label' ) );
		}

		// Size
		var $size = $self.children('.hybridextend-typo-button-size');
		if ( $size.length ) {
			var $sizeInput = $('#customize-control-' + controlGroup + '-size').find('select');
			$sizeInput.on('change',function(){
				$size.html( $sizeInput.val() );
			});
			$size.html( $sizeInput.val() );
		}

		// Style
		var $style = $self.children('.hybridextend-typo-button-style');
		if ( $style.length ) {
			var $styleInput = $('#customize-control-' + controlGroup + '-style').find('select');
			$styleInput.on('change',function(){
				$style.attr( 'data-style', $styleInput.val() );
			});
			$style.attr( 'data-style', $styleInput.val() );
		}

		// Color
		var $color = $self.children('.hybridextend-typo-button-color');
		if ( $color.length ) {

			// var $colorInput = $('#customize-control-' + controlGroup + '-color').find('input.color-picker-hex');
			// $colorInput.on('change',function(){ //change event is not triggered on $colorInput by the ColorPicker api
			// 	$color.css( 'background-color', $colorInput.val() );
			// });
			// $color.css( 'background-color', $colorInput.val() );

			// var $colorPicker = $('#customize-control-' + controlGroup + '-color').find('.wp-picker-container'),
			// 	$colorResult = $colorPicker.children('.wp-color-result');
			// $colorPicker.on('click',function(){ // This gets binded before ColorPicker api updates bg color
			// 	$color.css( 'background-color', $colorResult.css('background-color') );
			// })
			// $color.css( 'background-color', $colorResult.css('background-color') );

			var $colorPicker = $('#customize-control-' + controlGroup + '-color').find('.wp-picker-container'),
				$colorInput = $('#customize-control-' + controlGroup + '-color').find('input.color-picker-hex');
			$colorPicker.on('mouseenter mouseleave mousemove',function(){
				$color.css( 'background-color', $colorInput.val() );
			});
			$color.css( 'background-color', $colorInput.val() );

		}

	});

	/** Fontface Picker **/

	$('.hybridextend-fontface-list').each(function(){

		var $self = $(this),
			$pickedSelect = $self.siblings('.hybridextend-customize-control-fontface-picked'),
			$picked = $pickedSelect.children('div'),
			$input = $self.siblings('input.hybridextend-customize-control-fontface'),
			$fontfaces = $self.children('.hybridextend-fontface-listitem ');

		$pickedSelect.on('click',function(){
			$(this).toggleClass('active');
			$self.toggle();
		});

		$fontfaces.on('click',function(){
			$fontfaces.removeClass('selected');
			$(this).addClass('selected');
			$pickedSelect.removeClass('active');
			$self.hide();
			$input.val( $(this).data('value') ).data( 'label', $(this).data('label') ).trigger('change');
			$picked.html( $(this).data('label') );
		});

	});

	/** Close Fontface Picker **/

	var $fontfaceLists = $('.hybridextend-fontface-list'),
		$pickedSelects = $('.hybridextend-customize-control-fontface-picked');

	// Add .hybridextend-flypanel also as hybridextend-flypanel has event.stopPropagation() on click
	$('body, .hybridextend-flypanel').click( function(event){
		if ( ! $(event.target).closest('.customize-control-fontface').length ) {
			$fontfaceLists.hide();
			$pickedSelects.removeClass('active');
		}
	});


});