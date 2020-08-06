<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP -> INC_ADM_PATH."inc.adm_auth.php");
	include_once($GP -> CLS."class.jhboard.php");
	$C_JHBoard = new JHBoard();
	
		
	$args = "";	
	$args['jb_code'] = $_GET['jb_code'];	
	$rst = $C_JHBoard -> Board_Config_Data($args);	
	
	if($rst) {
		extract($rst);		
	}else{
		$C_Func->put_msg_and_modalclose("정보가 올바르지 않습니다.");	
	}	
	
	$read_level = $C_Func ->makeSelect("jba_read_level", $GP -> BOARD_CONFIG_LEVEL, $jba_read_level, "", "::: 선택 :::");	
	$write_level = $C_Func ->makeSelect("jba_write_level", $GP -> BOARD_CONFIG_LEVEL, $jba_write_level	, "", "::: 선택 :::");	
	$reply_level = $C_Func ->makeSelect("jba_reply_level", $GP -> BOARD_CONFIG_LEVEL, $jba_reply_level	, "", "::: 선택 :::");	
	$comment_level = $C_Func ->makeSelect("jba_comment_level", $GP -> BOARD_CONFIG_LEVEL, $jba_comment_level	, "", "::: 선택 :::");	
	
	
	$arr_skin = $C_JHBoard ->SKIN_LIST();
?>

<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>게시판 설정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?">
		<input type="hidden" name="mode" id="mode" value="BBS_MODI" />
		<input type="hidden" name="jb_code" id="jb_code" value="<?=$_GET['jb_code']?>" />
		<div class="boxContentBody">
			<div class="boxMemberInfoTable_layer">
				<div class="layerTable"> 
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th width="30%"><span>*</span>구분</th>
								<td width="70%">
									<input type="text" class="input_text" size="25" name="jba_title" id="jba_title" value="<?=$jba_title?>" />
								</td>
							</tr>
							<tr>
								<th><span>*</span>읽기권한</th>
								<td>
									<?=$read_level?>
								</td>
							</tr>
							<tr>
								<th><span>*</span>쓰기권한</th>
								<td>
									<?=$write_level?>
								</td>
							</tr>
							<tr>
								<th><span>*</span>답글권한</th>
								<td>
									<?=$reply_level?>
								</td>
							</tr>		
							<tr>
								<th><span>*</span>코멘트권한</th>
								<td>
									<?=$comment_level?>
								</td>
							</tr>		
							<tr>
								<th><span>*</span>코멘트사용유무</th>
								<td>
									<input type="radio" name="jba_comment_use" value="Y" <? if($jba_comment_use == "Y"){ echo "checked"; }?> />사용
									<input type="radio" name="jba_comment_use" value="N" <? if($jba_comment_use == "N"){ echo "checked"; }?> />미사용
								</td>
							</tr>		
							<tr>
								<th><span>*</span>상세페이지 하단리스트 사용유무</th>
								<td>
									<input type="radio" name="jba_list_use" value="Y" <? if($jba_list_use == "Y"){ echo "checked"; }?> />사용
									<input type="radio" name="jba_list_use" value="N" <? if($jba_list_use == "N"){ echo "checked"; }?> />미사용
								</td>
							</tr>	
							<tr>
								<th width="30%"><span>*</span>페이지당 리스트수</th>
								<td width="70%">
									<input type="text" class="input_text" size="25" name="jba_list_scale" id="jba_list_scale" value="<?=$jba_list_scale?>" />
								</td>
							</tr>	
							<tr>
								<th width="30%"><span>*</span>스킨</th>
								<td width="70%">
									<select name="jba_skin_dir" id="jba_skin_dir">
										<?
											for($i=0; $i<count($arr_skin); $i++) {
												if($arr_skin[$i]['jba_skin_dir'] == $jba_skin_dir) {
													echo "<option value='" . $arr_skin[$i]['jba_skin_dir']. "' selected>" . $arr_skin[$i]['jba_skin_dir'] . "</option>";
												}else{
													echo "<option value='" . $arr_skin[$i]['jba_skin_dir']. "'>" . $arr_skin[$i]['jba_skin_dir'] . "</option>";
												}
											}
										?>
									</select>
								</td>
							</tr>			
						</tbody>
					</table>
				</div>
				<div class="btnWrap">
				<button id="img_submit" class="btnSearch ">수정</button>
				<button id="img_cancel" class="btnSearch ">취소</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
</body>
</html>
<script src="/admin/js/jquery.alphanumeric.js" type="text/javascript"></script>
<script type="text/javascript" src="/admin/js/jquery.validate.js"></script>
<script type="text/javascript">

	$(document).ready(function(){	
		
		$('#img_submit').click(function(){					
			$('#base_form').submit();
			return false;
		});
		
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});		
		
		
		$('#base_form').validate({
			rules: {	
				jba_title: { required: true },
				jba_list_scale: { required: true }				
			},
			messages: {	
				jba_title: { required: "구분을 입력하세요" },
				jba_list_scale: { required: "노출수량을 입력하세요" }
			},
			errorPlacement: function(error, element) {
				var er = element.attr("name");
				error.insertAfter(element);
			},
			submitHandler: function(frm) {
			if (!confirm("수정하시겠습니까?")) return false;                
				frm.action = './proc/bbs_proc.php';
				frm.submit(); //통과시 전송
				return true;
			},

			success: function(element) {
			//
			}
		
		});
		
	});
</script>
