<?php
	include_once("../../_init.php");
	//include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP -> CLS .'/class.list.php');		
	include_once($GP -> CLS."/class.treat.php");
	$C_Treat 	= new Treat;
	$C_ListClass 	= new ListClass;

	
	$args = array();
	$args['show_row'] = 15;
	$args['pagetype'] = "go_FindTreatList";
	$args['ajax'] = "true";
	$args['page'] = $_POST['page'] == "" ? "1" : $_POST['page'];	

	$data = "";
	$data = $C_Treat->FindTreatList(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];	
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
?>
<div id="BoardHead" class="boxBoardHead">				
		<div class="boxMemberBoard_layer">
    	<input type="hidden" name="n_page" id="n_page" value="<?=$nowPage?>" />
 			<table>						
				<tbody>
					<?
						$dummy = 1;
						for($i=0; $i<$data_list_cnt; $i++) {
							$tc_idx 		= $data_list[$i]['tc_idx'];
							$tc_cate1	= $data_list[$i]['tc_cate1'];
							$tc_cate2	= $data_list[$i]['tc_cate2'];
							$tc_cate3	= $data_list[$i]['tc_cate3'];
							$tc_title	= $data_list[$i]['tc_title'];			
							
							$msg = "";
							if($tc_cate3 != '') {
								$msg = " > " . $GP->CATE3[$tc_cate2][$tc_cate3];
							}
						?>
						<tr>
							<td>
									<input type="checkbox" name="sel_treat" value="<?=$tc_idx?>|<?=$tc_title?>" />	
									<a href="#" style="display:inline-block; vertical-align:top;">
										<div style="display:inline-block;">[<?=$GP->CATE1[$tc_cate1]?> > <?=$GP->CATE2[$tc_cate1][$tc_cate2]?><?=$msg ?>]</div>
										<div style="display:inline-block; padding-left:10px;">제목 : <?=$tc_title?></div>
									</a>
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