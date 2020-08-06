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
	$data = $C_AdminMain->Admin_Auth_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);


	$get_par = "page=" . $nowPage . "&s_date=" . $_GET['s_date'] . "&e_date=" . $_GET['e_date'] . "&search_key=" . $_GET['search_key']. "&search_content=" . $_GET['search_content'];

	$args = '';
	$board_data = $C_AdminMain ->Board_List_All($args);
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
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="17" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>
		<? if($_SESSION['suserlevel'] == "9") { ?>	
		<div class="btnWrap">
		<button onClick="layerPop('ifm_reg','./adm_auth_reg.php', '100%', 500)"; class="btnSearch btnRight">권한 등록</button>
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
							<col style="width:101px;" />
						</colgroup>
						<thead>
							<tr>
								<th>No</th>
								<th>레벨</th>
								<th>접근가능폴더</th>
								<th>접근가능게시판</th>	
								<th>등록일</th>
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<?
								$arr_tmp1 = array();								
								$menuQuery = "";
								foreach($GP -> MENU_ADMIN as $key => $val) {
									$menuQuery = $val['folder'];
									foreach ($GP -> MENU_SUB_ADMIN[$menuQuery] as $key1 => $val1) {
										foreach ($val1 as $key2 => $val2) {
											$arr_tmp1[$val2['title']] = $val2['name'];
										}
									}
								}

								for($k=0; $k<count($board_data); $k++) {
									$jba_title = $board_data[$k]['jba_title'];
									$jb_code = $board_data[$k]['jb_code'];

									$arr_tmp3[$jb_code] = $jba_title;
								}
							
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
									$tl_idx 			= $data_list[$i]['tl_idx'];									
									$tl_level 			= $data_list[$i]['tl_level'];
									$tl_folder 		= $data_list[$i]['tl_folder'];
									$tl_folder_sub 	= $data_list[$i]['tl_folder_sub'];
									$tl_bbs	 		= $data_list[$i]['tl_bbs'];
									$tl_regdate 		= $data_list[$i]['tl_regdate'];	
									
									$arr_tmp = explode(',',$tl_folder);
									$arr_tmp4 = explode(',',$tl_folder_sub);
									$arr_tmp2 = explode(',',$tl_bbs);
									
									
									if($_SESSION['suserlevel'] == "9") {
										$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./adm_auth_edit.php?tl_idx=" . $tl_idx. "', '100%', 520)", 50,'');											
										$edit_btn .= $C_Button -> getButtonDesign('type2','삭제',0,"adm_auth_delete('" . $tl_idx. "')", 50,'');										
									}
								?>
									<tr>
										<td><?=$data['page_info']['start_num']--?></td>																				
										<td><?=$GP -> AUTH_LEVEL[$tl_level]?></td>										
										<td>
											<?
											foreach($arr_tmp4 as $key => $val) {
												echo "<p style='margin:2px;'>" .  $arr_tmp1[$val]  ."</p>";
											}
											?>
										</td>
										<td>
											<?
											foreach($arr_tmp2 as $key => $val) {
												echo "<p style='margin:2px;'>" .  $arr_tmp3[$val]  ."</p>";
											}
											?>
										</td>
										<td><?=$tl_regdate?></td>										
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

	function adm_auth_delete(tl_idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/main_proc.php",
			data: "mode=ADMINAUTHDEL&tl_idx=" + tl_idx,
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