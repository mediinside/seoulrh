<?php
if($jb_comment_count > 0) {						
?>
<ul class="replyList">
<?
	$args = array();
	$args['show_row'] = 100;
	$args['show_page'] = 5;
	$args['jb_code']  = $jb_code;	
	$args['jb_idx'] 	= $jb_idx;	
	$args['pagetype'] = "comment";
	
	$Comm_data = "";
	$Comm_data = $C_JHBoard -> Board_Comment_List(array_merge($_GET,$_POST,$args));
	
	$comm_data_list 		= $Comm_data['data'];
	$comm_page_link 		= $Comm_data['page_info']['link'];
	$comm_page_search 	= $Comm_data['page_info']['search'];
	$comm_totalcount 		= $Comm_data['page_info']['total'];
	$comm_totalpages 		= $Comm_data['page_info']['totalpages'];
	$comm_nowPage 			= $Comm_data['page_info']['page'];	
	$comm_num_idx				= $Comm_data['page_info']['start_num'];
		
	$comm_totalcount_l 	= number_format($comm_totalcount,0);
	$comm_data_list_cnt 	= count($comm_data_list);
	
	
	for($i=0; $i<$comm_data_list_cnt; $i++) {
		$jbc_idx 		= $comm_data_list[$i]['jbc_idx'];
		$jbc_mb_id	= $comm_data_list[$i]['jbc_mb_id'];
		$jbc_name_ori	= $comm_data_list[$i]['jbc_name'];
		$jbc_p_id_ori	= $comm_data_list[$i]['jbc_p_id'];
		$jbc_p_name	= $comm_data_list[$i]['jbc_p_name'];
		$jbc_step		= $comm_data_list[$i]['jbc_step'];
		$jbc_depth		= $comm_data_list[$i]['jbc_depth'];
		$jbc_group		= $comm_data_list[$i]['jbc_group'];
		$jbc_reg_date =  date("Y.m.d", strtotime($comm_data_list[$i]['jbc_reg_date']));
		$jbc_mod_date =  date("Y.m.d", strtotime($comm_data_list[$i]['jbc_mod_date']));	
		//등록일
		$jbc_reg_date 				= date("Y.m.d", strtotime($comm_data_list[$i]['jbc_reg_date']));	
		$jbc_reg_time 				= date("H:i", strtotime($comm_data_list[$i]['jbc_reg_date']));	
		//수정일
		$jbc_mod_date 				= date("Y.m.d", strtotime($comm_data_list[$i]['jbc_mod_date']));	
		
		//이름 ** 처리
		$jbc_name =  $C_Func->blindInfo($comm_data_list[$i]['jbc_name'], 6);
		$jb_id =  $C_Func->blindInfo($comm_data_list[$i]['jbc_mb_id'], 6);
		
		
		$jbc_p_id	=  $C_Func->blindInfo($comm_data_list[$i]['jbc_p_id'],6);
		$jbc_p_name	=  $C_Func->blindInfo($comm_data_list[$i]['jbc_p_name'],6);

		
		
		//내용 (HTML TAG제한)
		$jbc_content = nl2br(strip_tags($comm_data_list[$i]['jbc_content'], '<br>'));
		
		//답글처리
		if($jbc_step > 0)
				$depth_icon = $C_Func->reply_depth($comm_data_list[$i]['[jbc_step'], "");
		else
				$depth_icon = ""; //매 글마다 초기화를 해 주어야 한다.			
		
		$get_c_par = "";
		$get_c_par = "${index_page}?jb_code=${jb_code}&jb_idx=${jb_idx}";
		$get_c_par .= "&jb_group=${jb_group}&jb_step=${jb_step}&jb_depth=${jb_depth}&jbc_idx=${jbc_idx}";
		$get_c_par .= "&${search_key}&search_keyword=${search_keyword}&page=${page}";
	?>
  <li <? if($jbc_step > 0) { echo " class='reReply' ";}?>>
    <span class="replyInfo">    	
      <span class="name"><strong><?=$jbc_name?></strong> (<?=$jb_id?>)</span>
      <span class="date"><?=$jbc_reg_date?></span>
      <span class="time"><?=$jbc_reg_time?></span>
    </span>
    <? if($check_level == 9 || $check_id == $jbc_mb_id) { ?>
    <span class="util">
      <a href="#;" onclick="comm_reply('<?=$jbc_idx?>','<?=$jb_idx?>','<?=$jb_code?>','<?=$jbc_group?>','<?=$jbc_step?>','<?=$jbc_depth?>','<?=$jbc_name_ori?>','<?=$jbc_mb_id?>');">답글</a> |
      <a href="<?php echo "${get_c_par}&jbc_idx=${jbc_idx}&jb_mode=comment_modify";?>">수정</a> |
      <a href="<?php echo "${get_c_par}&jb_mode=comment_delete";?>">삭제</a>
    </span>
    <? } ?>
    <span class="cont">
    	<?
      	if($jbc_p_id_ori != '' && $jbc_step > 1) {
      		echo "<strong>" . $jbc_p_name . "</strong> (". $jbc_p_id .")";
				}
				echo htmlspecialchars_decode ($jbc_content, ENT_NOQUOTES);
			?>
    </span>
    <div id="comm_reply_<?=$jbc_idx?>" class="reReplyWrite"></div>
  </li>
<?php
	} // end for
?>
</ul>
<?
} //end_of_if($jb_comment_count > 0)	
?>
<script type="text/javascript">	
	var show = 0;
	function comm_reply(jbc_idx, jb_idx, jb_code, jbc_group, jbc_step, jbc_depth, jbc_name, jbc_mb_id) {		
		var url = "query.php?jb_mode=comment_reply&jb_code=" + jb_code + "&jb_idx=" + jb_idx  + "&jbc_group=" + jbc_group + "&jbc_step=" + jbc_step + "&jbc_depth=" + jbc_depth+ "&jbc_p_name=" + jbc_name+ "&jbc_p_id=" + jbc_mb_id;
		var str = "";
		str += '<form name="frm_re_comment" id="frm_re_comment" action="' + url + '" method="post">';
		str += '<div class="reCont reRegi">';		
		
		<?
			if(empty($_SESSION['suserid']))
	  	{
		?>
		str += '<div class="cmtRegister guest">';		
		str += '<p class="guestInput">';
		str += '<label>이름 : <input type="text" name="jbc_name" id="jbc_name" size="18" maxlength="30" value="<?=$check_name?>" class="text"></label>';		
		str += '<label>비밀번호 : <input type="password" name="jbc_password" id="jbc_password" size="18" maxlength="50" class="text"></label>';
	  str += '</p>';
		<?
			}else{
				$password_key1=md5($check_id);	
				$tm1=explode(" ",microtime());
				$jbc_password_re = $password_key1 . $tm1[1];
		?>
		str += '<div class="cmtRegister">';
		str += '<input type="hidden" name="jbc_name" id="jbc_name" value="<?=$check_name?>">';
		str += '<input type="hidden" name="jbc_password" id="jbc_password" value="<?=$jbc_password_re?>">';			
		<?
			}
		?>
		
		str += '<textarea name="jbc_re_content" id="jbc_re_content" cols="30" rows="10"></textarea>';
		str += '<div class="cmtBtn">';
		str += '<a href="javascript:;" class="btnRegi" onclick="frm_submit();">등록</a>';
		str += '<a href="javascript:;" class="btnRegi" onclick="frm_cancel(' + jbc_idx + ');">취소</a>';
		str += '</div>';
		str += '</div></div></form>';		
		
		if(show == 0) {
			$('#comm_reply_' + jbc_idx).append(str);
			show = 1;
		}else{
			$('#comm_reply_' + jbc_idx).html('');
			show = 0;
		}		
	}
	
	function frm_cancel(jbc_idx){
			$('#comm_reply_' + jbc_idx).html('');
			show = 0;		
	}
	
	function frm_submit() {
		var t = $.base64Encode($('#jbc_re_content').val()); 
		$('#jbc_re_content').val(t);				
		
		if ( $('#jbc_re_content').val() == "") {
			alert('내용을 입력하세요.');
			$('#jbc_re_content').focus();
			return false;
		}
		
		$('#frm_re_comment').submit();
		return false;	
	}
</script>