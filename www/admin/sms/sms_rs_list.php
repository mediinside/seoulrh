<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP -> CLS."/class.sms.php");	
	include_once($GP -> CLS."/class.reserve.php");	
	$C_Sms 	= new Sms();
	$C_Reserve 	= new Reserve;
	
	if($_GET['s_date'] == '') {
		$s_date = $C_Func->request_term_day(date('Y-m-d'), 1);
	}else{
		$s_date = $_GET['s_date'];
	}

	$args = '';
	$args['s_date']= $s_date;
	$arr_mem = $C_Reserve->Reserve_Sms_List($args);
?>

<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>예약자 선택</strong></span>
		</div>		
		<div class="boxContentBody">
			<div class="boxMemberInfoTable_layer">		
				<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
				<div class="boxBoardHead">
					<span style="line-height:24px;">예약일자</span>
					<span><input type="text" name="s_date" id="s_date" value="<?=$s_date?>" class="input_text" size="20"></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</div>
				<br />
				<div class="manageBox">
					<div id="SubMenu2">
						<div class="mngSection">
							<p class="tit"><strong>회원명</strong></p>
							<div class="selBox">
								<select name="available" multiple="multiple" id="available" style="height:200px;">
									<?
										for($i=0; $i<count($arr_mem); $i++) {
											$mb_name = $arr_mem[$i]['mb_name'];
											$mb_code = $arr_mem[$i]['mb_code'];
											$mb_mobile = $arr_mem[$i]['mb_mobile'];
											echo "<option value='$mb_mobile'>$mb_name | $mb_mobile</option>";																		
										}
									?>
								</select>
							</div>
						</div>
						<p class="mngMove">
							<a href="#" onClick="person_select();"><img src="/admin/images/sms/btn_right.png" alt="가져오기" /></a><br />
							<a href="#" onClick="person_remove();"><img src="/admin/images/sms/btn_left.png" alt="선택항목 빼기" /></a>
						</p>
						<div class="mngSection">
							<p class="tit"><strong>선택항목</strong></p>
							<div class="selBox">
								<select name="choiceBox" multiple="multiple" id="choiceBox" style="height:200px;">
								</select>
							</div>
						</div>
						<div class="btnWrap">
							<input type="button" onClick="person_save();" value="확인" class="btnM btnIdt" />
							<input type="button" onClick="parent.modalclose();" value="닫기" class="btnM btnGray" />
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<? include_once($GP -> INC_ADM_PATH."/footer.php"); ?>
</body>
</html>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.alphanumeric.js"></script>
<script type="text/javascript">
	//<[CDATA[		
	
	
	$('#search_submit').click(function(){																				
		location.href = "?s_date=" + $('#s_date').val();
		return false;
	});
	
	
	//개인별 선택항목 이동         
	function person_select() {		
		$('#available option:selected').each(function() { 		
			var val = $(this).val();
			var text = $(this).text();		
			var tar = $('#choiceBox option');  
			var check = 0;
			
			if(tar.length == 0)
			{				 
				$('#choiceBox').append($('<option></option').val(val).html(text));                
			}
			else
			{
				$('#choiceBox option').each(function(){												
					if(val == $(this).val())	
					{	
						check = 1;		
					}							
				});
			
				if(check != 1)				
				{
					//현재 선택항목을 append
					$('#choiceBox').append($('<option></option').val(val).html(text)); 
				}				 	
			}
		});
	}
	
	//개인별 선택항목 삭제
	function person_remove() {
		$('#choiceBox option:selected').remove();
	}  		 
	
	//개인별 선택저장
	function person_save() {
	
		var tar = $('#choiceBox option');             
		
		if (tar.length == 0) {
			 alert("SMS 보내실 분을 선택하세요");
			 return;
		}			 
		
		//한명 선택시
		if(tar.length == 1)
		{
			$('#choiceBox option').each(function() { 		
				var val = $(this).val();
				var text = $(this).text();
		
				parent.document.forms["frmSms"].txtReceiver.value = val;					 
				parent.document.forms["frmSms"].username.value = text;
				parent.document.forms["frmSms"].txtSender.focus();					 
				
				parent.document.getElementById("group_select").options.length = 0;
				parent.document.getElementById("group_select").value = '';
			});	 
		}
		else
		{
			$('#choiceBox option').each(function() {		
				var val = $(this).val();		//value
				var text = $(this).text();		//text					
				var openselect = $(parent.document).find('#group_select option');	//부모창 select 박스
				var check = 0;	//부모창에 같은 번호가 있는지 체크여부					
				
				if(openselect.length == 0)	//부모창의 select 박스가 0이면
				{
					//현재 선택항목을 append
					$(parent.document).find('#group_select').append("<option value='" + val + "'>" + text + "</option>");	
				}					
				else
				{					 
					$(parent.document).find('#group_select option').each(function(){																		
						if(val == $(this).val())	//선택항목의 값과 부모창의 선택리스트중 같은 항목이 있으면
						{	
							check = 1;		//체크필드 1																 
						}							
					});
			
					if(check != 1)	//선택항목의 값과 부모창의 선택리스트중 같은 항목이 없으면
					{
						//현재 선택항목을 append
						$(parent.document).find('#group_select').append("<option value='" + val + "'>" + text + "</option>");	
					}
				}
			});				
			$(parent.document).find('#txtReceiver').val('');
			$(parent.document).find('#txtReceiver').attr('readonly', 'readonly');
			$(parent.document).find('#username').val('');
			parent.document.forms["frmSms"].txtSender.focus();
		}		
		parent.modalclose();
	}
	
	
//]]>      
</script>













