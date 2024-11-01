jQuery(document).ready(function($){

	/**
	 * Mobile Selector
	 */
	
	if(!jQuery.browser.mobile)
		$('body').addClass('is-mobile');

	/**
	 * Video Intro Resizer
	 */

	var videoIntroFrameBorderWidth = parseInt($('#ts-videointro-frameborderwidth').val());
	if(jQuery.browser.mobile)
		videoIntroFrameBorderWidth = 0;
	var videoIntroWidth            = parseInt($('#ts-videointro-width').val());
	var videoIntroHeight           = parseInt($('#ts-videointro-height').val());
	var videoIntroFillscreen       = $('#ts-videointro-videofit').val() === 'fill';
	var videoIntroRatio            = videoIntroWidth / videoIntroHeight;

	function tsFitVideoIntro() {

		var htmlMarginTop  = parseInt($('html').css('margin-top')); //admin-bar height
		var browserWidth   = $(window).outerWidth();
		var viewPortWidth  = browserWidth - (videoIntroFrameBorderWidth * 2);
		var browserHeight  = window.innerHeight ? window.innerHeight : $(window).outerHeight();
		var viewPortHeight = (browserHeight - htmlMarginTop)  - (videoIntroFrameBorderWidth * 2);

		var viewPortRatio = viewPortWidth / viewPortHeight;

		if ( videoIntroFillscreen && !jQuery.browser.mobile ) {
			var ratio = (videoIntroRatio < viewPortRatio)
				? videoIntroWidth / viewPortWidth
				: videoIntroHeight / viewPortHeight;	
		} else {
			var ratio = (videoIntroRatio >= viewPortRatio)
				? videoIntroWidth / viewPortWidth
				: videoIntroHeight / viewPortHeight;
		}

		var newVideoIntroWidth  = videoIntroWidth / ratio;
		var newVideoIntroHeight = videoIntroHeight / ratio;

		$('body').css({
			'height'     : browserHeight - htmlMarginTop
		});

		$('.ts-videointro-viewport').css({
			'height'     : viewPortHeight,
			'left'       : videoIntroFrameBorderWidth,
			'top'        : videoIntroFrameBorderWidth + htmlMarginTop,
			'width'      : viewPortWidth
		});

	$('.ts-videointro-wrapper').css({
			'height'     : newVideoIntroHeight,
			'left'       : ((viewPortWidth - newVideoIntroWidth) / 2),
			'top'        : ((viewPortHeight - newVideoIntroHeight) / 2),
			'width'      : newVideoIntroWidth
		});			

	}

	tsFitVideoIntro();

	$(window).resize(function() {
		tsFitVideoIntro();
	});


	/**
	 * Skip Intro
	 */
	if (!jQuery.browser.mobile) {
		var elementsToFade = $('.ts-videointro-skipintro-wrapper');
	} else {
		var elementsToFade = $('.ts-videointro-skipintro-wrapper, .ts-videointro-logo-wrapper');
	}

	timer = setTimeout(function(){
		elementsToFade.fadeOut(200);
		$('#ts-videointro-player-main').css({cursor: 'none'});
	}, 1000);

	function videointroTimeOut() {
		clearTimeout(timer);
		elementsToFade.fadeIn(100);

		if($('.ts-videointro-skipintro-link').is(":hover")) return false;
		
		$('#ts-videointro-player-main').css({cursor: 'default'});
		timer = setTimeout(function(){
			elementsToFade.fadeOut(200);
			$('#ts-videointro-player-main').css({cursor: 'none'});
		}, 1000);
	}

	if(!jQuery.browser.mobile) {
		$('body').mousemove(function(){
			videointroTimeOut();
		});		
	} else {
		$('body').touchmove(function(){
			videointroTimeOut();
		});		
		$('body').tap(function(){
			videointroTimeOut();
		});
		$(window).resize(function() {
			videointroTimeOut();
		});
	}
});