$(document).ready(function() {
	//엔터키 막기
	$("*").keypress(function(e){
		if(e.keyCode==13) 
		{
			admLoginCheck();
			return false;
		}
		else
		{
			return true;	
		}
	});	
	
	$('#loginAdminId').focus();
	
});



function admLoginCheck(){
	var f = document.base_form;
	var msg = "";
	var aRst = "";
	var login_id   = f.loginAdminId;
	var login_pw = f.loginAdminpw;
		
	$("#login_result").empty();
	$('#login_result').html("<img src='/admin/img/common/loading.gif'>"); 
						 
	if(login_id.value.length < 3){		
		msg = "관리자 ID를 입력해주세요!";
		$('#login_result').html(msg); login_id.focus(); return;
	}
	
	if(login_pw.value.length < 3){		
		msg = "관리자 비밀번호를 입력해주세요!";
		$('#login_result').html(msg); login_pw.focus(); return;
	}
	
	$("#login_result").empty();
	
	// 인자, Action,errorDis, result id	
	returnAjaxData('&mode=login&login_id='+login_id.value+'&login_pw='+login_pw.value,'./action/login.proc.php',false, 'login_result');	
}