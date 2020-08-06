$(function () {
	// $(document).bind(
	// 	'touchmove',
	// 	function (e) {
	// 		e.preventDefault();
	// 	}
	// );

	var $iframe_h = $('iframe, video').width() * 0.5817;
	$('iframe, video').height($iframe_h);


	$(".header-thumb-close a.today-close").on("click", function () {
		$(".header-thumb").animate({
			height: 0,
			overflow: 'hidden'
		});
	});

	$(".header-menu > ul > li").on("mouseover", function () {
		$(this).find(".sub-dep").stop().slideDown(400);
		$(this).siblings().find(".sub-dep").hide();
	});

	$(".header-menu > ul > li").on("mouseleave", function () {
		$(this).find(".sub-dep").stop().slideUp(200);
		$(this).siblings().find(".sub-dep").hide();
	});

	$(".main-board-right ul li button").on("click", function () {
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


	$(".share_wrap .btn_share").on("click", function () {
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

	if ($(window).width() < 769) {
		$(".header-allBtn .sub-dep ul li > a").on("click", function () {
			$(this).toggleClass("on");
		});
		$(".header-allBtn .sub-dep ul li dl dt").on("click", function () {
			$(this).parent("dl").toggleClass("on");
		});
	}
});


$(window).on("load resize", function () {
	var window_W = $(window).width();
	var headMenu_W = $(".header-menu").width() + 300;
	var allMenu_W = window_W - headMenu_W;

	$("#m-swiper").addClass("m-swiper");
	$(".sub-dep").css({
		width: window_W
	});

	if (window.innerWidth >= window.innerHeight) {
		console.log('ehla?');
		// $("#viewport").attr("content", "width=device-width, initial-scale=0.65, minimum-scale=0.65, maximum-scale=1.2, user-scalable=yes");
		$(".header-allBtn .sub-dep").removeAttr('style');
		$("#viewport").attr("content", "width=1600");
		$("html").addClass('active');
		$(".header-logo img").attr("src", "/resource/images/logo.png");
		$(".header-lang img").attr("src", "/resource/images/lang.png");
		$(".header-allBtn img.on").attr("src", "/resource/images/menu.png");
		$(".header-menu").removeAttr('style');
		$(".swiper-container4").hide();
		$(".swiper-container7").show();
		$("#m-swiper").hide();
		$("#pc-swiper").show();
	} else {
		$("#viewport").attr("content", "width=620");
		// $(".header-allBtn .sub-dep").removeAttr('style');
		$(".swiper-container4").show();
		$(".swiper-container7").hide();
		$("#m-swiper").show();
		$("#pc-swiper").hide();
		if ($("html").hasClass("active")) {
			location.reload();
		}
		$("#header .header-logo img").attr("src", "/resource/images/m-logo.png");
		$("#header .header-lang img").attr("src", "/resource/images/lang-g.png");
		$("#header .header-allBtn img.show").attr("src", "/resource/images/menu-g.png");
		$("#header_sub .header-logo img").attr("src", "/resource/images/m-logo2.png");
		for (var i = 0; i < $(".contents .quick li").length; i++) {
			$(".contents .quick li").eq(i).find("img").attr("src", "/resource/images/m-icon-" + (i + 1) + ".png");
		}

		$("html").removeClass('active');

	};


	$(".swiper-container4").show();
	$(".swiper-container7").hide();



	var data = "on";
	$(".header-allBtn > a").on("click", function () {
		if (data == "on") {
			$(this).find(".on").removeClass("show").siblings().addClass("show");
			$(".header-allBtn .sub-dep ul").stop().slideDown();

			if (window_W < 769) {
				// $(".header-allBtn .sub-dep").removeAttr('style');
				$(".header-lang").addClass("on");
				$("body,html").addClass("on");
				$("#header").css({
					backgroundColor: "#65bb00"
				});
				$(".header-logo img").attr("src", "/resource/images/m-logo2.png");
				$(".header-allBtn .sub-dep").css({
					width: 720,
					overflowY: "auto",
					// height: $(window).height() - ($(".header-thumb").height() + 121),
					height:'auto',
					bottom:0,
					borderWidth: 0
				}, 100);
				$(".header-allBtn .sub-dep ul").stop().slideDown();

			} else if (window_W >= 769) {

				$("#header").css({
					backgroundColor: "#fff"
				});
				$(".header-logo img").attr("src", "/resource/images/logo.png");
				$(".header-allBtn .sub-dep").stop().animate({
					height: 'auto',
					borderWidth: 1
				}, 200);
				$(".header-lang").show();

			};

			data = "off";

		} else if (data == "off") {

			$(this).find(".off").removeClass("show").siblings().addClass("show");
			$("#header").removeAttr('style');

			if (window_W < 769) {

				$("#header .header-logo img").attr("src", "/resource/images/m-logo.png");
				$("#header_sub .header-logo img").attr("src", "/resource/images/m-logo2.png");
				$(".header-lang").removeClass("on");
				$("body,html").removeClass("on");

				$(".header-allBtn .sub-dep").css({
					bottom: 'auto',
					borderWidth: 0
				}, 100);

			} else if (window_W >= 769) {

				$("#header .header-logo img").attr("src", "/resource/images/logo.png");
				$(".header-lang").removeClass("on");
				$("body,html").removeClass("on");
				$(".header-allBtn .sub-dep").stop().animate({
					height: 0,
					borderWidth: 0
				}, 100);

			};

			$(".header-allBtn .sub-dep ul").stop().slideUp();
			data = "on";

		}
	});

});