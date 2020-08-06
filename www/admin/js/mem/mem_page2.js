	$(document).ready(function(){
							   
			$('#mb_pwd').alphanumeric({allow:"!@#$%^&*()+=[]\\\';,/{}|\":<>?~`.-_"});
			$('#mb_pwd_ok').alphanumeric({allow:"!@#$%^&*()+=[]\\\';,/{}|\":<>?~`.-_"});
			$('#mb_birth').numeric();
			$('#mb_mobile2').numeric();
			$('#mb_mobile3').numeric();
			  
										   
			$('#email_sel').change(function(){
				var val = $(this).val();
	
				if(val == "") {
					$('#mb_email2').val('');
				}else{
					$('#mb_email2').val(val);
				}
			});							   
								   
			$('#search_post').click(function() {										 
				window.open('/inc/address_pop.html?obj=mb_post1&obj1=mb_post2&obj2=mb_addr1&obj3=mb_addr2&obj4=mb_load_addr1&obj5=mb_load_addr2', 'ifm_addr', 'width=500,height=600,resizable=yes,scrollbars=no,status=no,toolbar=no' );
			});	
			
			$('#btn_submit').click(function(){
				$('#frmJoin').submit();
				return false;
			});
			
			$('#btn_cancel').click(function(){
				location.href = '/';
				return false;
			});
			
			$('#btn_pass_submit').click(function(){
				$('#frmPass').submit();
				return false;
			});
			
			$('#btn_pass_cancel').click(function(){
				location.href = '/';
				return false;
			});
			
			$('#btn_with_submit').click(function(){
				$('#frmWith').submit();
				return false;
			});
			
			$('#btn_with_cancel').click(function(){
				location.href = '/';
				return false;
			});
			
				
			$.validator.addMethod("emailcheck", function(value, element) {
				var val = value;
				return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(val);
			
			}, jQuery.validator.messages.emailcheck);
			
			$.validator.addMethod("alphanumeric", function(value, element) {
				return this.optional(element) || /^\w[\w\s]*$/.test(value);
			}, "영문(소)과 숫자를 조합하세요.");
			
			$.validator.addMethod("DoubleCheck", function(m_email) {
				var postURL = "/inc/DoubleEmailCheck.php";
				var result;
				var old_email = $('#old_email').val();
				
				if(m_email == old_email) {
					return true;
				}
	
				$.ajax({
					cache: false,
					async: false,
					type: "POST",
					data: "mb_email=" + m_email,
					url: postURL,
					success: function(msg) {
						result = (msg == 'true') ? true : false;
					}
				});
	
				return result;
			},  jQuery.validator.messages.DoubleCheck);	
			
			$.validator.addMethod("passch", function(mb_pwd) {
				var postURL = "/inc/PassCheck.php";
				var result;
				var mb_code = $('#mb_code').val();
				
				$.ajax({
					cache: false,
					async: false,
					type: "POST",
					data: "mb_pwd=" + mb_pwd + "&mb_code=" + mb_code,
					url: postURL,
					success: function(msg) {
						result = (msg == 'true') ? true : false;
					}
				});
	
				return result;
			},  jQuery.validator.messages.passch);
	
			
			$('#frmJoin').validate({
				rules: {
					mb_email: { required:true, emailcheck:true, DoubleCheck:true },
					mb_name : { required:true },
					mb_mobile1 : { required:true },
					mb_mobile2 : { required:true ,minlength: 4 , maxlength: 4},
					mb_mobile3 : { required:true ,minlength: 4 , maxlength: 4},
					mb_pwd: { required: true, minlength: 4, maxlength:15, alphanumeric:true, passch:true }					
				
				},
				messages: {				
					mb_email: { required: "이메일을 입력하세요", emailcheck: "올바른 이메일을 입력하세요" ,DoubleCheck:"사용할 수 없는 이메일 입니다"},
					mb_name: { required: "성명을 입력하세요"},
					mb_mobile1: { required: "휴대전화 번호를 선택하세요"},
					mb_mobile2: { required: "휴대전화 번호를 입력하세요", minlength: $.format("휴대전화 번호는 {0}자 이상 입력하시오") ,maxlength: $.format("휴대전화 번호는 {0}자 이상 입력하시오")},
					mb_mobile3: { required: "휴대전화 번호를 입력하세요", minlength: $.format("휴대전화 번호는 {0}자 이상 입력하시오") ,maxlength: $.format("휴대전화 번호는 {0}자 이상 입력하시오")},
					mb_pwd: { required: "패스워드를 입력하세요", minlength: $.format("패스워드는 {0}자 이상입니다"), maxlength: $.format("패스워드는 {0}자 이하입니다.") ,passch:"비밀번호가 일치하지 않습니다."}
				
				},
				onkeyup:false,
				onclick:false,
				onfocusout:false,			
				showErrors: function(errorMap, errorList) {
			/*		if(!$.isEmptyObject(errorList)){
						var caption = $(errorList[0].element).attr('caption') || $(errorList[0].element).attr('name');
						alert(errorList[0].message);
					}
			*/
				},
				submitHandler: function(frm) {
					if (!confirm("수정 하시겠습니까?")) return false;
						frm.action = './proc/mem_proc.html';
						frm.submit(); //통과시 전송
						return true;
				},
				success: function(element) {
				//
				}
			});
			
			$('#frmPass').validate({
				rules: {				
					mb_pwd: { required: true, minlength: 4, maxlength:15, alphanumeric:true, passch:true },
					mb_pwd_ch: { required: true, minlength: 4, maxlength:15, alphanumeric:false },
					mb_pwd_ok: { required: true, minlength: 5, equalTo: "#mb_pwd_ch" }
				},
				messages: {				
					mb_pwd: { required: "패스워드를 입력하세요", minlength: $.format("패스워드는 {0}자 이상입니다"), maxlength: $.format("패스워드는 {0}자 이하입니다."),passch:"비밀번호가 일치하지 않습니다." },
					mb_pwd_ch: { required: "패스워드를 입력하세요", minlength: $.format("패스워드는 {0}자 이상입니다"), maxlength: $.format("패스워드는 {0}자 이하입니다.") },
					mb_pwd_ok: { required: "패스워드를 재입력하세요", minlength: $.format("패스워드는 {0}자 이상 입력하시오"), equalTo: "패스워드는 일치하지 않습니다" }
				},
				onkeyup:false,
				onclick:false,
				onfocusout:false,			
				showErrors: function(errorMap, errorList) {
				/*	if(!$.isEmptyObject(errorList)){
						var caption = $(errorList[0].element).attr('caption') || $(errorList[0].element).attr('name');
						alert(errorList[0].message);
					}
				*/
				},
				submitHandler: function(frm) {
					if (!confirm("비밀번호를 변경 하시겠습니까?")) return false;
						frm.action = './proc/mem_proc.html';
						frm.submit(); //통과시 전송
						return true;
				},
				success: function(element) {
				//
				}
			});
			
			
			$.validator.addMethod("reason_check", function(value, element, param ) {
					if($(":input:radio[name=reason]:checked").val() != '9'){
						return true;
					}else{																						 
						// check if dependency is met
						if ( !this.depend(param, element) ) {
							return "dependency-mismatch";
						}
						if ( element.nodeName.toLowerCase() === "select" ) {
							// could be an array for select-multiple or a string, both are fine this way
							var val = $(element).val();
							return val && val.length > 0;
						}
						if ( this.checkable(element) ) {
							return this.getLength(value, element) > 0;
						}
						return $.trim(value).length > 0;					
					}				
			}, jQuery.validator.messages.reason_check);	
			
			
			$('#frmWith').validate({
				rules: {				
					dropoutAgree: { required: true },
					reason : { required: true },
					proposal : { reason_check:true}
				},
				messages: {				
					dropoutAgree: { required: "탈퇴약관에 동의하셔야 합니다." },
					reason: { required: "탈퇴사유를 선택하세요" },
					proposal :{ reason_check: "탈퇴사유를 입력하세요" }
				},
				onkeyup:false,
				onclick:false,
				onfocusout:false,			
				showErrors: function(errorMap, errorList) {
				/*	if(!$.isEmptyObject(errorList)){
						var caption = $(errorList[0].element).attr('caption') || $(errorList[0].element).attr('name');
						alert(errorList[0].message);
					}
				*/
				},
				submitHandler: function(frm) {
					if (!confirm("탈퇴하시겠습니까?")) return false;
						frm.action = './proc/mem_proc.html';
						frm.submit(); //통과시 전송
						return true;
				},
				success: function(element) {
				//
				}
			});
			
		});