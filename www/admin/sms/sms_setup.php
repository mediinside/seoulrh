<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP -> CLS."/class.sms.php");
	$C_Sms 	= new Sms;	

	$data = $C_Sms->Sms_Setup_Info();
	
	if($data) {
		extract($data);
		
		$arr_mobile = explode("-", $tss_mobile);
		$m_mobile1 = $arr_mobile[0];
		$m_mobile2 = $arr_mobile[1];
		$m_mobile3 = $arr_mobile[2];
		
		$mode = "SMS_STEUP_MODI";
		$btn_title = "수정";
	}else{
		$mode = "SMS_STEUP_REG";
		$btn_title = "등록";
	}
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<? include_once($GP -> INC_ADM_PATH."/inc.mem_search.php"); ?>													
			</div>
		</div>		
		
		<div class="boxContentBody">		
				<div class="boxMemberInfoTable_layer">		
					<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
					<input type="hidden" name="mode" id="mode" value="<?=$mode?>" />
					<input type="hidden" name="tss_idx" id="tss_idx" value="<?=$tss_idx?>" />							
					<table class="table table-bordered">  					
							<tbody>
									<tr>
											<th width="15%">담당자명</th>
											<td width="85%">
													<input type="text" class="input_text" size="25" name="tss_name" id="tss_name" value="<?=$tss_name?>" />
											</td>
									</tr>                  
									<tr>
											<th>연락처</th>
											<td>
													<input type="text" id="tss_mobile1" name="tss_mobile1" size="4" value="<?=$m_mobile1?>" maxlength="4" class="input_text" />-
													<input type="text" id="tss_mobile2" name="tss_mobile2" size="4" value="<?=$m_mobile2?>" maxlength="4" class="input_text" />-
													<input type="text" id="tss_mobile3" name="tss_mobile3" size="4" value="<?=$m_mobile3?>" maxlength="4" class="input_text" />
													<span class="my_error_display"></span>
											</td>
									</tr>    
							</tbody>
					</table>
				</div>			
			</div>
			<div style="margin-top:5px; text-align:center;">
				<button id="img_submit" class="btnSearch ">수정</button>
				<button id="img_cancel" class="btnSearch ">취소</button>
				</div>
		</div>
		<? include_once($GP -> INC_ADM_PATH."/footer.php"); ?>
	</div>
</div><!-- 전체를 감싸는 Wrap //-->
</body>
</html>
<script src="/admin/js/jquery.alphanumeric.js" type="text/javascript"></script>
<script type="text/javascript" src="/admin/js/jquery.validate.js"></script>
<script type="text/javascript">

	$(document).ready(function(){	
							   
		$('#tss_mobile2').numeric();
		$('#tss_mobile3').numeric();							   
		
		
		$('#img_submit').click(function(){					
			$('#base_form').submit();
			return false;
		});		
	
					
		$('#base_form').validate({
			rules: {	
				tss_name: { required: true},				
				tss_mobile2 : {required :true },
				tss_mobile3 : {required :true }				
			},
			messages: {	
				tss_name: { required: "회원명을 입력하세요" },				
				tss_mobile2: { required: "전호번호를 입력하세요" },
				tss_mobile3: { required: "전호번호를 입력하세요" }
			},
			errorPlacement: function(error, element) {
				var er = element.attr("name");
				
				if(er =="tss_mobile2" || er =="tss_mobile3") {
					element.parent().find("span.my_error_display").html('');
					error.appendTo(element.parent().find("span.my_error_display"));
				}else{
					error.insertAfter(element);	
				}
			},
			submitHandler: function(frm) {
			if (!confirm("설정하시겠습니까?")) return false;                
				frm.action = './proc/sms_proc.php';
				frm.submit(); //통과시 전송
				return true;
			},

			success: function(element) {
			//
			}
		
		});
		
	});
</script>
