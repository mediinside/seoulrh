<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP -> INC_ADM_PATH."inc.adm_auth.php");
	include_once $GP -> INC_ADM_PATH.'menu.php';
	include_once($GP->CLS."class.admmain.php");
	$C_AdminMain 	= new AdminMain;
		
	$args = "";	
	$args['mb_code'] = $_GET['mb_code'];
	$rst = $C_AdminMain->Admin_info($args);
	
	if($rst) {
		extract($rst);		
		
		$args = '';
		$args['tl_idx'] = $tl_idx;
		$rst1 = $C_AdminMain -> Admin_Auth_info($args);
	
		if($rst1) {
			extract($rst1);
			
			$arr_tmp = explode(',',$tl_folder);
			$arr_tmp2 = explode(',',$tl_folder_sub);
			$arr_tmp1 = explode(',',$tl_bbs);
		}
	}else{
		$C_Func->put_msg_and_modalclose("정보가 올바르지 않습니다.");	
	}	
	
	$sel_level = $C_Func ->makeSelect("mb_level", $GP -> AUTH_LEVEL, $mb_level, "class='select_type1'", "::: 선택 :::");	

	$args = '';
	$board_data = $C_AdminMain ->Board_List_All($args);
?>

<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>관리자 수정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?">
		<input type="hidden" name="mode" id="mode" value="ADMINMODI" />
		<input type="hidden" name="mb_code" id="mb_code" value="<?=$_GET['mb_code']?>" />
		<input type="hidden" name="old_mb_password" id="old_mb_password" value="<?=$mb_password?>" />
		<input type="hidden" name="old_mb_id" id="old_mb_id" value="<?=$mb_id?>" />
		<input type="hidden" name="tl_idx" id="tl_idx" value="<?=$tl_idx?>" />
		<div class="boxContentBody">
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th width="15%"><span>*</span>아이디</th>
								<td width="85%">
									<input type="text" class="input_text" size="25" name="mb_id" id="mb_id" value="<?=$mb_id?>" />
								</td>
							</tr>
							<tr>
								<th><span>*</span>성 명</th>
								<td>
									<input type="text" class="input_text" size="25" name="mb_name" id="mb_name" value="<?=$mb_name?>" />
								</td>
							</tr>
							<tr>
								<th><span>*</span>패스워드</th>
								<td>
								<input type="text" class="input_text" size="25" name="mb_password" id="mb_password" />※비밀번호는 8자 이상 입력하세요.
								</td>
							</tr>
							<tr>
								<th><span>*</span>이메일</th>
								<td><input type="text" class="input_text" size="25" name="mb_email" id="mb_email" value="<?=$mb_email?>" /></td>
							</tr>
							<tr>
								<th><span>*</span>연락처</th>
								<td><input type="text" class="input_text" size="25" name="mb_phone" id="mb_phone" value="<?=$mb_phone?>" /></td>
							</tr>
              <? if($_SESSION['suserlevel'] == '9') { ?>
							<tr>
								<th><span>*</span>회원레벨</th>
								<td><?=$sel_level?></td>
							</tr>
							<tr>
								<th><span>*</span>접근권한</th>
								<td>
									<p id='fl_txt'>
										<?
										$mq = '';
										foreach($GP -> MENU_ADMIN as $key => $val) {
											$menuQuery = $val['folder'];
											foreach ($GP -> MENU_SUB_ADMIN[$menuQuery] as $key1 => $val1) {
												foreach ($val1 as $key2 => $val2) {
													if(in_array($val2['title'], $arr_tmp2)) {
														echo $val2['name']. ", ";
													}
												}
											}
										}
										?>
									</p>
									<p id='bbs_txt'>
										<?
										for($i=0; $i<count($board_data); $i++) {
											$jba_title = $board_data[$i]['jba_title'];
											$jb_code = $board_data[$i]['jb_code'];
								
											if(in_array($jb_code, $arr_tmp1)) {
												echo $jba_title .", ";
											}
										}
										?>
									</p>
									<input type="button" value="접근권한선택" onClick="layerPop_new('iframset','./adm_auth_search.php', '100%', 650)" />
								</td>
							</tr>
              <? } ?>
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
		$('#mb_phone').numeric({allow:"-"});
		
		$('#img_submit').click(function(){					
			$('#base_form').submit();
			return false;
		});
		
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});
		
		$.validator.addMethod("DoubleCheck", function(mb_id) {
			var postURL = "/admin/common/DoubleIDCheck.php";
			var result;	
			
			var old_id = $('#old_mb_id').val();
			
			if(old_id == mb_id) {
				return true;
			}

			$.ajax({
				cache: false,
				async: false,
				type: "POST",
				data: "mb_id=" + mb_id,
				url: postURL,
				success: function(msg) {
					result = (msg == 'true') ? true : false;
				}
			});

			return result;
		}, '사용할 수 없는 아이디 입니다.');
		
		$('#mb_password').alphanumeric({allow:"!@#$%^&*()+=[]\\\';,/{}|\":<>?~`.-_"});
		
		$.validator.addMethod("alphanumeric", function(value, element) {
			return this.optional(element) ||   /^.*(?=.*\d)(?=.*[a-zA-Z0-9])(?=.*[!@#$%^&+=*-]).*$/.test(value);
		}, jQuery.validator.messages.alphanumeric);	
		
	/*	$.validator.addMethod("alphanumeric", function(value, element) {
			return this.optional(element) || /^\w[\w\s]*$/.test(value);
		}, "영문(소)과 숫자를 조합하세요."); 
	*/	
		$.validator.addMethod("emailcheck", function(value, element) {
			var val = value;
			return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(val);

		}, jQuery.validator.messages.emailcheck);
		
		$('#base_form').validate({
			rules: {	
				mb_id: { required: true, DoubleCheck:true },
				mb_name: { required: true },
				mb_email: { required: true , emailcheck:true },
				mb_phone: { required: true },
				mb_password: { required: true, minlength: 8, maxlength:15,  alphanumeric:true }			
			},
			messages: {	
				mb_id: { required: "아이디를 입력하세요" },
				mb_name: { required: "성명을 입력하세요" },
				mb_email: { required: "이메일을 입력하세요", emailcheck: "올바른 이메일을 입력하세요" },
				mb_phone: { required: "연락처를 입력하세요" }	,
				mb_password: { required: "패스워드를 입력하세요", /*minlength: $.format("패스워드는 {0}자 이상입니다"),*/ maxlength: $.format("패스워드는 {0}자 이하입니다.") ,alphanumeric : $.format("영문(소)과 특수문자,숫자를 조합하세요.")}
			},
			errorPlacement: function(error, element) {
				var er = element.attr("name");
				error.insertAfter(element);
			},
			submitHandler: function(frm) {
			if (!confirm("수정하시겠습니까?")) return false;                
				frm.action = './proc/main_proc.php';
				frm.submit(); //통과시 전송
				return true;
			},

			success: function(element) {
			//
			}
		
		});
		
	});
</script>
