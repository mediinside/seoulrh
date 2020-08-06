

if (typeof(MAIN_VISUAL_JS) == 'undefined') {
	var MAIN_VISUAL_JS = true;
	
	var show_idx = 0;
	var show_odr = 0;
	var mvis_moving = false;
	/*
	$(document).ready(function() {
		mvis_init();
		mvis_resize();
		mvis_copy();
		//mvis_move(-1, 1);
		photo_slide($('#prcon').children('ul').eq(0).find('.photo'));
	});*/
	/*
	$(window).resize(function() {
		mvis_resize();
	});
	*/
	/*
        var main_visual = $('.main-visual').bxSlider({
			auto: true,
			pager: true,
			controls: false
			});
*/

	

	

	
	// init

/*
	function mvis_init() {
		$('body').prepend($('#picpr'));		
		$('#prcon > ul').css('width', ($(window).width() * $('#prcon > ul').children('li').length) +'px');
		$('#picpr #prnum img').css('cursor', 'pointer');
		$('#picpr #prnum2 img').css('cursor', 'pointer').css('opacity', '0.3');
		
		$('#picpr #prnum img').click(function() {
			if(!mvis_moving) {
				var idx = $(this).index();
				
				if(idx > show_idx) {
					mvis_moving = true;
					mvis_move(-1, idx);
				}
				else if(idx < show_idx) {
					mvis_moving = true;
					mvis_move(1, idx);
				}
			}

		$('#picpr #prnum2 img').click(function() {
			if(!mvis_moving) {
				var idx = $(this).index() > 0 ? show_idx + 1 : show_idx - 1;
				
				if(idx < 0) {
					idx = 0;
				}
				else if(idx >= $('#picpr #prnum img').length) {
					idx = $('#picpr #prnum img').length - 1;
				}
				
				if(idx > show_idx) {
					mvis_moving = true;
					mvis_move(-1, idx);
				}
				else if(idx < show_idx) {
					mvis_moving = true;
					mvis_move(1, idx);
				}
			}
		}).hover(function() {
			$(this).css('opacity', '0.6');
		}, function() {
			$(this).css('opacity', '0.3');
		});
	}

	// padding resize
	function mvis_resize() {
		var ww = $(window).width();
		var ew = 980;
		var w = (ww / 2 - (ew / 2));
		var cnt = $('#prcon > ul').children('li').length;
		var uw = ww * cnt;

		$('#prcon > ul').css('width', uw +'px').css('left', (ww * show_odr * -1) +'px');
		$('#prcon > ul').children('li').css('padding', '0px '+ w +'px');
	}
	
	// DOM copy
	function mvis_copy() {
		$('#prcon').append( $('#prcon').html() );
		$('#prcon').children('ul').eq(1).hide();
		$('#prcon').children('ul').eq(0).children('li:first').nextAll().remove();
		$('#picpr').fadeIn('slow');
	}
	
	// move
	function mvis_move(dir, idx) {
		var ww = $(window).width();
		var ew = 980;
		var w = (ww / 2 - (ew / 2));
		var pos =  '';

		$('#prcon').children('ul').eq(0).children('li:not(:eq('+ show_odr +'))').remove();

		$('#prcon').children('ul').eq(0).find('.photo').remove();
		if(dir < 0) {
			pos = '-='+ $(window).width();
			$('#prcon').children('ul').eq(0).css('left', '0px');
			$('#prcon').children('ul').eq(0).append( '<li class="'+ $('#prcon').children('ul').eq(1).children('li').eq(idx).attr('class') +'">'+ $('#prcon').children('ul').eq(1).children('li').eq(idx).html() +'</li>' );
			$('#prcon').children('ul').eq(0).children('li:last').css('padding', '0px '+ w +'px');
			show_odr = 1;
		}
		else {
			pos = '+='+ $(window).width();
			$('#prcon').children('ul').eq(0).css('left', ($(window).width() * -1) +'px').prepend( '<li class="'+ $('#prcon').children('ul').eq(1).children('li').eq(idx).attr('class') +'">'+ $('#prcon').children('ul').eq(1).children('li').eq(idx).html() +'</li>' );
			$('#prcon').children('ul').eq(0).children('li:first').css('padding', '0px '+ w +'px');
			show_odr = 0;
		}
		
		$('#prcon > ul').stop().animate({ left: pos +'px' }, { duration: 'slow', easing: 'easeOutExpo', complete: function() {
			mvis_moving = false; }
		});
		show_idx = idx;

		$('#picpr #prnum img').each(function() {
			$(this).attr('src', $(this).attr('src').replace('_on.png', '.png'));
		});
		$('#picpr #prnum img').eq(show_idx).attr('src', $('#picpr #prnum img').eq(show_idx).attr('src').replace('.png', '_on.png'));
		
		//photo_slide($('#prcon').children('ul').eq(0).find('.photo'));
	}
	*/
	/*
	function photo_slide(obj) {
		obj.jCarouselLite({
			auto: false,
			//auto: true,
		    speed: 500,
	        btnNext: ".control .next",
	        btnPrev: ".control .prev"
	    });
	}
	*/
	function showVisualSub(obj) {
		$('.visual_sub ul').hide('slide');
		$(obj +' ul').show('slide');
		$(obj).hover(function(){}, function() {
			//$(obj +' ul').hide('slide');
		});
		/*
		$('#body').click(function() {
			 $('.visual_sub ul').hide('slide');
		});
		*/
	}
	
	function hideVisualSub(obj) {
		$(obj +' ul').hide('slide');
	}
}
