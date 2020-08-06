<?php
include_once $GP -> HOME."main_lib/main_proc.php";

$Slide_Q = Main_Slide('Q'); //탑배너

?>
<div class="quick">
    <ul class="pc">
        <li>
            <a href="/use/page08-1.html">
                <img src="/resource/images/sub-icon-6.png" alt="">
                <span>오시는길</span>
            </a>
        </li>
		<li>
            <a href="/use/page07.html">
                <img src="/resource/images/sub-icon-5.png" alt="">
                <span>전화문의</span>
            </a>
        </li>
		<li>
            <a href="/use/page03.html">
                <img src="/resource/images/sub-icon-4.png" alt="">
                <span>입원안내</span>
            </a>
        </li>
		<li>
            <a href="/use/page02.html">
                <img src="/resource/images/sub-icon-3.png" alt="">
                <span>외래진료안내</span>
            </a>
        </li>
        <li>
            <a href="/use/page04.html">
                <img src="/resource/images/sub-icon-1.png" alt="">
                <span>낮병동안내</span>
            </a>
        </li>
        <li>
            <a href="/donation/page01-1.html">
                <img src="/resource/images/sub-icon-2.png" alt="">
                <span>후원안내</span>
            </a>
        </li>
    </ul>
    <ul class="mb">
        <li>
            <a href="/use/page08-1.html">
                <img src="/resource/images/sub-icon-w6.png" alt="">
                <span>오시는길</span>
            </a>
        </li>
        <li>
            <a href="/use/page07.html">
                <img src="/resource/images/sub-icon-w5.png" alt="">
                <span>전화문의</span>
            </a>
        </li>
        <li>
            <a href="/use/page03.html">
                <img src="/resource/images/sub-icon-w4.png" alt="">
                <span>입원안내</span>
            </a>
        </li>
        <li>
            <a href="/use/page01.html">
                <img src="/resource/images/sub-icon-w8.png" alt="">
                <span>의료진안내</span>
            </a>
        </li>
    </ul>
    <!-- Swiper -->
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?=$Slide_Q?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>
    <div class="quick-tel">
        <span>대표번호</span>
        <b>02)<br>6020 - 3000</b>
    </div>
    <script>
		var swiper = new Swiper('.swiper-container', {
			loop: true,
			autoplay: {
				delay: 2500,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
			},
		});

		$(function(){
			$("#header_sub .quick .quick-tel").hide();
		});

		$(window).scroll(function(){
			if($(window).width()>768){
				var s = $(window).scrollTop();
				if (window.innerWidth >= window.innerHeight){
					$("#header_sub .quick").removeAttr('style');
					if(s > 90){
						$("#header_sub .quick").css({
							position:'fixed',
							top:0
						});
					}else if(s < 91){
						$("#header_sub .quick").css({
							position:'absolute',
							top:90
						});
					}
				}else {
					$("#header_sub .quick").removeAttr('style');
					$("#header_sub .quick").css({
						position:'fixed',
						top:'auto',
						bottom:0
					});
				}
			}
		});
	</script>
</div>
<!-- //end .quick -->