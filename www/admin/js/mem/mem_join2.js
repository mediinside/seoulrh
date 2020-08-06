$(document).ready(function(){		

		$('#mb_pwd').alphanumeric({allow:"!@#$%^&*()+=[]\\\';,/{}|\":<>?~`.-_"});
		$('#mb_pwd_ok').alphanumeric({allow:"!@#$%^&*()+=[]\\\';,/{}|\":<>?~`.-_"});
		$('#mb_mobile2').numeric();
		$('#mb_mobile3').numeric();						   
		
		
		$('#btn_submit').click(function(){
			$('#frmJoin').submit();
			return false;
		});
		
		
		$('#email_sel').change(function(){
			var val = $(this).val();

			if(val == "") {
				$('#mb_email2').val('');
			}else{
				$('#mb_email2').val(val);
			}
		});
		
		
		$.validator.addMethod("alphanumeric", function(value, element) {
			return this.optional(element) ||   /^.*(?=.*\d)(?=.*[a-zA-Z0-9])(?=.*[!@#$%^&+=*-]).*$/.test(value);
		}, jQuery.validator.messages.alphanumeric);		
		
		$.validator.addMethod("emailcheck", function(value, element) {
			var val = $('#mb_email1').val() + '@' + value;
			return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(val);

		}, jQuery.validator.messages.emailcheck);
		
	/*	$.validator.addMethod("alphanumeric", function(value, element) {
			return this.optional(element) || /^\w[\w\s]*$/.test(value);
		}, jQuery.validator.messages.alphanumeric);
	*/	
		$.validator.addMethod("DoubleCheck", function(m_email2) {
			var postURL = "/inc/email_ck_dup.php";
			var result;
			var m_email = $('#mb_email1').val() + "@" + m_email2;

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
		}, jQuery.validator.messages.DoubleCheck);	
		
		$.validator.addMethod("DoubleIdCheck", function(mb_id) {
			var postURL = "/inc/DoubleIDCheck.php";
			var result;			

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
		},  jQuery.validator.messages.DoubleIdCheck );
		

		$('#frmJoin').validate({
			rules: {
				mb_id : { required:true , minlength: 4, DoubleIdCheck:true } ,				
				mb_email1: { required:true } ,
				mb_email2: { required:true, emailcheck:true, DoubleCheck:true },
				mb_name : { required:true },			
				mb_pwd: { required: true, minlength: 8, maxlength:16},
				mb_pwd_ok: { required: true, minlength: 5, equalTo: "#mb_pwd" }	,	
				mb_zip_code : { required:true },	
				mb_address1 : { required:true },	
				mb_address2 : { required:true },
				mb_mobile1 : { required:true },	
				mb_mobile2 : { required:true ,minlength: 4 , maxlength: 4},
				mb_mobile3 : { required:true ,minlength: 4 , maxlength: 4}		
			},
			messages: {	
				mb_id: { required: "아이디를 입력하세요", minlength: $.format("아이디는 {0}자 이상입니다"), alphanumeric : $.format("영문(소)과 숫자를 조합하세요."), DoubleIdCheck : $.format("사용할 수 없는 아이디입니다.")},				
				mb_email1: { required: "이메일을 입력하세요"},
				mb_email2: { required: "이메일을 입력하세요", emailcheck: "올바른 이메일을 입력하세요" ,DoubleCheck:"이미 등록된 이메일 입니다"},
				mb_name: { required: "성명을 입력하세요"},								
				mb_pwd: { required: "패스워드를 입력하세요", minlength: $.format("패스워드는 {0}자 이상입니다"), maxlength: $.format("패스워드는 {0}자 이하입니다."), alphanumeric : $.format("영문(소)과 특수문자,숫자를 조합하세요.")},
				mb_pwd_ok: { required: "패스워드를 재입력하세요", minlength: $.format("패스워드는 {0}자 이상 입력하세요"), equalTo: "패스워드는 일치하지 않습니다" },	
				mb_zip_code: { required: "우편번호를 입력하세요"},			
				mb_address1: { required: "기본주소를 입력하세요"},			
				mb_address2: { required: "상세주소를 입력하세요"},			
				mb_mobile1: { required: "전화번호를 입력하세요"},
				mb_mobile2: { required: "핸드폰 번호를 입력하세요", minlength: $.format("핸드폰 번호는 {0}자 이상 입력하세요") ,maxlength: $.format("핸드폰 번호는 {0}자 이하로 입력하세요")},
				mb_mobile3: { required: "핸드폰 번호를 입력하세요", minlength: $.format("핸드폰 번호는 {0}자 이상 입력하세요") ,maxlength: $.format("핸드폰 번호는 {0}자 이하로 입력하세요")}
										
			},
			onkeyup:false,
			onclick:false,
			onfocusout:false,			
			showErrors: function(errorMap, errorList) {
			if(!$.isEmptyObject(errorList)){
		      var caption = $(errorList[0].element).attr('caption') || $(errorList[0].element).attr('name');
					alert(errorList[0].message);
				}
		
			},
			submitHandler: function(frm) {
				if (!confirm("가입 하시겠습니까?")) return false;
				//	frm.action = 'http://paragoncrt.co.kr/member/proc/mem_proc.html';
					frm.submit(); //통과시 전송
					return true;
			}, 
			success: function(element) {
			//
			}
		});
		
		
	});