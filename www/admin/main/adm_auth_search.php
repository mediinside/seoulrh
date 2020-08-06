<?php	
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	include_once($GP -> INC_ADM_PATH."inc.adm_auth.php");
	include_once $GP -> INC_ADM_PATH.'menu.php';

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


	$args = '';
	$board_data = $C_AdminMain ->Board_List_All($args);
?>
<script type="text/javascript">
	
	$(document).ready(function(){
														 
		//엔터키 막기
		$("*").keypress(function(e){
			if(e.keyCode==13) 
			{
				$('#img_search').click();
				return false;
			}
			else
			{
				return true;	
			}
		});		
		
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});			


		
		$('#img_submit').click(function(){			
			var tmp_idx = $("input:radio[name='tl_idx']:checked").val();
			
			if(tmp_idx == undefined) {
				alert("권한을 선택하세요");
				return false;
			}

			var fl_txt = $('#fl_' + tmp_idx).html();
			var bbs_txt = $('#bbs_' + tmp_idx).html();

			$(parent.document).find('#tl_idx').val('');
			$(parent.document).find('#fl_txt').html('');
			$(parent.document).find('#bbs_txt').html('');

			$(parent.document).find('#tl_idx').val(tmp_idx);
			$(parent.document).find('#fl_txt').html(fl_txt);
			$(parent.document).find('#bbs_txt').html(bbs_txt);
			parent.modalclose();					
		});
	});	
</script>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer boxSearchMember">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>접근권한 찾기</strong></span>
		</div>
		<form id="frm_find" name="frm_find" method="post">
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">			

					<div class="boxMemberBoard">
						<table>
							<colgroup>
								<col />
								<col />
								<col />
								<col />
								<col />
							</colgroup>
							<thead>
								<tr>
									<th>선택</th>
									<th>레벨</th>
									<th>접근가능폴더</th>
									<th>접근가능게시판</th>	
									<th>등록일</th>
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
									?>
										<tr>
											<td><input type='radio' name='tl_idx' value='<?=$tl_idx?>' /> </td>
											<td><?=$GP -> AUTH_LEVEL[$tl_level]?></td>										
											<td id='fl_<?=$tl_idx?>'>
												<?
												foreach($arr_tmp4 as $key => $val) {
													echo "<p style='margin:2px;'>" .  $arr_tmp1[$val]  ."</p>";
												}
												?>
											</td>
											<td id='bbs_<?=$tl_idx?>'>
												<?
												foreach($arr_tmp2 as $key => $val) {
													echo "<p style='margin:2px;'>" .  $arr_tmp3[$val]  ."</p>";
												}
												?>
											</td>
											<td><?=$tl_regdate?></td>
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
		
											
				<div class="btnWrap">
				<button id="img_submit" class="btnSearch ">확인</button>
				<button id="img_cancel" class="btnSearch ">취소</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
</body>
</html>