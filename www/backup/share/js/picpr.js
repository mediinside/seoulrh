if (typeof(MAIN_VISUAL_JS) == 'undefined') {
	var MAIN_VISUAL_JS = true;
	
	var show_idx = 0;
	var show_odr = 0;
	var mvis_moving = false;
	
	$(document).ready(function() {
		mvis_init();
		mvis_resize();
		mvis_copy();
		//mvis_move(-1, 1);
	});
	
	$(window).resize(function() {
		mvis_resize();
	});
	
	

	// init
	function mvis_init() {
		$('#prcon > ul').css('width', ($(window).width() * $('#prcon > ul li').length) +'px');
		$('#picpr #prnum img').css('cursor', 'pointer');
		
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
				
				$('#picpr #prnum img').attr('src', $(this).attr('src').replace('_on.png', '.png'));
				$(this).attr('src', $(this).attr('src').replace('.png', '_on.png'));
			}
		});
	}

	// padding resize
	function mvis_resize() {
		var ww = $(window).width();
		var ew = 980;
		var w = (ww / 2 - (ew / 2));
		var cnt = $('#prcon > ul li').length;
		var uw = ww * cnt;

		$('#prcon > ul').css('width', uw +'px').css('left', (ww * show_odr * -1) +'px');
		$('#prcon > ul li').css('padding', '0px '+ w +'px');
	}
	
	// DOM copy
	function mvis_copy() {
		$('#prcon').append( $('#prcon').html() );
		$('#prcon ul').eq(1).hide();
		$('#prcon ul').eq(0).find('li:first').nextAll().remove();
		$('#picpr').fadeIn('slow');
		
	}
	
	// move
	function mvis_move(dir, idx) {
		var ww = $(window).width();
		var ew = 980;
		var w = (ww / 2 - (ew / 2));
		var pos =  '';

		$('#prcon ul').eq(0).find('li:not(:eq('+ show_odr +'))').remove();
		
		if(dir < 0) {
			pos = '-='+ $(window).width();
			$('#prcon ul').eq(0).css('left', '0px');
			$('#prcon ul').eq(0).append( '<li>'+ $('#prcon ul').eq(1).find('li').eq(idx).html() +'</li>' );
			$('#prcon ul').eq(0).find('li:last').css('padding', '0px '+ w +'px');
			show_odr = 1;
		}
		else {
			pos = '+='+ $(window).width();
			$('#prcon ul').eq(0).css('left', ($(window).width() * -1) +'px').prepend( '<li>'+ $('#prcon ul').eq(1).find('li').eq(idx).html() +'</li>' );
			$('#prcon ul').eq(0).find('li:first').css('padding', '0px '+ w +'px');
			show_odr = 0;
		}
		
		$('#prcon ul').eq(0).stop().animate({ left: pos +'px' }, { duration: 'slow', easing: 'easeOutExpo', complete: function() {
			mvis_moving = false; }
		});
		show_idx = idx;
	}
}
