<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/header.php");	
	include_once($GP -> CLS."/class.sms.php");
	$C_Sms 	= new Sms;	
	
	//보낼 데이터
	$data = array(
		'type' => 'count',
		'return_type' => 'json',			
		'login_id' => $GP -> SMS_ID,			
		'login_pwd' => $GP -> SMS_PWD	
	);	
	
	// Send a request to example.com 
	$result = $C_Func->post_request('http://mediinside.co.kr/api/sms_api.php', $data); 
	
	if ($result['status'] == 'ok'){ 
		$re_msg = $result['content'];	
		$obj_result = json_decode($re_msg); 		
		
		if($obj_result->msg == "true") {
			$ssa_num = $obj_result->cnt;			
		}else{
			$ssa_num = 0;
		}
	} else { 
		echo '통신 실패 : ' . $result['error']; 
	} 
	
	$now_date = date('Ymd');
	$now_time = date('His');	
	$now_time_10 = date("His", strtotime("+10 minutes"));	
	
	
	$sms_data = $C_Sms->Sms_Setup_Info();
?>
<script type="text/JavaScript" src="/js/jquery.alphanumeric.js"></script>
<script type="text/JavaScript">
	//<[CDATA[		  
	$(document).ready(function() {
	
	 	$('#txtReceiver').numeric({allow:"-"});  //숫자와 하이픈
		$('#txtSender').numeric({allow:"-"}); 		//숫자와 하이픈 
		
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
				
				var string = $("form[name='frmSms']").serialize();
								
				$.ajax({
					type: "POST",
					url: "./proc/sms_proc.php",
					data: string + "&phone=" + strValues + "&name=" + strNames,
					dataType: "json",
					beforeSend: function() {
						$('#div_load').html('<p><img src="/admin/img/common/loading.gif"></p>');
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
	
	
	function UserView()
	{
		layerPop('ifm_reg','./sms_user.php', 500, 400);
		//window.open('sms_user.php?ssa_idx=' + $('#ssa_idx').val(),'','left=300, top=200, width=460, height=360, menubar=no, scrollbars=no, status=no, toolbar=no');
	}	
	
	
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
<style type="text/css">
#container {
	margin: 0 auto;   /* align for good browsers */
	text-align: left; /* counter the body center */
	width: 100%;
}
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.invisible{
	clear:none;
	border:none;
	background-color:transparent;
	color:#FFFFFF;
	font-weight: bold;
}
</style>

<div class="right_content">
  <?=$C_Func -> adminTitleBar("SMS 관리");?>
  <div class="list_content">
    <div id="div_load" style="position:absolute; top:300px; margin:0 auto;"></div>
    <div id="container">
      <form id="frmSms" name="frmSms" method="post" action="?">
      	<input type="hidden" name="mode" id="mode" value="SMS_SEND" />
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="">
          <tr>
            <td valign="top" bgcolor="f1f1f1" align="left">            
              <table width="750" border="0" cellspacing="0" cellpadding="0" bgcolor="f1f1f1">
                <tr>
                  <td width="15" bgcolor="f1f1f1">&nbsp;</td>
                  <td width="211" align="left" valign="top" bgcolor="f1f1f1"><table width="211" border="0" cellpadding="0" cellspacing="0" >
                      <tr>
                        <td width="218" height="269" background="/admin/img/sms/sms_top_02.gif" style="background-repeat:no-repeat" valign="top">
                        	<table width="211" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td height="83"></td>
                            </tr>
                            <tr>
                              <td height="159" valign="middle" style="padding-left:30px;"><textarea id="txtContent" name="txtContent"  onkeyup="checkByte(this,80)" onkeypress="checkByte(this,80)" style="overflow:auto; width:150px; height:120px; border-color:#e9e9e9;"></textarea></td>
                            </tr>
                            <tr>
                              <td height="27" align="right" valign="middle" style="padding-right: 7px;"><input name="byte" type="text" class="invisible" id="byte" value="0" size="2"  />
                                <span class="style1"> /80 Byte </span></td>
                              <td width="20">&nbsp;</td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td background="/admin/img/sms/sms_top_03.gif" style="padding-left:25px;" align="center"><table width="160" border="0" align="left" cellpadding="0" cellspacing="0">
                            <tr>
                              <td height="5"></td>
                            </tr>
                            <tr>
                              <td height="25"><font style="color:#FFF;"><strong>받는번호</strong></font></td>
                              <td width="100"><input type="text" id="txtReceiver" name="txtReceiver" maxlength="15" size="14" /></td>
                            </tr>
                            <tr>
                              <td height="25" ><font style="color:#FFF;"><strong>회신번호</strong></font></td>
                              <td><input type="text" id="txtSender" name="txtSender" maxlength="15" size="14" value="<?=$sms_data['tss_mobile'];?>"  /></td>
                            </tr>
                            <tr>
                              <td height="25" ><font style="color:#FFF;"><strong>예약일자</strong></font></td>
                              <td><input type="text" id="txtdate" name="txtdate" maxlength="14"  size="14" placeholder="20090909" /></td>
                            </tr>
                            <tr>
                              <td height="25" ><font style="color:#FFF;"><strong>예약시간</strong></font></td>
                              <td><input type="text" id="txttime" name="txttime" maxlength="14"  size="14" placeholder="173000" /></td>
                            </tr>
                            <tr>
                              <td align="center" colspan="2" height="30" valign="bottom"><font style="color:#FFF;"><b>1회 전송 가능한 문자수</b><br />
                                영문 : 80자 한글 : 40자</font></td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td><img src="/admin/img/sms/sms_top_04.gif" border="0" usemap="#Map2" /></td>
                      </tr>                     
                    </table></td>
                  <td width="213" align="left" valign="top"><table width="213" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="left"  class="txt11_gb" height="40"><img src="/admin/img/sms/sms_title3.gif" /></td>
                        <td align="right"  class="txt11_gb"><img src="/admin/img/sms/btn_del_01.gif" vspace="5" id="img_del" style="cursor:hand" /></td>
                      </tr>
                      <tr>
                        <td  colspan="2" ><table width="213" border="0" cellpadding="0" cellspacing="5" bgcolor="#DCDCDC">
                            <tr>
                              <td height="400" align="center" valign="middle" bgcolor="#FFFFFF"><select id="group_select" name="group_select[]" multiple="multiple" style="width:173px; height:360px;">
                                </select></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                  <td width="213" align="left" valign="top"><table width="213" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td  height="40"><span class="txt11_gb"><img src="/admin/img/sms/sms_title4.gif" /></span></td>
                      </tr>
                      <tr>
                        <td height="333" valign="top"><table width="213" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td align="center"><table width="213" border="0" cellpadding="0" cellspacing="5" bgcolor="#DCDCDC">
                                  <tr>
                                    <td height="400" align="center" bgcolor="#FFFFFF"><table width="173" border="0" cellpadding="0" cellspacing="1" bgcolor="#DDDBDB">
                                        <tr>
                                          <td height="360" align="center" valign="top" bgcolor="#EEEEEE"><table border="0" cellspacing="0" cellpadding="0" style="padding:5px;">
                                              <tr>
                                                <td height="20">&nbsp;</td>
                                                <td >&nbsp;</td>
                                              </tr>
                                              <tr>
                                                <td height="26">남은 메세지 수 :</td>
                                                <td style="font-weight: bold;"><?= $ssa_num; ?>
                                                  건</td>
                                              </tr>
                                              <tr>
                                                <td height="20">&nbsp;</td>
                                                <td>&nbsp;</td>
                                              </tr>
                                              <tr>
                                                <td height="26" colspan="2" align="left">예약전송방법 : <br />
                                                  <br />
                                                  <p>1.예약시에는 텍스트 박스에 20070614(년월일)</p>
                                                  <p>180000 (시분초) </p>
                                                  <p> 형태로 입력해주세요</p>
                                                  <br />
                                                  <p> 2. 당일 예약시간은</p>
                                                  <p> 최소 10분 이상으로 설정 </p>
                                                  
                                                  <br />
                                                  <p>3.즉시 발송시에는 공백으로 놓아두면 됩니다.</p>
                                                  <br />
                                                  <p>4.충전 요청은 관리자에게 해주세요.</p>
                                                </td>
                                              </tr>
                                            </table></td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                </table></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>               
              </table></td>
          </tr>
        </table>
        <input type="hidden" id="username" name="username" />
        <input type="hidden" id="ssa_num" name="ssa_num" value="<?=$ssa_num?>" />
      </form>
    </div>
  </div>
</div>
<map name="Map2" id="Map2">
    <area shape="rect" coords="16,19,74,60" href="javascript:UserView()" />
    <area shape="rect" coords="74,19,144,61" href="#" id="img_submit"/>
    <area shape="rect" coords="144,19,200,61" href="#" id="img_reset" />
</map>
<?
include_once($GP -> INC_ADM_PATH."/footer.php");
?>
<script type="text/javascript">
	$(function() {
		callDatePick('s_date');
		callDatePick('e_date');
	});
</script>
