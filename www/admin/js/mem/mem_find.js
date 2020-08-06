
$(document).ready(function(){
	
	$('#id_find').click(function() {
			if($("#find-id-form #mb_name").val() == '') {
				alert("이름은 필수사항 입니다.");
				$("#mb_name").focus();
				return false;
			} else if( $("#find-id-form #mb_email").val() == '') {
				alert("이메일을 입력해 주세요");
				$("#find-id-form #mb_email").focus();
				return false;
			} else {
				var data = $('#find-id-form').serialize();
				fnWinPopup('id_find_pop.html?' + data,'',640, 340);
			}
			return false;		
	});
	
	
	$('#pwd_find').click(function() {
			if($("#find-pw-form #mb_name").val() == '') {
				alert("이름을 입력해주세요.");
				$("#find-pw-form #mb_name").focus();
				return false;
			} else if( $("#find-pw-form #mb_email").val() == '') {
				alert("이메일을 입력해 주세요");
				$("#find-pw-form #mb_email").focus();
				return false;
			}else {
				if( confirm(" 비밀번호 확인 요청을 하시면, \n\n 가입 시 회원님의 이메일 주소로  \n\n 임시 비밀번호가 발급됩니다. \n\n 이메일에 따라 일부는 스팸편지함으로 \n\n 수신되오니 참고 바랍니다. \n\n 전달 받으시겠습니까?") ) {
					var data = $('#find-pw-form').serialize();
					
					$.ajax({
						url: "/inc/search_pw.php",
						type: 'POST',
						data: data,
						contentTypeString : "text/xml; charset=utf-8",				
						error: function(){
							alert('데이터 전송중 에러가 발생하였습니다.');
						},
						success: function(msg){							
							//$('#pwresult').html(msg);
							
							var data = "msg=" + msg;							
							fnWinPopup('pw_find_pop.html?' + data,'',640, 340);
						}			  			
					});					
				}
			}	
			return false;			
	});
});