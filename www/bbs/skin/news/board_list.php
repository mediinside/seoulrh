<div class="sub-visual-wrap">
					<img src="/resource/images/notice-bnnr1.png" alt="">
				</div>
				<!-- //end .sub-visual-wrap -->
			</section>
			<!-- main section 01 end -->
			<!-- main section 02 start -->
			<section class="contents sub-contents">
				<div class="wd-1200">
					<h3 class="sub-tit">병원소식</h3>
					<div class="main-list-menu v2 sub-v2">
						<div class="main-list-inner wd-1200">
							<ul class="sub-1 n3">
							<?php include $GP -> INC_PATH . "/${skin_dir}/board_list_inc.php";	?>
                            </ul>
						</div>
					</div>
					<div class="pagination">
                         <?=$page_link?>
					</div>
					<form class="board-search"  id="search_form"  name="search_form" method="get" action="?">>
						<input type="hidden" name="jb_code" id="jb_code" value="<?=$jb_code?>" />
						<fieldset>
							<legend>게시물 검색</legend>
							<select name="search_key" id="search_key">
                                <option value="jb_title" <?php if($_GET['search_key']=="jb_title") echo " selected";?>>제목</option>
                                <option value="jb_etc1" <?php if($_GET['search_key']=="jb_etc1") echo " selected";?>>해시태그</option>
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