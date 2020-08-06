$(document).ready(function() {
	//엔터키 막기
	$("*").keypress(function(e){
		if(e.keyCode==13) 
		{			
			$('#base_form').submit();
			return false;
		}
		else
		{
			return true;	
		}
	});	

	$('#loginAdminpw').alphanumeric({allow:"!@#$%^&*()+=[]\\\';,/{}|\":<>?~`.-_"});
	
	$('#loginAdminId').focus();
	
	$.validator.addMethod("alphanumeric", function(value, element) {
		return this.optional(element) || /^\w[\w\s]*$/.test(value);
	}, "영문(소)과 숫자를 조합하세요.");	
	
	$('#base_form').validate({
		rules: {
			loginAdminId: { required:true },
			loginAdminpw: { required: true, minlength: 4, maxlength:15 }
		},
		messages: {				
			loginAdminId: { required: "아이디를 입력하세요"},
			loginAdminpw: { required: "패스워드를 입력하세요", minlength: $.format("패스워드는 {0}자 이상입니다"), maxlength: $.format("비밀번호는 {0}자 이하입니다.") }
		},
		errorPlacement: function(error, element) {
			var er = element.attr("name");
			error.insertAfter(element);
			
			//element.parent().find("span.my_error_display").html('');
			//error.appendTo(element.parent().find("span.my_error_display"));
		},
		submitHandler: function(frm) {
			if (!confirm("로그인 하시겠습니까?")) return false;
				frm.action = './action/login.proc.php';
				frm.submit(); //통과시 전송
				return true;
		},
		success: function(element) {
		//
		}
	});
});