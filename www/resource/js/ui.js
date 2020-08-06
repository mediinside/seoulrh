// function init(){
// 	console.log('success')
// 	new SmoothScroll(document,200,20)
// }

// function SmoothScroll(target, speed, smooth) {
// 	if (target === document)
// 		target = (document.scrollingElement
//               || document.documentElement
//               || document.body.parentNode
//               || document.body)

// 	var moving = false
// 	var pos = target.scrollTop
// 	var frame = target === document.body
//               && document.documentElement
//               ? document.documentElement
//               : target

// 	target.addEventListener('mousewheel', scrolled, { passive: false })
// 	target.addEventListener('DOMMouseScroll', scrolled, { passive: false })

// 	function scrolled(e) {
// 		e.preventDefault();

// 		var delta = normalizeWheelDelta(e)

// 		pos += -delta * speed
// 		pos = Math.max(0, Math.min(pos, target.scrollHeight - frame.clientHeight)) // limit scrolling

// 		if (!moving) update()
// 	}

// 	function normalizeWheelDelta(e){
// 		if(e.detail){
// 			if(e.wheelDelta)
// 				return e.wheelDelta/e.detail/40 * (e.detail>0 ? 1 : -1) // Opera
// 			else
// 				return -e.detail/3 // Firefox
// 		}else
// 			return e.wheelDelta/120 // IE,Safari,Chrome
// 	}

// 	function update() {
// 		moving = true

// 		var delta = (pos - target.scrollTop) / smooth

// 		target.scrollTop += delta

// 		if (Math.abs(delta) > 0.5)
// 			requestFrame(update)
// 		else
// 			moving = false
// 	}

// 	var requestFrame = function() {
// 		return (
// 			window.requestAnimationFrame ||
// 			window.webkitRequestAnimationFrame ||
// 			window.mozRequestAnimationFrame ||
// 			window.oRequestAnimationFrame ||
// 			window.msRequestAnimationFrame ||
// 			function(func) {
// 				window.setTimeout(func, 1000 / 50);
// 			}
// 		);
// 	}()
// }

function tabContent(scope, obj) {
    var target = $(obj),
        depName = scope,
        iNum = target.index(),
        scope = target.parent().siblings('.' + depName + '_wrap'),
    	child = scope.find('.' +  depName + '_content');

    if ( target.hasClass('select') != 1 ){
        target.parent().find('.btn').removeClass('select');
        target.addClass('select');
	}else{
    	return false;
    }
    child.hide();
	child.eq(iNum).fadeIn();
}

function slideToggle(obj) {
    var target = $(obj),
		slideEl = target.siblings('.toggle-con');
	if ( target.hasClass('active') != 1 ){
		$('.toggle-btn').removeClass('active');
		$('.toggle-con').removeClass('show').stop().slideUp();
		target.addClass('active');
		slideEl.slideToggle();
	}else{
		$('.toggle-btn').removeClass('active');
		$('.toggle-con').removeClass('show').stop().slideUp();
	}
}

function headerFix(state){
	if ( state === true ){
		var e = $('#header, #header_sub'),
			h = e.outerHeight(),
			t = e.offset().top;
		$(window).scroll(function(){
			var s = $(window).scrollTop();
			h = e.outerHeight();
			console.log();
			if ( s > t+100 ){
				if ( $('#main').length === 0 ) {
					$('.header-thumb').css('height', h + 'px');
				}
				e.addClass('fixed');
			} else if (s < t+20) {
				if ( $('#main').length === 0 ) {
					$('.header-thumb').attr('style', '');
				}
				e.removeClass('fixed');
			}
		})
	}
}

function animate(state){
	if ( state === true ){
		$(window).on('scroll', function() {
			var winScroll = $(document).scrollTop();

			if( $('.ani-fade').length ){
				$('.ani-fade').each(function() {
					var fadeIn = $(this);
					if( winScroll > fadeIn.offset().top - $(window).height()*0.6 && winScroll < fadeIn.offset().top + fadeIn.outerHeight()/2 ){
						fadeIn.addClass('fade-in');
					}else{
						//fadeIn.removeClass('fade-in');
					}
				});
			}

			if( $('.ani-down-slide').length ){
				$('.ani-down-slide').each(function() {
					var downSlide = $(this);
					if( winScroll > downSlide.offset().top - $(window).height()*0.6 && winScroll < downSlide.offset().top + downSlide.outerHeight()/2 ){
						downSlide.addClass('slide-down');
					}else{
						//downSlide.removeClass('slide-down');
					}
				});
			}

			if( $('.ani-up-slide').length ){
				$('.ani-up-slide').each(function() {
					var upSlide = $(this);
					if( winScroll > upSlide.offset().top - $(window).height()*0.6 && winScroll < upSlide.offset().top + upSlide.outerHeight()/2 ){
						upSlide.addClass('slide-up');
					}else{
						//upSlide.removeClass('slide-up');
					}
				});
			}

			if( $('.ani-left-slide').length ){
				$('.ani-left-slide').each(function() {
					var leftSlide = $(this);
					if( winScroll > leftSlide.offset().top - $(window).height()*0.6 && winScroll < leftSlide.offset().top + leftSlide.outerHeight()/2 ){
						leftSlide.addClass('slide-left');
					}else{
						//leftSlide.removeClass('slide-left');
					}
				});
			}

			if( $('.ani-right-slide').length ){
				$('.ani-right-slide').each(function() {
					var rightSlide = $(this);
					if( winScroll > rightSlide.offset().top - $(window).height()*0.6 && winScroll < rightSlide.offset().top + rightSlide.outerHeight()/2 ){
						rightSlide.addClass('slide-right');
					}else{
						//rightSlide.removeClass('slide-right');
					}
				});
			}
		});
	}
}

$(function(){
	animate(true);
	headerFix(true);

	var swiper01 = '.main-visual-wrap ';

	// if ( $(swiper01).length ){
	// 	$(swiper01).css('height', window.innerHeight);
	// 	$(swiper01).find('.swiper-slide .visual').css('height', window.innerHeight);
	// 	var renderDelay = ['ani-d02','ani-d04','ani-d06']
    //     var swiper01 = new Swiper ( swiper01 + '.swiper-container', {
    //         //slidesPerGroup: 1,
    //         //observeParents: true,
    //         //observer: true,
    //         //centeredSlides: true,
    //         //autoHeight: true,
    //         //direction: 'vertical',
	// 		effect: 'fade',
    //         slidesPerView: 1,
    //         loop: true,
	// 		navigation: {
    //             nextEl: swiper01 + '.swiper-button-next',
    //             prevEl: swiper01 + '.swiper-button-prev',
    //         },
    //         pagination: {
    //             el: swiper01 + '.swiper-pagination',
    //             clickable: true,
	// 			renderBullet: function (index, className) {
	// 				//return '<span class="' + className + ' ' + renderDelay[index] + ' ani-a06"></span>';
	// 				return '<span class="' + className + ' ani-a06"></span>';
	// 			},
    //         },
    //         autoplay: {
    //             delay: 5000,
    //         }
    //     });
	// }

	// if ($('.more-site').length > 0 ){
	// 	$('.more-site').on('click', function(){
	// 		var _this = $(this),
	// 			_menu = _this.siblings('ul');
	// 		if ( _this.hasClass('on') ){
	// 			_this.removeClass('on');
	// 			_menu.stop().slideUp();
	// 		}else{
	// 			_this.addClass('on');
	// 			_menu.stop().slideDown();
	// 		}
	// 	});
	// }

	// if ($('#header .header-menu').length > 0 ){
	// 	$('.header-menu').on('mouseenter', function(){
	// 		if ( !$('#header').hasClass('menu-hover') ){
	// 			$('#header').addClass('menu-hover');
	// 		}
	// 	}).on('mouseleave', function(){
	// 		if ( $('#header').hasClass('menu-hover') ){
	// 			$('#header').removeClass('menu-hover');
	// 		}
	// 	});
	// }

	// if( $('.header-allBtn').length > 0 ){
	// 	var _menuEl = $('.header-all-menu'),
	// 		_menuClose = _menuEl.find('.close'),
	// 		_mHtml = '';
	// 	setTimeout(function(){
	// 		if ( $('.main-visual-wrap').length > 0 ){
	// 			_menuEl.css({
	// 				'height' : $('.main-visual-wrap').height() + 'px'
	// 			})
	// 		}else{
	// 			_menuEl.css({
	// 				'height' : window.innerHeight + 'px'
	// 			})
	// 		}
	// 	},100);
	// 	$('.header-allBtn a, .header-all-menu .close').on('click', function(){
	// 		var _this = $(this);
	// 		if ( !_menuEl.find('.header-all').length > 0 ){
	// 			_mHtml += '<div class="header-all">';
	// 				_mHtml += $('.header-menu').html();
	// 			_mHtml += '</div>'
	// 			_menuEl.append(_mHtml);
	// 		}
	// 		if ( !_menuEl.hasClass('show') ){
	// 			_menuEl.addClass('show');
	// 		}else{
	// 			_menuEl.removeClass('show');
	// 		}
	// 	})
	// }
});

