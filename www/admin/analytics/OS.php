<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
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
		
	
	$args = array();
	$args['s_date'] = $s_date;
	$args['e_date'] = $e_date;
	$args['show_row'] = 15;
	$args['pagetype'] = "admin";
	$data = "";	
	$data = $C_Analytics->Analytics_OS_List(array_merge($_GET,$_POST,$args));
	$data1 = $C_Analytics->Analytics_BW_List(array_merge($_GET,$_POST,$args));
	$data3 = $C_Analytics->Analytics_Bot_List(array_merge($_GET,$_POST,$args));
	$data4 = $C_Analytics->Analytics_Engine_List(array_merge($_GET,$_POST,$args));	
	
	
	$arr_tmp = $C_Analytics->Analytics_Total($args);
	
	//echo $_SERVER['HTTP_USER_AGENT'];
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
					<span><input type="text" name="s_date" id="s_date" value="<?=$s_date?>" class="input_text" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$e_date?>" class="input_text" size="13" /></span>
          <span><button id="search_submit" class="btnSearch ">검색</button></span>
           <span><input type="button" id="excel_btn" value="EXCEL" /></span>	
				   		
			</ul>
			</form>
			</div>
		</div>
		<div id="BoardHead" class="boxBoardHead">				
				<div class="boxMemberBoard" style="display:inline-block; vertical-align:top;">					 
						<table style="width:200px;">            	
							<thead>
								<tr>
									<th scope="col">운영체제</th>
									<th scope="col">수량</th>
								</tr>
							</thead>
							<tbody>
								<?
								$dummy = 1;
								for ($i = 0 ; $i < count($data); $i++) {
									$s_OS 		= $data[$i]['s_OS'];
									$cnt			= $data[$i]['cnt'];											
								?>
								<tr>
									<td><?=$s_OS?></td>
									<td><?=$cnt?></td>
								</tr>
								<?							
										}
								?>
							</tbody>
						</table>
				</div>			
        
        <div class="boxMemberBoard" style="display:inline-block; vertical-align:top;">					 
						<table style="width:200px;">            	
							<thead>
								<tr>
									<th scope="col">브라우저</th>
									<th scope="col">수량</th>
								</tr>
							</thead>
							<tbody>
								<?
								$dummy = 1;
								for ($i = 0 ; $i < count($data1); $i++) {
									$s_Browser 		= $data1[$i]['s_Browser'];
									$cnt			= $data1[$i]['cnt'];											
								?>
								<tr>
									<td><?=$s_Browser?></td>
									<td><?=$cnt?></td>
								</tr>
								<?							
										}
								?>
							</tbody>
						</table>
				</div>			
        
        <div class="boxMemberBoard" style="display:inline-block; vertical-align:top;">					 
						<table style="width:200px;">            	
							<thead>
								<tr>
									<th scope="col">검색로봇</th>
									<th scope="col">수량</th>
								</tr>
							</thead>
							<tbody>
								<?
								$dummy = 1;
								for ($i = 0 ; $i < count($data3); $i++) {
									$s_SearchBot 		= $data3[$i]['s_SearchBot'];
									$cnt			= $data3[$i]['cnt'];											
								?>
								<tr>
									<td><?=$s_SearchBot?></td>
									<td><?=$cnt?></td>
								</tr>
								<?							
										}
								?>
							</tbody>
						</table>
				</div>			
        
        <div class="boxMemberBoard" style="display:inline-block; vertical-align:top;">					 
						<table style="width:200px;">            	
							<thead>
								<tr>
									<th scope="col">검색엔진</th>
									<th scope="col">수량</th>
								</tr>
							</thead>
							<tbody>
								<?
								$dummy = 1;
								for ($i = 0 ; $i < count($data4); $i++) {
									$s_SearchEngine 		= $data4[$i]['s_SearchEngine'];
									$cnt								= $data4[$i]['cnt'];											
								?>
								<tr>
									<td><?=$s_SearchEngine?></td>
									<td><?=$cnt?></td>
								</tr>
								<?							
										}
								?>
							</tbody>
						</table>
				</div>			
        
			</div>
		
		</div>
		<? include_once($GP -> INC_ADM_PATH."/footer.php"); ?>
	</div>
</div><!-- 전체를 감싸는 Wrap //-->
<script type="text/javascript">

	$(document).ready(function(){	
		
		//엑셀 출력
		$('#excel_btn').click(function(){
				var string = $("#base_form").serialize();
				location.href = "os_excel.php?excel_file=일별통계" + "&" + string;
				return false;
		});

	});
</script>
</body>
</html>