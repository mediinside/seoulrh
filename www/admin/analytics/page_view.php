<?php
	include_once("../../_init.php");
	
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.analytics.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Analytics 	= new Analytics;
	$C_Button 		= new Button;	
	
	
	//년월이 관련 변수 설정
	$Year = date("Y");	
	$Month = date("m");	
	$Day = date("d");		
	
	
	if($_GET['s_date'] != '') {
		$s_date = $_GET['s_date'];
	}else{
		$s_date = date('Y-m-d');
	}
	
	if($_GET['e_date'] != '') {
		$e_date = $_GET['e_date'];
	}else{
		$e_date = date('Y-m-d');
	}
		
	
	$excelHanArr = array(
		"접속페이지" => "s_StatusURL",
		"접속수" => "cnt"
	);
		
	
	$args = array();
	$args['s_date'] = $s_date;
	$args['e_date'] = $e_date;
	$args['excel_file']		= $_GET['excel_file'];
	$args['excel']				= $excelHanArr;
	$args['show_row'] = 15;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Analytics->Analytics_PageView_List(array_merge($_GET,$_POST,$args));
	
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
			<? include_once($GP -> INC_ADM_PATH."/inc.mem_search.php"); ?>										
			<form name="base_form" id="base_form" method="GET">
			<ul>				
				<li>
					<strong class="tit">검색일자</strong>
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
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
							<col width="120px"></col>     
							<col width="120px"></col>    
							<col width="120px"></col>    
							<col width="*"></col>  
							</colgroup>
							<thead>
								<tr>
									<th>접속페이지</th>
									<th>접속수</th>
									<th>접속비율</th>
									<th>그래프</th>
								</tr>
							</thead>
							<tbody>
								<?
										$dummy = 1;
										for ($i = 0 ; $i < $data_list_cnt ; $i++) {
											$s_StatusURL 		= $data_list[$i]['s_StatusURL'];
											$StatusCnt 		= $data_list[$i]['cnt'];								
								?>
								<tr>
									<td><a href="<?=$s_StatusURL?>" target="_blank"><?=$s_StatusURL?></a></td>
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
				location.href = "?excel_file=월별통계" + "&" + string;
				return false;
		});

	});
</script>
</body>
</html>