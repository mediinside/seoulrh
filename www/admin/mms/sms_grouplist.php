<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP -> CLS."/class.sms.php");	
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Sms 	= new Sms;
	$C_Button 		= new Button;
	
	//error_reporting(E_ALL);
  //@ini_set("display_errors", 1);	

	
	$args = array();
	$args['show_row'] = 10;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Sms->Group_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
	
	
	$args = '';
	$arr_group = $C_Sms->Sms_Group_list($args);
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<? include_once($GP -> INC_ADM_PATH."/inc.mem_search.php"); ?>										
			<form name="base_form" id="base_form" method="GET">
			<ul>				
				<li>
					<strong class="tit">등록일</strong>
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
				</li>			
				<li>
					<strong class="tit">검색조건</strong>
					<span>
					<select name="search_key" id="search_key">
						<option value="">:: 선택 ::</option>
						<option value="mb_name">회원명</option>
						<option value="mb_mobile">핸드폰</option>
						<option value="mb_email">이메일</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="16" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>		
		<div class="btnWrap">
			<div id="div_groupreg" style="display:none;float:left;">
				그룹명 : <input type="text" id="group_name" name="group_name"  />
				<input type="button" id="group_reg" value="저장" class="btns btnGray" />
				<input type="button" id="group_close" value="닫기" class="btns btnGray" />
			</div>
			<button  id="group_add" class="btnSearch btnRight">그룹 등록</button>
		</div>
		<div id="BoardHead" class="boxBoardHead">				
				<div class="boxMemberBoard">
					<table>
						<colgroup>
							<col>
							<col style="width:80px;">
							<col>
							<col>
							<col style="width:101px;">
						</colgroup>
						<thead>
							<tr>
								<th>No</th>								
								<th>그룹선택</th>
								<th>그룹명</th>
								<th>회원수</th>								
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
									$cnt = $data_list[$i]['cnt'];
									 $gr_code = $data_list[$i]['gr_code'];
									 $gr_name = $data_list[$i]['gr_name'];	
								?>
										<tr>
											<td><?=$data['page_info']['start_num']--?></td>											
											<td class="alignCenter"><input type="checkbox" name="chkgroup" value="<?=$gr_code?>" /></td>											
											<td id="group_<?=$gr_code?>"><?=$gr_name;?></td>
											<td><?=$cnt?>명</td>
											<td>
												<input type="button" id="modi_<?=$gr_code?>" onClick="group_modify('<?=$gr_code?>');" value="수정" /><input type="button" id="del_<?=$gr_code?>" onClick="group_del('<?=$gr_code?>');" value="삭제" />
											</td>
										</tr>
										<?
									$dummy++;
								}
								?>						
						</tbody>
					</table>
				</div>			
			</div>
			<ul class="boxBoardPaging">
				<?=$page_link?>
			</ul>
		</div>
		<? include_once($GP -> INC_ADM_PATH."/footer.php"); ?>
	</div>
</div><!-- 전체를 감싸는 Wrap //-->
</body>
</html>
<script type="text/javascript">

	$(document).ready(function(){														 
	
		callDatePick('s_date');
		callDatePick('e_date');

		$('#search_submit').click(function(){

			if($.trim($('#search_content').val()) != '')
			{
				if($('#search_key option:selected').val() == '')
				{
					alert('검색조건을 선택하세요');
					return false;
				}
			}

			if($('#search_key option:selected').val() != '')
			{
				if($.trim($('#search_content').val()) == '')
				{
					alert('검색내용을 입력하세요');
					$('#search_content').focus();
					return false;
				}
			}


			$('#base_form').submit();
			return false;
		});

	});
	
	//<[CDATA[
	$(document).ready(function() {		
		
		//그룹추가 저장 클릭시
		$('#group_reg').click(function(){
										
			var group_name = $('#group_name');
			
			if(group_name.val().replace(/\s/g, "") == '')
			{
				alert("그룹명을 입력하세요");
				group_name.focus();
				return false;
			}
			else
			{
				$.ajax({
					type: "POST",
					url: "./proc/sms_proc.php",
					data: "mode=GROUP_REG&group_name=" + encodeURIComponent(group_name.val()),
					dataType: "text",
					beforeSend: function() {
						$('#div_load').html('<p><img src="/admin/img/common/loading.gif"></p>');
					},   
					success: function(msg) {					
						$('#div_load').remove();						
						if(msg == "true")
						{							
							window.location.reload(true);
						}
						else if(msg == "false1")
						{
							alert("동일한 그룹명이 있습니다");	
						}
						else
						{
							alert("등록실패하였습니다");
							return false;
						}
					},
					error: function(xhr, status, error) { alert(error); }
				});		
			}		
		});
		
		
		//그룹추가 버튼
		$('#group_add').click(function(){
			$('#div_groupreg').css('display','block');
		});
		
		//그룹추가 닫기 버튼
		$('#group_close').click(function(){
			$('#div_groupreg').css('display','none');
		});
		
		
		//이동버튼 클릭시
		$('#img_move_group').click(function(){
			
			var chkval = "";
			var sel_val = $('#sel_group_move1 option:selected').val();		//그룹코드
			
			//선택그룹
			$("input[name=chkgroup]:checkbox:checked").each(function(){				
				chkval += $(this).val() + ",";				
			});			
			
			if($('input[name=chkgroup]:checkbox:checked').length > 0)
			{
				$.ajax({
					type: "POST",
					url: "./proc/sms_proc.php",
					data: "mode=GROUP_MOVE&chkval=" + chkval + "&group_code=" + sel_val,
					dataType: "text",
					beforeSend: function() {
						$('#div_load').html('<p><img src="/admin/img/common/loading.gif"></p>');
					},   
					success: function(msg) {					
						$('#div_load').remove();						
						if(msg == "true")
						{							
							window.location.reload(true);							
						}						
						else
						{
							alert("등록실패하였습니다");
							return false;
						}
					},
					error: function(xhr, status, error) { alert(error); }
				});			
			}
			else
			{
				alert("이동할 그룹을 선택하세요");
				return false;
			}

		});
		
	});	
	
	
	//수정 클릭시 폼변화
	function group_modify(str)
	{
		var group_name = $('#group_' + str).html();
		
		if(group_name == "기타")
		{
			alert("기타 그룹은 수정할 수 없습니다");
			return false;
		}
		
		$('#group_' + str).html("<input type=\"text\" value=" + group_name + " class=\"input170\" title=\"이름 입력\" id=\"gname_" + str + "\">");		
		$('#modi_' + str).html("<a href=\"#\" onclick=\"group_save('"+ str +"');\"><img src=\"/admin/img/sms/btn_save.gif\" /></a> ");
		$('#del_' + str).html("<a href=\"#\" onclick=\"group_cancel('"+ str +"');\"><img src=\"/admin/img/sms/btn_cancel01.gif\" /></a>");	
	}
	
	//취소 클릭시 폼변화
	function group_cancel(str)
	{
		var group_name = $('#gname_' + str).val();			

		$('#group_' + str).html(group_name);
		$('#modi_' + str).html("<a href=\"#\" onclick=\"group_modify('"+ str +"');\"><img src=\"/admin/img/sms/btn_modify01.gif\" /></a> ");
		$('#del_' + str).html("<a href=\"#\"  onclick=\"group_del('"+ str +"');\"><img src=\"/admin/img/sms/btn_del01.gif\" /></a>");			
	}
	
	
	//삭제 클릭시
	function group_del(str)
	{
		var group_name = $('#group_' + str).html();
		
		if(group_name == "기타")
		{
			alert("기타 그룹은 삭제할 수 없습니다");
			return false;
		}
		
		if(!confirm("선택한 주소록을 삭제하시겠습니까?"))
		return
		
		$.ajax({
			type: "POST",
			url: "./proc/sms_proc.php",
			data: "mode=GROUP_DEL&group_code=" + str,
			dataType: "text",
			beforeSend: function() {
				$('#div_load').html('<p><img src="/admin/img/common/loading.gif"></p>');
			},   
			success: function(msg) {					
				$('#div_load').remove();	
				if(msg == "true")
				{						
					window.location.reload(true);
				}
				else
				{
					alert("삭제에 실패하였습니다");
					return false;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
		
	}
	
	
	//수정된 내용 저장
	function group_save(str)
	{	
		var group_name =$('#gname_' + str);
			
		if(group_name.val().replace(/\s/g, "") == '')
		{
			alert("그룹명을 입력하세요");
			group_name.focus();
			return false;	
		}
		else
		{
			$.ajax({
				type: "POST",
				url: "./proc/sms_proc.php",
				data: "mode=GROUP_MODI&group_name=" + encodeURIComponent(group_name.val()) + "&group_code=" + str,
				dataType: "text",
				beforeSend: function() {
					$('#div_load').html('<p><img src="/admin/img/common/loading.gif"></p>');
				},   
				success: function(msg) {					
					$('#div_load').remove();					
					if(msg == "true")
					{
						alert("수정되었습니다");
						$('#group_' + str).html(group_name.val());
						$('#modi_' + str).html("<a href=\"#\" onclick=\"group_modify('"+ str +"');\"><img src=\"/admin/img/sms/btn_modify01.gif\" /></a> ");
						$('#del_' + str).html("<a href=\"#\"  onclick=\"group_del('"+ str +"');\"><img src=\"/admin/img/sms/btn_del01.gif\" /></a>");
					}
					else if(msg == "false1")
					{
						alert("동일한 그룹명이 있습니다");	
					}
					else
					{
						alert("수정에 실패하였습니다");
						return false;
					}
				},
				error: function(xhr, status, error) { alert(error); }
			});
		}
	}
		
		
	//]]> 
	
</script>