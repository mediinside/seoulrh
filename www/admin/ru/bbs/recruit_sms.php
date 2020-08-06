<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP->CLS."class.recruit.php");
	include_once($GP -> CLS."/class.sms.php");
	include_once($GP -> CLS."/class.api.php");
	$C_Recruit 	= new Recruit;
	$C_Sms 	= new Sms;	
	$C_Api 	= new Api;	
	
	$args = '';
	$re_num =	$C_Api->Api_Sms_Remain_Num($args);
	
	$now_date = date('Ymd');
	$now_time = date('His');	
	$now_time_10 = date("His", strtotime("+10 minutes"));
		
	$sms_data = $C_Sms->Sms_Setup_Info();


	$args = '';
	$args['arr_idx'] = $_GET['arr_idx'];
	$data = $C_Recruit->Recruit_info_All($args);
?>

<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>입사지원 문자발송</strong></span>
		</div>
		<div class="boxContentBody">
			<div class="boxMemberInfoTable_layer">
				<div class="layerTable"> 
					<div id="div_load" style="position:absolute; top:300px; margin:0 auto;"></div>
					<div id="container">
						<div class="smsWrap">
							<form id="frmSms" name="frmSms" method="post" action="?">
								<input type="hidden" name="mode" id="mode" value="SMS_SEND" />
								<div class="phoneWrap">
									<div class="phoneInner">
										<textarea id="txtContent" name="txtContent" onKeyUp="checkByte(this,80)" onKeyPress="checkByte(this,80)" style=""></textarea>
										<p><input name="byte" type="text" class="invisible" id="byte" value="0" size="2" /> <span> / 80 Byte </span></p>
										<ul>
											<li>
												<label for="txtReceiver">받는번호</label>
												<input type="text" id="txtReceiver" name="txtReceiver" maxlength="15" size="14" placeholder="010-0000-0000" value="<? if(count($data) == 1) { echo $data[0]['rc_mobile']; }?>" />
											</li>
											<li>
												<label for="txtSender">회신번호</label>
												<input type="text" id="txtSender" name="txtSender" maxlength="15" size="14" value="<?=$sms_data['tss_mobile'];?>" />
											</li>
											<li>
												<label for="txtdate">예약일자</label>
												<input type="text" id="txtdate" name="txtdate" maxlength="14"  size="14" placeholder="20090909" />
											</li>
											<li>
												<label for="txttime">예약시간</label>
												<input type="text" id="txttime" name="txttime" maxlength="14"  size="14" placeholder="173000" />
											</li>
										</ul>
										<p class="sms"><strong>1회 전송 가능한 문자수</strong><br /> 영문 : 80자 한글 : 40자</p>
										<div class="btnWrap">
											<button type="button" id="img_submit">보내기</button>
											<button type="button"></button>
											<button type="button" id="img_reset">다시쓰기</button>
										</div>
									</div>
								</div>
								<div class="groupWrap">
									<div class="smsbox">
										<h2 class="tit">그룹선택항목</h2>
										<input type="button" id="img_del" class="btns btnGray" value="선택삭제">
										<p class="selHead"><span>Name</span> Number</p>
										<select id="group_select" name="group_select[]" multiple="multiple">
											<?
												if(count($data) > 1) {
													for($i=0; $i<count($data); $i++) {
														$name = $data[$i]['rc_name'];
														$mobile = $data[$i]['rc_mobile'];
											?>
											<option value="<?=$mobile?>"><?=$name?> | <?=$mobile?></option>
											<?
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="massageWrap">
									<div class="smsbox">
										<h2 class="tit">메세지 관리</h2>
										<p class="selHead">남은 메세지 수 : <?= $re_num; ?> 건</p>
										<p>예약전송방법 :</p>
										<ul>
											<li>1. 예약시에는 텍스트 박스에<br /> 예약일자 - 20070614 (년월일)<br /> 예약시간 - 093060 (시분초)<br /> 형태로 입력해주세요.</li>
											<li>2. 당일 예약시간은 최소 10분 이상으로 설정</li>
											<li>3. 즉시 발송시에는 공백으로 놓아두면 됩니다.</li>
											<li>4. 충전 요청은 관리자에게 해주세요.</li>
										</ul>
									</div>
								</div>
								<input type="hidden" id="username" name="username" />
								<input type="hidden" id="ssa_num" name="ssa_num" value="<?=$re_num?>" />
							</form>
						</div>
					</div>
				</div>
				<div class="btnWrap">				
					<button id="img_cancel" class="btnSearch ">닫기</button>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<script src="/admin/js/jquery.alphanumeric.js" type="text/javascript"></script>
<script type="text/javascript">
	//<[CDATA[		  
	$(document).ready(function() {
	
	 	$('#txtReceiver').numeric({allow:"-"});  //숫자와 하이픈
		$('#txtSender').numeric({allow:"-"}); 		//숫자와 하이픈 

		$('#img_cancel').click(function(){
				parent.modalclose();				
		});
		
		//폼체크
		$('#img_submit').click(function(){
			
			var Con = $('#txtContent');
			var Receiver = $('#txtReceiver');
			var sel = $('#group_select option');
			
			if(Con.val().replace(/\s/g, "") == '')
			{
				alert('내용을 입력하세요');
				Con.focus(); 
				return false;
			}		
			
			if(sel.length == 0)		
			{
				if(Receiver.val().replace(/\s/g, "") == '')			
				{
					alert('받는번호를 입력하세요');
					Receiver.focus();  
					return false;
				}			
			} 	
			
			msg_send();
										
		});
		
		
		//메세지 전송
		function msg_send()
		{	
			
			var strValues = "";
			var strNames = "";			
			var count = $('#ssa_num').val();
			
			if(count > 0)
			{	
				if($('#group_select option').length > count)
				{
					alert("남은 메세지 건수보다 보낼 사람수가 많습니다");
					return false;
				}		
				
				if($('#txtdate').val() == '<?=$now_date?>') {
					
					var time = $('#txttime').val();
					var time_10 = '<?=$now_time_10?>';
					
					if(Number(time) < Number(time_10)){
						alert("당일 예약시간은 최소 현재시간보다 10분이상 설정하셔야 합니다");
						$('#txttime').focus();
						return false;
					}
					
				}
				
				$('#group_select option').each(function(i) {													 
					 if (i == 0) {
						 strValues = $(this).val();
						 strNames = $(this).text();
					 }
					 else {
						 strValues = strValues + ";" + $(this).val();
						 strNames = strNames + ";" + $(this).text();
					 }					 
				});	
				
				var string = $("#frmSms").serialize();
				
				//$('#frmSms').attr('action','./proc/sms_proc.php');
				//$('#frmSms').submit();
				//return false;				
											
				$.ajax({
					type: "POST",
					url: "./proc/recruit_proc.php",
					data: string + "&phone=" + strValues + "&name=" + strNames,
					dataType: "json",
					beforeSend: function() {
						$('#div_load').html('<p><img src="/admin/images/loading.gif"></p>');
					},   
					success: function(json) {			
						
						$('#div_load').remove();										
						var result = json.msg;
						
						if(result == "true") {
							alert("메세지가 발송되었습니다");
							window.location.reload(true);
						}else{
							
							var error = json.error;
							
							if(error == "pwd_fail")
						  {
								alert("SMS 아이디 또는 패스워드가 일치하지 않습니다."); 
						  }						  
						  else if(error == "num_fail")
						  {
								alert("SMS 남은 발송건수가 부족합니다. 충전해주세요"); 	  
						  }
							else{
								alert("메세지가 발송이 실패하였습니다. 관리자에게 문의하세요. 에러 코드 : " + error);						
							}
							return false;							
						}
					},
					error: function(xhr, status, error) { alert(error); }
				});		
			}
			else
			{
				alert("남은 메세지 수가 없습니다");
				return false;
			}
		}
		
		//다시쓰기
		$('#img_reset').click(function(){
			var Con = $('#txtContent');
			var Receiver = $('#txtReceiver');
			var byte = $('#byte');
			
			Con.val('');
			byte.val('');
			Con.focus();
			Receiver.val('');
			
		});
		
		
		$('#img_del').click(function(){		
			$('#group_select option:selected').remove();
		});

	});	
	
	// 바이트 체크
	function checkByte(obj, length_limit) {
	   
		var length = calculate_msglen(obj.value);            
	
		if (length > length_limit) {
			alert("최대 " + length_limit + "byte이므로 초과된 글자수는 자동으로 삭제됩니다.");
			obj.value = obj.value.replace(/\r\n$/, "");
			obj.value = assert_msglen(obj.value, length_limit);
	
			document.getElementById("byte").value = "80";              
		}
		else
			document.getElementById("byte").value = length;
	}
	function calculate_msglen(message) {
		var nbytes = 0;
	
		for (i = 0; i < message.length; i++) {
			var ch = message.charAt(i);
			if (escape(ch).length > 4) {
				nbytes += 2;
			}
			else if (ch == '\n') {
				if (message.charAt(i - 1) != '\r') {
					nbytes += 1;
				}
			}
			else if (ch == '<' || ch == '>') {
				nbytes += 4;
			}
			else {
				nbytes += 1;
			}
		}
	
		return nbytes;
	}
	function assert_msglen(message, maximum) {
		var inc = 0;
		var nbytes = 0;
		var msg = "";
		var msglen = message.length;
	
		for (i = 0; i < msglen; i++) {
			var ch = message.charAt(i);
			if (escape(ch).length > 4) {
				inc = 2;
			}
			else if (ch == '\n') {
				if (message.charAt(i - 1) != '\r') {
					inc = 1;
				}
			}
			else if (ch == '<' || ch == '>') {
				inc = 4;
			}
			else {
				inc = 1;
			}
			if ((nbytes + inc) > maximum) {
				break;
			}
			nbytes += inc;
			msg += ch;
		}
		//textlimit.innerText = nbytes;
		return msg;
	}
</script>
