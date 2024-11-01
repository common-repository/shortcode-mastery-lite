jQuery( document ).ready( function( $ ) {
		
	$('.sm-tinymce-item a.sm-customize').click( function( e ) {
		
		e.preventDefault();
		
		var id = $(this).attr('data-id');
						
		if ( $(this).hasClass('opened') ) {

			$(this).removeClass('opened');
			$('.sm-tinymce-wrap').find('.sm-details-' + id).find('.sm-tinymce-fields').stop().animate({
			    "height": "hide",
			    "marginTop": "hide",
			    "marginBottom": "hide",
			    "paddingTop": "hide",
			    "paddingBottom": "hide",
			    "opacity": 0
			}, 200, function() {
				$('.sm-tinymce-wrap').find('.sm-details-' + id).hide();
			});
			
		} else {
		
			$(this).addClass('opened');
			$('.sm-tinymce-wrap').find('.sm-details-' + id).show();
			$('.sm-tinymce-wrap').find('.sm-details-' + id).find('.sm-tinymce-fields').hide();
			$('.sm-tinymce-wrap').find('.sm-details-' + id).find('.sm-tinymce-fields').stop().animate({
			    "height": "show",
			    "marginTop": "show",
			    "marginBottom": "show",
			    "paddingTop": "show",
			    "paddingBottom": "show",
			    "opacity": 1
			}, 200 );
		}
		
	});
	
	$('.sm-tinymce-item a.sm-more-details').click( function( e ) {
		
		e.preventDefault();
		
		var id = $(this).attr('data-id');
		
		if ( $(this).hasClass('opened') ) {
			
			$(this).removeClass('opened');
			$('.sm-tinymce-wrap').find('.sm-content-' + id).hide();
			
		} else {
		
			$(this).addClass('opened');
			$('.sm-tinymce-wrap').find('.sm-content-' + id).show();	
		}
		
	});
	
	$('.sm-tinymce-item a.sm-quick-insert').click( function( e ) {
		
		e.preventDefault();

		var inputs = $(document).find('.sm-details-' + $(this).attr('data-id') + ' input' );
						
		var params = '';
		
		var slug = $(this).attr('data-content');
				
		inputs.each(function( ) {
			
			if ( $(this).val().length == 0 && $(this).hasClass('required') ) {
				
				params += ' ' + $(this).attr('data-name') + '="' + 'sm-' + slug + '-' + makeid() + '"';
								
			}
			
		});
		
		params = params.replace(/(\])/g, '}').replace(/(\[)/g, '{');
				
		var shortcode;
		
		if ( $(this).attr( 'data-single' ) != 'true' ) {
			
			var content = $(this).closest('.sm-details').find('textarea').val();
				
			shortcode = '[sm_' + $(this).attr('data-content') + params + ']Content[/sm_' + $(this).attr('data-content') + ']';

		} else {
			
			shortcode = '[sm_' + $(this).attr('data-content') + params + ']';
		}
		
		if( ! window.parent.tinyMCE.activeEditor || window.parent.tinyMCE.activeEditor.isHidden() ) {
			
			jQuery('textarea#content', window.parent.document).val( jQuery('textarea#content', window.parent.document).val() + shortcode );
			
		} else {
				
			window.parent.tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, shortcode );
		
		}
		
		window.parent.$.magnificPopup.close();
		
	});
	
	$('.sm-details a.sm-tinymce-submit').click( function( e ) {
		
		e.preventDefault();

		var inputs = $(this).closest('td').find('input');
		
		var selects = $(this).closest('td').find('select');
						
		var params = '';
		
		var slug = $(this).attr('data-content');
		
		var stop = false;
		
		inputs.each(function( ) {
			
			if ( $(this).val().length == 0 && $(this).hasClass('required') ) {
				
				stop = true;
				
				alert( sm.required );
								
			}
			
			if ( $(this).val().length > 0 ) {
				
				var val = $(this).val();
				
				if ( $(this).hasClass('required') ) val = 'sm-' + slug + '-' + val;
				
				params += ' ' + $(this).attr('data-name') + '="' + val + '"';
				
			}
			
		});
		
		selects.each( function( ) {
						
			var val = $(this).find("option:selected").val();
													
			params += ' ' + $(this).attr('data-name') + '="' + val + '"';
									
		});
		
		params = params.replace(/(\])/g, '}').replace(/(\[)/g, '{');
		
		if ( stop ) return;
				
		var shortcode;
		
		if ( $(this).attr( 'data-single' ) != 'true' ) {
			
			var content = $(this).closest('.sm-details').find('textarea').val();
				
			shortcode = '[sm_' + $(this).attr('data-content') + params + ']' + content + '[/sm_' + $(this).attr('data-content') + ']';

		} else {
			
			shortcode = '[sm_' + $(this).attr('data-content') + params + ']';
		}
		
		if( ! window.parent.tinyMCE.activeEditor || window.parent.tinyMCE.activeEditor.isHidden() ) {
			
			jQuery('textarea#content', window.parent.document).val( jQuery('textarea#content', window.parent.document).val() + shortcode );
			
		} else {
				
			window.parent.tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, shortcode );
		
		}
		
		window.parent.$.magnificPopup.close();
		
	});
	
});

function makeid() {
  var text = "";
  var possible = "0123456789";

  for (var i = 0; i < 5; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}