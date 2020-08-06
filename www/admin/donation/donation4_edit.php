<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	include_once($GP -> CLS."/class.donation.php");
	$C_donation 	= new donation;
	
	$args = "";
	$args['do_idx'] 	= $_GET['do_idx'];
	$rst = $C_donation ->donation3_Info($args);
	
	if($rst) {
		extract($rst);
		$do_content  = $C_Func->dec_contents_edit($do_content);				
    }	
    
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>직원 헌금 수정</strong></span>
		</div>
        <form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
        <input type="hidden" name="do_type" id="do_type" value="do_4" />
		<input type="hidden" name="mode" id="mode" value="donation3_MODI" />
		<input type="hidden" name="do_idx" id="do_idx" value="<?=$_GET['do_idx']?>" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>                       
							<th><span>*</span>년도</th>
								<td>
                                    <input type="text" class="input_text" size="30" name="do_year" id="do_year" value="<?=$do_year?>" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" />년      &nbsp;※숫자만 입력해 주세요.
                                    
								</td>
							</tr>																		
							<tr>
								<th><span>*</span>헌금</th>
								<td>
                                <input type="text" class="input_text" size="30" name="do_content" id="do_content" value="<?=$do_content?>" />	
								</td>
                            </tr>
							<tr>
								<th><span>*</span>노출여부</th>
								<td>
									<input type="radio" name="do_show" id="do_show" value="Y" <? if($do_show == "Y"){ echo "checked";}?> />노출
									<input type="radio" name="do_show" id="do_show" value="N" <? if($do_show == "N"){ echo "checked";}?> />비노출
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
			url: "./proc/donation_proc.php",
			data: "mode=donation_IMGDEL&do_idx=" + idx + "&file=" + image + "&type=" + type,
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
		
		$('#do_type').change(function(){
			size_guide();
		});
		
		function size_guide(){
			var type = $("#do_type option:selected").val();
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
			if($('#do_descrition').val() == '') {
				alert('소제목을 입력하세요');
				$('#do_descrition').focus();
				return false;
			}		
			
			if($('#do_title').val() == '') {
				alert('제목을 입력하세요');
				$('#do_title').focus();
				return false;
			}	
			
			if($('#do_content').val() == '') {
				alert('내용을 입력하세요');
				$('#do_content').focus();
				return false;
			}
			*/
			
			$('#base_form').attr('action','./proc/donation_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>