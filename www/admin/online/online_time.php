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
    	<div class="layerTable">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<th width="15%">휴무일</th>
							<td width="85%">
								<label><input type="checkbox" /> 일</label>
								<label><input type="checkbox" /> 월</label>
								<label><input type="checkbox" /> 화</label>
								<label><input type="checkbox" /> 수</label>
								<label><input type="checkbox" /> 목</label>
								<label><input type="checkbox" /> 금</label>
								<label><input type="checkbox" /> 토</label>
							</td>
						</tr> 
						<tr>
							<th>진료시간설정</th>
							<td class="week">
								<p>
									<span class="cell">
										<span class="day">월</span>
										예약간격
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										적용시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										제외시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
								</p>
								<p>
									<span class="cell">
										<span class="day">화</span>
										예약간격
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										적용시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										제외시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
								</p>
								<p>
									<span class="cell">
										<span class="day">수</span>
										예약간격
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										적용시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										제외시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
								</p>
								<p>
									<span class="cell">
										<span class="day">목</span>
										예약간격
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										적용시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										제외시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
								</p>
								<p>
									<span class="cell">
										<span class="day">금</span>
										예약간격
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										적용시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										제외시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
								</p>
								<p>
									<span class="cell">
										<span class="day">토</span>
										예약간격
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										적용시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
									<span class="cell">
										제외시간
										<select>
											<option>선택하기</option>
										</select> ~ 
										<select>
											<option>선택하기</option>
										</select>
									</span>
								</p>
							</td>
						</tr> 
					</tbody>
				</table>
			</div>				
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