<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	include_once($GP -> INC_ADM_PATH."inc.adm_auth.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.admmain.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_AdminMain 	= new AdminMain;
	$C_Button 		= new Button;
	
	$args = array();
	$args['show_row'] = 10;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_AdminMain->getAdminList(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);


	$get_par = "page=" . $nowPage . "&s_date=" . $_GET['s_date'] . "&e_date=" . $_GET['e_date'] . "&search_key=" . $_GET['search_key']. "&search_content=" . $_GET['search_content'];
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
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="inputSearch" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="inputSearch" size="13" /></span>
				</li>			
				<li>
					<strong class="tit">검색조건</strong>
					<span>
					<select name="search_key" id="search_key">
						<option value="">:: 선택 ::</option>
						<option value="mb_name" <? if($_GET['search_key'] == "mb_name"){ echo "selected";}?> >성명</option>
						<option value="mb_email" <? if($_GET['search_key'] == "mb_email"){ echo "selected";}?>>이메일</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="inputSearch" size="17" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>
		<? if($_SESSION['suserlevel'] == "9") { ?>	
		<div class="btnWrap">
		<button onClick="layerPop('ifm_reg','./adm_reg.php', '100%', 520)"; class="btnSearch btnRight">관리자 등록</button>
		</div>
    <? } ?>
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
							<col />
							<col style="width:101px;" />
						</colgroup>
						<thead>
							<tr>
								<th>No</th>								
								<th>아이디</th>								
								<th>이름</th>								
								<th>이메일</th>								
								<th>연락처</th>								
								<th>등급</th>								
								<th>등록일</th>								
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
									$mb_code 		= $data_list[$i]['mb_code'];
									$mb_id 			= $data_list[$i]['mb_id'];
									$mb_name 		= $data_list[$i]['mb_name'];
									$mb_phone 	= $data_list[$i]['mb_phone'];
									$mb_email 	= $data_list[$i]['mb_email'];
									$mb_level 	= $data_list[$i]['mb_level'];
									$mb_register_date 	= $data_list[$i]['mb_register_date'];	
									
									if($_SESSION['suserlevel'] == "9") {
										$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./adm_edit.php?mb_code=" . $mb_code. "', '100%', 520)", 50,'');											
										$edit_btn .= $C_Button -> getButtonDesign('type2','삭제',0,"adm_delete('" . $mb_code. "')", 50,'');										
									}
								?>
									<tr>
										<td><?=$data['page_info']['start_num']--?></td>										
										<td><?=$mb_id?></td>										
										<td><?=$mb_name?></td>										
										<td><?=$mb_email?></td>									
										<td><?=$mb_phone?></td>										
										<td><?=$GP -> AUTH_LEVEL[$mb_level]?></td>										
										<td><?=$mb_register_date?></td>										
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

	function adm_delete(mb_code)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/main_proc.php",
			data: "mode=ADMINDEL&mb_code=" + mb_code,
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