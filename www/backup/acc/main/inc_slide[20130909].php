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

						<h4 class="nolink" id="id_visual1c1_11"><a href="#id_visual1c1_12"><img src="/img/main/main_img01.png" alt="취업지원센터. 일하는 즐거움을 나눕니다." class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_12"><div class="bg"></div>
						<h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5>
						<ul>
						<li><a <?=$mAnchor[4][1][1][0]?>><img src="/img/main/menu01_02.gif" alt="구인구직" /></a></li>
						<li><a <?=$mAnchor[4][1][2][0]?>><img src="/img/main/menu01_03.gif" alt="공공취업정보" /></a></li>
						<li><a <?=$mAnchor[4][1][3][0]?>><img src="/img/main/menu01_04.gif" alt="공공근로사업" /></a></li>
						<li><a <?=$mAnchor[4][1][4][0]?>><img src="/img/main/menu01_05.gif" alt="희망근로" /></a></li>
						</ul>
						<div class="more"><a <?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div>
						<div class="close"><a href="#id_visual1c1_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->


						</div>
						<!-- /visual1c1 -->
						
						<!-- visual1c2 -->
						<div class="visual1c2">
						
						
						<h4 class="nolink" id="id_visual1c2_11"><a href="#id_visual1c2_12"><img src="/img/main/main_img11.png" alt="열린군수실. 살기좋은 칠곡을 만들겠습니다 " class="png24" /></a></h4>
						
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c2_12"><div class="bg"></div>
						<h5><img src="/img/main/menu02_01.gif" alt="열린군수실" /></h5>
                        <ul>
						<li><a <?=$mAnchor[3][1][1][0]?>><img src="/img/main/menu02_02.gif" alt="백선기입니다." /></a></li>
						<li><a <?=$mAnchor[3][1][2][0]?>><img src="/img/main/menu02_03.gif" alt="실천25시 " /></a></li>
						<li><a <?=$mAnchor[3][1][3][0]?>><img src="/img/main/menu02_04.gif" alt="생생!현장속으로 " /></a></li>
						<li><a <?=$mAnchor[3][1][4][0]?>><img src="/img/main/menu02_05.gif" alt="새로운칠곡" /></a></li>
						</ul>
						 
						<!--div class="more"><a href="http://mayor.chilgok.go.kr/sub/01_01.php" target="_blank"  title="새창뜨기"><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div-->
						<div class="close"><a href="#id_visual1c2_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c2 -->
						
						
						<!-- visual1c3 -->
						<div class="visual1c3">
						 
						
						<h4 class="nolink" id="id_visual1c3_11"><a href="#id_visual1c3_12"><img src="/img/main/main_img03.png" alt="주민참여. 주민의 소중한 의견이 더 희망찬 칠곡을 만듭니다" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c3_12"><div class="bg"></div>
						
						
						<h5><img src="/img/main/menu03_01.gif" alt="주민참여" /></h5>
						<ul>
						<li><a <?=$mAnchor[1][2][1][0]?>><img src="/img/main/menu03_02.gif" alt="자유게시판" /></a></li>
						<li><a <?=$mAnchor[1][2][2][0]?>><img src="/img/main/menu03_03.gif" alt="군민제안" /></a></li>
						<li><a <?=$mAnchor[1][2][3][0]?>><img src="/img/main/menu03_04.gif" alt="자유기고" /></a></li>
						<li><a <?=$mAnchor[1][2][5][0]?>><img src="/img/main/menu03_05.gif" alt="사고팝니다" /></a></li>
						</ul>
						<div class="more"><a <?=$mAnchor[1][2][6][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div>
						<div class="close"><a href="#id_visual1c3_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont --> 
						</div>
						<!-- /visual1c3 -->
						
						<!-- visual1c4 -->
						<div class="visual1c4">
						<!-- 여기는 알림정보 코딩임 -->
						<div id="m6scroll">
						<ul  id="m6content">
						<jsp:include page="/common/main/inc_popupzone_portal.jsp" flush="true">
							<jsp:param name="site_gubun" value="DEFAULT"/>
							<jsp:param name="start" value="1"/>
						</jsp:include>
						</ul>
						</div>
						<script type="text/javascript">initmTicker(document.getElementById('m6scroll'),document.getElementById('m6content'),3000,165);</script>
						<div class="banner"><img src="/img/main/popup.png" alt="알림정보" class="png24" />
						<span class="prev01"><a href="#m6" onclick="prevmTicker(document.getElementById('m6scroll'));return false;" title="이전 배너 보기" class="prev_banner"><img src="/img/main/popup_prev.gif" alt="prev" /></a>&nbsp;<a href="#m6" onclick="stopmTicker(document.getElementById('m6scroll'));return false;" title="배너 순환 멈춤" class="stop"><img src="/img/main/popup_stop.gif" alt="stop" /></a>&nbsp;<a href="#m6" onclick="nextmTicker(document.getElementById('m6scroll'));return false;" title="다음 배너 보기" class="next_banner"><img src="/img/main/popup_next.gif" alt="next" /></a></span>
						</div>
						<!-- //여기는 알림정보 코딩임 -->
						</div>
						<!-- /visual1c4 -->
						
						<!-- visual1c5 -->
						<div class="visual1c5">
						 
						
						<h4 class="nolink" id="id_visual1c5_11"><a href="#id_visual1c5_12"><img src="/img/main/main_img05.png" alt="부동산 정보. 칠곡군의 부동산 정보가 궁금하세요? 여기를 클릭하세요" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c5_12"><div class="bg"></div>
						<h5><img src="/img/main/menu05_01.gif" alt="부동산정보" /></h5>
						<ul>
						<li><a <?=$mAnchor[6][1][1][0]?>><img src="/img/main/menu05_02.gif" alt="부동산실거래신고제도" /></a></li>
						<li><a <?=$mAnchor[6][1][2][0]?>><img src="/img/main/menu05_03.gif" alt="토지거래계약허가" /></a></li>
						<li><a <?=$mAnchor[6][1][3][0]?>><img src="/img/main/menu05_04.gif" alt="개별공시지가열람" /></a></li>
						<li><a <?=$mAnchor[6][1][4][0]?>><img src="/img/main/menu05_05.gif" alt="개별주택가격열람" /></a></li>
						</ul>
						<div class="more"><a <?=$mAnchor[6][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div>
						<div class="close"><a href="#id_visual1c5_11"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c5 -->
						<div class="txt"><img src="/img/main/main_txt.png" alt="&quot;잘사는 군민 새로운 칠곡. 살기좋은 도시 희망이 커가는 도시" class="png24" /></div>
						</div>
						<!-- visualc1 -->
					</div>
                    


					<div>
						<!-- !!! visualc2 -->
						<div class="visualc2">
						
						<!-- visual1c1 -->
						<div class="visual1c1">
						 
						
						<h4 class="nolink" id="id_visual1c1_21"><a href="#id_visual1c1_22"><img src="/img/main/main_img06.png" alt="칠곡군 종합안내. 각종 군청안내를 제공합니다"  class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c1_22"><div class="bg"></div>
						<h5><img src="/img/main/menu06_01.gif" alt="칠곡군 종합안내" /></h5>
						<ul>
						<li><a <?=$mAnchor[3][3][2][0]?>><img src="/img/main/menu06_02.gif" alt="군청안내" /></a></li>
						<li><a <?=$mAnchor[2][4][0][0]?>><img src="/img/main/menu06_03.gif" alt="민원봉사과" /></a></li>
						<li><a <?=$mAnchor[3][6][0][0]?>><img src="/img/main/menu06_04.gif" alt="실과정보" /></a></li>
						<li><a <?=$mAnchor[3][3][1][0]?>><img src="/img/main/menu06_05.gif" alt="찾아오시는 길" /></a></li>                        
						 
						</ul>
						 
						<div class="close"><a href="#id_visual1c1_21"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c1 -->
						
						<!-- visual1c2 -->
						<div class="visual1c2">
						 
						
						<h4 class="nolink" id="id_visual1c2_21"><a href="#id_visual1c2_22"><img src="/img/main/main_img07.png" alt="군민제안. 주민여러분의 아이디어를 담아주세요 " class="png24 " /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c2_22"><div class="bg"></div>
						<h5><img src="/img/main/menu07_01.gif" alt="군민제안" /></h5>
						<ul>
						<li><a <?=$mAnchor[1][2][2][1]?>><img src="/img/main/menu07_02.gif" alt="제안안내" /></a></li>
						<li><a href="http://www.chilgok.go.kr/01partin/02_02_02_01.jsp" title="제안 응모(제출)"><img src="/img/main/menu07_03.gif" alt="제안 응모(제출)" /></a></li>
						 
						</ul>
						 
						<div class="close"><a href="#id_visual1c2_21"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c2 -->
						
						
						<!-- visual1c3 -->
						<div class="visual1c3">

						<!-- 여기는 알림정보 코딩임 -->
						<div id="m7">
				 
						<div id="m7scroll">
						<ul  id="m7content">
						<jsp:include page="/common/main/inc_popupzone_portal.jsp" flush="true">
							<jsp:param name="site_gubun" value="DEFAULT"/>
							<jsp:param name="start" value="2"/>
						</jsp:include>
						</ul>
						</div>				 
						<script type="text/javascript">initmTicker(document.getElementById('m7scroll'),document.getElementById('m7content'),3000,165);</script>
						<div class="banner"><img src="/img/main/popup.png" alt="알림정보" class="png24" />
						<span class="prev01"><a href="#m7" onclick="prevmTicker(document.getElementById('m7scroll'));return false;" title="이전 배너 보기" class="prev_banner"><img src="/img/main/popup_prev.gif" alt="prev" /></a>&nbsp;<a href="#m7" onclick="stopmTicker(document.getElementById('m7scroll'));return false;" title="배너 순환 멈춤" class="stop"><img src="/img/main/popup_stop.gif" alt="stop" /></a>&nbsp;<a href="#m7" onclick="nextmTicker(document.getElementById('m7scroll'));return false;" title="다음 배너 보기" class="next_banner"><img src="/img/main/popup_next.gif" alt="next" /></a></span>
						</div>
                        
						</div>
						<!-- //여기는 알림정보 코딩임 -->
						 
						</div>
						<!-- /visual1c3 -->
						
						<!-- visual1c4 -->
						<div class="visual1c4">
						 
						
						<h4 class="nolink" id="id_visual1c4_21"><a href="#id_visual1c4_22"><img src="/img/main/main_img09.png" alt="신고센터. 작은관심이 살기좋은 칠곡을 만듭니다" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c4_22"><div class="bg"></div>
						<h5><img src="/img/main/menu09_01.gif" alt="신고센터" /></h5>
						<ul>
						<li><a <?=$mAnchor[1][3][1][0]?>><img src="/img/main/menu09_02.gif" alt="예산낭비신고" /></a></li>
						<li><a <?=$mAnchor[1][3][2][0]?>><img src="/img/main/menu09_03.gif" alt="규제개혁신고" /></a></li>
						<li><a <?=$mAnchor[1][3][4][0]?>><img src="/img/main/menu09_04.gif" alt="부정불량식품신고" /></a></li>
						<li><a <?=$mAnchor[1][3][5][0]?>><img src="/img/main/menu09_05.gif" alt="공직자 비리신고" /></a></li>
						</ul>
						<div class="more"><a <?=$mAnchor[1][3][6][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div>
						<div class="close"><a href="#id_visual1c4_21"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c4 -->
						
						<!-- visual1c5 -->
						<div class="visual1c5">
						 
						
						<h4 class="nolink" id="id_visual1c5_21"><a href="#id_visual1c5_22"><img src="/img/main/main_img10.png" alt="칠곡소개. 희망이 커가는 도시 칠곡을 소개합니다." class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual1c5_22"><div class="bg"></div>
						<h5><img src="/img/main/menu10_01.gif" alt="칠곡소개" /></h5>
						<ul>
						<li><a <?=$mAnchor[7][1][1][0]?>><img src="/img/main/menu10_02.gif" alt="연혁" /></a></li>
						<li><a <?=$mAnchor[7][1][2][0]?>><img src="/img/main/menu10_03.gif" alt="상징물" /></a></li>
						<li><a <?=$mAnchor[7][1][3][0]?>><img src="/img/main/menu10_04.gif" alt="자연환경" /></a></li>
						<li><a <?=$mAnchor[7][1][4][0]?>><img src="/img/main/menu10_05.gif" alt="군정현황" /></a></li>
						</ul>
						<div class="more"><a <?=$mAnchor[7][2][1][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div>
						<div class="close"><a href="#id_visual1c5_21"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c5 -->
						<div class="txt"><img src="/img/main/main_txt.png" alt="&quot;잘사는 군민 새로운 칠곡. 살기좋은 도시 희망이 커가는 도시" class="png24" /></div>
						
						</div>
						<!-- visualc2 -->					
					</div>
					
				</div><!-- //slides_container-->
				<div class="control">
				<span class="prev"><a href="#slides" title="앞 메뉴로 이동"><img src="/img/main/m1prev.png" alt="prev" class="png24"  /></a></span>
				<span class="next"><a href="#slides" title="뒷 메뉴로 이동"><img src="/img/main/m1next.png" alt="next" class="png24"  /></a></span>				
				</div>			
        
			
			
			</div>	<!-- //slides-->

			</div><!--//example-->

		</div><!--//container_s-->
		<div class="all"><a href="#ok01" title="전체 메뉴 보기" onclick="return false;"><img src="/img/main/btn_all.gif" alt="전체보기 +"  id="btn_menuall_show"/></a></div>
		
        
		
		<div class="visualc3" id="ok01" style="display:none;">
			<!-- visual3c1 -->
			<div class="visual3c1">
			
			
						<h4 class="nolink" id="id_visual3c1_31"><a href="#id_visual3c1_32"><img src="/img/main/main_img01.png" alt="취업지원센터. 일하는 즐거움을 나눕니다."  class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual3c1_32"><div class="bg"></div>
						<h5><img src="/img/main/menu01_01.gif" alt="취업지원센터" /></h5>
						<ul>
						<li><a <?=$mAnchor[4][1][1][0]?>><img src="/img/main/menu01_02.gif" alt="구인구직" /></a></li>
						<li><a <?=$mAnchor[4][1][2][0]?>><img src="/img/main/menu01_03.gif" alt="공공취업정보" /></a></li>
						<li><a <?=$mAnchor[4][1][3][0]?>><img src="/img/main/menu01_04.gif" alt="공공근로사업" /></a></li>
						<li><a <?=$mAnchor[4][1][4][0]?>><img src="/img/main/menu01_05.gif" alt="희망근로" /></a></li>
						</ul>
						<div class="more"><a <?=$mAnchor[4][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div>
						<div class="close"><a href="#id_visual3c1_31"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual3c1 -->
						
			
			<!-- visual3c2 -->
			<div class="visual3c2">
			 
			
						<h4 class="nolink" id="id_visual3c2_31"><a href="#id_visual3c2_32"><img src="/img/main/main_img11.png" alt="열린군수실. 살기좋은 칠곡을 만들겠습니다 " class="png24" /></a></h4>
						
						<!-- cont -->
						<div class="cont dpn" id="id_visual3c2_32"><div class="bg"></div>
						<h5><img src="/img/main/menu02_01.gif" alt="열린군수실" /></h5>
                        <ul>
						<li><a <?=$mAnchor[3][1][1][0]?>><img src="/img/main/menu02_02.gif" alt="백선기입니다." /></a></li>
						<li><a <?=$mAnchor[3][1][2][0]?>><img src="/img/main/menu02_03.gif" alt="실천25시 " /></a></li>
						<li><a <?=$mAnchor[3][1][3][0]?>><img src="/img/main/menu02_04.gif" alt="생생!현장속으로 " /></a></li>
						<li><a <?=$mAnchor[3][1][4][0]?>><img src="/img/main/menu02_05.gif" alt="새로운칠곡" /></a></li>
						</ul>
						 
						<!--div class="more"><a href="http://mayor.chilgok.go.kr/sub/01_01.php" target="_blank"  title="새창뜨기"><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div-->
						<div class="close"><a href="#id_visual3c2_31"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c2 -->
			
			
			<!-- visual3c3 -->
			<div class="visual3c3">
			 
			
						<h4 class="nolink" id="id_visual3c3_31"><a href="#id_visual3c3_32"><img src="/img/main/main_img03.png" alt="주민참여. 주민의 소중한 의견이 더 희망찬 칠곡을 만듭니다" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual3c3_32"><div class="bg"></div>
						
						
						<h5><img src="/img/main/menu03_01.gif" alt="주민참여" /></h5>
						<ul>
						<li><a <?=$mAnchor[1][2][1][0]?>><img src="/img/main/menu03_02.gif" alt="자유게시판" /></a></li>
						<li><a <?=$mAnchor[1][2][2][0]?>><img src="/img/main/menu03_03.gif" alt="군민제안" /></a></li>
						<li><a <?=$mAnchor[1][2][3][0]?>><img src="/img/main/menu03_04.gif" alt="자유기고" /></a></li>
						<li><a <?=$mAnchor[1][2][5][0]?>><img src="/img/main/menu03_05.gif" alt="사고팝니다" /></a></li>
						</ul>
						<div class="more"><a <?=$mAnchor[1][2][6][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div>
						<div class="close"><a href="#id_visual3c3_31"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont --> 
						</div>
						<!-- /visual1c3 -->
			
			<!-- visual3c4 -->
			<div class="visual3c4">

			<!-- 여기는 알림정보 코딩임 -->
			<div id="m8">
		 
			<div id="m8scroll">
			<ul  id="m8content">
			<jsp:include page="/common/main/inc_popupzone_portal.jsp" flush="true">
				<jsp:param name="site_gubun" value="DEFAULT"/>
				<jsp:param name="start" value="1"/>
			</jsp:include>
			</ul>
			</div>
		 
			<script type="text/javascript">initmTicker(document.getElementById('m8scroll'),document.getElementById('m8content'),3000,165);</script>
			<div class="banner"><img src="/img/main/popup.png" alt="알림정보" class="png24" />
			<div class="control_banner">
			<span class="prev01"><a href="#m8" onclick="prevmTicker(document.getElementById('m8scroll'));return false;" title="이전 배너 보기" class="prev_banner"><img src="/img/main/popup_prev.gif" alt="prev" /></a>&nbsp;<a href="#m8" onclick="stopmTicker(document.getElementById('m8scroll'));return false;" title="배너 순환 멈춤" class="stop"><img src="/img/main/popup_stop.gif" alt="stop" /></a>&nbsp;<a href="#m8" onclick="nextmTicker(document.getElementById('m8scroll'));return false;" title="다음 배너 보기" class="next_banner"><img src="/img/main/popup_next.gif" alt="next" /></a></span>
			</div>
			</div>
			</div>
			<!-- //여기는 알림정보 코딩임 -->
			
			</div>
			<!-- /visual3c4 -->
			
			<!-- visual3c5 -->
			<div class="visual3c5">
			 
						
						<h4 class="nolink" id="id_visual3c5_31"><a href="#id_visual3c5_32"><img src="/img/main/main_img05.png" alt="부동산 정보. 칠곡군의 부동산 정보가 궁금하세요? 여기를 클릭하세요" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual3c5_32"><div class="bg"></div>
						<h5><img src="/img/main/menu05_01.gif" alt="부동산정보" /></h5>
						<ul>
						<li><a <?=$mAnchor[6][1][1][0]?>><img src="/img/main/menu05_02.gif" alt="부동산실거래신고제도" /></a></li>
						<li><a <?=$mAnchor[6][1][2][0]?>><img src="/img/main/menu05_03.gif" alt="토지거래계약허가" /></a></li>
						<li><a <?=$mAnchor[6][1][3][0]?>><img src="/img/main/menu05_04.gif" alt="개별공시지가열람" /></a></li>
						<li><a <?=$mAnchor[6][1][4][0]?>><img src="/img/main/menu05_05.gif" alt="개별주택가격열람" /></a></li>
						</ul>
						<div class="more"><a <?=$mAnchor[6][1][5][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div>
						<div class="close"><a href="#id_visual3c5_31"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
						<!-- /visual1c5 -->
			
			<!-- visual3c6 -->
			<div class="visual3c6">
			 
						
						<h4 class="nolink" id="id_visual3c6_31"><a href="#id_visual3c6_32"><img src="/img/main/main_img06.png" alt="칠곡군 종합안내. 각종 군청안내를 제공합니다"  class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual3c6_32"><div class="bg"></div>
						<h5><img src="/img/main/menu06_01.gif" alt="칠곡군 종합안내" /></h5>
						<ul>
						<li><a <?=$mAnchor[3][3][2][0]?>><img src="/img/main/menu06_02.gif" alt="군청안내" /></a></li>
						<li><a <?=$mAnchor[2][4][0][0]?>><img src="/img/main/menu06_03.gif" alt="민원봉사과" /></a></li>
						<li><a <?=$mAnchor[3][6][0][0]?>><img src="/img/main/menu06_04.gif" alt="실과정보" /></a></li>
						<li><a <?=$mAnchor[3][3][1][0]?>><img src="/img/main/menu06_05.gif" alt="찾아오시는 길" /></a></li>                        
						 
						</ul>
						 
						<div class="close"><a href="#id_visual3c6_31"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
			<!-- /visual3c6 -->
			
			<!-- visual3c7 -->
			<div class="visual3c7">
			 
						
						<h4 class="nolink" id="id_visual3c7_31"><a href="#id_visual3c7_32"><img src="/img/main/main_img07.png" alt="군민제안. 주민여러분의 아이디어를 담아주세요 " class="png24 " /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual3c7_32"><div class="bg"></div>
						<h5><img src="/img/main/menu07_01.gif" alt="군민제안" /></h5>
						<ul>
						<li><a <?=$mAnchor[1][2][2][1]?>><img src="/img/main/menu07_02.gif" alt="제안안내" /></a></li>
						<li><a <?=$mAnchor[1][2][2][2]?>><img src="/img/main/menu07_03.gif" alt="제안 응모(제출)" /></a></li>
						 
						</ul>
						 
						<div class="close"><a href="#id_visual3c7_31"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
			<!-- /visual3c7 -->
			
			
			<!-- visual3c8 -->
			<div class="visual3c8">

			<!-- 여기는 알림정보 코딩임 -->
			<div id="m9">
			 
			<div id="m9scroll">
			<ul  id="m9content">
			<jsp:include page="/common/main/inc_popupzone_portal.jsp" flush="true">
				<jsp:param name="site_gubun" value="DEFAULT"/>
				<jsp:param name="start" value="2"/>
			</jsp:include>
			</ul>
			</div>
			 
			<script type="text/javascript">initmTicker(document.getElementById('m9scroll'),document.getElementById('m9content'),3000,165);</script>
			<div class="banner"><img src="/img/main/popup.png" alt="알림정보" class="png24" />
			<div class="control_banner">
			<span class="prev01"><a href="#m9" onclick="prevmTicker(document.getElementById('m9scroll'));return false;" title="이전 배너 보기" class="prev_banner"><img src="/img/main/popup_prev.gif" alt="prev" /></a>&nbsp;<a href="#m9" onclick="stopmTicker(document.getElementById('m9scroll'));return false;" title="배너 순환 멈춤" class="stop"><img src="/img/main/popup_stop.gif" alt="stop" /></a>&nbsp;<a href="#m9" onclick="nextmTicker(document.getElementById('m9scroll'));return false;" title="다음 배너 보기" class="next_banner"><img src="/img/main/popup_next.gif" alt="next" /></a></span>
			</div>
			</div>
			</div>
			<!-- //여기는 알림정보 코딩임 -->
			 
			</div>
			<!-- /visual3c8 -->
			
			<!-- visual3c9 -->
			<div class="visual3c9">
			 
						
						<h4 class="nolink" id="id_visual3c9_31"><a href="#id_visual3c9_32"><img src="/img/main/main_img09.png" alt="신고센터. 작은관심이 살기좋은 칠곡을 만듭니다" class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual3c9_32"><div class="bg"></div>
						<h5><img src="/img/main/menu09_01.gif" alt="신고센터" /></h5>
						<ul>
						<li><a <?=$mAnchor[1][3][1][0]?>><img src="/img/main/menu09_02.gif" alt="예산낭비신고" /></a></li>
						<li><a <?=$mAnchor[1][3][2][0]?>><img src="/img/main/menu09_03.gif" alt="규제개혁신고" /></a></li>
						<li><a <?=$mAnchor[1][3][4][0]?>><img src="/img/main/menu09_04.gif" alt="부정불량식품신고" /></a></li>
						<li><a <?=$mAnchor[1][3][5][0]?>><img src="/img/main/menu09_05.gif" alt="공직자비리신고" /></a></li>
						</ul>
						<div class="more"><a <?=$mAnchor[1][3][4][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div>
						<div class="close"><a href="#id_visual3c9_31"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
			<!-- /visual3c9 -->
			
			<!-- visual3c10 -->
			<div class="visual3c10">
			 
						<h4 class="nolink" id="id_visual3c10_31"><a href="#id_visual3c10_32"><img src="/img/main/main_img10.png" alt="칠곡소개. 희망이 커가는 도시 칠곡을 소개합니다." class="png24" /></a></h4>
						<!-- cont -->
						<div class="cont dpn" id="id_visual3c10_32"><div class="bg"></div>
						<h5><img src="/img/main/menu10_01.gif" alt="칠곡소개" /></h5>
						<ul>
						<li><a <?=$mAnchor[7][1][1][0]?>><img src="/img/main/menu10_02.gif" alt="연혁" /></a></li>
						<li><a <?=$mAnchor[7][1][2][0]?>><img src="/img/main/menu10_03.gif" alt="상징물" /></a></li>
						<li><a <?=$mAnchor[7][1][3][0]?>><img src="/img/main/menu10_04.gif" alt="자연환경" /></a></li>
						<li><a <?=$mAnchor[7][1][4][0]?>><img src="/img/main/menu10_05.gif" alt="군정현황" /></a></li>
						</ul>
						<div class="more"><a <?=$mAnchor[2][3][6][0]?>><img src="/img/main/menu_more.gif" alt="메뉴더보기" /></a></div>
						<div class="close"><a href="#id_visual3c10_31"><img src="/img/main/menu_close.gif" alt="닫기 X" /></a></div>
						</div>
						<!-- /cont -->
						</div>
			<!-- /visual3c10 -->
			 
			 
		<div class="all01"><a href="#slides" onclick="return false;" id="btn_menuall_hide" class="button" title="전체메뉴닫기"><img src="../img/main/btn_all01.gif" alt="전체보기 -" /></a></div>		
		</div>
		<!-- visualc3 -->

	
