var slider = [],
	co = [],
	fn = [];

// document size type
co.getdoc = (function(body,viewport,size,winWidth){
	body =$('body');
	switch (body.css('z-index')){
		case '1' : if (co.device != 'pc'){co.device = 'pc';co.respond = true;}break;
		case '2' : if (co.device != 'tb'){co.device = 'tb';co.respond = true;}break;
		case '3' : if (co.device != 'mb'){co.device = 'mb';co.respond = true;}break;
	}
	viewport = document.getElementById('viewport');
	size = parseInt(body.css('min-width'));
	winWidth = window.screen.width;
	if (winWidth <= size){
		viewport.setAttribute('content','user-scalable=no, width=' + size + 'px');
	} else {
		viewport.setAttribute('content','width=device-width, initial-scale=1, user-scalable=no');
	}
	$('#wrap').removeClass('hidden');
});

// navigation bar
co.nav = function(){
	var nav = $('#nav'),
		trigger = nav.find('.trigger > a')
	trigger.on('click',function(){
		$('body').toggleClass('nav-show');
		return false;
	});
	$(window).on(co.reszieEvent, function() {
		if (co.respond){
			co.respond = false;
			$('body').removeClass('nav-show');
		}
	})
};

// tab contents
co.tab = function(){
	$('.tab.selection').each(function(){
		var wrap		= $(this),
			title		= wrap.children('.trigger'),
			list		= wrap.children('.list'),
			item		= list.children('li'),
			listInit	= list.data('init');
		var current = function(trigger, callback){
			var parent = trigger.parent(),
				isCurrent = parent.hasClass('on'),
				eq = parent.index(),
				text = trigger.html(),
				triggerInit = eval(trigger.data('init'));
				listInit = eval(callback);
				title.html(text);
			if (!isCurrent){
				if (!wrap.hasClass('link')){
					list.children('.on').removeClass('on');
					item.eq(eq).addClass('on');
				}
				if (wrap.next().hasClass('tab-contents')){
					var tab_contents = wrap.next();
					tab_contents.children('.on').removeClass('on');
					tab_contents.children('.panel').eq(eq).addClass('on');
				}
				if (title.hasClass(co.device) && list.hasClass('on')){
					list.removeClass('on');
					title.removeClass('on');
				}
				if (typeof listInit == 'function') listInit(trigger);
				if (typeof triggerInit == 'function') triggerInit(trigger);
			}
		};
		title.on({
			'click':function(){
				if (title.hasClass(co.device) && !list.hasClass('on')){
					list.addClass('on');
					title.addClass('on');
				} else {
					list.removeClass('on');
					title.removeClass('on');
				}
			}
		});
		item.children('a').on({
			'click':function(){
				var me = $(this);
				current(me, listInit);
			}
		});
		wrap.on('mouseleave',function(){
			list.removeClass('on');
			title.removeClass('on');
		});
		setTimeout(function(){
			if (!wrap.hasClass('link')){
				current(item.eq(0).children('a'), listInit);
			}else {
				current(list.find('.on').children('a'), listInit);
			}
		});
	});
};
// accordian 
co.accordian = function() {
	$('.accordion').each(function(){
		var wrap			= $(this),
			panel			= wrap.children(),
			contsElement	= ".acco-contents",
			speed			= 500;
		panel.on('click','.acco-trigger',function(){
			var me = $(this),
				parent = me.parent(),
				contents = parent.find(contsElement),
				isCurrent = parent.hasClass('on');
			if (!isCurrent){
				var siblings = parent.siblings('.on').removeClass('on');
				siblings.find(contsElement).stop().slideUp(speed);
				parent.addClass('on');
				contents.stop().slideDown(speed);
			} else {
				parent.removeClass('on');
				contents.stop().slideUp(speed);
			}
			return false;
		});
	});
};
slider.item = [];
fn.sliderOption = function(element,eq,custom,option){
	element = element.parent();
	!custom.speed && (custom.speed = 500);
	!custom.pause && (custom.pause = 6000);
	!custom.auto && (custom.auto = true);

	option = {
		responsive: true,
		touchEnabled: true,
		pager : false,
		controls : false,
		autoControls :false
	};
	$.extend(option,custom);
	if (custom.buildPager)option.buildPager = function(sliderIndex){
		return eval(custom.buildPager)(element, sliderIndex);
	}
	option.onSlideBefore = function($slideElement, oldIndex, newIndex){
		custom.onSlideBefore && eval(custom.onSlideBefore)(element, $slideElement, oldIndex, newIndex, slider.item[eq])
		if (option.progressBar) {
			element.find('.bx-progress').children('.percent').stop().css('width','0%').delay(custom.speed).animate({'width':'100%'},custom.pause - custom.speed,function(){
				custom.auto && slider.item[eq].goToNextSlide();
			});
		} else {
			slider.item[eq].stopAuto();
			slider.item[eq].startAuto();
		}
	}
	option.onSliderLoad = function(currentIndex){
		custom.onSliderLoad && eval(custom.onSliderLoad)(element,currentIndex,eq);
		if (option.progressBar) {
			!element.find('.bx-controls').length && element.find('.bx-wrapper').append('<div class="bx-controls" />');
			element.find('.bx-controls').append('<div class="bx-progress"><span class="percent"></span></div>');
			element.find('.bx-progress').children('.percent').stop().css('width','0%').delay(custom.speed).animate({'width':'100%'},custom.pause - custom.speed,function(){
				custom.auto && slider.item[eq].goToNextSlide();
			});
		}
	};
	return option;
};
co.flaxEffect = function(win,scroll){
	!win && (win = $('#wrap').parent());
	!scroll && (scroll = win.scrollTop());
	$('.flax-effect').each(function(eq,item,height,offset,start,size){
		item = $(item);
		height = item.height();
		offset = item.position().top;
		start = offset - win.height();
		size = offset + height - start;
		flag = $(window).height() * 0.25;
		//flag = height * 0.8  > $(window).height() * 0.5 ? $(window).height() * 0.5 : height * 0.8;
		if (scroll  > start + flag){
			item.addClass('visible');
		}else {
			item.removeClass('visible');
		}
	});
};
co.init = function(body, version){
	body =  $('body');
	co.getdoc();
	co.nav();
	co.tab();
	co.accordian();
	$('.fileBtn input').bind('change',function(){
		var val = $(this).val();
		$(this).parent().prev('.i_text').val(val);
	});
	$('.layerBtn').click(function(){
		$(this).next().show();
		return false;
	});
	$('.popClose').click(function(){
		$('.layerPopup').hide();
		return false;
	});
	$('.layerPopup .back').click(function(){
		$('.layerPopup').hide();
		return false;
	});
	$('.i-label > .i-placeholder').on('click',function(){
		$(this).next().trigger('focus');
	});
	$('.i-label > .i-text').on({
		'focus focusin' : function(){
			$(this).parent().children('.i-placeholder').hide();
		},
		'blur focusout' : function(input, placeholder){
			input = $(this),
			placeholder = $(this).parent().children('.i-placeholder');
			if (input.val().length > 0){
				placeholder.hide();
			} else {
				placeholder.show();
			}
		},
	});
	$('.go2top').on('click', function(){
		$('html, body').scrollTop(0);
		return false;
	});
	$('.swiper').each(function(eq, element){
		element = $(element);
		slider.item[eq] = element.bxSlider(fn.sliderOption(element, eq, element.data()));
	});
	$('.ui-select').each(function(eq,ui){
		$(ui)
		.on('click','.trigger',function(){
			$(this).parent('.ui-select').toggleClass('on');
		})
		.on('click','.option > li > a',function(option,parent,select,value,callback){
			option = $(this);
			parent = option.parent();
			select = option.parents('.ui-select');
			parent.siblings('.current').removeClass('current');
			parent.addClass('current');
			value = option.data('value');
			select.find('.trigger').text(option.text());
			select.data('value', value);
			callback = select.data('callback');
			if (callback){
				callback = eval(callback);
				if (typeof callback == 'function') callback(value);
			}
			select.removeClass('on');
		})
		.on('mouseleave', function(){
			$(this).removeClass('on');
		});
	});
	$('.faq-data-list').each(function(faq,question,answer,speed){
		faq = $(this),
		question = faq.find('.question'),
		answer = faq.find('.answer'),
		speed = 500;
		question.on('click',function(me,isCurrent){
			me = $(this),
			isCurrent = me.hasClass('on');
			if (isCurrent){
				me.removeClass('on');
				answer.stop().slideUp(speed,function(){
					$(this).removeAttr('style').hide();
				});
			} else {
				question.removeClass('on');
				answer.stop().slideUp(speed,function(){
					$(this).removeAttr('style').hide();
				});
				me.addClass('on');
				me.next().stop().slideDown(speed,function(){
					$(this).removeAttr('style').show();
				});
			}
		});
		question.eq(0).trigger('click');
	});
	co.flaxEffect();
	if ($.browser.msie && $.browser.versionNumber < 9){
	} else {
		body.queryLoader2({
			barColor: "transparent",
			backgroundColor: "transparent",
			percentage: false,
			barHeight: 0,
			onComplete : function(a,b,c){
			}
		});
	}
	if ($.browser.msie && $.browser.versionNumber < 9){
		version = $('<div id="version"><p>고객님께서는 현재 Explorer 구형버전으로 접속 중이십니다. 이 사이트는 Explorer 최신버전에 최적화 되어 있습니다. <a href="http://windows.microsoft.com/ko-kr/internet-explorer/download-ie" target="_blank">Explorer 업그레이드 하기</a></p> <button type="button" class="version-close">X</button></div>')
		body.prepend(version);
		version.on('click','.version-close',function(){
			version.remove();
		});
	}
};
co.reszieEvent = 'orientationchange' in window ? 'orientationchange' : 'resize';
co.resize = function(screen){
	co.getdoc();
	co.flaxEffect();
	if (fn.resize) fn.resize();
};
co.scroll = function(){
	co.flaxEffect();
	if (fn.scroll) fn.scroll();
};
($(function(){
	$(document).on('ready',function(){
		co.init();
		if (fn.init) fn.init();
	});
	$(window).on(co.reszieEvent, function() {
		co.resize();
	})
	.on('scroll',function(){
		co.scroll();
	});
}(jQuery)));
Date.prototype.format = function(f) {
	if (!this.valueOf()) return ' ';
	var weekName = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
	var d = this;
	var h;
	return f.replace(/(yyyy|yy|MM|dd|E|hh|mm|ss|a\/p)/gi, function($1) {
		switch ($1) {
			case 'yyyy': return d.getFullYear();
			case 'yy': return (d.getFullYear() % 1000).zf(2);
			case 'MM': return (d.getMonth() + 1).zf(2);
			case 'dd': return d.getDate().zf(2);
			case 'E': return weekName[d.getDay()];
			case 'HH': return d.getHours().zf(2);
			case 'hh': return ((h = d.getHours() % 12) ? h : 12).zf(2);
			case 'mm': return d.getMinutes().zf(2);
			case 'ss': return d.getSeconds().zf(2);
			case 'a/p': return d.getHours() < 12 ? 'AM' : 'PM';
			default: return $1;
		}
	});
};
String.prototype.string = function(len){var s = '', i = 0; while (i++ < len) { s += this; } return s;};
String.prototype.zf = function(len){return '0'.string(len - this.length) + this;};
Number.prototype.zf = function(len){return this.toString().zf(len);};
