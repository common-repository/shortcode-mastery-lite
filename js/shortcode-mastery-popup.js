var $ = jQuery.noConflict();

$(document).ready(function() {
	
	if ( typeof $ != 'undefined' && $.hasOwnProperty('magnificPopup') ) {
		
		var scrT;
		$('.sm-popup-link').magnificPopup({
			type: 'iframe',
			removalDelay: 300,
			mainClass: 'mfp-fade',
			callbacks: {
				open: function() {
					scrT = $(document).scrollTop();
					$('#wpwrap').addClass('sm-blur');
				},
				beforeClose: function() {
					$('#wpwrap').removeClass('sm-blur');
				},
				afterClose: function() {
					$("html, body").scrollTop(scrT);
				},
			}
		});
		$('.sm-popup-inline').magnificPopup({
			type: 'inline',
			removalDelay: 300,
			mainClass: 'mfp-fade',
			callbacks: {
				open: function() {
					scrT = $(document).scrollTop();
					$('#wpwrap').addClass('sm-blur');
				},
				beforeClose: function() {
					$('#wpwrap').removeClass('sm-blur');
				},
				afterClose: function() {
					$("html, body").scrollTop(scrT);
				},
			}
		});

	}
	
});