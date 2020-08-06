<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.reserve.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Reserve 	= new Reserve;
	$C_Button 		= new Button;
	
	$excelHanArr = array(
		"이름" => "mb_name",
		"타입" => "mb_type",
		"진료과" => "dr_clinic",
		"의료진" => "dr_name",
		"예약일자" => "rd_date",
		"예약시간" => "rd_s_time",
		"상태" => "rd_status",
		"등록일자" => "rd_regdate"
	);

	$args = array();
	$args['show_row'] = 10;
	$args['pagetype'] = "admin";
	$args['excel_file']		= $_GET['excel_file'];
	$args['excel']			= $excelHanArr;
	$data = "";
	$data = $C_Reserve->Reserve_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);


	$cn_select = $C_Func -> makeSelect_Normal('dr_clinic', $GP -> CLINIC_TYPE, $_GET['dr_clinic'], ' style="width:200px;" ', '::선택::');	
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
        <form name="base_form" id="base_form" method="GET">
        <ul>				
          <li>
            <strong class="tit">예약일자</strong>
            <span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
            <span>~</span>
            <span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
          </li>	
		<li>
            <strong class="tit">등록일</strong>
            <span><input type="text" name="s_date2" id="s_date2" value="<?=$_GET['s_date2']?>" class="input_text" size="13"></span>
            <span>~</span>
            <span><input type="text" name="e_date2" id="e_date2" value="<?=$_GET['e_date2']?>" class="input_text" size="13" /></span>
          </li>	          	
          <li>
            <strong class="tit">진료과</strong>
            <span>
            	<?=$cn_select?>
            </span>
          </li>			
          <li>
            <strong class="tit">검색조건</strong>
            <span>
            <select name="search_key" id="search_key">
              <option value="">:: 선택 ::</option>
              <option value="mb_name" <? if($_GET['search_key'] == "mb_name"){ echo "selected";}?> >성명</option>
              <!-- <option value="mb_id" <? if($_GET['search_key'] == "mb_id"){ echo "selected";}?>>아이디</option> -->
              <option value="dr_name" <? if($_GET['search_key'] == "dr_name"){ echo "selected";}?>>의료진</option>
            </select>
            </span>
            <span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="17" /></span>
            <span><button id="search_submit" class="btnSearch ">검색</button></span>
            <span><input type="button" id="excel_btn" value="EXCEL" /></span>
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
							<!-- <col /> -->
							<col />
							<!-- <col /> -->
							<col />
							<col />
							<col />
							<!-- <col /> -->
							<col />
							<col />
							<col style="width:150px;"/>
						</colgroup>
						<thead>
							<tr>
								<th>No</th>
								<!-- <th>아이디</th> -->
								<th>성명</th>
								<!-- <th>환자명</th> -->
								<th>타입</th>
								<th>진료과</th>
								<th>의료진</th>
								<th>예약일자</th>
								<th>예약시간</th>
								<!-- <th>구분</th> -->
								<th>상태</th>
								<th>등록일자</th>
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
									$rd_idx 			= $data_list[$i]['rd_idx'];
									$cp_idx 			= $data_list[$i]['cp_idx'];
									$mb_code 			= $data_list[$i]['mb_code'];
									$mb_id				= $data_list[$i]['mb_id'];
									$mb_name			= $data_list[$i]['mb_name'];
									$mb_name2			= $data_list[$i]['mb_name2'];
									$mb_mobile		= $data_list[$i]['mb_mobile'];
									$mb_email			= $data_list[$i]['mb_email'];
									$mb_type			= $data_list[$i]['mb_type'];
									$mb_address1	= $data_list[$i]['mb_address1'];
									$mb_address2	= $data_list[$i]['mb_address2'];
									$dr_idx				= $data_list[$i]['dr_idx'];
									$dr_name			= $data_list[$i]['dr_name'];
									$dr_center		= $data_list[$i]['dr_center'];
									$dr_clinic		= $data_list[$i]['dr_clinic'];
									$rd_date			= $data_list[$i]['rd_date'];
									$rd_s_time		= $data_list[$i]['rd_s_time'];
									$rd_e_time		= $data_list[$i]['rd_e_time'];
									$rd_type			= $data_list[$i]['rd_type'];
									$rd_status		= $data_list[$i]['rd_status'];									
									$rd_sms				= $data_list[$i]['rd_sms'];									
									$rd_regdate		= $data_list[$i]['rd_regdate'];
									
									$send_btn = "";
									if($rd_status == "Y" && $rd_sms != 'Y') {
										$send_btn = $C_Button -> getButtonDesign('type2','문자발송',0,"send_sms('" . $rd_idx ."', '" . $mb_name. "')", 100, "title='확정문자 발송' ");	 
										$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./reserve_edit.php?rd_idx=" . $rd_idx. "','100%', 650)", 50,'');	
										$edit_btn .= "" . $C_Button -> getButtonDesign('type2','삭제',0,"reserve_delete('" . $rd_idx. "')", 50,'');							
									}
									
									if($rd_status == "Y" && $rd_sms == 'Y') {
										$send_btn = $C_Button -> getButtonDesign('type2','발송완료',0,"", 50,"title='확정문자 발송완료' ");	 										
										$edit_btn = "" . $C_Button -> getButtonDesign('type2','삭제',0,"reserve_delete('" . $rd_idx. "')", 50,'');							
									}

									if(($rd_status == "N" || $rd_status == "M" || $rd_status == "P" || $rd_status == "C")  && $rd_sms == 'N') {
										$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./reserve_edit.php?rd_idx=" . $rd_idx. "','100%', 650)", 50,'');	
										$edit_btn .= "" . $C_Button -> getButtonDesign('type2','삭제',0,"reserve_delete('" . $rd_idx. "')", 50,'');		
									}

								?>
										<tr>
											<td><?=$data['page_info']['start_num']--?></td>
											<!-- <td><?=$mb_id?></td> -->
											<td><?=$mb_name?></td>
											<!-- <td><?=$mb_name2?></td> -->
											<td><?=$GP->RESERVE_CL_TYPE[$mb_type]?></td>
											<td><?=$GP -> CLINIC_TYPE[$dr_clinic]?></td>
											<td><?=$dr_name;?></td>
											<td><?=$rd_date;?></td>
											<td><?=$rd_s_time;?></td>
											<!-- <td><?=$rd_type?></td> -->
											<td><?=$GP -> RESERVE_RESULT[$rd_status]?></td>
											<td><?=$rd_regdate;?></td>
											<td class="alignCenter"><?=$send_btn?> <?=$edit_btn?></td>
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
		//엑셀 출력
		$('#excel_btn').click(function(){
				var string = $("#base_form").serialize();
				location.href = "?excel_file=mem_list" + "&" + string;
				return false;
		});
		
		callDatePick('s_date');
		callDatePick('e_date');
		callDatePick('s_date2');
		callDatePick('e_date2');

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

	
	function send_sms(rd_idx, name) {
		if(!confirm( name + " 고객님에게 확정문자를 보내시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/reserve_proc.php",
			data: "mode=RESERVE_SMS&rd_idx=" + rd_idx,
			dataType: "text",
			success: function(msg) {
				
				if($.trim(msg) == "true") {
					alert("발송 되었습니다");
					window.location.reload();
					return false;
				}else{
					alert('발송에 실패하였습니다.');
					return;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
	}


	function reserve_delete(rd_idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/reserve_proc.php",
			data: "mode=RESERVE_DEL&rd_idx=" + rd_idx,
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