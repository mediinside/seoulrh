<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP -> CLS."/class.doctor.php");	
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Doctor 	= new Doctor;
	$C_Button 		= new Button;
	
	//error_reporting(E_ALL);
	//@ini_set("display_errors", 1);
	
	$args = array();
	$args['show_row'] = 50;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Doctor->Doctor_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
	
	$cn_select = $C_Func -> makeSelect_Normal('dr_clinic', $GP -> CLINIC_TYPE, $dr_clinic, '', '::선택::');	
	
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
					<strong class="tit">진료과목</strong>
					<span><?=$cn_select?></span>
				</li>				
				<li>
					<strong class="tit">검색조건</strong>
					<span>
					<select name="search_key" id="search_key">
						<option value="">:: 선택 ::</option>
						<option value="dr_name" <? if($_GET['search_key'] == "mb_name"){ echo "selected";}?> >이름</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="16" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>		
		<div class="btnWrap">
			<p class="txtLeft">순번 변경시 해당 행을 드래그하여 원하시는 위치에 놓으시면 됩니다.</p>
			<button onClick="layerPop('ifm_reg','./dr_reg.php', '100%', 600)"; class="btnSearch btnRight">의료진 등록</button>
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
							<col style="width:101px;" />
						</colgroup>
						<thead>
							<tr>
								<th>No</th>								
								<th>이름</th>								
								<th>사진</th>
								<th>진료시간표</th>	
								<th>진료센터</th>								
								<th>진료과목</th>								
								<th>직책</th>
								<th>메인노출</th>
								<th>등록일</th>																
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<input type="hidden" name="max_desc" id="max_desc" value="<?=$data_list[0]['dr_desc']?>" />
							<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
									$dr_idx 			= $data_list[$i]['dr_idx'];
									$dr_name			= $data_list[$i]['dr_name'];
									$dr_clinic			= $data_list[$i]['dr_clinic'];
									$dr_center		= $data_list[$i]['dr_center'];
									$dr_position		= $data_list[$i]['dr_position'];
									$dr_history		= $data_list[$i]['dr_history'];
									$dr_face_img		= $data_list[$i]['dr_face_img'];	
									$dr_list_img		= $data_list[$i]['dr_list_img'];	
									$dr_regdate 		= $data_list[$i]['dr_regdate'];		
									$dr_desc 			= $data_list[$i]['dr_desc'];	
									$dr_main_view 	= $data_list[$i]['dr_main_view'];	
									
									$cn_arr = explode(",", $dr_position);	
									
									$dr_img = '';
									if($dr_face_img !=  '') {
										$dr_img = "<img src='" . $GP -> UP_DOCTOR_URL . $dr_face_img . "' width='100px' />";
									}

									$dr_img1 = '';
									if($dr_list_img !=  '') {
										$dr_img1 = "<img src='" . $GP -> UP_DOCTOR_URL . $dr_list_img . "' width='100px' />";
									}
									
									$book_btn = $C_Button -> getButtonDesign('type2','관리',0,"layerPop('ifm_reg','./book_list.php?dr_idx=" . $dr_idx. "', '100%', 600)", 50,'');									
									$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./dr_edit.php?dr_idx=" . $dr_idx. "', '100%', 600)", 50,'');	
									$edit_btn .= $C_Button -> getButtonDesign('type2','삭제',0,"dr_delete('" . $dr_idx. "')", 50,'');									
								?>
										<tr id="<?=$dr_idx?>">
											<td><?=$data['page_info']['start_num']--?></td>											
											<td><?=$dr_name?></td>
											<td><?=$dr_img?></td>
											<td><?=$dr_img1?></td>
											<td><?=$GP -> CENTER_TYPE[$dr_center]?></td>
											<td><?=$GP -> CLINIC_TYPE[$dr_clinic]?></td>
											<td>
											<?
												$str = "";
												foreach ($cn_arr as $key => $val) {
													$str .= $GP -> DOCTOR_POSITION[$val] . ",";
												}
												echo rtrim($str , ",");
											?>
											</td>
											<td><?=($dr_main_view == "Y") ? "노출":"비노출";?></td>
											<td><?=$dr_regdate?></td>											
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
														 
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		};

		var cl_id = "";
		var ch_id = "";
		$(".boxMemberBoard tbody").sortable({
			helper: fixHelper,
			start: function( event, ui ) {
				cl_id = ui.item.attr('id');
			},	
			stop: function( event, ui ) {
				/*
				var tot_num = ui.item.parent().find('tr').length - 1;
				var now_num = ui.item.index();				
				
				if(now_num == tot_num) {
					fd_num = now_num - 1;					
					ch_id = ui.item.parent().find("tr:eq(" + fd_num + ")").attr('id');					
				}else {
					fd_num = now_num + 1;					
					ch_id = ui.item.parent().find("tr:eq(" + fd_num + ")").attr('id');					
				}
				*/
				
				var tot_num = ui.item.parent().find('tr').length;
				var tmp_id = "";
				for(i=0;  i< tot_num; i++){
					var val = ui.item.parent().find("tr:eq(" + i + ")").attr('id');
					tmp_id += val + ",";
				 }
				 tmp_id = tmp_id.slice(0,-1);

				 var max_desc = $('#max_desc').val();
				 console.log(tmp_id);
				 console.log(max_desc);
				
				console.log("mode=DT_AUTO_CH&tmp_id=" + tmp_id + "&max_desc=" + max_desc);
				$.ajax({
					type: "POST",
					url: "./proc/dt_proc.php",
					data: "mode=DT_AUTO_CH&tmp_id=" + tmp_id + "&max_desc=" + max_desc,
					dataType: "text",
					success: function(msg) {
		
						if($.trim(msg) == "true") {
						//	alert("변경되었습니다.");
						//	window.location.reload();
							return false;
						}else{
							alert('변경에 실패하였습니다.');
							return;
						}
					},
					error: function(xhr, status, error) { alert(error); }
				});
				
			},	
			
			
		}).disableSelection();
	
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
	
	function desc_idx(type, idx, num) {
		
		$.ajax({
			type: "POST",
			url: "./proc/dt_proc.php",
			data: "mode=DT_DESC&type=" + type + "&dr_idx=" + idx+ "&desc=" + num,
			dataType: "text",
			success: function(msg) {

				if($.trim(msg) == "true") {
					alert("변경되었습니다.");
					window.location.reload();
					return false;
				}else{
					alert('변경에 실패하였습니다.');
					return;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
	}

	function dr_delete(dr_idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/dt_proc.php",
			data: "mode=DOCTOR_DEL&dr_idx=" + dr_idx,
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