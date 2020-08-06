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
	$data = $C_Sms->Mem_List(array_merge($_GET,$_POST,$args));
	
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
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="16" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>		
		
		<div id="BoardHead" class="boxBoardHead">				
				<div class="boxMemberBoard">
					<table>
						<colgroup>
							<col>
							<col style="width:50px;">
							<col>
							<col>
							<col>
							<col style="width:101px;">
						</colgroup>
						<thead>
							<tr>
								<th>No</th>								
								<th>선택</th>								
								<th>그룹</th>								
								<th>이름</th>
								<th>핸드폰</th>								
								<th>변경/삭제</th>
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
										$gr_code = $data_list[$i]['mb_gr_code'];
										$gr_name = $data_list[$i]['gr_name'];										
										$mb_mobile = $data_list[$i]['mb_mobile'];										
										$mb_code = $data_list[$i]['mb_code'];	
										$mb_name = $data_list[$i]['mb_name'];								
								?>
										<tr>
											<td><?=$data['page_info']['start_num']--?></td>											
											<td class="alignCenter"><input type="checkbox" name="chkmem" value="<?=$mb_code?>" /></td>
											<td><?=$gr_name?></td>
											<td id="name_<?=$mb_code?>"><?=$mb_name?></td>
											<td id="phone_<?=$mb_code?>"><?=$mb_mobile;?></td>
											<td>
												<input type="button" id="mem_modi_<?=$mb_code?>" onClick="mem_modify('<?=$mb_code?>');" value="수정" /><input type="button" id="mem_del_<?=$mb_code?>" onClick="mem_del('<?=$mb_code?>');" value="삭제" />
										</tr>
										<?
									$dummy++;
								}
								?>						
						</tbody>
						<tfoot>
								<tr>
									<td colspan="11" align="right"><?
												?>
										선택한 그룹의 모든 회원을
										<select id="sel_group_move" name="sel_group_move">
											<?
												for($k=0; $k<count($arr_group); $k++){
														$gr_code = $arr_group[$k]['gr_code'];
														$gr_name = $arr_group[$k]['gr_name'];
													echo "<option value='$gr_code'>$gr_name</option>";	
												}
											?>
										</select>
										그룹으로 이동
										<input type="button" id="img_move" class="btns btnGray" value="이동" />
								</tr>
							</tfoot>
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
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.alphanumeric.js"></script>
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
							
		//핸드폰번호 체크
		$('#addr_mobile').numeric({allow:"-"});  //숫자와 하이픈
		
		
		//이동버튼 클릭시
		$('#img_move').click(function(){
			

			var chkval = "";
			var sel_val = $('#sel_group_move option:selected').val();		//그룹코드
			
			//선택회원
			$('input[name=chkmem]:checkbox:checked').each(function(){				
				chkval += $(this).val() + ",";				
			});			
			
			if($('input[name=chkmem]:checkbox:checked').length > 0)
			{
				$.ajax({
					type: "POST",
					url: "./proc/sms_proc.php",
					data: "mode=MEM_MOVE&chkval=" + chkval + "&group_code=" + sel_val,
					dataType: "text",
					beforeSend: function() {
						$('#div_load1').html('<p><img src="/admin/img/common/loading.gif"></p>');
					},   
					success: function(msg) {					
						$('#div_load1').remove();						
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
				alert("그룹이동할 회원을 선택하세요");
				return false;
			}

		});
	});
	
	//회원 수정 클릭시 폼변화
	function mem_modify(str)
	{	    
		var name = $('#name_' + str).html();	
		var phone = $('#phone_' + str).html();	
		
		$('#name_' + str).html("<input type=\"text\" value=" + name + " class=\"input75\" title=\"이름 입력\" id=\"mem_name_" + str + "\">");	
		$('#phone_' + str).html("<input type=\"text\" value=" + phone + " class=\"input75\" title=\"핸드폰 입력\" id=\"mem_phone_" + str + "\">");	

		$('#mem_modi_' + str).html("<a href=\"#\" onclick=\"mem_save('"+ str +"');\"><img src=\"/admin/img/sms/btn_save.gif\" /></a> ");
		$('#mem_del_' + str).html("<a href=\"#\" onclick=\"mem_cancel('"+ str +"');\"><img src=\"/admin/img/sms/btn_cancel01.gif\" /></a>");	
	}
	
	//취소 클릭시 폼변화
	function mem_cancel(str)
	{
		var name = $('#mem_name_' + str).val();	
		var phone = $('#mem_phone_' + str).val();	

		$('#name_' + str).html(name);
		$('#phone_' + str).html(phone);

		$('#mem_modi_' + str).html("<a href=\"#\" onclick=\"mem_modify('"+ str +"');\"><img src=\"/admin/img/sms/btn_modify01.gif\" /></a> ");
		$('#mem_del_' + str).html("<a href=\"#\"  onclick=\"mem_del('"+ str +"');\"><img src=\"/admin/img/sms/btn_del01.gif\" /></a>");			
	}
	
	//삭제 클릭시
	function mem_del(str)
	{
		if(!confirm("선택한 회원을 삭제하시겠습니까?"))
			return
		
		$.ajax({
			type: "POST",
			url: "./proc/sms_proc.php",
			data: "mode=MEM_DEL&mb_code=" + str,
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
	function mem_save(str)
	{			
		var name = $('#mem_name_' + str);
		var mobile = $('#mem_phone_' + str);
			
		if(name.val().replace(/\s/g, "") == '') 
		{
			alert("이름을 입력하세요");
			name.focus();
			return false;
		}
		else if(mobile.val().replace(/\s/g, "") == '') 
		{
			alert("핸드폰 번호를 입력하세요");
			mobile.focus();
			return false;
		}		
		else
		{
			$.ajax({
				type: "POST",
				url: "./proc/sms_proc.php",
				data: "mode=MEM_MODI&mb_name=" + encodeURIComponent(name.val()) + "&mb_mobile=" + mobile.val() + "&mb_code=" + str,
				dataType: "text",
				beforeSend: function() {
					$('#div_load').html('<p><img src="/admin/img/common/loading.gif"></p>');
				},   
				success: function(msg) {					
					$('#div_load').remove();											
					if(msg == "true")
					{
						alert("수정되었습니다");
						$('#name_' + str).html(name.val());
						$('#phone_' + str).html(mobile.val());
						
						$('#mem_modi_' + str).html("<a href=\"#\" onclick=\"mem_modify('"+ str +"');\"><img src=\"/admin/img/sms/btn_modify01.gif\" /></a> ");
						$('#mem_del_' + str).html("<a href=\"#\"  onclick=\"mem_del('"+ str +"');\"><img src=\"/admin/img/sms/btn_del01.gif\" /></a>");		
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