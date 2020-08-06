<div class="sub-visual-wrap">
					<img src="/resource/images/notice-bnnr1.png" alt="">
				</div>
				<!-- //end .sub-visual-wrap -->
			</section>
			<!-- main section 01 end -->
			<!-- main section 02 start -->
			<section class="contents sub-contents">
				<div class="board-list wd-1200">
					<h3 class="sub-tit">채용정보</h3>
					<h4 class="sub-tit2">서울재활병원과 함께할 <br class="mb-show">열정있는 인재를 기다리고 있습니다.</h4>
					<ul class="vision">
						<li>
							<img src="/resource/images/mark1.png" alt="">
							<p>전문</p>
							<span>
								최신의 재활의료 지식과<br>
								최고의 실력으로 세상에<br>
								전문성을 나누는 사람
							</span>
						</li>
						<li>
							<img src="/resource/images/mark2.png" alt="">
							<p>사명</p>
							<span>
								재활의 사명을 나누며,<br>
								공익과 완전한 사회 통합을 위해<br>
								헌신하는 사람
							</span>
						</li>
						<li>
							<img src="/resource/images/mark3.png" alt="">
							<p>인성</p>
							<span>
								환자와 직원을 존중 및 사랑하며,<br>
								성실하고 겸손한 자세로<br>
								행복을 전하는 사람
							</span>
						</li>
						<li>
							<img src="/resource/images/mark4.png" alt="">
							<p>협력</p>
							<span>
								상호 신뢰와 배려의 문화 속에서<br>
								서로 화합하며, 최고의 팀웍을<br>
								이루는 사람
							</span>
						</li>
						<li>
							<img src="/resource/images/mark5.png" alt="">
							<p>도전</p>
							<span>
								비전을 향한 끊임없는 도전과<br>
								창의적인 사고로 혁신하며,<br>
								함께 성장하는 사람
							</span>
						</li>
					</ul>
					<div class="btn-box flex mt60 mb60">
						<ul style="width:60%">
							<li class="mb15">- 이력서는 공지된 병원양식을 다운받아서 작성 후 <br class="mb-show"><b class="mb-show">&nbsp;&nbsp;</b>첨부해주시기 바랍니다.</li>
							<li>- 문의 : 인사담당 02-6020-3092</li>
						</ul>
						<div class="flex" style="width:40%;justify-content:flex-end;">
							<a style="width:50%" class="btn-green" href="/file/서울재활병원(이력서양식)0728.hwp" download>
								<img src="/resource/images/down.png" alt=""> 이력서양식 다운로드
                            </a>

							<!-- <a class="btn-lightgreen" href="mailto:srh-recruit@hanmail.net">이메일 문의하기</a> -->
						</div>
					</div>
					<style>
						@media screen and (max-width:768px) {
							.btn-box a:not(.link).btn-green,
							.btn-box span:not(.link).btn-green {
								width:100% !important;
							}
						}
					</style>
					<h3 class="sub-tit"></h3>
					<table class="employment">
						<thead>
							<tr>
								<th class="num">번호</th>
								<th class="text-left">제목</th>
								<th>모집기간</th>
								<th class="state">접수현황</th>
							</tr>
						</thead>
						<tbody>
							<?php include $GP -> INC_PATH . "/${skin_dir}/board_list_inc.php";	?>
						</tbody>
					</table>
					<div class="pagination">
						<?=$page_link?>
					</div>
					<script type="text/javascript">
					$(document).ready(function(){
						$('#search_submit').click(function(){
							$('#search_form').submit();
							return false;
						});

						$('#page_row').change(function(){
							var val = $(this).val();
							location.href="?dep1=<?=$_GET['dep1']?>&dep2=<?=$_GET['dep2']?>&search_key=<?=$_GET['search_key']?>&search_keyword=<?=$_GET['search_keyword']?>&page=<?=$_GET['page']?>&page_row=" + val;
						});

						$('#twrite_btn').click(function(){
							alert("로그인 후 이용가능 합니다.");
							location.href ='/member/login.html?reurl=/community/page03.html';
						});
					});
					</script>