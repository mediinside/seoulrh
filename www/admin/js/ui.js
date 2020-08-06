var slider = [],
	co = [],
	fn = [];
co.originFontSize = [];
co.resizeFontSize = [];
// document size type
!co.viewContsCount && (co.viewContsCount = 0);
co.getdoc = (function(body,viewport,size,winWidth,contents,count){
	body =$('body');
	switch (body.css('z-index')){
		case '1' : if (co.device != 'pc'){co.device = 'pc';co.respond = true;co.respondView = true;}break;
		case '2' : if (co.device != 'tb'){co.device = 'tb';co.respond = true;co.respondView = true;}break;
		case '3' : if (co.device != 'mb'){co.device = 'mb';co.respond = true;co.respondView = true;}break;
	}
	viewport = document.getElementById('viewport');
	size = parseInt(body.css('min-width'));
	winWidth = window.screen.width;
	if (winWidth <= size){
		viewport.setAttribute('content','user-scalable=no, width=' + size + 'px');
	} else {
		viewport.setAttribute('content','width=device-width, initial-scale=1, user-scalable=no');
	}
	contents = $('.fontsize-convert').find('*');
	count = contents.length;
	contents.each(function(eq,el,size,resize,unit,origin){
		if (co.viewContsCount < count){
			co.viewContsCount++;
			if (el.style.fontSize){
				size = parseInt(el.style.fontSize);
				resize = size * 1.5;
				unit = el.style.fontSize.replace(size,'');
				co.originFontSize[eq] = size + unit;
				co.resizeFontSize[eq] = resize + unit;
			}
		}
		if (el.style.fontSize){
			if (co.device == 'mb'){
				el.style.fontSize = co.resizeFontSize[eq];
			} else {
				el.style.fontSize = co.originFontSize[eq];
			}
		}
	});
	co.respondView = false;
	$('#wrap').removeClass('hidden');
});

// navigation bar
co.nav = function(body,nav,trigger,overlay,group,panel,dp1,dp2,close){
	var speed		= 350,
		body		= $('body'),
		nav			= $('#nav'),
		trigger		= nav.find('.trigger > a'),
		panel		= nav.find('.group > .panel'),
		listDp1		= nav.find('.list .dp1'),
		dpMenu1		= listDp1.children('a'),
		dpSect1		= listDp1.children('.dp-section').children('ul'),
		dp1			= nav.find('.dp1'),
		dp2			= nav.find('.dp2'),
		dpMenu2		= dp2.children('a'),
		dpSect2		= dp2.children('.dp-section').children('ul');
	nav.find('.quick > .all > a').on('click',function(me){
		me = $(this);
		if (!me.hasClass('on')){
			$('#header').addClass('break');
			me.addClass('on');
			nav.children('.sitemap').show();
		} else {
			$('#header').removeClass('break');
			me.removeClass('on');
			nav.children('.sitemap').hide();
		}
	});
	nav.find('.overlay').on('click',function(){
		body.removeClass('nav-show');
		dp1.removeClass('on');
		dp2.removeClass('on');
		dpSect1.hide();
		dpSect2.hide();
	});
	nav.find('.group').find('.btn-close').on('click',function(){
		body.removeClass('nav-show');
		dp1.removeClass('on');
		dp2.removeClass('on');
		dpSect1.hide();
		dpSect2.hide();
	});
	trigger.on('click',function(){
		body.addClass('nav-show');
		if (dp1.hasClass('on')) dp1.removeClass('on');
		panel.fadeIn(500);
	});
	dpMenu1.on({
		'focusin mouseenter' : function(me,parent,siblings){
			me		= $(this);
			parent	= me.parent();
			siblings = parent.siblings('.on');
			if (co.device == 'pc') {
				dp2.removeClass('on');
				if (nav.find('.quick > .all > a').hasClass('on')){
					nav.find('.quick > .all > a').trigger('click');
				}
				body.addClass('nav-show');
				if (!parent.hasClass('on')){
					siblings.children('.dp-section').children('ul').stop().slideUp(speed);
					siblings.removeClass('on');
					parent.addClass('on');
					parent.children('.dp-section').children('ul').stop().slideDown(speed);
				}
			}
			return false;
		},
		'focusout blur' :function(){
			setTimeout(function(){
				if(co.device == 'pc' && $(':focus').parents('#nav .list').length == 0){
					body.removeClass('nav-show');
					setTimeout(function(){
						dp1.removeClass('on');
						dp1.children('.dp-section').children('ul').stop().slideUp(speed);
					},200);
				}
			});
		},
		'click' : function(me, parent){
			me = $(this),
			parent = me.parent();
			if (co.device != 'pc'){
				if (parent.hasClass('on')){
					parent.removeClass('on').find('.dp-section').stop().slideUp('fase');
				} else {
					parent.siblings('.on').find('.dp-section').stop().slideUp('fase');
					parent.siblings('.on').removeClass('on');
					parent.addClass('on');
					parent.children('.dp-section').children('ul').removeAttr('style')
					parent.children('.dp-section').removeAttr('style').stop().slideDown();
				}
				if (parent.children('.dp-section').length > 0) return false;
			}
		}
	});
	dp1.on({
		'mouseleave' : function(me){
			me = $(this);
			if (co.device == 'pc'){
				me.parents('.list').length > 0 && me.children('.dp-section').children('ul').stop().slideUp(speed);
				body.removeClass('nav-show');
				dp1.removeClass('on');
				dp2.removeClass('on');
				dpSect2.stop().fadeOut(speed);
			}
		}
	});
	dpMenu2.on({
		'focusin mouseenter' : function(me,parent,siblings){
			me = $(this);
			parent	= me.parent();
			siblings = parent.siblings('.on');
			if (co.device == 'pc') {
				if (!parent.hasClass('on')){
					siblings.children('.dp-section').stop().fadeOut(speed);
					siblings.removeClass('on');
					parent.addClass('on');
					parent.children('.dp-section').stop().fadeIn(speed);
				}
			}
			return false;
		},
		'focusout blur' :function(){
			setTimeout(function(){
				if(co.device == 'pc' && $(':focus').parents('#nav').length == 0){
					body.removeClass('nav-show');
					setTimeout(function(){
						dp2.removeClass('on');
						dp2.children('.dp-section').stop().slideUp(speed);
					},200);
				}
			});
		},
		'click' : function(me, parent){
			me = $(this),
			parent = me.parent();
			if (co.device != 'pc'){
				if (parent.hasClass('on')){
					parent.removeClass('on').find('.dp-section').stop().slideUp('fase');
				} else {
					parent.siblings('.on').find('.dp-section').stop().slideUp('fase');
					parent.siblings('.on').removeClass('on');
					parent.addClass('on');
					parent.children('.dp-section').removeAttr('style').stop().slideDown('fase');
				}
				if (parent.children('.dp-section').length > 0) return false;
			}
		}
    });
    
    $(".menu-button").on("click",function(){
        $(this).toggleClass("cross");
        $("#wrap").toggleClass("on");
        $(".slide-flex").slideToggle();
    });
	

	var locat = $('#location');
	if (locat.length > 0){
		var locDp = locat.find('.depth'),
			locDp1 = locDp.eq(0),
			locDp2 = locDp.eq(1),
			locDp3 = locDp.eq(2);

		locDp1.children('a').children('span').text(nav.find('.dp1.current').children('a').text());
		locDp1.children('.dp-section').children('li').eq(nav.find('.dp1.current').index()).addClass('current');
		locDp2.children('a').children('span').text(nav.find('.dp2.current').children('a').text());
		locDp2.children('.dp-section').html(nav.find('.dp1.current').children('.dp-section').children('ul').html());
		locDp2.find('.dp2').children('.dp-section').remove();
		locDp.find('.dp2.sub').removeClass('sub').children('.dp-section').remove();
		locDp.children('a').on('click',function(){
			var parent = $(this).parents('.depth');
			if (!parent.hasClass('on')){
				locDp.removeClass('on').children('.dp-section').stop().slideUp(200);
				parent.addClass('on').children('.dp-section').css('height','auto').slideDown(200);
			} else {
				parent.removeClass('on').children('.dp-section').stop().slideUp(200);
			}
		});
		locDp.on('mouseleave',function(){
			locat.find(':focus').trigger('blur');
			locDp.removeClass('on').children('.dp-section').stop().slideUp(200);
		});
		var lnb = $('#lnb');
		if (lnb.length > 0){
			lnb.children('.header').html(nav.find('.dp1.current').children('a').html());
			lnb.children('.contain').html(nav.find('.dp1.current').children('.dp-section').html());
			lnb.children('.contain').find('a').on('click',function(){
				var parent = $(this).parent();
				if (parent.children('.dp-section').length > 0){
					if (!parent.hasClass('on')){
						lnb.children('.dp-section').stop().slideUp(200);
						parent.addClass('on').children('.dp-section').css('height','auto').slideDown(200);
					} else {
						parent.removeClass('on').children('.dp-section').stop().slideUp(200);
					}
					return false;
				}
			});
			if (lnb.find('.dp2.current').children('.dp-section').length > 0){
				lnb.find('.dp2.current').children('a').trigger('click');
			}
		}
	}
};
co.tickerDraw = function(item){
	var swiperWidth = 0;
	item.each(function(eq,el){
		swiperWidth += $(el).outerWidth();
	});
	swiperWidth*=3;
	item.parent().css('width', swiperWidth+'px');
	return swiperWidth;
};
co.ticker = function() {
	var wrap = $('.ticker'),
		swiper = wrap.find('.ticker-swiper > ul'),
		item = swiper.children(),
		prev = wrap.find('.bx-prev'),
		next = wrap.find('.bx-next'),
		direction = 'left',
		position = 0,
		swiperWidth;

	swiper.prepend(item.clone().addClass('clone'));
	swiper.append(item.clone().addClass('clone'));
	swiperWidth = co.tickerDraw(item);

	if (direction == 'left') {
		position = (swiperWidth / 3 * -1);
	} else {
		position = (swiperWidth / 3 * 2 * -1);
	}
	swiper.css('left', position);

	prev.click(function(){
		clearInterval(timer);
		direction = 'left';
		wrap.action();
		return false;
	})
	next.click(function(){
		clearInterval(timer);
		direction = 'right';
		wrap.action();
		return false;
	})

	var timer;
	wrap.action = function(){
		timer = setInterval(function(){
			swiperWidth = co.tickerDraw(item);
			if (direction == 'left') {
				position--;
				if (position == (swiperWidth / 3 * -2)) {
					position -= (swiperWidth / 3  * -1);
				}
			}
			if (direction == 'right') {
				position++;
				if (position == (swiperWidth / 3 * -1)) {
					position += (swiperWidth / 3 * -1);
				}
			}
			swiper.css('left', position);
		},20);
	}
	wrap.action();
	swiper.on('mouseenter focus', 'a', function(){
		clearInterval(timer);
	});
	swiper.on('mouseleave focusout', 'a', function(){
		wrap.action();
	});
}


// tab contents
co.tab = function(){
	$('.tab').each(function(){
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

co.itemParallax = function(){
	var controller = new ScrollMagic.Controller();
	var items = document.querySelectorAll(".parallax-item");
	co.itemParallaxScrollMagic = [];
	for (var i=0; i<items.length; i++) {
		var item = items[i];
		co.itemParallaxScrollMagic[i] = new ScrollMagic.Scene({
			triggerElement: item,
		})
		.addTo(controller)
		.on('enter',function(e){
			var el = $(e.target.triggerElement());
			if (!el.hasClass('visible')){
				el.addClass('visible');
			}
		}).on('leave',function(e){
			if (e.state == 'BEFORE'){
				$(e.target.triggerElement()).removeClass('visible');
			}
		});
	}
};

slider.item = [];
fn.sliderOption = function(el,custom,option){
	el = el.parent();
	var id = el.attr('id');
	!custom.speed && (custom.speed = 500);
	!custom.pause && (custom.pause = 6000);
	!custom.controlPrefix && (custom.controlPrefix = 'mswiper');
	option = {
		responsive: true,
		touchEnabled: true,
		oneToOneTouch :false,
		pager : false,
		controls : false,
		autoControls :false,
		nextText: '<i class="ip-icon-' + custom.controlPrefix + '-next"></i><span class="text-ir">next</span>',
		prevText: '<i class="ip-icon-' + custom.controlPrefix + '-prev"></i><span class="text-ir">prev</span>',
		startText: '<i class="ip-icon-' + custom.controlPrefix + '-play"></i><span class="tup">play</span>',
		stopText: '<i class="ip-icon-' + custom.controlPrefix + '-pause"></i><span class="tup">pause</span>',
	};
	$.extend(option,custom);
	if (custom.buildPager)option.buildPager = function(sliderIndex){
		return eval(custom.buildPager)(el, sliderIndex);
	}
	option.onSlideBefore = function($slideElement, oldIndex, newIndex){
		custom.onSlideBefore && eval(custom.onSlideBefore)(el, $slideElement, oldIndex, newIndex)
		$slideElement.siblings('.active').removeClass('active');
		el.find('.bx-controls').addClass('disabled');
		if (option.auto){
			slider.item[id].stopAuto();
			slider.item[id].startAuto();
		} 
	}
	option.onSlideAfter = function($slideElement, oldIndex, newIndex){
		custom.onSlideAfter && eval(custom.onSlideAfter)(el, $slideElement, oldIndex, newIndex)
		el.find('.bx-controls').removeClass('disabled');
		$slideElement.addClass('active');
	}
	option.onSliderLoad = function(currentIndex){
		custom.onSliderLoad && eval(custom.onSliderLoad)(el, currentIndex);
		setTimeout(function(){
			el.find('.swiper').children().not('.bx-clone').eq(0).addClass('active');
		});
		custom.controlSelector && $(custom.controlSelector).append(el.find('.bx-controls > .bx-controls-direction'));

	};
	return option;
};
co.flaxEffect = function(scroll){
	var win = $(window),
		wrap = $('#wrap'),
		header = $('#header'),
		footer = $('#footer'),
		trigger = 130,
		top = '-100px';
	if (co.device != 'pc'){
		top = '-115px';
	}
	!scroll && (scroll = win.scrollTop());

	if (scroll > trigger){
		if (!wrap.hasClass('fixed')){
			wrap.addClass('fixed');
			trigger && header.css('top',top).animate({'top':0},250,function(){
				$(this).removeAttr('style');
			});
		}
	} else {
		wrap.removeClass('fixed');
		header.stop().removeAttr('style');
	}
};
co.init = function(body, version){
	body =  $('body');
	co.getdoc();
	co.nav();
	co.tab();
	co.accordian();
	co.flaxEffect();
	co.itemParallax();
	co.ticker();
	var ieOldVer = $('#ie-version');
	if (ieOldVer.length){
		ieOldVer.on('click','.version-close',function(){
			ieOldVer.remove();
		});
	}
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
	var iLabel = $('.i-label');
	iLabel.children('.i-placeholder').on('click',function(){
		$(this).next().trigger('focus');
	});
	iLabel.children('.i-text').on({
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
	iLabel.each(function(eq,label){
		label = $(label);
		var input = label.children('.i-text'),
			placeholder = label.children('.i-placeholder');
		if (input.val().length > 0){
			placeholder.hide();
		} else {
			placeholder.show();
		}
	});
	$(document).on('change','select',function(){
		var select = $(this),
			id = select.attr('id'),
			label = select.prev('label[for="'+id+'"]');
		if (label.length){
			if (select.val() != ''){
				label.addClass('on');
			} else {
				label.removeClass('on');
			}
			label.html(select.find('option:checked').html());
		}
	});

	$('.go2top').on('click', function(){
		$('html, body').scrollTop(0);
		return false;
	});
	if ($('#wrap.desktop').length){
		$('#wrap.desktop').find('a[href^="tel:"]').on('click',function(){
			console.log('not support call');
			return false;
		});
	}
	$('.swiper').each(function(eq, el){
		el = $(el);
		var id = el.parent().attr('id');
		slider.item[id] = el.bxSlider(fn.sliderOption(el,el.data()));
	});
	$('.ui-select').each(function(eq,ui){
		ui = $(ui);
		ui
		.on('click','.trigger',function(){
			$(this).parent('.ui-select').toggleClass('on');
		})
		.on('click','.option > li > a',function(option,parent,select,value,callback){
			option = $(this);
			if (option.attr('href').indexOf('javascript:void') == 0){
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
			}
		})
		.on('mouseleave', function(){
			$(this).removeClass('on');
		});
		if (ui.find('.option > li.on').length){
			ui.find('.trigger > span').text(ui.find('.option > li.on').text());
		}
	});
	$('.ui-accordion').each(function(accordion,panel,contsElement,speed,tab){
		accordion		= $(this),
		panel			= accordion.children(),
		contsElement	= ".acco-contents",
		speed			= 250;
		tab = panel.filter('.tab-header');
		tab.find('a').on('click',function(parent,eq){
			parent = $(this).parent();
			parent.siblings('.on').removeClass('on');
			parent.addClass('on');
			eq = parent.index();
			panel.eq(++eq).children('.acco-trigger').trigger('click');
		});
		panel.on('click','.acco-trigger',function(e,element,parent,contents,isCurrent,siblings){
			element = $(this),
			parent = element.parent(),
			contents = parent.find(contsElement),
			isCurrent = parent.hasClass('on');
			if (!isCurrent){
				tab.length && tab.children().removeClass('on').removeClass('active').eq(parent.index()-1).addClass('on').addClass('active');
				if (panel.data('accent') != 'false'){
					siblings = parent.siblings('.on').removeClass('on').removeClass('active');
					if (tab.length && co.device == 'pc' || accordion.hasClass('fix')){
						siblings.find(contsElement).removeAttr('style').hide();
					} else {
						siblings.find(contsElement).stop().slideUp(speed,function(){
							siblings.find(contsElement).removeAttr('style').hide();
						});
					}
				}
				parent.addClass('on').addClass('active');
				if (tab.length && co.device == 'pc' || accordion.hasClass('fix')){
					contents.removeAttr('style').show();
				} else {
					contents.stop().slideDown(speed,function(win,offset,scroll){
						contents.removeAttr('style').show();
						/*!co.accoCount && (co.accoCount = 0);
						co.accoCount++; 
						if (co.accoCount > $('.ui-accordion').length){
							win = $(window);
							offset = contents.prev().offset().top - $('#header').height();
							scroll = win.scrollTop();
							offset < scroll && $('html,body').animate({'scrollTop':offset});
						}*/
					});
				}
			}  else if((!tab.length || tab.hasClass('toggle')) && co.device != 'pc') {
				parent.removeClass('on');
				contents.stop().slideUp(speed,function(){
					contents.removeAttr('style');
				});
			} else if((!tab.length || tab.hasClass('step'))) {
				parent.removeClass('on');
				contents.stop().slideUp(speed,function(){
					contents.removeAttr('style');
				});
			}  
		});
		$(window).on(co.reszieEvent,function(){
			if (co.device == 'pc'){
				var active = panel.filter('.active');
				if (!active.hasClass('on')){
					active.addClass('on');
					active.find(contsElement).show();
				}
			}
		});
		accordion.children('.on').removeClass('on').find('.acco-trigger').trigger('click');
	});
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
Array.prototype.remove=function(){for(var t,r,e=arguments,i=e.length;i&&this.length;)for(t=e[--i];-1!==(r=this.indexOf(t));)this.splice(r,1);return this};

$(function() {

		$('.item').matchHeight({
			byRow: false,
			property: 'height',
			target: null,
			remove: false
		});

			$('.item01').matchHeight({
			byRow: false,
			property: 'height',
			target: null,
			remove: false
		});

			$('.item02').matchHeight({
			byRow: false,
			property: 'height',
			target: null,
			remove: false
		});

});