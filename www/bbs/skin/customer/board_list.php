				<div class="sub-visual-wrap">
					<img src="/resource/images/notice-bnnr1.png" alt="">
				</div>
				<!-- //end .sub-visual-wrap -->
			</section>
			<!-- main section 01 end -->
			<!-- main section 02 start -->
			<section class="contents sub-contents">
				<div class="board-list wd-1200">
					<h3 class="sub-tit">고객의 소리</h3>
					<table>
						<caption>고객의 소리 리스트</caption>
						<thead style="display: none;">
							<tr>
								<th scope="col" class="num">NO</th>
								<th scope="col" class="subject">제목</th>
								<th scope="col" class="date">작성일</th>
								<th scope="col" class="hit">조회수</th>
							</tr>
						</thead>
						<tbody>
							<?php include $GP -> INC_PATH . "/${skin_dir}/board_list_inc.php";	?>
						</tbody>
					</table>
					<div class="pagination">
						<?=$page_link?>
					</div>
					<form class="board-search"  id="search_form"  name="search_form" method="get" action="?">>
						<input type="hidden" name="jb_code" id="jb_code" value="<?=$jb_code?>" />
						<fieldset>
							<legend>게시물 검색</legend>
							<select name="search_key" id="search_key">
								<option value="jb_name" <?php if($_GET['search_key']=="jb_name") echo " selected";?>>이름</option>
							</select>
							<input type="search" class="search-input" placeholder="키워드를 입력하세요."  name="search_keyword" id="search_keyword" value="<?=$_GET['search_keyword']?>" >
							<button type="submit" class="search-btn"  id="search_submit"><span>검색</span></button>
						</fieldset>
					</form>
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