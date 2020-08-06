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
	$data = $C_Reserve->Reserve_List_Old(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);

	
	$args = '';
	$arr_center = $C_Reserve->Center_List_Old($args);
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
            <strong class="tit">예약일자</strong>
            <span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
            <span>~</span>
            <span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
          </li>			          	
          <li>
            <strong class="tit">구분</strong>
            <span>
            	<select name="Area" id="Area">
              	<option value="">:::선택:::</option>
                <option value="수원" <? if($_GET['Area'] == "수원"){echo "selected";}?>>수원</option>
                <option value="안양" <? if($_GET['Area'] == "안양"){echo "selected";}?>>안양</option>
              </select>
              
            	<select name="idx_of_Group" id="idx_of_Group">
              	<option value="">:::선택:::</option>                
              </select>
              
              <select name="idx_of_Doctor" id="idx_of_Doctor">
              	<option value="">:::선택:::</option>                
              </select>
            </span>
          </li>		
          <li>
            <strong class="tit">상태</strong>
            <span>
            	<select name="Reservation_Status" id="Reservation_Status">
              	<option value="">:::선택:::</option>
                <option value="1" <? if($_GET['Reservation_Status'] == "1"){echo "selected";}?>>예약대기</option>
                <option value="2" <? if($_GET['Reservation_Status'] == "2"){echo "selected";}?>>예약완료</option>
              </select>
            </span>
          </li>			
          <li>
            <strong class="tit">검색조건</strong>
            <span>
            <select name="search_key" id="search_key">
              <option value="">:: 선택 ::</option>
              <option value="mb_name" <? if($_GET['search_key'] == "mb_name"){ echo "selected";}?> >성명</option>
              <option value="mb_id" <? if($_GET['search_key'] == "mb_id"){ echo "selected";}?>>아이디</option>
              <option value="dr_name" <? if($_GET['search_key'] == "dr_name"){ echo "selected";}?>>의료진</option>
            </select>
            </span>
            <span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="17" /></span>
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
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />							
							<col />
							<col />
							<col style="width:150px;"/>
						</colgroup>
						<thead>
							<tr>
								<th>No</th>
								<th>아이디</th>
								<th>성명</th>
								<th>진료센터</th>
								<th>의료진</th>
								<th>예약일자</th>
								<th>예약시간</th>								
								<th>상태</th>
								<th>등록일자</th>
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
									$idx 			= $data_list[$i]['idx'];
									$MemID	 			= $data_list[$i]['MemID'];
									$MemName			= $data_list[$i]['MemName'];									
									$gr_name			= $data_list[$i]['gr_name'];									
									$doctorName 	= $data_list[$i]['doctorName'];									
									$selectConsultation	= $data_list[$i]['selectConsultation'];									
									$Possible_Date	= $data_list[$i]['Possible_Date'];									
									$Possible_Time	= $data_list[$i]['Possible_Time'];									
									$Reservation_Status			= $data_list[$i]['Reservation_Status'];																		
									$aDate		= date("Y.m.d", strtotime($data_list[$i]['aDate']));									
																	
									$edit_btn = $C_Button -> getButtonDesign('type2','보기',0,"layerPop('ifm_reg','./reserve_edit_old.php?idx=" . $idx. "','100%', 580)", 50,'');	
									$edit_btn .= "" . $C_Button -> getButtonDesign('type2','취소',0,"reserve_delete('" . $idx. "')", 50,'');							
								?>
										<tr>
											<td><?=$data['page_info']['start_num']--?></td>
											<td><?=$MemID?></td>
											<td><?=$MemName?></td>
											<td><?=$gr_name?></td>
											<td><?=$doctorName;?><?=($selectConsultation == 1) ? "[선택진료]":"";?></td>
											<td><?=$Possible_Date;?></td>
											<td><?=$Possible_Time;?></td>											
											<td><?=($Reservation_Status == 2) ? "예약완료" : "예약대기중";?></td>
											<td><?=$aDate;?></td>
											<td class="alignCenter"><?=$edit_btn?></td>
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
		
		<? if($_GET['Area'] !='') {?>
		$.ajax({
			type: "POST",
			url: "reserve_center_old.php",
			data: "Area=<?=$_GET['Area']?>&idx_of_Group=<?=$_GET['idx_of_Group']?>",
			dataType: "text",
			success: function(data) {
				$('#idx_of_Group').empty();
				$('#idx_of_Group').append('<option value="">::: 선택 :::</option>');
				$('#idx_of_Group').append(data);					
			},
			error: function(xhr, status, error) { alert(error); }
		});	
		<? } ?>
		
		<? if($_GET['idx_of_Group'] !='') {?>
		$.ajax({
			type: "POST",
			url: "reserve_doctor_old.php",
			data: "idx_of_Group=<?=$_GET['idx_of_Group']?>&idx_of_Doctor=<?=$_GET['idx_of_Doctor']?>",
			dataType: "text",
			success: function(data) {
				$('#idx_of_Doctor').empty();
				$('#idx_of_Doctor').append('<option value="">::: 선택 :::</option>');
				$('#idx_of_Doctor').append(data);					
			},
			error: function(xhr, status, error) { alert(error); }
		});	
		<? } ?>
		
		$('#Area').change(function(){
			var val = $(this).val();
			
			if(val == '') {
				return false;
			}
			
			$('#idx_of_Group').empty();
			$('#idx_of_Group').append('<option value="">::: 선택 :::</option>');
			
			$('#idx_of_Doctor').empty();
			$('#idx_of_Doctor').append('<option value="">::: 선택 :::</option>');
			
			$.ajax({
				type: "POST",
				url: "reserve_center_old.php",
				data: "Area=" + val,
				dataType: "text",
				success: function(data) {
					$('#idx_of_Group').empty();
					$('#idx_of_Group').append('<option value="">::: 선택 :::</option>');
					$('#idx_of_Group').append(data);					
				},
				error: function(xhr, status, error) { alert(error); }
			});
		});	
		
		$('#idx_of_Group').change(function(){
			var val = $(this).val();
			
			if(val == '') {
				return false;
			}
			
			$('#idx_of_Doctor').empty();
			$('#idx_of_Doctor').append('<option value="">::: 선택 :::</option>');
			
			$.ajax({
				type: "POST",
				url: "reserve_doctor_old.php",
				data: "idx_of_Group=" + val,
				dataType: "text",
				success: function(data) {
					$('#idx_of_Doctor').empty();
					$('#idx_of_Doctor').append('<option value="">::: 선택 :::</option>');
					$('#idx_of_Doctor').append(data);					
				},
				error: function(xhr, status, error) { alert(error); }
			});
		});	
		
		
	});




	function reserve_delete(idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/reserve_proc.php",
			data: "mode=RESERVE_DEL_OLD&idx=" + idx,
			dataType: "text",
			success: function(msg) {

				if($.trim(msg) == "true") {
					alert("취소 되었습니다");
					window.location.reload();
					return false;
				}else{
					alert('취소에 실패하였습니다.');
					return;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
	}
</script>