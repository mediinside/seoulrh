<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP -> CLS."/class.member.php");	
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Member 	= new Member;
	$C_Button 		= new Button;
	
	$args = array();
	$args['show_row'] = 15;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Member->Mem_Out_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
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
						<option value="mb_name" <? if($_GET['search_key'] == "mb_name"){ echo "selected";}?> >성명</option>
						<option value="mb_email" <? if($_GET['search_key'] == "mb_email"){ echo "selected";}?>>이메일</option>
						<option value="mb_mobile" <? if($_GET['search_key'] == "mb_mobile"){ echo "selected";}?>>핸드폰</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="16" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
					<span><input type="button" id="email_del_btn" value="선택 삭제" /></span>
				</li>
			</ul>
			</form>
			</div>
		</div>		
		<div id="BoardHead" class="boxBoardHead">				
				<div class="boxMemberBoard">
					<table>
						<colgroup>
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />
							<col style="width:101px;" />
						</colgroup>
						<thead>
							<tr>
								<th><input type="checkbox" name="ck_all" id="ck_all" /></th>
								<th>No</th>								
								<th>이메일</th>								
								<th>성명</th>								
								<th>핸드폰</th>								
								<th>탈퇴유형</th>								
								<th>탈퇴일</th>								
								<th>보기/삭제</th>								
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
									$mb_code 		= $data_list[$i]['mb_code'];
									$mb_name		= $data_list[$i]['mb_name'];
									$mb_email 		= $data_list[$i]['mb_email'];
									$mb_sex 		= $data_list[$i]['mb_sex'];
									$mb_birthday	= $data_list[$i]['mb_birthday'];
									$mb_mobile 		= $data_list[$i]['mb_mobile'];	
									$mb_register_date 		= $data_list[$i]['mb_register_date'];								
									
									$edit_btn = $C_Button -> getButtonDesign('type2','보기',0,"layerPop('ifm_reg','./mem_view.php?mb_code=" . $mb_code. "', '100%', 650)", 50,'');	
									$edit_btn .= $C_Button -> getButtonDesign('type2','삭제',0,"mem_delete('" . $mb_code. "')", 50,'');
									
								?>
								<tr>
									<td><input type="checkbox" name="ck_num" value="<?=$mb_code?>" /></td>
									<td><?=$data['page_info']['start_num']--?></td>									
									<td><?=$mb_email?></td>									
									<td><?=$mb_name?></td>									
									<td><?=$mb_mobile?></td>									
									<td><?=$GP -> WITHDRAWAL_REASON[$mb_withdrawal]?></td>									
									<td><?=$mb_withdrawal_date?></td>									
									<td><?=$edit_btn?></td>
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
<form id="frm_email" name="frm_email" action="?" method="post">
	<input type="hidden" name="mode" id="mode" value="" />
	<input type="hidden" name="arr_idx" id="arr_idx" value="" />
</form>
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

		//전체선택 체크박스 클릭
		$("#ck_all").click(function(){
			//만약 전체 선택 체크박스가 체크된상태일경우
			if($("#ck_all").prop("checked")) {
				//해당화면에 전체 checkbox들을 체크해준다
				$("input:checkbox[name='ck_num']").prop("checked",true);
			// 전체선택 체크박스가 해제된 경우
			} else {
				//해당화면에 모든 checkbox들의 체크를해제시킨다.
				$("input:checkbox[name='ck_num']").prop("checked",false);
			}
		});

		$('#email_del_btn').click(function(){
			if(!confirm("선택하신 회원들을 삭제하시겠습니까?")) return;

			var num = "";
			$("input:checkbox[name=ck_num]").each(function(){
				if($(this).prop('checked') == true) {
					num += $(this).val()  + ",";
				}
			});
			num = num.slice(0,-1);

			if(num == "") {
				alert("삭제할 사람을 체크해주세요");
				return false;
			}
			
			$("#ajaxloading")
			.css("position","absolute")
			.css("z-index","10001")
			.css("top","50%")
			.css("left","50%")
			.css("margin",$("#ajaxloading").height()/2*-1+" 0 0 "+$("#ajaxloading").width()/2*-1);

			$.ajax({
				type: "POST",
				url: "./proc/humem_proc.php",
				data: "mode=HUEMAIL_USER_DEL&arr_idx=" + num,
				dataType: "text",
				beforeSend : function(){
					$("#ajaxloading").html("<img src='/admin/img/common/ajax-loader.gif'/>");
					$('.boxBoardHead').hide();
				},
				success: function(msg) {
					$("#ajaxloading").empty();
					$('.boxBoardHead').show();
					if($.trim(msg) == "true") {
						alert("처리 되었습니다");
						window.location.reload();
						return false;
					}else{
						alert('처리에 실패하였습니다.');
						return;
					}
				},
				error: function(xhr, status, error) { alert(error); }
			});
		});

	});

	function mem_delete(mb_code)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/mem_proc.php",
			data: "mode=MEM_DEL&mb_code=" + mb_code,
			dataType: "text",
			success: function(msg) {

				if($.trim(msg) == "true") {
					alert("삭제되었습니다");
					window.location.reload();
					return false;
				}else{
					alert('삭제에 실패하였습니다.');
					return;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});

	}
</script>