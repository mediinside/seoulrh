<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.reserve.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Reserve 	= new Reserve;
	$C_Button 		= new Button;
	
	$args = array();
	$args['show_row'] = 10;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Reserve->Doctor_Schdule_Setup_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
	
	
	$cn_select = $C_Func -> makeSelect_Normal('dr_center', $GP -> CENTER_TYPE, $dr_center, ' style="width:200px;" ', '::선택::');	
	$cn_select1 = $C_Func -> makeSelect_Normal('dr_clinic', $GP -> CLINIC_TYPE, $dr_clinic, ' style="width:200px;" ', '::선택::');	
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<form name="base_form" id="base_form" method="GET">
			<ul>				
				<li>
					<strong class="tit">등록일</strong>
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
				</li>							
        <li>
					<strong class="tit">진료센터</strong>
					<span>
						<?=$cn_select?>
					</span>
				</li>				
				<li>
					<strong class="tit">진료과목</strong>
					<span>
						<?=$cn_select1?>
					</span>
				</li>			       
				<li>
					<strong class="tit">검색조건</strong>
					<span>
					<select name="search_key" id="search_key">
						<option value="">:: 선택 ::</option>
						<option value="D.dr_name" <? if($_GET['search_key'] == "dr_name"){ echo "selected";}?> >의료진명</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="17" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>
    	
			<div class="btnWrap">				
				<p class="txtLeft">일정중 예약 내역이 있는경우 전체 삭제가 불가능하니 일일 일정을 통해 삭제해주세요</p>
				<div class="btnRight">
					<input type="button" value="의료진 일정 등록" class="btnM btnIdt" onClick="layerPop('ifm_reg','./m_sch_reg.php', '100%', 700)"; />
				</div>
			</div>
      
			<div class="boxMemberBoard">
				<table>
					<colgroup>
						<col />
						<col />
						<col />
						<col />
						<col />
						<col style="width:150px;" />
					</colgroup>
					<thead>
						<tr>
							<th scope="col">No</th>
							<th scope="col">의료진</th>
							<th scope="col">요일 / 간격 / 업무시간 / 제외시간</th>
							<th scope="col">적용기간</th>
							<th scope="col">등록일</th>
							<th scope="col">관리</th>
						</tr>
					</thead>
					<tbody>
          	<?
						$dummy = 1;
						if($data_list_cnt > 0) {
							for ($i = 0 ; $i < $data_list_cnt ; $i++) {
								$so_idx 	= $data_list[$i]['so_idx'];							
								$dr_idx 	= $data_list[$i]['dr_idx'];							
								$dr_name 	= $data_list[$i]['dr_name'];							
								$dr_center 	= $data_list[$i]['dr_center'];							
								$dr_clinic 	= $data_list[$i]['dr_clinic'];															

								$so_rsinterval_sun	= $data_list[$i]['so_rsinterval_sun'];
								$so_work_stime_sun	= $data_list[$i]['so_work_stime_sun'];
								$so_work_etime_sun	= $data_list[$i]['so_work_etime_sun'];
								$so_ext_stime_sun	= $data_list[$i]['so_ext_stime_sun'];
								$so_ext_etime_sun	= $data_list[$i]['so_ext_etime_sun'];

								$so_rsinterval_mon	= $data_list[$i]['so_rsinterval_mon'];
								$so_work_stime_mon	= $data_list[$i]['so_work_stime_mon'];
								$so_work_etime_mon	= $data_list[$i]['so_work_etime_mon'];
								$so_ext_stime_mon	= $data_list[$i]['so_ext_stime_mon'];
								$so_ext_etime_mon	= $data_list[$i]['so_ext_etime_mon'];

								$so_rsinterval_tue	= $data_list[$i]['so_rsinterval_tue'];
								$so_work_stime_tue	= $data_list[$i]['so_work_stime_tue'];
								$so_work_etime_tue	= $data_list[$i]['so_work_etime_tue'];
								$so_ext_stime_tue	= $data_list[$i]['so_ext_stime_tue'];
								$so_ext_etime_tue	= $data_list[$i]['so_ext_etime_tue'];
								
								$so_rsinterval_wed	= $data_list[$i]['so_rsinterval_wed'];
								$so_work_stime_wed	= $data_list[$i]['so_work_stime_wed'];
								$so_work_etime_wed	= $data_list[$i]['so_work_etime_wed'];
								$so_ext_stime_wed	= $data_list[$i]['so_ext_stime_wed'];
								$so_ext_etime_wed	= $data_list[$i]['so_ext_etime_wed'];
																
								$so_rsinterval_thu	= $data_list[$i]['so_rsinterval_thu'];
								$so_work_stime_thu	= $data_list[$i]['so_work_stime_thu'];
								$so_work_etime_thu	= $data_list[$i]['so_work_etime_thu'];
								$so_ext_stime_thu	= $data_list[$i]['so_ext_stime_thu'];
								$so_ext_etime_thu	= $data_list[$i]['so_ext_etime_thu'];
																
								$so_rsinterval_fri	= $data_list[$i]['so_rsinterval_fri'];
								$so_work_stime_fri	= $data_list[$i]['so_work_stime_fri'];
								$so_work_etime_fri	= $data_list[$i]['so_work_etime_fri'];
								$so_ext_stime_fri	= $data_list[$i]['so_ext_stime_fri'];
								$so_ext_etime_fri	= $data_list[$i]['so_ext_etime_fri'];
																
								$so_rsinterval_sat	= $data_list[$i]['so_rsinterval_sat'];
								$so_work_stime_sat	= $data_list[$i]['so_work_stime_sat'];
								$so_work_etime_sat	= $data_list[$i]['so_work_etime_sat'];
								$so_ext_stime_sat	= $data_list[$i]['so_ext_stime_sat'];
								$so_ext_etime_sat	= $data_list[$i]['so_ext_etime_sat'];
								
								$so_week_holiday = $data_list[$i]['so_week_holiday'];
								$so_apply_sdate	= $data_list[$i]['so_apply_sdate'];
								$so_apply_edate	= $data_list[$i]['so_apply_edate'];
								$so_regdate	= date("Y.m.d", strtotime($data_list[$i]['so_regdate']));
								
								$args = '';
								$args['s_date'] = $so_apply_sdate;
								$args['e_date'] = $so_apply_edate;
								$args['so_idx'] = $so_idx;
								$rst = $C_Reserve->CHECK_CAPPA($args);

								$rst1 = $C_Reserve->CHECK_CAPPA_RESERVE($args);									
								
								if($rst['cnt'] == 0) {
									$opt_btn  =  $C_Button -> getButtonDesign('type2','적용',0,"m_sch_set('" . $so_idx. "')", 50,'');					
								}else {
									$opt_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./m_sch_edit.php?so_idx=" . $so_idx. "','100%', 650)", 50,'');
									// $opt_btn  =  $C_Button -> getButtonDesign('type2', '해제',0,"m_sch_cancel('" . $so_idx. "')", 50,'');	
									/*if($rst1['cnt'] == 0) {
										$opt_btn  =  $C_Button -> getButtonDesign('type2', '해제',0,"m_sch_cancel('" . $so_idx. "')", 50,'');	
									}else{
										$opt_btn = "<span>해제불가</span>";
									}*/
								}
								
								$edit_btn = $C_Button -> getButtonDesign('type2','삭제',0,"m_sch_delete('" . $so_idx. "')", 50,'');	
								/*if($rst1['cnt'] == 0) {
									$edit_btn = $C_Button -> getButtonDesign('type2','삭제',0,"m_sch_delete('" . $so_idx. "')", 50,'');	
								}else{
									$edit_btn = "<span>삭제불가</span>";
								}*/
								/*if($rst1['cnt'] == 0) {
									$edit_btn = $C_Button -> getButtonDesign('type2','삭제',0,"m_sch_delete('" . $so_idx. "')", 50,'');							
								}else{
									$edit_btn = "<span>삭제불가</span>";
								}*/
						?>
						<tr>
							<td><?=$data['page_info']['start_num']--?></td>
							<td><?=$dr_name?></td>
							<td>
							<?
							if($so_rsinterval_sun != 0) {									
								echo "일 / " . $GP->RESERVE_INTERVAL_TYPE[$so_rsinterval_sun] . " / " . $so_work_stime_sun ." ~ ".$so_work_etime_sun ." / " . $so_ext_stime_sun ." ~ ".$so_ext_etime_sun ."<br />";
							}
							
							if($so_rsinterval_mon != 0) {									
								echo "월 / " . $GP->RESERVE_INTERVAL_TYPE[$so_rsinterval_mon] . " / " . $so_work_stime_mon ." ~ ".$so_work_etime_mon ." / " . $so_ext_stime_mon ." ~ ".$so_ext_etime_mon ."<br />";
							}
							
							if($so_rsinterval_tue != 0) {									
								echo "화 / " . $GP->RESERVE_INTERVAL_TYPE[$so_rsinterval_tue] . " / " . $so_work_stime_tue ." ~ ".$so_work_etime_tue ." / " . $so_ext_stime_tue ." ~ ".$so_ext_etime_tue ."<br />";
							}
							
							if($so_rsinterval_wed != 0) {									
								echo "수 / " . $GP->RESERVE_INTERVAL_TYPE[$so_rsinterval_wed] . " / " . $so_work_stime_wed ." ~ ".$so_work_etime_wed ." / " . $so_ext_stime_wed ." ~ ".$so_ext_etime_wed ."<br />";
							}
							
							if($so_rsinterval_thu != 0) {									
								echo "목 / " . $GP->RESERVE_INTERVAL_TYPE[$so_rsinterval_thu] . " / " . $so_work_stime_thu ." ~ ".$so_work_etime_thu ." / " . $so_ext_stime_thu ." ~ ".$so_ext_etime_thu ."<br />";
							}
							
							if($so_rsinterval_fri != 0) {									
								echo "금 / " . $GP->RESERVE_INTERVAL_TYPE[$so_rsinterval_fri] . " / " . $so_work_stime_fri ." ~ ".$so_work_etime_fri ." / " . $so_ext_stime_fri ." ~ ".$so_ext_etime_fri ."<br />";
							}
							
							if($so_rsinterval_sat != 0) {									
								echo "토 / " . $GP->RESERVE_INTERVAL_TYPE[$so_rsinterval_sat] . " / " . $so_work_stime_sat ." ~ ".$so_work_etime_sat ." / " . $so_ext_stime_sat ." ~ ".$so_ext_etime_sat ."<br />";
							}
              ?>								
							</td>
							<td><?=$so_apply_sdate?>~<?=$so_apply_edate?></td>
							<td><?=$so_regdate?></td>
							<td class="alignCenter">
              	<?=$opt_btn?> <?=$edit_btn?>								
							</td>
						</tr>
            <?							
							} 
						}else{
								echo "<tr><td colspan='6' align='center'>등록된 데이터가 없습니다.</td></tr>";
						}
						?>						
					</tbody>
				</table>
			</div>
			<div class="btnWrap">				
				<ul class="boxBoardPaging">
					<?=$page_link?>
				</ul>
			</div>
		</div>
		
		<? include_once($GP -> INC_ADM_PATH."/footer.php"); ?>
	</div>
</div><!-- 전체를 감싸는 Wrap //-->
</body>
<form name="test_frm" id="test_frm" method="post">
	<input type="hidden" name="mode" value="SCH_SET" />
  <input type="hidden" name="so_idx" id="so_idx" />
</form>
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
	
	
	function m_sch_set(so_idx) {
		
		//$('#so_idx').val(so_idx);
		//$('#test_frm').attr('action','./proc/reserve_proc.php');
		//$('#test_frm').submit();
		//return false;
		
		
		
		if(!confirm("적용하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/reserve_proc.php",
			data: "mode=SCH_SET&so_idx=" + so_idx,
			dataType: "text",
			success: function(msg) {

				if($.trim(msg) == "true") {
					alert("적용되었습니다");
					window.location.reload();
					return false;
				}else{
					alert('적용에 실패하였습니다.');
					return false;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
		return false;
	}
	
	
	function m_sch_cancel(so_idx)
	{
		if(!confirm("적용해제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/reserve_proc.php",
			data: "mode=SCH_CANCEL&so_idx=" + so_idx,
			dataType: "text",
			success: function(msg) {

				if($.trim(msg) == "true") {
					alert("적용해제되었습니다");
					window.location.reload();
					return false;
				}else{
					alert('적용해제에 실패하였습니다.');
					return false;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
		return false;

	}

	function m_sch_delete(so_idx)
	{
		if(!confirm("적용된 모든 일정이 삭제 됩니다. 삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/reserve_proc.php",
			data: "mode=SCH_DEL&so_idx=" + so_idx,
			dataType: "text",
			success: function(msg) {

				if($.trim(msg) == "true") {
					alert("삭제되었습니다");
					window.location.reload();
					return false;
				}else{
					alert('삭제에 실패하였습니다.');
					return false;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
		return false;

	}
</script>