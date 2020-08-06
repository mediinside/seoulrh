<fieldset class="bbsSearch">
	<form id="search_form" name="search_form" method="get" action="?">
	<input type="hidden" name="jb_code" id="jb_code" value="<?=$jb_code?>" />
  <legend>게시물 검색</legend>
  <select title="검색어 분류" name="search_key" id="search_key">
    <option value="jb_name" <?php if($_GET['search_key']=="jb_name") echo " selected";?>>이름</option>
    <option value="jb_title" <?php if($_GET['search_key']=="jb_title") echo " selected";?>>제목</option>
    <option value="jb_content" <?php if($_GET['search_key']=="jb_content") echo " selected";?>>내용</option>
  </select>
  <input type="text" class="txt" title="검색어 입력" name="search_keyword" id="search_keyword" value="<?=$_GET['search_keyword']?>" />
  <a href="#;" class="btnM btnSearch" id="search_submit">검색</a>
  </form>
</fieldset>
<div class="bbsList">
<style type="text/css">
.subject {width:78%;}
.date {width:10%;}
</style>
  <ul>
  	<?php include $GP -> INC_PATH . "/action/list.inc.php"; ?>		
  </ul>
</div>
<div class="btnWrap">
  <span class="btnLeft">
		<?
    if($_GET['search_key'] && $_GET['search_keyword']) {
      echo "<a href=\"javascript:;\" class=\"btnM btnList\" onclick=\"javascript:location.href='${index_page}?jb_code=${jb_code}'\" title='목록'>목록</a>";
    }
    ?>
  </span>  
  <span class="btnRight">
		<?
      //쓰기권한
      if($check_level >= $db_config_data['jba_write_level']) {
        echo "<a class='btnM btnWrite' href=\"#\" onclick=\"javascript:location.href='${index_page}?jb_code=${jb_code}&jb_mode=twrite'\" title='글쓰기'>글쓰기</a>";
      } else {
        //echo "<a class='btnM btnWrite' href=\"javascript:alert('글쓰기 권한이 없습니다.');\" title='글쓰기'>글쓰기</a>";
      }
    ?>
  </span>
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
});
</script>
