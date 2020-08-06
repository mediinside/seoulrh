<?php

$Slide_B = Main_Slide('B',"",""); //탑배너

?>
        <ul class="skipNav">
			<li><a href="#container">본문 바로가기</a></li>
		</ul>
		<div class="header-thumb swiper-container" style="top:0;" id="topSlide">
			<ul class="swiper-wrapper">
						<?=$Slide_B?>
			</ul>
			<div class="header-thumb-close">
				<a href="#none">
					<!-- <img src="/resource/images/close-btn.png" alt="" width="10"> -->
					<label for="today_hide">
						<input type="checkbox" id="today_hide" onclick="closeSlide();"> 오늘하루 열지않기
					</label>
				</a>
				<a class="today-close" href="#none">[닫기]</a>
			</div>
		</div>
		<header id="header" class="">
			<div class="header-inner clearfix wd-1700">
				<div class="header-logo"><a href="/"><img src="/resource/images/logo.png"></a></div>
				<div class="header-menu">
					<ul>
						<li>
							<a class="ani-a02" href="/info/page01.html"><span>병원안내</span></a>
							<div class="sub-dep">
								<ul>
									<li>병원안내</li>
									<li>
										<dl>
											<dt><a href="/info/page01.html">병원안내</a></dt>
											<dd><a href="/info/page01.html">병원소개</a></dd>
											<dd><a href="/info/page02.html">병원장인사말</a></dd>
											<dd><a href="/info/page03.html">미션/비전/HI</a></dd>
											<dd><a href="/info/page04.html?h_type=4">병원연혁</a></dd>
											<dd><a href="/info/page05.html">협력기관</a></dd>
											<dd><a href="/info/page06.html">조직도</a></dd>
										</dl>
									</li>
									<li>
										<div class="menu-bnnr">
											<a href="https://secure.donus.org/srh/pay/step1" target="_blank">
												<img src="resource/images/menu-bnnr.png" alt="">
											</a>
											<div class="menu-bnnr-btn">
												<a href="/notice/notice.php?jb_code=50">고객의 소리</a>
												<a href="/use/page01.html">진료안내</a>
											</div>
											<table>
												<colgroup>
													<col style="width:80px;">
													<col>
												</colgroup>
												<thead>
													<tr>
														<th>대표전화</th>
														<td>02-6020-<span class="color-green">3000</span></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th>평일</th>
														<td>AM09:00 - PM05:30</td>
													</tr>
													<tr>
														<th>점심시간</th>
														<td>PM12:30 - PM01:30</td>
													</tr>
													<tr>
														<th></th>
														<td class="color-green">토요일, 일요일, 공휴일 휴진</td>
													</tr>
												</tbody>
											</table>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a class="ani-a02" href="/use/page01.html"><span>진료/이용안내</span></a>
							<div class="sub-dep">
								<ul>
									<li>진료 / 이용안내</li>
									<li>
										<dl>
											<dt><a href="/use/page01.html">진료안내</a></dt>
											<dd><a href="/use/page01.html">의료진 / 진료시간 안내</a></dd>
											<dd><a href="/use/page02.html">외래진료 안내</a></dd>
											<dd><a href="/use/page03.html">입원 안내</a></dd>
											<dd><a href="/use/page04.html">낮병동 안내</a></dd>
											<dd><a href="/use/page05.html">특수검사</a></dd>
										</dl>
									</li>
									<li>
										<dl>
											<dt><a href="/use/page06-1.html">이용안내</a></dt>
											<dd><a href="/use/page06-1.html">병원층별안내</a></dd>
											<dd><a href="/use/page07.html">전화번호</a></dd>
											<dd><a href="/use/page08-1.html">오시는길</a></dd>
											<dd><a href="/use/page09.html">면회안내</a></dd>
											<dd><a href="/use/page10.html">증명서발급 안내</a></dd>
											<dd><a href="http://www.hira.or.kr/re/diag/getNpayNotiCsuiSvcFom.do?yadmSbstKey=SKUNmUk9Aek9I54v" target="_blank">비급여진료비</a></dd>
										</dl>
									</li>
									<li>
										<div class="menu-bnnr">
											<a href="https://secure.donus.org/srh/pay/step1" target="_blank">
												<img src="resource/images/menu-bnnr.png" alt="">
											</a>
											<div class="menu-bnnr-btn">
												<a href="/notice/notice.php?jb_code=50">고객의 소리</a>
												<a href="/use/page01.html">진료안내</a>
											</div>
											<table>
												<colgroup>
													<col style="width:80px;">
													<col>
												</colgroup>
												<thead>
													<tr>
														<th>대표전화</th>
														<td>02-6020-<span class="color-green">3000</span></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th>평일</th>
														<td>AM09:00 - PM05:30</td>
													</tr>
													<tr>
														<th>점심시간</th>
														<td>PM12:30 - PM01:30</td>
													</tr>
													<tr>
														<th></th>
														<td class="color-green">토요일, 일요일, 공휴일 휴진</td>
													</tr>
												</tbody>
											</table>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a class="ani-a02" href="/service/page01.html"><span>재활서비스</span></a>
							<div class="sub-dep">
								<ul>
									<li>
										재활서비스
									</li>
									<li>
										<dl>
											<dt><a href="/service/page01.html">소아/청소년재활</a></dt>
											<dd><a href="/service/page01.html">질환소개(관련클리닉)</a></dd>
											<dd><a href="/service/page02.html">입원/낮병동/외래</a></dd>
											<dd><a href="/service/page03-1.html">재활치료(물리, 작업, 언어, 심리)</a></dd>
											<dd><a href="/service/page04.html">생애주기별 관리 시스템</a></dd>
										</dl>
									</li>
									<li>
										<dl>
											<dt><a href="/service/page05.html">뇌신경/척추손상 재활</a></dt>
											<dd><a href="/service/page05.html">질환소개(관련클리닉)</a></dd>
											<dd><a href="/service/page06.html">입원/낮병동/외래</a></dd>
											<dd><a href="/service/page07-1.html">재활치료(물리, 작업, 언어, 심리)</a></dd>
											<dd><a href="/service/page08.html">특수치료프로그램</a></dd>
											<dd><a href="/service/page09.html">회복기재활안내</a></dd>
										</dl>
									</li>
									<li>
										<dl>
											<dt><a href="/service/page10-1.html">근골격/스포츠재활</a></dt>
											<dd><a href="/service/page10-1.html">통증치료</a></dd>
											<dd><a href="/service/page10-2.html">도수치료</a></dd>
										</dl>
									</li>
									<li>
										<div class="menu-bnnr">
											<a href="https://secure.donus.org/srh/pay/step1" target="_blank">
												<img src="resource/images/menu-bnnr.png" alt="">
											</a>
											<div class="menu-bnnr-btn">
												<a href="/notice/notice.php?jb_code=50">고객의 소리</a>
												<a href="/use/page01.html">진료안내</a>
											</div>
											<table>
												<colgroup>
													<col style="width:80px;">
													<col>
												</colgroup>
												<thead>
													<tr>
														<th>대표전화</th>
														<td>02-6020-<span class="color-green">3000</span></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th>평일</th>
														<td>AM09:00 - PM05:30</td>
													</tr>
													<tr>
														<th>점심시간</th>
														<td>PM12:30 - PM01:30</td>
													</tr>
													<tr>
														<th></th>
														<td class="color-green">토요일, 일요일, 공휴일 휴진</td>
													</tr>
												</tbody>
											</table>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a class="ani-a02" href="/edu/page01.html"><span>공공재활/교육사업</span></a>
							<div class="sub-dep">
								<ul>
									<li>공공재활 / 교육사업</li>
									<li>
										<dl>
											<dt><a href="/edu/page01.html">공공재활/교육사업</a></dt>
											<dd><a href="/edu/page01.html">사회복귀지원사업</a></dd>
											<dd><a href="/edu/page02.html">환자가족행복사업</a></dd>
											<dd><a href="/edu/page03.html">지역사회건강증진사업</a></dd>
											<dd><a href="/edu/page04.html">저소득환자지원사업</a></dd>
											<dd><a href="/edu/page05-1.html">국제협력사업</a></dd>
											<dd><a href="/edu/page06-1.html">연구사업</a></dd>
											<dd><a href="/edu/page07.html">교육사업</a></dd>
										</dl>
									</li>
									<li>
										<div class="menu-bnnr">
											<a href="https://secure.donus.org/srh/pay/step1" target="_blank">
												<img src="resource/images/menu-bnnr.png" alt="">
											</a>
											<div class="menu-bnnr-btn">
												<a href="/notice/notice.php?jb_code=50">고객의 소리</a>
												<a href="/use/page01.html">진료안내</a>
											</div>
											<table>
												<colgroup>
													<col style="width:80px;">
													<col>
												</colgroup>
												<thead>
													<tr>
														<th>대표전화</th>
														<td>02-6020-<span class="color-green">3000</span></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th>평일</th>
														<td>AM09:00 - PM05:30</td>
													</tr>
													<tr>
														<th>점심시간</th>
														<td>PM12:30 - PM01:30</td>
													</tr>
													<tr>
														<th></th>
														<td class="color-green">토요일, 일요일, 공휴일 휴진</td>
													</tr>
												</tbody>
											</table>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a class="ani-a02" href="/donation/page01-1.html"><span>나눔안내</span></a>
							<div class="sub-dep">
								<ul>
									<li>나눔안내</li>
									<li>
										<dl>
											<dt><a href="/donation/page01-1.html">나눔안내</a></dt>
											<dd><a href="/donation/page01-1.html">후원신청</a></dd>
											<dd><a href="/donation/page02-1.html">후원현황</a></dd>
											<dd><a href="/donation/page03.html">자원봉사안내</a></dd>
										</dl>
									</li>
									<li>
										<div class="menu-bnnr">
											<a href="https://secure.donus.org/srh/pay/step1" target="_blank">
												<img src="resource/images/menu-bnnr.png" alt="">
											</a>
											<div class="menu-bnnr-btn">
												<a href="/notice/notice.php?jb_code=50">고객의 소리</a>
												<a href="/use/page01.html">진료안내</a>
											</div>
											<table>
												<colgroup>
													<col style="width:80px;">
													<col>
												</colgroup>
												<thead>
													<tr>
														<th>대표전화</th>
														<td>02-6020-<span class="color-green">3000</span></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th>평일</th>
														<td>AM09:00 - PM05:30</td>
													</tr>
													<tr>
														<th>점심시간</th>
														<td>PM12:30 - PM01:30</td>
													</tr>
													<tr>
														<th></th>
														<td class="color-green">토요일, 일요일, 공휴일 휴진</td>
													</tr>
												</tbody>
											</table>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a class="ani-a02" href="/notice/notice.php?jb_code=10"><span>병원소식/참여</span></a>
							<div class="sub-dep">
								<ul>
									<li>병원소식/참여</li>
									<li>
										<dl>
											<dt><a href="/notice/notice.php?jb_code=10">병원소식/참여</a></dt>
											<dd><a href="/notice/notice.php?jb_code=10">공지사항</a></dd>
											<dd><a href="/notice/notice.php?jb_code=20">병원소식</a></dd>
											<dd><a href="/notice/notice.php?jb_code=30">언론보도</a></dd>
											<dd><a href="/notice/notice.php?jb_code=40">자주하는 질문</a></dd>
											<dd><a href="/notice/notice.php?jb_code=50">고객의 소리</a></dd>
											<dd><a href="/notice/notice.php?jb_code=60">채용정보</a></dd>
											<dd><a href="/notice/notice.php?jb_code=70">연차보고서</a></dd>
											<dd><a href="/notice/notice.php?jb_code=80">재활치료 영상</a></dd>
										</dl>
									</li>
									<li>
										<div class="menu-bnnr">
											<a href="https://secure.donus.org/srh/pay/step1" target="_blank">
												<img src="resource/images/menu-bnnr.png" alt="">
											</a>
											<div class="menu-bnnr-btn">
												<a href="/notice/notice.php?jb_code=50">고객의 소리</a>
												<a href="/use/page01.html">진료안내</a>
											</div>
											<table>
												<colgroup>
													<col style="width:80px;">
													<col>
												</colgroup>
												<thead>
													<tr>
														<th>대표전화</th>
														<td>02-6020-<span class="color-green">3000</span></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th>평일</th>
														<td>AM09:00 - PM05:30</td>
													</tr>
													<tr>
														<th>점심시간</th>
														<td>PM12:30 - PM01:30</td>
													</tr>
													<tr>
														<th></th>
														<td class="color-green">토요일, 일요일, 공휴일 휴진</td>
													</tr>
												</tbody>
											</table>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a class="ani-a02" href="/center/page01-1.html"><span>서울특별시북부<br>지역장애인보건의료센터</span></a>
							<div class="sub-dep">
								<ul>
									<li>서울특별시북부<br>지역장애인<br>보건의료센터</li>
									<li>
										<dl>
											<dt><a href="/center/page01-1.html">서울특별시북부<br>지역장애인보건의료센터</a></dt>
											<dd><a href="/center/page01-1.html">센터소개</a></dd>
											<dd><a href="/center/page02-1.html">주요업무안내</a></dd>
										</dl>
									</li>
									<li>
										<div class="menu-bnnr">
											<a href="https://secure.donus.org/srh/pay/step1" target="_blank">
												<img src="resource/images/menu-bnnr.png" alt="">
											</a>
											<div class="menu-bnnr-btn">
												<a href="/notice/notice.php?jb_code=50">고객의 소리</a>
												<a href="/use/page01.html">진료안내</a>
											</div>
											<table>
												<colgroup>
													<col style="width:80px;">
													<col>
												</colgroup>
												<thead>
													<tr>
														<th>대표전화</th>
														<td>02-6020-<span class="color-green">3000</span></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th>평일</th>
														<td>AM09:00 - PM05:30</td>
													</tr>
													<tr>
														<th>점심시간</th>
														<td>PM12:30 - PM01:30</td>
													</tr>
													<tr>
														<th></th>
														<td class="color-green">토요일, 일요일, 공휴일 휴진</td>
													</tr>
												</tbody>
											</table>
										</div>
									</li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
				<div class="header-r-menu clearfix">
					<div class="header-lang">
						<a href="#none">
							<img src="/resource/images/lang.png">
						</a>
						<div class="lang-ch">
							<a href="/landing/chn/index.html">CHN</a>
							<a href="/landing/eng/index.html">ENG</a>
						</div>
					</div>
					<div class="header-allBtn">
						<a href="#none">
							<img class="show on" src="/resource/images/menu.png">
							<img class="off" src="/resource/images/close-btn.png">
						</a>
						<div class="sub-dep ani-a02">
							<ul>
								<li class="btn-group m-menu-info">
									<a href="/notice/notice.php?jb_code=50" class="btn deep">고객의 소리</a>
									<a href="/use/page01.html" class="btn light">진료안내</a>
								</li>
								<li>
									<a href="#none">병원안내</a>
									<dl class="no-dt">
										<dt class="mb-hide"><a href="#none">병원안내</a></dt>
										<dd><a href="/info/page01.html">병원소개</a></dd>
										<dd><a href="/info/page02.html">병원장인사말</a></dd>
										<dd><a href="/info/page03.html">미션/비전/HI</a></dd>
										<dd><a href="/info/page04.html">병원연혁</a></dd>
										<dd><a href="/info/page05.html">협력기관</a></dd>
										<dd><a href="/info/page06.html">조직도</a></dd>
									</dl>
								</li>
								<li>
									<a href="#none">진료 및 이용안내</a>
									<dl>
										<dt><a href="#none">진료안내</a></dt>
										<dd><a href="/use/page01.html">의료진 / 진료시간 안내</a></dd>
										<dd><a href="/use/page02.html">외래진료 안내</a></dd>
										<dd><a href="/use/page03.html">입원 안내</a></dd>
										<dd><a href="/use/page04.html">낮병동 안내</a></dd>
										<dd><a href="/use/page05.html">특수검사</a></dd>
									</dl>
									<dl>
										<dt><a href="#none">이용안내</a></dt>
										<dd><a href="/use/page06-1.html">병원층별안내</a></dd>
										<dd><a href="/use/page07.html">전화번호</a></dd>
										<dd><a href="/use/page08-1.html">오시는길</a></dd>
										<dd><a href="/use/page09.html">면회안내</a></dd>
										<dd><a href="/use/page10.html">증명서발급 안내</a></dd>
										<dd><a href="http://www.hira.or.kr/re/diag/getNpayNotiCsuiSvcFom.do?yadmSbstKey=SKUNmUk9Aek9I54v" target="_blank">비급여진료비</a></dd>
									</dl>
								</li>
								<li>
									<a href="#none">재활서비스</a>
									<dl>
										<dt><a href="#none">소아/청소년재활</a></dt>
										<dd><a href="/service/page01.html">질환소개(관련클리닉)</a></dd>
										<dd><a href="/service/page02.html">입원/낮병동/외래</a></dd>
										<dd><a href="/service/page03-1.html">재활치료(물리, 작업, 언어, 심리)</a></dd>
										<dd><a href="/service/page04.html">생애주기별 관리 시스템</a></dd>
									</dl>
									<dl>
										<dt><a href="#none">뇌신경·척추손상 재활</a></dt>
										<dd><a href="/service/page05.html">질환소개(관련클리닉)</a></dd>
										<dd><a href="/service/page06.html">입원/낮병동/외래</a></dd>
										<dd><a href="/service/page07-1.html">재활치료(물리, 작업, 언어, 심리)</a></dd>
										<dd><a href="/service/page08.html">특수치료프로그램</a></dd>
										<dd><a href="/service/page09.html">회복기재활안내</a></dd>
									</dl>
									<dl>
										<dt><a href="#none">근골격/스포츠재활</a></dt>
										<dd><a href="/service/page10-1.html">도수치료/통증치료</a></dd>
									</dl>
								</li>
								<li>
									<a href="#none">공공재활 및 교육사업</a>
									<dl class="no-dt">
										<dt class="mb-hide"><a href="#none">공공재활 및 교육사업</a></dt>
										<dd><a href="/edu/page01.html">사회복귀지원사업</a></dd>
										<dd><a href="/edu/page02.html">환자가족행복사업</a></dd>
										<dd><a href="/edu/page03.html">지역사회건강증진사업</a></dd>
										<dd><a href="/edu/page04.html">저소득환자지원사업</a></dd>
										<dd><a href="/edu/page05-1.html">국제협력사업</a></dd>
										<dd><a href="/edu/page06-1.html">연구사업</a></dd>
										<dd><a href="/edu/page07.html">교육사업</a></dd>
									</dl>
								</li>
								<li>
									<a href="#none">나눔안내</a>
									<dl class="no-dt">
										<dt class="mb-hide"><a href="#none">나눔안내</a></dt>
										<dd><a href="/donation/page01-1.html">후원신청</a></dd>
										<dd><a href="/donation/page02-1.html">후원현황</a></dd>
										<dd><a href="/donation/page03.html">자원봉사안내</a></dd>
									</dl>
								</li>
								<li>
									<a href="#none">병원소식/참여</a>
									<dl class="no-dt">
										<dt class="mb-hide"><a href="#none">병원소식/참여</a></dt>
										<dd><a href="/notice/notice.php?jb_code=10">공지사항</a></dd>
										<dd><a href="/notice/notice.php?jb_code=20">병원소식</a></dd>
										<dd><a href="/notice/notice.php?jb_code=30">언론보도</a></dd>
										<dd><a href="/notice/notice.php?jb_code=40">자주하는 질문</a></dd>
										<dd><a href="/notice/notice.php?jb_code=50">고객의 소리</a></dd>
										<dd><a href="/notice/notice.php?jb_code=60">채용정보</a></dd>
										<dd><a href="/notice/notice.php?jb_code=70">연차보고서</a></dd>
										<dd><a href="/notice/notice.php?jb_code=80">재활치료 영상</a></dd>
									</dl>
								</li>
								<li>
									<a href="#none">서울특별시북부지역장애인보건의료센터</a>
									<dl class="no-dt">
										<dt class="mb-hide"><a href="#none">서울특별시북부지역장애인 <br class="mb-hide">보건의료센터</a></dt>
										<dd><a href="/center/page01-1.html">센터소개</a></dd>
										<dd><a href="/center/page02-1.html">주요업무안내</a></dd>
									</dl>
								</li>
								<li class="inf0">
									<a href="https://secure.donus.org/srh/pay/step1" target="_blank">
										<img src="/resource/images/m_b_1.jpg" alt="">
									</a>
									<a href="/notice/notice.php?jb_code=60">
										<img src="/resource/images/m_b_2.jpg" alt="">
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
            <script>
        $(document).ready(function(){
		if(getCookie('topSlide') == 'done') {
			$('#topSlide').remove();
		}
	    });

		function event_banner_off(){
			$(".header_top").remove();
		}

		function closeSlide() {
			setCookie( 'topSlide', 'done' , 1);
			$(".header_top").remove();
			$("#wrap").removeAttr("style");
		}
		function getCookie(name){
			var nameOfCookie = name + "=";
			var x = 0;
			while ( x <= document.cookie.length ) {
				var y = (x+nameOfCookie.length);
				if ( document.cookie.substring( x, y ) == nameOfCookie ) {
					if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
						endOfCookie = document.cookie.length;
					return unescape( document.cookie.substring( y, endOfCookie ) );
				}
				x = document.cookie.indexOf( " ", x ) + 1;
				if ( x == 0 )
				break;
			}
			return;
		}

		function setCookie(name, value, expiredays) {
			var todayDate = new Date();
			todayDate.setDate( todayDate.getDate() + expiredays );
			document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
		}
	</script>
		</header>
