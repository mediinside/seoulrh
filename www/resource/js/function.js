
$(function(){
	var $iframe_h = $('iframe, video').width() * 0.5817;
	$('iframe, video').height($iframe_h);

	$(".swiper-container4").hide();
	$(".swiper-container7").show();
	$("#m-swiper").hide();

	$(".header-thumb-close a.today-close").on("click",function(){
		$(".header-thumb").animate({
			height: 0,
			overflow:'hidden'
		});
	});

	$(".header-menu > ul > li").on("mouseenter",function(){
		// $(this).find(".sub-dep").stop().slideDown(400);
		// $(this).siblings().find(".sub-dep").hide();
		$(this).addClass('active').siblings().removeClass('active');
	});

	$("header").on("mouseleave", function () {
		$(".header-menu > ul > li").removeClass('active');
	});

	// $(".header-menu > ul > li").on("mouseleave", function () {
	// 	$(this).find(".sub-dep").stop().slideUp(200);
	// 	$(this).siblings().find(".sub-dep").hide();
	// });

	$(".main-board-right ul li button").on("click",function(){
		$(this).addClass("on").next(".tab-cont").fadeIn(100);
		$(this).parent("li").siblings("li").find("button").removeClass("on").next(".tab-cont").fadeOut(100);
	});

	$(".section li").on("mouseover", function () {
		$(this).find(".dp-section").css({
			height: $(".dropdown-item").height() * $(this).find(".dropdown-item").length
		});
	});
	$(".section li").on("mouseout", function () {
		$(this).find(".dp-section").css({
			height: 0
		});
	});


	$(".share_wrap .btn_share").on("click",function(){
	   $(".share_wrap").toggleClass("on");
	});

	$('.more-site').on('click', function () {
		var _this = $(this),
			_menu = _this.siblings('ul');
		if (_this.hasClass('on')) {
			_this.removeClass('on');
			_menu.stop().slideUp();
		} else {
			_this.addClass('on');
			_menu.stop().slideDown();
		}
	});

	 $(".accordion .item").on("click", function () {
		 if ($(this).hasClass("active")) {
			 $(this).removeClass("active").find('.collapsed').attr('title', '열기');
		 } else {
			 $(this).addClass("active").find('.collapsed').attr('title', '닫기');
		 }
	 });
});

$(window).on("load resize",function(){
	var window_W = $(window).width();
	var headMenu_W = $(".header-menu").width() + 300;
	var allMenu_W = window_W - headMenu_W;

	$(".sub-dep").css({
		width: window_W
	});

	var data = "on";
	$(".header-allBtn > a").on("click", function () {
		if (data == "on") {
			$(this).find(".on").removeClass("show").siblings().addClass("show");
			$(".header-allBtn .sub-dep ul").stop().slideDown();

			$("#header").css({
				backgroundColor: "#fff"
			});
			$(".header-logo img").attr("src", "/resource/images/logo.png");
			$(".header-allBtn .sub-dep").stop().animate({
				paddingTop:40,
				height: 597,
				borderWidth: 1
			}, 200);
			$(".header-lang").show();

			data = "off";

		} else if (data == "off") {

			$(this).find(".off").removeClass("show").siblings().addClass("show");
			$("#header").removeAttr('style');


			$("#header .header-logo img").attr("src", "/resource/images/logo.png");
			$(".header-lang").removeClass("on");
			$("body,html").removeClass("on");
			$(".header-allBtn .sub-dep").stop().animate({
				paddingTop:0,
				height: 0,
				borderWidth: 0
			}, 100);

			$(".header-allBtn .sub-dep ul").stop().slideUp();
			data = "on";

		}
	});



	$("html").removeClass('active');
	$(".header-logo img").attr("src", "/resource/images/logo.png");
	$(".header-lang img").attr("src", "/resource/images/lang.png");
	$(".header-allBtn img.on").attr("src", "/resource/images/menu.png");
	$(".header-menu").removeAttr('style');

});