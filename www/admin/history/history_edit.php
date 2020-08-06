<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	include_once($GP -> CLS."/class.history.php");
	$C_history 	= new history;
	
	$args = "";
	$args['h_idx'] 	= $_GET['h_idx'];
	$rst = $C_history ->history_Info($args);
	
	if($rst) {
		extract($rst);
		$h_content  = $C_Func->dec_contents_edit($h_content);				
    }	
    $history_select = $C_Func -> makeSelect_Normal('h_type', $GP -> SLIDE_TYPE, $h_type, '', '::선택::');
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>메인 슬라이드 수정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="history_MODI" />
		<input type="hidden" name="h_idx" id="h_idx" value="<?=$_GET['h_idx']?>" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>
                        <tr>
								<th><span>*</span>년대</th>
								<td>
									<?=$history_select?>
								</td>
							</tr> 	
                        <tr>
								<th><span>*</span>년도</th>
								<td>
                                    <input type="text" class="input_text" size="30" name="h_year" id="h_year" value="<?=$h_year?>" />년      &nbsp;※숫자만 입력해 주세요.
                                    
								</td>
							</tr>
							<tr>
								<th><span>*</span>날짜</th>
								<td>
									<input type="text" class="input_text" size="30" name="h_day" id="h_day" value="<?=$h_day?>"/>
								</td>
							</tr>												
							<tr>
								<th><span>*</span>내용</th>
								<td>
									<textarea name="h_content" id="h_content" style="width:98%; height:100px;  overflow:auto;" ><?=$h_content?></textarea>
								</td>
                            </tr>
							<tr>
								<th><span>*</span>노출여부</th>
								<td>
									<input type="radio" name="h_show" id="h_show" value="Y" <? if($h_show == "Y"){ echo "checked";}?> />노출
									<input type="radio" name="h_show" id="h_show" value="N" <? if($h_show == "N"){ echo "checked";}?> />비노출
								</td>
							</tr>								
						</tbody>
					</table>
				</div>				
				<div class="btnWrap">
					<span class="btnRight">
						<button id="img_submit" class="btnSearch ">수정</button>
						<button id="img_cancel" class="btnSearch ">취소</button>
					</span>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
</body>
</html>
<script type="text/javascript">


	function img_del(image, idx, type)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/history_proc.php",
			data: "mode=history_IMGDEL&h_idx=" + idx + "&file=" + image + "&type=" + type,
			dataType: "text",
			success: function(msg) {

				if($.trim(msg) == "true") {
					alert("삭제되었습니다");
					window.location.reload();
					return false;
				}else{
					alert('삭제에 실패하였습니다.');
					return;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
	}

	$(document).ready(function(){	
		size_guide();
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});	
		
		$('#h_type').change(function(){
			size_guide();
		});
		
		function size_guide(){
			var type = $("#h_type option:selected").val();
			if (type == 'main') {
				$('#size_pc').text('(1398*600)');
				$('#size_m').text('(720*420)');
				$('#mobile_img').show();
			}else if (type == 'left') {
				$('#size_pc').text('(200*360)');
				$('#size_m').text('(720*180)');
				$('#mobile_img').show();
			}else{
				$('#size_pc').text('(360*200)');
				$('#mobile_img').hide();
			}
		}
		
		$('#img_submit').click(function(){
			/*
			if($('#h_descrition').val() == '') {
				alert('소제목을 입력하세요');
				$('#h_descrition').focus();
				return false;
			}		
			
			if($('#h_title').val() == '') {
				alert('제목을 입력하세요');
				$('#h_title').focus();
				return false;
			}	
			
			if($('#h_content').val() == '') {
				alert('내용을 입력하세요');
				$('#h_content').focus();
				return false;
			}
			*/
			
			$('#base_form').attr('action','./proc/history_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>