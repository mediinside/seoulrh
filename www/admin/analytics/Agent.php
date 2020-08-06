<?php
	include_once("../../_init.php");

	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.analytics.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Analytics 	= new Analytics;
	$C_Button 		= new Button;	
	
	
	//년월이 관련 변수 설정
	if (!$_GET['s_date']) {
		$s_date = date("Y-m-d");		
	}else{
		$s_date = $_GET['s_date'];		
	}
	if (!$_GET['e_date']) {
		$e_date = date("Y-m-d");		
	}else{
		$e_date = $_GET['e_date'];		
	}
	
	$excelHanArr = array(
		"Agent" => "s_Agent",
		"수량" => "cnt",
	);
	
	$args = array();
	$args['s_date'] = $s_date;
	$args['e_date'] = $e_date;
	$args['excel_file']		= $_GET['excel_file'];
	$args['excel']				= $excelHanArr;
	$args['show_row'] = 20;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Analytics->Analytics_agent_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
	
	$excelDown_btn			= $C_Button -> getButtonDesign('type2','EXCEL',0, '', 50,'');	//엑셀출력버튼
	
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
				
					<strong class="tit">기간검색</strong>
					<span><input type="text" name="s_date" id="s_date" value="<?=$s_date?>" class="inputSearch" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$e_date?>" class="inputSearch" size="13" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
					<span><input type="button" id="excel_btn" value="EXCEL" /></span>	
										
			</ul>
			</form>
			</div>
		</div>	
	
		<div id="BoardHead" class="boxBoardHead">				
        <div class="boxMemberBoard">					 
						<table>            	
							<thead>
								<tr>
									<th scope="col" width="95%">Agent</th>
									<th scope="col">수량</th>
								</tr>
							</thead>
							<tbody>
								<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt; $i++) {
									$s_Agent 		= $data_list[$i]['s_Agent'];
									$cnt			= $data_list[$i]['cnt'];											
								?>
								<tr>
									<td style="text-align:left;"><?=$s_Agent?></td>
									<td><?=$cnt?></td>
								</tr>
								<?							
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
				location.href = "?excel_file=Agent통계" + "&" + string;
				return false;
		});

	});
</script>
</body>
</html>