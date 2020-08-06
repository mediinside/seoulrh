/**
 * jQuery vertical scrolling plugin
 *
 * Author : eyesonlyz(eyesonlyz@nate.com)
 * Copyright (c) 2011-2012 eyesonlyz
 * Created : 2011-09-25
 * Updated : 2012-01-07
 * License : GPL
 * Preview : http://ezpress.phps.kr/ix_rolling/
 */
(function($, undefined) {
	if( typeof ($.ix_rolling_tpls) != 'undefined') {
		return;
	}
	var date_string = function(d) {
		var today_int = (Date.parse(Date().valueOf()));
		var v = Math.abs(today_int - d);
		var result = parseInt(v / 1000);
		if(result > 59) {
			result = parseInt(result / 60);
			if(result > 59) {
				result = parseInt(result / 60);
				if(result > 23) {
					return parseInt(result / 24) + "일전";
				} else {
					return result + "시간전";
				}
			} else {
				return result + "분전";
			}
		} else {
			return result + "초전";
		}
	};
	/**
	 * global ix_rolling templates
	 *
	 */
	$.ix_rolling_tpls = {};
	$.ix_rolling_tpls['twitter'] = {
		/**
		 * 아이테별 body 출력
		 */
		body : function(item, options) {
			var html = "", date, date_int;
			var text = $.ix_rolling_tpls['twitter']._bodyParser(item.text, options.target_string);
			if(options.userid) {
				date_int = Date.parse(item.created_at.replace('+', 'UTC+'));
				date = date_string(date_int);
				html += '<p>';
				if(options.use_profile_img) {
					html += "<a href=\"http://twitter.com/intent/user?screen_name=";
					html += item.user.screen_name + "\" " + ">";
					html += '<img class="profile-img" align="left" src="' + item.user.profile_image_url + '" title="' + item.user.screen_name + '"/></a>';
				}
				html += '<a href="http://twitter.com/' + options.userid + '/status/' + item.id_str + '" ' + options.target_string + ' class="sns-icon">&nbsp;</a>';
				html += text;
				if(options.show_date) {
					html += '<span class="timer" title="' + date_int + '">' + date + '</span>';
				}
				html += '<div class="clear"></div></p>';
				return html;
			} else {
				date_int = Date.parse(item.created_at);
				date = date_string(date_int);
				html += '<p>';
				if(options.use_profile_img) {
					html += "<a href=\"http://twitter.com/intent/user?screen_name=";
					html += item.from_user + "\" " + options.target_string + ">";
					html += '<img class="profile-img" align="left" src="' + item.profile_image_url + '" title="' + item.from_user + '"/></a>';
				}
				html += '<a href="http://twitter.com/' + item.from_user + '/status/' + item.id_str + '" ' + options.target_string + ' class="sns-icon">&nbsp;</a>' + text;
				if(options.show_date) {
					html += '<span class="timer" title="' + date_int + '">' + date + '</span>';
				}
				html += '<div class="clear"></div></p>';
				return html;
			}
		},
		/**
		 * 기본옵션
		 */
		default_options : {
			keyword : null,
			userid : null,
			dataType : 'jsonp',
			dataKey : null,
			cut_text : null, //문자열 자르기항상없음,
			since_id : '',
			use_profile_img : true,
			className : 'twitter',
			my_profile : true
		},
		get_url : function(options) {
			var url;
			options.dataType = 'jsonp';
			if(options.userid) {
				url = "http://twitter.com/statuses/user_timeline/" + options.userid + ".json?count=" + options.count;
				options.dataKey = null;
			} else {
				url = "http://search.twitter.com/search.json?q=" + encodeURI(options.keyword) + '&count=' + options.count;
				options.dataKey = 'results';

			}
			return url;
		},
		get_data : function(data, options) {
			return options.dataKey ? (data[options.dataKey] || {}) : data;
		},
		_bodyParser : function(text, target_string) {
			text = text.replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, '<a href="$1" ' + target_string + '>$1</a>');
			text = text.replace(/(^|\s)@(\w+)/g, "$1@<a href=\"http://www.twitter.com/$2\" " + target_string + ">$2</a>");
			text = text.replace(/(^|\s)#(\w+)/g, "$1#<a href=\"http://search.twitter.com/search?q=%23$2\" " + target_string + ">$2</a>");
			return text;
		},
		_open_window : function(obj) {
			window.open(obj.href, '', 'width=550,height=550,scrollbars=1,resizable=1');
			return false;
		},
		body_click : function(evt) {
			var target;
			if(evt.target.tagName == 'A') {
			} else if(evt.target.tagName == 'IMG') {
				evt.preventDefault();
				if(evt.target.parentNode.tagName == 'A') {
					if(evt.target.parentNode.href) {
						window.open(evt.target.parentNode.href, '_blank', 'left=100,top=100,width=550,height=550,scrollbars=1,resizable=1');
					}
				}
			}
		},
		header : function($$, options) {
		}
	}

	$.fn.ix_rolling = function(settings) {

		var template = settings.template ? $.ix_rolling_tpls[settings.template] || null : null;
		if(!template || !template.default_options) {
			return this;
		}
		var options = {
			height : '200px', ///< 높이
			width : '300px', ///< 너비
			time : 30, ///< ajax 요청 초단위임
			cut_text : null, ///< 문자열 자르기
			target : '_new', ///< 내용링크 타넷
			count : 10, ///< 출력 갯수
			className : null, ///< 탬플릿별 css 처리를 위한 clssname
			show_date : true, ///< 날자(시간) 출력 여부
			use_profile_img : true, ///< 프로필 이미지 출력 여부
			body_click : function() { ///< item body 클릭 이벤트
			},
			request_end : function() { ////cancel...
			},
			ix_path : "/ix_rolling", ///< ix_rolling 웹 절대경로
			item_body_tag : 'text', ///< item 데이타 본문 키
			item_id_tag : 'id', ///< item 아이디 태그
			item_time_tag : 'created_at', ///< @todo..
			skip_prepare_item : true, ///< count 보다 대기 아이템이 많은경우 유지여부
			prepare_alert : true, ///< 대기 아이템 갯수 메세지박스 출력여부
			my_profile : true ///< 내 프로필
		};
		$.extend(options, template.default_options, settings);
		options.target_string = options.target ? 'target="' + options.target + '"' : '';
		return this.each(function() {

			var timeOut, $$ = $(this);
			/** @formatter:off */
			!(options.count < 3) 	|| (options.count = 3); 
			!(options.time < 60) 	|| (options.time = 60);
			!(options.width) 		|| $$.css('width', options.width + 'px'); 
			!(options.userid) 		|| $$.addClass('userid'); 
			!(options.className) 	|| $$.addClass(options.className);
			(options.use_profile_img)	|| $$.addClass('no-profile-img');
			/** @formatter:on */
			options.time = options.time * 1000;
			// body click event
			$$.click(template.body_click);
			var url = template.get_url(options);

			var header = null, header_height = 0;
			var message_box = $('<div class="ix-rolling-message"></div>').appendTo($$).slideUp();
			if(options.template == 'twitter' && options.userid && options.my_profile) {
				header = $('<div class="ix-header"></div>').appendTo($$);
				$.getJSON('https://api.twitter.com/1/users/show.json?screen_name=' + options.userid + '&include_entities=false&callback=?', function(results) {
					var _html = "";
					_html += "<a href=\"http://twitter.com/intent/user?screen_name=";
					_html += options.userid + "\" " + options.target_string + ">";
					_html += '<img border=0 class="s-profile-img" align="left" src="https://api.twitter.com/1/users/profile_image?screen_name=' + options.userid + '&size=mini " title="' + options.userid + '"/></a>';
					_html += ' <strong>' + results.screen_name + '</strong>';
					_html += results.description || "";
					header.append(_html);

				});
				header_height = header.height();
				message_box.css("top", header_height);
			};

			var sync_count = 0, now_sync = $("<span class='sync' unselectable='on'>┛</span>").appendTo($$).toggle(function() {
				$$.data("old_height", $$.height());
				$$.animate({
					height : uls.height() - 2
				}, 400);
			}, function() {
				$$.animate({
					height : $$.data("old_height")
				}, 200);
			}), uls = $("<ul></ul>").appendTo($$).data({
				total : 0,
				idxs : {},
				recently_max_id : 0,
				add_els : [],
				started : false,
				stoped : false
			}),
			///<@todo
			menu_box = $('<div class="ix-rolling-menu">play | stop | prev | next | sync</div>').appendTo(now_sync), start = function() {
				if(uls.data("stoped") == true) {
					window.clearTimeout(timeOut);
					timeOut = null;
					return;
				}
				var update = false, last = null, first = uls.find("li:first"), item = null, data = uls.data();
				if(data.add_els && data.add_els.length > 0 && data.recently_max_id == first.data("id")) {
					item = uls.data("add_els").pop();
					last = $('<li unselectable="on">' + template.body(item, options) + "</li>").css("display", "none").appendTo(uls);
					last.data("id", item[options.item_id_tag]);
					update = true;
					data.recently_max_id = item[options.item_id_tag];
					uls.data('recently_max_id', data.recently_max_id);
				} else {
					last = uls.find("li:last");
				}
				uls.stop(true, true).animate({
					top : "+=" + last.outerHeight()
				}, 800, function() {
					uls.css({
						top : 0
					});

					var id = last.data("id");
					last.remove().css("display", "none").insertBefore(first).fadeIn(function() {
						if(update) {
							$(this).addClass("first");
						}
					}).data("id", id);
					if(update) {
						//uls.find("li:last").html('').empty().remove();
						uls.find("li:last").remove();
						first.removeClass("first");
						if(options.prepare_alert) {
							var _len = uls.data("add_els").length;
							if(_len > 0) {
								message_box.html(_len > 0 ? _len + "개 대기중.." : '').slideDown().delay(1000).slideUp();
							}
						}
					}
					window.clearTimeout(timeOut);
					last = null;
					timeOut = null;
					data = null;
					item = null;
					if(options.show_date) {
						$.each(uls.find("span.timer"), function(i, item) {
							item.innerText = date_string(item.title);
						});
					}
					if(uls.data("stoped") == false) {
						timeOut = window.setTimeout(start, 5000);
					}
				});
			}, stop = function() {
				uls.data("stoped", true);
			};
			$$.hover(function(evt) {
				stop();
			}, function() {
				if(uls.data("stoped") == true) {
					uls.data("stoped", false);
					window.clearTimeout(timeOut);
					if(uls.data('total') > 1) timeOut = window.setTimeout(start, 3000);
				}
			});
			$$.css("height", options.height + 'px');
			var reload_time = null;

			var reload = function() {
				$.ajax({
					url : url + (!options.userid ? "&since_id=" + uls.data('recently_max_id') : ""),
					type : "GET",
					cache : false,
					dataType : options.dataType,
					timeout : 30000,
					statusCode : {///1.5
						404 : function() {
							//message_box.html('page not found!').slideDown();
						}
					},
					error : function(jqXHR, textStatus, errorThrown) {///1.5
						options.time = 36000;
						$$.css({
							"backgroundImage" : "none"
						});
						if(jqXHR && jqXHR.getResponseHeader('X-message')){
							message_box.html(jqXHR.getResponseHeader('X-message')).slideDown();
						}
					},
					success : function(json) {
						var i = 0;
						var rows = [];
						var add_els = [];
						//message_box.html("Request").slideDown().delay(1000).slideUp();
						if( rows = template.get_data(json, options, $$)) {
							var total = uls.data('total');
							var item_body_tag = options.item_body_tag;
							var item_id_tag = options.item_id_tag;

							$.each(rows, function(i, item) {
								if(item[item_body_tag] && item[item_body_tag].length > 0) {
									if( typeof (uls.data("idxs")[item[item_id_tag]]) == 'undefined') {
										if(sync_count > 0) {
											if(item[item_id_tag] > uls.data('recently_max_id')) {
												add_els.push(item);
												uls.data("idxs")[item[item_id_tag]] = item[item_id_tag];
												if(options.skip_prepare_item && add_els.length > options.count) {
													var j = add_els.length - options.count;
													while(j > 0) {
														add_els.shift(); --j;
													}
												}
											}
										} else {
											if(total < options.count) {
												$('<li unselectable="on">' + template.body(item,options) + "</li>")[sync_count > 0? 'prependTo':'appendTo'](uls).data('id', item[item_id_tag]);
												uls.data("idxs")[item[item_id_tag]] = item[item_id_tag];
												uls.data("total", ++total);
												if(uls.data('recently_max_id') < item[item_id_tag]) {
													uls.data('recently_max_id', item[item_id_tag]);
												}
											}
										}
									}
								}
							});
						}++sync_count;
						if(sync_count == 1) {
							uls.find("li:first").addClass("first");
							$$.css({
								"backgroundImage" : "none"
							});
						}
						if(add_els.length > 0) {
							uls.data("add_els", add_els);
							if(options.prepare_alert) {//debug 용
								message_box.html("request " + add_els.length + "개 대기중..").slideDown().delay(1000).slideUp();
							}
						}
						if(reload_time) {
							clearTimeout(reload_time);
						}
						reload_time = setTimeout(reload, options.time);
						if(false == uls.data("started") && total > 1) {
							uls.data("started", true);
							setTimeout(start, 5000);
						}
						json = null;
					}
				});
			};
			reload();
		});
	};
	///< facebook 검색 탬플릿
	$.ix_rolling_tpls['facebook'] = {
		body : function(item, options) {
			if(options.show_date) {
				var date_int = item.created_at * 1000;
				var date = date_string(date_int);
			}
			var html = "<p>";
			var target_string = options.target_string;
			var text = (options.cut_text > 0 && item.message.length > options.cut_text) ? item.message.substr(0, options.cut_text) : item.message;
			text = text.replace(/(http:\/\/\S+)/g, '<a href="$1" ' + target_string + '>$1</a>');
			if(options.use_profile_img) {
				html += '<a href="http://www.facebook.com/profile.php?id=' + item.from.id + '" ' + target_string + '><img src="http://graph.facebook.com/' + item.from.id + '/picture' + '" class="fp_' + item.from.id + ' profile-img" align="left"/></a>';
			}
			html += '<a href="' + (item.link || "javascript:void(0)") + '" ' + target_string + ' class="sns-icon">&nbsp;</a>';
			html += (options.cut_text > 0 && item.message.length.length > options.cut_text) ? text + '...' : text;
			if(options.show_date) {
				html += '<span class="timer" title="' + date_int + '">' + date + '</span>';
			}
			return html + '<div class="clear"></div></p>';
		},
		get_data : function(data, options, $$) {
			for(var n in data.data) {
				data.data[n].created_at = data.data[n].created_time;
			}
			return data.data;
		},
		get_url : function(options) {
			var url = "https://graph.facebook.com/search?q=" + encodeURI(options.keyword) + "&date_format=U&type=" + options.type + "&limit=" + options.count;
			return url;
		},
		default_options : {
			dataType : 'jsonp',
			item_body_tag : 'message',
			item_id_tag : 'id',
			cut_text : null,
			className : 'facebook',
			use_profile_img : true,
			type : 'post' ///< facebook search object type
		}
	};

	///< 그누보드용 탬플릿
	$.ix_rolling_tpls['gnu4'] = {
		body : function(item, options) {
			var target_string = options.target_string;
			var text = item.text.replace(/(http:\/\/\S+)/g, '<a href="$1" ' + target_string + '>$1</a>');

			if(options.show_date) {
				var date_int = Date.parse(item.created_at);
				var date = date_string(date_int);
			}

			var html = '';
			if(item.profile_image_url) {
				html = '<a href="#" ' + (target_string) + '>';
				html += '<img class="profile-img" src="' + item.profile_image_url + '" title="' + item.from_user + '"/></a>';
			}
			var el;
			html += '<a href="' + item.href + '" ' + target_string + '>';
			html += (options.cut_text > 0 && text.length > options.cut_text) ? text.substr(0, options.cut_text) + '...' : text;
			html += '</a>';
			if(options.show_date) {
				html += '<span class="timer" title="' + date_int + '">' + date + '</span>';
			}
			html += '<div class="clear"><div>';
			return html;
		},
		get_data : function(data, options) {
			return (data && data.results) ? data.results : [];
		},
		get_url : function(options) {
			var url = options.ix_path + '/gnu4-json.php?bo_table=' + options.bo_table;
			if(options.euckr) {
				url += '&euckr=1';
			}
			return url;
		},
		default_options : {
			bo_table : null,
			euckr : false, //gnuboard euckr
			dataType : 'json',
			className : 'gnu4',
			use_profile_img : false,
			g4_path : "../"
		}
	}

})(jQuery);
