<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP -> CLS."/class.reserve.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Reserve 	= new Reserve;
	$C_Button 		= new Button;
	
	$args = array();
	$args['show_row'] = 15;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Reserve->Mem_Reseve_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
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
					<span style="padding-right:42px; line-height:24px;">등록일</span>
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="20"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="20" /></span>
				</li>			
				<li>
					<span style="padding-right:31px;">검색조건</span>
					<span>
					<select name="search_key" id="search_key">
						<option value="">:: 선택 ::</option>
						<option value="mb_name" <? if($_GET['search_key'] == "mb_name"){ echo "selected";}?> >성명</option>
						<option value="mb_email" <? if($_GET['search_key'] == "mb_email"){ echo "selected";}?>>이메일</option>
						<option value="mb_mobile" <? if($_GET['search_key'] == "mb_mobile"){ echo "selected";}?>>핸드폰</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="30" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>		
		<div id="BoardHead" class="boxBoardHead">	
				<ul>
					<li><a href="#MemberBoard1">예약이력</a></li
					>
				</ul>			
				<div class="boxMemberBoard">
					<table>
						<thead>
							<tr>
								<th>No</th>
								<td width="1">|</td>
								<th>이메일</th>
								<td width="1">|</td>
								<th>성명</th>
								<td width="1">|</td>
								<th>핸드폰</th>
								<td width="1">|</td>
								<th>상품</th>
								<td width="1">|</td>
								<th>예약일자</th>	
								<td width="1">|</td>
								<th>타입</th>
								<td width="1">|</td>
								<th>상태</th>
								<td width="1">|</td>								
								<th>보기</th>						
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
									$mb_code 			= $data_list[$i]['mb_code'];
									$mb_name			= $data_list[$i]['mb_name'];
									$mb_email 		= $data_list[$i]['mb_email'];
									$mb_sex 			= $data_list[$i]['mb_sex'];
									$mb_birthday	= $data_list[$i]['mb_birthday'];
									$mb_mobile 		= $data_list[$i]['mb_mobile'];	
									$mb_register_date 		= $data_list[$i]['mb_register_date'];		
									$mr_mobile		= $data_list[$i]['mr_mobile'];
									$mr_email 		= $data_list[$i]['mr_email'];	
									$mr_memo			= $data_list[$i]['mr_memo'];
									$mr_name			= $data_list[$i]['mr_name'];
									$mr_product 	= $data_list[$i]['mr_product'];	
									$mr_s_time 		= date("Y-m-d H:i ", strtotime($data_list[$i]['mr_s_time']));
									$mr_e_time 		= date("Y-m-d H:i ", strtotime($data_list[$i]['mr_e_time']));	
									$mr_Color 		= $data_list[$i]['mr_Color'];									
									$mb_status 		= $data_list[$i]['mb_status'];									
									
									$year 		= date("Y", strtotime($data_list[$i]['mr_s_time']));
									$month 		= date("m", strtotime($data_list[$i]['mr_s_time']));
									$day 		= date("d", strtotime($data_list[$i]['mr_s_time']));
									
									$email = '';
									if($mr_email != ''){
										$email = $mr_email;
									}else{
										$email = $mb_email;
									}
									
									$name = "";
									if($mr_name != ''){
										$name = $mr_name;
									}else{
										$name = $mb_name;
									}
									
									$mobile = "";
									if($mr_mobile != ''){
										$mobile = $mr_mobile;
									}else{
										$mobile = $mb_mobile;
									}
									
									$time = $mr_s_time . "~" . $mr_e_time;
									
									$edit_btn = $C_Button -> getButtonDesign('type2','보기',0,"reserv_go('$year','$month','$day')", 50,'');	
									
								?>
										<tr>
											<td><?=$data['page_info']['start_num']--?></td>
											<td></td>
											<td><?=$email?></td>
											<td></td>
											<td><?=$name?></td>
											<td></td>
											<td><?=$mobile?></td>
											<td></td>
											<td><?=$mr_product?></td>
											<td></td>
											<td><?=$time?></td>
											<td></td>
											<td><?=$GP -> QNA_USER_TYPE[$mb_status]?></td>
											<td></td>
											<td><?=$GP -> RS_REAL_RESULT[$mr_Color]?></td>
											<td></td>
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
	
	
	function reserv_go(y, m, d){
		location.href = "../reserve/reserve_list.php?y=" + y + "&m=" + m + "&d=" + d;	
	}
</script>