<div class="sub-visual-wrap">
					<img src="/resource/images/notice-bnnr1.png" alt="">
				</div>
				<!-- //end .sub-visual-wrap -->
			</section>
			<!-- main section 01 end -->
			<!-- main section 02 start -->
			<section class="contents sub-contents">
				<div class="board-list view wd-1200">
					<h3 class="sub-tit">연차보고서</h3>
					<div class="main-list-menu v2 sub-v2">
						<div class="main-list-inner wd-1200">
							<ul class="sub-3">
							<?php include $GP -> INC_PATH . "/${skin_dir}/board_list_inc.php";	?>
                            </ul>
						</div>
					</div>
				</div>
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