<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP -> INC_ADM_PATH."inc.adm_auth.php");
	include_once($GP -> CLS."/class.member.php");
	$C_Member 	= new Member;
	
	$args = "";
	$args['mb_code'] = $_GET['mb_code'];
	$data = $C_Member->Mem_Info($args);
	
	if($data) {
		extract($data);
		
		$arr_post = explode("-", $mb_zip_code);
		$m_post1 = $arr_post[0];
		$m_post2 = $arr_post[1];
		
		$arr_mobile = explode("-", $mb_mobile);
		$m_mobile1 = $arr_mobile[0];
		$m_mobile2 = $arr_mobile[1];
		$m_mobile3 = $arr_mobile[2];				

		$mobile_select = $C_Func -> makeSelect('m_mobile1', $GP -> MOBILE, $m_mobile1, '', '::선택::');		
		$level_select = $C_Func -> makeSelect('mb_level', $GP -> MEMBER_CONFIG_LEVEL, $mb_level, '', '::선택::');		
	}
?>

<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>회원정보 수정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="MEM_PWD_CK" />
		<input type="hidden" name="mb_code" id="mb_code" value="<?=$_GET['mb_code']?>" />
		<div class="boxContentBody">
			<div class="boxMemberInfoTable_layer">				
				<table class="table table-bordered">
					<tbody>
						<tr>
							<th width="30%"><span>*</span>회원명</th>
							<td width="70%">
								<input type="text" class="input_text" size="25" name="mb_name" id="mb_name" value="<?=$mb_name?>" />
							</td>
						</tr>           
						<tr>
							<th><span>*</span>변경할 비밀번호</th>
							<td>
							<input type="text" class="input_text" size="25" name="mb_pwd" id="mb_pwd" value=""  />
							</td>
						</tr>
						
					</tbody>
				</table><div style="margin-top:5px; text-align:center;">
				<button id="img_submit" class="btnSearch" type="button">수정</button>
				<button id="img_cancel" class="btnSearch" type="button" onClick="btn_cancel()">취소</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
<script src="/admin/js/jquery.alphanumeric.js" type="text/javascript"></script>
<script type="text/javascript" src="/admin/js/jquery.validate.js"></script>
<script type="text/javascript">

	function btn_cancel() {
		parent.modalclose();
	}

	$(document).ready(function(){	
		
		$('#img_submit').click(function(){					
			$('#base_form').submit();
			return false;
		});	
		

		$.validator.addMethod("passwordCk",  function( value, element ) {

		return this.optional(element) ||  /^.*(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&+-=]).*$/.test(value);

		}, jQuery.validator.messages.passwordCk); 			
		$('#base_form').validate({
			rules: {	
				mb_pwd: { required: true,passwordCk: true, minlength: 8, maxlength:16,}
			},
			messages: {	
				mb_pwd: { required: "변경할 비밀번호를 입력하세요", passwordCk : "영문, 숫자,특수문자(!,@,#,$,%,^,&,+,-) 조합 8자리 이상이 아닙니다.", minlength: $.format("패스워드는 {0}자 이상입니다"), maxlength: $.format("패스워드는 {0}자 이하입니다.") }
			},
			errorPlacement: function(error, element) {
				var er = element.attr("name");
				
				if(er == "m_addr" || er =="m_addr_sub" || er =="m_mobile1" || er =="m_mobile2" || er =="m_mobile3") {
					element.parent().find("span.my_error_display").html('');
					error.appendTo(element.parent().find("span.my_error_display"));
				}else{
					error.insertAfter(element);	
				}
			},
			submitHandler: function(frm) {
			if (!confirm("변경하시겠습니까?")) return false;                
				frm.action = './proc/mem_proc.php';
				frm.submit(); //통과시 전송
				return true;
			},

			success: function(element) {
			//
			}
		
		});
		
	});
</script>
</body>
</html>

