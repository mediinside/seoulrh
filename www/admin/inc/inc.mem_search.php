<script type="text/javascript">
		$(document).ready(function(){
			$('#mem_find_btn').click(function(){
					
					if($('form#frm_mem_search #mb_name').val() == '') {
						alert("회원명을 입력하세요");
						return false;
					}
												
					$('#frm_mem_search').submit();
					return false;
			});													 
		});
</script>
<form method="post" action="/admin/member/mem_list.php" id="frm_mem_search" name="frm_mem_search">
	<strong class="tit">통합 고객검색</strong>
	<input type="text" name="mb_name_sch" id="mb_name_sch" class="inputSearch" value="<?=$_POST['mb_name_sch']?>">
	<button type="button" class="btnSearch" id="mem_find_btn">조 회</button>
</form>
