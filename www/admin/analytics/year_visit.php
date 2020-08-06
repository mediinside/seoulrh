<?php
	include_once("../../_init.php");

	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.analytics.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Analytics 	= new Analytics;
	$C_Button 		= new Button;	
	
	
	//년월이 관련 변수 설정
	if (!$_GET['Year'])
		$Year = date("Y");
	if (!$_GET['Month'])
		$Month = date("m");
	if (!$_GET['Day'])
		$Day = date("d");		
		
		
	$excelHanArr = array(
		"접속월" => "StatusMonth",
		"접속수" => "cnt"
	);
		
	
	$args = array();
	$args['Year'] = $Year;
	$args['Month'] = $Month;
	$args['Day'] = $Day;
	$args['excel_file']		= $_GET['excel_file'];
	$args['excel']				= $excelHanArr;
	$args['show_row'] = 5;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Analytics->Analytics_Year_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);	
	$data_list_cnt 	= count($data_list);	
	
	$arr_tmp = $C_Analytics->Analytics_Total($args);
	
	$totalCount = 0;
	for ($i = 0 ; $i < $data_list_cnt ; $i++) {	
		$totalCount = $totalCount + $data_list[$i]['cnt'];
	}
	
	
	
	include_once($GP -> INC_ADM_PATH."/head.php");
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<!--? include_once($GP -> INC_ADM_PATH."/inc.mem_search.php"); ?-->										
			<form name="base_form" id="base_form" method="GET">
			<ul>				
				
					<strong class="tit">날짜</strong>
					<span>
						<select name="Year" class="input" onChange="document.base_form.submit();">
						<?php
						$s_year = date('Y') - 5;
						$e_year = date('Y');												
						for ($i=$s_year;$i<=$e_year;$i++) {
							echo "<option value=".$i;
							if(!$Year) {
								if ($i == date("Y")) echo " selected";
							} else {
								if ($i == "$Year") echo " selected";
							}
							echo ">".$i."</option>";								
						}							
						?>
						</select>년																
					</span>
          <span><input type="button" id="excel_btn" value="EXCEL" /></span>	
										
			</ul>
			</form>
			</div>
		</div>	
	
		<div id="BoardHead" class="boxBoardHead">				
				<div class="boxMemberBoard">
					 <ul class="analysis_tot">
					 	<li>전체접속수 : <span><?=$arr_tmp[0];?></span></li>
						<li>월간접속수 : <span><?=$arr_tmp[1];?></span></li>
						<li>일간접속수 : <span><?=round($arr_tmp[1] / $data_list_cnt)?></span></li>
					 </ul>
						<table>
							<colgroup>
							<col width="120px"></col>     
							<col width="120px"></col>    
							<col width="120px"></col>    
							<col width="*"></col>  
							</colgroup>
							<thead>
								<tr>
									<th>접속월</th>
									<th>접속수</th>
									<th>접속비율</th>
									<th>그래프</th>
								</tr>
							</thead>
							<tbody>
								 <?
											$dummy = 1;
											for ($i = 0 ; $i < $data_list_cnt ; $i++) {
												$StatusMonth 		= $data_list[$i]['StatusMonth'];
												$StatusCnt 		= $data_list[$i]['cnt'];								
									?>
									<tr>
										<td><a href="month_visit.php?Year=<?=$Year;?>&Month=<?=$StatusMonth;?>"><?=$StatusMonth?></a></td>
										<td><?=$StatusCnt?></td>
										<td><?=substr((($StatusCnt / $totalCount) * 100),0,4);?>%</td>
										<td>
											<table border="0" height="10" cellpadding="0" cellspacing="0" width="<?=substr((($StatusCnt / $totalCount) * 100),0,3);?>%">
												<tr>
													<td bgcolor="#F1943A" height="10"></td>
												</tr>
											</table>
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
		<? include_once($GP -> INC_ADM_PATH."/footer.php"); ?>
	</div>
</div><!-- 전체를 감싸는 Wrap //-->
<script type="text/javascript">

	$(document).ready(function(){	
		
		//엑셀 출력
		$('#excel_btn').click(function(){
				var string = $("#base_form").serialize();
				location.href = "?excel_file=년별통계" + "&" + string;
				return false;
		});

	});
</script>
</body>
</html>