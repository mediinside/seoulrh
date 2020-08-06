<!-- 게시물 보기 -->
<div class="bbsView">
  <h1 class="viewTitle"><?=$jb_title?></h1>
  <p class="viewInfo">
    <span class="date"><strong>게시일.</strong> <?=$jb_reg_date?></span>    
  </p> 
  <div class="viewCont">
  	<?php								
			if($file_cnt > 0) {
				for($i=0; $i<$file_cnt; $i++)	{
					if($ex_jb_file_name[$i]) {
						//파일의 확장자
						$file_ext = substr( strrchr($ex_jb_file_name[$i], "."),1); 
						$file_ext = strtolower($file_ext);	//확장자를 소문자로...
						
						if ($file_ext=="gif" || $file_ext=="jpg" || $file_ext=="png" || $file_ext=="bmp") {										
							echo "<a href='" . $GP->UP_IMG_SMARTEDITOR_URL ."jb_${jb_code}/${ex_jb_file_code[$i]}' target='_blank'>";
							echo "<img src=\"" . $GP->UP_IMG_SMARTEDITOR_URL ."jb_" . $jb_code . "/" . $ex_jb_file_code[$i] ."\" class='imgResponsive'>";
							echo "</a>";
						}
					}	 
				}
			}
		?>
    <?=$content?>
  </div>
  <div class="btnWrap">
    <span class="btnLeft">
      <?php
				//글목록버튼
				echo "<a href=\"#\" onclick=\"javascript:location.href='${index_page}?jb_code=${jb_code}&${search_key}&search_keyword=${search_keyword}&page=${page}'\" class=\"btnM btnList\" title='목록'>목록</a>";	
				?>
    </span>
    <span class="btnRight">
      <?
			//답변권한
			//if($check_level >= $db_config_data['jba_reply_level'])
					//echo "<a href=\"#\" onclick=\"javascript:location.href='${get_par}&jb_mode=treply'\" class=\"btnM btnAnswer \" title=\"답글\">답글</a> ";			
			//수정(쓰기권한이 있어야 수정 가능)
			if($check_level >= 9 || $check_id == $jb_mb_id)
					echo "<a href=\"#\" onclick=\"javascript:location.href='${get_par}&jb_mode=tmodify'\" class=\"btnM btnModify \" title=\"수정\">수정</a> ";
			//삭제권한(쓰기권한이 있어야 삭제 가능)
			if($check_level >= 9 || $check_id == $jb_mb_id)
					echo "<a href=\"#\" onclick=\"javascript:location.href='${get_par}&jb_mode=tdelete'\" class=\"btnM btnDel \" title=\"삭제\">삭제</a> ";								
			//쓰기권한
			if($check_level >= $db_config_data['jba_write_level'])
					echo "<a href=\"#\" onclick=\"javascript:location.href='${index_page}?jb_code=${jb_code}&jb_mode=twrite'\" class=\"btnM btnWrite \" title=\"쓰기\">쓰기</a>";						
			?>	
    </span>
  </div>
  <ul class="viewNavi">
  	<? if($be_idx != '') { ?>	
      <li>
        <a href="<?=$get_par1?>"><strong class="prev">이전 게시물</strong><?=$be_content?></a>
      </li>			
    <? } ?>
   	
    <? if($af_idx != '') { ?>		
      <li>			
        <a href="<?=$get_par2?>"><strong class="next">다음 게시물</strong><?=$af_content?></a>
      </li>	
    <? } ?>	  
  </ul>
</div>
<!-- //게시물 보기 -->
<!-- 댓글 -->
<?
//if($jb_order >= 100 && $db_config_data['jba_comment_use'] == 'Y'  && $check_level >= $db_config_data['jba_comment_level']) {	
?>
<div class="replySection">
  <h4>댓글(<?=$jb_comment_count?>)</h4>
  <? 	include $GP -> INC_PATH . "/action/comment.inc.php"; ?>
</div>
<?
//} //end_of_if($jb_order >= 100)
?>
<!-- //댓글 -->
