<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP -> CLS."/class.sms.php");	
	$C_Sms 	= new Sms();

	//error_reporting(E_ALL);
	//@ini_set("display_errors", 1);
	ini_set("memory_limit" , -1);	
	
	$args = '';
	$arr_group = $C_Sms->Sms_Group_list($args);
	
	$args = '';
	$arr_mem = $C_Sms->Sms_Mem_list($args);
?>

<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>이벤트 수정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="SE_REG" />
		<div class="boxContentBody">
			<div class="boxMemberInfoTable_layer">
				<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
					<div class="boxBoardHead">
						<ul>
							<li><a href="javascript:fnSubMenu(2);" class="active">개인</a></li>
							<li><a href="javascript:fnSubMenu(1);">그룹</a></li>
						</ul>
					</div>
					<p class="mngBtn"><input type="button" onclick="GroupView();" value="그룹/회원관리" class="btns"></p>
					<div class="manageBox">
						<div id="SubMenu1" style="display:none;">
							<div class="mngSection">
								<p class="tit"><strong>그룹명</strong></p>
								<div class="selBox">
									<select name="lbGroup" multiple="multiple" id="lbGroup" style="height:70px;">
										<? 
											for($i=0; $i<count($arr_group); $i++) {
												$gr_name = $arr_group[$i]['gr_name'];
												$gr_code = $arr_group[$i]['gr_code'];
												echo "<option value='$gr_code'>$gr_name</option>";
											}																	
										?>
									</select>
									<p class="groupBtn"><input type="button" onClick="group_list();" class="btns btnGray" value="그룹리스트 가져오기" /></p>
									<select name="lbGrouplist" multiple="multiple" id="lbGrouplist" style="height:85px;">
									</select>
								</div>
							</div>
							<p class="mngMove">
								<a href="#" onClick="group_select();"><img src="/admin/images/sms/btn_right.png" alt="가져오기" /></a><br />
								<a href="#" onClick="group_remove();"><img src="/admin/images/sms/btn_left.png" alt="선택항목 빼기" /></a>
							</p>
							<div class="mngSection">
								<p class="tit"><strong>선택항목</strong></p>
								<div class="selBox">
									<select name="lbGroupSelect" multiple="multiple" id="lbGroupSelect" style="height:200px;">
									</select>
								</div>
							</div>
							<div class="btnWrap">
								<input type="button" onClick="group_save();" value="확인" class="btnM btnIdt" />
								<input type="button" onClick="parent.modalclose();" value="닫기" class="btnM btnGray" />
							</div>
						</div>
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
		</form>
	</div>
</div>
</body>
</html>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.alphanumeric.js"></script>
<script type="text/javascript">
	//<[CDATA[
	$('.boxBoardHead a').click(function(){
		var _this = $(this)
			,tab = $('.boxBoardHead a');
		tab.removeClass('active');
		_this.addClass('active');
	});
	//개인. 그룹 구분 탭
	function fnSubMenu(MenuNum) {
		if (MenuNum == 1) {
			 $('#SubMenu1').css('display', 'block');
			 $('#SubMenu2').css('display', 'none');
		}
		else {
			 $('#SubMenu1').css('display', 'none');
			 $('#SubMenu2').css('display', 'block');
		}
	}
	
	//그룹별 선택항목
	function group_list() {
	
		var GroupCode = $('#lbGroup option:selected').val();
	
		if(GroupCode == undefined)
		{
			alert("그룹을 선택하세요");
			return false;
		}
	
		resetSelect();	//그룹리스트 초기화
	
		//아약으로 그룹리스트 가져오기
		$.ajax({
			type: "POST",
			url: "sms_userlist.php",
			data: "GroupCode=" + GroupCode, 
			dataType: "xml",
			success: function(xml) {
				if ($(xml).find("UserGroup").find("User").length > 0) {
					$(xml).find("UserGroup").find("User").each(function(i) {
						var id = $(this).attr("id");
						var value = decodeURIComponent($(this).text());
						if(id != "")
						{
							document.getElementById("lbGrouplist").options[i] = new Option(value, id);							 
						}
					});
					document.getElementById("lbGrouplist").disabled = false;
					document.getElementById("lbGrouplist").focus();
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
	}
	
	//그룹리스트 초기화
	function resetSelect() {
		document.getElementById("lbGrouplist").options.length = 0;
		document.getElementById("lbGrouplist").value = '';
	}
	
	//그룹별 선택항목 이동         
	function group_select() {		
		$('#lbGrouplist option:selected').each(function() { 				 
			var val = $(this).val();
			var text = $(this).text();
			var tar = $('#lbGroupSelect option');  
			var check = 0;
		
			if(tar.length == 0)
			{				 
				$('#lbGroupSelect').append($('<option></option').val(val).html(text));                
			}
			else
			{
				$('#lbGroupSelect option').each(function(){												
					if(val == $(this).val())	
					{	
						check = 1;		
					}							
				});
			
				if(check != 1)	
				{
					//현재 선택항목을 append
					$('#lbGroupSelect').append($('<option></option').val(val).html(text)); 
				}				 	
			}
		});
	}
	
	//그룹별 선택항목 삭제
	function group_remove() {
		$('#lbGroupSelect option:selected').remove();
	}    
	
	//그룹별 선택저장
	function group_save() {
	
		var tar = $('#lbGroupSelect option');             
		
		if (tar.length == 0) {
			 alert("SMS 보내실 분을 선택하세요");
			 return;
		}			 
	
		//한명 선택시
		if(tar.length == 1)
		{
			$('#lbGroupSelect option').each(function() { 			
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
			$('#lbGroupSelect option').each(function() {	
										 
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
			$(parent.document).find('#txtReceiver').attr('disabled', 'disabled');
			$(parent.document).find('#username').val('');
			parent.document.forms["frmSms"].txtSender.focus();
		}		
		//window.close();
		parent.modalclose();
	}	
	
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
	
	function GroupView()
	{	
		parent.location.href = "./sms_grouplist.php";
		//window.open('sms_grouplist.php?ssa_idx=<?=$_GET[ssa_idx];?>','','left=300, top=200, width=600, height=339, menubar=no, scrollbars=no, status=no, toolbar=no');
	}		
//]]>      
</script>













