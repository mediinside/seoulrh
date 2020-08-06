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
		$withdrawal_select = $C_Func -> makeSelect('mb_withdrawal', $GP -> WITHDRAWAL_REASON, $mb_withdrawal, '', '::선택::');	
	}
?>

<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>회원탈퇴 상세</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="MEM_MODI" />
		<input type="hidden" name="mb_code" id="mb_code" value="<?=$_GET['mb_code']?>" />
		<div class="boxContentBody">
			<div class="boxMemberInfoTable_layer">				
			<div class="layerTable">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<th width="15%"><span>*</span>회원명</th>
							<td width="85%">
								<input type="text" class="input_text" size="25" name="mb_name" id="mb_name" value="<?=$mb_name?>" />
							</td>
						</tr>
						<tr>
							<th><span>*</span>이메일</th>
							<td>
								<input type="text" class="input_text" size="25" name="mb_email" id="mb_email" value="<?=$mb_email?>" />
							</td>
						</tr>
						<tr>
							<th><span>*</span>생년월일</th>
							<td>
							<input type="text" class="input_text" size="25" name="mb_birthday" id="mb_birthday" value="<?=$mb_birthday?>"  />
							</td>
						</tr>
						<tr>
							<th><span>*</span>주소</th>
							<td>	
								<div style="margin-top:3px;"><input type="text" name="m_post1" id="m_post1" value="<?=$m_post1?>" size="10" class="input_text" /> - <input type="text" name="m_post2" id="m_post2" value="<?=$m_post2?>" size="10" class="input_text" />
								<button id="img_submit" class="btnSearch " onClick="layerPop('ifm_addr','/admin/common/address_pop.html?obj=m_post1&obj1=m_post2&obj2=m_addr&obj3=m_addr_sub&obj4=mb_load_addr1&obj5=mb_load_addr2', 520, 500);">우편번호</button></div>
								<div style="margin-top:3px;"><input type="text" name="m_addr" id="m_addr" value="<?=$mb_address1?>" placeholder=" * 주소가 자동입력됩니다." size="60" class="input_text" /></div>
								<div style="margin-top:3px;"><input type="text" name="m_addr_sub" id="m_addr_sub" value="<?=$mb_address2?>"  placeholder=" * 상세주소를 입력하십시오." size="60" class="input_text" /></div>
								<div style="margin-top:3px;"><input type="text" name="mb_load_addr1" id="mb_load_addr1" value="<?=$mb_load_address1?>" placeholder=" * 주소가 자동입력됩니다." size="60" class="input_text" /></div>
								<div style="margin-top:3px; margin-bottom:3px;"><input type="text" name="mb_load_addr2" id="mb_load_addr2" value="<?=$mb_load_address2?>"  placeholder=" * 상세주소를 입력하십시오." size="60" class="input_text" /></div>
								<span class="my_error_display"></span>
							</td>
						</tr>
						<tr>
							<th><span>*</span>연락처</th>
							<td>
									<?=$mobile_select?>-
									<input type="text" id="m_mobile2" name="m_mobile2" size="4" value="<?=$m_mobile2?>" maxlength="4" class="input_text" />-
									<input type="text" id="m_mobile3" name="m_mobile3" size="4" value="<?=$m_mobile3?>" maxlength="4" class="input_text" />
									<span class="my_error_display"></span>
							</td>
						</tr>
						<tr>
							<th><span>*</span>성별</th>
							<td>
								<input type="radio" name="mb_sex" value="M" <? if($mb_sex == 'M'){ echo "checked"; } ?> />남
								<input type="radio" name="mb_sex" value="W" <? if($mb_sex == 'W'){ echo "checked"; } ?> />여
							</td>
						</tr>
						<tr>
							<th><span>*</span>탈퇴유형</th>
							<td>
								<?=$withdrawal_select?>
							</td>
						</tr>
						<tr>
							<th><span>*</span>탈퇴유형</th>
							<td>
								<?=$withdrawal_select?>
							</td>
						</tr>
						<tr>
							<th><span>*</span>탈퇴사유<br />(기타일 경우)</th>
							<td>
								<textarea style="width:100%; height:100px;"><?=$mb_withdrawal_reason?></textarea>  
							</td>
						</tr>
						<tr>
							<th><span>*</span>탈퇴일자</th>
							<td>
								<input type="text" id="mb_withdrawal_date" name="mb_withdrawal_date" size="20" value="<?=$mb_withdrawal_date?>" class="input_text" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="btnWrap">
				<button id="img_cancel" class="btnSearch ">닫기</button>
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
							   
		$('#m_mobile2').numeric();
		$('#m_mobile3').numeric();							   
		
		
		$('#img_submit').click(function(){					
			$('#base_form').submit();
		});
		
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});
		
		$.validator.addMethod("emailcheck", function(value, element) {
			var val = value;
			return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(val);
		
		}, jQuery.validator.messages.emailcheck);
					
		$('#base_form').validate({
			rules: {	
				mb_name: { required: true},				
				mb_email: { required: true, emailcheck:true},
				mb_birthday : { required:true },
				m_addr : {required :true },
				m_addr_sub : {required :true },
				m_mobile2 : {required :true },
				m_mobile3 : {required :true }				
			},
			messages: {	
				mb_name: { required: "회원명을 입력하세요" },
				mb_email: { required: "이메일을 입력하세요", emailcheck:"올바른 이메일을 입력하세요" },			
				mb_birthday: { required: "생년월일을 입력하세요" },
				m_addr: { required: "주소를 입력하세요" },
				m_addr_sub: { required: "상세주소를 입력하세요" },
				m_mobile2: { required: "전호번호를 입력하세요" },
				m_mobile3: { required: "전호번호를 입력하세요" }
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
			if (!confirm("수정하시겠습니까?")) return false;                
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
