<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.admmain.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_AdminMain 	= new AdminMain;
	$C_Button 		= new Button;
	
	$args = array();
	$args['show_row'] = 20;
	$args['pagetype'] = "admin";
	$args['jb_code_in'] = $_SESSION['adminbbs'];
	print_r($args);
	$data = "";
	$data = $C_AdminMain->getBoardList(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);

	//print_r($data_list);


	$get_par = "page=" . $nowPage . "&s_date=" . $_GET['s_date'] . "&e_date=" . $_GET['e_date'] . "&search_key=" . $_GET['search_key']. "&search_content=" . $_GET['search_content'];
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<? include_once($GP -> INC_ADM_PATH."/inc.mem_search.php"); ?>													
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
							<col />
							<col />
							<col />
							<col />
							<col style="width:101px;" />
						</colgroup>
						<thead>
							<tr>
								<th>No</th>								
								<th>구분</th>								
								<th>읽기</th>								
								<th>쓰기</th>								
								<th>답글</th>								
								<th>코멘트</th>								
								<th>코멘트사용유무</th>								
								<th>상세하단리스트</th>								
								<th>한페이지수</th>								
								<th>스킨</th>								
								<th>관리</th>
							</tr>
						</thead>
						<tbody>
							<?
							$dummy = 1;
							for ($i = 0 ; $i < $data_list_cnt ; $i++) {
								$jba_title 				= $data_list[$i]['jba_title'];
								$jba_read_level 	= $data_list[$i]['jba_read_level'];
								$jba_write_level 	= $data_list[$i]['jba_write_level'];
								$jba_skin_dir 		= $data_list[$i]['jba_skin_dir'];
								$jba_reply_level  = $data_list[$i]['jba_reply_level'];
								$jba_list_use			= $data_list[$i]['jba_list_use'];
								$jba_comment_level  = $data_list[$i]['jba_comment_level'];
								$jba_comment_use  = $data_list[$i]['jba_comment_use'];
								$jba_list_scale 	= $data_list[$i]['jba_list_scale'];
								$jb_code 					= $data_list[$i]['jb_code'];
								
								$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./bbs_edit.php?jb_code=" . $jb_code. "', '100%', 650)", 50,'');						
								$edit_btn .= $C_Button -> getButtonDesign('type2','관리',0,"javascript:BoardAdminWin(" . $jb_code .")", 50,'');									
							?>
									<tr>
										<td><?=$data['page_info']['start_num']--?></td>										
										<td><?=$jba_title?></td>										
										<td><?=$GP -> BOARD_CONFIG_LEVEL[$jba_read_level]?></td>										
										<td><?=$GP -> BOARD_CONFIG_LEVEL[$jba_write_level]?></td>										
										<td><?=$GP -> BOARD_CONFIG_LEVEL[$jba_reply_level]?></td>										
										<td><?=$GP -> BOARD_CONFIG_LEVEL[$jba_comment_level]?></td>										
										<td><? if($jba_comment_use == 'Y'){ echo "사용";}else{echo "미사용";}?></td>										   
										<td><? if($jba_list_use == 'Y'){ echo "보임";}else{echo "안보임";}?></td>  										 
										<td><?=$jba_list_scale?></td>   										
										<td><?=$jba_skin_dir?></td>  										        
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
function BoardAdminWin(jb_code)
{
	var url="/bbs/index.php?jb_code=" + jb_code;
	window.open (url,'board_admin_win' ,'scrollbars=yes, top=0, left=0, width=800, height=900')
}
</script>