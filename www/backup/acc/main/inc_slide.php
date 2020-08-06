<script src="/share/js/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="/share/js/slides.jquery.js" type="text/javascript"></script>
<script type="text/javascript">/*<![CDATA[*/
	$(function(){
		/* 슬라이드 */
		$('#slides').slides({
			preload: true,
			play: 0,
			fadeSpeed :0,
			pause: 2500,
			hoverPause: true
		});
		
		/* 전체 보기 */
/*		$("#btn_menuall_show").click(function(){
			$('#slides').slideUp(250, function(){
				$("#ok01").slideDown(250);	
				document.getElementById("btn_menuall_show").style.display = "none";
			});
		});
*/		
		$("#btn_menuall_show").parent().click(function(){
			$('div.slides_container').slideUp(250, function(){
				$("#ok01").slideDown(250);	
				$('#btn_menuall_show').addClass("dpn");
				$('#slides').find("div.control").addClass("dpn");
				$('#ok01').find("h4.nolink > a").first().focus();
			});			
		});

		/* 전체보기 닫기 */
/*		$("#btn_menuall_hide").click(function(){
			$('#ok01').slideUp(250, function(){						
				$("#slides").slideDown(500);	
				$('#btn_menuall_show').removeClass("dpn");			
			});			
		});
*/		$("#btn_menuall_hide").click(function(){
			$('#ok01').slideUp(250, function(){						
				$("div.slides_container").slideDown(500, function(){
					$('#slides').find("div.control").removeClass("dpn");
					$('#slides').find("div.visualc1[style.display=block]").find("h4.nolink > a").first().focus();
					$('#slides').find("div.visualc2[style.display=block]").find("h4.nolink > a").first().focus();					
				});	
				$('#btn_menuall_show').removeClass("dpn");								
			});			
		});

		/* 닫기 버튼 */
		$("#visual").find("div.close > a").click(function(){
			var subv = $(this).parent().parent().parent();
			subv.find("h4.nolink > a > img").removeClass("dpn");
			subv.find("div.cont").addClass("dpn");
			subv.find("h4.nolink > a > img").parent().focus();
			return false;
		});
		
		/* 디자인 이미지 내의 img click */
/*		$("#visual").find("h4.nolink > a > img").click(function(){
			var subv = $(this).parent().parent().parent();
			subv.find("h4.nolink > a > img").addClass("dpn");
			subv.find("div.cont").removeClass("dpn");
			return false;
		});
*/
		$("#visual").find("h4.nolink > a").click(function(){
			var subv = $(this).parent().parent();
			subv.find("h4.nolink > a > img").addClass("dpn");
			subv.find("div.cont").removeClass("dpn");
			subv.find("div.cont > ul > li > a").first().focus();
			return false;
		});
		
		
	});
/*]]>*/
</script>

	<div id="container_s">
		<div id="example">			
			<div id="slides">
				<div class="slides_container">				
				
					<div>
						<!--!!!  visualc1 -->
						<div class="visualc1">
                        
						<!-- visual1c1 -->
						<div class="visual1c1">
						<!-- 절대 지우지 마세요. fade 효과 사용시 이거 씀다. -->

						<h4 class="nolink" id="id_visual1c1_11"><a href="#id_visual1c1_12"><img src="/img/main/main_img01.png" alt="진료예약" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_12"><div class="bg"></div>
						<!-- <h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5> -->
						<ul>
						<li><a <?=$mAnchor[1][1][0][0]?>><img src="/img/main/menu01_01.gif" alt="01.입원예약" /></a></li>
						<li><a <?=$mAnchor[1][2][0][0]?>><img src="/img/main/menu01_02.gif" alt="02.낮병동예약" /></a></li>
						<li><a <?=$mAnchor[1][3][0][0]?>><img src="/img/main/menu01_03.gif" alt="03.외래예약" /></a></li>
						<li><a <?=$mAnchor[1][4][0][0]?>><img src="/img/main/menu01_04.gif" alt="04.제증명발급" /></a></li>
						<li><a <?=$mAnchor[1][5][0][0]?>><img src="/img/main/menu01_05.gif" alt="04.비급여항목" /></a></li>
						</ul>
						<!-- <div class="more"><a <//?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div> -->
						<div class="close"><a href="#id_visual1c1_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
                        </div>
						<!-- /visual1c1 -->
						
						
                        <!-- visual1c2 -->
						<div class="visual1c2">

						<h4 class="nolink" id="id_visual1c1_11"><a href="#id_visual1c1_12"><img src="/img/main/main_img03.png" alt="성인" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_12"><div class="bg"></div>
						<!-- <h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5> -->
						<ul>
						<li><a <?=$mAnchor[3][1][0][0]?>><img src="/img/main/menu03_01.gif" alt="01.가상치료체험" /></a></li>
						<li><a <?=$mAnchor[3][2][0][0]?>><img src="/img/main/menu03_02.gif" alt="02.질환별클리닉" /></a></li>
						<li><a <?=$mAnchor[3][3][0][0]?>><img src="/img/main/menu03_03.gif" alt="03.치료후기" /></a></li>
						<li><a <?=$mAnchor[3][4][0][0]?>><img src="/img/main/menu03_04.gif" alt="04.의료상담" /></a></li>
						</ul>
						<!-- <div class="more"><a <//?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div> -->
						<div class="close"><a href="#id_visual1c1_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c2 -->
						
						
                        <!-- visual1c3 -->
						<div class="visual1c3">
						<!-- 여기는 사진 슬라이드이미지 코딩임 -->
						<div id="m6scroll">
						<ul  id="m6content">
                        <li><img src="/img/main/picpr/pr01.jpg" alt="서울재활병원 관련사진01" class="png24" /></li>
                        <li><img src="/img/main/picpr/pr02.jpg" alt="서울재활병원 관련사진02" class="png24" /></li>
                        <li><img src="/img/main/picpr/pr03.jpg" alt="서울재활병원 관련사진03" class="png24" /></li>
                        <li><img src="/img/main/picpr/pr04.jpg" alt="서울재활병원 관련사진04" class="png24" /></li>
						</ul>
						</div>
						<script type="text/javascript">initmTicker(document.getElementById('m6scroll'),document.getElementById('m6content'),3000,165);</script>
						<span class="prev01"><a href="#m6" onclick="prevmTicker(document.getElementById('m6scroll'));return false;" title="이전 배너 보기" class="prev_banner"><img src="/img/main/popup_prev.png" alt="prev" /></a></span>
                        <span class="next01"><a href="#m6" onclick="nextmTicker(document.getElementById('m6scroll'));return false;" title="다음 배너 보기" class="next_banner"><img src="/img/main/popup_next.png" alt="next" /></a></span>
						</div>
						<!-- //여기는 사진 슬라이드이미지 코딩임 -->
						</div>
						<!-- /visual1c3 -->
						
						
                        <!-- visual1c4 -->
						<div class="visual1c4">

						<h4 class="nolink" id="id_visual1c1_11"><a href="#id_visual1c1_12"><img src="/img/main/main_img04.png" alt="소아/청소년" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_12"><div class="bg"></div>
						<!-- <h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5> -->
						<ul>
						<li><a <?=$mAnchor[4][1][0][0]?>><img src="/img/main/menu04_01.gif" alt="01.가상치료체험" /></a></li>
						<li><a <?=$mAnchor[4][2][0][0]?>><img src="/img/main/menu04_02.gif" alt="02.질환별클리닉" /></a></li>
						<li><a <?=$mAnchor[4][3][0][0]?>><img src="/img/main/menu04_03.gif" alt="03.치료후기" /></a></li>
						<li><a <?=$mAnchor[4][4][0][0]?>><img src="/img/main/menu04_04.gif" alt="04.의료상담" /></a></li>
						</ul>
						<!-- <div class="more"><a <//?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div> -->
						<div class="close"><a href="#id_visual1c1_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c4 -->
						
						
                        <!-- visual1c5 -->
						<div class="visual1c5">

						<h4 class="nolink" id="id_visual1c1_11"><a href="#id_visual1c1_12"><img src="/img/main/main_img02.png" alt="진료시간표/진료시간" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_12"><div class="bg"></div>
						<!-- <h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5> -->
						<ul>
						<li><a <?=$mAnchor[2][1][0][0]?>><img src="/img/main/menu02_01.gif" alt="01.진료시간표" /></a></li>
						<li><a <?=$mAnchor[2][2][0][0]?>><img src="/img/main/menu02_02.gif" alt="02.진료시간" /></a></li>
						</ul>
						<!-- <div class="more"><a <//?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div> -->
						<div class="close"><a href="#id_visual1c1_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c5 -->
						
						
                        <!-- visual1c6 -->
						<div class="visual1c6">

						<h4 class="nolink" id="id_visual1c1_11"><a href="#id_visual1c1_12"><img src="/img/main/main_img05.png" alt="병원소개" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_12"><div class="bg"></div>
						<!-- <h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5> -->
						<ul>
						<li><a <?=$mAnchor[5][1][0][0]?>><img src="/img/main/menu05_01.gif" alt="01.오시는길" /></a></li>
						<li><a <?=$mAnchor[5][2][0][0]?>><img src="/img/main/menu05_02.gif" alt="02.전화번호" /></a></li>
						<li><a <?=$mAnchor[5][3][0][0]?>><img src="/img/main/menu05_03.gif" alt="02.병원소개" /></a></li>
						<li><a <?=$mAnchor[5][4][0][0]?>><img src="/img/main/menu05_04.gif" alt="02.병원소식" /></a></li>
						<li><a <?=$mAnchor[5][5][0][0]?>><img src="/img/main/menu05_05.gif" alt="02.언론보도" /></a></li>
						<li><a <?=$mAnchor[5][6][0][0]?>><img src="/img/main/menu05_06.gif" alt="02.협력기관" /></a></li>
						</ul>
						<!-- <div class="more"><a <//?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div> -->
						<div class="close"><a href="#id_visual1c1_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c6 -->
                        
                        </div>
						<!-- /!!! visualc1 -->
					</div>
                    


					<div>
						<!-- !!! visualc2 -->
						<div class="visualc2">
                        
						<!-- visual1c1 -->
						<div class="visual1c1">
						<!-- 절대 지우지 마세요. fade 효과 사용시 이거 씀다. -->

						<h4 class="nolink" id="id_visual1c1_11"><a href="#id_visual1c1_12"><img src="/img/main/main_img06.png" alt="진료예약" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_12"><div class="bg"></div>
						<!-- <h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5> -->
						<ul>
						<li><a <?=$mAnchor[1][1][0][0]?>><img src="/img/main/menu01_01.gif" alt="01.입원예약" /></a></li>
						<li><a <?=$mAnchor[1][2][0][0]?>><img src="/img/main/menu01_02.gif" alt="02.낮병동예약" /></a></li>
						<li><a <?=$mAnchor[1][3][0][0]?>><img src="/img/main/menu01_03.gif" alt="03.외래예약" /></a></li>
						<li><a <?=$mAnchor[1][4][0][0]?>><img src="/img/main/menu01_04.gif" alt="04.제증명발급" /></a></li>
						<li><a <?=$mAnchor[1][5][0][0]?>><img src="/img/main/menu01_05.gif" alt="04.비급여항목" /></a></li>
						</ul>
						<!-- <div class="more"><a <//?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div> -->
						<div class="close"><a href="#id_visual1c1_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
                        </div>
						<!-- /visual1c1 -->
						
						
                        <!-- visual1c2 -->
						<div class="visual1c2">

						<h4 class="nolink" id="id_visual1c1_11"><a href="#id_visual1c1_12"><img src="/img/main/main_img08.png" alt="성인" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_12"><div class="bg"></div>
						<!-- <h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5> -->
						<ul>
						<li><a <?=$mAnchor[3][1][0][0]?>><img src="/img/main/menu03_01.gif" alt="01.가상치료체험" /></a></li>
						<li><a <?=$mAnchor[3][2][0][0]?>><img src="/img/main/menu03_02.gif" alt="02.질환별클리닉" /></a></li>
						<li><a <?=$mAnchor[3][3][0][0]?>><img src="/img/main/menu03_03.gif" alt="03.치료후기" /></a></li>
						<li><a <?=$mAnchor[3][4][0][0]?>><img src="/img/main/menu03_04.gif" alt="04.의료상담" /></a></li>
						</ul>
						<!-- <div class="more"><a <//?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div> -->
						<div class="close"><a href="#id_visual1c1_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c2 -->
						
						
                        <!-- visual1c3 -->
						<div class="visual1c3">
						<!-- 여기는 사진 슬라이드이미지 코딩임 -->
						<div id="m6scroll">
						<ul  id="m6content">
                        <li><img src="/img/main/picpr/pr01.jpg" alt="서울재활병원 관련사진01" class="png24" /></li>
                        <li><img src="/img/main/picpr/pr02.jpg" alt="서울재활병원 관련사진02" class="png24" /></li>
                        <li><img src="/img/main/picpr/pr03.jpg" alt="서울재활병원 관련사진03" class="png24" /></li>
                        <li><img src="/img/main/picpr/pr04.jpg" alt="서울재활병원 관련사진04" class="png24" /></li>
						</ul>
						</div>
						<script type="text/javascript">initmTicker(document.getElementById('m6scroll'),document.getElementById('m6content'),3000,165);</script>
						<span class="prev01"><a href="#m6" onclick="prevmTicker(document.getElementById('m6scroll'));return false;" title="이전 배너 보기" class="prev_banner"><img src="/img/main/popup_prev.png" alt="prev" /></a></span>
                        <span class="next01"><a href="#m6" onclick="nextmTicker(document.getElementById('m6scroll'));return false;" title="다음 배너 보기" class="next_banner"><img src="/img/main/popup_next.png" alt="next" /></a></span>
						</div>
						<!-- //여기는 사진 슬라이드이미지 코딩임 -->
						</div>
						<!-- /visual1c3 -->
						
						
                        <!-- visual1c4 -->
						<div class="visual1c4">

						<h4 class="nolink" id="id_visual1c1_11"><a href="#id_visual1c1_12"><img src="/img/main/main_img09.png" alt="소아/청소년" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_12"><div class="bg"></div>
						<!-- <h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5> -->
						<ul>
						<li><a <?=$mAnchor[4][1][0][0]?>><img src="/img/main/menu04_01.gif" alt="01.가상치료체험" /></a></li>
						<li><a <?=$mAnchor[4][2][0][0]?>><img src="/img/main/menu04_02.gif" alt="02.질환별클리닉" /></a></li>
						<li><a <?=$mAnchor[4][3][0][0]?>><img src="/img/main/menu04_03.gif" alt="03.치료후기" /></a></li>
						<li><a <?=$mAnchor[4][4][0][0]?>><img src="/img/main/menu04_04.gif" alt="04.의료상담" /></a></li>
						</ul>
						<!-- <div class="more"><a <//?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div> -->
						<div class="close"><a href="#id_visual1c1_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c4 -->
						
						
                        <!-- visual1c5 -->
						<div class="visual1c5">

						<h4 class="nolink" id="id_visual1c1_11"><a href="#id_visual1c1_12"><img src="/img/main/main_img07.png" alt="진료시간표/진료시간" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_12"><div class="bg"></div>
						<!-- <h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5> -->
						<ul>
						<li><a <?=$mAnchor[2][1][0][0]?>><img src="/img/main/menu02_01.gif" alt="01.진료시간표" /></a></li>
						<li><a <?=$mAnchor[2][2][0][0]?>><img src="/img/main/menu02_02.gif" alt="02.진료시간" /></a></li>
						</ul>
						<!-- <div class="more"><a <//?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div> -->
						<div class="close"><a href="#id_visual1c1_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c5 -->
						
						
                        <!-- visual1c6 -->
						<div class="visual1c6">

						<h4 class="nolink" id="id_visual1c1_11"><a href="#id_visual1c1_12"><img src="/img/main/main_img10.png" alt="병원소개" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_12"><div class="bg"></div>
						<!-- <h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5> -->
						<ul>
						<li><a <?=$mAnchor[5][1][0][0]?>><img src="/img/main/menu05_01.gif" alt="01.오시는길" /></a></li>
						<li><a <?=$mAnchor[5][2][0][0]?>><img src="/img/main/menu05_02.gif" alt="02.전화번호" /></a></li>
						<li><a <?=$mAnchor[5][3][0][0]?>><img src="/img/main/menu05_03.gif" alt="02.병원소개" /></a></li>
						<li><a <?=$mAnchor[5][4][0][0]?>><img src="/img/main/menu05_04.gif" alt="02.병원소식" /></a></li>
						<li><a <?=$mAnchor[5][5][0][0]?>><img src="/img/main/menu05_05.gif" alt="02.언론보도" /></a></li>
						<li><a <?=$mAnchor[5][6][0][0]?>><img src="/img/main/menu05_06.gif" alt="02.협력기관" /></a></li>
						</ul>
						<!-- <div class="more"><a <//?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div> -->
						<div class="close"><a href="#id_visual1c1_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c6 -->
						
						</div>
						<!-- /!!! visualc2 -->					
					</div>
					
				</div><!-- //slides_container-->
				<div class="control">
				<span class="prev"><a href="#slides" title="앞 메뉴로 이동"><img src="/img/main/m1prev.png" alt="prev" class="png24"  /></a></span>
				<span class="next"><a href="#slides" title="뒷 메뉴로 이동"><img src="/img/main/m1next.png" alt="next" class="png24"  /></a></span>				
				</div>			
        
			
			
			</div>	<!-- //slides-->

			</div><!--//example-->

		</div><!--//container_s-->

