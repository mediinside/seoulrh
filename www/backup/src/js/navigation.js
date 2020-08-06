(function($) {
	var defaults = {
		showSpeed:	300,			// 서브 Show 속도 (밀리세컨드)
		hideSpeed:	300,			// 서브 Hidden 속도 (밀리세컨드)
		subLeft:	4,				// 서브 Left 강제 조절 (</a>와 <ul> 사이 줄 바뀜에 빈 텍스트 노드 때문에 서브가 밀릴경우 조절함)
		direction:	'horizontal',	// 가로/세로 방향
		animation:	'compress',		// 애니메이션 (none: 없음) - fulldown 메뉴로 사용시 compress로 설정
		fulldown:	'',				// 풀다운 객체
		current:	Array(),		// 기본 활성 메뉴
		zIndex:		10			// 네비 z-index
	}
	
	var ani = {
		diagonal: {
			horizontal:	{
				deft: {left: 20, top: -10},
				show: {left: '-=20', top: '+=20', opacity: 1},
				hide: {left: '+=20', top: '-=20',  opacity: 0}
			},
			vetical:	{
				deft: {top: 20},
				show: {top: '+=20', opacity: 1},
				hide: {top: '-=20', opacity: 0}
			}
		},
		compress: {
			horizontal:	{
				deft: {height: 0},
				show: {opacity: 1},
				hide: {height: 0, opacity: 0}
			},
			vetical:	{
				deft: {height: 0},
				show: {opacity: 1},
				hide: {height: 0, opacity: 0}
			}
		},
		left: {
			horizontal:	{
				deft: {left: -20},
				show: {left: '+=20', opacity: 1},
				hide: {left: '-=20', opacity: 0}
			},
			vetical:	{
				deft: {top: -20},
				show: {top: '+=20', opacity: 1},
				hide: {top: '-=20', opacity: 0}
			}
		},
		right: {
			horizontal:	{
				deft: {left: 20},
				show: {left: '-=20', opacity: 1},
				hide: {left: '+=20', opacity: 0}
			},
			vetical:	{
				deft: {top: 20},
				show: {top: '-=20', opacity: 1},
				hide: {top: '+=20', opacity: 0}
			}
		},
		leftright: {
			horizontal:	{
				deft: {left: -20},
				show: {left: '+=20', opacity: 1},
				hide: {left: '+=20', opacity: 0}
			},
			vetical:	{
				deft: {top: -20},
				show: {top: '+=20', opacity: 1},
				hide: {top: '-=20', opacity: 0}
			}
		},
		rightleft: {
			horizontal:	{
				deft: {left: 20},
				show: {left: '-=20', opacity: 1},
				hide: {left: '-=20', opacity: 0}
			},
			vetical:	{
				deft: {top: 20},
				show: {top: '-=20', opacity: 1},
				hide: {top: '-=20', opacity: 0}
			}
		},
		up: {
			horizontal:	{
				deft: {top: 10},
				show: {top: '-=20', opacity: 1},
				hide: {top: '-=20', opacity: 0}
			},
			vetical:	{
				deft: {top: 10},
				show: {top: '-=20', opacity: 1},
				hide: {top: '-=20', opacity: 0}
			}
		},
		down: {
			horizontal:	{
				deft: {top: -10},
				show: {top: '+=20', opacity: 1},
				hide: {top: '+=20', opacity: 0}
			},
			vetical:	{
				deft: {top: -10},
				show: {top: '-=20', opacity: 1},
				hide: {top: '-=20', opacity: 0}
			}
		},
		updown: {
			horizontal:	{
				deft: {top: 10},
				show: {top: '-=20', opacity: 1},
				hide: {top: '+=20', opacity: 0}
			},
			vetical:	{
				deft: {top: 10},
				show: {top: '-=20', opacity: 1},
				hide: {top: '+=20', opacity: 0}
			}
		},
		downup: {
			horizontal:	{
				deft: {top: -10},
				show: {top: '+=20', opacity: 1},
				hide: {top: '-=20', opacity: 0}
			},
			vetical:	{
				deft: {top: -20},
				show: {top: '+=20', opacity: 1},
				hide: {top: '-=20', opacity: 0}
			}
		}
	}
	
    $.fn.navigation = function(options) {
		return new $nv(this, options);
	};
	
	$.navigation = function(obj, options) {
		this.id = obj.attr('id');
		this.navi = $(obj);
		this.settings = $.extend({}, defaults, options || {});
		this.ani = $.extend({}, ani);
		this.setup();
	};
	
	// Shortcut
	var $nv = $.navigation;
	$nv.fn = $nv.prototype = {};
	$nv.fn.extend = $.extend;
	
	$nv.fn.extend({
			// 초기화
			setup: function()
			{
				var self = this;

				if(self.settings.animation != 'none') {
					// 기능상 필수 css
					$('#'+ this.id +' li').css('background','url(/src/imgs/js/transparent.gif)');
					$('#'+ this.id +' > li').css('position','relative').css('white-space','nowrap');
					$('#'+ this.id +' > li ul').css('position','absolute');
					$('#'+ this.id +' > li ul li').css('position','absolute');
					
					$('#'+ this.id +' li').each(function() {
						$(this).find('img').css('position', 'relative').css('z-index', --self.settings.zIndex);
						$(this).attr('width', $(this).width()).attr('height', $(this).height());
					});
					
					// 풀다운 메뉴
					if(self.settings.fulldown) {
						$(self.settings.fulldown).attr('width', $(self.settings.fulldown).width()).attr('height', $(self.settings.fulldown).height());
						$(self.settings.fulldown).css('position', 'relative').css('z-index', self.settings.zIndex).css('overflow', 'hidden').css('height', '0px');
					}
					
					$('#'+ this.id +' li ul').hide();
				}
				
				self.current(true);
				
				// 이미지 롤오버
				self.navi.find('img').hover(
					function() {
						var overImg = $(this).attr('over');
						if(overImg != undefined) {
							swapImg(this, overImg);
						}
					},
					function() {}
				);
				if(self.settings.fulldown) {
					$(self.settings.fulldown).find('img').hover(
						function() {
							var overImg = $(this).attr('over');
							if(overImg != undefined) {
								swapImg(this, overImg);
							}
						},
						function() {}
					);
				}
				
				// 메뉴 마우스 오버
				self.navi.find('li').children().not('ul').hover(
	        		function() {
	    				if($(this).parent().find('ul').length == 0 || $(this).parent().find('ul:first').css('display') == 'none' || $(this).parent().find('ul:first li:first').attr('is_fade') == '1') {
	    					if(self.settings.animation != 'none') {
	    						self.m_show($(this).parent());
	    					}
	    				}
	    				
	    				return false;
	    			},
	    			function() {}
	        	);
	    		
				// 네비 마우스 아웃
				self.set_hover(self.navi);
			},
			
			// 커런트 메뉴 활성화
			current: function(is_stopped) {
				var cnt = 0;
				
				if(this.settings.current[0]) {
					var obj = '#'+ this.id;
					
					for(var i = 0; i < this.settings.current.length; i++) {
						obj = obj + ' > li[rel="'+ this.settings.current[i] +'"]';
						if($(obj).css('display') != 'none' && !this.settings.fulldown) {
							cnt++;
						}

						// 활성화 메뉴는 오버이미지로
						var a = $(obj +' a').eq(0);
						var img = $(obj +' img').eq(0);
						var overImg = img.attr('over');
						var parent = img.parent().parent();
						if(overImg != undefined) {
							img.attr('src' , overImg);
						}
						if(a != undefined) {
							a.addClass('on');
						}

						obj = obj +' > ul';
						
						$(obj).show();
						
						if($(obj).index() > 0 && $(obj +':first').attr('id') != this.id && ($(obj +':first').css('display') == 'none' || $(obj +':first li:first').attr('is_fade'))) {
							this.m_show($(obj +':first').parent(), is_stopped);
							cnt++;
						}
					}
				}
				
				if(!cnt) {
					this.m_hidden();
				}
			},
			
			// 오버된 하위 객체를 보이기
			m_show: function(m_obj, is_stopped) { 
				if(this.settings.animation == 'none') {
					return false;
				}
				
				var self = this;
				var overflow = ul_width = ul_height = 0;
				var is_stopped = is_stopped != undefined ? is_stopped : false;
				var left = this.settings.direction == 'horizontal' ? - m_obj.children().eq(0).position().left : (m_obj.outerWidth() - m_obj.width()) / 2;
				var top = this.settings.direction == 'horizontal' ? parseInt(m_obj.attr('height')) + (((m_obj.outerHeight() - m_obj.height()) / 2)) : - m_obj.outerHeight();
				var margin_top = parseInt(m_obj.css('margin-top').replace('px',''));
				var margin_left = parseInt(m_obj.css('margin-left').replace('px','')) + this.settings.subLeft;
				var margin_right = parseInt(m_obj.css('margin-right').replace('px',''));
				var nv_right = $('#'+ this.id).offset().left + $('#'+ this.id).outerWidth() + parseInt($('#'+ this.id).css('margin-right').replace('px',''));				
				var ul_left = m_obj.find('ul:first').index() >= 0 ? m_obj.find('ul:first').position().left : 0;
				var effect = eval('this.ani.'+ this.settings.animation +'.'+ this.settings.direction);
				
				var def_t = effect.deft.top != undefined ? effect.deft.top : 0;
				var def_l = effect.deft.left != undefined ? effect.deft.left : 0;
				var def_w = effect.deft.width != undefined ? effect.deft.width : 0;
				var def_h = effect.deft.height != undefined ? effect.deft.height : 0;

				var eff = new Object;
				eff.top = effect.show.top;
				eff.left = effect.show.left;
				//eff.opacity = effect.show.opacity;
				
				this.m_hidden(m_obj.siblings());
				
				m_obj.find('ul:first').css('display', 'inline').css('top', (top + margin_top + def_t) +'px').css('left', '0px');
				
				// 하위 li 노드를 순서대로 확인 후 보이기
				for(i = 0; i < m_obj.find('ul:first > li').length; i++) {
					var curr = m_obj.find('ul:first > li').eq(i);
					var width = curr.outerWidth();
					var height = curr.outerHeight();			
					var ul_left = m_obj.find('ul:first').index() >= 0 ? m_obj.find('ul:first').position().left : 0;
					
					curr.css('left', (left + ((curr.outerWidth() - curr.width()) / 2) + def_l) +'px').css('top', def_t +'px');
					curr.css('width', (curr.width() + def_w) +'px').css('height', (curr.height() + def_h) +'px');
					curr.css('zoom', '1');
					curr.css('filter', 'alpha(opacity=80)');
					if(effect.hide.width != undefined || effect.hide.height != undefined) {
						eff.width = effect.show.width != undefined ? effect.show.width : curr.attr('width');
						eff.height = effect.show.height != undefined ? effect.show.height : curr.attr('height');
					}
					
					curr.attr('is_fade', '').stop().animate(
						eff,
						is_stopped ? 0 : this.settings.showSpeed
					);
					
					// IE8 에서 하위 이미지 opacity 적용이 안되어 별도 실행
					curr.attr('is_fade', '').find('img').stop().animate({
						opacity: effect.show.opacity
						},
						is_stopped ? 0 : this.settings.showSpeed
					);
					
					var curr_margin = parseInt(curr.css('margin-left').replace('px','')) + parseInt(curr.css('margin-right').replace('px',''));
					overflow = (curr.offset().left + width + curr_margin) - nv_right;
					
					// 서브 메뉴가 영역을 벗어날 경우 ul의 left를 감소함
					if(overflow > 0 && this.settings.direction == 'horizontal') {
						ul_left = ul_left - overflow + 15;
						m_obj.find('ul:first').css('left', ul_left +'px');
					}
					
					left = this.settings.direction == 'horizontal' ? left + width : left;
					top = this.settings.direction == 'vetical' ? top + height : top;
					ul_width = ul_width + parseInt(eff.width) + margin_right + (curr.outerWidth() - parseInt(eff.width));
					ul_height = ul_height > parseInt(eff.height) ? ul_height : parseInt(eff.height);
				}
				
				// 풀다운 메뉴 show
				if(this.settings.fulldown) {
					if(effect.show.width != undefined) {
						eff.width = effect.hide.width;
					}
					else if(effect.hide.width != undefined) {
						eff.width = $(self.settings.fulldown).attr('width');
					}
					if(effect.show.height != undefined) {
						eff.height = effect.hide.height;
					}
					else if(effect.hide.height != undefined) {
						eff.height = $(self.settings.fulldown).attr('height');
					}
					
					$(this.settings.fulldown).stop().animate(
						eff,
						this.settings.showSpeed
					);
					
					self.navi.off();
					self.set_hover($(self.settings.fulldown));
				}
				
				m_obj.find('ul:first').css('width', ul_width + (m_obj.outerWidth() - m_obj.outerWidth()) / 2 +'px')
				m_obj.find('ul:first').css('height', ul_height + (m_obj.outerHeight() - m_obj.height()) / 2  +'px');
			},
			
			// 마우스가 벗어난 부모 객체를 숨김
			m_hidden: function(m_obj) {
				if(this.settings.animation == 'none') {
					return false;
				}
				
				var self = this;
				var effect = eval('this.ani.'+ this.settings.animation +'.'+ this.settings.direction);

				var eff = new Object;
				eff.top = effect.hide.top;
				eff.left = effect.hide.left;
				//eff.opacity = effect.hide.opacity;
				
				m_obj = m_obj != undefined ? m_obj : $('#'+ this.id +' li');
				
				for(i = 0; i < m_obj.length; i++) {
					if(m_obj.eq(i).find('li').attr('is_fade') != '1' && m_obj.eq(i).find('li').css('opacity')) {						
						m_obj.eq(i).find('li').each(function() {
							
							if(effect.hide.width != undefined || effect.hide.height != undefined) {
								eff.width = effect.hide.width != undefined ? effect.hide.width : $(this).attr('width');
								eff.height = effect.hide.height != undefined ? effect.hide.height : $(this).attr('height');
							}
							
							$(this).attr('is_fade', '1').stop().animate(
								eff,
								self.settings.hideSpeed,
								function(){
									$(this).attr('is_fade', '');
									$(this).parent().parent().find('ul').hide();
							});
							
							// IE8 에서 하위 이미지 opacity 적용이 안되어 별도 실행
							$(this).attr('is_fade', '1').find('img').stop().animate({
								opacity: effect.hide.opacity
								},
								self.settings.hideSpeed
							);
						});
					}
				}
				
				// 풀다운 메뉴 hidden
				if(self.settings.fulldown) {
					if(effect.hide.width != undefined) {
						eff.width = effect.hide.width;
					}
					else if(effect.show.width != undefined) {
						eff.width = $(self.settings.fulldown).attr('width');
					}
					if(effect.hide.height != undefined) {
						eff.height = effect.hide.height;
					}
					else if(effect.show.height != undefined) {
						eff.height = $(self.settings.fulldown).attr('height');
					}

					$(self.settings.fulldown).stop().animate(
						eff,
						self.settings.showSpeed
					);
				}
			},
			
			// 마우스가 벗어났을때 네비를 초기화 할 객체 정의
			set_hover: function(jobj) {
				var self = this;
				
				$(jobj).hover(
					function(){},
					function(e) {
	    				if(self.settings.animation != 'none') {
	    					self.current();
	    				}
	    				
	    				return false;
					}
				);
			}
	});
})(jQuery);
